<?php
$user    = $user ?? ($_SESSION['user'] ?? null);
$canBook = (($shop['status'] ?? 'active') === 'active') && (!$user || $user['role'] === 'client');
$pageTitle       = e($shop['name']);
$pageDescription = $shop['description']
    ? truncate($shop['description'], 160)
    : "Reservá tu turno en {$shop['name']} — {$shop['city']}.";

$typeLabels = ['barbershop'=>'Barbería','salon'=>'Salón de Belleza','mixed'=>'Local Mixto','nails'=>'Manicura & Uñas','spa'=>'Spa & Relajación','tattoo'=>'Tatuajes & Piercings','makeup'=>'Maquillaje','other'=>'Otro'];
$typeIcons  = ['barbershop'=>'✂','salon'=>'💇','mixed'=>'✨','nails'=>'💅','spa'=>'🌿','tattoo'=>'🖋','makeup'=>'💄','other'=>'🏠'];
$daysEs     = ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'];
$daysLong   = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
$typeLabel  = $typeLabels[$shop['type']] ?? 'Local';
$typeIcon   = $typeIcons[$shop['type']] ?? '✂';
?>

<div class="pt-16">

<!-- ══════════════════════════════════════════════════════
     GALERÍA HERO
══════════════════════════════════════════════════════════ -->
<div class="relative bg-ink-900">
    <?php if (!empty($photos)): ?>
    <div id="gallery" class="grid grid-cols-4 grid-rows-2 h-64 sm:h-96 gap-1 px-0">
        <?php foreach ($photos as $i => $photo): ?>
        <div class="<?= $i===0 ? 'col-span-2 row-span-2' : '' ?> overflow-hidden bg-ink-700 <?= $i>2 ? 'hidden sm:block' : '' ?>">
            <img src="<?= path('uploads/') ?><?= e($photo['filename']) ?>"
                 alt="<?= e($shop['name']) ?>"
                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-500 cursor-pointer"
                 onclick="openLightbox(<?= $i ?>)">
        </div>
        <?php endforeach; ?>
        <?php if (count($photos) > 5): ?>
        <button onclick="openLightbox(0)"
                class="absolute bottom-4 right-4 bg-ink-900/80 backdrop-blur-sm border border-ink-600 rounded-lg px-4 py-2 text-sm font-medium text-cream hover:border-gold/40 transition-all">
            📷 +<?= count($photos)-5 ?> fotos
        </button>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <div class="h-48 sm:h-64 flex items-center justify-center bg-gradient-to-br from-ink-800 to-ink-900 relative overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03]"
             style="background-image:repeating-linear-gradient(45deg,#C9A84C 0,#C9A84C 1px,transparent 0,transparent 60px)"></div>
        <div class="text-center relative z-10">
            <div class="text-7xl opacity-10 mb-3"><?= $typeIcon ?></div>
            <p class="text-xs text-ink-600 uppercase tracking-widest">Sin fotos aún</p>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- ══════════════════════════════════════════════════════
     HEADER DEL LOCAL  (sticky)
══════════════════════════════════════════════════════════ -->
<div class="bg-ink-900 border-b border-ink-700 sticky top-16 z-30">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-1.5 sm:py-3">

        <!-- Logo + nombre + CTA -->
        <div class="flex items-center gap-3 sm:gap-4">
            <!-- Logo -->
            <?php if ($shop['logo']): ?>
            <img src="<?= path('uploads/') ?><?= e($shop['logo']) ?>"
                 alt="Logo"
                 class="w-11 h-11 sm:w-14 sm:h-14 rounded-xl object-cover border border-ink-600 shrink-0">
            <?php else: ?>
            <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-xl bg-ink-700 border border-ink-600 flex items-center justify-center text-xl sm:text-2xl shrink-0">
                <?= $typeIcon ?>
            </div>
            <?php endif; ?>

            <!-- Nombre + meta -->
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                    <h1 class="font-display text-base sm:text-xl font-bold text-cream-light leading-tight truncate max-w-[180px] sm:max-w-none">
                        <?= e($shop['name']) ?>
                    </h1>
                    <span class="type-badge-<?= e($shop['type']) ?> text-[10px] sm:text-xs font-semibold px-2 py-0.5 rounded-full shrink-0">
                        <?= $typeIcon ?> <?= $typeLabel ?>
                    </span>
                    <?php if ($isOpenNow): ?>
                    <span class="hidden sm:inline-flex items-center gap-1 text-xs font-semibold text-emerald-400 bg-emerald-400/10 border border-emerald-400/20 rounded-full px-2 py-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>Abierto
                    </span>
                    <?php endif; ?>
                </div>
                <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs sm:text-sm text-ink-400 mt-0.5">
                    <?php if ($shop['city']): ?>
                    <span class="flex items-center gap-1 truncate">
                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        <?= e($shop['city']) ?><?= $shop['province'] ? ', '.e($shop['province']) : '' ?>
                    </span>
                    <?php endif; ?>
                    <?php if ($shop['rating_count'] > 0): ?>
                    <span class="flex items-center gap-1 shrink-0">
                        <span class="text-gold">★</span>
                        <span class="font-semibold text-cream"><?= number_format($shop['rating_avg'],1) ?></span>
                        <span class="text-ink-500 hidden sm:inline">(<?= $shop['rating_count'] ?> reseñas)</span>
                    </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- CTA -->
            <div class="flex items-center gap-2 shrink-0">
            <?php if ($canBook): ?>
            <a href="<?= path('/reservar/' . e($shop['slug'])) ?>"
               class="inline-flex items-center gap-1.5 bg-gold hover:bg-gold-500 text-ink-900 font-bold px-3 sm:px-5 py-2 sm:py-2.5 rounded-xl transition-all text-xs sm:text-sm whitespace-nowrap">
                <svg class="w-3.5 h-3.5 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Reservar
            </a>
            <?php endif; ?>
            <!-- Reportar local — solo clientes, no dueños/admin -->
            <?php if ($user && $user['role'] === 'client'): ?>
            <button onclick="openReportModal()"
                    title="Reportar este local"
                    class="p-2 sm:py-2.5 sm:px-3 rounded-xl border border-ink-600 text-ink-400 hover:border-red-500/50 hover:text-red-400 hover:bg-red-500/5 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                </svg>
                <span class="hidden sm:inline text-xs font-medium ml-1">Reportar</span>
            </button>
            <?php endif; ?>
            </div>
        </div>

        <!-- Nav de secciones — scroll horizontal en móvil -->
        <nav class="flex gap-0 mt-2 -mb-1.5 sm:-mb-3 overflow-x-auto scrollbar-none border-b border-ink-700/0" id="section-nav">
            <?php foreach ([
                ['#servicios', 'Servicios'],
                ['#empleados', 'El equipo'],
                ['#horarios',  'Horarios'],
                ['#resenas',   'Reseñas'],
                ['#ubicacion', 'Ubicación'],
            ] as [$href, $label]): ?>
            <a href="<?= $href ?>"
               class="section-nav-link shrink-0 text-xs sm:text-sm font-medium text-ink-400 hover:text-cream px-3 sm:px-4 py-2.5 border-b-2 border-transparent hover:border-gold/50 transition-all whitespace-nowrap">
                <?= $label ?>
            </a>
            <?php endforeach; ?>
        </nav>
    </div>
</div>


<!-- ══════════════════════════════════════════════════════
     CUERPO PRINCIPAL
