<?php $pageTitle = $pageTitle ?? 'Detalle'; ?>
<div class="flex min-h-screen pt-16 pb-16 lg:pb-0">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-1 p-5 lg:p-8 overflow-x-hidden min-w-0">
  <div class="mb-5">
    <a href="<?= path('/admin/locales') ?>" class="text-sm text-ink-400 hover:text-gold">← Locales</a>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <div class="lg:col-span-2 space-y-5">
      <!-- Info -->
      <div class="bg-ink-800 border border-ink-700 rounded-2xl p-6">
        <div class="flex flex-wrap items-start justify-between gap-3 mb-4">
          <div>
            <div class="flex flex-wrap items-center gap-2 mb-1">
              <h1 class="font-display text-xl font-bold text-cream-light"><?= e($shop['name']) ?></h1>
              <?php if($shop['verified']): ?><span class="text-[11px] bg-blue-500/10 text-blue-400 border border-blue-500/20 px-2 py-0.5 rounded-full">✓ Verificado</span><?php endif; ?>
              <?php if($shop['featured']): ?><span class="text-[11px] bg-gold/10 text-gold border border-gold/20 px-2 py-0.5 rounded-full">⭐ Destacado</span><?php endif; ?>
              <?php if($shop['is_shadowbanned']): ?><span class="text-[11px] bg-purple-500/10 text-purple-400 border border-purple-500/20 px-2 py-0.5 rounded-full">👻 Shadowban</span><?php endif; ?>
            </div>
            <p class="text-sm text-ink-400"><?= \App\Models\Shop::typeLabel($shop['type']) ?> · <?= \App\Models\Shop::audienceLabel($shop['target_audience']??'unisex') ?> · <?= e($shop['city']??'') ?></p>
          </div>
          <span class="text-xs px-3 py-1.5 rounded-full <?= $shop['status']==='active'?'bg-emerald-500/10 text-emerald-400':($shop['status']==='pending'?'bg-amber-500/10 text-amber-400':'bg-red-500/10 text-red-400') ?>"><?= ucfirst($shop['status']) ?></span>
        </div>
        <!-- Stats -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
          <?php foreach([['Turnos totales',number_format((int)($stats['total'] ?? 0)),'text-cream-light'],['Completados',number_format((int)($stats['completed'] ?? 0)),'text-emerald-400'],['Ingresos',money((float)($stats['revenue'] ?? 0)),'text-gold'],['Rating','★ '.number_format($shop['rating_avg'],1).' ('.$shop['rating_count'].')','text-amber-400']]as[$l,$v,$c]): ?>
          <div class="bg-ink-700/50 rounded-xl p-3 text-center"><div class="text-xs text-ink-500 mb-1"><?=$l?></div><div class="font-bold <?=$c?> text-sm"><?=$v?></div></div>
          <?php endforeach; ?>
        </div>
        <div class="text-sm text-ink-300 space-y-1.5">
          <p><span class="text-ink-500">Dueño:</span> <?= e($owner['name']??'—') ?> — <?= e($owner['email']??'') ?></p>
          <p><span class="text-ink-500">Email:</span> <?= e($shop['email']??'—') ?></p>
          <p><span class="text-ink-500">Teléfono:</span> <?= e($shop['phone']??'—') ?></p>
          <p><span class="text-ink-500">Plan:</span> <?= ucfirst($shop['plan']) ?> · <span class="text-ink-500">Vistas:</span> <?= number_format($shop['views_count']) ?></p>
          <?php if($shop['suspension_reason']): ?><p class="text-amber-400"><span class="text-ink-500">Razón suspensión:</span> <?= e($shop['suspension_reason']) ?></p><?php endif; ?>
          <?php if($shop['ban_reason']): ?><p class="text-red-400"><span class="text-ink-500">Razón ban:</span> <?= e($shop['ban_reason']) ?></p><?php endif; ?>
        </div>
      </div>
      <!-- Reseñas recientes -->
      <?php if(!empty($reviews)): ?>
      <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5">
        <h2 class="font-semibold text-cream-light mb-3 text-sm">Reseñas recientes</h2>
        <div class="space-y-2">
          <?php foreach($reviews as $r): ?>
          <div class="p-3 bg-ink-700/50 rounded-xl">
            <div class="flex items-center justify-between mb-1">
              <span class="text-xs font-medium text-cream-light"><?= e($r['client_name']) ?></span>
              <span class="text-amber-400 text-xs"><?= str_repeat('★',(int)$r['rating']) ?><?= str_repeat('☆',5-(int)$r['rating']) ?></span>
            </div>
            <p class="text-xs text-ink-300"><?= e(truncate($r['comment']??'',120)) ?></p>
            <?php if($r['report_count']>0): ?><span class="text-[10px] text-red-400 mt-1 block">⚠ <?=$r['report_count']?> reporte(s)</span><?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <!-- Acciones -->
    <div class="space-y-4">
      <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5">
        <h2 class="font-semibold text-cream-light mb-4 text-sm">Acciones de moderación</h2>
        <div class="space-y-2">
          <?php if($shop['status']==='pending'): ?>
          <button onclick="doAction('approve','Aprobar local',false)" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-semibold py-2.5 rounded-xl text-sm transition-colors">✓ Aprobar local</button>
          <?php endif; ?>
          <?php if($shop['status']==='active'): ?>
          <button onclick="doAction('suspend','Suspender',true)" class="w-full bg-amber-600/80 hover:bg-amber-600 text-white font-semibold py-2.5 rounded-xl text-sm transition-colors">⏸ Suspender</button>
          <button onclick="doAction('ban','Banear',false)" class="w-full bg-red-700/80 hover:bg-red-700 text-white font-semibold py-2.5 rounded-xl text-sm transition-colors">🚫 Banear</button>
          <?php elseif($shop['status']==='suspended'): ?>
          <button onclick="quickAct('unsuspend')" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-semibold py-2.5 rounded-xl text-sm transition-colors">✓ Levantar suspensión</button>
          <?php endif; ?>
          <?php if(!$shop['is_shadowbanned']): ?>
          <button onclick="quickAct('shadowban')" class="w-full bg-purple-700/80 hover:bg-purple-700 text-white font-semibold py-2.5 rounded-xl text-sm transition-colors">👻 Shadowban</button>
          <?php else: ?>
          <button onclick="quickAct('unshadowban')" class="w-full bg-ink-600 hover:bg-ink-500 text-ink-200 font-semibold py-2.5 rounded-xl text-sm transition-colors">✕ Quitar shadowban</button>
          <?php endif; ?>
          <?php if(!$shop['featured']): ?>
          <button onclick="quickAct('feature')" class="w-full bg-gold/15 hover:bg-gold/25 text-gold font-semibold py-2.5 rounded-xl text-sm border border-gold/20 transition-colors">⭐ Destacar</button>
          <?php else: ?>
          <button onclick="quickAct('unfeature')" class="w-full bg-ink-700 hover:bg-ink-600 text-ink-300 font-semibold py-2.5 rounded-xl text-sm transition-colors">✕ Quitar destacado</button>
          <?php endif; ?>
          <?php if(!$shop['verified']): ?>
          <button onclick="quickAct('verify')" class="w-full bg-blue-600/80 hover:bg-blue-600 text-white font-semibold py-2.5 rounded-xl text-sm transition-colors">✓ Verificar</button>
          <?php endif; ?>
          <button onclick="doAction('note','Agregar nota',false)" class="w-full bg-ink-700 hover:bg-ink-600 text-ink-200 font-semibold py-2.5 rounded-xl text-sm border border-ink-600 transition-colors">📝 Nota interna</button>
          <a href="<?= path('/local/'.e($shop['slug'])) ?>" target="_blank" class="block w-full text-center bg-ink-700 hover:bg-ink-600 text-ink-200 font-semibold py-2.5 rounded-xl text-sm border border-ink-600 transition-colors">↗ Ver perfil público</a>
        </div>
      </div>
      <!-- Log -->
      <div class="bg-ink-800 border border-ink-700 rounded-2xl p-5">
        <h2 class="font-semibold text-cream-light mb-3 text-sm">Historial de moderación</h2>
        <div class="space-y-2 max-h-72 overflow-y-auto pr-1">
          <?php if(empty($modLog)): ?><p class="text-xs text-ink-500">Sin historial.</p>
          <?php else: $ac=['approved'=>'text-emerald-400','suspended'=>'text-amber-400','banned'=>'text-red-400','shadowban'=>'text-purple-400','featured'=>'text-gold','verified'=>'text-blue-400','note'=>'text-ink-400','unsuspended'=>'text-emerald-300','unbanned'=>'text-emerald-300','unshadowban'=>'text-purple-300'];
          foreach($modLog as $log): $c=$ac[$log['action']]??'text-ink-300'; ?>
          <div class="text-xs border-b border-ink-700/40 pb-1.5 last:border-0">
            <div class="flex items-center justify-between">
              <span class="<?=$c?> font-bold uppercase text-[10px]"><?= e($log['action']) ?></span>
              <span class="text-ink-500"><?= fecha($log['created_at'],'d/m H:i') ?></span>
            </div>
            <?php if($log['reason']): ?><p class="text-ink-300 mt-0.5"><?= e($log['reason']) ?></p><?php endif; ?>
            <p class="text-ink-500">por <?= e($log['admin_name']) ?></p>
          </div>
          <?php endforeach; endif; ?>
        </div>
        </div>
      </div>
    </div>
  </div>
