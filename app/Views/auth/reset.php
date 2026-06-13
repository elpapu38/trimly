<?php $pageTitle = 'Nueva contraseña'; ?>

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
        </div>

        <div class="bg-ink-800 border border-ink-700 rounded-2xl p-8">

            <?= flash_messages() ?>

            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-ink-700 border border-ink-600 text-2xl mb-3">🔒</div>
                <h2 class="font-display text-xl font-bold text-cream-light">Creá tu nueva contraseña</h2>
                <p class="text-sm text-ink-400 mt-2">Elegí una contraseña segura para tu cuenta.</p>
            </div>

            <form action="<?= path('/reset/' . e($token)) ?>" method="POST" novalidate>
                <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">

                <div class="mb-4">
                    <label for="password" class="block text-xs font-semibold uppercase tracking-widest text-ink-400 mb-2">
                        Nueva contraseña
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                               placeholder="Mínimo 8 caracteres"
                               class="input-gold w-full px-4 py-3.5 bg-ink-900 border border-ink-600 rounded-xl text-cream-light placeholder-ink-600 text-sm transition-all pr-12"
                               oninput="checkStrength(this.value)">
                        <button type="button" onclick="togglePwd('password',this)"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-ink-500 hover:text-ink-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="flex gap-1 mt-2">
                        <div class="h-1 flex-1 rounded bg-ink-700" id="sb-1"></div>
                        <div class="h-1 flex-1 rounded bg-ink-700" id="sb-2"></div>
                        <div class="h-1 flex-1 rounded bg-ink-700" id="sb-3"></div>
                        <div class="h-1 flex-1 rounded bg-ink-700" id="sb-4"></div>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="password_confirm" class="block text-xs font-semibold uppercase tracking-widest text-ink-400 mb-2">
                        Repetir contraseña
                    </label>
                    <div class="relative">
                        <input type="password" id="password_confirm" name="password_confirm" required
                               placeholder="••••••••"
                               class="input-gold w-full px-4 py-3.5 bg-ink-900 border border-ink-600 rounded-xl text-cream-light placeholder-ink-600 text-sm transition-all pr-12"
                               oninput="checkMatch()">
                        <button type="button" onclick="togglePwd('password_confirm',this)"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-ink-500 hover:text-ink-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs mt-1 hidden" id="match-label"></p>
                </div>

                <button type="submit"
                        class="w-full bg-gold hover:bg-gold-500 text-ink-900 font-bold py-3.5 rounded-xl transition-all text-sm mb-4">
                    Guardar nueva contraseña
                </button>

                <div class="text-center">
                    <a href="<?= path('/login') ?>" class="text-sm text-ink-400 hover:text-cream transition-colors">
                        ← Volver al inicio de sesión
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function togglePwd(id, btn) {
    const input = document.getElementById(id);
    input.type = input.type === 'text' ? 'password' : 'text';
    btn.querySelector('svg').style.opacity = input.type === 'text' ? '0.4' : '1';
}
function checkStrength(val) {
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const colors = ['bg-red-500','bg-orange-400','bg-yellow-400','bg-emerald-400'];
    for (let i = 1; i <= 4; i++) {
        document.getElementById(`sb-${i}`).className = `h-1 flex-1 rounded ${i <= score ? colors[score-1] : 'bg-ink-700'}`;
    }
}
function checkMatch() {
    const p1 = document.getElementById('password').value;
    const p2 = document.getElementById('password_confirm').value;
    const lbl = document.getElementById('match-label');
    if (!p2) { lbl.classList.add('hidden'); return; }
    lbl.classList.remove('hidden');
    if (p1 === p2) {
        lbl.textContent = '✓ Las contraseñas coinciden';
        lbl.className = 'text-xs mt-1 text-emerald-400';
    } else {
        lbl.textContent = '✗ No coinciden';
        lbl.className = 'text-xs mt-1 text-red-400';
    }
}
</script>
