<?php
$errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_errors']);
$days = diasSemana();
$hoursByDay = [];
foreach ($hours as $h) $hoursByDay[(int)$h['day_of_week']] = $h;

$types = ['barbershop'=>'Barbería','salon'=>'Salón de Belleza','mixed'=>'Mixto / Unisex','nails'=>'Manicura & Uñas','spa'=>'Spa & Relajación','tattoo'=>'Tatuajes & Piercings','makeup'=>'Maquillaje & Estética','other'=>'Otro'];
?>
<div class="flex min-h-screen pt-16 pb-20 lg:pb-6">
  <?php require __DIR__ . '/partials/sidebar.php'; ?>

  <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden min-w-0">
    <h1 class="font-display text-xl font-bold text-cream-light mb-5">Configuración del local</h1>

    <!-- Tabs -->
    <div class="flex gap-1 mb-6 bg-ink-800 p-1 rounded-xl w-full sm:w-fit overflow-x-auto">
      <?php foreach(['info'=>'📋 Información','horarios'=>'🕐 Horarios','fotos'=>'📷 Fotos'] as $id=>$label): ?>
      <button onclick="showTab('<?=$id?>')" id="tab-<?=$id?>"
        class="tab-btn flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs sm:text-sm font-medium transition-all whitespace-nowrap
               <?=$id==='info'?'text-gold bg-ink-700':'text-ink-400 hover:text-cream-light'?>">
        <?=$label?>
      </button>
      <?php endforeach; ?>
    </div>

    <!-- ── TAB INFO ──────────────────────────────────────── -->
    <div id="pane-info">
      <form method="POST" action="<?= path('/panel/local') ?>" enctype="multipart/form-data" class="space-y-5">
        <input type="hidden" name="_csrf" value="<?= csrf() ?>">

        <!-- Info básica -->
        <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5">
          <h2 class="font-semibold text-cream-light mb-4 text-sm">Información básica</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs text-ink-400 mb-1">Nombre del local *</label>
              <input type="text" name="name" value="<?= e($shop['name']) ?>" required
                     class="input-gold w-full bg-ink-700 border <?=isset($errors['name'])?'border-red-500':'border-ink-600'?> rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
            </div>
            <div>
              <label class="block text-xs text-ink-400 mb-1">Tipo de local *</label>
              <select name="type" class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
                <?php foreach($types as $v=>$l): ?>
                <option value="<?=$v?>" <?=$shop['type']===$v?'selected':''?>><?=$l?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="block text-xs text-ink-400 mb-1">Audiencia principal</label>
              <select name="target_audience" class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
                <option value="unisex" <?=($shop['target_audience']??'')==='unisex'?'selected':''?>>Unisex (todos)</option>
                <option value="men"    <?=($shop['target_audience']??'')==='men'   ?'selected':''?>>Caballeros</option>
                <option value="women"  <?=($shop['target_audience']??'')==='women' ?'selected':''?>>Damas</option>
              </select>
            </div>
            <div class="sm:col-span-2">
              <label class="block text-xs text-ink-400 mb-1">Descripción</label>
              <textarea name="description" rows="3"
                        class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light resize-none focus:outline-none"><?= e($shop['description']??'') ?></textarea>
            </div>
            <div class="sm:col-span-2">
              <label class="block text-xs text-ink-400 mb-1">Especialidades <span class="text-ink-500">(separadas por coma)</span></label>
              <input type="text" name="specialties" value="<?= e($shop['specialties']??'') ?>"
                     placeholder="Ej: Fade, Barba, Coloración, Keratina"
                     class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
            </div>
            <div class="sm:col-span-2">
              <label class="block text-xs text-ink-400 mb-1">Comodidades <span class="text-ink-500">(separadas por coma)</span></label>
              <input type="text" name="amenities" value="<?= e($shop['amenities']??'') ?>"
                     placeholder="Ej: WiFi, Estacionamiento, Aire acondicionado"
                     class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
            </div>
          </div>
        </div>

        <!-- Contacto -->
        <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5">
          <h2 class="font-semibold text-cream-light mb-4 text-sm">Contacto y ubicación</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs text-ink-400 mb-1">Teléfono</label>
              <input type="text" name="phone" value="<?= e($shop['phone']??'') ?>"
                     class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
            </div>
            <div>
              <label class="block text-xs text-ink-400 mb-1">WhatsApp</label>
              <input type="text" name="whatsapp" value="<?= e($shop['whatsapp']??'') ?>" placeholder="+5492920..."
                     class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
            </div>
            <div>
              <label class="block text-xs text-ink-400 mb-1">Email de contacto</label>
              <input type="email" name="email" value="<?= e($shop['email']??'') ?>"
                     class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
            </div>
            <div>
              <label class="block text-xs text-ink-400 mb-1">Website</label>
              <input type="url" name="website" value="<?= e($shop['website']??'') ?>"
                     class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
            </div>
            <div>
              <label class="block text-xs text-ink-400 mb-1">Instagram <span class="text-ink-500">(sin @)</span></label>
              <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-ink-400 text-sm">@</span>
                <input type="text" name="instagram" value="<?= e($shop['instagram']??'') ?>"
                       class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl pl-7 pr-4 py-2.5 text-sm text-cream-light focus:outline-none">
              </div>
            </div>
            <div>
              <label class="block text-xs text-ink-400 mb-1">Facebook</label>
              <input type="text" name="facebook" value="<?= e($shop['facebook']??'') ?>"
                     class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
            </div>
            <div>
              <label class="block text-xs text-ink-400 mb-1">Dirección</label>
              <input type="text" name="address" value="<?= e($shop['address']??'') ?>"
                     class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
            </div>
            <div>
              <label class="block text-xs text-ink-400 mb-1">Ciudad *</label>
              <input type="text" name="city" value="<?= e($shop['city']??'') ?>" required
                     class="input-gold w-full bg-ink-700 border <?=isset($errors['city'])?'border-red-500':'border-ink-600'?> rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
            </div>
            <div>
              <label class="block text-xs text-ink-400 mb-1">Provincia</label>
              <input type="text" name="province" value="<?= e($shop['province']??'') ?>"
                     class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
            </div>
            <div>
              <label class="block text-xs text-ink-400 mb-1">Código postal</label>
              <input type="text" name="postal_code" value="<?= e($shop['postal_code']??'') ?>"
                     class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
            </div>

            <!-- ── MAPA DE UBICACIÓN ── -->
            <div class="sm:col-span-2">
              <label class="block text-xs text-ink-400 mb-2">Ubicación en el mapa</label>
              <p class="text-xs text-ink-500 mb-3">
                Hacé clic en el mapa para mover el marcador, o usá el botón para geocodificar la dirección ingresada arriba.
              </p>

              <!-- Campos ocultos que se envían con el formulario -->
              <input type="hidden" name="latitude"  id="field-lat" value="<?= e($shop['latitude'] ??'') ?>">
              <input type="hidden" name="longitude" id="field-lng" value="<?= e($shop['longitude']??'') ?>">

              <!-- Mapa Leaflet -->
              <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
              <div id="settings-map" class="w-full rounded-xl overflow-hidden border border-ink-600 bg-ink-700 mb-3" style="height:260px"></div>

              <div class="flex flex-wrap gap-2 items-center">
                <button type="button" onclick="geocodeAddress()"
                        id="geocode-btn"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-ink-700 hover:bg-ink-600 border border-ink-500 text-cream-light text-xs font-semibold rounded-lg transition-all">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  Buscar por dirección
                </button>
                <button type="button" onclick="clearCoords()"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-ink-700 hover:bg-ink-600 border border-ink-600 text-ink-400 hover:text-cream text-xs rounded-lg transition-all">
                  Limpiar coordenadas
                </button>
                <span id="coords-display" class="text-xs text-ink-500 font-mono ml-1">
                  <?php if($shop['latitude'] && $shop['longitude']): ?>
                    <?= number_format((float)$shop['latitude'],6) ?>, <?= number_format((float)$shop['longitude'],6) ?>
                  <?php else: ?>
                    Sin coordenadas guardadas
                  <?php endif; ?>
                </span>
              </div>
              <p id="geocode-status" class="text-xs mt-2 hidden"></p>
            </div>

          </div>
        </div>

        <!-- Imágenes -->
        <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5">
          <h2 class="font-semibold text-cream-light mb-4 text-sm">Imágenes</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs text-ink-400 mb-2">Logo</label>
              <?php if($shop['logo']): ?>
              <img src="<?= upload_url($shop['logo']) ?>" class="w-16 h-16 rounded-xl object-cover mb-2 border border-ink-600">
              <?php endif; ?>
              <input type="file" name="logo" accept="image/*"
                     class="w-full text-ink-400 text-xs file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-gold/20 file:text-gold file:text-xs hover:file:bg-gold/30 cursor-pointer">
            </div>
            <div>
              <label class="block text-xs text-ink-400 mb-2">Imagen de portada</label>
              <?php if($shop['cover_image']): ?>
              <img src="<?= upload_url($shop['cover_image']) ?>" class="w-full h-16 rounded-xl object-cover mb-2 border border-ink-600">
              <?php endif; ?>
              <input type="file" name="cover_image" accept="image/*"
                     class="w-full text-ink-400 text-xs file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-gold/20 file:text-gold file:text-xs hover:file:bg-gold/30 cursor-pointer">
            </div>
          </div>
        </div>

        <button type="submit" class="w-full sm:w-auto bg-gold hover:bg-gold-500 text-ink-900 font-bold px-8 py-3 rounded-xl transition-colors">
          Guardar cambios
        </button>
      </form>
    </div>

    <!-- ── TAB HORARIOS ──────────────────────────────────── -->
    <div id="pane-horarios" class="hidden">
      <form method="POST" action="<?= path('/panel/local/horarios') ?>">
        <input type="hidden" name="_csrf" value="<?= csrf() ?>">
        <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5 mb-4">
          <h2 class="font-semibold text-cream-light mb-4 text-sm">Horarios de atención</h2>
          <div class="space-y-2">
            <?php for($d=0;$d<=6;$d++):
              $h = $hoursByDay[$d] ?? [];
              $open = !empty($h['opens_at']);
            ?>
            <div class="flex flex-wrap items-center gap-2 sm:gap-3 p-3 bg-ink-700/50 rounded-xl">
              <label class="flex items-center gap-2 cursor-pointer w-28 shrink-0">
                <input type="checkbox" name="days[<?=$d?>]" value="1" <?=$open?'checked':''?>
                       class="w-4 h-4 accent-gold" onchange="toggleDay(this,<?=$d?>)">
                <span class="text-sm text-cream-light font-medium"><?=$days[$d]?></span>
              </label>
              <div id="daytime-<?=$d?>" class="flex flex-wrap items-center gap-2 <?=$open?'':'opacity-40 pointer-events-none'?>">
                <div class="flex items-center gap-1.5">
                  <span class="text-xs text-ink-400">Abre</span>
                  <input type="time" name="opens_at[<?=$d?>]" value="<?= e($h['opens_at']??'09:00') ?>"
                         class="input-gold bg-ink-800 border border-ink-600 rounded-lg px-2.5 py-1.5 text-cream-light text-xs focus:outline-none">
                </div>
                <div class="flex items-center gap-1.5">
                  <span class="text-xs text-ink-400">Cierra</span>
                  <input type="time" name="closes_at[<?=$d?>]" value="<?= e($h['closes_at']??'20:00') ?>"
                         class="input-gold bg-ink-800 border border-ink-600 rounded-lg px-2.5 py-1.5 text-cream-light text-xs focus:outline-none">
                </div>
                <div class="flex items-center gap-1.5">
                  <span class="text-xs text-ink-500">Pausa</span>
                  <input type="time" name="break_start[<?=$d?>]" value="<?= e($h['break_start']??'') ?>"
                         class="input-gold bg-ink-800 border border-ink-600 rounded-lg px-2.5 py-1.5 text-cream-light text-xs focus:outline-none">
                  <span class="text-ink-500 text-xs">-</span>
                  <input type="time" name="break_end[<?=$d?>]" value="<?= e($h['break_end']??'') ?>"
                         class="input-gold bg-ink-800 border border-ink-600 rounded-lg px-2.5 py-1.5 text-cream-light text-xs focus:outline-none">
                </div>
              </div>
              <?php if(!$open): ?>
              <span class="text-xs text-ink-500 italic ml-1">Cerrado</span>
              <?php endif; ?>
            </div>
            <?php endfor; ?>
          </div>
        </div>
        <button type="submit" class="w-full sm:w-auto bg-gold hover:bg-gold-500 text-ink-900 font-bold px-8 py-3 rounded-xl transition-colors">
          Guardar horarios
        </button>
      </form>
    </div>

    <!-- ── TAB FOTOS ─────────────────────────────────────── -->
    <div id="pane-fotos" class="hidden">
      <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5 mb-4">
        <h2 class="font-semibold text-cream-light mb-4 text-sm">Galería de fotos</h2>
        <div id="photo-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 mb-5">
          <?php foreach($photos as $p): ?>
          <div class="relative group rounded-xl overflow-hidden border border-ink-700 aspect-square" id="ph-<?=$p['id']?>">
            <img src="<?= upload_url($p['filename']) ?>" class="w-full h-full object-cover" loading="lazy">
            <button onclick="deletePhoto(<?=$p['id']?>)"
                    class="absolute top-1.5 right-1.5 bg-red-600/90 hover:bg-red-500 text-white rounded-full w-6 h-6 text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">✕</button>
          </div>
          <?php endforeach; ?>
          <?php if(empty($photos)): ?>
          <p class="col-span-full text-sm text-ink-500 py-4">No hay fotos aún.</p>
          <?php endif; ?>
        </div>

        <!-- Zona de subida -->
        <div id="upload-zone"
             class="border-2 border-dashed border-ink-600 hover:border-gold/50 rounded-2xl p-8 text-center transition-colors cursor-pointer"
             onclick="document.getElementById('photo-input').click()"
             ondragover="event.preventDefault();this.classList.add('border-gold/50')"
             ondrop="handleDrop(event)">
          <p class="text-ink-400 text-sm mb-2">📷 Arrastrá fotos aquí o tocá para elegir</p>
          <p class="text-ink-600 text-xs">JPG, PNG o WebP — máx. 5 MB por foto</p>
          <input type="file" id="photo-input" accept="image/*" multiple class="hidden" onchange="uploadPhotos(this.files)">
        </div>

        <div id="upload-progress" class="mt-3 hidden">
          <div class="h-1.5 bg-ink-700 rounded-full overflow-hidden">
            <div id="progress-bar" class="h-full bg-gold rounded-full transition-all" style="width:0%"></div>
          </div>
          <p id="upload-status" class="text-xs text-ink-400 mt-1 text-center"></p>
        </div>
      </div>
    </div>
  </main>