</main>
</div>

<div id="act-modal" class="fixed inset-0 bg-black/70 z-50 hidden items-center justify-center p-4">
  <div class="bg-ink-800 border border-ink-700 rounded-2xl p-6 w-full max-w-sm">
    <h3 class="font-semibold text-cream-light mb-3" id="act-title">Acción</h3>
    <textarea id="act-r" rows="3" placeholder="Motivo (opcional)..." class="w-full bg-ink-700 border border-ink-600 rounded-xl px-3 py-2 text-sm text-cream-light focus:border-gold focus:outline-none resize-none mb-3"></textarea>
    <div id="act-days-wrap" class="hidden mb-3">
      <label class="text-xs text-ink-400 mb-1 block">Días (0 = indefinido)</label>
      <input type="number" id="act-d" min="0" max="365" value="7" class="w-full bg-ink-700 border border-ink-600 rounded-xl px-3 py-2 text-sm text-cream-light focus:border-gold focus:outline-none">
    </div>
    <div id="act-public-wrap" class="hidden mb-3">
      <label class="text-xs text-ink-400 mb-1.5 block">¿El local debe aparecer suspendido para los clientes?</label>
      <div class="grid grid-cols-2 gap-2">
        <button type="button" onclick="setActPublic(true,this)"
                class="act-vis-btn py-2.5 rounded-xl text-sm font-semibold border-2 border-gold bg-gold/15 text-gold transition-all"
                data-val="1">
          Sí, mostrar suspendido
        </button>
        <button type="button" onclick="setActPublic(false,this)"
                class="act-vis-btn py-2.5 rounded-xl text-sm font-semibold border-2 border-ink-600 text-ink-300 hover:border-ink-500 transition-all"
                data-val="0">
          No, ocultarlo
        </button>
      </div>
      <p class="text-[10px] text-ink-500 mt-1.5">
        "Ocultarlo" mantiene la suspensión interna pero el local se ve normal para los clientes (sin reservas).
      </p>
    </div>
    <div class="flex gap-3">
      <button id="act-confirm" class="flex-1 bg-gold text-ink-900 font-semibold py-2.5 rounded-xl text-sm">Confirmar</button>
      <button onclick="document.getElementById('act-modal').classList.replace('flex','hidden')" class="flex-1 bg-ink-700 text-ink-200 py-2.5 rounded-xl text-sm">Cancelar</button>
    </div>
  </div>
