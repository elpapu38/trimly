<?php /* app/Views/account/change_email.php */ ?>
<div class="min-h-screen bg-ink-900 pt-24 pb-12">
<div class="max-w-md mx-auto px-4">
  <h1 class="font-display text-2xl font-bold text-cream-light mb-2">Cambiar email</h1>
  <p class="text-ink-400 text-sm mb-6">Ingresá tu nuevo email y confirmá tu contraseña actual.</p>

  <?php foreach ($this->getFlash() as $type => $msgs): foreach ($msgs as $m): ?>
  <div class="mb-4 px-4 py-3 rounded-xl text-sm <?= $type==='success' ? 'bg-emerald-500/10 border border-emerald-500/30 text-emerald-400' : 'bg-red-500/10 border border-red-500/30 text-red-400' ?>">
    <?= e($m) ?>
  </div>
  <?php endforeach; endforeach; ?>

  <form method="POST" action="<?= path('/cuenta/email') ?>"
        class="bg-ink-800 border border-ink-700 rounded-2xl p-6 space-y-4">
    <input type="hidden" name="_csrf" value="<?= csrf() ?>">
    <div>
      <label class="block text-xs text-ink-400 mb-1">Email actual</label>
      <p class="text-sm text-ink-300 bg-ink-700/50 rounded-xl px-4 py-2.5"><?= e($user['email']) ?></p>
    </div>
    <div>
      <label class="block text-xs text-ink-400 mb-1">Nuevo email *</label>
      <input type="email" name="new_email" required
             class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
    </div>
    <div>
      <label class="block text-xs text-ink-400 mb-1">Contraseña actual *</label>
      <input type="password" name="password" required
             class="input-gold w-full bg-ink-700 border border-ink-600 rounded-xl px-4 py-2.5 text-sm text-cream-light focus:outline-none">
    </div>
    <button type="submit" class="w-full bg-gold hover:bg-gold-500 text-ink-900 font-bold py-3 rounded-xl text-sm transition-colors">
      Actualizar email
    </button>
  </form>

  <div class="mt-4 text-center space-x-4">
    <a href="<?= path('/cuenta/contrasena') ?>" class="text-xs text-ink-400 hover:text-gold transition-colors">Cambiar contraseña</a>
    <?php if ($user['role'] !== 'superadmin'): ?>
    <a href="<?= path('/cuenta/eliminar') ?>" class="text-xs text-red-500/70 hover:text-red-400 transition-colors">Eliminar cuenta</a>
    <?php endif; ?>
  </div>
</div>
</div>