══════════════════════════════════════════════════════════ -->
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-10">

    <!-- ══ BANNER SUSPENSIÓN ══ -->
    <?php if (($shop['status']??'active') === 'suspended' && !empty($shop['suspension_public'])): ?>
    <div class="relative mb-6 rounded-2xl overflow-hidden border border-red-500/40">
        <!-- Greyed-out blurred background -->
        <?php if (!empty($photos)): ?>
        <img src="<?= upload_url($photos[0]['filename']) ?>" alt=""
             class="w-full h-32 object-cover filter grayscale brightness-30 blur-sm select-none pointer-events-none">
        <?php else: ?>
        <div class="w-full h-32 bg-ink-700 filter grayscale"></div>
        <?php endif; ?>
        <!-- Overlay text -->
        <div class="absolute inset-0 bg-ink-900/80 flex flex-col items-center justify-center p-4 text-center">
            <span class="text-3xl mb-2">🚫</span>
            <p class="text-red-400 font-bold text-lg">Local suspendido</p>
            <?php if (!empty($shop['suspension_reason'])): ?>
            <p class="text-red-300/80 text-sm mt-1 max-w-md">Motivo: <?= e($shop['suspension_reason']) ?></p>
            <?php endif; ?>
            <p class="text-ink-500 text-xs mt-2">Este local no puede recibir reservas en este momento.</p>
        </div>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-10">

        <!-- ── Columna principal ── -->
        <div class="lg:col-span-2 space-y-12">

            <!-- Descripción -->
            <?php if ($shop['description']): ?>
            <section>
                <p class="text-ink-200 leading-relaxed text-sm sm:text-base" id="desc-short">
                    <?= e(truncate($shop['description'], 280)) ?>
                </p>
                <?php if (mb_strlen($shop['description']) > 280): ?>
                <p class="text-ink-400 leading-relaxed text-sm sm:text-base hidden" id="desc-full">
                    <?= e($shop['description']) ?>
                </p>
                <button onclick="toggleDesc()" id="desc-toggle"
                        class="mt-2 text-sm font-medium text-gold hover:text-gold-300 transition-colors">
                    Ver más ↓
                </button>
                <?php endif; ?>
            </section>
            <?php endif; ?>

            <!-- ══ SERVICIOS ══ -->
            <section id="servicios" class="scroll-mt-32 sm:scroll-mt-36">
                <div class="flex items-end justify-between mb-5">
                    <div>
                        <p class="gold-line text-xs font-semibold uppercase tracking-widest text-gold-400">Qué ofrecemos</p>
                        <h2 class="font-display text-xl sm:text-2xl font-bold text-cream-light">Servicios</h2>
                    </div>
                    <?php if ($canBook): ?>
                    <a href="<?= path('/reservar/' . e($shop['slug'])) ?>"
                       class="hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold text-gold hover:text-gold-300 transition-colors">
                        Reservar →
                    </a>
                    <?php endif; ?>
                </div>

                <?php if (empty($servicesGrouped)): ?>
                <p class="text-ink-500 text-sm">Este local aún no cargó sus servicios.</p>
                <?php else: ?>

                <!-- Tabs de categoría -->
                <?php $categories = array_keys($servicesGrouped); ?>
                <?php if (count($categories) > 1): ?>
                <div class="flex flex-wrap gap-2 mb-5 overflow-x-auto scrollbar-none pb-1" id="cat-tabs">
                    <button onclick="filterCat('all')" data-cat="all"
                            class="cat-tab active text-xs font-semibold px-3 py-1.5 rounded-full border transition-all border-gold/40 bg-gold/10 text-gold whitespace-nowrap">
                        Todo
                    </button>
                    <?php foreach ($categories as $cat): ?>
                    <button onclick="filterCat('<?= e(htmlspecialchars($cat, ENT_QUOTES)) ?>')" data-cat="<?= e($cat) ?>"
                            class="cat-tab text-xs font-semibold px-3 py-1.5 rounded-full border border-ink-600 text-ink-300 hover:border-ink-500 hover:text-cream transition-all whitespace-nowrap">
                        <?= e($cat) ?>
                    </button>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div class="space-y-8" id="services-container">
                    <?php foreach ($servicesGrouped as $categoryName => $services): ?>
                    <div class="service-group" data-category="<?= e($categoryName) ?>">
                        <?php if (count($categories) > 1): ?>
                        <h3 class="text-xs font-semibold uppercase tracking-widest text-ink-500 mb-3 flex items-center gap-3">
                            <span><?= e($categoryName) ?></span>
                            <span class="flex-1 h-px bg-ink-700"></span>
                        </h3>
                        <?php endif; ?>

                        <!-- Cards estilo booking -->
                        <div class="space-y-2">
                            <?php foreach ($services as $service): ?>
                            <?php
                                $bookUrl = path('/reservar/' . e($shop['slug']) . '?servicio=' . $service['id']);
                            ?>
                            <?php if ($canBook): ?>
                            <a href="<?= $bookUrl ?>"
                               class="service-card group flex items-center gap-3 sm:gap-4 p-4 bg-ink-800 border border-ink-700 rounded-xl cursor-pointer
                                      hover:border-gold/60 hover:bg-gold/5 transition-all">
                            <?php else: ?>
                            <div class="service-card group flex items-center gap-3 sm:gap-4 p-4 bg-ink-800 border border-ink-700 rounded-xl">
                            <?php endif; ?>

                                <!-- Selector visual (círculo estilo step1) -->
                                <?php if ($canBook): ?>
                                <div class="w-5 h-5 rounded-full border-2 border-ink-500 group-hover:border-gold flex-shrink-0 flex items-center justify-center transition-all">
                                    <div class="w-2.5 h-2.5 rounded-full bg-gold opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                </div>
                                <?php endif; ?>

                                <!-- Ícono / imagen -->
                                <?php if ($service['image']): ?>
                                <img src="<?= path('uploads/') ?><?= e($service['image']) ?>"
                                     alt="<?= e($service['name']) ?>"
                                     class="w-12 h-12 rounded-lg object-cover shrink-0">
                                <?php else: ?>
                                <div class="w-12 h-12 rounded-lg bg-ink-700 border border-ink-600 flex items-center justify-center text-lg shrink-0 opacity-40">
                                    <?= $typeIcon ?>
                                </div>
                                <?php endif; ?>

                                <!-- Info -->
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-cream-light group-hover:text-gold transition-colors text-sm leading-snug">
                                        <?= e($service['name']) ?>
                                    </p>
                                    <?php if ($service['description']): ?>
                                    <p class="text-xs text-ink-400 mt-0.5 line-clamp-1 hidden sm:block">
                                        <?= e(truncate($service['description'], 80)) ?>
                                    </p>
                                    <?php endif; ?>
                                    <?php if ($service['deposit_pct'] > 0): ?>
                                    <span class="inline-flex items-center text-xs text-gold-400 bg-gold/8 border border-gold/20 rounded px-1.5 py-0.5 mt-1">
                                        Seña <?= $service['deposit_pct'] ?>%
                                    </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Precio + duración -->
                                <div class="text-right shrink-0">
                                    <p class="font-bold text-cream-light text-sm group-hover:text-gold transition-colors">
                                        <?= money($service['price'], $shop['currency']) ?>
                                    </p>
                                    <p class="text-xs text-ink-500 mt-0.5 flex items-center justify-end gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <?= duracionTexto($service['duration_min']) ?>
                                    </p>
                                </div>

                            <?php if ($canBook): ?>
                            </a>
                            <?php else: ?>
                            </div>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($canBook): ?>
                <div class="mt-8 text-center">
                    <a href="<?= path('/reservar/' . e($shop['slug'])) ?>"
                       class="inline-flex items-center gap-2 bg-gold hover:bg-gold-500 text-ink-900 font-bold px-8 py-3.5 rounded-xl transition-all hover:shadow-lg hover:shadow-gold/20 text-sm">
                        Reservar turno ahora →
                    </a>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </section>


            <!-- ══ EQUIPO ══ -->
            <?php if (!empty($employees)): ?>
            <section id="empleados" class="scroll-mt-32 sm:scroll-mt-36">
                <div class="mb-5">
                    <p class="gold-line text-xs font-semibold uppercase tracking-widest text-gold-400">Quiénes somos</p>
                    <h2 class="font-display text-xl sm:text-2xl font-bold text-cream-light">El equipo</h2>
                </div>

                <div class="space-y-6">
                <?php foreach ($employees as $emp): ?>
                <!-- Tarjeta de empleado -->
                <div class="bg-ink-800 border border-ink-700 hover:border-ink-600 rounded-2xl overflow-hidden transition-all">

                    <!-- Cabecera del empleado -->
                    <div class="flex items-start gap-4 p-4 sm:p-5">
                        <?php if ($emp['avatar']): ?>
                        <img src="<?= path('uploads/') ?><?= e($emp['avatar']) ?>"
                             alt="<?= e($emp['name']) ?>"
                             class="w-14 h-14 sm:w-16 sm:h-16 rounded-full object-cover border-2 border-ink-600 shrink-0">
                        <?php else: ?>
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-ink-700 border-2 border-ink-600 flex items-center justify-center shrink-0">
                            <span class="font-display font-bold text-xl text-ink-400"><?= strtoupper(substr($emp['name'],0,1)) ?></span>
                        </div>
                        <?php endif; ?>

                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-cream-light text-base"><?= e($emp['name']) ?></p>
                            <?php if ($emp['specialty']): ?>
                            <p class="text-xs text-gold mt-0.5"><?= e($emp['specialty']) ?></p>
                            <?php endif; ?>
                            <?php if ($emp['bio']): ?>
                            <p class="text-xs text-ink-400 mt-1.5 leading-relaxed line-clamp-3"><?= e($emp['bio']) ?></p>
                            <?php endif; ?>
                            <?php if ($emp['instagram']): ?>
                            <a href="https://instagram.com/<?= e(ltrim($emp['instagram'],'@')) ?>"
                               target="_blank" rel="noopener"
                               class="inline-flex items-center gap-1 text-xs text-ink-400 hover:text-pink-400 transition-colors mt-1.5">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                @<?= e(ltrim($emp['instagram'],'@')) ?>
                            </a>
                            <?php endif; ?>
                        </div>

                        <?php if ($canBook): ?>
                        <a href="<?= path('/reservar/' . e($shop['slug']) . '?empleado=' . $emp['id']) ?>"
                           class="shrink-0 text-xs font-semibold text-ink-400 hover:text-gold border border-ink-600 hover:border-gold/40 px-3 py-1.5 rounded-lg transition-all whitespace-nowrap">
                            Reservar
                        </a>
                        <?php endif; ?>
                    </div>

                    <!-- Portfolio del empleado — cuadrícula, abre en el lightbox global -->
                    <?php
                    $portfolio12 = array_slice($emp['portfolio'], 0, 12);
                    if (!empty($portfolio12)):
                    $empGridId = 'empgrid-' . $emp['id'];
                    $empPhotosJson = json_encode(array_values(array_map(
                        fn($p) => ['url' => upload_url($p['filename']), 'cap' => $p['caption'] ?? ''],
                        $portfolio12
                    )));
                    ?>
                    <div class="px-4 pb-4 sm:px-5 sm:pb-5 border-t border-ink-700/50 pt-4">
                        <div class="flex items-center justify-between mb-2 max-w-md mx-auto lg:max-w-none">
                            <p class="text-xs text-ink-500 uppercase tracking-widest font-semibold">Trabajos</p>
                            <span class="text-sm font-bold text-white"><?= count($portfolio12) ?> foto<?= count($portfolio12)!==1?'s':'' ?></span>
                        </div>
                        <div class="grid grid-cols-3 gap-1.5 max-w-md mx-auto lg:max-w-none" id="<?= $empGridId ?>">
                            <?php foreach ($portfolio12 as $pIdx => $photo): ?>
                            <div class="group relative rounded-lg overflow-hidden cursor-pointer" style="aspect-ratio:1"
                                 onclick='globalLightboxOpen(<?= $empPhotosJson ?>, <?= $pIdx ?>)'>
                                <img src="<?= upload_url($photo['filename']) ?>"
                                     alt="<?= e($photo['caption'] ?? $emp['name']) ?>"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"
                                     loading="lazy">
                                <?php if ($photo['caption']): ?>
                                <div class="absolute bottom-0 left-0 right-0 bg-black/60 px-1.5 py-1">
                                    <p class="text-white text-[10px] leading-tight line-clamp-2 font-medium"><?= e($photo['caption']) ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
                <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Lightbox global del portfolio — un único modal compartido por todos los empleados -->
            <div id="globalLb" class="fixed inset-0 z-[70] bg-ink-900/95 hidden items-center justify-center p-4 sm:p-8"
                 onclick="if(event.target===this) globalLightboxClose()">
                <button onclick="globalLightboxPrev()"
                        class="absolute left-3 sm:left-6 top-1/2 -translate-y-1/2 w-10 h-10 bg-ink-700/80 hover:bg-ink-600 text-white rounded-full text-xl flex items-center justify-center z-10 transition-colors">‹</button>

                <div class="max-w-3xl w-full text-center">
                    <img id="globalLbImg" src="" alt=""
                         class="max-h-[80vh] w-auto max-w-full object-contain rounded-xl mx-auto">
                    <p id="globalLbCap" class="text-white text-sm mt-3 font-medium"></p>
                    <p id="globalLbCnt" class="text-ink-400 text-xs mt-1"></p>
                </div>

                <button onclick="globalLightboxNext()"
                        class="absolute right-3 sm:right-6 top-1/2 -translate-y-1/2 w-10 h-10 bg-ink-700/80 hover:bg-ink-600 text-white rounded-full text-xl flex items-center justify-center z-10 transition-colors">›</button>
                <button onclick="globalLightboxClose()"
                        class="absolute top-4 right-4 w-9 h-9 bg-ink-700/80 hover:bg-ink-600 text-white rounded-full flex items-center justify-center text-lg z-10 transition-colors">×</button>
            </div>
            <script>
            // ── Lightbox global del portfolio ──────────────────────────
            // Un solo set de funciones para TODOS los empleados de la página.
            // Cada grilla pasa su propio array de fotos al abrir.
            window._glbPhotos = [];
            window._glbIdx    = 0;

            function globalLightboxRender() {
                const p = window._glbPhotos[window._glbIdx];
                if (!p) return;
                document.getElementById('globalLbImg').src         = p.url;
                document.getElementById('globalLbCap').textContent = p.cap || '';
                document.getElementById('globalLbCnt').textContent = (window._glbIdx + 1) + ' / ' + window._glbPhotos.length;
            }

            function globalLightboxOpen(photos, idx) {
                window._glbPhotos = photos;
                window._glbIdx    = idx;
                globalLightboxRender();
                const m = document.getElementById('globalLb');
                m.classList.remove('hidden');
                m.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }

            function globalLightboxClose() {
                const m = document.getElementById('globalLb');
                m.classList.add('hidden');
                m.classList.remove('flex');
                document.body.style.overflow = '';
            }

            function globalLightboxNext() {
                if (!window._glbPhotos.length) return;
                window._glbIdx = (window._glbIdx + 1) % window._glbPhotos.length;
                globalLightboxRender();
            }

            function globalLightboxPrev() {
                if (!window._glbPhotos.length) return;
                window._glbIdx = (window._glbIdx - 1 + window._glbPhotos.length) % window._glbPhotos.length;
                globalLightboxRender();
            }

            document.addEventListener('keydown', e => {
                const m = document.getElementById('globalLb');
                if (!m || !m.classList.contains('flex')) return;
                if (e.key === 'Escape')     globalLightboxClose();
                if (e.key === 'ArrowRight') globalLightboxNext();
                if (e.key === 'ArrowLeft')  globalLightboxPrev();
            });

            // Swipe táctil — funciona igual en mobile y PC (touch vs click ya cubierto arriba)
            (function() {
                let startX = 0;
                const lb = document.getElementById('globalLb');
                lb.addEventListener('touchstart', e => { startX = e.touches[0].clientX; }, { passive: true });
                lb.addEventListener('touchend', e => {
                    const dx = e.changedTouches[0].clientX - startX;
                    if (Math.abs(dx) > 40) dx < 0 ? globalLightboxNext() : globalLightboxPrev();
                }, { passive: true });
            })();
            </script>


            <!-- ══ RESEÑAS ══ -->
            <section id="resenas" class="scroll-mt-32 sm:scroll-mt-36">
                <div class="flex items-end justify-between mb-5">
                    <div>
                        <p class="gold-line text-xs font-semibold uppercase tracking-widest text-gold-400">Lo que dicen</p>
                        <h2 class="font-display text-xl sm:text-2xl font-bold text-cream-light">Reseñas</h2>
                    </div>
                    <?php if ($shop['rating_count'] > 0): ?>
                    <div class="text-right">
                        <div class="font-display text-2xl sm:text-3xl font-bold text-gold"><?= number_format($shop['rating_avg'],1) ?></div>
                        <div class="text-xs text-ink-500"><?= $shop['rating_count'] ?> reseñas</div>
                        <div class="flex gap-0.5 mt-1 justify-end">
                            <?php
                                $avg      = (float)$shop['rating_avg'];
                                $full     = (int)floor($avg);
                                $hasHalf  = ($avg - $full) >= 0.25 && ($avg - $full) < 0.75;
                                $rounded  = ($avg - $full) >= 0.75 ? $full + 1 : $full;
                            ?>
                            <?php for ($s = 1; $s <= 5; $s++): ?>
                                <?php if ($s <= $rounded): ?>
                                    <span class="text-base" style="color:#C9A84C">★</span>
                                <?php elseif ($s === $rounded + 1 && $hasHalf): ?>
                                    <span class="text-base" style="background:linear-gradient(90deg,#C9A84C 50%,#374151 50%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">★</span>
                                <?php else: ?>
                                    <span class="text-base" style="color:#374151">★</span>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Filtros de ordenamiento -->
                <?php if (!empty($reviews['data'])): ?>
                <div class="flex flex-wrap gap-2 mb-5">
                    <?php
                        $sortOpts = ['newest'=>'Más recientes','highest'=>'Mayor puntuación','lowest'=>'Menor puntuación'];
                        $currentSort = $reviewSort ?? 'newest';
                    ?>
                    <?php foreach($sortOpts as $val => $label): ?>
                    <a href="?resenas=<?= $val ?>#resenas"
                       class="text-xs font-semibold px-3 py-1.5 rounded-full border transition-all
                              <?= $currentSort === $val
                                  ? 'border-gold/40 bg-gold/10 text-gold'
                                  : 'border-ink-600 text-ink-400 hover:border-ink-500 hover:text-cream' ?>">
                        <?= $label ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if (empty($reviews['data'])): ?>
                <div class="text-center py-10 border border-ink-700 rounded-xl bg-ink-800/40">
                    <div class="text-3xl mb-3 opacity-20">★</div>
                    <p class="text-ink-500 text-sm">Todavía no hay reseñas para este local.</p>
                    <p class="text-ink-600 text-xs mt-1">¡Reservá tu turno y sé el primero en opinar!</p>
                </div>
                <?php else: ?>

                <!-- Botón escribir reseña (solo si tiene turno completado sin reseñar) -->
                <?php if ($canReview): ?>
                <div class="mb-5">
                    <button onclick="openShopReviewModal()"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gold/10 hover:bg-gold/20 border border-gold/40 hover:border-gold/70 text-gold font-semibold rounded-xl text-sm transition-all">
                        <span class="text-base">⭐</span> Escribir reseña
                    </button>
                </div>
                <?php endif; ?>

                <div class="space-y-4" id="reviews-container">
                    <?php foreach ($reviews['data'] as $review): ?>
                    <div class="p-4 sm:p-5 bg-ink-800 border border-ink-700 rounded-xl" id="review-<?= $review['id'] ?>">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex items-center gap-3">
                                <?php if (!empty($review['client_avatar'])): ?>
                                <img src="<?= upload_url($review['client_avatar']) ?>" alt="<?= e($review['client_name']) ?>"
                                     class="w-9 h-9 rounded-full object-cover border border-ink-600 shrink-0">
                                <?php else: ?>
                                <div class="w-9 h-9 rounded-full bg-ink-700 border border-ink-600 flex items-center justify-center shrink-0">
                                    <span class="text-sm font-bold text-ink-300">
                                        <?= strtoupper(substr($review['client_name'],0,1)) ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                                <div>
                                    <p class="text-sm font-semibold text-cream-light">
                                        <?= e(explode(' ',$review['client_name'])[0]) ?>
                                        <?= isset(explode(' ',$review['client_name'])[1])
                                            ? strtoupper(substr(explode(' ',$review['client_name'])[1],0,1)).'.'
                                            : '' ?>
                                    </p>
                                    <p class="text-xs text-ink-500"><?= fecha($review['created_at']) ?></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <div class="flex gap-0.5">
                                    <?php for ($s = 1; $s <= 5; $s++): ?>
                                    <span class="text-sm" style="color:<?= $s <= (int)$review['rating'] ? '#C9A84C' : '#374151' ?>">★</span>
                                    <?php endfor; ?>
                                </div>
                                <?php if ($user): ?>
                                <button onclick="openReviewReport(<?= $review['id'] ?>)"
                                        title="Reportar reseña"
                                        class="text-ink-600 hover:text-red-400 transition-colors text-sm leading-none ml-1 p-0.5">⚑</button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ($review['comment']): ?>
                        <p class="text-sm text-ink-200 leading-relaxed"><?= e($review['comment']) ?></p>
                        <?php endif; ?>
                        <?php if ($review['reply']): ?>
                        <div class="mt-3 pl-4 border-l-2 border-gold/30">
                            <p class="text-xs font-semibold mb-1" style="color:#C9A84C">Respuesta del local</p>
                            <p class="text-xs text-ink-300 leading-relaxed"><?= e($review['reply']) ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($reviews['last_page'] > 1): ?>
                <button id="load-more-reviews"
                        data-shop="<?= e($shop['slug']) ?>"
                        data-page="2"
                        data-last="<?= $reviews['last_page'] ?>"
                        onclick="loadMoreReviews(this)"
                        class="mt-6 w-full py-3 text-sm font-medium text-ink-300 hover:text-cream border border-ink-700 hover:border-ink-500 rounded-xl transition-all">
                    Cargar más reseñas
                </button>
                <?php endif; ?>
                <?php endif; ?>
            </section>

        </div><!-- /columna principal -->


        <!-- ══ SIDEBAR ══ -->
        <div class="space-y-5">

            <!-- ─ Card Reservar ─ -->
            <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5 lg:sticky lg:top-[7rem] lg:max-h-[calc(100vh-8rem)] lg:overflow-y-auto">
                <p class="text-xs font-semibold uppercase tracking-widest text-ink-400 mb-3">Reservar turno</p>
                <?php if (isset($minPrice['min_p']) && $minPrice['min_p'] > 0): ?>
                <p class="text-sm text-ink-400 mb-4">
                    Desde <span class="font-bold text-cream-light"><?= money($minPrice['min_p'], $shop['currency']) ?></span>
                </p>
                <?php endif; ?>
                <?php if ($canBook): ?>
                <a href="<?= path('/reservar/' . e($shop['slug'])) ?>"
                   class="block w-full text-center bg-gold hover:bg-gold-500 text-ink-900 font-bold py-3 sm:py-3.5 rounded-xl transition-all hover:shadow-lg hover:shadow-gold/20 text-sm mb-3">
                    Reservar ahora
                </a>
                <?php elseif (isset($_SESSION['user'])): ?>
                <div class="text-xs text-center text-ink-400 bg-ink-700/50 border border-ink-600 rounded-xl py-3 px-4 mb-3">
                    Las cuentas de local o admin no pueden reservar turnos.
                </div>
                <?php if ($shop['phone']): ?>
                <a href="tel:<?= e(preg_replace('/\D/','',$shop['phone'])) ?>"
                   class="flex items-center justify-center gap-2 w-full text-sm font-medium text-ink-300 hover:text-cream border border-ink-600 hover:border-ink-400 py-3 rounded-xl transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/></svg>
                    <?= e($shop['phone']) ?>
                </a>
                <?php endif; ?>
                <?php if ($shop['whatsapp'] ?? false): ?>
                <a href="https://wa.me/<?= e(preg_replace('/\D/','',$shop['whatsapp'])) ?>"
                   target="_blank"
                   class="flex items-center justify-center gap-2 w-full text-sm font-medium text-emerald-400 hover:text-emerald-300 border border-emerald-500/30 hover:border-emerald-400/50 py-3 rounded-xl transition-all mt-2">
                    WhatsApp
                </a>
                <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- ─ Card Horarios ─ -->
            <div id="horarios" class="bg-ink-800 border border-ink-700 rounded-2xl p-5 scroll-mt-32 sm:scroll-mt-36">
                <h3 class="text-sm font-semibold text-cream-light mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Horarios de atención
                </h3>
                <div class="space-y-1">
                    <?php for ($d=0; $d<=6; $d++):
                        $h       = $hoursByDay[$d] ?? null;
                        $isToday = $d === $todayDow;
                        $open    = $h && $h['opens_at'] && $h['closes_at'];
                    ?>
                    <div class="flex items-center justify-between py-1.5 <?= $isToday ? 'text-cream' : 'text-ink-400' ?>">
                        <span class="text-sm <?= $isToday ? 'font-semibold' : '' ?>">
                            <?= $isToday ? '→ ' : '' ?><?= $daysLong[$d] ?>
                        </span>
                        <?php if ($open): ?>
                        <span class="text-sm <?= $isToday ? 'text-gold font-semibold' : 'text-ink-300' ?>">
                            <?= hora($h['opens_at']) ?> – <?= hora($h['closes_at']) ?>
                        </span>
                        <?php else: ?>
                        <span class="text-xs text-ink-600">Cerrado</span>
                        <?php endif; ?>
                    </div>
                    <?php endfor; ?>
                </div>
                <?php if ($isOpenNow): ?>
                <div class="mt-4 flex items-center gap-2 text-emerald-400 text-xs font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Abierto ahora
                </div>
                <?php endif; ?>
            </div>

            <!-- ─ Card Ubicación — Leaflet + Nominatim (sin API key) ─ -->
            <?php if ($shop['address'] || $shop['city']): ?>
            <div id="ubicacion" class="bg-ink-800 border border-ink-700 rounded-2xl p-5 scroll-mt-32 sm:scroll-mt-36">
                <h3 class="text-sm font-semibold text-cream-light mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    Ubicación
                </h3>

                <!-- Mapa Leaflet -->
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
                <div id="shop-map" class="rounded-xl overflow-hidden mb-3 bg-ink-700" style="height:160px"></div>
                <script>
                (function(){
                    const lat  = <?= $shop['latitude']  ? (float)$shop['latitude']  : 'null' ?>;
                    const lng  = <?= $shop['longitude'] ? (float)$shop['longitude'] : 'null' ?>;
                    const addr = <?= json_encode(trim(($shop['address'] ?? '') . ' ' . ($shop['city'] ?? '') . ' ' . ($shop['country'] ?? 'Argentina'))) ?>;

                    function initMap(la, ln) {
                        const mapEl = document.getElementById('shop-map');
                        if (!mapEl || mapEl._leaflet_id) return;

                        const map = L.map(mapEl, { zoomControl: true, scrollWheelZoom: false })
                            .setView([la, ln], 16);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a>',
                            maxZoom: 19
                        }).addTo(map);

                        // Marcador personalizado en dorado
                        const icon = L.divIcon({
                            html: `<div style="width:28px;height:28px;background:#C9A84C;border:3px solid #fff;border-radius:50% 50% 50% 0;transform:rotate(-45deg);box-shadow:0 2px 8px rgba(0,0,0,.4)"></div>`,
                            iconSize: [28, 28],
                            iconAnchor: [14, 28],
                            className: ''
                        });
                        L.marker([la, ln], { icon })
                            .addTo(map)
                            .bindPopup(`<b><?= e($shop['name']) ?></b><br><?= e($shop['address'] ?? '') ?>`)
                            .openPopup();
                    }

                    function loadLeaflet(cb) {
                        if (window.L) { cb(); return; }
                        const s = document.createElement('script');
                        s.src = 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js';
                        s.onload = cb;
                        document.head.appendChild(s);
                    }

                    if (lat && lng) {
                        loadLeaflet(() => initMap(lat, lng));
                    } else if (addr.trim()) {
                        // Nominatim con parámetros estructurados para mayor precisión
                        const street  = <?= json_encode($shop['address'] ?? '') ?>;
                        const city    = <?= json_encode($shop['city'] ?? '') ?>;
                        const state   = <?= json_encode($shop['province'] ?? '') ?>;
                        const country = <?= json_encode($shop['country'] ?? 'Argentina') ?>;

                        // Construir URL con campos separados (más preciso que q=)
                        const params = new URLSearchParams();
                        if (street)  params.set('street',  street);
                        if (city)    params.set('city',    city);
                        if (state)   params.set('state',   state);
                        if (country) params.set('country', country);
                        params.set('format', 'json');
                        params.set('limit',  '1');
                        params.set('addressdetails', '0');

                        fetch(`https://nominatim.openstreetmap.org/search?${params.toString()}`, {
                            headers: { 'Accept-Language': 'es', 'User-Agent': 'Trimly/1.0' }
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data && data[0]) {
                                const la = parseFloat(data[0].lat);
                                const ln = parseFloat(data[0].lon);
                                loadLeaflet(() => initMap(la, ln));
                                const osmLink = document.getElementById('osm-link');
                                if (osmLink) osmLink.href = `https://www.openstreetmap.org/?mlat=${la}&mlon=${ln}#map=16/${la}/${ln}`;
                                const waze = document.getElementById('waze-link');
                                if (waze) { waze.href = `https://waze.com/ul?ll=${la},${ln}&navigate=yes`; waze.style.display = ''; }
                            } else {
                                // Fallback: buscar solo ciudad si dirección no encontró resultados
                                const fallback = new URLSearchParams({ city, state, country, format:'json', limit:'1' });
                                return fetch(`https://nominatim.openstreetmap.org/search?${fallback.toString()}`, {
                                    headers: { 'Accept-Language': 'es', 'User-Agent': 'Trimly/1.0' }
                                }).then(r2 => r2.json()).then(d2 => {
                                    if (d2 && d2[0]) {
                                        loadLeaflet(() => {
                                            initMap(parseFloat(d2[0].lat), parseFloat(d2[0].lon));
                                            // Zoom más bajo si solo tenemos ciudad
                                            setTimeout(() => { try { document.getElementById('shop-map')._leaflet_map?.setZoom(13); } catch(e){} }, 500);
                                        });
                                    } else {
                                        document.getElementById('shop-map').style.display = 'none';
                                    }
                                });
                            }
                        })
                        .catch(() => document.getElementById('shop-map').style.display = 'none');
                    } else {
                        document.getElementById('shop-map').style.display = 'none';
                    }
                })();
                </script>

                <div class="space-y-1 text-sm">
                    <?php if ($shop['address']): ?>
                    <p class="text-cream"><?= e($shop['address']) ?></p>
                    <?php endif; ?>
                    <p class="text-ink-400">
                        <?= e($shop['city']) ?><?= $shop['province'] ? ', '.e($shop['province']) : '' ?><?= $shop['country'] ? ', '.e($shop['country']) : '' ?>
                    </p>
                </div>
                <div class="flex gap-3 mt-3 flex-wrap">
                    <?php
                        $osmLat = $shop['latitude']  ? (float)$shop['latitude']  : 0;
                        $osmLng = $shop['longitude'] ? (float)$shop['longitude'] : 0;
                        $osmHref = $osmLat && $osmLng
                            ? "https://www.openstreetmap.org/?mlat={$osmLat}&mlon={$osmLng}#map=16/{$osmLat}/{$osmLng}"
                            : '#';
                    ?>
                    <a id="osm-link" href="<?= $osmHref ?>" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-1.5 text-xs font-medium text-gold hover:text-gold-300 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Ver en mapa
                    </a>
                    <?php if ($osmLat && $osmLng): ?>
                    <a href="https://waze.com/ul?ll=<?= $osmLat ?>,<?= $osmLng ?>&navigate=yes"
                       target="_blank" rel="noopener"
                       id="waze-link"
                       class="inline-flex items-center gap-1.5 text-xs font-medium text-ink-400 hover:text-cream transition-colors">
                        🚗 Waze
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- ─ Card Redes ─ -->
            <?php if ($shop['instagram'] || $shop['website']): ?>
            <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5">
                <h3 class="text-sm font-semibold text-cream-light mb-4">Seguinos</h3>
                <div class="flex flex-wrap gap-2">
                    <?php if ($shop['instagram']): ?>
                    <a href="https://instagram.com/<?= e(ltrim($shop['instagram'],'@')) ?>"
                       target="_blank" rel="noopener"
                       class="flex items-center gap-1.5 text-xs font-medium text-ink-300 hover:text-pink-400 border border-ink-600 hover:border-pink-400/30 px-3 py-2 rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        @<?= e(ltrim($shop['instagram'],'@')) ?>
                    </a>
                    <?php endif; ?>
                    <?php if ($shop['website']): ?>
                    <a href="<?= e($shop['website']) ?>"
                       target="_blank" rel="noopener"
                       class="flex items-center gap-1.5 text-xs font-medium text-ink-300 hover:text-cream border border-ink-600 hover:border-ink-400 px-3 py-2 rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        Sitio web
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

        </div><!-- /sidebar -->
    </div>
