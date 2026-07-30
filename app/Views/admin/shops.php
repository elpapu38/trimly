<?php $pageTitle = $pageTitle ?? 'Locales'; ?>
<div class="flex min-h-screen pt-16 pb-16 lg:pb-0">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-1 p-5 lg:p-8 overflow-x-hidden min-w-0">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
    <h1 class="font-display text-xl font-bold text-cream-light">Locales</h1>
    <form method="GET" class="flex flex-wrap gap-2">
      <input name="q" value="<?= e($filter['search']) ?>" placeholder="Buscar..." class="bg-ink-700 border border-ink-600 rounded-xl px-3 py-2 text-sm text-cream-light focus:border-gold focus:outline-none w-36">
      <select name="status" class="bg-ink-700 border border-ink-600 rounded-xl px-3 py-2 text-sm text-cream-light focus:border-gold focus:outline-none">
        <option value="">Todos los estados</option>
        <?php foreach(['active'=>'Activos','pending'=>'Pendientes','suspended'=>'Suspendidos','closed'=>'Cerrados']as$v=>$l): ?>
        <option value="<?=$v?>" <?=$filter['status']===$v?'selected':''?>><?=$l?></option>
        <?php endforeach; ?>
      </select>
      <select name="type" class="bg-ink-700 border border-ink-600 rounded-xl px-3 py-2 text-sm text-cream-light focus:border-gold focus:outline-none">
        <option value="">Todos los tipos</option>
        <?php foreach(['barbershop'=>'Barbería','salon'=>'Salón','mixed'=>'Mixto','nails'=>'Uñas','spa'=>'Spa','tattoo'=>'Tatuajes','makeup'=>'Maquillaje','other'=>'Otro']as$v=>$l): ?>
        <option value="<?=$v?>" <?=$filter['type']===$v?'selected':''?>><?=$l?></option>
        <?php endforeach; ?>
      </select>
      <button class="bg-gold text-ink-900 font-semibold px-4 py-2 rounded-xl text-sm hover:bg-gold-500 transition-colors">Filtrar</button>
    </form>
  </div>

  <div class="bg-ink-800 border border-ink-700 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-sm min-w-[600px]">
        <thead class="bg-ink-700/50">
          <tr class="text-left text-ink-400 text-xs uppercase tracking-wider">
            <th class="px-4 py-3">Local</th>
            <th class="px-4 py-3 hidden md:table-cell">Tipo</th>
            <th class="px-4 py-3 hidden lg:table-cell">Ciudad</th>
            <th class="px-4 py-3">Estado</th>
            <th class="px-4 py-3 hidden sm:table-cell">Rating</th>
            <th class="px-4 py-3">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-ink-700/40">
          <?php foreach($shops as $sh):
            $sc=['active'=>'text-emerald-400 bg-emerald-500/10','pending'=>'text-amber-400 bg-amber-500/10','suspended'=>'text-red-400 bg-red-500/10','closed'=>'text-ink-400 bg-ink-700'][$sh['status']]??'text-ink-400 bg-ink-700';
          ?>
          <tr class="hover:bg-ink-700/20 transition-colors <?=$sh['is_shadowbanned']?'opacity-60':''?>">
            <td class="px-4 py-3">
              <div class="flex items-center gap-1.5">
                <?php if($sh['featured']): ?><span class="text-gold text-xs" title="Destacado">⭐</span><?php endif; ?>
                <?php if($sh['verified']): ?><span class="text-blue-400 text-xs" title="Verificado">✓</span><?php endif; ?>
                <?php if($sh['is_shadowbanned']): ?><span class="text-purple-400 text-xs" title="Shadowban">👻</span><?php endif; ?>
                <div class="min-w-0">
                  <a href="<?= path('/admin/locales/'.$sh['id']) ?>" class="font-medium text-cream-light hover:text-gold block truncate max-w-[140px]"><?= e($sh['name']) ?></a>
                  <p class="text-xs text-ink-500 truncate max-w-[140px]"><?= e($sh['email']??'') ?></p>
                </div>
              </div>
            </td>
            <td class="px-4 py-3 hidden md:table-cell text-ink-300 text-xs"><?= \App\Models\Shop::typeLabel($sh['type']) ?></td>
            <td class="px-4 py-3 hidden lg:table-cell text-ink-300 text-xs"><?= e($sh['city']??'—') ?></td>
            <td class="px-4 py-3"><span class="text-[11px] px-2 py-0.5 rounded-full <?=$sc?>"><?= ucfirst($sh['status']) ?></span></td>
            <td class="px-4 py-3 hidden sm:table-cell text-xs"><span class="text-amber-400">★ <?= number_format($sh['rating_avg'],1) ?></span> <span class="text-ink-500">(<?= $sh['rating_count'] ?>)</span></td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-1">
                <a href="<?= path('/admin/locales/'.$sh['id']) ?>" class="text-[11px] bg-ink-700 hover:bg-ink-600 text-ink-200 px-2 py-1.5 rounded-lg">Ver</a>
                <?php if($sh['status']==='pending'): ?><button onclick="act(<?=$sh['id']?>,'approve')" class="text-[11px] bg-emerald-600 hover:bg-emerald-500 text-white px-2 py-1.5 rounded-lg">✓</button>
                <?php elseif($sh['status']==='active'): ?><button onclick="openSusp(<?=$sh['id']?>)" class="text-[11px] bg-amber-600/80 hover:bg-amber-600 text-white px-2 py-1.5 rounded-lg">Susp.</button>
                <?php elseif($sh['status']==='suspended'): ?><button onclick="act(<?=$sh['id']?>,'unsuspend')" class="text-[11px] bg-emerald-600 hover:bg-emerald-500 text-white px-2 py-1.5 rounded-lg">Activar</button>
                <?php endif; ?>
                <?php if(!$sh['is_shadowbanned']): ?><button onclick="act(<?=$sh['id']?>,'shadowban')" class="text-[11px] bg-purple-700/70 hover:bg-purple-700 text-white px-2 py-1.5 rounded-lg" title="Shadowban">👻</button>
                <?php else: ?><button onclick="act(<?=$sh['id']?>,'unshadowban')" class="text-[11px] bg-ink-600 text-ink-300 px-2 py-1.5 rounded-lg" title="Quitar shadowban">✕</button><?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if(empty($shops)): ?><tr><td colspan="6" class="px-4 py-10 text-center text-ink-500">Sin resultados.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
    <?php if($pagination['last_page']>1): ?>
    <div class="flex items-center justify-between px-4 py-3 border-t border-ink-700">
      <p class="text-xs text-ink-500"><?= count($shops) ?> de <?= $pagination['total'] ?></p>
      <div class="flex gap-1">
        <?php for($p=1;$p<=$pagination['last_page'];$p++): $a=$p===$pagination['current_page']; ?>
        <a href="?<?= http_build_query(array_merge($filter,['page'=>$p])) ?>" class="px-3 py-1.5 text-xs rounded-lg <?=$a?'bg-gold text-ink-900 font-bold':'bg-ink-700 text-ink-300 hover:bg-ink-600'?>"><?=$p?></a>
        <?php endfor; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</main>
