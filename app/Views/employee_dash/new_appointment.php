<?php /* app/Views/employee_dash/new_appointment.php */ ?>
<div class="flex min-h-screen pt-16 pb-20 lg:pb-6">
  <?php require __DIR__ . '/partials/sidebar.php'; ?>
  <main class="flex-1 p-4 sm:p-6 lg:p-8 lg:ml-56 max-w-2xl">

    <h1 class="font-display text-2xl font-bold text-cream-light mb-6">Cargar turno manual</h1>

    <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5 sm:p-6">
      <p id="form-error" class="text-red-400 text-sm mb-4 hidden"></p>
      <p id="form-success" class="text-emerald-400 text-sm mb-4 hidden"></p>

      <div class="space-y-4">
        <!-- Servicio -->
        <div>
          <label class="block text-xs text-ink-400 mb-1">Servicio *</label>
          <?php if (empty($services)): ?>
          <p class="text-amber-400 text-sm bg-amber-500/10 border border-amber-500/20 rounded-xl p-3">
            No tenés servicios asignados. <a href="<?= path('/mi-panel/servicios') ?>" class="underline">Configurá tus servicios primero.</a>
          </p>
          <?php else: ?>
          <select id="f-service" class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
            <option value="">Elegí un servicio</option>
            <?php foreach ($services as $s): ?>
            <option value="<?= $s['id'] ?>" data-duration="<?= $s['duration_min'] ?>" data-price="<?= $s['price'] ?>">
              <?= e($s['name']) ?> — <?= money((float)$s['price'], $employee['currency'] ?? 'ARS') ?> (<?= duracionTexto((int)$s['duration_min']) ?>)
            </option>
            <?php endforeach; ?>
          </select>
          <?php endif; ?>
        </div>

        <!-- Fecha y hora — usando slots reales del sistema -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs text-ink-400 mb-1">Fecha *</label>
            <input type="date" id="f-date" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>"
                   onchange="loadSlots()"
                   class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
          </div>
          <div>
            <label class="block text-xs text-ink-400 mb-1">Horario disponible *</label>
            <select id="f-time"
                    class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
              <option value="">Elegí una fecha y servicio</option>
            </select>
            <p id="slots-status" class="text-xs text-ink-500 mt-1 hidden"></p>
          </div>
        </div>

        <!-- Datos del cliente -->
        <div>
          <label class="block text-xs text-ink-400 mb-1">Nombre del cliente *</label>
          <input type="text" id="f-name" placeholder="Ej: Juan García"
                 class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs text-ink-400 mb-1">Teléfono</label>
            <input type="text" id="f-phone" placeholder="+549..."
                   class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
          </div>
          <div>
            <label class="block text-xs text-ink-400 mb-1">Email</label>
            <input type="email" id="f-email" placeholder="(opcional)"
                   class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
          </div>
        </div>
        <div>
          <label class="block text-xs text-ink-400 mb-1">Notas internas</label>
          <textarea id="f-notes" rows="2" placeholder="Preferencias, aclaraciones…"
                    class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light resize-none focus:outline-none"></textarea>
        </div>

        <button onclick="submitAppointment()" id="submit-btn"
                class="w-full bg-gold hover:bg-gold-500 text-ink-900 font-bold py-3 rounded-xl transition-colors text-sm">
          Guardar turno
        </button>
      </div>
    </div>
  </main>
</div>

<script>
const BASE_PATH = <?= json_encode(base_path()) ?>;
const CSRF      = <?= json_encode(csrf()) ?>;

const EMP_ID   = <?= $employee['id'] ?>;
const BASE_API = <?= json_encode(base_path()) ?>;

async function loadSlots() {
  const serviceId = document.getElementById('f-service').value;
  const date      = document.getElementById('f-date').value;
  const sel       = document.getElementById('f-time');
  const status    = document.getElementById('slots-status');

  sel.innerHTML = '<option value="">Cargando...</option>';
  status.classList.add('hidden');

  if (!serviceId || !date) {
    sel.innerHTML = '<option value="">Elegí servicio y fecha primero</option>';
    return;
  }

  try {
    const res  = await fetch(`${BASE_API}/api/slots?employee_id=${EMP_ID}&service_id=${serviceId}&date=${date}`);
    const data = await res.json();
    if (data.slots && data.slots.length > 0) {
      sel.innerHTML = '<option value="">Elegí un horario</option>' +
        data.slots.map(s => `<option value="${s.start}">${s.start} – ${s.end}</option>`).join('');
      status.classList.add('hidden');
    } else {
      sel.innerHTML = '<option value="">Sin horarios disponibles</option>';
      status.textContent = 'No hay turnos disponibles para esa fecha.';
      status.classList.remove('hidden');
    }
  } catch(e) {
    sel.innerHTML = '<option value="">Error al cargar</option>';
  }
}

// Also load when service changes
document.getElementById('f-service').addEventListener('change', loadSlots);
// Initial load
loadSlots();

async function submitAppointment() {
  const errEl = document.getElementById('form-error');
  const okEl  = document.getElementById('form-success');
  const btn   = document.getElementById('submit-btn');
  errEl.classList.add('hidden');
  okEl.classList.add('hidden');

  const serviceId  = document.getElementById('f-service').value;
  const date       = document.getElementById('f-date').value;
  const time       = document.getElementById('f-time').value;  // comes from select
  const clientName = document.getElementById('f-name').value.trim();

  if (!serviceId || !date || !time || !clientName) {
    errEl.textContent = 'Completá los campos obligatorios (*)';
    errEl.classList.remove('hidden');
    return;
  }

  btn.disabled = true;
  btn.textContent = 'Guardando…';

  const fd = new FormData();
  fd.append('_csrf',        CSRF);
  fd.append('service_id',   serviceId);
  fd.append('date',         date);
  fd.append('start_time',   time);
  fd.append('client_name',  clientName);
  fd.append('client_phone', document.getElementById('f-phone').value.trim());
  fd.append('client_email', document.getElementById('f-email').value.trim());
  fd.append('notes',        document.getElementById('f-notes').value.trim());

  try {
    const res  = await fetch(`${BASE_PATH}/mi-panel/turnos/nuevo`, {method:'POST', body:fd});
    const data = await res.json();
    if (data.success) {
      okEl.textContent = '✓ Turno cargado correctamente.';
      okEl.classList.remove('hidden');
      // Reset form
      document.getElementById('f-service').value = '';
      document.getElementById('f-time').value    = '';
      document.getElementById('f-name').value    = '';
      document.getElementById('f-phone').value   = '';
      document.getElementById('f-email').value   = '';
      document.getElementById('f-notes').value   = '';
    } else {
      errEl.textContent = data.error || 'Error al guardar el turno.';
      errEl.classList.remove('hidden');
    }
  } catch(e) {
    errEl.textContent = 'Error de conexión.';
    errEl.classList.remove('hidden');
  } finally {
    btn.disabled = false;
    btn.textContent = 'Guardar turno';
  }
}
</script>
