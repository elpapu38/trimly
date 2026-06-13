<?php /* app/Views/account/delete_account.php */ ?>
<div class="min-h-screen bg-ink-900 pt-24 pb-12">
<div class="max-w-md mx-auto px-4">
  <h1 class="font-display text-2xl font-bold text-red-400 mb-2">Eliminar cuenta</h1>
  <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 mb-6 text-sm text-red-300 space-y-1">
    <p class="font-semibold">⚠ Esta acción es irreversible.</p>
    <p>Al eliminar tu cuenta:</p>
    <ul class="list-disc list-inside text-xs space-y-0.5 text-red-300/80 mt-1">
      <li>Tus reseñas publicadas se mantienen (de forma anónima)</li>
      <li>Los turnos ya completados se conservan en las estadísticas</li>
      <li>Los turnos pendientes o confirmados serán cancelados</li>
      <li>No podrás recuperar la cuenta</li>
    </ul>
  </div>

  <?php foreach ($this->getFlash() as $type => $msgs): foreach ($msgs as $m): ?>
  <div class="mb-4 px-4 py-3 rounded-xl text-sm bg-red-500/10 border border-red-500/30 text-red-400">
    <?= e($m) ?>
  </div>
  <?php endforeach; endforeach; ?>

  <form method="POST" action="<?= path('/cuenta/eliminar') ?>"
        class="bg-ink-800 border border-red-900/40 rounded-2xl p-6 space-y-4"
        onsubmit="return confirm('¿Estás seguro? Esta acción no se puede deshacer.')">
    <input type="hidden" name="_csrf" value="<?= csrf() ?>">
    <div>
      <label class="block text-xs text-ink-400 mb-1">Confirmá tu contraseña *</label>
      <input type="password" name="password" required
             class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
    </div>
    <button type="submit"
            class="w-full bg-red-700 hover:bg-red-600 text-white font-bold py-3 rounded-xl text-sm transition-colors">
      Eliminar mi cuenta definitivamente
    </button>
    <a href="javascript:history.back()" class="block text-center text-xs text-ink-400 hover:text-cream transition-colors">
      Cancelar — volver atrás
    </a>
  </form>
</div>
</div>
