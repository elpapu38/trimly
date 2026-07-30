<?php $pageTitle = $pageTitle ?? 'Estadísticas'; ?>
<div class="flex min-h-screen pt-16 pb-16 lg:pb-0">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-1 p-5 lg:p-8 overflow-x-hidden min-w-0">
  <div class="flex items-center justify-between mb-6">
    <h1 class="font-display text-xl font-bold text-cream-light">Estadísticas globales</h1>
    <p class="text-xs text-ink-500">Datos del año en curso</p>
  </div>

  <!-- Monthly chart -->
  <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5 mb-5">
    <h2 class="font-semibold text-cream-light mb-4 text-sm">Turnos completados por mes — <?= $year ?></h2>
    <?php $months=['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic']; ?>
    <canvas id="monthlyChart" height="90"></canvas>
    <script>
    window._monthlyData = {
      labels: <?= json_encode($months) ?>,
      completed: <?= json_encode(array_map('intval', array_column($byMonth, 'completed'))) ?>,
      total:     <?= json_encode(array_map('intval', array_column($byMonth, 'total_appts'))) ?>,
    };
    </script>
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
      <?php if(empty($growth)): ?>
        <p class="text-xs text-ink-500 py-4 text-center">Sin datos.</p>
      <?php else: ?>
      <canvas id="growthChart" height="120"></canvas>
      <script>
      (function(){
        const labels = <?= json_encode(array_column($growth,'ym')) ?>;
        const data   = <?= json_encode(array_map('intval', array_column($growth,'new_users'))) ?>;
        if(!window.Chart) return; // Chart.js not yet loaded, will retry below
        new Chart(document.getElementById('growthChart'), {
          type: 'bar',
          data: {
            labels,
            datasets:[{ label:'Nuevos usuarios', data,
              backgroundColor:'rgba(34,197,94,0.6)', borderColor:'#22c55e', borderWidth:1, borderRadius:3 }]
          },
          options:{ responsive:true, plugins:{legend:{display:false}}, scales:{ y:{beginAtZero:true,ticks:{stepSize:1}} } }
        });
      })();
      </script>
      <?php endif; ?>
    </div>

  <!-- Chart.js + init all charts -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script>
  Chart.defaults.color       = '#A0A0A0';
  Chart.defaults.borderColor = '#2E2E2E';

  // Monthly turnos chart
  const mData = window._monthlyData;
  if(mData && document.getElementById('monthlyChart')) {
    new Chart(document.getElementById('monthlyChart'), {
      type: 'bar',
      data: {
        labels: mData.labels,
        datasets: [
          { label: 'Completados', data: mData.completed,
            backgroundColor: 'rgba(201,168,76,0.75)', borderColor: '#C9A84C', borderWidth:1, borderRadius:4 },
          { label: 'Total',       data: mData.total,
            backgroundColor: 'rgba(201,168,76,0.2)',  borderColor: '#C9A84C', borderWidth:1, borderRadius:4,
            borderDash: [4,2] }
        ]
      },
      options: { responsive:true, scales:{ y:{ beginAtZero:true } } }
    });
  }

  // Growth chart
  const gEl = document.getElementById('growthChart');
  if(gEl && !gEl._chart) {
    const labels = <?= json_encode(array_column($growth ?? [],'ym')) ?>;
    const data   = <?= json_encode(array_map('intval', array_column($growth ?? [],'new_users'))) ?>;
    gEl._chart = new Chart(gEl, {
      type: 'bar',
      data: { labels, datasets:[{ label:'Nuevos usuarios', data,
        backgroundColor:'rgba(34,197,94,0.65)', borderColor:'#22c55e', borderWidth:1, borderRadius:3 }] },
      options:{ responsive:true, plugins:{legend:{display:false}}, scales:{ y:{beginAtZero:true, ticks:{stepSize:1}} } }
    });
  }
  </script>
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
