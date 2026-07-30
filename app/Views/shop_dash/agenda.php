<?php $pageTitle = $pageTitle ?? 'Agenda'; ?>
<div class="flex min-h-screen pt-16 pb-20 lg:pb-6">
  <?php require __DIR__ . '/partials/sidebar.php'; ?>
  <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden min-w-0">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
      <h1 class="font-display text-xl font-bold text-cream-light">Agenda</h1>
      <div class="flex items-center gap-2">
        <button id="prev-btn" class="p-2 bg-ink-700 hover:bg-ink-600 text-ink-200 rounded-xl transition-colors">‹</button>
        <span id="week-label" class="text-sm font-medium text-cream-light min-w-[160px] text-center"></span>
        <button id="next-btn" class="p-2 bg-ink-700 hover:bg-ink-600 text-ink-200 rounded-xl transition-colors">›</button>
        <button id="today-btn" class="text-xs px-3 py-2 bg-gold/15 text-gold border border-gold/20 rounded-xl hover:bg-gold/25 transition-colors">Hoy</button>
      </div>
    </div>

    <!-- Filtro empleado -->
    <?php if(count($employees) > 1): ?>
    <div class="flex gap-2 mb-4 overflow-x-auto pb-1">
      <button onclick="setEmployee(0)" id="emp-0"
              class="emp-btn shrink-0 text-xs px-3 py-2 rounded-xl border border-gold/30 bg-gold/15 text-gold transition-colors">
        Todos
      </button>
      <?php foreach($employees as $e): ?>
      <button onclick="setEmployee(<?=$e['id']?>)" id="emp-<?=$e['id']?>"
              class="emp-btn shrink-0 text-xs px-3 py-2 rounded-xl border border-ink-600 text-ink-300 hover:border-gold/30 hover:text-gold transition-colors">
        <?= e($e['name']) ?>
      </button>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Vista semana (desktop) / Vista día (mobile) -->
    <div class="bg-ink-800 border border-ink-700 rounded-2xl overflow-hidden">

      <!-- Header días -->
      <div id="week-header" class="hidden sm:grid border-b border-ink-700 bg-ink-700/40">
        <!-- generado por JS -->
      </div>

      <!-- Mobile: selector de día -->
      <div id="day-selector" class="sm:hidden flex overflow-x-auto border-b border-ink-700 bg-ink-700/30">
        <!-- generado por JS -->
      </div>

      <!-- Contenido -->
      <div id="agenda-content" class="min-h-[400px]">
        <div class="flex items-center justify-center py-16">
          <div class="w-8 h-8 border-2 border-gold border-t-transparent rounded-full animate-spin"></div>
        </div>
      </div>
    </div>

    <!-- Leyenda -->
    <div class="flex flex-wrap gap-3 mt-4">
      <?php foreach(['pending'=>['bg-amber-400','Pendiente'],'confirmed'=>['bg-emerald-400','Confirmado'],'completed'=>['bg-blue-400','Completado'],'no_show'=>['bg-ink-500','No asistió'],'cancelled_client'=>['bg-red-400','Cancelado']] as $s=>[$c,$l]): ?>
      <div class="flex items-center gap-1.5 text-xs text-ink-400">
        <span class="w-2.5 h-2.5 rounded-full <?=$c?>"></span><?=$l?>
      </div>
      <?php endforeach; ?>
    </div>

  </main>
</div>

<!-- Modal detalle de turno -->
<div id="appt-modal" class="fixed inset-0 bg-black/70 z-50 hidden items-center justify-center p-4">
  <div class="bg-ink-800 border border-ink-700 rounded-2xl w-full max-w-sm p-6">
    <div class="flex items-start justify-between mb-4">
      <h3 class="font-semibold text-cream-light" id="modal-title">Turno</h3>
      <button onclick="closeModal()" class="text-ink-400 hover:text-cream text-lg leading-none">✕</button>
    </div>
    <div id="modal-body" class="space-y-2 text-sm mb-5"></div>
    <div class="flex gap-2" id="modal-actions"></div>
  </div>
</div>

