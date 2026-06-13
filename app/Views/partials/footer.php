<footer class="bg-ink-800 border-t border-ink-700 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">

            <!-- Brand -->
            <div class="md:col-span-2">
                <div class="font-display font-bold text-2xl text-cream-light mb-3">
                    Trimly<span class="text-gold">.</span>
                </div>
                <p class="text-sm text-ink-300 leading-relaxed max-w-xs">
                    La plataforma para encontrar y reservar turnos en las mejores barberías y salones de Argentina.
                </p>
                <div class="flex items-center gap-4 mt-5">
                    <a href="#" class="text-ink-500 hover:text-gold transition-colors" aria-label="Instagram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="#" class="text-ink-500 hover:text-gold transition-colors" aria-label="Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Links -->
            <div>
                <h4 class="text-xs font-semibold uppercase tracking-widest text-ink-400 mb-4">Plataforma</h4>
                <ul class="space-y-2.5">
                    <li><a href="<?= path('/buscar') ?>" class="text-sm text-ink-300 hover:text-cream transition-colors">Buscar locales</a></li>
                    <li><a href="<?= path('/registrar-local') ?>" class="text-sm text-ink-300 hover:text-cream transition-colors">Registrá tu local</a></li>
                    <li><a href="<?= path('/login') ?>" class="text-sm text-ink-300 hover:text-cream transition-colors">Iniciar sesión</a></li>
                    <li><a href="<?= path('/registro') ?>" class="text-sm text-ink-300 hover:text-cream transition-colors">Crear cuenta</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-xs font-semibold uppercase tracking-widest text-ink-400 mb-4">Soporte</h4>
                <ul class="space-y-2.5">
                    <li><a href="#" class="text-sm text-ink-300 hover:text-cream transition-colors">Cómo funciona</a></li>
                    <li><a href="#" class="text-sm text-ink-300 hover:text-cream transition-colors">Preguntas frecuentes</a></li>
                    <li><a href="#" class="text-sm text-ink-300 hover:text-cream transition-colors">Términos y condiciones</a></li>
                    <li><a href="#" class="text-sm text-ink-300 hover:text-cream transition-colors">Privacidad</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-ink-700 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-ink-500">© <?= date('Y') ?> Trimly. Todos los derechos reservados.</p>
            <p class="text-xs text-ink-600">Hecho con cuidado en Argentina 🇦🇷</p>
        </div>
    </div>
</footer>
