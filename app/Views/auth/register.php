<?php
$pageTitle = 'Crear cuenta';
$old = $_SESSION['register_old'] ?? [];
unset($_SESSION['register_old']);
?>

<div class="min-h-screen flex items-center justify-center px-4 py-20 grain-bg relative">
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
            <p class="text-sm text-ink-400 mt-2">Creá tu cuenta gratis</p>
        </div>

        <!-- Card -->
        <div class="bg-ink-800 border border-ink-700 rounded-2xl p-8">

            <?= flash_messages() ?>

            <!-- Selector de tipo de cuenta -->
            <div class="mb-6">
                <p class="text-xs font-semibold uppercase tracking-widest text-ink-400 mb-3">Tipo de cuenta</p>
                <div class="grid grid-cols-2 gap-3" id="role-selector">

                    <label class="role-option cursor-pointer" for="role-client">
                        <input type="radio" name="role_display" id="role-client" value="client"
                               class="sr-only" <?= ($old['role'] ?? 'client') === 'client' ? 'checked' : '' ?>
                               onchange="setRole('client')">
                        <div class="role-card flex flex-col items-center gap-2 p-4 bg-ink-900 border-2 rounded-xl transition-all
                                    <?= ($old['role'] ?? 'client') === 'client' ? 'border-gold bg-gold/5' : 'border-ink-600 hover:border-ink-500' ?>">
                            <span class="text-2xl">👤</span>
                            <span class="text-sm font-semibold text-cream-light">Soy cliente</span>
                            <span class="text-xs text-ink-400 text-center leading-tight">Reservo turnos en barberías y salones</span>
                        </div>
                    </label>

                    <label class="role-option cursor-pointer" for="role-owner">
                        <input type="radio" name="role_display" id="role-owner" value="shop_owner"
                               class="sr-only" <?= ($old['role'] ?? '') === 'shop_owner' ? 'checked' : '' ?>
                               onchange="setRole('shop_owner')">
                        <div class="role-card flex flex-col items-center gap-2 p-4 bg-ink-900 border-2 rounded-xl transition-all
                                    <?= ($old['role'] ?? '') === 'shop_owner' ? 'border-gold bg-gold/5' : 'border-ink-600 hover:border-ink-500' ?>">
                            <span class="text-2xl">✂</span>
                            <span class="text-sm font-semibold text-cream-light">Tengo un local</span>
                            <span class="text-xs text-ink-400 text-center leading-tight">Registro mi barbería o salón</span>
                        </div>
                    </label>

                </div>
            </div>

            <form action="<?= path('/registro') ?>" method="POST" novalidate id="register-form">
                <input type="hidden" name="_csrf"  value="<?= e($csrf) ?>">
                <input type="hidden" name="role"   id="role-value" value="<?= e($old['role'] ?? 'client') ?>">

                <!-- Nombre -->
                <div class="mb-4">
                    <label for="name" class="block text-xs font-semibold uppercase tracking-widest text-ink-400 mb-2">
                        Nombre completo
                    </label>
                    <input type="text" id="name" name="name" autocomplete="name" required
                           placeholder="Juan Pérez"
                           value="<?= e($old['name'] ?? '') ?>"
                           class="input-gold w-full px-4 py-3.5 bg-ink-900 border border-ink-600 rounded-xl text-cream-light placeholder-ink-600 text-sm transition-all">
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-xs font-semibold uppercase tracking-widest text-ink-400 mb-2">
                        Email
                    </label>
                    <input type="email" id="email" name="email" autocomplete="email" required
                           placeholder="tu@email.com"
                           value="<?= e($old['email'] ?? '') ?>"
                           class="input-gold w-full px-4 py-3.5 bg-ink-900 border border-ink-600 rounded-xl text-cream-light placeholder-ink-600 text-sm transition-all">
                </div>

                <!-- Teléfono (opcional) -->
                <div class="mb-4">
                    <label for="phone" class="block text-xs font-semibold uppercase tracking-widest text-ink-400 mb-2">
                        Teléfono <span class="normal-case text-ink-600 font-normal">(opcional)</span>
                    </label>
                    <input type="tel" id="phone" name="phone" autocomplete="tel"
                           placeholder="+54 9 2920 000000"
                           value="<?= e($old['phone'] ?? '') ?>"
                           class="input-gold w-full px-4 py-3.5 bg-ink-900 border border-ink-600 rounded-xl text-cream-light placeholder-ink-600 text-sm transition-all">
                </div>

                <!-- Contraseña -->
                <div class="mb-4">
                    <label for="password" class="block text-xs font-semibold uppercase tracking-widest text-ink-400 mb-2">
                        Contraseña
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" autocomplete="new-password" required
                               placeholder="Mínimo 8 caracteres"
                               class="input-gold w-full px-4 py-3.5 bg-ink-900 border border-ink-600 rounded-xl text-cream-light placeholder-ink-600 text-sm transition-all pr-12"
                               oninput="checkStrength(this.value)">
                        <button type="button" onclick="togglePwd('password', this)"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-ink-500 hover:text-ink-200 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    <!-- Indicador de fortaleza -->
                    <div class="flex gap-1 mt-2" id="strength-bars">
                        <div class="h-1 flex-1 rounded bg-ink-700" id="sb-1"></div>
                        <div class="h-1 flex-1 rounded bg-ink-700" id="sb-2"></div>
                        <div class="h-1 flex-1 rounded bg-ink-700" id="sb-3"></div>
                        <div class="h-1 flex-1 rounded bg-ink-700" id="sb-4"></div>
                    </div>
                    <p class="text-xs text-ink-600 mt-1" id="strength-label"></p>
                </div>

                <!-- Confirmar contraseña -->
                <div class="mb-6">
                    <label for="password_confirm" class="block text-xs font-semibold uppercase tracking-widest text-ink-400 mb-2">
                        Repetir contraseña
                    </label>
                    <div class="relative">
                        <input type="password" id="password_confirm" name="password_confirm" autocomplete="new-password" required
                               placeholder="••••••••"
                               class="input-gold w-full px-4 py-3.5 bg-ink-900 border border-ink-600 rounded-xl text-cream-light placeholder-ink-600 text-sm transition-all pr-12"
                               oninput="checkMatch()">
                        <button type="button" onclick="togglePwd('password_confirm', this)"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-ink-500 hover:text-ink-200 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs mt-1 hidden" id="match-label"></p>
                </div>

                <!-- Términos -->
                <div class="flex items-start gap-3 mb-6">
                    <input type="checkbox" name="terms" id="terms" required
                           class="w-4 h-4 mt-0.5 rounded border-ink-600 bg-ink-900 cursor-pointer accent-gold shrink-0">
                    <label for="terms" class="text-xs text-ink-400 cursor-pointer leading-relaxed">
                        Acepto los
                        <a href="#" class="text-gold hover:text-gold-300 transition-colors">términos y condiciones</a>
                        y la
                        <a href="#" class="text-gold hover:text-gold-300 transition-colors">política de privacidad</a>
                        de Trimly.
                    </label>
                </div>

                <button type="submit"
                        class="w-full bg-gold hover:bg-gold-500 text-ink-900 font-bold py-3.5 rounded-xl transition-all hover:shadow-lg hover:shadow-gold/20 text-sm">
                    Crear cuenta gratis
                </button>
            </form>

            <div class="flex items-center gap-4 my-6">
                <div class="flex-1 h-px bg-ink-700"></div>
                <span class="text-xs text-ink-600">ó</span>
                <div class="flex-1 h-px bg-ink-700"></div>
            </div>

            <p class="text-center text-sm text-ink-400">
                ¿Ya tenés cuenta?
                <a href="<?= path('/login') ?>" class="font-semibold text-gold hover:text-gold-300 transition-colors ml-1">
                    Iniciar sesión
                </a>
            </p>
        </div>

        <div class="text-center mt-6">
            <a href="<?= path('/') ?>" class="text-xs text-ink-600 hover:text-ink-300 transition-colors">← Volver al inicio</a>
        </div>
    </div>