<script>
const BASE = '<?= path('/') ?>';
const API  = '<?= path('/panel/agenda/api') ?>';
let currentWeekStart = getMonday(new Date());
let currentEmpId     = 0;
let currentDayIndex  = new Date().getDay(); // for mobile
let allEvents        = [];

const statusColors = {
  pending:          'bg-amber-400/20 border-amber-400/40 text-amber-300',
  confirmed:        'bg-emerald-400/20 border-emerald-400/40 text-emerald-300',
  completed:        'bg-blue-400/20 border-blue-400/40 text-blue-300',
  no_show:          'bg-ink-600/50 border-ink-500/40 text-ink-400',
  cancelled_client: 'bg-red-400/10 border-red-400/30 text-red-400',
  cancelled_shop:   'bg-red-400/10 border-red-400/30 text-red-400',
};
const statusLabels = {
  pending:'Pendiente', confirmed:'Confirmado', completed:'Completado',
  no_show:'No asistió', cancelled_client:'Cancelado cliente', cancelled_shop:'Cancelado local'
};

function getMonday(d) {
  const day = d.getDay(), diff = d.getDate() - day + (day === 0 ? -6 : 1);
  const m = new Date(d); m.setDate(diff); m.setHours(0,0,0,0); return m;
}

function fmtDate(d) {
  return d.toISOString().split('T')[0];
}

function addDays(d, n) {
  const r = new Date(d); r.setDate(r.getDate()+n); return r;
}

function setEmployee(id) {
  currentEmpId = id;
  document.querySelectorAll('.emp-btn').forEach(b => {
    const active = b.id === 'emp-'+id;
    b.classList.toggle('bg-gold/15', active); b.classList.toggle('text-gold', active);
    b.classList.toggle('border-gold/30', active);
    b.classList.toggle('bg-transparent', !active); b.classList.toggle('text-ink-300', !active);
    b.classList.toggle('border-ink-600', !active);
  });
  loadWeek();
}

async function loadWeek() {
  const start = fmtDate(currentWeekStart);
  const end   = fmtDate(addDays(currentWeekStart, 6));
  const url   = `${API}?start=${start}&end=${end}${currentEmpId ? '&employee_id='+currentEmpId : ''}`;
  document.getElementById('agenda-content').innerHTML = '<div class="flex items-center justify-center py-16"><div class="w-8 h-8 border-2 border-gold border-t-transparent rounded-full animate-spin"></div></div>';
  try {
    const r = await fetch(url); allEvents = await r.json(); render();
  } catch(e) { document.getElementById('agenda-content').innerHTML = '<p class="text-center text-ink-500 py-10">Error al cargar turnos.</p>'; }
}

function render() {
  const isMobile = window.innerWidth < 640;
  isMobile ? renderMobile() : renderDesktop();
  updateLabels();
}

function updateLabels() {
  const end = addDays(currentWeekStart, 6);
  const opts = {day:'numeric',month:'short'};
  document.getElementById('week-label').textContent =
    currentWeekStart.toLocaleDateString('es-AR',opts) + ' — ' + end.toLocaleDateString('es-AR',opts);
}

function renderDesktop() {
  const days = Array.from({length:7}, (_,i) => addDays(currentWeekStart,i));
  const today = fmtDate(new Date());

  // Header
  const hdr = document.getElementById('week-header');
  hdr.className = 'hidden sm:grid border-b border-ink-700 bg-ink-700/40';
  hdr.style.gridTemplateColumns = 'repeat(7,1fr)';
  hdr.innerHTML = days.map(d => {
    const ds = fmtDate(d); const isT = ds===today;
    return `<div class="p-3 text-center border-r border-ink-700/50 last:border-0">
      <p class="text-[10px] uppercase text-ink-500">${d.toLocaleDateString('es-AR',{weekday:'short'})}</p>
      <p class="text-sm font-semibold ${isT?'text-gold':'text-cream-light'}">${d.getDate()}</p>
    </div>`;
  }).join('');

  // Grid
  const grid = document.getElementById('agenda-content');
  grid.style.display = 'grid';
  grid.style.gridTemplateColumns = 'repeat(7,1fr)';
  grid.innerHTML = '';

  days.forEach(d => {
    const ds = fmtDate(d); const isT = ds===today;
    const dayEvents = allEvents.filter(e => e.start.startsWith(ds))
      .sort((a,b) => a.start.localeCompare(b.start));
    const col = document.createElement('div');
    col.className = `min-h-[200px] p-1.5 border-r border-ink-700/30 last:border-0 ${isT?'bg-gold/3':''}`;
    if(dayEvents.length === 0) {
      col.innerHTML = '<p class="text-center text-ink-700 text-xs mt-6">—</p>';
    } else {
      dayEvents.forEach(ev => { col.appendChild(buildEventCard(ev, true)); });
    }
    grid.appendChild(col);
  });
}