</div>

<script>
function showTab(id) {
  document.querySelectorAll('[id^="pane-"]').forEach(p => p.classList.add('hidden'));
  document.querySelectorAll('.tab-btn').forEach(b => { b.classList.remove('text-gold','bg-ink-700'); b.classList.add('text-ink-400'); });
  document.getElementById('pane-'+id).classList.remove('hidden');
  const btn = document.getElementById('tab-'+id);
  btn.classList.add('text-gold','bg-ink-700'); btn.classList.remove('text-ink-400');
}

function toggleDay(cb, d) {
  const el = document.getElementById('daytime-'+d);
  el.classList.toggle('opacity-40', !cb.checked);
  el.classList.toggle('pointer-events-none', !cb.checked);
}

async function uploadPhotos(files) {
  if (!files.length) return;
  const prog = document.getElementById('upload-progress');
  const bar  = document.getElementById('progress-bar');
  const stat = document.getElementById('upload-status');
  prog.classList.remove('hidden');
  for (let i = 0; i < files.length; i++) {
    const fd = new FormData();
    fd.append('photo', files[i]);
    stat.textContent = `Subiendo ${i+1} de ${files.length}…`;
    bar.style.width  = Math.round((i/files.length)*100) + '%';
    try {
      const r = await fetch('<?= path('/panel/local/fotos') ?>', {method:'POST', body:fd});
      const d = await r.json();
      if (d.success) {
        const div = document.createElement('div');
        div.className = 'relative group rounded-xl overflow-hidden border border-ink-700 aspect-square';
        div.id = 'ph-'+d.photo.id;
        div.innerHTML = `<img src="${d.url}" class="w-full h-full object-cover" loading="lazy">
          <button onclick="deletePhoto(${d.photo.id})" class="absolute top-1.5 right-1.5 bg-red-600/90 hover:bg-red-500 text-white rounded-full w-6 h-6 text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">✕</button>`;
        document.getElementById('photo-grid').appendChild(div);
      } else {
        alert('Error: ' + (d.error || 'No se pudo subir la foto'));
      }
    } catch(e) { alert('Error de red al subir la foto'); }
  }
  bar.style.width  = '100%';
  stat.textContent = `${files.length} foto${files.length!==1?'s':''} subida${files.length!==1?'s':''} ✓`;
  setTimeout(() => prog.classList.add('hidden'), 3000);
}

