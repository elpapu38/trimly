<?php /* app/Views/booking/step3.php */
$currentStep = 3;
$basePath    = base_path(); // ← resuelve subfolder si aplica
?>
<div class="min-h-screen bg-ink-900 py-10">
<div class="max-w-3xl mx-auto px-4">

  <?php include __DIR__ . '/partials/steps.php'; ?>

  <!-- Resumen -->
  <div class="grid grid-cols-2 gap-3 mb-8">
    <div class="p-3 bg-ink-800 rounded-xl border border-ink-600">
      <p class="text-xs text-ink-400">Servicio</p>
      <p class="text-cream-light font-medium text-sm"><?= e($booking['service']['name']) ?></p>
      <p class="text-gold text-sm"><?= money((float)$booking['service']['price']) ?> · <?= duracionTexto((int)$booking['service']['duration_min']) ?></p>
    </div>
    <div class="p-3 bg-ink-800 rounded-xl border border-ink-600">
      <p class="text-xs text-ink-400">Profesional</p>
      <p class="text-cream-light font-medium text-sm"><?= e($booking['employee_name']) ?></p>
    </div>
  </div>

  <h1 class="font-display text-2xl text-cream-light mb-6">¿Cuándo querés tu turno?</h1>

  <!-- Selector de fecha -->
  <div class="mb-6">
    <label class="block text-sm text-ink-300 mb-2">Elegí una fecha</label>
    <input type="date" id="dateInput"
           min="<?= date('Y-m-d') ?>"
           max="<?= date('Y-m-d', strtotime('+60 days')) ?>"
           value="<?= e($booking['date'] ?? date('Y-m-d', strtotime('+1 day'))) ?>"
           class="input-gold w-full bg-ink-800 border border-ink-600 rounded-xl px-4 py-3 text-cream-light">
  </div>

  <!-- Slots -->
  <div id="slotsContainer">
    <div class="text-center py-8 text-ink-400">
      <div class="animate-spin w-6 h-6 border-2 border-gold border-t-transparent rounded-full mx-auto mb-2"></div>
      Cargando horarios disponibles…
    </div>
  </div>

  <form id="slotForm" method="POST" action="<?= path('/reservar/' . e($shop['slug']) . '/horario') ?>" class="hidden">
    <input type="hidden" name="_csrf" value="<?= csrf() ?>">
    <input type="hidden" name="date" id="hiddenDate">
    <input type="hidden" name="start_time" id="hiddenSlot">
  </form>

  <div class="flex justify-start mt-8">
    <a href="<?= path('/reservar/' . e($shop['slug']) . '/empleado') ?>"
       class="px-6 py-3 border border-ink-600 text-ink-300 rounded-full hover:border-gold/50 transition-colors">
      ← Atrás
    </a>
  </div>

</div>
</div>

<script>
const BASE       = <?= json_encode($basePath) ?>;
const empIdRaw   = <?= (int)$booking['employee_id'] ?>;
const serviceId  = <?= (int)$booking['service']['id'] ?>;
const shopSlug   = <?= json_encode($shop['slug']) ?>;
const dateInput  = document.getElementById('dateInput');
const container  = document.getElementById('slotsContainer');

let resolvedEmpId = empIdRaw;

// Si employee_id=0 ("cualquiera"), resolver el primer empleado
// disponible para el servicio antes de pedir los slots
async function resolveEmployee() {
    if (empIdRaw !== 0) return empIdRaw;
    try {
        const url  = `${BASE}/api/empleados-para-servicio?shop_slug=${encodeURIComponent(shopSlug)}&service_id=${serviceId}`;
        const res  = await fetch(url);
        const data = await res.json();
        if (data.employees && data.employees.length > 0) {
            return parseInt(data.employees[0].id);
        }
    } catch(e) {
        console.error('Error resolviendo empleado:', e);
    }
    return 0;
}

async function loadSlots(date) {
    container.innerHTML = `<div class="text-center py-8 text-ink-400">
        <div class="animate-spin w-6 h-6 border-2 border-gold border-t-transparent rounded-full mx-auto mb-2"></div>
        Cargando horarios…</div>`;

    if (resolvedEmpId === 0) {
        resolvedEmpId = await resolveEmployee();
    }

    if (!resolvedEmpId) {
        container.innerHTML = '<p class="text-red-400 text-center py-6 bg-red-500/10 border border-red-500/20 rounded-xl p-4">No hay profesionales disponibles para este servicio.</p>';
        return;
    }

    try {
        const url  = `${BASE}/api/slots?employee_id=${resolvedEmpId}&service_id=${serviceId}&date=${date}`;
        const res  = await fetch(url);
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const data = await res.json();
        if ((data.slots || []).length === 0) {
            // Find next available date (search up to 60 days ahead)
            findNextAvailable(date, resolvedEmpId);
        } else {
            renderSlots(data.slots, date);
        }
    } catch(e) {
        console.error('Error cargando slots:', e);
        container.innerHTML = '<p class="text-red-400 text-center py-6 bg-red-500/10 border border-red-500/20 rounded-xl p-4">Error al cargar horarios. Intentá de nuevo.</p>';
    }
}