</div>
</div>


<!-- ══ MODAL REPORTAR LOCAL ══ -->
<?php if ($user): ?>
<div id="reportModal"
     class="fixed inset-0 z-50 bg-ink-900/80 backdrop-blur-sm hidden items-center justify-center p-4"
     onclick="if(event.target===this) closeReportModal()">
    <div class="bg-ink-800 border border-ink-600 rounded-2xl w-full max-w-md p-6 shadow-2xl" onclick="event.stopPropagation()">

        <div class="flex items-center justify-between mb-5">
            <h3 class="font-display text-lg font-bold text-cream-light">Reportar local</h3>
            <button onclick="closeReportModal()" class="text-ink-400 hover:text-cream transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <p class="text-sm text-ink-300 mb-5">
            Ayudanos a mantener Trimly seguro. Tu reporte es anónimo y será revisado por el equipo.
        </p>

        <!-- Motivos -->
        <div class="space-y-2 mb-4" id="reportReasons">
            <?php foreach ([
                ['spam',      '🚫', 'Spam o publicidad engañosa'],
                ['fake',      '🎭', 'Negocio falso o no existe'],
                ['offensive', '⚠️', 'Contenido ofensivo o inapropiado'],
                ['closed',    '🔒', 'Local cerrado permanentemente'],
                ['other',     '💬', 'Otro motivo'],
            ] as [$val, $icon, $label]): ?>
            <label class="flex items-center gap-3 p-3 rounded-xl border border-ink-600 cursor-pointer
                          hover:border-gold/40 hover:bg-gold/5 transition-all has-[:checked]:border-gold has-[:checked]:bg-gold/5">
                <input type="radio" name="report_reason" value="<?= $val ?>" class="sr-only" required>
                <div class="w-5 h-5 rounded-full border-2 border-ink-500 flex-shrink-0 flex items-center justify-center transition-all report-radio-circle">
                    <div class="w-2.5 h-2.5 rounded-full bg-gold opacity-0 transition-opacity report-radio-dot"></div>
                </div>
                <span class="text-lg leading-none"><?= $icon ?></span>
                <span class="text-sm text-cream-light"><?= $label ?></span>
            </label>
            <?php endforeach; ?>
        </div>

        <!-- Nota opcional -->
        <textarea id="reportNote" placeholder="Detalles adicionales (opcional)…"
                  maxlength="500" rows="2"
                  class="w-full bg-ink-900 border border-ink-600 rounded-xl px-4 py-3 text-sm text-cream-light placeholder-ink-500
                         focus:outline-none focus:border-gold/50 resize-none mb-5"></textarea>

        <!-- Error -->
        <p id="reportError" class="text-red-400 text-sm mb-3 hidden"></p>

        <div class="flex gap-3">
            <button onclick="closeReportModal()"
                    class="flex-1 py-2.5 border border-ink-600 text-ink-300 rounded-xl text-sm hover:border-ink-400 transition-colors">
                Cancelar
            </button>
            <button onclick="submitReport()"
                    id="reportSubmitBtn"
                    class="flex-1 py-2.5 bg-red-500/20 hover:bg-red-500/30 text-red-400 border border-red-500/30 hover:border-red-400/50 rounded-xl text-sm font-semibold transition-all">
                Enviar reporte
            </button>
        </div>
    </div>
