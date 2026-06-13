<?php $pageTitle = $pageTitle ?? 'Usuarios'; ?>
<div class="flex min-h-screen pt-16 pb-16 lg:pb-0">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-1 p-5 lg:p-8 overflow-x-hidden min-w-0">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
    <h1 class="font-display text-xl font-bold text-cream-light">Usuarios</h1>
    <form method="GET" class="flex flex-wrap gap-2">
      <input name="q" value="<?= e($filter['search']) ?>" placeholder="Buscar..." class="bg-ink-700 border border-ink-600 rounded-xl px-3 py-2 text-sm text-cream-light focus:border-gold focus:outline-none w-36">
      <select name="role" class="bg-ink-700 border border-ink-600 rounded-xl px-3 py-2 text-sm text-cream-light focus:border-gold focus:outline-none">
        <option value="">Todos los roles</option>
        <?php foreach(['client'=>'Clientes','shop_owner'=>'Propietarios','employee'=>'Empleados','superadmin'=>'Admins'] as $v=>$l): ?>
        <option value="<?=$v?>" <?=$filter['role']===$v?'selected':''?>><?=$l?></option>
        <?php endforeach; ?>
      </select>
      <select name="status" class="bg-ink-700 border border-ink-600 rounded-xl px-3 py-2 text-sm text-cream-light focus:border-gold focus:outline-none">
        <option value="">Todos los estados</option>
        <?php foreach(['active'=>'Activos','suspended'=>'Suspendidos','banned'=>'Baneados'] as $v=>$l): ?>
        <option value="<?=$v?>" <?=$filter['status']===$v?'selected':''?>><?=$l?></option>
        <?php endforeach; ?>
      </select>
      <button class="bg-gold text-ink-900 font-semibold px-4 py-2 rounded-xl text-sm hover:bg-gold-500 transition-colors">Filtrar</button>
    </form>
  </div>
  <div class="bg-ink-800 border border-ink-700 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-sm min-w-[560px]">
        <thead class="bg-ink-700/50">
          <tr class="text-left text-ink-400 text-xs uppercase tracking-wider">
            <th class="px-4 py-3">Usuario</th>
            <th class="px-4 py-3">Rol</th>
            <th class="px-4 py-3 hidden md:table-cell">Registro</th>
            <th class="px-4 py-3">Estado</th>
            <th class="px-4 py-3">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-ink-700/40">
          <?php foreach($users as $u):
            $roleColors=['client'=>'text-blue-400 bg-blue-500/10','shop_owner'=>'text-gold bg-gold/10','employee'=>'text-cyan-400 bg-cyan-500/10','superadmin'=>'text-purple-400 bg-purple-500/10'];
            $rc=$roleColors[$u['role']]??'text-ink-400 bg-ink-700';
            $sc=['active'=>'text-emerald-400 bg-emerald-500/10','suspended'=>'text-amber-400 bg-amber-500/10','banned'=>'text-red-400 bg-red-500/10'][$u['status']??'active']??'text-ink-400 bg-ink-700';
            $isBanned = ($u['status']??'active') === 'banned';
            $isSusp   = ($u['status']??'active') === 'suspended';
          ?>
          <tr class="hover:bg-ink-700/20 transition-colors">
            <td class="px-4 py-3">
              <p class="font-medium text-cream-light"><?= e($u['name']) ?></p>
              <p class="text-xs text-ink-500"><?= e($u['email']) ?></p>
              <?php if (!empty($u['ban_reason'])): ?>
              <p class="text-xs text-red-400/80 mt-0.5">Motivo: <?= e($u['ban_reason']) ?></p>
              <?php endif; ?>
              <?php if ($isSusp && !empty($u['suspended_until'])): ?>
              <p class="text-xs text-amber-400/80 mt-0.5">Hasta: <?= date('d/m/Y H:i', strtotime($u['suspended_until'])) ?></p>
              <?php endif; ?>
            </td>
            <td class="px-4 py-3"><span class="text-[11px] px-2 py-0.5 rounded-full <?=$rc?>"><?= ucfirst($u['role']) ?></span></td>
            <td class="px-4 py-3 hidden md:table-cell text-xs text-ink-400"><?= fecha($u['created_at']) ?></td>
            <td class="px-4 py-3"><span class="text-[11px] px-2 py-0.5 rounded-full <?=$sc?>"><?= ucfirst($u['status']??'active') ?></span></td>
            <td class="px-4 py-3">
              <?php if($u['role']!=='superadmin'): ?>
              <div class="flex gap-1 flex-wrap">
                <?php if(($u['status']??'active')!=='active'): ?>
                <button onclick="setStatus(<?=$u['id']?>,'active')" class="text-[11px] bg-emerald-600 hover:bg-emerald-500 text-white px-2.5 py-1.5 rounded-lg">Activar</button>
                <?php else: ?>
                <button onclick="openBanModal(<?=$u['id']?>,'suspended')" class="text-[11px] bg-amber-600/80 hover:bg-amber-600 text-white px-2.5 py-1.5 rounded-lg">Susp.</button>
                <button onclick="openBanModal(<?=$u['id']?>,'banned')" class="text-[11px] bg-red-700/80 hover:bg-red-700 text-white px-2.5 py-1.5 rounded-lg">Ban</button>
                <?php endif; ?>
                <?php if($isBanned): ?>
                <button onclick="deleteUser(<?=$u['id']?>,'<?= e(addslashes($u['name'])) ?>')"
                        class="text-[11px] bg-red-900/80 hover:bg-red-800 text-red-300 px-2.5 py-1.5 rounded-lg border border-red-800">Eliminar</button>
                <?php endif; ?>
              </div>
              <?php else: ?>
              <span class="text-xs text-ink-500">—</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if(empty($users)): ?><tr><td colspan="5" class="px-4 py-10 text-center text-ink-500">Sin usuarios.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
    <?php if($pagination['last_page']>1): ?>
    <div class="flex items-center justify-between px-4 py-3 border-t border-ink-700">
      <p class="text-xs text-ink-500"><?= count($users) ?> de <?= $pagination['total'] ?></p>
      <div class="flex gap-1 flex-wrap">
        <?php for($p=1;$p<=$pagination['last_page'];$p++): $a=$p===$pagination['current_page']; ?>
        <a href="?<?= http_build_query(array_merge($filter,['page'=>$p])) ?>" class="px-3 py-1.5 text-xs rounded-lg <?=$a?'bg-gold text-ink-900 font-bold':'bg-ink-700 text-ink-300 hover:bg-ink-600'?>"><?=$p?></a>
        <?php endfor; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</main>
