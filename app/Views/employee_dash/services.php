<?php /* app/Views/employee_dash/services.php */ ?>
<div class="flex min-h-screen pt-16 pb-20 lg:pb-6">
  <?php require __DIR__ . '/partials/sidebar.php'; ?>
  <main class="flex-1 p-4 sm:p-6 lg:p-8 lg:ml-56 max-w-2xl">

    <h1 class="font-display text-2xl font-bold text-cream-light mb-2">Mis servicios</h1>
    <p class="text-ink-400 text-sm mb-6">Elegí qué servicios ofrecés vos específicamente.</p>

    <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5 sm:p-6">
      <p id="svc-msg" class="text-sm mb-4 hidden"></p>

      <?php if (empty($allServices)): ?>
      <p class="text-ink-500 text-sm text-center py-6">El local todavía no tiene servicios cargados.</p>
      <?php else: ?>
      <div class="space-y-2 mb-6" id="services-list">
        <?php foreach ($allServices as $s): ?>
        <label class="flex items-start gap-3 p-3 rounded-xl border cursor-pointer transition-all
                      <?= in_array($s['id'], $assignedIds) ? 'border-gold/40 bg-gold/5' : 'border-ink-600 hover:border-ink-500' ?>">
          <input type="checkbox" name="service_ids[]" value="<?= $s['id'] ?>"
                 <?= in_array($s['id'], $assignedIds) ? 'checked' : '' ?>
                 onchange="toggleHighlight(this)"
                 class="mt-0.5 accent-gold shrink-0">
          <div class="flex-1 min-w-0">
            <p class="text-cream-light text-sm font-medium"><?= e($s['name']) ?></p>
            <p class="text-ink-400 text-xs"><?= money((float)$s['price']) ?> · <?= duracionTexto((int)$s['duration_min']) ?></p>
          </div>
        </label>
        <?php endforeach; ?>
      </div>
      <button onclick="saveServices()"
              class="w-full bg-gold hover:bg-gold-500 text-ink-900 font-bold py-3 rounded-xl text-sm transition-colors">
        Guardar cambios
      </button>
      <?php endif; ?>
    </div>
  </main>
</div>

<script>
const BASE_PATH = <?= json_encode(base_path()) ?>;
const CSRF      = <?= json_encode(csrf()) ?>;

function toggleHighlight(cb) {
  const label = cb.closest('label');
  if (cb.checked) {
    label.classList.replace('border-ink-600','border-gold/40');
    label.classList.add('bg-gold/5');
  } else {
    label.classList.replace('border-gold/40','border-ink-600');
    label.classList.remove('bg-gold/5');
  }
}

async function saveServices() {
  const msg    = document.getElementById('svc-msg');
  const checks = [...document.querySelectorAll('#services-list input:checked')];
  const fd     = new FormData();
  fd.append('_csrf', CSRF);
  checks.forEach(c => fd.append('service_ids[]', c.value));

  const res  = await fetch(`${BASE_PATH}/mi-panel/servicios`, {method:'POST', body:fd});
  const data = await res.json();
  msg.textContent  = data.success ? `✓ Guardado — ${data.assigned} servicio(s) asignados.` : (data.error || 'Error.');
  msg.className    = `text-sm mb-4 ${data.success ? 'text-emerald-400' : 'text-red-400'}`;
  msg.classList.remove('hidden');
}
</script>