</div>
<script>
let _act=null;
let _actPublic=1; // por defecto: mostrar suspendido
const shopId=<?= $shop['id'] ?>;

function setActPublic(val, btn){
  _actPublic = val ? 1 : 0;
  document.querySelectorAll('.act-vis-btn').forEach(b=>{
    b.classList.remove('border-gold','bg-gold/15','text-gold');
    b.classList.add('border-ink-600','text-ink-300');
  });
  btn.classList.remove('border-ink-600','text-ink-300');
  btn.classList.add('border-gold','bg-gold/15','text-gold');
}

function doAction(action,title,showDays){
  _act=action; _actPublic=1;
  document.getElementById('act-title').textContent=title;
  document.getElementById('act-r').value='';
  document.getElementById('act-d').value='7';
  document.getElementById('act-days-wrap').classList.toggle('hidden',!showDays);
  document.getElementById('act-public-wrap').classList.toggle('hidden',!showDays);
  document.getElementById('act-confirm').onclick=()=>sendAction();
  document.getElementById('act-modal').classList.replace('hidden','flex');
}
async function sendAction(){
  document.getElementById('act-modal').classList.replace('flex','hidden');
  await call(_act,document.getElementById('act-r').value,document.getElementById('act-d').value,_actPublic);
}
async function quickAct(action){await call(action,'',0);}
async function call(action,reason,days,pub=1){
  const d=await trimlyAction(`<?= path('/admin/locales/') ?>${shopId}/moderar`,{action,reason,days,suspension_public:pub},()=>setTimeout(()=>location.reload(),1200));
}
</script>
