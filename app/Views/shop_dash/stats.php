<?php /* app/Views/shop_dash/stats.php */
$months = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
?>
<div class="flex min-h-screen pt-16 pb-20 lg:pb-0">
  <?php require __DIR__ . '/partials/sidebar.php'; ?>
  <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden min-w-0">

    <!-- Header responsive -->
    <div class="flex flex-col gap-3 mb-6">
      <h1 class="font-display text-xl font-bold text-cream-light">Estadísticas</h1>

      <!-- Filtros: solo períodos personalizados del superadmin -->
      <?php if (!empty($allPeriods)): ?>
      <div class="flex flex-wrap gap-2">
        <?php foreach ($allPeriods as $p): ?>
        <a href="?period=<?= $p['id'] ?>"
           class="px-3 py-1.5 rounded-lg text-sm border truncate max-w-[200px]
                  <?= ($periodId == $p['id']) ? 'bg-gold text-ink-900 border-gold font-semibold' : 'border-ink-600 text-ink-300 hover:border-gold/50' ?>"
           title="<?= e($p['name']) ?> (<?= date('d/m/Y', strtotime($p['date_from'])) ?> – <?= date('d/m/Y', strtotime($p['date_to'])) ?>)">
          <?= e($p['name']) ?>
        </a>
        <?php endforeach; ?>
      </div>
      <?php if ($activePeriod): ?>
      <p class="text-xs text-gold/80">
        Mostrando: <strong><?= e($activePeriod['name']) ?></strong>
        · <?= date('d/m/Y', strtotime($activePeriod['date_from'])) ?> → <?= date('d/m/Y', strtotime($activePeriod['date_to'])) ?>
      </p>
      <?php else: ?>
      <p class="text-xs text-ink-500">Elegí un período para ver las estadísticas.</p>
      <?php endif; ?>
      <?php else: ?>
      <p class="text-xs text-amber-400/80 bg-amber-500/10 border border-amber-500/20 rounded-xl px-3 py-2">
        El administrador de Trimly todavía no creó períodos de estadísticas.
        Cuando los cree, aparecerán aquí como filtros.
      </p>
      <?php endif; ?>
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
      <?php $kpis = [
        ['label'=>'Turnos totales',   'val'=> (int)$totals['total'],     'icon'=>'📋'],
        ['label'=>'Completados',      'val'=> (int)$totals['completed'], 'icon'=>'✅'],
        ['label'=>'Cancelados',       'val'=> (int)$totals['cancelled'], 'icon'=>'❌'],
        ['label'=>'Ingresos totales', 'val'=> money((float)$totals['revenue']), 'icon'=>'💰'],
      ];
      foreach ($kpis as $k): ?>
      <div class="bg-ink-800 border border-ink-700 rounded-xl p-4">
        <span class="text-2xl"><?= $k['icon'] ?></span>
        <p class="text-xl sm:text-2xl font-bold text-cream-light mt-2 break-all"><?= $k['val'] ?></p>
        <p class="text-xs sm:text-sm text-ink-400"><?= $k['label'] ?></p>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Gráfico de ingresos por mes -->
    <div class="bg-ink-800 border border-ink-700 rounded-xl p-4 sm:p-5 mb-4">
      <h2 class="font-semibold text-cream-light mb-4 text-sm sm:text-base">Ingresos por mes</h2>
      <div class="overflow-x-auto">
        <div style="min-width:320px">
          <canvas id="revenueChart" height="120"></canvas>
        </div>
      </div>
    </div>

    <!-- Gráfico de turnos por mes -->
    <div class="bg-ink-800 border border-ink-700 rounded-xl p-4 sm:p-5 mb-4">
      <h2 class="font-semibold text-cream-light mb-4 text-sm sm:text-base">Turnos por mes</h2>
      <div class="overflow-x-auto">
        <div style="min-width:320px">
          <canvas id="apptsChart" height="100"></canvas>
        </div>
      </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-4">
      <!-- Top servicios -->
      <div class="bg-ink-800 border border-ink-700 rounded-xl p-4 sm:p-5">
        <h2 class="font-semibold text-cream-light mb-4 text-sm sm:text-base">Servicios más solicitados</h2>
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
      <div class="bg-ink-800 border border-ink-700 rounded-xl p-4 sm:p-5">
        <h2 class="font-semibold text-cream-light mb-4 text-sm sm:text-base">Rendimiento por profesional</h2>
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

  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const months  = <?= json_encode($months) ?>;
const byMonth = <?= json_encode(array_values($byMonth)) ?>;
const revenues  = byMonth.map(m => parseFloat(m.revenue)||0);
const completed = byMonth.map(m => parseInt(m.completed)||0);
const cancelled = byMonth.map(m => parseInt(m.cancelled)||0);

Chart.defaults.color       = '#A0A0A0';
Chart.defaults.borderColor = '#2E2E2E';

new Chart(document.getElementById('revenueChart'), {
  type: 'bar',
  data: {
    labels: months,
    datasets: [{ label:'Ingresos ($)', data:revenues, backgroundColor:'rgba(201,168,76,0.7)', borderColor:'#C9A84C', borderWidth:1, borderRadius:4 }]
  },
  options: { responsive:true, plugins:{ legend:{display:false} }, scales:{ y:{ beginAtZero:true } } }
});

new Chart(document.getElementById('apptsChart'), {
  type: 'line',
  data: {
    labels: months,
    datasets: [
      { label:'Completados', data:completed, borderColor:'#22c55e', backgroundColor:'rgba(34,197,94,0.1)', tension:0.3, fill:true },
      { label:'Cancelados',  data:cancelled, borderColor:'#ef4444', backgroundColor:'rgba(239,68,68,0.1)',  tension:0.3, fill:true }
    ]
  },
  options: { responsive:true, scales:{ y:{ beginAtZero:true } } }
});
</script>
