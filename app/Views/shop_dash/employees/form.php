<?php
$isEdit     = !empty($employee['id']);
$errors     = $_SESSION['form_errors'] ?? [];
$old        = $_SESSION['form_old']    ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_old']);
$action     = $isEdit ? path("/panel/empleados/{$employee['id']}") : path('/panel/empleados');
$days       = diasSemana();
$hoursByDay = [];
foreach (($hours ?? []) as $h) $hoursByDay[(int)$h['day_of_week']] = $h;
?>
<div class="flex min-h-screen pt-16 pb-20 lg:pb-6">
  <?php require __DIR__ . '/../partials/sidebar.php'; ?>
  <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden min-w-0">

    <div class="flex items-center gap-3 mb-5">
      <a href="<?= path('/panel/empleados') ?>" class="text-ink-400 hover:text-gold text-sm">← Volver</a>
      <h1 class="font-display text-xl font-bold text-cream-light">
        <?= $isEdit ? 'Editar — '.e($employee['name']) : 'Nuevo profesional' ?>
      </h1>
    </div>

    <form method="POST" action="<?= $action ?>" enctype="multipart/form-data" class="space-y-5 max-w-3xl">
      <input type="hidden" name="_csrf" value="<?= csrf() ?>">

      <!-- Datos básicos -->
      <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5">
        <h2 class="font-semibold text-cream-light mb-4 text-sm">Datos del profesional</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs text-ink-400 mb-1">Nombre completo *</label>
            <input type="text" name="name" required value="<?= e($old['name'] ?? $employee['name'] ?? '') ?>"
                   class="input-gold w-full bg-ink-700 border <?=isset($errors['name'])?'border-red-500':'border-ink-600'?> rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
            <?php if(isset($errors['name'])): ?><p class="text-red-400 text-xs mt-1"><?= e($errors['name'][0]) ?></p><?php endif; ?>
          </div>
          <div>
            <label class="block text-xs text-ink-400 mb-1">Especialidad <span class="text-ink-600">(ej: Fade, Colorista)</span></label>
            <input type="text" name="specialty" value="<?= e($old['specialty'] ?? $employee['specialty'] ?? '') ?>"
                   placeholder="Ej: Fade, Barba clásica"
                   class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
          </div>
          <div class="sm:col-span-2">
            <label class="block text-xs text-ink-400 mb-1">Bio</label>
            <textarea name="bio" rows="3"
                      class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light resize-none focus:outline-none"><?= e($old['bio'] ?? $employee['bio'] ?? '') ?></textarea>
          </div>
          <div>
            <label class="block text-xs text-ink-400 mb-1">Instagram <span class="text-ink-600">(sin @)</span></label>
            <div class="relative">
              <span class="absolute left-3 top-1/2 -translate-y-1/2 text-ink-400 text-sm">@</span>
              <input type="text" name="instagram" value="<?= e($old['instagram'] ?? $employee['instagram'] ?? '') ?>"
                     class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl pl-7 pr-4 py-2.5 text-sm text-cream-light focus:outline-none">
            </div>
          </div>
          <div>
            <label class="block text-xs text-ink-400 mb-1">Orden de aparición</label>
            <input type="number" name="sort_order" min="0" value="<?= e($old['sort_order'] ?? $employee['sort_order'] ?? 0) ?>"
                   class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
          </div>
          <?php if($isEdit): ?>
          <div>
            <label class="block text-xs text-ink-400 mb-1">Estado</label>
            <select name="status" class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
              <option value="active"   <?=($employee['status']??'')==='active'  ?'selected':''?>>Activo</option>
              <option value="inactive" <?=($employee['status']??'')==='inactive'?'selected':''?>>Inactivo</option>
            </select>
          </div>
          <?php endif; ?>
          <div>
            <label class="block text-xs text-ink-400 mb-1">Foto de perfil</label>
            <?php if(!empty($employee['avatar'])): ?>
            <img src="<?= upload_url($employee['avatar']) ?>" class="w-14 h-14 rounded-full object-cover mb-2 border border-ink-600">
            <?php endif; ?>
            <input type="file" name="avatar" accept="image/*"
                   class="w-full text-ink-400 text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-gold/20 file:text-gold hover:file:bg-gold/30 cursor-pointer">
          </div>
        </div>
      </div>

      <!-- Servicios -->
      <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5">
        <h2 class="font-semibold text-cream-light mb-1 text-sm">Servicios que realiza</h2>
        <p class="text-xs text-ink-500 mb-4">Seleccioná los servicios que este profesional puede brindar</p>
        <?php if(empty($services)): ?>
        <div class="text-center py-6">
          <p class="text-sm text-ink-500 mb-3">No hay servicios creados todavía.</p>
          <a href="<?= path('/panel/servicios/nuevo') ?>" class="text-sm text-gold hover:underline">→ Crear primer servicio</a>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
          <?php foreach($services as $svc): ?>
          <label class="flex items-center gap-3 p-3 bg-ink-700 rounded-xl cursor-pointer hover:bg-ink-600 transition-colors group">
            <input type="checkbox" name="service_ids[]" value="<?= $svc['id'] ?>"
                   <?= in_array((string)$svc['id'], array_map('strval', $assigned ?? [])) ? 'checked' : '' ?>
                   class="w-4 h-4 accent-gold shrink-0">
            <div class="min-w-0 flex-1">
              <p class="text-sm text-cream-light group-hover:text-white truncate"><?= e($svc['name']) ?></p>
              <p class="text-xs text-ink-400"><?= money((float)($svc['price']??0)) ?> · <?= duracionTexto((int)($svc['duration_min']??30)) ?></p>
            </div>
          </label>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- Horarios -->
      <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5">
        <h2 class="font-semibold text-cream-light mb-1 text-sm">Horarios del profesional</h2>
        <p class="text-xs text-ink-500 mb-4">Dejá sin marcar los días para heredar el horario del local</p>
        <div class="space-y-2">
          <?php for($d=0;$d<=6;$d++):
            $h   = $hoursByDay[$d] ?? [];
            $has = !empty($h['opens_at']);
          ?>
          <div class="flex flex-wrap items-center gap-2 p-3 bg-ink-700/50 rounded-xl">
            <label class="flex items-center gap-2 cursor-pointer shrink-0 w-28">
              <input type="checkbox" name="emp_days[<?=$d?>]" value="1" <?=$has?'checked':''?>
                     class="w-4 h-4 accent-gold" onchange="toggleEmpDay(this,<?=$d?>)">
              <span class="text-sm text-cream-light"><?=$days[$d]?></span>
            </label>
            <div id="empday-<?=$d?>" class="flex flex-wrap items-center gap-2 <?=$has?'':'opacity-40 pointer-events-none'?>">
              <div class="flex items-center gap-1.5">
                <span class="text-xs text-ink-500">De</span>
                <input type="time" name="emp_opens[<?=$d?>]" value="<?= e($h['opens_at']??'09:00') ?>"
                       class="input-gold bg-ink-800 border border-ink-600 rounded-lg px-2.5 py-1.5 text-cream-light text-xs focus:outline-none">
              </div>
              <div class="flex items-center gap-1.5">
                <span class="text-xs text-ink-500">a</span>
                <input type="time" name="emp_closes[<?=$d?>]" value="<?= e($h['closes_at']??'18:00') ?>"
                       class="input-gold bg-ink-800 border border-ink-600 rounded-lg px-2.5 py-1.5 text-cream-light text-xs focus:outline-none">
              </div>
            </div>
            <?php if(!$has): ?><span class="text-xs text-ink-600 italic">Usa horario del local</span><?php endif; ?>
          </div>
          <?php endfor; ?>
        </div>
      </div>

      <div class="flex flex-col sm:flex-row gap-3 pb-4">
        <a href="<?= path('/panel/empleados') ?>"
           class="flex-1 sm:flex-none text-center px-6 py-3 border border-ink-600 text-ink-300 rounded-xl hover:border-gold/50 transition-colors text-sm">
          Cancelar
        </a>
        <button type="submit"
                class="flex-1 sm:flex-none bg-gold hover:bg-gold-500 text-ink-900 font-bold px-8 py-3 rounded-xl transition-colors text-sm">
          <?= $isEdit ? 'Guardar cambios' : 'Crear profesional' ?>
        </button>
      </div>
    </form>

    <!-- ── Vincular cuenta de acceso al panel ── -->
    <?php if ($isEdit): ?>
    <div class="mt-6 bg-ink-800 border border-ink-700 rounded-2xl p-5">
      <h3 class="font-semibold text-cream-light mb-1">Cuenta de acceso</h3>
      <p class="text-xs text-ink-400 mb-4">
        Vinculá un usuario registrado para que este empleado pueda iniciar sesión en su propio panel.
      </p>

      <?php if ($employee['user_id']): ?>
        <!-- Already linked -->
        <?php
          $db2 = \App\Models\Database::getInstance();
          $us  = $db2->prepare("SELECT name, email FROM users WHERE id = ? LIMIT 1");
          $us->execute([$employee['user_id']]);
          $linkedUser = $us->fetch();
        ?>
        <div class="flex items-center justify-between bg-ink-700/50 border border-ink-600 rounded-xl px-4 py-3">
          <div>
            <p class="text-cream-light text-sm font-medium"><?= e($linkedUser['name'] ?? '') ?></p>
            <p class="text-ink-400 text-xs"><?= e($linkedUser['email'] ?? '') ?></p>
          </div>
          <button onclick="unlinkAccount(<?= $employee['id'] ?>)"
                  class="text-xs text-red-400 hover:text-red-300 border border-red-500/30 hover:border-red-400/50 px-3 py-1.5 rounded-lg transition-all">
            Desvincular
          </button>
        </div>
        <p id="link-msg" class="text-xs mt-2 hidden"></p>
      <?php else: ?>
        <!-- Not linked yet -->
        <div class="flex gap-2">
          <input type="email" id="link-email" placeholder="email@usuario.com"
                 class="input-gold flex-1 bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
          <button onclick="linkAccount(<?= $employee['id'] ?>)"
                  class="px-4 py-2.5 bg-ink-700 border border-ink-500 hover:border-gold/50 text-cream-light rounded-xl text-sm font-semibold transition-all">
            Vincular
          </button>
        </div>
        <p class="text-xs text-ink-500 mt-2">El usuario debe estar registrado en Trimly. Se le asignará el rol de empleado automáticamente.</p>
        <p id="link-msg" class="text-xs mt-2 hidden"></p>
      <?php endif; ?>
    </div>
    <?php endif; ?>

  </main>
