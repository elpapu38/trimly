<?php /* app/Views/employee_dash/index.php */ ?>
<div class="flex min-h-screen pt-16 pb-20 lg:pb-6">
  <?php require __DIR__ . '/partials/sidebar.php'; ?>
  <main class="flex-1 p-4 sm:p-6 lg:p-8 lg:ml-56 overflow-x-hidden">

    <h1 class="font-display text-2xl font-bold text-cream-light mb-6">
      Hola, <?= e(explode(' ', $employee['name'])[0]) ?> 👋
    </h1>

    <!-- Stats del mes -->
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-8">
      <div class="bg-ink-800 border border-ink-700 rounded-2xl p-4">
        <p class="text-xs text-ink-400 mb-1">Turnos este mes</p>
        <p class="font-display text-2xl font-bold text-cream-light"><?= (int)($monthStats['total'] ?? 0) ?></p>
      </div>
      <div class="bg-ink-800 border border-ink-700 rounded-2xl p-4">
        <p class="text-xs text-ink-400 mb-1">Completados</p>
        <p class="font-display text-2xl font-bold text-emerald-400"><?= (int)($monthStats['completed'] ?? 0) ?></p>
      </div>
      <div class="bg-ink-800 border border-ink-700 rounded-2xl p-4 col-span-2 sm:col-span-1">
        <p class="text-xs text-ink-400 mb-1">Facturado</p>
        <p class="font-display text-2xl font-bold text-gold"><?= money((float)($monthStats['revenue'] ?? 0), $employee['currency'] ?? 'ARS') ?></p>
      </div>
    </div>

    <!-- Turnos de hoy -->
    <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5 mb-6">
      <div class="flex items-center justify-between mb-4">
        <h2 class="font-semibold text-cream-light">Turnos de hoy <span class="text-ink-400 font-normal text-sm">(<?= date('d/m/Y') ?>)</span></h2>
        <a href="<?= path('/mi-panel/turnos') ?>" class="text-xs text-gold hover:text-gold-300 transition-colors">Ver todos →</a>
      </div>
      <?php if (empty($todayAppts)): ?>
      <p class="text-ink-500 text-sm py-4 text-center">Sin turnos para hoy.</p>
      <?php else: ?>
      <div class="space-y-2">
        <?php
        $config   = require BASE_PATH . '/config/app.php';
        $statuses = $config['appointment_statuses'];
        foreach ($todayAppts as $a):
          $sc = $statuses[$a['status']] ?? ['label'=>$a['status'],'color'=>'gray'];
        ?>
        <div class="flex items-center gap-3 p-3 bg-ink-700/50 rounded-xl">
          <span class="font-mono text-gold text-sm w-12 shrink-0"><?= hora($a['start_time']) ?></span>
          <div class="flex-1 min-w-0">
            <p class="text-cream-light text-sm font-medium truncate"><?= e($a['client_name']) ?></p>
            <p class="text-ink-400 text-xs"><?= e($a['service_name']) ?> · <?= duracionTexto((int)$a['duration_min']) ?></p>
          </div>
          <span class="text-xs px-2 py-0.5 rounded-full bg-<?= $sc['color'] ?>-500/20 text-<?= $sc['color'] ?>-400 shrink-0"><?= $sc['label'] ?></span>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- Accesos rápidos -->
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
      <a href="<?= path('/mi-panel/nuevo-turno') ?>"
         class="flex flex-col items-center gap-2 p-4 bg-gold/10 border border-gold/30 hover:bg-gold/20 rounded-2xl transition-all text-center">
        <span class="text-2xl">➕</span>
        <span class="text-xs font-semibold text-gold">Cargar turno</span>
      </a>
      <a href="<?= path('/mi-panel/servicios') ?>"
         class="flex flex-col items-center gap-2 p-4 bg-ink-800 border border-ink-700 hover:border-ink-500 rounded-2xl transition-all text-center">
        <span class="text-2xl">✂</span>
        <span class="text-xs font-medium text-ink-300">Mis servicios</span>
      </a>
      <a href="<?= path('/mi-panel/fotos') ?>"
         class="flex flex-col items-center gap-2 p-4 bg-ink-800 border border-ink-700 hover:border-ink-500 rounded-2xl transition-all text-center">
        <span class="text-2xl">📷</span>
        <span class="text-xs font-medium text-ink-300">Mis fotos</span>
      </a>
    </div>

  </main>
</div>
