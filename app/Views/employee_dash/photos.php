<?php
/* app/Views/employee_dash/photos.php */
$photoCount = count($photos);
$maxPhotos  = 12;
$remaining  = $photoCount - 6;
?>
<div class="flex min-h-screen pt-16 pb-20 lg:pb-6">
  <?php require __DIR__ . '/partials/sidebar.php'; ?>
  <main class="flex-1 p-4 sm:p-6 lg:p-8 lg:ml-56">

    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="font-display text-2xl font-bold text-cream-light">Mis fotos</h1>
        <p class="text-ink-400 text-sm mt-1">
          <?= $photoCount ?>/<?= $maxPhotos ?> fotos subidas. Mostrá tus trabajos en tu perfil del local.
        </p>
      </div>
      <?php if ($photoCount < $maxPhotos): ?>
      <button onclick="document.getElementById('upload-area').classList.toggle('hidden')"
              class="px-4 py-2 bg-gold text-ink-900 font-semibold rounded-xl text-sm hover:bg-gold-300 transition-colors">
        + Subir foto
      </button>
      <?php else: ?>
      <div class="px-4 py-2 bg-ink-700 border border-amber-500/40 text-amber-400 rounded-xl text-xs font-semibold">
        Límite de <?= $maxPhotos ?> fotos alcanzado
      </div>
      <?php endif; ?>
    </div>

    <!-- Upload area -->
    <div id="upload-area" class="hidden mb-6 bg-ink-800 border border-ink-700 rounded-2xl p-5">
      <h3 class="text-sm font-semibold text-cream-light mb-3">Nueva foto</h3>
      <div class="space-y-3">
        <div id="drop-zone"
             class="border-2 border-dashed border-ink-600 rounded-xl p-8 text-center cursor-pointer hover:border-gold/50 transition-colors"
             onclick="document.getElementById('photo-input').click()"
             ondragover="event.preventDefault();this.classList.add('border-gold/50')"
             ondragleave="this.classList.remove('border-gold/50')"
             ondrop="handleDrop(event)">
          <p class="text-ink-400 text-sm">Hacé clic o arrastrá una imagen</p>
          <p class="text-ink-600 text-xs mt-1">JPG, PNG, WebP — máx. 5 MB</p>
          <input type="file" id="photo-input" accept="image/*" class="hidden" onchange="previewFile(this)">
        </div>
        <div id="preview-wrap" class="hidden">
          <img id="preview-img" src="" alt="" class="max-h-48 rounded-xl object-cover">
        </div>
        <input type="text" id="caption-input" placeholder="Descripción del trabajo (opcional)"
               class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
        <div class="flex gap-3">
          <button onclick="uploadPhoto()" id="upload-btn"
                  class="px-5 py-2.5 bg-gold text-ink-900 font-bold rounded-xl text-sm hover:bg-gold-300 transition-colors">Subir</button>
          <button onclick="document.getElementById('upload-area').classList.add('hidden')"
                  class="px-5 py-2.5 border border-ink-600 text-ink-400 rounded-xl text-sm hover:border-ink-500">Cancelar</button>
        </div>
        <p id="upload-msg" class="text-sm hidden"></p>
      </div>
    </div>

    <!-- Gallery — cuadrícula con máx 6 visibles, resto oculto con overlay -->
    <?php if (empty($photos)): ?>
    <div class="text-center py-20 border border-dashed border-ink-700 rounded-2xl">
      <p class="text-4xl mb-3 opacity-30">📷</p>
      <p class="text-ink-400">Todavía no subiste ninguna foto.</p>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-3 gap-2 sm:gap-3" id="gallery">
      <?php foreach ($photos as $idx => $p): ?>
      <?php $isHidden = $idx >= 6; $isLast = $idx === 5 && $remaining > 0; ?>
      <div class="group relative rounded-xl overflow-hidden border border-ink-700 aspect-square <?= $isHidden ? 'hidden' : '' ?>"
           data-id="<?= $p['id'] ?>" data-idx="<?= $idx ?>">

        <img src="<?= upload_url($p['filename']) ?>"
             alt="<?= e($p['caption'] ?? '') ?>"
             class="w-full h-full object-cover cursor-pointer transition-transform duration-200 group-hover:scale-105"
             onclick="openLightbox(<?= $idx ?>)">

        <?php if ($isLast): ?>
        <!-- Overlay "X más" en la 6ta foto si hay más de 6 -->
        <div class="absolute inset-0 bg-ink-900/70 flex items-center justify-center cursor-pointer"
             onclick="showAllPhotos()">
          <span class="text-cream-light text-xl font-bold">+<?= $remaining ?> más</span>
        </div>
        <?php else: ?>
        <?php if ($p['caption']): ?>
        <div class="absolute bottom-0 left-0 right-0 bg-ink-900/80 px-2 py-1.5 text-xs text-cream-light truncate opacity-0 group-hover:opacity-100 transition-opacity">
          <?= e($p['caption']) ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>

        <!-- Delete button -->
        <button onclick="deletePhoto(<?= $p['id'] ?>, this)"
                class="absolute top-2 right-2 w-7 h-7 bg-red-600/80 hover:bg-red-500 text-white rounded-full text-xs flex items-center justify-center
                       opacity-0 group-hover:opacity-100 transition-opacity shadow-md z-10">
          ✕
        </button>
      </div>
      <?php endforeach; ?>
    </div>

    <?php if ($remaining > 0): ?>
    <button id="show-all-btn" onclick="showAllPhotos()"
            class="mt-3 w-full py-2.5 border border-ink-600 hover:border-ink-500 text-ink-400 hover:text-cream text-sm rounded-xl transition-all">
      Ver todas las fotos (<?= $photoCount ?>)
    </button>
    <?php endif; ?>
    <?php endif; ?>

  </main>
