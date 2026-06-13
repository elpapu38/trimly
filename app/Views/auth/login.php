<?php $pageTitle = 'Iniciar sesión'; ?>

<div class="min-h-screen flex items-center justify-center px-4 py-20 grain-bg relative">
    <!-- Fondo -->
    <div class="absolute inset-0 bg-gradient-to-br from-ink-900 via-ink-800 to-ink-900 pointer-events-none"></div>
    <div class="absolute inset-0 opacity-[0.025]"
         style="background-image:repeating-linear-gradient(0deg,#C9A84C 0,#C9A84C 1px,transparent 0,transparent 60px),repeating-linear-gradient(90deg,#C9A84C 0,#C9A84C 1px,transparent 0,transparent 60px)">
    </div>

    <div class="relative w-full max-w-md animate-fade-up">

        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="<?= path('/') ?>" class="inline-block">
                <span class="font-display font-bold text-3xl text-cream-light">
                    Trimly<span class="text-gold">.</span>
                </span>
            </a>
            <p class="text-sm text-ink-400 mt-2">Iniciá sesión en tu cuenta</p>
        </div>

        <!-- Card -->
        <div class="bg-ink-800 border border-ink-700 rounded-2xl p-8">

            <!-- Flash messages -->
            <?= flash_messages() ?>

            <form action="<?= path('/login') ?>" method="POST" novalidate>
                <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">

                <!-- Email -->
                <div class="mb-5">
                    <label for="email" class="block text-xs font-semibold uppercase tracking-widest text-ink-400 mb-2">
                        Email
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        autocomplete="email"
                        required
                        placeholder="tu@email.com"
                        class="input-gold w-full px-4 py-3.5 bg-ink-900 border border-ink-600 rounded-xl text-cream-light placeholder-ink-600 text-sm transition-all"
                        value="<?= e($_SESSION['register_old']['email'] ?? '') ?>"
                    >
                </div>

                <!-- Contraseña -->
                <div class="mb-2">
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-xs font-semibold uppercase tracking-widest text-ink-400">
                            Contraseña
                        </label>
                        <a href="<?= path('/recuperar') ?>" class="text-xs text-gold hover:text-gold-300 transition-colors">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            autocomplete="current-password"
                            required
                            placeholder="••••••••"
                            class="input-gold w-full px-4 py-3.5 bg-ink-900 border border-ink-600 rounded-xl text-cream-light placeholder-ink-600 text-sm transition-all pr-12"
                        >
                        <button type="button" onclick="togglePwd('password', this)"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-ink-500 hover:text-ink-200 transition-colors">
                            <svg id="eye-password" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Recordarme -->
                <div class="flex items-center gap-2 mb-6 mt-4">
                    <input type="checkbox" name="remember" id="remember"
                           class="w-4 h-4 rounded border-ink-600 bg-ink-900 text-gold cursor-pointer accent-gold">
                    <label for="remember" class="text-sm text-ink-400 cursor-pointer select-none">
                        Recordar sesión por 30 días
                    </label>
                </div>

                <!-- Submit -->
                <button type="submit"
                        class="w-full bg-gold hover:bg-gold-500 text-ink-900 font-bold py-3.5 rounded-xl transition-all hover:shadow-lg hover:shadow-gold/20 text-sm">
                    Iniciar sesión
                </button>
            </form>

            <!-- Divider -->
            <div class="flex items-center gap-4 my-6">
                <div class="flex-1 h-px bg-ink-700"></div>
                <span class="text-xs text-ink-600">ó</span>
                <div class="flex-1 h-px bg-ink-700"></div>
            </div>

            <!-- Link a registro -->
            <p class="text-center text-sm text-ink-400">
                ¿No tenés cuenta?
                <a href="<?= path('/registro') ?>" class="font-semibold text-gold hover:text-gold-300 transition-colors ml-1">
                    Registrate gratis
                </a>
            </p>
        </div>

        <!-- Volver al inicio -->
        <div class="text-center mt-6">
            <a href="<?= path('/') ?>" class="text-xs text-ink-600 hover:text-ink-300 transition-colors">
                ← Volver al inicio
            </a>
        </div>
    </div>
</div>

<script>
function togglePwd(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.querySelector('svg').style.opacity = isText ? '1' : '0.4';
}
</script>