</div>

<script>
function openReportModal() {
    const modal = document.getElementById('reportModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
    // Limpiar estado anterior
    document.querySelectorAll('input[name="report_reason"]').forEach(r => r.checked = false);
    document.querySelectorAll('.report-radio-circle').forEach(c => c.classList.remove('border-gold'));
    document.querySelectorAll('.report-radio-dot').forEach(d => d.classList.add('opacity-0'));
    document.getElementById('reportNote').value = '';
    document.getElementById('reportError').classList.add('hidden');
    document.getElementById('reportSubmitBtn').disabled = false;
    document.getElementById('reportSubmitBtn').textContent = 'Enviar reporte';
}

function closeReportModal() {
    const modal = document.getElementById('reportModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

// Actualizar visual de radio buttons
document.querySelectorAll('input[name="report_reason"]').forEach(radio => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('input[name="report_reason"]').forEach(r => {
            const circle = r.closest('label').querySelector('.report-radio-circle');
            const dot    = r.closest('label').querySelector('.report-radio-dot');
            if (r.checked) {
                circle.classList.add('border-gold');
                dot.classList.remove('opacity-0');
            } else {
                circle.classList.remove('border-gold');
                dot.classList.add('opacity-0');
            }
        });
    });
});

async function submitReport() {
    const reason = document.querySelector('input[name="report_reason"]:checked')?.value;
    const note   = document.getElementById('reportNote').value.trim();
    const errEl  = document.getElementById('reportError');
    const btn    = document.getElementById('reportSubmitBtn');

    if (!reason) {
        errEl.textContent = 'Por favor elegí un motivo.';
        errEl.classList.remove('hidden');
        return;
    }

    btn.disabled    = true;
    btn.textContent = 'Enviando…';
    errEl.classList.add('hidden');

    try {
        const res  = await fetch('<?= path('/local/' . e($shop['slug']) . '/reportar') ?>', {
            method:  'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest'},
            body:    `_csrf=<?= csrf() ?>&reason=${encodeURIComponent(reason)}&note=${encodeURIComponent(note)}`
        });
        const data = await res.json();

        if (data.success) {
            // Mostrar confirmación dentro del modal
            document.getElementById('reportModal').querySelector('.bg-ink-800').innerHTML = `
                <div class="text-center py-6">
                    <div class="text-5xl mb-4">✅</div>
                    <h3 class="font-display text-lg font-bold text-cream-light mb-2">¡Reporte enviado!</h3>
                    <p class="text-sm text-ink-300 mb-6">Gracias por ayudarnos a mejorar Trimly. Lo revisaremos a la brevedad.</p>
                    <button onclick="closeReportModal()" class="px-6 py-2.5 bg-gold text-ink-900 font-bold rounded-xl text-sm hover:bg-gold-300 transition-colors">
                        Cerrar
                    </button>
                </div>`;
        } else {
            errEl.textContent = data.error || 'Ocurrió un error. Intentá de nuevo.';
            errEl.classList.remove('hidden');
            btn.disabled    = false;
            btn.textContent = 'Enviar reporte';
        }
    } catch(e) {
        errEl.textContent = 'Error de conexión. Intentá de nuevo.';
        errEl.classList.remove('hidden');
        btn.disabled    = false;
        btn.textContent = 'Enviar reporte';
    }
}

