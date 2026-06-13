<?php /* app/Views/client/track.php */ ?>
<div class="min-h-screen bg-ink-900 py-16">
<div class="max-w-2xl mx-auto px-4">
  <h1 class="font-display text-3xl text-cream-light mb-2">Seguimiento de turnos</h1>
  <p class="text-ink-400 mb-8">Ingresá tu email para ver tus reservas sin necesidad de cuenta</p>

  <form method="POST" class="flex gap-3 mb-10">
    <input type="hidden" name="_csrf" value="<?= csrf() ?>">
    <input type="email" name="email" value="<?= e($email) ?>" required placeholder="tu@email.com"
           class="input-gold flex-1 bg-ink-800 border border-ink-600 rounded-xl px-4 py-3 text-cream-light">
    <button type="submit" class="px-6 py-3 bg-gold text-ink-900 font-semibold rounded-xl hover:bg-gold-300 transition-colors">
      Buscar
    </button>
  </form>

  <?php if (!empty($appointments)): ?>
  <div class="space-y-3">
    <?php foreach ($appointments as $a):
      $config = require BASE_PATH.'/config/app.php';
      $sc = $config['appointment_statuses'][$a['status']] ?? ['label'=>$a['status'],'color'=>'gray'];
    ?>
    <div class="p-4 bg-ink-800 border border-ink-600 rounded-xl">
      <div class="flex items-start justify-between mb-2">
        <div>
          <p class="text-cream-light font-medium"><?= e($a['shop_name']) ?></p>
          <p class="text-ink-400 text-sm"><?= e($a['service_name']) ?> con <?= e($a['employee_name']) ?></p>
        </div>
        <span class="px-2 py-1 rounded-full text-xs bg-<?= $sc['color'] ?>-500/20 text-<?= $sc['color'] ?>-400">
          <?= $sc['label'] ?>
        </span>
      </div>
      <div class="flex items-center justify-between text-sm">
        <p class="text-ink-300"><?= fecha($a['date'], 'd/m/Y') ?> · <?= hora($a['start_time']) ?> hs</p>
        <p class="text-gold font-medium"><?= money((float)$a['price']) ?></p>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php elseif ($email): ?>
  <div class="text-center py-12 text-ink-400">
    <p>No encontramos turnos para <strong class="text-cream-light"><?= e($email) ?></strong></p>
  </div>
  <?php endif; ?>
</div>
</div>
