<?php $pageTitle = $pageTitle ?? 'Estadísticas'; ?>
<div class="flex min-h-screen pt-16 pb-16 lg:pb-0">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-1 p-5 lg:p-8 overflow-x-hidden min-w-0">
  <div class="flex items-center justify-between mb-6">
    <h1 class="font-display text-xl font-bold text-cream-light">Estadísticas globales</h1>
    <form method="GET" class="flex gap-2">
      <select name="year" onchange="this.form.submit()" class="bg-ink-700 border border-ink-600 rounded-xl px-3 py-2 text-sm text-cream-light focus:border-gold focus:outline-none">
        <?php for($y=date('Y');$y>=date('Y')-3;$y--): ?>
        <option value="<?=$y?>" <?=$y===$year?'selected':''?>><?=$y?></option>
        <?php endfor; ?>
      </select>
    </form>
  </div>

  <!-- Monthly chart -->
  <div class="bg-ink-800 border border-ink-700 rounded-2xl p-6 mb-5">
    <h2 class="font-semibold text-cream-light mb-4 text-sm">Turnos completados por mes — <?= $year ?></h2>
    <?php $maxC=max(1,max(array_column($byMonth,'completed'))); $months=['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic']; ?>
    <div class="flex items-end gap-1.5 h-32">
      <?php foreach($byMonth as $i=>$m): $h=max(2,(int)(($m['completed']/$maxC)*100)); ?>
      <div class="flex-1 flex flex-col items-center gap-1">
        <span class="text-[9px] text-ink-400 hidden sm:block"><?= $m['completed'] ?></span>
        <div class="w-full bg-gold rounded-t" style="height:<?=$h?>%"></div>
        <span class="text-[9px] text-ink-500"><?= $months[$i] ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-5">
    <!-- Por tipo -->
    <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5">
      <h2 class="font-semibold text-cream-light mb-3 text-sm">Locales por tipo</h2>
      <div class="space-y-2">
        <?php $totalT=max(1,array_sum(array_column($byType,'cnt'))); foreach($byType as $t): $pct=(int)($t['cnt']/$totalT*100); ?>
        <div>
          <div class="flex justify-between text-xs mb-1">
            <span class="text-ink-300"><?= \App\Models\Shop::typeLabel($t['type']) ?></span>
            <span class="text-ink-500"><?= $t['cnt'] ?></span>
          </div>
          <div class="h-1.5 bg-ink-700 rounded-full"><div class="h-full bg-gold rounded-full" style="width:<?=$pct?>%"></div></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <!-- Por ciudad -->
    <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5">
      <h2 class="font-semibold text-cream-light mb-3 text-sm">Top ciudades</h2>
      <div class="space-y-2">
        <?php $totalC2=max(1,array_sum(array_column($byCity,'cnt'))); foreach($byCity as $c): $pct=(int)($c['cnt']/$totalC2*100); ?>
        <div>
          <div class="flex justify-between text-xs mb-1">
            <span class="text-ink-300"><?= e($c['city']) ?></span>
            <span class="text-ink-500"><?= $c['cnt'] ?> locales</span>
          </div>
          <div class="h-1.5 bg-ink-700 rounded-full"><div class="h-full bg-blue-400 rounded-full" style="width:<?=$pct?>%"></div></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <!-- Crecimiento usuarios -->
    <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5">
      <h2 class="font-semibold text-cream-light mb-3 text-sm">Nuevos usuarios (12m)</h2>
      <?php if(empty($growth)): ?><p class="text-xs text-ink-500">Sin datos.</p>
      <?php else: $maxG=max(1,max(array_column($growth,'new_users'))); ?>
      <div class="flex items-end gap-1 h-20">
        <?php foreach($growth as $g): $h=max(2,(int)($g['new_users']/$maxG*100)); ?>
        <div class="flex-1 flex flex-col items-center gap-0.5">
          <div class="w-full bg-emerald-500 rounded-t" style="height:<?=$h?>%"></div>
          <span class="text-[8px] text-ink-500"><?= substr($g['ym'],5) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Top locales -->
  <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5">
    <h2 class="font-semibold text-cream-light mb-4 text-sm">Top 10 locales por ingresos — <?= $year ?></h2>
    <div class="overflow-x-auto">
      <table class="w-full text-sm min-w-[400px]">
        <thead><tr class="text-left text-ink-400 text-xs uppercase border-b border-ink-700">
          <th class="pb-2">#</th><th class="pb-2">Local</th><th class="pb-2">Ciudad</th><th class="pb-2">Turnos</th><th class="pb-2 text-right">Ingresos</th>
        </tr></thead>
        <tbody class="divide-y divide-ink-700/40">
          <?php foreach($topShops as $i=>$sh): ?>
          <tr class="hover:bg-ink-700/20">
            <td class="py-2.5 text-ink-500 text-xs"><?= $i+1 ?></td>
            <td class="py-2.5 font-medium text-cream-light"><?= e($sh['name']) ?></td>
            <td class="py-2.5 text-xs text-ink-400"><?= e($sh['city']??'—') ?></td>
            <td class="py-2.5 text-xs text-ink-300"><?= number_format($sh['total']) ?></td>
            <td class="py-2.5 text-right text-gold font-semibold text-xs"><?= money($sh['revenue']) ?></td>
          </tr>
          <?php endforeach; ?>
          <?php if(empty($topShops)): ?><tr><td colspan="5" class="py-8 text-center text-ink-500">Sin datos.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>
</div>
