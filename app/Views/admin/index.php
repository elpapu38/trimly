<?php $pageTitle = $pageTitle ?? 'Panel Admin'; ?>
<div class="flex min-h-screen pt-16 pb-16 lg:pb-0">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-1 p-5 lg:p-8 overflow-x-hidden min-w-0">

  <div class="mb-6">
    <h1 class="font-display text-2xl font-bold text-cream-light">Panel de Administración</h1>
    <p class="text-ink-400 text-sm mt-1"><?= date('d \d\e F Y') ?></p>
  </div>

  <!-- KPIs -->
  <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
    <?php $kpis=[['Activos',$stats['active_shops'],'text-emerald-400 bg-emerald-500/10','🏪'],['Pendientes',$stats['pending_shops'],'text-amber-400 bg-amber-500/10','⏳'],['Suspendidos',$stats['suspended_shops'],'text-red-400 bg-red-500/10','🚫'],['Shadowban',$stats['shadowbanned'],'text-purple-400 bg-purple-500/10','👻'],['Clientes',$stats['total_users'],'text-blue-400 bg-blue-500/10','👥'],['Propietarios',$stats['total_owners'],'text-cyan-400 bg-cyan-500/10','👔'],['Turnos mes',$stats['month_appts'],'text-gold bg-gold/10','📅'],['Reseñas flag.',$stats['flagged_reviews'],'text-rose-400 bg-rose-500/10','⚠️']];
    foreach($kpis as[$l,$v,$c,$ic]): ?>
    <div class="bg-ink-800 border border-ink-700 rounded-2xl p-4">
      <div class="flex items-center justify-between mb-2">
        <span><?= $ic ?></span>
        <span class="text-[10px] uppercase font-semibold px-2 py-0.5 rounded-full <?= $c ?>"><?= $l ?></span>
      </div>
      <div class="text-2xl font-bold text-cream-light"><?= number_format($v) ?></div>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">
    <!-- Chart ingresos -->
    <div class="lg:col-span-2 bg-ink-800 border border-ink-700 rounded-2xl p-5">
      <h2 class="font-semibold text-cream-light mb-4 text-sm">Ingresos últimos 6 meses</h2>
      <?php if(empty($revenue)): ?>
        <p class="text-sm text-ink-500 py-8 text-center">Sin datos de ingresos aún.</p>
      <?php else: $maxRev=max(1,max(array_column($revenue,'rev'))); ?>
      <div class="flex items-end gap-2 h-28">
        <?php foreach($revenue as $r): $h=max(4,(int)(($r['rev']/$maxRev)*100)); ?>
        <div class="flex-1 flex flex-col items-center gap-1">
          <span class="text-[9px] text-ink-400"><?= money($r['rev']) ?></span>
          <div class="w-full bg-gold rounded-t transition-all" style="height:<?= $h ?>%"></div>
          <span class="text-[9px] text-ink-400"><?= substr($r['ym'],5) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
    <!-- Top locales -->
    <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5">
      <h2 class="font-semibold text-cream-light mb-3 text-sm">Top locales del mes</h2>
      <div class="space-y-3">
        <?php foreach($topShops as $i=>$sh): ?>
        <div class="flex items-center gap-2">
          <span class="text-xs font-bold text-ink-500 w-4"><?= $i+1 ?></span>
          <div class="flex-1 min-w-0">
            <a href="<?= path('/admin/locales/'.$sh['id']) ?>" class="text-sm font-medium text-cream-light hover:text-gold truncate block"><?= e($sh['name']) ?></a>
            <span class="text-xs text-ink-500">⭐ <?= number_format($sh['rating_avg'],1) ?> · <?= $sh['views_count'] ?> vistas</span>
          </div>
          <span class="text-xs font-bold text-gold shrink-0"><?= $sh['month_appts'] ?></span>
        </div>
        <?php endforeach; ?>
        <?php if(empty($topShops)): ?><p class="text-xs text-ink-500">Sin datos.</p><?php endif; ?>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    <!-- Pendientes -->
    <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5">
      <div class="flex items-center justify-between mb-4">
        <h2 class="font-semibold text-cream-light text-sm">Pendientes de aprobación</h2>
        <a href="<?= path('/admin/locales?status=pending') ?>" class="text-xs text-gold hover:underline">Ver todos →</a>
      </div>
      <?php $pendingShops = is_array($pendingShops) && isset($pendingShops["data"]) ? $pendingShops["data"] : (array)$pendingShops; ?>
      <?php if(empty($pendingShops)): ?>
        <div class="text-center py-6"><span class="text-2xl">✅</span><p class="text-sm text-ink-500 mt-2">Todo al día.</p></div>
      <?php else: foreach($pendingShops as $sh): ?>
      <div class="flex items-center gap-3 p-3 bg-ink-700/40 rounded-xl mb-2 last:mb-0">
        <div class="flex-1 min-w-0">
          <p class="text-sm font-medium text-cream-light truncate"><?= e($sh['name'] ?? '—') ?></p>
          <p class="text-xs text-ink-400"><?= e($sh['city']??'') ?> · <?= \App\Models\Shop::typeLabel($sh['type'] ?? null) ?></p>
        </div>
        <div class="flex gap-1.5 shrink-0">
          <button onclick="quickAction(<?=$sh['id']?>,'approve')" class="text-xs bg-emerald-600 hover:bg-emerald-500 text-white px-2.5 py-1.5 rounded-lg">✓</button>
          <a href="<?= path('/admin/locales/'.$sh['id']) ?>" class="text-xs bg-ink-600 hover:bg-ink-500 text-ink-200 px-2.5 py-1.5 rounded-lg">Ver</a>
        </div>
      </div>
      <?php endforeach; endif; ?>
    </div>

    <!-- Moderación reciente -->
    <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5">
      <h2 class="font-semibold text-cream-light mb-3 text-sm">Moderación reciente</h2>
      <?php if(empty($recentMod)): ?>
        <p class="text-xs text-ink-500">Sin actividad.</p>
      <?php else:
        $ac=['approved'=>'text-emerald-400','suspended'=>'text-amber-400','banned'=>'text-red-400','shadowban'=>'text-purple-400','featured'=>'text-gold','verified'=>'text-blue-400','note'=>'text-ink-400','unsuspended'=>'text-emerald-300','unbanned'=>'text-emerald-300'];
        foreach($recentMod as $log): $c=$ac[$log['action']]??'text-ink-300'; ?>
      <div class="flex items-center gap-2 text-xs py-1.5 border-b border-ink-700/40 last:border-0">
        <span class="<?= $c ?> font-bold uppercase w-20 shrink-0 text-[10px]"><?= e($log['action']) ?></span>
        <span class="text-cream-light truncate flex-1"><?= e($log['shop_name']) ?></span>
        <span class="text-ink-500 shrink-0"><?= fecha($log['created_at'],'d/m') ?></span>
      </div>
      <?php endforeach; endif; ?>
    </div>
  </div>
</main>
</div>

<script>
async function quickAction(id, action) {
    const r = await fetch(`<?= path('/admin/locales/') ?>${id}/moderar`,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:new URLSearchParams({action,reason:'',days:0})});
    const d = await r.json();
    if(d.success) location.reload(); else alert('Error: '+(d.error||'?'));
}
</script>