// Cerrar con Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && !document.getElementById('reportModal').classList.contains('hidden')) {
        closeReportModal();
    }
});
</script>
<?php endif; ?>


<!-- ══ MODAL ESCRIBIR RESEÑA ══ -->
<?php if ($canReview): ?>
<?php
    $pendingApptStmt = \App\Models\Database::getInstance()
        ->prepare("SELECT a.id FROM appointments a LEFT JOIN reviews r ON r.appointment_id=a.id WHERE a.shop_id=? AND a.client_email=? AND a.status='completed' AND r.id IS NULL ORDER BY a.date DESC LIMIT 1");
    $pendingApptStmt->execute([$shop['id'], $user['email']]);
    $pendingApptId = (int)($pendingApptStmt->fetchColumn() ?: 0);
?>
<div id="shopReviewModal"
     class="fixed inset-0 z-50 bg-ink-900/85 backdrop-blur-sm hidden items-center justify-center p-4"
     onclick="if(event.target===this) closeShopReviewModal()">
    <div class="bg-ink-800 border border-ink-600 rounded-2xl w-full max-w-md p-6 shadow-2xl" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-display text-lg font-bold text-cream-light">Tu reseña</h3>
            <button onclick="closeShopReviewModal()" class="text-ink-400 hover:text-cream transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <p class="text-xs text-ink-400 mb-5">Tenés un turno completado en este local. ¡Tu opinión ayuda a otros!</p>
        <div class="flex justify-center gap-3 mb-2" id="srStars">
            <?php for ($i = 1; $i <= 5; $i++): ?>
            <button type="button" onclick="setSrRating(<?= $i ?>)" data-val="<?= $i ?>"
                    class="sr-star text-4xl transition-all hover:scale-110 focus:outline-none"
                    style="color:#374151">★</button>
            <?php endfor; ?>
        </div>
        <p id="srRatingLabel" class="text-center text-xs text-ink-500 mb-4 h-4"></p>
        <textarea id="srComment" placeholder="Contá tu experiencia (opcional)…" maxlength="800" rows="3"
                  class="w-full bg-ink-900 border border-ink-600 rounded-xl px-4 py-3 text-sm text-cream-light placeholder-ink-500 focus:outline-none focus:border-gold/50 resize-none mb-1"></textarea>
        <p class="text-right text-xs text-ink-600 mb-4"><span id="srCharCount">0</span>/800</p>
        <p id="srError" class="text-red-400 text-sm mb-3 hidden"></p>
        <div class="flex gap-3">
            <button onclick="closeShopReviewModal()" class="flex-1 py-2.5 border border-ink-600 text-ink-300 rounded-xl text-sm hover:border-ink-400 transition-colors">Cancelar</button>
            <button onclick="submitShopReview()" id="srSubmitBtn" class="flex-1 py-2.5 bg-gold text-ink-900 font-bold rounded-xl text-sm hover:bg-gold/80 transition-all">Publicar reseña</button>
        </div>
    </div>
