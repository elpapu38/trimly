<?php /* app/Views/shop_dash/services/index.php */ ?>
<div class="flex">
  <?php include __DIR__ . '/../partials/sidebar.php'; ?>
  <div class="ml-56 flex-1 p-6 min-h-screen bg-ink-900">

    <div class="flex items-center justify-between mb-6">
      <h1 class="font-display text-2xl text-cream-light">Servicios</h1>
      <a href="<?= path('/panel/servicios/nuevo') ?>" class="px-5 py-2.5 bg-gold text-ink-900 font-semibold rounded-xl hover:bg-gold-300 transition-colors text-sm">
        + Nuevo servicio
      </a>
    </div>

    <?php if (empty($grouped)): ?>
    <div class="text-center py-20 text-ink-400">
      <p class="text-lg mb-4">Todavía no cargaste servicios</p>
      <a href="<?= path('/panel/servicios/nuevo') ?>" class="inline-block px-6 py-3 bg-gold text-ink-900 font-semibold rounded-full">Agregar primero</a>
    </div>
    <?php else: ?>
    <?php foreach ($grouped as $cat => $items): ?>
    <div class="mb-6">
      <h2 class="text-xs font-semibold uppercase tracking-widest text-gold mb-3"><?= e($cat) ?></h2>
      <div class="bg-ink-800 border border-ink-700 rounded-xl overflow-hidden">
        <?php foreach ($items as $i => $svc): ?>
        <div class="flex items-center gap-4 px-4 py-3 <?= $i > 0 ? 'border-t border-ink-700' : '' ?> <?= !$svc['is_active'] ? 'opacity-50' : '' ?>">
          <?php if ($svc['image']): ?>
            <img src="<?= upload_url($svc['image']) ?>" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
          <?php else: ?>
            <div class="w-10 h-10 bg-ink-700 rounded-lg flex items-center justify-center text-xl flex-shrink-0">✂️</div>
          <?php endif; ?>
          <div class="flex-1 min-w-0">
            <p class="text-cream-light font-medium"><?= e($svc['name']) ?></p>
            <?php if ($svc['description']): ?>
              <p class="text-ink-400 text-xs truncate"><?= e($svc['description']) ?></p>
            <?php endif; ?>
          </div>
          <div class="text-right flex-shrink-0">
            <p class="text-gold font-semibold"><?= money((float)$svc['price']) ?></p>
            <p class="text-ink-400 text-xs"><?= duracionTexto((int)$svc['duration_min']) ?></p>
          </div>
          <div class="flex gap-2 flex-shrink-0 ml-2">
            <a href="<?= path('/panel/servicios/' . $svc['id']) ?>" class="px-3 py-1.5 border border-ink-600 text-ink-300 rounded-lg text-xs hover:border-gold/50">Editar</a>
            <form method="POST" action="<?= path('/panel/servicios/' . $svc['id'] . '/eliminar') ?>" onsubmit="return confirm('¿Desactivar este servicio?')">
              <input type="hidden" name="_csrf" value="<?= csrf() ?>">
              <button class="px-3 py-1.5 border border-red-500/30 text-red-400 rounded-lg text-xs hover:bg-red-500/10">Desactivar</button>
            </form>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>
