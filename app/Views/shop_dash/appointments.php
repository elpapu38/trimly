<?php /* app/Views/shop_dash/appointments.php */
$config = require BASE_PATH.'/config/app.php';
$statuses = $config['appointment_statuses'];
?>
<div class="flex">
  <?php include __DIR__ . '/partials/sidebar.php'; ?>
  <div class="ml-56 flex-1 p-6 min-h-screen bg-ink-900">

    <h1 class="font-display text-2xl text-cream-light mb-6">Turnos</h1>

    <!-- Filtros -->
    <form method="GET" class="flex flex-wrap gap-3 mb-6">
      <input type="date" name="date" value="<?= e($selectedDate) ?>"
             class="input-gold bg-ink-800 border border-ink-600 rounded-xl px-4 py-2 text-cream-light text-sm">
      <select name="status" class="input-gold bg-ink-800 border border-ink-600 rounded-xl px-4 py-2 text-cream-light text-sm">
        <option value="">Todos los estados</option>
        <?php foreach ($statuses as $key => $sc): ?>
          <option value="<?= $key ?>" <?= $selectedStatus === $key ? 'selected' : '' ?>><?= $sc['label'] ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="px-5 py-2 bg-gold text-ink-900 font-semibold rounded-xl text-sm hover:bg-gold-300">Filtrar</button>
      <a href="<?= path('/panel/turnos') ?>" class="px-5 py-2 border border-ink-600 text-ink-300 rounded-xl text-sm hover:border-gold/50">Limpiar</a>
    </form>

    <?php if (empty($appointments)): ?>
      <div class="text-center py-16 text-ink-400">Sin turnos para los filtros seleccionados</div>
    <?php else: ?>
    <div class="bg-ink-800 border border-ink-700 rounded-xl overflow-hidden">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-ink-700">
            <th class="text-left px-4 py-3 text-ink-400 font-medium">Hora</th>
            <th class="text-left px-4 py-3 text-ink-400 font-medium">Cliente</th>
            <th class="text-left px-4 py-3 text-ink-400 font-medium">Servicio</th>
            <th class="text-left px-4 py-3 text-ink-400 font-medium">Empleado</th>
            <th class="text-left px-4 py-3 text-ink-400 font-medium">Precio</th>
            <th class="text-left px-4 py-3 text-ink-400 font-medium">Estado</th>
            <th class="px-4 py-3"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($appointments as $a):
            $sc = $statuses[$a['status']] ?? ['label'=>$a['status'],'color'=>'gray'];
          ?>
          <tr class="border-b border-ink-700/50 hover:bg-ink-700/30 transition-colors">
            <td class="px-4 py-3 text-gold font-mono"><?= hora($a['start_time']) ?></td>
            <td class="px-4 py-3">
              <p class="text-cream-light font-medium"><?= e($a['client_name']) ?></p>
              <p class="text-ink-500 text-xs"><?= e($a['client_phone'] ?? '') ?></p>
            </td>
            <td class="px-4 py-3 text-ink-300"><?= e($a['service_name']) ?></td>
            <td class="px-4 py-3 text-ink-300"><?= e($a['employee_name']) ?></td>
            <td class="px-4 py-3 text-gold"><?= money((float)$a['price']) ?></td>
            <td class="px-4 py-3">
              <span class="px-2 py-1 rounded-full text-xs bg-<?= $sc['color'] ?>-500/20 text-<?= $sc['color'] ?>-400">
                <?= $sc['label'] ?>
              </span>
            </td>
            <td class="px-4 py-3">
              <div class="flex gap-1">
                <?php if ($a['status'] === 'pending'): ?>
                  <button onclick="changeStatus(<?= $a['id'] ?>,'confirmed')" class="px-2 py-1 bg-green-600/80 text-white rounded text-xs hover:bg-green-500">Confirmar</button>
                <?php endif; ?>
                <?php if ($a['status'] === 'confirmed'): ?>
                  <button onclick="changeStatus(<?= $a['id'] ?>,'completed')" class="px-2 py-1 bg-blue-600/80 text-white rounded text-xs hover:bg-blue-500">Completar</button>
                <?php endif; ?>
                <?php if (!in_array($a['status'], ['cancelled_client','cancelled_shop','completed'])): ?>
                  <button onclick="changeStatus(<?= $a['id'] ?>,'cancelled_shop')" class="px-2 py-1 bg-red-600/60 text-white rounded text-xs hover:bg-red-500">Cancelar</button>
                <?php endif; ?>
                <?php if ($a['status'] === 'confirmed'): ?>
                  <button onclick="changeStatus(<?= $a['id'] ?>,'no_show')" class="px-2 py-1 bg-gray-600/60 text-white rounded text-xs hover:bg-gray-500">No asistió</button>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>

<script>
const BASE_PATH = <?= json_encode(base_path()) ?>;
async function changeStatus(id, status) {
  if (!confirm('¿Cambiar el estado del turno?')) return;
  const form = new FormData();
  form.append('status', status);
  const res = await fetch(`${BASE_PATH}/panel/turnos/${id}/status`, {method:'POST', body:form});
  if (res.ok) {
    location.reload();
  } else {
    alert('Error al cambiar el estado. Recargá la página e intentá de nuevo.');
  }
}
</script>