</div>
<script>
let srRating = 0;
const srLabels = ['','Muy malo','Regular','Bueno','Muy bueno','¡Excelente!'];
function openShopReviewModal() {
    srRating = 0;
    document.querySelectorAll('.sr-star').forEach(s => { s.style.color='#374151'; s.style.transform=''; });
    document.getElementById('srRatingLabel').textContent = '';
    document.getElementById('srComment').value = '';
    document.getElementById('srCharCount').textContent = '0';
    document.getElementById('srError').classList.add('hidden');
    const btn = document.getElementById('srSubmitBtn');
    btn.disabled = false; btn.textContent = 'Publicar reseña';
    const m = document.getElementById('shopReviewModal');
    m.classList.remove('hidden'); m.classList.add('flex');
    document.body.style.overflow = 'hidden';
}
function closeShopReviewModal() {
    const m = document.getElementById('shopReviewModal');
    m.classList.add('hidden'); m.classList.remove('flex');
    document.body.style.overflow = '';
}
function setSrRating(val) {
    srRating = val;
    document.querySelectorAll('.sr-star').forEach(s => {
        const v = parseInt(s.dataset.val);
        s.style.color     = v <= val ? '#C9A84C' : '#374151';
        s.style.transform = v <= val ? 'scale(1.15)' : 'scale(1)';
    });
    document.getElementById('srRatingLabel').textContent = srLabels[val] || '';
}
document.getElementById('srComment')?.addEventListener('input', function() {
    document.getElementById('srCharCount').textContent = this.value.length;
});
async function submitShopReview() {
    if (!srRating) {
        const e = document.getElementById('srError');
        e.textContent = 'Por favor elegí una puntuación.'; e.classList.remove('hidden'); return;
    }
    const btn = document.getElementById('srSubmitBtn');
    btn.disabled = true; btn.textContent = 'Publicando…';
    document.getElementById('srError').classList.add('hidden');
    const fd = new FormData();
    fd.append('_csrf',   '<?= csrf() ?>');
    fd.append('rating',  srRating);
    fd.append('comment', document.getElementById('srComment').value.trim());
    try {
        const res  = await fetch('<?= path('/resena/' . $pendingApptId) ?>', { method:'POST', body:fd });
        const data = await res.json();
        if (data.success) {
            document.getElementById('shopReviewModal').querySelector('.bg-ink-800').innerHTML = `
                <div class="text-center py-8">
                    <div class="text-5xl mb-4">⭐</div>
                    <h3 class="font-display text-lg font-bold text-cream-light mb-2">¡Gracias por tu reseña!</h3>
                    <p class="text-sm text-ink-300 mb-6">Tu opinión ya es visible en el perfil del local.</p>
                    <button onclick="closeShopReviewModal();location.reload()" class="px-6 py-2.5 bg-gold text-ink-900 font-bold rounded-xl text-sm">Ver reseñas</button>
                </div>`;
        } else {
            const e = document.getElementById('srError');
            e.textContent = data.error || 'No se pudo publicar.'; e.classList.remove('hidden');
            btn.disabled = false; btn.textContent = 'Publicar reseña';
        }
    } catch(err) {
        const e = document.getElementById('srError');
        e.textContent = 'Error de conexión.'; e.classList.remove('hidden');
        btn.disabled = false; btn.textContent = 'Publicar reseña';
    }
}
document.addEventListener('keydown', ev => {
    if (ev.key === 'Escape' && document.getElementById('shopReviewModal') &&
        !document.getElementById('shopReviewModal').classList.contains('hidden')) closeShopReviewModal();
});
</script>
<?php endif; ?>

