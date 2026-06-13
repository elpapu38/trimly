<?php $pageTitle = $pageTitle ?? 'Buscar locales'; ?>
<div class="pt-16">
  <div class="bg-ink-800/60 border-b border-ink-700/50 py-5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <form method="GET" action="<?= path('/buscar') ?>" class="flex flex-col gap-3">
        <!-- Fila 1: buscador full-width -->
        <div class="relative">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-ink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
          <input name="q" value="<?= e($query) ?>" placeholder="Nombre, ciudad, servicio..." class="w-full bg-ink-700 border border-ink-600 rounded-xl pl-9 pr-4 py-3 text-sm text-cream-light focus:border-gold focus:outline-none">
        </div>
        <!-- Fila 2: filtros + botón -->
        <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-2">
          <select name="ciudad" class="bg-ink-700 border border-ink-600 rounded-xl px-3 py-2.5 text-sm text-cream-light focus:border-gold focus:outline-none">
            <option value="">Todas las ciudades</option>
            <?php foreach($cities as $c): ?><option value="<?= e($c['city']) ?>" <?= $city===$c['city']?'selected':'' ?>><?= e($c['city']) ?> (<?= $c['total'] ?>)</option><?php endforeach; ?>
          </select>
          <select name="tipo" class="bg-ink-700 border border-ink-600 rounded-xl px-3 py-2.5 text-sm text-cream-light focus:border-gold focus:outline-none">
            <option value="">Todos los tipos</option>
            <?php foreach(['barbershop'=>'Barbería','salon'=>'Salón de Belleza','mixed'=>'Mixto / Unisex','nails'=>'Manicura & Uñas','spa'=>'Spa & Relajación','tattoo'=>'Tatuajes & Piercings','makeup'=>'Maquillaje & Estética','other'=>'Otro'] as $v=>$l): ?>
            <option value="<?=$v?>" <?=$type===$v?'selected':''?>><?=$l?></option>
            <?php endforeach; ?>
          </select>
          <select name="audiencia" class="bg-ink-700 border border-ink-600 rounded-xl px-3 py-2.5 text-sm text-cream-light focus:border-gold focus:outline-none">
            <option value="">Para todos</option>
            <option value="men"    <?= ($audience??'')==='men'   ?'selected':''?>>Caballeros</option>
            <option value="women"  <?= ($audience??'')==='women' ?'selected':''?>>Damas</option>
            <option value="unisex" <?= ($audience??'')==='unisex'?'selected':''?>>Unisex</option>
          </select>
          <button class="bg-gold hover:bg-gold-500 text-ink-900 font-semibold px-6 py-2.5 rounded-xl text-sm transition-colors w-full">Buscar</button>
        </div>
      </form>
    </div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="font-display text-xl font-bold text-cream-light">
          <?= $total > 0 ? number_format($total).' locales encontrados' : 'Sin resultados' ?>
        </h1>
        <?php if($query||$city||$type): ?>
        <p class="text-sm text-ink-400 mt-0.5">
          <?php if($query): ?>Búsqueda: <span class="text-cream-light">"<?= e($query) ?>"</span> <?php endif; ?>
          <?php if($city): ?>en <span class="text-cream-light"><?= e($city) ?></span><?php endif; ?>
        </p>
        <?php endif; ?>
      </div>
      <?php if($query||$city||$type||($audience??'')): ?>
      <a href="<?= path('/buscar') ?>" class="text-xs text-ink-400 hover:text-gold border border-ink-600 px-3 py-1.5 rounded-lg transition-colors">✕ Limpiar filtros</a>
      <?php endif; ?>
    </div>

    <?php if(empty($data)): ?>
    <div class="text-center py-20">
      <div class="text-5xl mb-4">🔍</div>
      <h2 class="font-display text-xl font-semibold text-cream-light mb-2">No encontramos resultados</h2>
      <p class="text-ink-400 mb-6">Probá con otros términos o eliminá los filtros</p>
      <a href="<?= path('/buscar') ?>" class="bg-gold text-ink-900 font-semibold px-6 py-3 rounded-xl text-sm hover:bg-gold-500 transition-colors">Ver todos los locales</a>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-10">
      <?php foreach($data as $i=>$shop): ?>
      <a href="<?= path('/local/'.e($shop['slug'] ?? '')) ?>"
         class="card-lift group flex flex-col bg-ink-800 border border-ink-700 hover:border-gold/30 rounded-2xl overflow-hidden">

        <!-- Imagen / placeholder -->
        <div class="relative h-44 bg-ink-700 overflow-hidden shrink-0">
          <?php if(!empty($shop['first_photo'])): ?>
          <img src="<?= upload_url($shop['first_photo']) ?>"
               alt="<?= e($shop['name'] ?? '') ?>"
               class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
               loading="lazy">
          <?php else: ?>
          <div class="w-full h-full flex flex-col items-center justify-center gap-2 bg-gradient-to-br from-ink-800 to-ink-700">
            <span class="text-4xl opacity-20"><?= \App\Models\Shop::typeEmoji($shop['type'] ?? null) ?></span>
            <span class="text-xs text-ink-600 uppercase tracking-widest"><?= \App\Models\Shop::typeLabel($shop['type'] ?? null) ?></span>
          </div>
          <?php endif; ?>
          <!-- Badges featured / verified -->
          <div class="absolute top-2 left-2 flex gap-1.5">
            <?php if(!empty($shop['featured'])): ?><span class="text-[10px] font-bold bg-gold text-ink-900 px-2 py-0.5 rounded-full">Destacado</span><?php endif; ?>
            <?php if(!empty($shop['verified'])): ?><span class="text-[10px] font-bold bg-blue-600/90 text-white px-2 py-0.5 rounded-full">✓ Verificado</span><?php endif; ?>
          </div>
          <!-- Rating badge encima de la imagen -->
          <?php if(!empty($shop['rating_count']) && $shop['rating_count'] > 0): ?>
          <div class="absolute bottom-2 right-2 flex items-center gap-1 bg-ink-900/80 backdrop-blur-sm rounded-full px-2 py-0.5">
            <span class="text-gold text-xs">★</span>
            <span class="text-xs font-semibold text-cream-light"><?= number_format($shop['rating_avg'],1) ?></span>
            <span class="text-[10px] text-ink-400">(<?= $shop['rating_count'] ?>)</span>
          </div>
          <?php endif; ?>
        </div>

        <!-- Info del local -->
        <div class="flex flex-col flex-1 p-4 gap-2">
          <!-- Nombre -->
          <h3 class="font-display font-semibold text-cream-light group-hover:text-gold transition-colors leading-snug text-sm sm:text-base">
            <?= e($shop['name'] ?? '') ?>
          </h3>
          <!-- Tipo + audiencia -->
          <div class="flex flex-wrap gap-1.5">
            <span class="text-[10px] px-2 py-0.5 rounded-full border border-gold/20 text-gold/80">
              <?= \App\Models\Shop::typeLabel($shop['type'] ?? null) ?>
            </span>
            <?php if(!empty($shop['target_audience']) && $shop['target_audience']!=='unisex'): ?>
            <span class="text-[10px] px-2 py-0.5 rounded-full border border-ink-600 text-ink-400">
              <?= \App\Models\Shop::audienceLabel($shop['target_audience']) ?>
            </span>
            <?php endif; ?>
          </div>
          <!-- Ciudad -->
          <p class="text-xs text-ink-400 flex items-center gap-1 mt-auto">
            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <?= !empty($shop['city']) ? e($shop['city']) : '<span class="text-ink-600 italic">Sin dirección</span>' ?>
            <?php if(!empty($shop['province']) && $shop['province'] !== $shop['city']): ?>
            <span class="text-ink-600">· <?= e($shop['province']) ?></span>
            <?php endif; ?>
          </p>
        </div>
      </a>
      <?php endforeach; ?>
    </div>

    <?php if($last_page>1): ?>
    <div class="flex justify-center gap-2 flex-wrap">
      <?php if($current_page>1): ?><a href="?<?= http_build_query(array_merge(compact('query','city','type'),['audiencia'=>$audience??'','pagina'=>$current_page-1])) ?>" class="px-4 py-2 rounded-xl text-sm bg-ink-700 text-ink-300 hover:bg-ink-600">‹ Anterior</a><?php endif; ?>
      <?php for($p=max(1,$current_page-2);$p<=min($last_page,$current_page+2);$p++): ?>
      <a href="?<?= http_build_query(array_merge(compact('query','city','type'),['audiencia'=>$audience??'','pagina'=>$p])) ?>" class="px-4 py-2 rounded-xl text-sm <?= $p===$current_page?'bg-gold text-ink-900 font-bold':'bg-ink-700 text-ink-300 hover:bg-ink-600' ?>"><?=$p?></a>
      <?php endfor; ?>
      <?php if($current_page<$last_page): ?><a href="?<?= http_build_query(array_merge(compact('query','city','type'),['audiencia'=>$audience??'','pagina'=>$current_page+1])) ?>" class="px-4 py-2 rounded-xl text-sm bg-ink-700 text-ink-300 hover:bg-ink-600">Siguiente ›</a><?php endif; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
  </div>
</div>
