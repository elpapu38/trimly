<?php /* app/Views/booking/step1.php */ ?>
<div class="min-h-screen bg-ink-900 py-10">
<div class="max-w-3xl mx-auto px-4">

  <!-- Breadcrumb del flujo -->
  <div class="flex items-center gap-2 mb-8 text-sm">
    <a href="<?= path('/local/' . e($shop['slug'])) ?>" class="text-gold hover:underline"><?= e($shop['name']) ?></a>
    <span class="text-ink-400">/</span>
    <span class="text-cream-light">Reservar turno</span>
  </div>

  <!-- Pasos -->
  <?php include __DIR__ . '/partials/steps.php'; ?>

  <!-- Cabecera del local -->
  <div class="flex items-center gap-4 mb-8 p-4 bg-ink-800 rounded-xl border border-ink-600">
    <?php if ($shop['logo']): ?>
      <img src="<?= upload_url($shop['logo']) ?>" alt="<?= e($shop['name']) ?>" class="w-14 h-14 rounded-full object-cover">
    <?php else: ?>
      <div class="w-14 h-14 rounded-full bg-gold/20 flex items-center justify-center text-gold text-xl font-bold"><?= initials($shop['name']) ?></div>
    <?php endif; ?>
    <div>
      <h2 class="font-display text-xl text-cream-light"><?= e($shop['name']) ?></h2>
      <p class="text-ink-300 text-sm"><?= e($shop['city']) ?> · <?= e($shop['address']) ?></p>
    </div>
  </div>

  <h1 class="font-display text-2xl text-cream-light mb-6">¿Qué servicio buscás?</h1>

  <?php if (empty($services)): ?>
    <div class="text-center py-16 text-ink-400">
      <p class="text-lg">Este local aún no tiene servicios publicados.</p>
      <a href="<?= path('/local/' . e($shop['slug'])) ?>" class="mt-4 inline-block text-gold hover:underline">← Volver al perfil</a>
    </div>
  <?php else: ?>

  <form method="POST" action="<?= path('/reservar/' . e($shop['slug']) . '/servicio') ?>">
    <input type="hidden" name="_csrf" value="<?= csrf() ?>">

    <?php foreach ($services as $category => $items): ?>
    <div class="mb-6">
      <h3 class="text-xs font-semibold uppercase tracking-widest text-gold mb-3"><?= e($category) ?></h3>
      <div class="space-y-2">
        <?php foreach ($items as $service): ?>
        <label class="flex items-center gap-4 p-4 bg-ink-800 border border-ink-600 rounded-xl cursor-pointer hover:border-gold/50 transition-all peer-checked:border-gold has-[:checked]:border-gold has-[:checked]:bg-gold/5">
          <input type="radio" name="service_id" value="<?= $service['id'] ?>"
                 <?= ($booking['service_id'] ?? 0) == $service['id'] ? 'checked' : '' ?>
                 class="sr-only peer" required>
          <div class="w-5 h-5 rounded-full border-2 border-ink-500 peer-checked:border-gold peer-checked:bg-gold flex-shrink-0 flex items-center justify-center transition-all">
            <div class="w-2.5 h-2.5 rounded-full bg-gold opacity-0 peer-checked:opacity-100 transition-opacity"></div>
          </div>
          <?php if ($service['image']): ?>
            <img src="<?= upload_url($service['image']) ?>" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
          <?php endif; ?>
          <div class="flex-1 min-w-0">
            <p class="font-medium text-cream-light"><?= e($service['name']) ?></p>
            <?php if ($service['description']): ?>
              <p class="text-sm text-ink-300 truncate"><?= e($service['description']) ?></p>
            <?php endif; ?>
          </div>
          <div class="text-right flex-shrink-0">
            <p class="text-gold font-semibold"><?= money((float)$service['price']) ?></p>
            <p class="text-xs text-ink-400"><?= duracionTexto((int)$service['duration_min']) ?></p>
          </div>
        </label>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>

    <div class="flex justify-end mt-8">
      <button type="submit" class="px-8 py-3 bg-gold text-ink-900 font-semibold rounded-full hover:bg-gold-300 transition-colors">
        Continuar →
      </button>
    </div>
  </form>
  <?php endif; ?>

</div>
</div>

<style>
label:has(input[type=radio]:checked) .w-2\.5 { opacity:1; }
label:has(input[type=radio]:checked) .w-5 { border-color:#C9A84C; }
</style>