<!-- ══ MODAL REPORTAR RESEÑA ══ -->
<?php if ($user): ?>
<div id="reviewReportModal"
     class="fixed inset-0 z-50 bg-ink-900/85 backdrop-blur-sm hidden items-center justify-center p-4"
     onclick="if(event.target===this) closeReviewReport()">
    <div class="bg-ink-800 border border-ink-600 rounded-2xl w-full max-w-sm p-6 shadow-2xl" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-display text-base font-bold text-cream-light">Reportar reseña</h3>
            <button onclick="closeReviewReport()" class="text-ink-400 hover:text-cream transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <p class="text-xs text-ink-400 mb-4">¿Por qué querés reportar esta reseña?</p>
        <div class="space-y-2 mb-4">
            <?php foreach ([
                ['spam',       '🚫', 'Spam o publicidad'],
                ['fake',       '🎭', 'Reseña falsa o inventada'],
                ['offensive',  '⚠️', 'Contenido ofensivo'],
                ['irrelevant', '💭', 'No tiene relación con el local'],
                ['other',      '💬', 'Otro motivo'],
            ] as [$val, $icon, $label]): ?>
            <label class="flex items-center gap-3 p-2.5 rounded-xl border border-ink-600 cursor-pointer
                          hover:border-gold/40 hover:bg-gold/5 transition-all has-[:checked]:border-gold has-[:checked]:bg-gold/5">
                <input type="radio" name="rr_reason" value="<?= $val ?>" class="sr-only">
                <div class="w-4 h-4 rounded-full border-2 border-ink-500 shrink-0 flex items-center justify-center rr-circle">
                    <div class="w-2 h-2 rounded-full bg-gold opacity-0 rr-dot"></div>
                </div>
                <span><?= $icon ?></span>
                <span class="text-sm text-cream-light"><?= $label ?></span>
            </label>
            <?php endforeach; ?>
        </div>
        <p id="rrError" class="text-red-400 text-xs mb-3 hidden"></p>
        <div class="flex gap-3">
            <button onclick="closeReviewReport()" class="flex-1 py-2.5 border border-ink-600 text-ink-300 rounded-xl text-sm hover:border-ink-400 transition-colors">Cancelar</button>
            <button onclick="submitReviewReport()" id="rrSubmitBtn" class="flex-1 py-2.5 bg-red-500/20 hover:bg-red-500/30 text-red-400 border border-red-500/30 rounded-xl text-sm font-semibold transition-all">Reportar</button>
        </div>
    </div>
