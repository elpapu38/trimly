<?php $pageTitle = 'Recuperar contraseña'; ?>

<div class="min-h-screen flex items-center justify-center px-4 py-20 grain-bg relative">
    <div class="absolute inset-0 bg-gradient-to-br from-ink-900 via-ink-800 to-ink-900 pointer-events-none"></div>
    <div class="absolute inset-0 opacity-[0.025]"
         style="background-image:repeating-linear-gradient(0deg,#C9A84C 0,#C9A84C 1px,transparent 0,transparent 60px),repeating-linear-gradient(90deg,#C9A84C 0,#C9A84C 1px,transparent 0,transparent 60px)">
    </div>

    <div class="relative w-full max-w-md animate-fade-up">

        <div class="text-center mb-8">
            <a href="<?= path('/') ?>" class="inline-block">
                <span class="font-display font-bold text-3xl text-cream-light">Trimly<span class="text-gold">.</span></span>
            </a>
            <p class="text-sm text-ink-400 mt-2">Recuperá el acceso a tu cuenta</p>
        </div>

        <div class="bg-ink-800 border border-ink-700 rounded-2xl p-8">

            <?= flash_messages() ?>

            <!-- Ícono decorativo -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-ink-700 border border-ink-600 text-2xl mb-3">🔑</div>
                <h2 class="font-display text-xl font-bold text-cream-light">¿Olvidaste tu contraseña?</h2>
                <p class="text-sm text-ink-400 mt-2 leading-relaxed">
                    Ingresá tu email y te enviamos un enlace para crear una nueva contraseña.
                </p>
            </div>

            <form action="<?= path('/recuperar') ?>" method="POST" novalidate>
                <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">

                <div class="mb-6">
                    <label for="email" class="block text-xs font-semibold uppercase tracking-widest text-ink-400 mb-2">
                        Email
                    </label>
                    <input type="email" id="email" name="email" required autocomplete="email"
                           placeholder="tu@email.com"
                           class="input-gold w-full px-4 py-3.5 bg-ink-900 border border-ink-600 rounded-xl text-cream-light placeholder-ink-600 text-sm transition-all">
                </div>

                <button type="submit"
                        class="w-full bg-gold hover:bg-gold-500 text-ink-900 font-bold py-3.5 rounded-xl transition-all hover:shadow-lg hover:shadow-gold/20 text-sm mb-4">
                    Enviar enlace de recuperación
                </button>

                <div class="text-center">
                    <a href="<?= path('/login') ?>" class="text-sm text-ink-400 hover:text-cream transition-colors">
                        ← Volver al inicio de sesión
                    </a>
                </div>
            </form>
        </div>

        <!-- Info de seguridad -->
        <div class="mt-6 p-4 bg-ink-800/50 border border-ink-700/50 rounded-xl">
            <div class="flex items-start gap-3">
                <svg class="w-4 h-4 text-ink-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs text-ink-500 leading-relaxed">
                    Por seguridad, el enlace de recuperación expira en <strong class="text-ink-400">1 hora</strong>.
                    Si no lo ves en tu bandeja, revisá la carpeta de spam.
                </p>
            </div>
        </div>
    </div>
</div>