function renderMobile() {
  const days = Array.from({length:7}, (_,i) => addDays(currentWeekStart,i));
  const today = fmtDate(new Date());

  // Day selector
  const sel = document.getElementById('day-selector');
  sel.innerHTML = days.map((d,i) => {
    const ds = fmtDate(d); const isT = ds===today; const isCur = i===currentDayIndex;
    const cnt = allEvents.filter(e=>e.start.startsWith(ds)).length;
    return `<button onclick="selectDay(${i})" class="shrink-0 flex flex-col items-center gap-0.5 px-3 py-2.5 border-b-2 transition-colors ${isCur?'border-gold text-gold':'border-transparent text-ink-400 hover:text-cream-light'}">
      <span class="text-[10px] uppercase">${d.toLocaleDateString('es-AR',{weekday:'short'})}</span>
      <span class="text-sm font-bold ${isT?'text-gold':''}">${d.getDate()}</span>
      ${cnt?`<span class="w-4 h-4 bg-gold text-ink-900 rounded-full text-[9px] font-bold flex items-center justify-center">${cnt}</span>`:'<span class="w-4 h-4"></span>'}
    </button>`;
  }).join('');

  // Content for selected day
  const ds = fmtDate(addDays(currentWeekStart, currentDayIndex));
  const dayEvents = allEvents.filter(e => e.start.startsWith(ds)).sort((a,b)=>a.start.localeCompare(b.start));
  const content = document.getElementById('agenda-content');
  content.style.display = 'block';
  if(dayEvents.length === 0) {
    content.innerHTML = '<div class="text-center py-12"><p class="text-ink-500 text-sm">Sin turnos este día.</p></div>';
  } else {
    content.innerHTML = '<div class="p-3 space-y-2"></div>';
    const wrap = content.firstChild;
    dayEvents.forEach(ev => wrap.appendChild(buildEventCard(ev, false)));
  }
}

function selectDay(i) {
  currentDayIndex = i;
  renderMobile();
}

function buildEventCard(ev, compact) {
  const status = ev.extendedProps?.status || 'pending';
  const cls = statusColors[status] || 'bg-ink-700 border-ink-600 text-ink-300';
  const div = document.createElement('div');
  div.className = `border rounded-xl p-2 cursor-pointer transition-opacity hover:opacity-80 mb-1 ${cls}`;
  const t = ev.start.split('T')[1]?.substring(0,5) || '';
  if(compact) {
    div.innerHTML = `<p class="text-[11px] font-semibold leading-tight">${t}</p>
      <p class="text-[10px] leading-tight truncate">${escH(ev.title)}</p>`;
  } else {
    div.innerHTML = `<div class="flex items-start justify-between gap-2">
      <div>
        <p class="text-xs font-bold">${t} — ${ev.end?.split('T')[1]?.substring(0,5)||''}</p>
        <p class="text-sm font-semibold leading-tight mt-0.5">${escH(ev.title)}</p>
        <p class="text-xs opacity-70 mt-0.5">${escH(ev.extendedProps?.employee||'')}</p>
      </div>
      <span class="text-[10px] px-2 py-0.5 rounded-full bg-black/20 shrink-0">${statusLabels[status]||status}</span>
    </div>`;
  }
  div.onclick = () => openModal(ev);
  return div;
}

function escH(s) { const d=document.createElement('div');d.textContent=s||'';return d.innerHTML; }

