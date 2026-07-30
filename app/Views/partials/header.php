<?php
$user     = $_SESSION['user'] ?? null;
$role     = $user['role'] ?? null;
$isAdmin  = $role === 'superadmin';
$isOwner  = $role === 'shop_owner';
$isClient   = $role === 'client';
$isEmployee = $role === 'employee';
?>
<header class="fixed top-0 left-0 right-0 z-50" style="left:0;right:0;width:100%">
  <div id="hdr" class="transition-all duration-300 border-b border-transparent">
    <div class="w-full px-4 sm:px-6">
      <div class="flex items-center justify-between h-14">
        <!-- Logo — always top-left -->
        <a href="<?= path('/') ?>" class="flex items-center shrink-0 mr-6">
          <span class="font-display font-bold text-xl text-cream-light tracking-tight">Trimly<span class="text-gold">.</span></span>
        </a>
        <!-- Desktop Nav -->
        <nav class="hidden md:flex items-center gap-6">
          <?php if($isAdmin): ?>
            <a href="<?= path('/admin') ?>" class="nav-link text-sm font-semibold text-gold">Panel Admin</a>
            <a href="<?= path('/admin/locales') ?>" class="nav-link text-sm text-ink-300 hover:text-cream transition-colors">Locales</a>
            <a href="<?= path('/admin/usuarios') ?>" class="nav-link text-sm text-ink-300 hover:text-cream transition-colors">Usuarios</a>
          <?php else: ?>
            <a href="<?= path('/buscar') ?>" class="nav-link text-sm text-ink-300 hover:text-cream transition-colors">Buscar locales</a>
            <?php if($isOwner): ?><a href="<?= path('/panel') ?>" class="nav-link text-sm text-ink-300 hover:text-cream transition-colors">Mi panel</a><?php endif; ?>
            <?php if($isEmployee): ?><a href="<?= path('/mi-panel') ?>" class="nav-link text-sm text-ink-300 hover:text-cream transition-colors">Mi panel</a><?php endif; ?>
          <?php endif; ?>
        </nav>
        <!-- Desktop Auth -->
        <div class="hidden md:flex items-center gap-3">
          <?php if($user): ?>
            <?php if (!empty($user['avatar'])): ?>
            <img src="<?= upload_url($user['avatar']) ?>" alt="" class="w-7 h-7 rounded-full object-cover border border-ink-600">
            <?php endif; ?>
            <span class="text-sm text-ink-300">Hola, <span class="text-cream font-medium"><?= e(explode(' ',$user['name'])[0]) ?></span>
              <?php if($isAdmin): ?><span class="ml-1 text-[10px] bg-gold/15 text-gold border border-gold/25 px-2 py-0.5 rounded-full">Admin</span><?php endif; ?>
            </span>
            <?php if($isClient): ?>
            <a href="<?= path('/mis-turnos') ?>" class="text-sm font-medium text-ink-200 hover:text-cream border border-ink-600 hover:border-ink-400 px-3 py-1.5 rounded-lg transition-all">Mis turnos</a>
            <?php endif; ?>
            <a href="<?= path('/logout') ?>" class="text-sm text-ink-500 hover:text-ink-200 transition-colors">Salir</a>
          <?php else: ?>
            <a href="<?= path('/login') ?>" class="text-sm font-medium text-ink-200 hover:text-cream px-3 py-1.5 transition-colors">Iniciar sesión</a>
            <a href="<?= path('/registro') ?>" class="text-sm font-semibold bg-gold hover:bg-gold-500 text-ink-900 px-4 py-2 rounded-lg transition-colors">Registrarse</a>
          <?php endif; ?>
        </div>
        <!-- Mobile burger -->
        <button id="nav-btn" class="md:hidden p-2 text-ink-300 hover:text-cream" aria-label="Menú">
          <svg id="ic-open"  class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
          <svg id="ic-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
    </div>
  </div>
  <!-- Mobile menu -->
  <div id="mob-menu" class="hidden md:hidden bg-ink-800/98 backdrop-blur-sm border-t border-ink-700">
    <div class="px-4 py-4 space-y-1">
      <?php if($isAdmin): ?>
        <a href="<?= path('/admin') ?>"              class="block px-3 py-2.5 text-sm text-gold font-semibold rounded-xl hover:bg-ink-700">Panel Admin</a>
        <a href="<?= path('/admin/locales') ?>"      class="block px-3 py-2.5 text-sm text-ink-200 rounded-xl hover:bg-ink-700">Locales</a>
        <a href="<?= path('/admin/usuarios') ?>"     class="block px-3 py-2.5 text-sm text-ink-200 rounded-xl hover:bg-ink-700">Usuarios</a>
        <a href="<?= path('/admin/estadisticas') ?>" class="block px-3 py-2.5 text-sm text-ink-200 rounded-xl hover:bg-ink-700">Estadísticas</a>
      <?php else: ?>
        <a href="<?= path('/buscar') ?>" class="block px-3 py-2.5 text-sm text-ink-200 rounded-xl hover:bg-ink-700">Buscar locales</a>
        <?php if($isOwner): ?><a href="<?= path('/panel') ?>" class="block px-3 py-2.5 text-sm text-ink-200 rounded-xl hover:bg-ink-700">Mi panel</a><?php endif; ?>
        <?php if($isEmployee): ?><a href="<?= path('/mi-panel') ?>" class="block px-3 py-2.5 text-sm text-ink-200 rounded-xl hover:bg-ink-700">Mi panel</a><?php endif; ?>
      <?php endif; ?>
      <div class="border-t border-ink-700 pt-3 mt-3 space-y-1">
        <?php if($user): ?>
          <p class="px-3 py-1 text-xs text-ink-500"><?= e($user['name']) ?></p>
          <?php if($isClient): ?><a href="<?= path('/mis-turnos') ?>" class="block px-3 py-2.5 text-sm text-ink-200 rounded-xl hover:bg-ink-700">Mis turnos</a><?php endif; ?>
          <a href="<?= path('/logout') ?>" class="block px-3 py-2.5 text-sm text-red-400 rounded-xl hover:bg-ink-700">Cerrar sesión</a>
        <?php else: ?>
          <a href="<?= path('/login') ?>"   class="block px-3 py-2.5 text-sm text-ink-200 rounded-xl hover:bg-ink-700">Iniciar sesión</a>
          <a href="<?= path('/registro') ?>" class="block px-3 py-2.5 text-sm font-semibold text-gold rounded-xl hover:bg-ink-700">Registrarse</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>
<script>
const hdr=document.getElementById('hdr');
window.addEventListener('scroll',()=>{
  if(window.scrollY>30){hdr.classList.add('bg-ink-900/95','backdrop-blur-md','border-ink-700/60');hdr.classList.remove('border-transparent');}
  else{hdr.classList.remove('bg-ink-900/95','backdrop-blur-md','border-ink-700/60');hdr.classList.add('border-transparent');}
},{passive:true});
document.getElementById('nav-btn').addEventListener('click',()=>{
  document.getElementById('mob-menu').classList.toggle('hidden');
  document.getElementById('ic-open').classList.toggle('hidden');
  document.getElementById('ic-close').classList.toggle('hidden');
});
</script>
