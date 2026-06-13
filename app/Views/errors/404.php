<?php $pageTitle = $pageTitle ?? '404 — Página no encontrada'; ?>
<div class="pt-16 min-h-screen flex items-center justify-center px-4">
  <div class="text-center max-w-md">
    <div class="font-display text-[120px] font-bold text-ink-800 leading-none select-none mb-2">404</div>
    <h1 class="font-display text-2xl font-bold text-cream-light mb-3">Página no encontrada</h1>
    <p class="text-ink-400 mb-8">El local o la página que buscás no existe o fue removida.</p>
    <div class="flex items-center justify-center gap-3 flex-wrap">
      <a href="<?= path('/') ?>" class="bg-gold hover:bg-gold-500 text-ink-900 font-semibold px-6 py-3 rounded-xl text-sm transition-colors">Ir al inicio</a>
      <a href="<?= path('/buscar') ?>" class="border border-ink-600 hover:border-ink-400 text-ink-200 hover:text-cream px-6 py-3 rounded-xl text-sm transition-all">Buscar locales</a>
    </div>
  </div>
</div>