function openModal(ev) {
  const p = ev.extendedProps || {};
  const status = p.status || 'pending';
  document.getElementById('modal-title').textContent = ev.title;
  document.getElementById('modal-body').innerHTML = `
    <div class="flex items-center gap-2 mb-1">
      <span class="text-xs px-2 py-0.5 rounded-full border ${statusColors[status]||''}">${statusLabels[status]||status}</span>
    </div>
    <p><span class="text-ink-500">Empleado:</span> <span class="text-cream-light">${escH(p.employee)}</span></p>
    <p><span class="text-ink-500">Horario:</span> <span class="text-cream-light">${ev.start?.split('T')[1]?.substring(0,5)||''} – ${ev.end?.split('T')[1]?.substring(0,5)||''}</span></p>
    ${p.phone?`<p><span class="text-ink-500">Teléfono:</span> <a href="tel:${escH(p.phone)}" class="text-gold">${escH(p.phone)}</a></p>`:''}
    ${p.price?`<p><span class="text-ink-500">Precio:</span> <span class="text-gold font-semibold">$${Number(p.price).toLocaleString('es-AR')}</span></p>`:''}
    ${p.notes?`<div class="mt-2 p-2.5 bg-amber-500/10 border border-amber-500/20 rounded-xl"><p class="text-xs text-amber-400 font-semibold mb-0.5">📝 Nota interna</p><p class="text-xs text-amber-200">${escH(p.notes)}</p></div>`:''}
  `;
  const acts = document.getElementById('modal-actions');
  acts.innerHTML = '';
  if(!['completed','cancelled_client','cancelled_shop'].includes(status)) {
    if(status === 'pending') {
      const btn = document.createElement('button');
      btn.className='flex-1 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold py-2.5 rounded-xl text-sm transition-colors';
      btn.textContent='Confirmar';
      btn.onclick=()=>changeStatus(ev.id,'confirmed');
      acts.appendChild(btn);
    }
    if(['pending','confirmed'].includes(status)) {
      const btn2=document.createElement('button');
      btn2.className='flex-1 bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2.5 rounded-xl text-sm transition-colors';
      btn2.textContent='Completado';
      btn2.onclick=()=>changeStatus(ev.id,'completed');
      acts.appendChild(btn2);
    }
    if(['pending','confirmed'].includes(status)) {
      const btnC=document.createElement('button');
      btnC.className='flex-1 bg-red-700/80 hover:bg-red-700 text-white font-semibold py-2.5 rounded-xl text-sm transition-colors';
      btnC.textContent='Cancelar';
      btnC.onclick=()=>changeStatus(ev.id,'cancelled_shop');
      acts.appendChild(btnC);
    }
    if(status === 'confirmed') {
      const btnN=document.createElement('button');
      btnN.className='flex-1 bg-ink-600 hover:bg-ink-500 text-ink-200 font-semibold py-2.5 rounded-xl text-sm transition-colors';
      btnN.textContent='No asistió';
      btnN.onclick=()=>changeStatus(ev.id,'no_show');
      acts.appendChild(btnN);
    }
  }
  const cls=document.createElement('button');
  cls.className='px-4 py-2.5 bg-ink-700 hover:bg-ink-600 text-ink-200 rounded-xl text-sm transition-colors';
  cls.textContent='Cerrar'; cls.onclick=closeModal;
  acts.appendChild(cls);
  document.getElementById('appt-modal').classList.replace('hidden','flex');
}

async function changeStatus(id, status) {
  closeModal();
  const d = await trimlyAction(`${BASE}panel/turnos/${id}/status`,{status},()=>setTimeout(()=>loadWeek(),800));
}

function closeModal() { document.getElementById('appt-modal').classList.replace('flex','hidden'); }

document.getElementById('prev-btn').onclick  = () => { currentWeekStart=addDays(currentWeekStart,-7); loadWeek(); };
document.getElementById('next-btn').onclick  = () => { currentWeekStart=addDays(currentWeekStart, 7); loadWeek(); };
document.getElementById('today-btn').onclick = () => { currentWeekStart=getMonday(new Date()); currentDayIndex=new Date().getDay(); loadWeek(); };
window.addEventListener('resize', render);
loadWeek();
</script>
