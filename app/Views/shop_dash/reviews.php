<?php /* app/Views/shop_dash/reviews.php */ ?>
<div class="flex min-h-screen pt-16 pb-20 lg:pb-0">
  <?php include __DIR__ . '/partials/sidebar.php'; ?>
  <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden min-w-0">

    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
      <h1 class="font-display text-xl sm:text-2xl text-cream-light font-bold">Reseñas</h1>
      <div class="flex items-center gap-2 bg-ink-800 border border-ink-700 rounded-xl px-4 py-2">
        <span class="text-gold text-lg">★</span>
        <span class="text-cream-light font-bold text-lg"><?= number_format((float)$shop['rating_avg'],1) ?></span>
        <span class="text-ink-400 text-sm">(<?= $shop['rating_count'] ?> reseñas)</span>
      </div>
    </div>

    <?php if (empty($reviews)): ?>
    <div class="text-center py-20 text-ink-400">
      <p>Todavía no recibiste reseñas</p>
      <p class="text-sm mt-2">Las reseñas aparecerán cuando los clientes completen sus turnos</p>
    </div>
    <?php else: ?>
    <div class="space-y-4">
      <?php foreach ($reviews as $r): ?>
      <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5" id="review-<?= $r['id'] ?>">
        <div class="flex items-start justify-between mb-3">
          <div>
            <p class="text-cream-light font-medium"><?= e($r['client_name'] ?? 'Cliente') ?></p>
            <p class="text-ink-400 text-xs"><?= fecha($r['created_at'], 'd/m/Y') ?></p>
          </div>
          <div class="flex items-center gap-3">
            <div class="text-gold"><?= str_repeat('★', (int)$r['rating']) ?><span class="text-ink-600"><?= str_repeat('★', 5-(int)$r['rating']) ?></span></div>
            <button onclick="toggleVisibility(<?= $r['id'] ?>, <?= $r['is_visible'] ?>)"
                    class="text-xs px-2 py-1 rounded border <?= $r['is_visible'] ? 'border-green-500/30 text-green-400' : 'border-red-500/30 text-red-400' ?>">
              <?= $r['is_visible'] ? 'Visible' : 'Oculta' ?>
            </button>
          </div>
        </div>

        <?php if ($r['comment']): ?>
        <p class="text-ink-300 text-sm leading-relaxed mb-4"><?= e($r['comment']) ?></p>
        <?php endif; ?>

        <!-- Respuesta -->
        <?php if ($r['reply']): ?>
        <div class="ml-4 pl-4 border-l-2 border-gold/30">
          <p class="text-xs text-gold mb-1">Tu respuesta:</p>
          <p class="text-ink-300 text-sm" id="reply-text-<?= $r['id'] ?>"><?= e($r['reply']) ?></p>
          <button onclick="showReplyForm(<?= $r['id'] ?>)" class="text-xs text-ink-500 hover:text-gold mt-1">Editar</button>
        </div>
        <?php else: ?>
        <button onclick="showReplyForm(<?= $r['id'] ?>)" class="text-sm text-gold hover:underline">+ Responder</button>
        <?php endif; ?>

        <!-- Form de respuesta (oculto) -->
        <div id="reply-form-<?= $r['id'] ?>" class="hidden mt-3">
          <textarea id="reply-input-<?= $r['id'] ?>" rows="2" placeholder="Tu respuesta..."
                    class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-3 text-cream-light text-sm resize-none mb-2"><?= e($r['reply'] ?? '') ?></textarea>
          <div class="flex gap-2">
            <button onclick="submitReply(<?= $r['id'] ?>)" class="px-4 py-2 bg-gold text-ink-900 font-semibold rounded-lg text-sm hover:bg-gold-300">Publicar</button>
            <button onclick="hideReplyForm(<?= $r['id'] ?>)" class="px-4 py-2 border border-ink-600 text-ink-300 rounded-lg text-sm">Cancelar</button>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Paginación -->
    <?php if ($pagination['total_pages'] > 1): ?>
    <div class="flex justify-center gap-2 mt-6">
      <?php for ($p = 1; $p <= $pagination['total_pages']; $p++): ?>
      <a href="?page=<?= $p ?>"
         class="w-9 h-9 rounded-lg flex items-center justify-center text-sm border
           <?= $p == $pagination['current_page'] ? 'bg-gold text-ink-900 border-gold' : 'border-ink-600 text-ink-300 hover:border-gold/50' ?>">
        <?= $p ?>
      </a>
      <?php endfor; ?>
    </div>
    <?php endif; ?>

    <?php endif; ?>
  </main>
</div>

<script>
function showReplyForm(id) {
  document.getElementById('reply-form-'+id).classList.remove('hidden');
}
function hideReplyForm(id) {
  document.getElementById('reply-form-'+id).classList.add('hidden');
}

async function submitReply(id) {
  const text = document.getElementById('reply-input-'+id).value.trim();
  if (!text) return;
  const form = new FormData();
  form.append('reply', text);
  const BASE = <?= json_encode(base_path()) ?>;
  const res = await fetch(`${BASE}/panel/resenas/${id}/reply`, {method:'POST', body:form});
  const data = await res.json();
  if (data.success) {
    hideReplyForm(id);
    location.reload();
  } else {
    alert(data.error || 'Error al guardar');
  }
}

async function toggleVisibility(id, current) {
  const form = new FormData();
  const res = await fetch(`/panel/resenas/${id}/toggle`, {method:'POST', body:form});
  const data = await res.json();
  if (data.success) location.reload();
}
</script>