</div>
<script>
let currentReviewId = null;
function openReviewReport(reviewId) {
    currentReviewId = reviewId;
    document.querySelectorAll('input[name="rr_reason"]').forEach(r => r.checked = false);
    document.querySelectorAll('.rr-circle').forEach(c => c.classList.remove('border-gold'));
    document.querySelectorAll('.rr-dot').forEach(d => d.classList.add('opacity-0'));
    document.getElementById('rrError').classList.add('hidden');
    const btn = document.getElementById('rrSubmitBtn');
    btn.disabled = false; btn.textContent = 'Reportar';
    const m = document.getElementById('reviewReportModal');
    m.classList.remove('hidden'); m.classList.add('flex');
    document.body.style.overflow = 'hidden';
}
function closeReviewReport() {
    const m = document.getElementById('reviewReportModal');
    m.classList.add('hidden'); m.classList.remove('flex');
    document.body.style.overflow = '';
    currentReviewId = null;
}
document.querySelectorAll('input[name="rr_reason"]').forEach(r => {
    r.addEventListener('change', () => {
        document.querySelectorAll('input[name="rr_reason"]').forEach(rb => {
            const c = rb.closest('label').querySelector('.rr-circle');
            const d = rb.closest('label').querySelector('.rr-dot');
            if (rb.checked) { c.classList.add('border-gold'); d.classList.remove('opacity-0'); }
            else             { c.classList.remove('border-gold'); d.classList.add('opacity-0'); }
        });
    });
});
async function submitReviewReport() {
    const reason = document.querySelector('input[name="rr_reason"]:checked')?.value;
    const errEl  = document.getElementById('rrError');
    const btn    = document.getElementById('rrSubmitBtn');
    if (!reason) { errEl.textContent = 'Elegí un motivo.'; errEl.classList.remove('hidden'); return; }
    if (!currentReviewId) return;
    btn.disabled = true; btn.textContent = 'Enviando…';
    errEl.classList.add('hidden');
    try {
        const res  = await fetch('<?= path('/resena/reportar') ?>', {
            method: 'POST',
            headers: { 'Content-Type':'application/x-www-form-urlencoded' },
            body: `_csrf=<?= csrf() ?>&review_id=${currentReviewId}&reason=${encodeURIComponent(reason)}`
        });
        const data = await res.json();
        if (data.success) {
            document.getElementById('reviewReportModal').querySelector('.bg-ink-800').innerHTML = `
                <div class="text-center py-6">
                    <div class="text-4xl mb-3">✅</div>
                    <p class="font-bold text-cream-light mb-2">Reseña reportada</p>
                    <p class="text-sm text-ink-300 mb-5">Gracias. El equipo la revisará pronto.</p>
                    <button onclick="closeReviewReport()" class="px-5 py-2 bg-gold text-ink-900 font-bold rounded-xl text-sm">Cerrar</button>
                </div>`;
        } else {
            errEl.textContent = data.error || 'Error al enviar.'; errEl.classList.remove('hidden');
            btn.disabled = false; btn.textContent = 'Reportar';
        }
    } catch(e) {
        errEl.textContent = 'Error de conexión.'; errEl.classList.remove('hidden');
        btn.disabled = false; btn.textContent = 'Reportar';
    }
}
document.addEventListener('keydown', ev => {
    if (ev.key === 'Escape' && document.getElementById('reviewReportModal') &&
        !document.getElementById('reviewReportModal').classList.contains('hidden')) closeReviewReport();
});
</script>
<?php endif; ?>

<?php if (!empty($photos)): ?>
<div id="lightbox" class="fixed inset-0 z-50 bg-ink-900/95 backdrop-blur-sm hidden items-center justify-center" onclick="closeLightbox()">
    <button onclick="closeLightbox(); event.stopPropagation()" class="absolute top-4 right-4 text-ink-300 hover:text-cream z-10">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <button onclick="lbPrev(); event.stopPropagation()" class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 text-ink-300 hover:text-cream z-10">
        <svg class="w-8 sm:w-10 h-8 sm:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <img id="lightbox-img" src="" alt="" class="max-w-[90vw] max-h-[80vh] rounded-xl object-contain" onclick="event.stopPropagation()">
    <button onclick="lbNext(); event.stopPropagation()" class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 text-ink-300 hover:text-cream z-10">
        <svg class="w-8 sm:w-10 h-8 sm:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/></svg>
    </button>
    <div id="lightbox-counter" class="absolute bottom-4 left-1/2 -translate-x-1/2 text-xs text-ink-400"></div>
</div>
<script>
const photos = <?= json_encode(array_map(fn($p)=>path('uploads/').e($p['filename']), $photos)) ?>;
let lbIdx = 0;
function openLightbox(i) {
    lbIdx = i;
    document.getElementById('lightbox-img').src = photos[i];
    document.getElementById('lightbox-counter').textContent = (i+1)+' / '+photos.length;
    document.getElementById('lightbox').classList.remove('hidden');
    document.getElementById('lightbox').classList.add('flex');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').classList.add('hidden');
    document.getElementById('lightbox').classList.remove('flex');
    document.body.style.overflow = '';
}
function lbNext() { lbIdx=(lbIdx+1)%photos.length; openLightbox(lbIdx); }
function lbPrev() { lbIdx=(lbIdx-1+photos.length)%photos.length; openLightbox(lbIdx); }
document.addEventListener('keydown', e => {
    if(!document.getElementById('lightbox').classList.contains('hidden')) {
        if(e.key==='ArrowRight') lbNext();
        if(e.key==='ArrowLeft')  lbPrev();
        if(e.key==='Escape')     closeLightbox();
    }
});
</script>
<?php endif; ?>

<script>
function filterCat(cat) {
    document.querySelectorAll('.cat-tab').forEach(btn => {
        const active = btn.dataset.cat === cat;
        btn.classList.toggle('border-gold/40', active);
        btn.classList.toggle('bg-gold/10',     active);
        btn.classList.toggle('text-gold',      active);
        btn.classList.toggle('border-ink-600', !active);
        btn.classList.toggle('text-ink-300',   !active);
    });
    document.querySelectorAll('.service-group').forEach(g => {
        g.style.display = (cat==='all' || g.dataset.category===cat) ? '' : 'none';
    });
}

function toggleDesc() {
    const short  = document.getElementById('desc-short');
    const full   = document.getElementById('desc-full');
    const toggle = document.getElementById('desc-toggle');
    if (full && short) {
        const expanded = !full.classList.contains('hidden');
        full.classList.toggle('hidden', expanded);
        short.classList.toggle('hidden', !expanded);
        toggle.textContent = expanded ? 'Ver más ↓' : 'Ver menos ↑';
    }
}

async function loadMoreReviews(btn) {
    const slug = btn.dataset.shop;
    const page = parseInt(btn.dataset.page);
    const last = parseInt(btn.dataset.last);
    btn.textContent = 'Cargando...';
    btn.disabled = true;
    try {
        const sort = new URLSearchParams(location.search).get('resenas') || 'newest';
        const res  = await fetch(`<?= base_path() ?>/local/${slug}/resenas?pagina=${page}&orden=${sort}`);
        const json = await res.json();
        const cont = document.getElementById('reviews-container');
        json.data.forEach(r => {
            const stars = Array.from({length:5},(_,i) =>
                `<span style="color:${i < r.rating ? '#C9A84C' : '#374151'};font-size:.875rem">★</span>`
            ).join('');
            const parts = (r.client_name || '').split(' ');
            const initial     = (parts[0] || '').charAt(0).toUpperCase();
            const displayName = parts[0] + (parts[1] ? ' ' + parts[1].charAt(0) + '.' : '');
            cont.insertAdjacentHTML('beforeend', `
                <div class="p-4 sm:p-5 bg-ink-800 border border-ink-700 rounded-xl" id="review-dyn-${r.id}">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-ink-700 border border-ink-600 flex items-center justify-center shrink-0">
                                <span class="text-sm font-bold text-ink-300">${initial}</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-cream-light">${displayName}</p>
                                <p class="text-xs text-ink-500">${(r.created_at||'').substring(0,10).split('-').reverse().join('/')}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex gap-0.5">${stars}</div>
                            <?php if ($user): ?>
                            <button onclick="openReviewReport(${r.id})" title="Reportar reseña"
                                class="text-ink-600 hover:text-red-400 transition-colors text-xs ml-1">⚑</button>
                            <?php endif; ?>
                        </div>
                    </div>
                    ${r.comment ? `<p class="text-sm text-ink-200 leading-relaxed">${r.comment}</p>` : ''}
                    ${r.reply ? `<div class="mt-3 pl-4 border-l-2 border-gold/30"><p class="text-xs font-semibold mb-1" style="color:#C9A84C">Respuesta del local</p><p class="text-xs text-ink-300">${r.reply}</p></div>` : ''}
                </div>`);
        });
        if (page >= last) {
            btn.remove();
        } else {
            btn.dataset.page = page + 1;
            btn.textContent  = 'Cargar más reseñas';
            btn.disabled     = false;
        }
    } catch(e) {
        btn.textContent = 'Error — intentá de nuevo';
        btn.disabled    = false;
    }
}

// Highlight del nav al hacer scroll
const sections = document.querySelectorAll('section[id], div[id="horarios"], div[id="ubicacion"]');
const navLinks  = document.querySelectorAll('.section-nav-link');
const observer  = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            navLinks.forEach(l => {
                const active = l.getAttribute('href') === '#'+entry.target.id;
                l.classList.toggle('border-gold',        active);
                l.classList.toggle('text-cream',         active);
                l.classList.toggle('border-transparent', !active);
                l.classList.toggle('text-ink-400',       !active);
            });
        }
    });
}, { rootMargin:'-20% 0px -70% 0px' });
sections.forEach(s => observer.observe(s));
</script>