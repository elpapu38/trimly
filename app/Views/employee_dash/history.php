<?php /* app/Views/employee_dash/history.php */ ?>
<div class="flex min-h-screen pt-16 pb-20 lg:pb-6">
  <?php require __DIR__ . '/partials/sidebar.php'; ?>
  <main class="flex-1 p-4 sm:p-6 lg:p-8 lg:ml-56 overflow-x-hidden">

    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
      <div>
        <h1 class="font-display text-2xl font-bold text-cream-light">Historial</h1>
        <p class="text-xs text-ink-500 mt-1"><?= $total ?> turno<?= $total!==1?'s':'' ?> pasados</p>
      </div>
      <a href="<?= path('/mi-panel/turnos') ?>"
         class="px-4 py-2 border border-ink-600 text-ink-400 rounded-xl text-sm hover:border-ink-500">
        ← Mis turnos
      </a>
    </div>

    <!-- Filtro estado -->
    <form method="GET" class="flex flex-wrap gap-2 mb-4">
      <input type="hidden" name="view" value="history">
      <select name="status" class="input-gold bg-ink-800 border border-ink-600 rounded-xl px-4 py-2 text-cream-light text-sm">
        <option value="">Todos los estados</option>
        <?php foreach ($statuses as $key => $sc): ?>
        <option value="<?= $key ?>" <?= $selectedStatus === $key ? 'selected' : '' ?>><?= $sc['label'] ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="px-4 py-2 bg-gold text-ink-900 font-semibold rounded-xl text-sm">Filtrar</button>
    </form>

    <?php if (empty($appointments)): ?>
    <div class="text-center py-16 border border-dashed border-ink-700 rounded-2xl text-ink-400">
      Sin turnos en el historial.
    </div>
    <?php else: ?>
    <div class="bg-ink-800 border border-ink-700 rounded-xl overflow-x-auto">
      <table class="w-full text-sm min-w-[520px]">
        <thead>
          <tr class="border-b border-ink-700">
            <th class="text-left px-4 py-3 text-ink-400 font-medium">Fecha</th>
            <th class="text-left px-4 py-3 text-ink-400 font-medium">Cliente</th>
            <th class="text-left px-4 py-3 text-ink-400 font-medium">Servicio</th>
            <th class="text-left px-4 py-3 text-ink-400 font-medium">Estado</th>
            <th class="px-4 py-3"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($appointments as $a):
            $sc = $statuses[$a['status']] ?? ['label'=>$a['status'],'color'=>'gray'];
          ?>
          <tr class="border-b border-ink-700/50 hover:bg-ink-700/20">
            <td class="px-4 py-3 text-gold font-mono text-xs">
              <?= date('d/m/Y', strtotime($a['date'])) ?>
              <span class="block text-ink-400"><?= hora($a['start_time']) ?></span>
            </td>
            <td class="px-4 py-3">
              <p class="text-cream-light"><?= e($a['client_name']) ?></p>
              <p class="text-xs text-ink-500"><?= e($a['client_phone']??'') ?></p>
            </td>
            <td class="px-4 py-3 text-ink-300 text-xs">
              <?= e($a['service_name']) ?>
              <span class="block text-ink-500"><?= duracionTexto((int)$a['duration_min']) ?></span>
            </td>
            <td class="px-4 py-3">
              <span class="px-2 py-1 rounded-full text-xs bg-<?= $sc['color'] ?>-500/20 text-<?= $sc['color'] ?>-400">
                <?= $sc['label'] ?>
              </span>
            </td>
            <td class="px-4 py-3">
              <?php if ($a['status'] === 'confirmed'): ?>
              <div class="flex gap-1 flex-wrap">
                <button onclick="changeStatus(<?= $a['id'] ?>,'completed')"
                        class="px-2 py-1 bg-blue-600/80 text-white rounded text-xs hover:bg-blue-500">Asistió</button>
                <button onclick="changeStatus(<?= $a['id'] ?>,'no_show')"
                        class="px-2 py-1 bg-gray-600/60 text-white rounded text-xs hover:bg-gray-500">No asistió</button>
              </div>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php if ($lastPage > 1): ?>
    <div class="flex flex-wrap gap-2 justify-center mt-4">
      <?php for ($pg = 1; $pg <= $lastPage; $pg++): ?>
      <a href="?view=history&page=<?= $pg ?>&status=<?= urlencode($selectedStatus??'') ?>"
         class="px-3 py-1.5 rounded-lg text-sm <?= $pg===$page?'bg-gold text-ink-900 font-bold':'bg-ink-700 text-ink-300 hover:bg-ink-600' ?>">
        <?= $pg ?>
      </a>
      <?php endfor; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
  </main>
</div>

<script>
const BASE_PATH = <?= json_encode(base_path()) ?>;
async function changeStatus(id, status) {
  if (!confirm('¿Confirmar cambio de estado?')) return;
  const fd = new FormData(); fd.append('status', status);
  const res = await fetch(`${BASE_PATH}/mi-panel/turnos/${id}/status`, {method:'POST', body:fd});
  if (res.ok) location.reload(); else alert('Error al cambiar el estado.');
}
</script>
