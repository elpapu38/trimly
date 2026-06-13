<?php $pageTitle = $pageTitle ?? 'Reseñas reportadas'; ?>
<div class="flex min-h-screen pt-16 pb-16 lg:pb-0">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-1 p-4 sm:p-5 lg:p-8 overflow-x-hidden min-w-0">
  <div class="mb-6">
    <h1 class="font-display text-xl font-bold text-cream-light">Reseñas reportadas</h1>
    <p class="text-sm text-ink-400 mt-1"><?= $total ?> reseña<?= $total!==1?'s':'' ?> con reportes</p>
  </div>
  <?php if(empty($reviews)): ?>
  <div class="text-center py-20"><span class="text-4xl">✅</span><p class="text-ink-400 mt-3">No hay reseñas reportadas.</p></div>
  <?php else: ?>
  <div class="space-y-4">
    <?php foreach($reviews as $r): ?>
    <div class="bg-ink-800 border <?= $r['report_count']>=3?'border-red-500/40':'border-ink-700' ?> rounded-2xl p-4 sm:p-5">
      <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 mb-3">
        <div class="flex-1 min-w-0">
          <div class="flex flex-wrap items-center gap-2 mb-1">
            <span class="text-amber-400 text-sm"><?= str_repeat('★',(int)$r['rating']) ?><?= str_repeat('☆',5-(int)$r['rating']) ?></span>
            <span class="text-sm font-medium text-cream-light"><?= e($r['client_name']) ?></span>
            <span class="text-xs text-ink-500">en <?= e($r['shop_name']) ?></span>
          </div>
          <p class="text-sm text-ink-300"><?= e($r['comment']??'Sin comentario') ?></p>
          <p class="text-xs text-ink-500 mt-1"><?= fecha($r['created_at'],'d/m/Y') ?></p>
        </div>
        <span class="text-xs font-bold px-2.5 py-1 rounded-full shrink-0 <?= $r['report_count']>=3?'bg-red-500/10 text-red-400':'bg-amber-500/10 text-amber-400' ?>">
          ⚠ <?= $r['report_count'] ?> reporte<?= $r['report_count']!==1?'s':'' ?>
        </span>
      </div>
      <?php if($r['reply']): ?>
      <div class="bg-ink-700/50 rounded-xl p-3 mb-3 text-xs text-ink-300">
        <span class="text-gold font-semibold">Respuesta del local:</span> <?= e($r['reply']) ?>
      </div>
      <?php endif; ?>
      <div class="flex flex-wrap gap-2">
        <?php if($r['is_visible']): ?>
        <button onclick="modReview(<?=$r['id']?>,'hide')"
                class="text-xs bg-amber-700/80 hover:bg-amber-700 text-white px-3 py-1.5 rounded-lg transition-colors">Ocultar</button>
        <?php else: ?>
        <button onclick="modReview(<?=$r['id']?>,'show')"
                class="text-xs bg-emerald-600 hover:bg-emerald-500 text-white px-3 py-1.5 rounded-lg transition-colors">Restaurar</button>
        <span class="text-xs text-ink-500 self-center">Oculta</span>
        <?php endif; ?>
        <button onclick="modReview(<?=$r['id']?>,'delete')"
                class="text-xs bg-red-700/80 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg transition-colors">Eliminar</button>
        <button onclick="openWarnModal(<?=$r['id']?>)"
                class="text-xs bg-ink-700 hover:bg-ink-600 border border-ink-600 hover:border-amber-500/50 text-ink-300 hover:text-amber-300 px-3 py-1.5 rounded-lg transition-colors">
          ⚡ Advertir usuario
        </button>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php if($lastPage>1): ?>
  <div class="flex justify-center gap-2 mt-6 flex-wrap">
    <?php for($p=1;$p<=$lastPage;$p++): ?>
    <a href="?page=<?=$p?>" class="px-4 py-2 rounded-xl text-sm <?= $p===$page?'bg-gold text-ink-900 font-bold':'bg-ink-700 text-ink-300 hover:bg-ink-600' ?>"><?=$p?></a>
    <?php endfor; ?>
  </div>
  <?php endif; ?>
  <?php endif; ?>
</main>
</div>

<!-- Modal advertencia -->
<div id="warn-modal" class="fixed inset-0 z-50 bg-ink-900/80 backdrop-blur-sm hidden items-center justify-center p-4">
  <div class="bg-ink-800 border border-ink-700 rounded-2xl w-full max-w-md p-6 shadow-2xl">
    <h3 class="font-display text-lg font-bold text-cream-light mb-3">Advertir al usuario</h3>
    <p class="text-xs text-ink-400 mb-4">Se ocultará la reseña y se registrará una advertencia en la cuenta del usuario.</p>
    <label class="block text-xs text-ink-400 mb-1">Motivo de la advertencia</label>
    <textarea id="warn-reason" rows="3"
              placeholder="Ej: Lenguaje inapropiado, reseña falsa, spam…"
              class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light resize-none focus:outline-none mb-4"></textarea>
    <div class="flex gap-3">
      <button onclick="closeWarnModal()" class="flex-1 px-4 py-2.5 border border-ink-600 text-ink-300 rounded-xl text-sm hover:border-ink-500">Cancelar</button>
      <button onclick="sendWarn()" class="flex-1 px-4 py-2.5 bg-amber-600 hover:bg-amber-500 text-white font-semibold rounded-xl text-sm">Enviar advertencia</button>
    </div>
  </div>
</div>

<script>
const BASE = <?= json_encode(base_path()) ?>;
let _warnId = null;

async function modReview(id, action) {
  if (action === 'delete' && !confirm('¿Eliminar esta reseña permanentemente?')) return;
  const fd = new FormData(); fd.append('action', action);
  const r  = await fetch(`${BASE}/admin/resenas/${id}/moderar`, {method:'POST', body:fd});
  const d  = await r.json();
  if (d.success) location.reload(); else alert('Error');
}

function openWarnModal(id) {
  _warnId = id;
  document.getElementById('warn-reason').value = '';
  const m = document.getElementById('warn-modal');
  m.classList.remove('hidden'); m.classList.add('flex');
}
function closeWarnModal() {
  document.getElementById('warn-modal').classList.replace('flex','hidden');
}
async function sendWarn() {
  const reason = document.getElementById('warn-reason').value.trim();
  const fd = new FormData();
  fd.append('action', 'warn');
  if (reason) fd.append('reason', reason);
  const r = await fetch(`${BASE}/admin/resenas/${_warnId}/moderar`, {method:'POST', body:fd});
  const d = await r.json();
  closeWarnModal();
  if (d.success) location.reload(); else alert('Error');
}
</script>
