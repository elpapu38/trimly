<?php /* app/Views/shop_dash/services/form.php */
$isEdit = !empty($service['id']);
$errors = $_SESSION['form_errors'] ?? [];
$old    = $_SESSION['form_old']    ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_old']);
$action = $isEdit ? "/panel/servicios/{$service['id']}" : '/panel/servicios/nuevo';
?>
<div class="flex">
  <?php include __DIR__ . '/../partials/sidebar.php'; ?>
  <div class="ml-56 flex-1 p-6 min-h-screen bg-ink-900 max-w-2xl">

    <div class="flex items-center gap-3 mb-6">
      <a href="<?= path('/panel/servicios') ?>" class="text-ink-400 hover:text-gold">← Volver</a>
      <h1 class="font-display text-2xl text-cream-light"><?= $isEdit ? 'Editar — '.e($service['name']) : 'Nuevo servicio' ?></h1>
    </div>

    <form method="POST" action="<?= $action ?>" enctype="multipart/form-data">
      <input type="hidden" name="_csrf" value="<?= csrf() ?>">

      <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5 space-y-4">

        <div class="grid sm:grid-cols-2 gap-4">
          <div class="sm:col-span-2">
            <label class="block text-sm text-ink-300 mb-1">Nombre del servicio *</label>
            <input type="text" name="name" required value="<?= e($old['name'] ?? $service['name'] ?? '') ?>"
                   class="input-gold w-full bg-ink-700 border <?= isset($errors['name']) ? 'border-red-500' : 'border-ink-600' ?> rounded-xl px-4 py-3 text-cream-light">
          </div>

          <div class="sm:col-span-2">
            <label class="block text-sm text-ink-300 mb-1">Descripción</label>
            <textarea name="description" rows="2"
                      class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-3 text-cream-light resize-none"><?= e($old['description'] ?? $service['description'] ?? '') ?></textarea>
          </div>

          <div>
            <label class="block text-sm text-ink-300 mb-1">Precio (ARS) *</label>
            <input type="number" name="price" min="0" step="50" required
                   value="<?= e($old['price'] ?? $service['price'] ?? '') ?>"
                   class="input-gold w-full bg-ink-700 border <?= isset($errors['price']) ? 'border-red-500' : 'border-ink-600' ?> rounded-xl px-4 py-3 text-cream-light">
          </div>

          <div>
            <label class="block text-sm text-ink-300 mb-1">Duración (minutos) *</label>
            <select name="duration_min" required
                    class="input-gold w-full bg-ink-700 border <?= isset($errors['duration_min']) ? 'border-red-500' : 'border-ink-600' ?> rounded-xl px-4 py-3 text-cream-light">
              <?php foreach ([15,20,30,45,60,75,90,120] as $min): ?>
              <option value="<?= $min ?>" <?= ($old['duration_min'] ?? $service['duration_min'] ?? 30) == $min ? 'selected' : '' ?>><?= duracionTexto($min) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div>
            <label class="block text-sm text-ink-300 mb-1">Categoría</label>
            <select name="category_id" id="catSelect"
                    class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-3 text-cream-light"
                    onchange="toggleNewCat(this)">
              <option value="">Sin categoría</option>
              <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>" <?= ($service['category_id'] ?? 0) == $cat['id'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
              <?php endforeach; ?>
              <option value="new">+ Nueva categoría</option>
            </select>
          </div>

          <div id="newCatField" class="hidden">
            <label class="block text-sm text-ink-300 mb-1">Nombre de nueva categoría</label>
            <input type="text" name="new_category" placeholder="Ej: Coloración"
                   class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-3 text-cream-light">
          </div>

          <div>
            <label class="block text-sm text-ink-300 mb-1">% Seña online (0 = sin seña)</label>
            <input type="number" name="deposit_pct" min="0" max="100"
                   value="<?= e($old['deposit_pct'] ?? $service['deposit_pct'] ?? 0) ?>"
                   class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-3 text-cream-light">
          </div>

          <div>
            <label class="block text-sm text-ink-300 mb-1">Orden</label>
            <input type="number" name="sort_order" min="0"
                   value="<?= e($old['sort_order'] ?? $service['sort_order'] ?? 0) ?>"
                   class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-3 text-cream-light">
          </div>

          <?php if ($isEdit): ?>
          <div class="flex items-center gap-3">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" name="is_active" value="1"
                     <?= ($service['is_active'] ?? 1) ? 'checked' : '' ?>
                     class="w-4 h-4 accent-gold">
              <span class="text-sm text-cream-light">Servicio activo</span>
            </label>
          </div>
          <?php endif; ?>

          <div class="sm:col-span-2">
            <label class="block text-sm text-ink-300 mb-1">Imagen del servicio</label>
            <?php if (!empty($service['image'])): ?>
              <img src="<?= upload_url($service['image']) ?>" class="w-24 h-24 rounded-xl object-cover mb-2">
            <?php endif; ?>
            <input type="file" name="image" accept="image/*"
                   class="w-full text-ink-300 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-gold/20 file:text-gold hover:file:bg-gold/30">
          </div>
        </div>

        <div class="flex gap-3 pt-2">
          <a href="<?= path('/panel/servicios') ?>" class="px-6 py-3 border border-ink-600 text-ink-300 rounded-xl hover:border-gold/50 transition-colors">Cancelar</a>
          <button type="submit" class="px-8 py-3 bg-gold text-ink-900 font-semibold rounded-xl hover:bg-gold-300 transition-colors">
            <?= $isEdit ? 'Guardar cambios' : 'Crear servicio' ?>
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
function toggleNewCat(sel) {
  document.getElementById('newCatField').classList.toggle('hidden', sel.value !== 'new');
}
</script>
