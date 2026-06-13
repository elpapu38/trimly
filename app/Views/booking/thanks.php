<?php /* app/Views/booking/thanks.php */ ?>
<div class="min-h-screen bg-ink-900 flex items-center justify-center py-16">
<div class="max-w-lg mx-auto px-4 text-center">

  <div class="w-20 h-20 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
    <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
    </svg>
  </div>

  <h1 class="font-display text-3xl text-cream-light mb-3">¡Turno reservado!</h1>
  <p class="text-ink-300 mb-8">Te enviamos la confirmación a <strong class="text-cream-light"><?= e($appointment['client_email']) ?></strong></p>

  <!-- Detalles -->
  <div class="p-5 bg-ink-800 border border-ink-600 rounded-2xl text-left mb-6">
    <div class="space-y-3 text-sm">
      <div class="flex justify-between">
        <span class="text-ink-400">Local</span>
        <span class="text-cream-light font-medium"><?= e($shop['name']) ?></span>
      </div>
      <div class="flex justify-between">
        <span class="text-ink-400">Servicio</span>
        <span class="text-cream-light font-medium"><?= e($service['name']) ?></span>
      </div>
      <div class="flex justify-between">
        <span class="text-ink-400">Profesional</span>
        <span class="text-cream-light font-medium"><?= e($employee['name']) ?></span>
      </div>
      <div class="flex justify-between">
        <span class="text-ink-400">Fecha</span>
        <span class="text-cream-light font-medium"><?= fecha($appointment['date'], 'd/m/Y') ?></span>
      </div>
      <div class="flex justify-between">
        <span class="text-ink-400">Hora</span>
        <span class="text-cream-light font-medium"><?= hora($appointment['start_time']) ?> hs</span>
      </div>
      <div class="flex justify-between pt-3 border-t border-ink-700">
        <span class="text-ink-400">Total</span>
        <span class="text-gold font-bold"><?= money((float)$appointment['price']) ?></span>
      </div>
      <div class="flex justify-between">
        <span class="text-ink-400">Pago</span>
        <span class="text-cream-light"><?= $appointment['payment_option'] === 'on_site' ? 'En el local' : 'Online' ?></span>
      </div>
    </div>
  </div>

  <p class="text-xs text-ink-500 mb-8">Para cancelar tu turno, usá el enlace que te enviamos por email.</p>

  <div class="flex flex-col sm:flex-row gap-3 justify-center">
    <a href="<?= path('/local/' . e($shop['slug'])) ?>" class="px-6 py-3 border border-ink-600 text-ink-300 rounded-full hover:border-gold/50 transition-colors text-sm">
      Ver local
    </a>
    <a href="<?= path('/') ?>" class="px-6 py-3 bg-gold text-ink-900 font-semibold rounded-full hover:bg-gold-300 transition-colors text-sm">
      Ir al inicio
    </a>
  </div>
</div>
</div>
