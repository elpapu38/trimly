<?php /* app/Views/booking/step4.php */
$currentStep = 4;
$errors  = $_SESSION['form_errors'] ?? [];
$old     = $_SESSION['form_old'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_old']);
$diasES = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
$dayName = $diasES[(int)date('w', strtotime($booking['date']))] ?? '';
?>
<div class="min-h-screen bg-ink-900 py-10">
<div class="max-w-3xl mx-auto px-4">

  <?php include __DIR__ . '/partials/steps.php'; ?>

  <h1 class="font-display text-2xl text-cream-light mb-6">Confirmá tu turno</h1>

  <!-- Resumen del turno -->
  <div class="p-5 bg-ink-800 border border-gold/30 rounded-2xl mb-8">
    <h2 class="font-semibold text-gold mb-4 text-sm uppercase tracking-wide">Resumen</h2>
    <div class="grid grid-cols-2 gap-y-3 text-sm">
      <div>
        <p class="text-ink-400">Local</p>
        <p class="text-cream-light font-medium"><?= e($shop['name']) ?></p>
      </div>
      <div>
        <p class="text-ink-400">Servicio</p>
        <p class="text-cream-light font-medium"><?= e($booking['service']['name']) ?></p>
      </div>
      <div>
        <p class="text-ink-400">Profesional</p>
        <p class="text-cream-light font-medium"><?= e($booking['employee_name']) ?></p>
      </div>
      <div>
        <p class="text-ink-400">Duración</p>
        <p class="text-cream-light font-medium"><?= duracionTexto((int)$booking['service']['duration_min']) ?></p>
      </div>
      <div>
        <p class="text-ink-400">Fecha</p>
        <p class="text-cream-light font-medium"><?= $dayName . ' ' . fecha($booking['date'], 'd/m/Y') ?></p>
      </div>
      <div>
        <p class="text-ink-400">Horario</p>
        <p class="text-cream-light font-medium"><?= hora($booking['start_time']) ?> hs</p>
      </div>
      <div class="col-span-2 pt-3 border-t border-ink-700 flex justify-between">
        <p class="text-ink-400">Total</p>
        <p class="text-gold text-lg font-bold"><?= money((float)$booking['service']['price']) ?></p>
      </div>
    </div>
  </div>

  <form method="POST" action="<?= path('/reservar/' . e($shop['slug']) . '/confirmar') ?>">
    <input type="hidden" name="_csrf" value="<?= csrf() ?>">

    <h2 class="font-semibold text-cream-light mb-4">Tus datos</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
      <div>
        <label class="block text-sm text-ink-300 mb-1">Nombre completo *</label>
        <input type="text" name="client_name" required
               value="<?= e($old['client_name'] ?? $user['name'] ?? '') ?>"
               class="input-gold w-full bg-ink-800 border <?= isset($errors['client_name']) ? 'border-red-500' : 'border-ink-600' ?> rounded-xl px-4 py-3 text-cream-light">
        <?php if (!empty($errors['client_name'])): ?>
          <p class="text-red-400 text-xs mt-1"><?= e($errors['client_name'][0]) ?></p>
        <?php endif; ?>
      </div>
      <div>
        <label class="block text-sm text-ink-300 mb-1">Email *</label>
        <input type="email" name="client_email" required
               value="<?= e($old['client_email'] ?? $user['email'] ?? '') ?>"
               class="input-gold w-full bg-ink-800 border <?= isset($errors['client_email']) ? 'border-red-500' : 'border-ink-600' ?> rounded-xl px-4 py-3 text-cream-light">
        <?php if (!empty($errors['client_email'])): ?>
          <p class="text-red-400 text-xs mt-1"><?= e($errors['client_email'][0]) ?></p>
        <?php endif; ?>
      </div>
      <div>
        <label class="block text-sm text-ink-300 mb-1">Teléfono</label>
        <input type="tel" name="client_phone"
               value="<?= e($old['client_phone'] ?? $user['phone'] ?? '') ?>"
               class="input-gold w-full bg-ink-800 border border-ink-600 rounded-xl px-4 py-3 text-cream-light">
      </div>
      <div>
        <label class="block text-sm text-ink-300 mb-1">Nota para el local (opcional)</label>
        <input type="text" name="notes"
               value="<?= e($old['notes'] ?? '') ?>"
               placeholder="Ej: preferencia de corte"
               class="input-gold w-full bg-ink-800 border border-ink-600 rounded-xl px-4 py-3 text-cream-light">
      </div>
    </div>

    <!-- Pago -->
    <div class="mb-6">
      <p class="text-sm text-ink-300 mb-3 font-medium">¿Cómo querés pagar?</p>
      <?php if ($depositPct > 0): ?>
      <!-- Servicio con seña — mostrar 3 opciones -->
      <div class="p-3 bg-amber-500/10 border border-amber-500/30 rounded-xl mb-3 text-xs text-amber-300">
        Este servicio requiere una seña del <strong><?= $depositPct ?>%</strong>
        (<?= money($depositAmt, $shop['currency']) ?>) para confirmar el turno.
        Podés pagarla ahora online o pagar el monto completo de una vez.
        También podés acordar el pago directamente en el local.
      </div>
      <div class="space-y-2">
        <label class="flex items-center gap-3 p-4 bg-ink-800 border border-ink-600 rounded-xl cursor-pointer has-[:checked]:border-gold has-[:checked]:bg-gold/5">
          <input type="radio" name="payment_option" value="deposit" checked class="sr-only">
          <div class="w-5 h-5 rounded-full border-2 border-ink-500 flex-shrink-0 radio-dot"></div>
          <div class="flex-1">
            <p class="text-cream-light font-medium text-sm">Pagar seña ahora <span class="text-gold"><?= money($depositAmt, $shop['currency']) ?></span></p>
            <p class="text-xs text-ink-400">El resto (<?= money($booking['service']['price'] - $depositAmt, $shop['currency']) ?>) lo abonás en el local</p>
          </div>
        </label>
        <label class="flex items-center gap-3 p-4 bg-ink-800 border border-ink-600 rounded-xl cursor-pointer has-[:checked]:border-gold has-[:checked]:bg-gold/5">
          <input type="radio" name="payment_option" value="online" class="sr-only">
          <div class="w-5 h-5 rounded-full border-2 border-ink-500 flex-shrink-0 radio-dot"></div>
          <div>
            <p class="text-cream-light font-medium text-sm">Pagar total ahora <span class="text-gold"><?= money($booking['service']['price'], $shop['currency']) ?></span></p>
            <p class="text-xs text-ink-400">Pago completo vía MercadoPago</p>
          </div>
        </label>
        <!-- No se ofrece "pagar en local" cuando hay seña: el local la pide para garantizar la asistencia -->
      </div>
      <?php else: ?>
      <!-- Servicio sin seña — opciones originales -->
      <div class="grid grid-cols-2 gap-3">
        <label class="flex items-center gap-3 p-4 bg-ink-800 border border-ink-600 rounded-xl cursor-pointer has-[:checked]:border-gold has-[:checked]:bg-gold/5">
          <input type="radio" name="payment_option" value="on_site" checked class="sr-only">
          <div class="w-5 h-5 rounded-full border-2 border-ink-500 flex-shrink-0 radio-dot"></div>
          <div>
            <p class="text-cream-light font-medium text-sm">En el local</p>
            <p class="text-xs text-ink-400">Pagás el día del turno</p>
          </div>
        </label>
        <label class="flex items-center gap-3 p-4 bg-ink-800 border border-ink-600 rounded-xl cursor-pointer has-[:checked]:border-gold has-[:checked]:bg-gold/5">
          <input type="radio" name="payment_option" value="online" class="sr-only">
          <div class="w-5 h-5 rounded-full border-2 border-ink-500 flex-shrink-0 radio-dot"></div>
          <div>
            <p class="text-cream-light font-medium text-sm">Online ahora</p>
            <p class="text-xs text-ink-400">Vía MercadoPago</p>
          </div>
        </label>
      </div>
      <?php endif; ?>
    </div>

    <div class="flex justify-between mt-8">
      <a href="<?= path('/reservar/' . e($shop['slug']) . '/horario') ?>" class="px-6 py-3 border border-ink-600 text-ink-300 rounded-full hover:border-gold/50 transition-colors">
        ← Atrás
      </a>
      <button type="submit" class="px-8 py-3 bg-gold text-ink-900 font-bold rounded-full hover:bg-gold-300 transition-colors">
        Confirmar turno
      </button>
    </div>
  </form>
</div>
</div>

<style>
label:has(input[type=radio]:checked) .w-5 { border-color:#C9A84C; background:rgba(201,168,76,0.3); }
</style>
