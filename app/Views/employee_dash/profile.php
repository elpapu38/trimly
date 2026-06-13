<?php /* app/Views/employee_dash/profile.php */
$errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_errors']);
?>
<div class="flex min-h-screen pt-16 pb-20 lg:pb-6">
  <?php require __DIR__ . '/partials/sidebar.php'; ?>
  <main class="flex-1 p-4 sm:p-6 lg:p-8 lg:ml-56 max-w-2xl">

    <h1 class="font-display text-2xl font-bold text-cream-light mb-6">Mi perfil</h1>

    <?php foreach ($this->getFlash() as $type => $msgs): ?>
      <?php foreach ($msgs as $m): ?>
      <div class="mb-4 px-4 py-3 rounded-xl text-sm <?= $type==='success' ? 'bg-emerald-500/10 border border-emerald-500/30 text-emerald-400' : 'bg-red-500/10 border border-red-500/30 text-red-400' ?>">
        <?= e($m) ?>
      </div>
      <?php endforeach; ?>
    <?php endforeach; ?>

    <form method="POST" action="<?= path('/mi-panel/perfil') ?>" enctype="multipart/form-data"
          class="bg-ink-800 border border-ink-700 rounded-2xl p-5 sm:p-6 space-y-5">
      <input type="hidden" name="_csrf" value="<?= csrf() ?>">

      <!-- Avatar -->
      <div class="flex items-center gap-4">
        <?php if ($employee['avatar']): ?>
          <img src="<?= upload_url($employee['avatar']) ?>" alt="" class="w-20 h-20 rounded-full object-cover border-2 border-ink-600">
        <?php else: ?>
          <div class="w-20 h-20 rounded-full bg-ink-700 border-2 border-ink-600 flex items-center justify-center text-3xl text-ink-400">
            <?= mb_strtoupper(mb_substr($employee['name'],0,1)) ?>
          </div>
        <?php endif; ?>
        <div>
          <label class="block text-xs text-ink-400 mb-1">Foto de perfil</label>
          <input type="file" name="avatar" accept="image/*"
                 class="text-xs text-ink-300 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-ink-700 file:text-cream-light hover:file:bg-ink-600 cursor-pointer">
          <p class="text-xs text-ink-500 mt-1">JPG, PNG o WebP · máx. 2 MB</p>
        </div>
      </div>

      <div>
        <label class="block text-xs text-ink-400 mb-1">Nombre *</label>
        <input type="text" name="name" value="<?= e($employee['name']) ?>" required
               class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
        <?php if (!empty($errors['name'])): ?>
          <p class="text-red-400 text-xs mt-1"><?= e($errors['name'][0]) ?></p>
        <?php endif; ?>
      </div>

      <div>
        <label class="block text-xs text-ink-400 mb-1">Especialidad</label>
        <input type="text" name="specialty" value="<?= e($employee['specialty'] ?? '') ?>"
               placeholder="Ej: Colorista, Barbero"
               class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
      </div>

      <div>
        <label class="block text-xs text-ink-400 mb-1">Instagram</label>
        <div class="relative">
          <span class="absolute left-4 top-1/2 -translate-y-1/2 text-ink-500 text-sm">@</span>
          <input type="text" name="instagram" value="<?= e($employee['instagram'] ?? '') ?>"
                 placeholder="usuario"
                 class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl pl-8 pr-4 py-2.5 text-sm text-cream-light focus:outline-none">
        </div>
      </div>

      <div>
        <label class="block text-xs text-ink-400 mb-1">Bio</label>
        <textarea name="bio" rows="3" placeholder="Contá un poco sobre vos…"
                  class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light resize-none focus:outline-none"><?= e($employee['bio'] ?? '') ?></textarea>
      </div>

      <button type="submit" class="w-full bg-gold hover:bg-gold-500 text-ink-900 font-bold py-3 rounded-xl text-sm transition-colors">
        Guardar cambios
      </button>
    </form>
  </main>
</div>
