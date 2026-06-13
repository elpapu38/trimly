<?php
/* app/Views/employee_dash/partials/sidebar.php */
$user = $_SESSION['user'] ?? [];
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = base_path();
$nav = [
    '/mi-panel'           => ['🏠', 'Dashboard'],
    '/mi-panel/turnos'    => ['📅', 'Mis turnos'],
    '/mi-panel/nuevo-turno' => ['➕', 'Cargar turno'],
    '/mi-panel/servicios' => ['✂', 'Mis servicios'],
    '/mi-panel/fotos'     => ['📷', 'Mis fotos'],
    '/mi-panel/perfil'    => ['👤', 'Mi perfil'],
];
?>
<aside class="w-56 shrink-0 bg-ink-800 border-r border-ink-700 min-h-screen fixed top-0 left-0 pt-16 z-20 hidden lg:flex flex-col">
  <div class="px-4 py-4 border-b border-ink-700">
    <p class="text-xs text-ink-500 uppercase tracking-widest mb-1">Empleado</p>
    <p class="font-semibold text-cream-light text-sm truncate"><?= e($employee['name'] ?? $user['name'] ?? '') ?></p>
    <p class="text-xs text-gold truncate"><?= e($employee['shop_name'] ?? '') ?></p>
  </div>
  <nav class="flex-1 px-2 py-4 space-y-0.5">
    <?php foreach ($nav as $path => [$icon, $label]): ?>
    <?php $active = str_starts_with($currentPath, $base . $path) && ($path === '/mi-panel' ? $currentPath === $base . '/mi-panel' : true); ?>
    <a href="<?= path($path) ?>"
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
              <?= $active ? 'bg-gold/10 text-gold border border-gold/20' : 'text-ink-400 hover:text-cream-light hover:bg-ink-700/50' ?>">
      <span class="text-base w-5 text-center"><?= $icon ?></span>
      <?= $label ?>
    </a>
    <?php endforeach; ?>
  </nav>
  <div class="px-4 py-4 border-t border-ink-700">
    <a href="<?= path('/logout') ?>" class="text-xs text-ink-500 hover:text-red-400 transition-colors">Cerrar sesión</a>
  </div>
</aside>