</div>

<!-- Modal ban/suspensión -->
<div id="ban-modal" class="fixed inset-0 z-50 bg-ink-900/80 backdrop-blur-sm hidden items-center justify-center p-4">
  <div class="bg-ink-800 border border-ink-700 rounded-2xl w-full max-w-md p-6 shadow-2xl">
    <h3 id="ban-modal-title" class="font-display text-lg font-bold text-cream-light mb-4"></h3>
    <div class="space-y-4">
      <div>
        <label class="block text-xs text-ink-400 mb-1">Motivo (visible para el usuario)</label>
        <input type="text" id="ban-reason" placeholder="Ej: Comportamiento inapropiado"
               class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
      </div>
      <div id="ban-days-wrap">
        <label class="block text-xs text-ink-400 mb-1">Duración de la suspensión</label>
        <div class="grid grid-cols-4 gap-2">
          <button onclick="setDays(1)"   class="day-btn px-2 py-2 bg-ink-700 border border-ink-600 rounded-lg text-xs text-ink-300 hover:border-gold/50">1 día</button>
          <button onclick="setDays(3)"   class="day-btn px-2 py-2 bg-ink-700 border border-ink-600 rounded-lg text-xs text-ink-300 hover:border-gold/50">3 días</button>
          <button onclick="setDays(7)"   class="day-btn px-2 py-2 bg-ink-700 border border-ink-600 rounded-lg text-xs text-ink-300 hover:border-gold/50">7 días</button>
          <button onclick="setDays(30)"  class="day-btn px-2 py-2 bg-ink-700 border border-ink-600 rounded-lg text-xs text-ink-300 hover:border-gold/50">30 días</button>
        </div>
        <div class="flex items-center gap-2 mt-2">
          <input type="number" id="ban-days" min="1" max="3650" placeholder="O escribí los días"
                 class="input-gold flex-1 bg-ink-700 border border-ink-600 rounded-xl px-3 py-2 text-sm text-cream-light focus:outline-none">
          <span class="text-xs text-ink-500">días</span>
        </div>
        <p class="text-xs text-ink-500 mt-1">Dejá en 0 para suspensión indefinida.</p>
      </div>
    </div>
    <div class="flex gap-3 mt-6">
      <button onclick="closeBanModal()" class="flex-1 px-4 py-2.5 border border-ink-600 text-ink-300 rounded-xl text-sm hover:border-ink-500">Cancelar</button>
      <button onclick="confirmBan()" id="ban-confirm-btn" class="flex-1 px-4 py-2.5 bg-red-700 hover:bg-red-600 text-white font-semibold rounded-xl text-sm">Confirmar</button>
    </div>
  </div>
