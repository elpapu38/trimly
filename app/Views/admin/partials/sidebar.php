<?php
$cur = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$b   = base_path();
if ($b && str_starts_with($cur,$b)) $cur = substr($cur, strlen($b));
$nav=[['url'=>'/admin','label'=>'Dashboard','icon'=>'📊'],['url'=>'/admin/locales','label'=>'Locales','icon'=>'🏪'],['url'=>'/admin/usuarios','label'=>'Usuarios','icon'=>'👥'],['url'=>'/admin/resenas','label'=>'Reseñas','icon'=>'⭐'],['url'=>'/admin/periodos','label'=>'Períodos stats','icon'=>'📆'],['url'=>'/admin/estadisticas','label'=>'Estadísticas','icon'=>'📈']];
?>
<aside class="hidden lg:flex flex-col w-52 shrink-0 bg-ink-800 border-r border-ink-700 min-h-screen pt-4 pb-6 px-3">
  <div class="mb-5 px-2"><p class="text-[10px] font-bold uppercase tracking-widest text-ink-500">Superadmin</p></div>
  <nav class="flex-1 space-y-0.5">
    <?php foreach($nav as $item):
      $active=$cur===$item['url']||($item['url']!=='/admin'&&str_starts_with($cur,$item['url']));
      $cls=$active?'bg-gold/15 text-gold border border-gold/20':'text-ink-300 hover:bg-ink-700 hover:text-cream-light border border-transparent';
    ?>
    <a href="<?= path($item['url']) ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all <?= $cls ?>">
      <span><?= $item['icon'] ?></span><span><?= $item['label'] ?></span>
    </a>
    <?php endforeach; ?>
  </nav>
  <div class="border-t border-ink-700 pt-3 mt-3 space-y-1">
    <a href="<?= path('/') ?>"      class="flex items-center gap-2 px-3 py-2 text-xs text-ink-500 hover:text-gold rounded-lg transition-colors">↗ Ver sitio</a>
    <a href="<?= path('/cuenta/contrasena') ?>" class="flex items-center gap-2 px-3 py-2 text-xs text-ink-400 hover:text-gold rounded-lg transition-colors">⚙ Mi cuenta</a>
    <a href="<?= path('/logout') ?>" class="flex items-center gap-2 px-3 py-2 text-xs text-red-400 hover:text-red-300 rounded-lg transition-colors">⎋ Salir</a>
  </div>
</aside>
<div class="fixed bottom-0 left-0 right-0 z-40 lg:hidden bg-ink-800/95 backdrop-blur border-t border-ink-700 flex">
  <?php foreach($nav as $item): $active=$cur===$item['url']||($item['url']!=='/admin'&&str_starts_with($cur,$item['url'])); ?>
  <a href="<?= path($item['url']) ?>" class="flex-1 flex flex-col items-center gap-0.5 py-2.5 text-[10px] <?= $active?'text-gold':'text-ink-400' ?>">
    <span class="text-base"><?= $item['icon'] ?></span><span><?= $item['label'] ?></span>
  </a>
  <?php endforeach; ?>
</div>