</div>

<!-- Lightbox -->
<div id="lb-overlay" class="fixed inset-0 z-50 bg-ink-900/95 hidden items-center justify-center p-4"
     onclick="if(event.target===this)closeLightbox()">
  <button onclick="lbPrev()" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-ink-700/80 hover:bg-ink-600 text-white rounded-full text-xl flex items-center justify-center z-10">‹</button>
  <div class="max-w-2xl w-full text-center">
    <img id="lb-img" src="" alt="" class="max-h-[80vh] object-contain rounded-xl mx-auto">
    <p id="lb-caption" class="text-ink-400 text-sm mt-3"></p>
    <p id="lb-counter" class="text-ink-600 text-xs mt-1"></p>
  </div>
  <button onclick="lbNext()" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-ink-700/80 hover:bg-ink-600 text-white rounded-full text-xl flex items-center justify-center z-10">›</button>
  <button onclick="closeLightbox()" class="absolute top-4 right-4 w-9 h-9 bg-ink-700/80 hover:bg-ink-600 text-white rounded-full text-lg flex items-center justify-center z-10">×</button>
</div>

<script>
const BASE_PATH   = <?= json_encode(base_path()) ?>;
const CSRF        = <?= json_encode(csrf()) ?>;
const MAX_PHOTOS  = <?= $maxPhotos ?>;
const allPhotos   = <?= json_encode(array_values(array_map(fn($p) => [
    'id'       => $p['id'],
    'url'      => upload_url($p['filename']),
    'caption'  => $p['caption'] ?? '',
], $photos))) ?>;
let lbIdx     = 0;
let selectedFile = null;
let allVisible   = false;