</div>

<script>
const BASE = <?= json_encode(base_path()) ?>;
let _banUserId = null, _banAction = null;

function openBanModal(id, action) {
  _banUserId  = id;
  _banAction  = action;
  document.getElementById('ban-reason').value = '';
  document.getElementById('ban-days').value   = '';
  document.getElementById('ban-days-wrap').classList.toggle('hidden', action === 'banned');
  document.getElementById('ban-modal-title').textContent =
    action === 'banned' ? 'Banear usuario permanentemente' : 'Suspender usuario';
  document.getElementById('ban-confirm-btn').className =
    action === 'banned'
      ? 'flex-1 px-4 py-2.5 bg-red-700 hover:bg-red-600 text-white font-semibold rounded-xl text-sm'
      : 'flex-1 px-4 py-2.5 bg-amber-600 hover:bg-amber-500 text-white font-semibold rounded-xl text-sm';
  const m = document.getElementById('ban-modal');
  m.classList.remove('hidden'); m.classList.add('flex');
}
function closeBanModal() {
  const m = document.getElementById('ban-modal');
  m.classList.add('hidden'); m.classList.remove('flex');
}
function setDays(n) {
  document.getElementById('ban-days').value = n;
  document.querySelectorAll('.day-btn').forEach(b => {
    b.classList.toggle('border-gold/50', parseInt(b.textContent) === n || b.textContent.includes(n+' '));
    b.classList.toggle('text-gold', parseInt(b.textContent) === n || b.textContent.includes(n+' '));
  });
}
async function confirmBan() {
  const reason = document.getElementById('ban-reason').value.trim();
  const days   = parseInt(document.getElementById('ban-days').value) || 0;
  const fd = new FormData();
  fd.append('action', _banAction);
  if (reason) fd.append('reason', reason);
  if (days > 0) fd.append('days', days);
  const res  = await fetch(`${BASE}/admin/usuarios/${_banUserId}/status`, {method:'POST', body:fd});
  const data = await res.json();
  if (data.success) { closeBanModal(); setTimeout(() => location.reload(), 400); }
  else alert(data.error || 'Error');
}
async function setStatus(id, status) {
  if (!confirm('¿Activar este usuario?')) return;
  const fd = new FormData(); fd.append('action', status);
  await fetch(`${BASE}/admin/usuarios/${id}/status`, {method:'POST', body:fd});
  setTimeout(() => location.reload(), 400);
}
async function deleteUser(id, name) {
  if (!confirm(`¿Eliminar permanentemente la cuenta de ${name}?\n\nSus reseñas y turnos completados se conservan. Esta acción no se puede deshacer.`)) return;
  const fd = new FormData(); fd.append('action', 'delete');
  const res  = await fetch(`${BASE}/admin/usuarios/${id}/status`, {method:'POST', body:fd});
  const data = await res.json();
  if (data.success) setTimeout(() => location.reload(), 400);
  else alert(data.error || 'Error al eliminar');
}
</script>
