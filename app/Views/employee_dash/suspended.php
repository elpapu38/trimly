<?php /* app/Views/employee_dash/suspended.php */ ?>
<div class="flex min-h-screen pt-16 pb-20 lg:pb-6">
  <?php require __DIR__ . '/partials/sidebar.php'; ?>
  <main class="flex-1 p-4 sm:p-6 lg:p-8 lg:ml-56 overflow-x-hidden flex items-center justify-center">

    <div class="max-w-lg w-full text-center">
      <!-- Icono -->
      <div class="mx-auto mb-6 w-20 h-20 rounded-full bg-red-900/30 border border-red-700/40 flex items-center justify-center">
        <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
        </svg>
      </div>

      <!-- Título -->
      <h1 class="font-display text-2xl font-bold text-red-400 mb-3">Local suspendido</h1>

      <!-- Mensaje -->
      <p class="text-ink-300 mb-4 leading-relaxed">
        El local <strong class="text-cream-light"><?= e($employee['shop_name']) ?></strong>
        ha sido suspendido por el administrador de la plataforma.
        Mientras dure la suspensión no podés realizar ninguna acción en el sistema.
      </p>

      <?php if (!empty($employee['suspension_reason'])): ?>
      <div class="bg-red-950/40 border border-red-800/50 rounded-xl p-4 mb-6 text-left">
        <p class="text-xs text-red-400 font-semibold uppercase tracking-wider mb-1">Motivo informado</p>
        <p class="text-ink-200 text-sm"><?= e($employee['suspension_reason']) ?></p>
      </div>
      <?php endif; ?>

      <p class="text-ink-400 text-sm">
        Contactá al dueño del local para obtener más información.
      </p>
    </div>

  </main>
</div>
