<?php /* app/Views/employee_dash/photos.php */ ?>
<div class="flex min-h-screen pt-16 pb-20 lg:pb-6">
  <?php require __DIR__ . '/partials/sidebar.php'; ?>
  <main class="flex-1 p-4 sm:p-6 lg:p-8 lg:ml-56">

    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="font-display text-2xl font-bold text-cream-light">Mis fotos</h1>
        <p class="text-ink-400 text-sm mt-1">Mostrá tus trabajos en tu perfil del local.</p>
      </div>
      <button onclick="document.getElementById('upload-area').classList.toggle('hidden')"
              class="px-4 py-2 bg-gold text-ink-900 font-semibold rounded-xl text-sm hover:bg-gold-300 transition-colors">
        + Subir foto
      </button>
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
                  class="px-5 py-2.5 bg-gold text-ink-900 font-bold rounded-xl text-sm hover:bg-gold-300 transition-colors">
            Subir
          </button>
          <button onclick="document.getElementById('upload-area').classList.add('hidden')"
                  class="px-5 py-2.5 border border-ink-600 text-ink-400 rounded-xl text-sm hover:border-ink-500">
            Cancelar
          </button>
        </div>
        <p id="upload-msg" class="text-sm hidden"></p>
      </div>
    </div>

    <!-- Gallery -->
    <?php if (empty($photos)): ?>
    <div class="text-center py-20 border border-dashed border-ink-700 rounded-2xl">
      <p class="text-4xl mb-3 opacity-30">📷</p>
      <p class="text-ink-400">Todavía no subiste ninguna foto.</p>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3" id="gallery">
      <?php foreach ($photos as $p): ?>
      <div class="group relative rounded-xl overflow-hidden border border-ink-700" data-id="<?= $p['id'] ?>">
        <img src="<?= upload_url($p['filename']) ?>" alt="<?= e($p['caption'] ?? '') ?>"
             class="w-full aspect-square object-cover">
        <?php if ($p['caption']): ?>
        <div class="absolute bottom-0 left-0 right-0 bg-ink-900/80 px-2 py-1.5 text-xs text-cream-light truncate">
          <?= e($p['caption']) ?>
        </div>
        <?php endif; ?>
        <button onclick="deletePhoto(<?= $p['id'] ?>, this)"
                class="absolute top-2 right-2 w-7 h-7 bg-red-600/80 hover:bg-red-500 text-white rounded-full text-xs flex items-center justify-center
                       opacity-0 group-hover:opacity-100 transition-opacity shadow-md">
          ✕
        </button>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </main>
</div>

<script>
const BASE_PATH = <?= json_encode(base_path()) ?>;
const CSRF      = <?= json_encode(csrf()) ?>;
let selectedFile = null;

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
    const dt = new DataTransfer();
    dt.items.add(file);
    const input = document.getElementById('photo-input');
    input.files = dt.files;
    previewFile(input);
  }
}

async function uploadPhoto() {
  const msg = document.getElementById('upload-msg');
  const btn = document.getElementById('upload-btn');
  msg.classList.add('hidden');

  if (!selectedFile) {
    msg.textContent = 'Seleccioná una imagen primero.';
    msg.className   = 'text-sm text-red-400';
    msg.classList.remove('hidden');
    return;
  }

  const fd = new FormData();
  fd.append('photo',   selectedFile);
  fd.append('caption', document.getElementById('caption-input').value.trim());

  btn.disabled    = true;
  btn.textContent = 'Subiendo…';

  try {
    const res  = await fetch(`${BASE_PATH}/mi-panel/fotos`, {method:'POST', body:fd});
    const data = await res.json();
    if (data.success) {
      // Add to gallery
      const gallery = document.getElementById('gallery');
      if (gallery) {
        const div = document.createElement('div');
        div.className = 'group relative rounded-xl overflow-hidden border border-ink-700';
        div.dataset.id = data.id;
        div.innerHTML = `
          <img src="${data.url}" alt="" class="w-full aspect-square object-cover">
          <button onclick="deletePhoto(${data.id}, this)"
                  class="absolute top-2 right-2 w-7 h-7 bg-red-600/80 hover:bg-red-500 text-white rounded-full text-xs flex items-center justify-center
                         opacity-0 group-hover:opacity-100 transition-opacity shadow-md">✕</button>
        `;
        gallery.prepend(div);
      } else {
        location.reload();
      }
      // Reset
      selectedFile = null;
      document.getElementById('upload-area').classList.add('hidden');
      document.getElementById('caption-input').value = '';
      document.getElementById('photo-input').value   = '';
      document.getElementById('preview-wrap').classList.add('hidden');
      document.getElementById('drop-zone').classList.remove('hidden');
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
    btn.disabled    = false;
    btn.textContent = 'Subir';
  }
}

async function deletePhoto(id, btn) {
  if (!confirm('¿Eliminar esta foto?')) return;
  const res  = await fetch(`${BASE_PATH}/mi-panel/fotos/${id}/eliminar`, {method:'POST'});
  const data = await res.json();
  if (data.success) {
    btn.closest('[data-id]').remove();
  } else {
    alert('No se pudo eliminar la foto.');
  }
}
</script>