</div>

<div id="susp-modal" class="fixed inset-0 bg-black/70 z-50 hidden items-center justify-center p-4">
  <div class="bg-ink-800 border border-ink-700 rounded-2xl p-6 w-full max-w-sm">
    <h3 class="font-semibold text-cream-light mb-4">Suspender local</h3>
    <textarea id="s-r" rows="3" placeholder="Motivo..." class="w-full bg-ink-700 border border-ink-600 rounded-xl px-3 py-2 text-sm text-cream-light focus:border-gold focus:outline-none resize-none mb-3"></textarea>
    <label class="text-xs text-ink-400 mb-1 block">Días (0 = indefinido)</label>
    <input type="number" id="s-d" min="0" max="365" value="7" class="w-full bg-ink-700 border border-ink-600 rounded-xl px-3 py-2 text-sm text-cream-light focus:border-gold focus:outline-none mb-4">

    <!-- Visibilidad pública: binario y claro -->
    <label class="text-xs text-ink-400 mb-1.5 block">¿El local debe aparecer suspendido para los clientes?</label>
    <div class="grid grid-cols-2 gap-2 mb-4">
      <button type="button" onclick="setSuspVis(true,this)"
              class="susp-vis-btn py-2.5 rounded-xl text-sm font-semibold border-2 border-gold bg-gold/15 text-gold transition-all"
              data-val="1">
        Sí, mostrar suspendido
      </button>
      <button type="button" onclick="setSuspVis(false,this)"
              class="susp-vis-btn py-2.5 rounded-xl text-sm font-semibold border-2 border-ink-600 text-ink-300 hover:border-ink-500 transition-all"
              data-val="0">
        No, ocultarlo
      </button>
    </div>
    <p class="text-[11px] text-ink-500 mb-4">
      Si elegís "ocultarlo", el local sigue suspendido internamente pero los clientes lo ven normal,
      sin reservas habilitadas y sin saber que está suspendido.
    </p>

    <div class="flex gap-3">
      <button onclick="doSusp()" class="flex-1 bg-amber-600 hover:bg-amber-500 text-white font-semibold py-2.5 rounded-xl text-sm">Suspender</button>
      <button onclick="document.getElementById('susp-modal').classList.replace('flex','hidden')" class="flex-1 bg-ink-700 text-ink-200 py-2.5 rounded-xl text-sm">Cancelar</button>
    </div>
  </div>
</div>
<script>
let _sid=null;
let _suspVis=1; // por defecto: mostrar suspendido

function setSuspVis(val, btn){
  _suspVis = val ? 1 : 0;
  document.querySelectorAll('.susp-vis-btn').forEach(b=>{
    b.classList.remove('border-gold','bg-gold/15','text-gold','border-ink-600','text-ink-300');
    b.classList.add('border-ink-600','text-ink-300');
  });
  btn.classList.remove('border-ink-600','text-ink-300');
  btn.classList.add('border-gold','bg-gold/15','text-gold');
}

function openSusp(id){
  _sid=id; _suspVis=1;
  document.getElementById('susp-modal').classList.replace('hidden','flex');
}

async function doSusp(){
  document.getElementById('susp-modal').classList.replace('flex','hidden');
  await act(_sid,'suspend',document.getElementById('s-r').value,document.getElementById('s-d').value,_suspVis);
}

async function act(id,action,reason='',days=0,suspension_public=1){
  const d=await trimlyAction(`<?= path('/admin/locales/') ?>${id}/moderar`,{action,reason,days,suspension_public},()=>setTimeout(()=>location.reload(),1000));
}
</script>
