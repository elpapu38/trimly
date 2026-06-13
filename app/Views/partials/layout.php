<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle ?? 'Trimly') ?></title>
  <meta name="description" content="Encontrá y reservá turnos en las mejores barberías y salones cerca tuyo.">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
  tailwind.config = {
    theme: { extend: {
      colors: {
        ink:  { DEFAULT:'#0D0D0D',50:'#F5F5F5',100:'#E8E8E8',200:'#C8C8C8',300:'#A0A0A0',400:'#707070',500:'#484848',600:'#2E2E2E',700:'#1A1A1A',800:'#111111',900:'#0D0D0D' },
        gold: { DEFAULT:'#C9A84C',50:'#FDF8EC',100:'#F8ECC8',300:'#E8C35A',400:'#C9A84C',500:'#A88A38',700:'#5E4D1B' },
        cream:{ DEFAULT:'#F5F0E8',light:'#FAF8F4',dark:'#E8E0D0' },
      },
      fontFamily: { display:['Playfair Display','Georgia','serif'], sans:['DM Sans','system-ui','sans-serif'] },
    }}
  }
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    .card-lift{transition:transform .25s ease,box-shadow .25s ease;}
    .card-lift:hover{transform:translateY(-4px);box-shadow:0 20px 40px -8px rgba(201,168,76,.15);}
    .input-gold:focus{outline:none;border-color:#C9A84C;box-shadow:0 0 0 3px rgba(201,168,76,.1);}
    .nav-link{position:relative;}
    .nav-link::after{content:'';position:absolute;bottom:-2px;left:0;width:0;height:1px;background:#C9A84C;transition:width .3s ease;}
    .nav-link:hover::after{width:100%;}
    ::-webkit-scrollbar{width:4px;}
    ::-webkit-scrollbar-track{background:#111;}
    ::-webkit-scrollbar-thumb{background:#C9A84C;border-radius:2px;}
    .line-clamp-2{overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;}
    .type-badge-barbershop{background:rgba(201,168,76,.1);color:#C9A84C;border:1px solid rgba(201,168,76,.2);}
    .type-badge-salon{background:rgba(212,160,200,.1);color:#d4a0c8;border:1px solid rgba(212,160,200,.2);}
    .type-badge-mixed{background:rgba(126,200,160,.1);color:#7ec8a0;border:1px solid rgba(126,200,160,.2);}
    .type-badge-nails{background:rgba(240,120,180,.1);color:#f078b4;border:1px solid rgba(240,120,180,.2);}
    .type-badge-spa{background:rgba(100,180,220,.1);color:#64b4dc;border:1px solid rgba(100,180,220,.2);}
    .type-badge-tattoo{background:rgba(160,100,240,.1);color:#a064f0;border:1px solid rgba(160,100,240,.2);}
    .type-badge-makeup{background:rgba(240,100,100,.1);color:#f06464;border:1px solid rgba(240,100,100,.2);}
    /* Toast notifications */
    #toast-container{position:fixed;top:5rem;right:1rem;z-index:9999;width:320px;pointer-events:none;}
    .toast{pointer-events:auto;transition:all .3s ease;transform:translateX(0);}
    .toast.hiding{opacity:0;transform:translateX(100px);}
  </style>
</head>
<body class="bg-ink-900 text-cream-light font-sans antialiased min-h-screen flex flex-col">
  <?php require __DIR__ . '/header.php'; ?>

  <!-- Toast container -->
  <div id="toast-container"></div>

  <?php if (!empty($_SESSION['flash'])): ?>
  <script>
  const _flashMessages = <?= json_encode($_SESSION['flash'] ?? []) ?>;
  </script>
  <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>

  <main class="flex-1"><?php require $content; ?></main>

  <?php require __DIR__ . '/footer.php'; ?>

  <script>
  // Toast system
  const toastColors = {
    success: 'bg-emerald-900/90 border-emerald-500/40 text-emerald-200',
    error:   'bg-red-900/90 border-red-500/40 text-red-200',
    info:    'bg-blue-900/90 border-blue-500/40 text-blue-200',
    warning: 'bg-amber-900/90 border-amber-500/40 text-amber-200',
  };
  const toastIcons = { success:'✓', error:'✕', info:'ℹ', warning:'⚠' };

  function showToast(type, message, duration = 4000) {
    const container = document.getElementById('toast-container');
    const toast     = document.createElement('div');
    const cls       = toastColors[type] || toastColors.info;
    toast.className = `toast mb-2 flex items-start gap-3 px-4 py-3 rounded-xl border backdrop-blur-sm text-sm ${cls}`;
    toast.innerHTML = `<span class="shrink-0 font-bold">${toastIcons[type]||'•'}</span><span class="flex-1">${message}</span><button onclick="this.closest('.toast').remove()" class="shrink-0 opacity-50 hover:opacity-100 text-xs ml-1">✕</button>`;
    container.appendChild(toast);
    setTimeout(() => {
      toast.classList.add('hiding');
      setTimeout(() => toast.remove(), 300);
    }, duration);
  }

  // Show server-side flash messages as toasts
  if (typeof _flashMessages !== 'undefined') {
    Object.entries(_flashMessages).forEach(([type, msgs]) => {
      msgs.forEach(msg => showToast(type, msg));
    });
  }

  // Global AJAX success/error helper
  window.trimlyAction = async function(url, body, onSuccess) {
    try {
      const r = await fetch(url, { method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body: new URLSearchParams(body) });
      const d = await r.json();
      if (d.success) {
        showToast('success', d.message || 'Acción completada correctamente.');
        if (onSuccess) onSuccess(d);
      } else {
        showToast('error', d.error || 'Ocurrió un error. Intentá de nuevo.');
      }
      return d;
    } catch(e) {
      showToast('error', 'Error de conexión. Verificá tu internet.');
      return null;
    }
  };

  // Intersection observer for animations
  if ('IntersectionObserver' in window) {
    const obs = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.style.opacity = '1';
          e.target.style.transform = 'translateY(0)';
          obs.unobserve(e.target);
        }
      });
    }, { threshold: 0.08 });
    document.querySelectorAll('[data-anim]').forEach(el => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(20px)';
      el.style.transition = 'opacity .5s ease, transform .5s ease';
      obs.observe(el);
    });
  }
  </script>
</body>
</html>