async function findNextAvailable(fromDate, empId) {
    container.innerHTML = `<div class="text-center py-8 text-ink-400">
        <div class="animate-spin w-6 h-6 border-2 border-gold border-t-transparent rounded-full mx-auto mb-2"></div>
        Buscando próxima fecha disponible…</div>`;

    let d = new Date(fromDate + 'T12:00:00');
    for (let i = 1; i <= 60; i++) {
        d.setDate(d.getDate() + 1);
        const iso = d.toISOString().split('T')[0];
        try {
            const r    = await fetch(`${BASE}/api/slots?employee_id=${empId}&service_id=${serviceId}&date=${iso}`);
            const data = await r.json();
            if ((data.slots||[]).length > 0) {
                // Show friendly recommendation WITHOUT auto-jumping
                container.innerHTML = `
                <div class="py-8 text-center">
                  <div class="inline-flex flex-col items-center gap-3 bg-ink-800 border border-ink-700 rounded-2xl px-6 py-5 max-w-xs mx-auto">
                    <span class="text-3xl">📅</span>
                    <div>
                      <p class="text-cream-light font-semibold text-sm">Sin turnos para esta fecha</p>
                      <p class="text-ink-400 text-xs mt-1">La próxima fecha disponible es:</p>
                      <p class="text-gold font-bold text-lg mt-1">${formatDateES(iso)}</p>
                    </div>
                    <button onclick="jumpToDate('${iso}')"
                            class="w-full bg-gold hover:bg-gold-500 text-ink-900 font-bold py-2.5 px-4 rounded-xl text-sm transition-colors">
                      Ir a esa fecha
                    </button>
                    <p class="text-xs text-ink-500">O elegí otra fecha en el calendario</p>
                  </div>
                </div>`;
                return;
            }
        } catch(e) { /* skip */ }
    }
    container.innerHTML = `<div class="text-center py-12 border border-ink-700 rounded-xl bg-ink-800/40">
        <div class="text-4xl mb-3 opacity-30">📅</div>
        <p class="text-lg font-medium text-cream-light/60">Sin disponibilidad próxima</p>
        <p class="text-sm mt-1 text-ink-500">No hay horarios en los próximos 60 días</p>
    </div>`;
}

function jumpToDate(iso) {
    dateInput.value = iso;
    loadSlots(iso);
}

function renderSlots(slots, date, jumped = false) {
    if (!slots.length) {
        container.innerHTML = `<div class="text-center py-12 border border-ink-700 rounded-xl bg-ink-800/40">
            <div class="text-4xl mb-3 opacity-30">📅</div>
            <p class="text-lg font-medium text-cream-light/60">Sin horarios disponibles</p>
            <p class="text-sm mt-1 text-ink-500">Probá eligiendo otra fecha</p>
        </div>`;
        return;
    }
    const jumpedBanner = jumped
        ? `<div class="mb-4 p-3 bg-gold/10 border border-gold/30 rounded-xl text-sm text-gold flex items-center gap-2">
            <span>📅</span>
            <span>No había turnos para la fecha anterior. Próxima fecha disponible: <strong>${formatDateES(date)}</strong></span>
           </div>`
        : '';

    const morning   = slots.filter(s => s.start < '12:00');
    const afternoon = slots.filter(s => s.start >= '12:00' && s.start < '17:00');
    const evening   = slots.filter(s => s.start >= '17:00');
    const groups    = [
        { label: '🌤 Mañana',  items: morning },
        { label: '☀️ Tarde',   items: afternoon },
        { label: '🌙 Noche',   items: evening },
    ];

    let html = jumpedBanner || '';
    for (const g of groups) {
        if (!g.items.length) continue;
        html += `<div class="mb-6">
            <h3 class="text-xs font-semibold uppercase tracking-widest text-ink-400 mb-3">${g.label}</h3>
            <div class="flex flex-wrap gap-2">`;
        for (const slot of g.items) {
            html += `<button type="button"
                onclick="selectSlot('${slot.start}','${date}',this)"
                class="slot-btn px-4 py-2.5 rounded-full border border-ink-600 text-cream-light text-sm
                       hover:border-gold hover:text-gold hover:bg-gold/5 transition-all font-medium">
                ${slot.start}
            </button>`;
        }
        html += `</div></div>`;
    }
    container.innerHTML = html;
}

function selectSlot(start, date, btn) {
    document.querySelectorAll('.slot-btn').forEach(b => {
        b.classList.remove('border-gold','text-gold','bg-gold/10','ring-2','ring-gold/30');
    });
    btn.classList.add('border-gold','text-gold','bg-gold/10','ring-2','ring-gold/30');

    document.getElementById('hiddenDate').value = date;
    document.getElementById('hiddenSlot').value = start;

    setTimeout(() => document.getElementById('slotForm').submit(), 280);
}

function formatDateES(iso) {
    const [y,m,d] = iso.split('-');
    const months = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    return `${parseInt(d)} de ${months[parseInt(m)-1]} de ${y}`;
}

dateInput.addEventListener('change', e => loadSlots(e.target.value));
loadSlots(dateInput.value);
</script>