</div>

<script>
// Selección de rol
function setRole(role) {
    document.getElementById('role-value').value = role;
    document.querySelectorAll('.role-card').forEach(card => {
        card.classList.remove('border-gold','bg-gold/5');
        card.classList.add('border-ink-600');
    });
    const selected = document.querySelector(`input[value="${role}"]`);
    if (selected) {
        const card = selected.closest('label').querySelector('.role-card');
        card.classList.add('border-gold','bg-gold/5');
        card.classList.remove('border-ink-600');
    }
}

// Toggle visibilidad de contraseña
function togglePwd(id, btn) {
    const input = document.getElementById(id);
    input.type = input.type === 'text' ? 'password' : 'text';
    btn.querySelector('svg').style.opacity = input.type === 'text' ? '0.4' : '1';
}

// Medidor de fortaleza
function checkStrength(val) {
    let score = 0;
    if (val.length >= 8)  score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const colors = ['bg-red-500','bg-orange-400','bg-yellow-400','bg-emerald-400'];
    const labels = ['Muy débil','Regular','Buena','Muy segura'];
    const lblColors = ['text-red-400','text-orange-400','text-yellow-400','text-emerald-400'];
    for (let i = 1; i <= 4; i++) {
        const bar = document.getElementById(`sb-${i}`);
        bar.className = `h-1 flex-1 rounded ${i <= score ? colors[score-1] : 'bg-ink-700'}`;
    }
    const lbl = document.getElementById('strength-label');
    lbl.textContent = val.length ? labels[score-1] || '' : '';
    lbl.className = `text-xs mt-1 ${val.length ? lblColors[score-1] : 'text-ink-600'}`;
}

// Verificar coincidencia de contraseñas
function checkMatch() {
    const p1  = document.getElementById('password').value;
    const p2  = document.getElementById('password_confirm').value;
    const lbl = document.getElementById('match-label');
    if (!p2) { lbl.classList.add('hidden'); return; }
    lbl.classList.remove('hidden');
    if (p1 === p2) {
        lbl.textContent = '✓ Las contraseñas coinciden';
        lbl.className   = 'text-xs mt-1 text-emerald-400';
    } else {
        lbl.textContent = '✗ Las contraseñas no coinciden';
        lbl.className   = 'text-xs mt-1 text-red-400';
    }
}
</script>
