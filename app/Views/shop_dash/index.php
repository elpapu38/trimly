<?php $pageTitle = $pageTitle ?? 'Panel'; ?>
<div class="flex min-h-screen pt-16 pb-20 lg:pb-6">
  <?php require __DIR__ . '/partials/sidebar.php'; ?>
  <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden min-w-0">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
      <div>
        <h1 class="font-display text-xl font-bold text-cream-light"><?= e($shop['name']) ?></h1>
        <div class="flex flex-wrap items-center gap-2 mt-1">
          <span class="text-xs text-ink-400"><?= \App\Models\Shop::typeLabel($shop['type']) ?></span>
          <?php if(!empty($shop['verified'])): ?><span class="text-[10px] bg-blue-500/10 text-blue-400 border border-blue-500/20 px-2 py-0.5 rounded-full">✓ Verificado</span><?php endif; ?>
          <?php if(!empty($shop['featured'])): ?><span class="text-[10px] bg-gold/10 text-gold border border-gold/20 px-2 py-0.5 rounded-full">⭐ Destacado</span><?php endif; ?>
          <?php if($shop['status']!=='active'): ?>
          <span class="text-[10px] bg-amber-500/10 text-amber-400 border border-amber-500/20 px-2 py-0.5 rounded-full">
            <?php if($shop['status']==='suspended'): ?>⏸ Suspendido<?php else: ?><?= ucfirst($shop['status']) ?><?php endif; ?>
          </span>
          <?php endif; ?>
        </div>
      </div>
      <a href="<?= path('/local/'.e($shop['slug'])) ?>" target="_blank"
         class="text-xs border border-ink-600 hover:border-gold/40 text-ink-300 hover:text-gold px-3 py-2 rounded-xl transition-all self-start sm:self-center">
        ↗ Ver perfil público
      </a>
    </div>

    <!-- Alerta si suspendido -->
    <?php if($shop['status']==='suspended'): ?>
    <div class="bg-amber-500/10 border border-amber-500/30 rounded-2xl p-4 mb-5">
      <p class="text-amber-300 font-semibold text-sm">⚠ Tu local está suspendido</p>
      <?php if(!empty($shop['suspension_reason'])): ?>
      <p class="text-amber-400 text-xs mt-1"><?= e($shop['suspension_reason']) ?></p>
      <?php endif; ?>
      <?php if(!empty($shop['suspension_until'])): ?>
      <p class="text-amber-400/70 text-xs mt-1">Hasta: <?= fecha($shop['suspension_until'],'d/m/Y H:i') ?></p>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- KPIs del mes -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
      <?php $ms = $monthStats ?? []; ?>
      <?php foreach([
        ['Turnos del mes',  $ms['total']??0,     'text-cream-light', '📅'],
        ['Completados',     $ms['completed']??0, 'text-emerald-400', '✓'],
        ['Cancelados',      $ms['cancelled']??0, 'text-red-400',     '✕'],
        ['Ingresos',        money((float)($ms['revenue']??0)), 'text-gold', '💰'],
      ] as [$l,$v,$c,$ic]): ?>
      <div class="bg-ink-800 border border-ink-700 rounded-2xl p-4">
        <div class="flex items-center justify-between mb-2">
          <span class="text-sm"><?=$ic?></span>
          <span class="text-[10px] text-ink-500 uppercase font-semibold"><?=$l?></span>
        </div>
        <div class="text-xl font-bold <?=$c?>"><?=$v?></div>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

      <!-- Turnos de hoy -->
      <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5">
        <div class="flex items-center justify-between mb-4">
          <h2 class="font-semibold text-cream-light text-sm">Turnos de hoy</h2>
          <a href="<?= path('/panel/agenda') ?>" class="text-xs text-gold hover:underline">Ver agenda →</a>
        </div>
        <?php if(empty($todayAppts)): ?>
        <div class="text-center py-8">
          <p class="text-2xl mb-2">📭</p>
          <p class="text-sm text-ink-500">Sin turnos para hoy.</p>
        </div>
        <?php else: ?>
        <div class="space-y-2 max-h-72 overflow-y-auto pr-1">
          <?php foreach($todayAppts as $a):
            $sc=['pending'=>'text-amber-400','confirmed'=>'text-emerald-400','completed'=>'text-blue-400','cancelled_client'=>'text-ink-500','cancelled_shop'=>'text-ink-500','no_show'=>'text-ink-500'];
            $c=$sc[$a['status']]??'text-ink-400';
          ?>
          <div class="flex items-center gap-3 p-3 bg-ink-700/40 rounded-xl">
            <div class="text-center shrink-0 w-12">
              <p class="text-xs font-bold text-cream-light"><?= hora($a['start_time']) ?></p>
              <p class="text-[10px] text-ink-500"><?= hora($a['end_time']) ?></p>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-cream-light truncate"><?= e($a['client_name']) ?></p>
              <p class="text-xs text-ink-400 truncate"><?= e($a['service_name']) ?> · <?= e($a['employee_name']) ?></p>
            </div>
            <span class="<?=$c?> text-[10px] font-semibold uppercase shrink-0"><?= $a['status']==='confirmed'?'✓':($a['status']==='pending'?'⏳':($a['status']==='completed'?'✅':'✕')) ?></span>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- Reseñas recientes -->
      <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5">
        <div class="flex items-center justify-between mb-4">
          <h2 class="font-semibold text-cream-light text-sm">Reseñas recientes</h2>
          <a href="<?= path('/panel/resenas') ?>" class="text-xs text-gold hover:underline">Ver todas →</a>
        </div>
        <?php if(empty($recentReviews)): ?>
        <div class="text-center py-8">
          <p class="text-2xl mb-2">⭐</p>
          <p class="text-sm text-ink-500">Sin reseñas todavía.</p>
        </div>
        <?php else: ?>
        <div class="space-y-3 max-h-72 overflow-y-auto pr-1">
          <?php foreach($recentReviews as $r): ?>
          <div class="p-3 bg-ink-700/40 rounded-xl">
            <div class="flex items-center justify-between mb-1">
              <span class="text-xs font-medium text-cream-light"><?= e($r['client_name']) ?></span>
              <span class="text-amber-400 text-xs"><?= str_repeat('★',(int)$r['rating']) ?><?= str_repeat('☆',5-(int)$r['rating']) ?></span>
            </div>
            <p class="text-xs text-ink-300 line-clamp-2"><?= e(truncate($r['comment']??'',100)) ?></p>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- Accesos rápidos -->
      <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5 lg:col-span-2">
        <h2 class="font-semibold text-cream-light mb-3 text-sm">Accesos rápidos</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
          <?php foreach([
            ['/panel/turnos',       '📋','Ver turnos'],
            ['/panel/empleados',    '👥','Profesionales'],
            ['/panel/servicios',    '💈','Servicios'],
            ['/panel/estadisticas', '📈','Estadísticas'],
          ] as[$u,$ic,$l]): ?>
          <a href="<?= path($u) ?>" class="flex flex-col items-center gap-2 p-4 bg-ink-700/40 hover:bg-ink-700 rounded-xl transition-colors group">
            <span class="text-2xl"><?=$ic?></span>
            <span class="text-xs text-ink-300 group-hover:text-cream-light transition-colors text-center"><?=$l?></span>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </main>
</div>