// ── Lightbox ──────────────────────────────────────────────
function openLightbox(idx) {
    lbIdx = idx;
    renderLb();
    const ov = document.getElementById('lb-overlay');
    ov.classList.remove('hidden'); ov.classList.add('flex');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lb-overlay').classList.replace('flex','hidden');
    document.body.style.overflow = '';
}
function renderLb() {
    const p = allPhotos[lbIdx];
    document.getElementById('lb-img').src         = p.url;
    document.getElementById('lb-caption').textContent = p.caption;
    document.getElementById('lb-counter').textContent  = (lbIdx + 1) + ' / ' + allPhotos.length;
}
function lbNext() { lbIdx = (lbIdx + 1) % allPhotos.length; renderLb(); }
function lbPrev() { lbIdx = (lbIdx - 1 + allPhotos.length) % allPhotos.length; renderLb(); }
document.addEventListener('keydown', e => {
    if (!document.getElementById('lb-overlay').classList.contains('flex')) return;
    if (e.key === 'ArrowRight') lbNext();
    if (e.key === 'ArrowLeft')  lbPrev();
    if (e.key === 'Escape')     closeLightbox();
});

// Swipe support
let lbStartX = 0;
document.getElementById('lb-overlay').addEventListener('touchstart', e => { lbStartX = e.touches[0].clientX; }, {passive:true});
document.getElementById('lb-overlay').addEventListener('touchend', e => {
    const dx = e.changedTouches[0].clientX - lbStartX;
    if (Math.abs(dx) > 40) dx < 0 ? lbNext() : lbPrev();
}, {passive:true});

// ── Show all photos ───────────────────────────────────────
function showAllPhotos() {
    if (allVisible) return;
    allVisible = true;
    document.querySelectorAll('#gallery [data-id]').forEach(el => el.classList.remove('hidden'));
    // Remove the +X overlay from 6th photo
    const sixth = document.querySelector('#gallery [data-idx="5"] .absolute.inset-0.bg-ink-900\\/70');
    if (sixth) sixth.remove();
    const btn = document.getElementById('show-all-btn');
    if (btn) btn.remove();
}

// ── Upload ────────────────────────────────────────────────
function previewFile(input) {
    if (!input.files?.length) return;
    selectedFile = input.files[0];
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('preview-img').src = e.target.result;
        document.getElementById('preview-wrap').classList.remove('hidden');
        document.getElementById('drop-zone').classList.add('hidden');
    };
    reader.readAsDataURL(selectedFile);
}
function handleDrop(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('border-gold/50');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer(); dt.items.add(file);
        const input = document.getElementById('photo-input');
        input.files = dt.files; previewFile(input);
    }
}
async function uploadPhoto() {
    const msg = document.getElementById('upload-msg');
    const btn = document.getElementById('upload-btn');
    msg.classList.add('hidden');

    if (allPhotos.length >= MAX_PHOTOS) {
        msg.textContent = `Ya alcanzaste el límite de ${MAX_PHOTOS} fotos.`;
        msg.className   = 'text-sm text-amber-400';
        msg.classList.remove('hidden'); return;
    }
    if (!selectedFile) {
        msg.textContent = 'Seleccioná una imagen primero.';
        msg.className   = 'text-sm text-red-400';
        msg.classList.remove('hidden'); return;
    }

    const fd = new FormData();
    fd.append('photo',   selectedFile);
    fd.append('caption', document.getElementById('caption-input').value.trim());
    btn.disabled = true; btn.textContent = 'Subiendo…';

    try {
        const res  = await fetch(`${BASE_PATH}/mi-panel/fotos`, {method:'POST', body:fd});
        const data = await res.json();
        if (data.success) {
            location.reload();
        } else {
            msg.textContent = data.error || 'Error al subir la foto.';
            msg.className   = 'text-sm text-red-400';
            msg.classList.remove('hidden');
        }
    } catch(e) {
        msg.textContent = 'Error de conexión.';
        msg.className   = 'text-sm text-red-400';
        msg.classList.remove('hidden');
    } finally {
        btn.disabled = false; btn.textContent = 'Subir';
    }
}
async function deletePhoto(id, btn) {
    if (!confirm('¿Eliminar esta foto?')) return;
    const res  = await fetch(`${BASE_PATH}/mi-panel/fotos/${id}/eliminar`, {method:'POST'});
    const data = await res.json();
    if (data.success) location.reload();
    else alert('No se pudo eliminar la foto.');
}
</script>
