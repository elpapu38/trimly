<?php
$pageTitle       = 'Trimly';
$pageDescription = 'Encontrá y reservá turnos en las mejores barberías y salones de belleza cerca tuyo.';
?>

<!-- ═══════════ HERO ═══════════ -->
<section class="relative min-h-screen flex flex-col justify-center grain-bg overflow-hidden pt-16">
    <div class="absolute inset-0 bg-gradient-to-br from-ink-900 via-ink-800 to-ink-900"></div>
    <!-- Grid pattern -->
    <div class="absolute inset-0 opacity-[0.03]"
         style="background-image:repeating-linear-gradient(0deg,#C9A84C 0,#C9A84C 1px,transparent 0,transparent 60px),
                                  repeating-linear-gradient(90deg,#C9A84C 0,#C9A84C 1px,transparent 0,transparent 60px);">
    </div>
    <!-- Decorative rings -->
    <div class="absolute -right-48 top-1/2 -translate-y-1/2 w-[700px] h-[700px] rounded-full border border-gold/5 pointer-events-none"></div>
    <div class="absolute -right-24 top-1/2 -translate-y-1/2 w-[450px] h-[450px] rounded-full border border-gold/8 pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="max-w-3xl">

            <!-- Badge -->
            <div class="animate-fade-in inline-flex items-center gap-2 bg-ink-700/70 backdrop-blur-sm border border-ink-600 rounded-full px-4 py-1.5 mb-8">
                <span class="w-2 h-2 rounded-full bg-gold animate-pulse"></span>
                <span class="text-xs font-medium text-ink-200 tracking-widest uppercase">
                    <?= number_format(max($stats['shops'], 1)) ?>+ locales activos en Argentina
                </span>
            </div>

            <!-- Headline -->
            <h1 class="animate-fade-up delay-1 font-display text-5xl sm:text-6xl lg:text-[4.5rem] font-bold text-cream-light leading-[1.06] mb-6 tracking-tight">
                Tu próximo<br>
                <em class="not-italic text-gold">corte perfecto</em><br>
                está a un clic.
            </h1>

            <p class="animate-fade-up delay-2 text-lg text-ink-200 leading-relaxed max-w-xl mb-10">
                Encontrá barberías y salones cerca tuyo, comparalos y reservá tu turno en segundos — sin llamadas, sin esperas.
            </p>

            <!-- Search form -->
            <form action="<?= path('/buscar') ?>" method="GET" class="animate-fade-up delay-3">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-ink-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="q" placeholder="Barbería, nombre, ciudad..."
                               class="input-gold w-full pl-12 pr-4 py-4 bg-ink-700/80 border border-ink-600 rounded-xl text-cream-light placeholder-ink-400 text-sm font-medium transition-all">
                    </div>
                    <div class="relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-ink-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        <select name="ciudad" class="input-gold pl-10 pr-8 py-4 bg-ink-700/80 border border-ink-600 rounded-xl text-cream-light text-sm font-medium appearance-none cursor-pointer transition-all min-w-[180px]">
                            <option value="">Todas las ciudades</option>
                            <?php foreach ($cities as $c): ?>
                            <option value="<?= e($c['city']) ?>"><?= e($c['city']) ?> (<?= $c['total'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="bg-gold hover:bg-gold-500 text-ink-900 font-semibold px-8 py-4 rounded-xl transition-all hover:shadow-lg hover:shadow-gold/25 whitespace-nowrap">
                        Buscar
                    </button>
                </div>
                <!-- Quick filters -->
                <div class="flex flex-wrap items-center gap-2.5 mt-4">
                    <span class="text-xs text-ink-500">Explorar:</span>
                    <a href="<?= path('/buscar?tipo=barbershop') ?>" class="text-xs font-medium px-3 py-1.5 rounded-full bg-ink-700/60 border border-ink-600 text-ink-200 hover:border-gold/40 hover:text-gold-300 transition-all">✂ Barberías</a>
                    <a href="<?= path('/buscar?tipo=salon') ?>"      class="text-xs font-medium px-3 py-1.5 rounded-full bg-ink-700/60 border border-ink-600 text-ink-200 hover:border-gold/40 hover:text-gold-300 transition-all">💇 Salones</a>
                    <a href="<?= path('/buscar?tipo=mixed') ?>"      class="text-xs font-medium px-3 py-1.5 rounded-full bg-ink-700/60 border border-ink-600 text-ink-200 hover:border-gold/40 hover:text-gold-300 transition-all">✨ Mixtos</a>
                </div>
            </form>
        </div>
    </div>
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 opacity-30 animate-bounce">
        <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/></svg>
    </div>
</section>

<!-- ═══════════ STATS ═══════════ -->
<section class="bg-ink-800 border-y border-ink-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-3 gap-6 sm:gap-10">
            <?php foreach ([
                [$stats['shops'],        'Locales activos'],
                [$stats['cities'],       'Ciudades'],
                [$stats['appointments'], 'Turnos reservados'],
            ] as [$num, $label]): ?>
            <div class="text-center" data-anim>
                <div class="font-display text-3xl sm:text-4xl font-bold text-gold mb-1"><?= number_format(max($num,0)) ?>+</div>
                <div class="text-xs sm:text-sm text-ink-300"><?= $label ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ═══════════ LOCALES DESTACADOS ═══════════ -->
<?php if (!empty($featured)): ?>
<section class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-12" data-anim>
            <div>
                <p class="gold-line text-xs font-semibold uppercase tracking-widest text-gold-400">Destacados</p>
                <h2 class="font-display text-3xl lg:text-4xl font-bold text-cream-light">Los mejores locales</h2>
            </div>
            <a href="<?= path('/buscar') ?>" class="hidden sm:flex items-center gap-2 text-sm font-medium text-gold hover:text-gold-300 transition-colors group">
                Ver todos
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($featured as $i => $shop): ?>
            <a href="<?= path('/local/' . e($shop['slug'])) ?>"
               class="card-lift group block bg-ink-800 border border-ink-700 hover:border-ink-600 rounded-2xl overflow-hidden"
               data-anim style="animation-delay:<?= (int)$i*0.08 ?>s">

                <div class="relative h-48 bg-ink-700 overflow-hidden">
                    <?php if (!empty($shop['first_photo'])): ?>
                        <img src="<?= path('uploads/') ?><?= e($shop['first_photo']) ?>" alt="<?= e($shop['name']) ?>"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-5xl opacity-10">
                            <?= $shop['type']==='barbershop' ? '✂' : ($shop['type']==='salon' ? '💇' : '✨') ?>
                        </div>
                    <?php endif; ?>
                    <div class="absolute top-3 left-3">
                        <span class="type-badge-<?= e($shop['type']) ?> text-xs font-semibold px-2.5 py-1 rounded-full">
                            <?= $shop['type']==='barbershop'?'Barbería':($shop['type']==='salon'?'Salón':'Mixto') ?>
                        </span>
                    </div>
                </div>

                <div class="p-5">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <h3 class="font-display font-semibold text-lg text-cream-light group-hover:text-gold transition-colors leading-tight">
                            <?= e($shop['name']) ?>
                        </h3>
                        <?php if ($shop['rating_count'] > 0): ?>
                        <div class="flex items-center gap-1 shrink-0 mt-0.5">
                            <span class="star-filled text-sm">★</span>
                            <span class="text-sm font-semibold text-cream"><?= number_format($shop['rating_avg'],1) ?></span>
                            <span class="text-xs text-ink-500">(<?= $shop['rating_count'] ?>)</span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($shop['city']): ?>
                    <p class="flex items-center gap-1.5 text-sm text-ink-400 mb-3">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        <?= e($shop['city']) ?><?= $shop['province'] ? ', '.e($shop['province']) : '' ?>
                    </p>
                    <?php endif; ?>
                    <?php if ($shop['description']): ?>
                    <p class="text-xs text-ink-500 leading-relaxed line-clamp-2">
                        <?= e(truncate($shop['description'], 90)) ?>
                    </p>
                    <?php endif; ?>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-xs text-ink-600">Reservar turno</span>
                        <svg class="w-4 h-4 text-gold opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-10 sm:hidden">
            <a href="<?= path('/buscar') ?>" class="inline-flex items-center gap-2 text-sm font-medium text-gold border border-gold/30 hover:border-gold/60 px-6 py-3 rounded-xl transition-all">
                Ver todos los locales →
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ═══════════ CÓMO FUNCIONA ═══════════ -->
<section class="py-20 lg:py-28 bg-ink-800/40" id="como-funciona">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-anim>
            <p class="text-xs font-semibold uppercase tracking-widest text-gold-400 mb-3">Así de simple</p>
            <h2 class="font-display text-3xl lg:text-4xl font-bold text-cream-light">Reservá en 4 pasos</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
            <?php foreach ([
                ['01','🔍','Buscá','Encontrá barberías y salones por ciudad, nombre o tipo de servicio.'],
                ['02','✂', 'Elegí el servicio','Seleccioná el corte o tratamiento que querés, con su precio y duración.'],
                ['03','👤','Elegí el profesional','Optá por el empleado que preferas o dejá que te asignen el disponible.'],
                ['04','📅','Confirmá','Elegí fecha y horario, completá tus datos y elegí si pagás ahora o en el local.'],
            ] as $i => [$num, $icon, $title, $desc]):?>
            <div data-anim style="animation-delay:<?= (int)$i*0.1 ?>s">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-ink-700 border border-ink-600 text-2xl mb-5">
                    <?= $icon ?>
                </div>
                <div class="font-mono text-xs font-bold text-gold-600 mb-1 tracking-widest"><?= $num ?></div>
                <h3 class="font-display text-xl font-semibold text-cream-light mb-2"><?= $title ?></h3>
                <p class="text-sm text-ink-300 leading-relaxed"><?= $desc ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ═══════════ CIUDADES ═══════════ -->
<?php if (!empty($cities)): ?>
<section class="py-20 lg:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10" data-anim>
            <p class="gold-line text-xs font-semibold uppercase tracking-widest text-gold-400">Dónde estamos</p>
            <h2 class="font-display text-3xl font-bold text-cream-light">Buscá por ciudad</h2>
        </div>
        <div class="flex flex-wrap gap-3">
            <?php foreach ($cities as $i => $city): ?>
            <a href="<?= path('/buscar?ciudad=' . urlencode($city['city'])) ?>"
               class="group flex items-center gap-2 px-4 py-2.5 bg-ink-800 border border-ink-700 hover:border-gold/40 hover:bg-ink-700 rounded-xl transition-all"
               data-anim style="animation-delay:<?= (int)$i*0.05 ?>s">
                <svg class="w-3.5 h-3.5 text-ink-500 group-hover:text-gold transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                </svg>
                <span class="text-sm font-medium text-ink-200 group-hover:text-cream transition-colors"><?= e($city['city']) ?></span>
                <span class="text-xs text-ink-600 group-hover:text-ink-400 transition-colors"><?= $city['total'] ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ═══════════ CTA PARA LOCALES ═══════════ -->
<section class="py-20 lg:py-28 relative overflow-hidden grain-bg">
    <div class="absolute inset-0 bg-gradient-to-br from-gold-700/8 via-ink-800 to-ink-900"></div>
    <div class="absolute top-0 right-0 w-96 h-96 rounded-full bg-gold/5 blur-3xl pointer-events-none"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center" data-anim>
        <p class="text-xs font-semibold uppercase tracking-widest text-gold-400 mb-4">¿Tenés un local?</p>
        <h2 class="font-display text-4xl lg:text-5xl font-bold text-cream-light mb-5 leading-tight">
            Llevá tu barbería<br>al siguiente nivel
        </h2>
        <p class="text-lg text-ink-200 max-w-xl mx-auto mb-10 leading-relaxed">
            Gestioná tu agenda, empleados y servicios desde un solo lugar. Recibí reservas online 24/7.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="<?= path('/registrar-local') ?>" class="bg-gold hover:bg-gold-500 text-ink-900 font-semibold px-8 py-4 rounded-xl transition-all hover:shadow-xl hover:shadow-gold/20 text-sm">
                Registrá tu local gratis →
            </a>
            <a href="#como-funciona" class="text-sm font-medium text-ink-200 hover:text-cream border border-ink-600 hover:border-ink-400 px-8 py-4 rounded-xl transition-all">
                Conocer más
            </a>
        </div>
        <p class="mt-5 text-xs text-ink-600">Sin costo de alta · Sin comisión en el plan gratuito</p>
    </div>
</section>