</div>

<script>
const BASE_PATH = <?= json_encode(base_path()) ?>;

function toggleEmpDay(cb, d) {
  const el = document.getElementById('empday-'+d);
  el.classList.toggle('opacity-40',          !cb.checked);
  el.classList.toggle('pointer-events-none', !cb.checked);
}

async function linkAccount(empId) {
  const email = document.getElementById('link-email')?.value?.trim();
  const msg   = document.getElementById('link-msg');
  if (!email) { showMsg(msg, 'Ingresá el email del usuario.', false); return; }

  const fd = new FormData();
  fd.append('email', email);
  const res  = await fetch(`${BASE_PATH}/panel/empleados/${empId}/vincular`, {method:'POST', body:fd});
  const data = await res.json();
  if (data.success) {
    showMsg(msg, `✓ Cuenta de ${data.user_name} vinculada. Recargá la página.`, true);
    setTimeout(() => location.reload(), 1500);
  } else {
    showMsg(msg, data.error || 'Error al vincular.', false);
  }
}

async function unlinkAccount(empId) {
  if (!confirm('¿Desvincular la cuenta? El usuario volverá a rol cliente.')) return;
  const msg  = document.getElementById('link-msg');
  const res  = await fetch(`${BASE_PATH}/panel/empleados/${empId}/desvincular`, {method:'POST'});
  const data = await res.json();
  if (data.success) {
    showMsg(msg, '✓ Cuenta desvinculada.', true);
    setTimeout(() => location.reload(), 1000);
  } else {
    showMsg(msg, data.error || 'Error al desvincular.', false);
  }
}

function showMsg(el, text, ok) {
  el.textContent = text;
  el.className   = `text-xs mt-2 ${ok ? 'text-emerald-400' : 'text-red-400'}`;
  el.classList.remove('hidden');
}
</script>
