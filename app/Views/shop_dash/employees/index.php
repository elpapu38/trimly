<?php $pageTitle = $pageTitle ?? 'Empleados'; ?>
<div class="flex min-h-screen pt-16 pb-20 lg:pb-6">
  <?php require __DIR__ . '/../partials/sidebar.php'; ?>
  <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden min-w-0">
    <div class="flex items-center justify-between mb-5">
      <h1 class="font-display text-xl font-bold text-cream-light">Profesionales</h1>
      <a href="<?= path('/panel/empleados/nuevo') ?>"
         class="bg-gold hover:bg-gold-500 text-ink-900 font-bold px-4 py-2 rounded-xl text-sm transition-colors flex items-center gap-2">
        <span>+</span><span class="hidden sm:inline">Nuevo profesional</span>
      </a>
    </div>

    <?php if(empty($employees)): ?>
    <div class="text-center py-16 bg-ink-800 border border-ink-700 rounded-2xl">
      <div class="text-4xl mb-3">👥</div>
      <h2 class="font-semibold text-cream-light mb-2">Sin profesionales todavía</h2>
      <p class="text-sm text-ink-400 mb-5">Agregá los profesionales de tu local para que los clientes puedan elegir con quién atenderse.</p>
      <a href="<?= path('/panel/empleados/nuevo') ?>" class="bg-gold text-ink-900 font-bold px-6 py-3 rounded-xl text-sm hover:bg-gold-500 transition-colors">
        + Agregar primer profesional
      </a>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <?php foreach($employees as $emp): ?>
      <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5 flex flex-col">
        <div class="flex items-center gap-3 mb-3">
          <?php if(!empty($emp['avatar'])): ?>
          <img src="<?= upload_url($emp['avatar']) ?>" class="w-12 h-12 rounded-full object-cover border border-ink-600 shrink-0">
          <?php else: ?>
          <div class="w-12 h-12 rounded-full bg-ink-700 border border-ink-600 flex items-center justify-center text-lg font-bold text-gold shrink-0">
            <?= initials($emp['name']) ?>
          </div>
          <?php endif; ?>
          <div class="min-w-0">
            <p class="font-semibold text-cream-light truncate"><?= e($emp['name']) ?></p>
            <?php if($emp['specialty']): ?>
            <p class="text-xs text-ink-400 truncate"><?= e($emp['specialty']) ?></p>
            <?php endif; ?>
          </div>
        </div>

        <!-- Servicios asignados -->
        <?php if(!empty($emp['service_ids'])): ?>
        <div class="mb-3">
          <p class="text-xs text-ink-500 mb-1.5">Servicios:</p>
          <div class="flex flex-wrap gap-1">
            <?php
            $ids = explode(',', $emp['service_ids']);
            $servNames = [];
            if(!empty($ids)) {
                $db = \App\Models\Database::getInstance();
                $in = implode(',', array_fill(0, count($ids), '?'));
                $st = $db->prepare("SELECT name FROM services WHERE id IN ({$in})");
                $st->execute($ids);
                $servNames = array_column($st->fetchAll(), 'name');
            }
            foreach(array_slice($servNames, 0, 4) as $sn):
            ?>
            <span class="text-[10px] bg-ink-700 text-ink-300 border border-ink-600 px-2 py-0.5 rounded-full truncate max-w-[100px]"><?= e($sn) ?></span>
            <?php endforeach; ?>
            <?php if(count($servNames) > 4): ?>
            <span class="text-[10px] text-ink-500">+<?= count($servNames)-4 ?> más</span>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>

        <div class="flex items-center justify-between mt-auto pt-3 border-t border-ink-700/50">
          <span class="text-[11px] px-2 py-0.5 rounded-full <?=$emp['status']==='active'?'bg-emerald-500/10 text-emerald-400':'bg-ink-700 text-ink-500'?>">
            <?= $emp['status']==='active'?'Activo':'Inactivo' ?>
          </span>
          <a href="<?= path('/panel/empleados/'.$emp['id']) ?>"
             class="text-xs bg-ink-700 hover:bg-ink-600 text-ink-200 px-3 py-1.5 rounded-lg transition-colors">
            Editar
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </main>
</div>
