<?php /* app/Views/shop_dash/stats.php */
$months = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
?>
<div class="flex">
  <?php include __DIR__ . '/partials/sidebar.php'; ?>
  <div class="ml-56 flex-1 p-6 min-h-screen bg-ink-900">

    <div class="flex items-center justify-between mb-6">
      <h1 class="font-display text-2xl text-cream-light">Estadísticas</h1>
      <div class="flex items-center gap-2">
        <?php for ($y = date('Y'); $y >= date('Y')-2; $y--): ?>
        <a href="?year=<?= $y ?>"
           class="px-3 py-1.5 rounded-lg text-sm border <?= $y == $year ? 'bg-gold text-ink-900 border-gold' : 'border-ink-600 text-ink-300 hover:border-gold/50' ?>">
          <?= $y ?>
        </a>
        <?php endfor; ?>
      </div>
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <?php $kpis = [
        ['label'=>'Turnos totales',   'val'=> (int)$totals['total'],     'icon'=>'📋'],
        ['label'=>'Completados',      'val'=> (int)$totals['completed'], 'icon'=>'✅'],
        ['label'=>'Cancelados',       'val'=> (int)$totals['cancelled'], 'icon'=>'❌'],
        ['label'=>'Ingresos totales', 'val'=> money((float)$totals['revenue']), 'icon'=>'💰'],
      ];
      foreach ($kpis as $k): ?>
      <div class="bg-ink-800 border border-ink-700 rounded-xl p-4">
        <span class="text-2xl"><?= $k['icon'] ?></span>
        <p class="text-2xl font-bold text-cream-light mt-2"><?= $k['val'] ?></p>
        <p class="text-sm text-ink-400"><?= $k['label'] ?></p>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Gráfico de ingresos por mes -->
    <div class="bg-ink-800 border border-ink-700 rounded-xl p-5 mb-6">
      <h2 class="font-semibold text-cream-light mb-4">Ingresos por mes</h2>
      <canvas id="revenueChart" height="120"></canvas>
    </div>

    <!-- Gráfico de turnos por mes -->
    <div class="bg-ink-800 border border-ink-700 rounded-xl p-5 mb-6">
      <h2 class="font-semibold text-cream-light mb-4">Turnos por mes</h2>
      <canvas id="apptsChart" height="100"></canvas>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">

      <!-- Top servicios -->
      <div class="bg-ink-800 border border-ink-700 rounded-xl p-5">
        <h2 class="font-semibold text-cream-light mb-4">Servicios más solicitados</h2>
        <?php if (empty($topServices)): ?>
          <p class="text-ink-500 text-sm">Sin datos aún</p>
        <?php else: ?>
        <div class="space-y-3">
          <?php foreach ($topServices as $s): ?>
          <div class="flex items-center gap-3">
            <div class="flex-1 min-w-0">
              <div class="flex justify-between mb-1">
                <span class="text-cream-light text-sm truncate"><?= e($s['name']) ?></span>
                <span class="text-ink-400 text-xs ml-2 flex-shrink-0"><?= $s['cnt'] ?> turnos</span>
              </div>
              <div class="w-full bg-ink-700 rounded-full h-1.5">
                <div class="bg-gold h-1.5 rounded-full" style="width:<?= min(100, round($s['cnt'] / max(1, $topServices[0]['cnt']) * 100)) ?>%"></div>
              </div>
            </div>
            <span class="text-gold text-sm font-medium flex-shrink-0"><?= money((float)$s['revenue']) ?></span>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- Top empleados -->
      <div class="bg-ink-800 border border-ink-700 rounded-xl p-5">
        <h2 class="font-semibold text-cream-light mb-4">Rendimiento por profesional</h2>
        <?php if (empty($topEmployees)): ?>
          <p class="text-ink-500 text-sm">Sin datos aún</p>
        <?php else: ?>
        <div class="space-y-3">
          <?php foreach ($topEmployees as $emp): ?>
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-gold/20 flex items-center justify-center text-gold text-xs font-bold flex-shrink-0"><?= initials($emp['name']) ?></div>
            <div class="flex-1 min-w-0">
              <div class="flex justify-between mb-1">
                <span class="text-cream-light text-sm truncate"><?= e($emp['name']) ?></span>
                <span class="text-ink-400 text-xs ml-2 flex-shrink-0"><?= $emp['cnt'] ?> turnos</span>
              </div>
              <div class="w-full bg-ink-700 rounded-full h-1.5">
                <div class="bg-gold h-1.5 rounded-full" style="width:<?= min(100, round($emp['cnt'] / max(1, $topEmployees[0]['cnt']) * 100)) ?>%"></div>
              </div>
            </div>
            <span class="text-gold text-sm font-medium flex-shrink-0"><?= money((float)$emp['revenue']) ?></span>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const months = <?= json_encode($months) ?>;
const byMonth = <?= json_encode($byMonth) ?>;
const revenues = byMonth.map(m => parseFloat(m.revenue)||0);
const completed = byMonth.map(m => parseInt(m.completed)||0);
const cancelled = byMonth.map(m => parseInt(m.cancelled)||0);

Chart.defaults.color = '#A0A0A0';
Chart.defaults.borderColor = '#2E2E2E';

new Chart(document.getElementById('revenueChart'), {
  type: 'bar',
  data: {
    labels: months,
    datasets: [{ label: 'Ingresos ($)', data: revenues, backgroundColor: 'rgba(201,168,76,0.7)', borderColor: '#C9A84C', borderWidth:1, borderRadius:4 }]
  },
  options: { responsive: true, plugins:{ legend:{display:false} }, scales:{ y:{ beginAtZero:true } } }
});

new Chart(document.getElementById('apptsChart'), {
  type: 'line',
  data: {
    labels: months,
    datasets: [
      { label:'Completados', data: completed, borderColor:'#22c55e', backgroundColor:'rgba(34,197,94,0.1)', tension:0.3, fill:true },
      { label:'Cancelados',  data: cancelled, borderColor:'#ef4444', backgroundColor:'rgba(239,68,68,0.1)',  tension:0.3, fill:true }
    ]
  },
  options: { responsive: true, scales:{ y:{ beginAtZero:true } } }
});
</script>
