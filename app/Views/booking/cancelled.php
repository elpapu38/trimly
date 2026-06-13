<?php /* app/Views/booking/cancelled.php */ ?>
<div class="min-h-screen bg-ink-900 flex items-center justify-center py-16">
<div class="max-w-md mx-auto px-4 text-center">
  <div class="w-20 h-20 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
    <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
    </svg>
  </div>
  <h1 class="font-display text-3xl text-cream-light mb-3">Turno cancelado</h1>
  <p class="text-ink-300 mb-6">Tu turno del <strong class="text-cream-light"><?= fecha($appointment['date'], 'd/m/Y') ?></strong> a las <strong class="text-cream-light"><?= hora($appointment['start_time']) ?> hs</strong> en <strong class="text-cream-light"><?= e($shop['name']) ?></strong> fue cancelado correctamente.</p>
  <a href="<?= path('/') ?>" class="inline-block px-6 py-3 bg-gold text-ink-900 font-semibold rounded-full hover:bg-gold-300 transition-colors">
    Reservar nuevo turno
  </a>
</div>
</div>