function handleDrop(e) {
  e.preventDefault();
  document.getElementById('upload-zone').classList.remove('border-gold/50');
  uploadPhotos(e.dataTransfer.files);
}

async function deletePhoto(id) {
  if (!confirm('¿Eliminar esta foto?')) return;
  const r = await fetch('<?= path('/panel/local/fotos/') ?>'+id+'/delete', {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:''});
  const d = await r.json();
  if (d.success) document.getElementById('ph-'+id)?.remove();
  else alert('No se pudo eliminar');
}
// Mostrar tab por hash
const hash = location.hash.replace('#','');
if (['info','horarios','fotos'].includes(hash)) showTab(hash);

// ── MAPA DE UBICACIÓN ───────────────────────────────────────
(function() {
  const initLat = <?= $shop['latitude']  ? (float)$shop['latitude']  : 'null' ?>;
  const initLng = <?= $shop['longitude'] ? (float)$shop['longitude'] : 'null' ?>;
  const DEFAULT_LAT = -38.7196;   // Viedma, Río Negro (fallback Argentina)
  const DEFAULT_LNG = -62.2724;
  const DEFAULT_ZOOM = initLat ? 16 : 5;

  let map, marker;

  function loadLeaflet(cb) {
    if (window.L) { cb(); return; }
    const s = document.createElement('script');
    s.src = 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js';
    s.onload = cb;
    document.head.appendChild(s);
  }

  function buildIcon() {
    return L.divIcon({
      html: '<div style="width:28px;height:28px;background:#C9A84C;border:3px solid #fff;border-radius:50% 50% 50% 0;transform:rotate(-45deg);box-shadow:0 2px 8px rgba(0,0,0,.5)"></div>',
      iconSize: [28,28], iconAnchor: [14,28], className: ''
    });
  }

  function updateFields(lat, lng) {
    document.getElementById('field-lat').value = lat.toFixed(7);
    document.getElementById('field-lng').value = lng.toFixed(7);
    document.getElementById('coords-display').textContent =
      lat.toFixed(6) + ', ' + lng.toFixed(6);
  }

  function initMap() {
    const mapEl = document.getElementById('settings-map');
    if (!mapEl || mapEl._leaflet_id) return;

    const startLat = initLat || DEFAULT_LAT;
    const startLng = initLng || DEFAULT_LNG;

    map = L.map(mapEl, { scrollWheelZoom: false })
           .setView([startLat, startLng], DEFAULT_ZOOM);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a>',
      maxZoom: 19
    }).addTo(map);

    if (initLat && initLng) {
      marker = L.marker([initLat, initLng], { icon: buildIcon(), draggable: true }).addTo(map);
      marker.on('dragend', e => {
        const p = e.target.getLatLng();
        updateFields(p.lat, p.lng);
      });
    }

    // Click to place / move marker
    map.on('click', function(e) {
      const { lat, lng } = e.latlng;
      if (marker) {
        marker.setLatLng([lat, lng]);
      } else {
        marker = L.marker([lat, lng], { icon: buildIcon(), draggable: true }).addTo(map);
        marker.on('dragend', ev => {
          const p = ev.target.getLatLng();
          updateFields(p.lat, p.lng);
        });
      }
      updateFields(lat, lng);
    });
  }

  // Expose geocode function globally
  window.geocodeAddress = async function() {
    const btn    = document.getElementById('geocode-btn');
    const status = document.getElementById('geocode-status');
    const street  = document.querySelector('[name="address"]')?.value.trim()  || '';
    const city    = document.querySelector('[name="city"]')?.value.trim()     || '';
    const state   = document.querySelector('[name="province"]')?.value.trim() || '';
    const country = 'Argentina';

    if (!city && !street) {
      status.textContent  = '⚠ Completá al menos la ciudad para buscar.';
      status.className    = 'text-xs mt-2 text-amber-400';
      status.classList.remove('hidden');
      return;
    }

    btn.textContent = 'Buscando…';
    btn.disabled    = true;
    status.classList.add('hidden');

    const params = new URLSearchParams({ format:'json', limit:'1', addressdetails:'0', 'accept-language':'es' });
    if (street)  params.set('street',  street);
    if (city)    params.set('city',    city);
    if (state)   params.set('state',   state);
    params.set('country', country);

    try {
      let res  = await fetch('https://nominatim.openstreetmap.org/search?' + params, {
        headers: { 'User-Agent': 'Trimly/1.0' }
      });
      let data = await res.json();

      // Fallback: solo ciudad si no encontró con dirección
      if (!data?.length && street) {
        const fallback = new URLSearchParams({ format:'json', limit:'1', city, country });
        if (state) fallback.set('state', state);
        res  = await fetch('https://nominatim.openstreetmap.org/search?' + fallback, {
          headers: { 'User-Agent': 'Trimly/1.0' }
        });
        data = await res.json();
      }

      if (data?.length) {
        const lat = parseFloat(data[0].lat);
        const lng = parseFloat(data[0].lon);
        updateFields(lat, lng);
        map.setView([lat, lng], street ? 17 : 13);
        if (marker) {
          marker.setLatLng([lat, lng]);
        } else {
          marker = L.marker([lat, lng], { icon: buildIcon(), draggable: true }).addTo(map);
          marker.on('dragend', ev => {
            const p = ev.target.getLatLng();
            updateFields(p.lat, p.lng);
          });
        }
        status.textContent  = '✓ Ubicación encontrada. Podés ajustar el marcador arrastrándolo.';
        status.className    = 'text-xs mt-2 text-emerald-400';
      } else {
        status.textContent  = '✗ No se encontró la dirección. Probá con menos datos o hacé clic en el mapa.';
        status.className    = 'text-xs mt-2 text-red-400';
      }
    } catch(e) {
      status.textContent  = '✗ Error de conexión al buscar.';
      status.className    = 'text-xs mt-2 text-red-400';
    } finally {
      status.classList.remove('hidden');
      btn.textContent = 'Buscar por dirección';
      btn.disabled    = false;
    }
  };

  window.clearCoords = function() {
    document.getElementById('field-lat').value = '';
    document.getElementById('field-lng').value = '';
    document.getElementById('coords-display').textContent = 'Sin coordenadas guardadas';
    if (marker && map) { map.removeLayer(marker); marker = null; }
  };

  // Init when "info" tab is shown (map needs visible container)
  const origShowTab = window.showTab;
  window.showTab = function(id) {
    origShowTab(id);
    if (id === 'info') {
      loadLeaflet(() => { setTimeout(initMap, 50); });
    }
  };

  // If info tab is already active on load, init immediately
  if (!document.getElementById('pane-info').classList.contains('hidden')) {
    loadLeaflet(() => setTimeout(initMap, 100));
  }
})();
</script>
