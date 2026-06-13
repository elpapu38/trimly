<?php $pageTitle = $pageTitle ?? 'Reseñas reportadas'; ?>
<div class="flex min-h-screen pt-16 pb-16 lg:pb-0">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-1 p-5 lg:p-8 overflow-x-hidden min-w-0">
  <div class="mb-6">
    <h1 class="font-display text-xl font-bold text-cream-light">Reseñas reportadas</h1>
    <p class="text-sm text-ink-400 mt-1"><?= $total ?> reseña<?= $total!==1?'s':'' ?> con reportes</p>
  </div>
  <?php if(empty($reviews)): ?>
  <div class="text-center py-20"><span class="text-4xl">✅</span><p class="text-ink-400 mt-3">No hay reseñas reportadas.</p></div>
  <?php else: ?>
  <div class="space-y-4">
    <?php foreach($reviews as $r): ?>
    <div class="bg-ink-800 border <?= $r['report_count']>=3?'border-red-500/40':'border-ink-700' ?> rounded-2xl p-5">
      <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 mb-3">
        <div>
          <div class="flex items-center gap-2 mb-1">
            <span class="text-amber-400 text-sm"><?= str_repeat('★',(int)$r['rating']) ?><?= str_repeat('☆',5-(int)$r['rating']) ?></span>
            <span class="text-sm font-medium text-cream-light"><?= e($r['client_name']) ?></span>
            <span class="text-xs text-ink-500">en <?= e($r['shop_name']) ?></span>
          </div>
          <p class="text-sm text-ink-300"><?= e($r['comment']??'Sin comentario') ?></p>
          <p class="text-xs text-ink-500 mt-1"><?= fecha($r['created_at'],'d/m/Y') ?></p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
          <span class="text-xs font-bold px-2.5 py-1 rounded-full <?= $r['report_count']>=3?'bg-red-500/10 text-red-400':'bg-amber-500/10 text-amber-400' ?>">
            ⚠ <?= $r['report_count'] ?> reporte<?= $r['report_count']!==1?'s':'' ?>
          </span>
        </div>
      </div>
      <?php if($r['reply']): ?>
      <div class="bg-ink-700/50 rounded-xl p-3 mb-3 text-xs text-ink-300">
        <span class="text-gold font-semibold">Respuesta del local:</span> <?= e($r['reply']) ?>
      </div>
      <?php endif; ?>
      <div class="flex gap-2">
        <?php if($r['is_visible']): ?>
        <button onclick="moderateReview(<?=$r['id']?>,'hide')" class="text-xs bg-red-700/80 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg transition-colors">Ocultar reseña</button>
        <?php else: ?>
        <button onclick="moderateReview(<?=$r['id']?>,'show')" class="text-xs bg-emerald-600 hover:bg-emerald-500 text-white px-3 py-1.5 rounded-lg transition-colors">Restaurar</button>
        <span class="text-xs text-ink-500 self-center">Actualmente oculta</span>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php if($lastPage>1): ?>
  <div class="flex justify-center gap-2 mt-6">
    <?php for($p=1;$p<=$lastPage;$p++): ?>
    <a href="?page=<?=$p?>" class="px-4 py-2 rounded-xl text-sm <?= $p===$page?'bg-gold text-ink-900 font-bold':'bg-ink-700 text-ink-300 hover:bg-ink-600' ?>"><?=$p?></a>
    <?php endfor; ?>
  </div>
  <?php endif; ?>
  <?php endif; ?>
</main>
</div>
<script>
async function moderateReview(id,action){
  const r=await fetch(`<?= path('/admin/resenas/') ?>${id}/moderar`,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:new URLSearchParams({action})});
  const d=await r.json();
  if(d.success)location.reload();else alert('Error');
}
</script>
