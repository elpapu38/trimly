<?php
require_once __DIR__ . '/app/bootstrap.php';
$routes = require BASE_PATH . '/routes/web.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST' && isset($_POST['_method'])) {
    $o = strtoupper($_POST['_method']);
    if (in_array($o, ['PUT','PATCH','DELETE'])) $method = $o;
}

$uri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = base_path();
if ($base && str_starts_with($uri, $base)) $uri = substr($uri, strlen($base));
$uri = rtrim($uri, '/') ?: '/';

$matched = null; $params = [];
foreach ($routes as [$rMethod, $pattern, $handler, $middleware]) {
    if (strtoupper($rMethod) !== $method) continue;
    $regex = str_contains($pattern, '{any}')
        ? str_replace('{any}', '(.*)', $pattern)
        : preg_replace('/\{[^}]+\}/', '([^/]+)', $pattern);
    $regex = '#^' . $regex . '$#u';
    if (preg_match($regex, $uri, $matches)) {
        preg_match_all('/\{([^}]+)\}/', $pattern, $paramNames);
        array_shift($matches);
        $params  = !empty($paramNames[1]) ? array_combine($paramNames[1], $matches) : [];
        $matched = [$handler, $middleware, $params];
        break;
    }
}

if (!$matched) {
    http_response_code(404);
    $content = APP_PATH . '/Views/errors/404.php';
    $pageTitle = 'Página no encontrada';
    require APP_PATH . '/Views/partials/layout.php';
    exit;
}

[$handler, $middleware, $params] = $matched;
$user = $_SESSION['user'] ?? null;

// Auto-unsuspend expired shop suspensions (every 5 min)
if (!isset($_SESSION['_susp_ck']) || time() - $_SESSION['_susp_ck'] > 300) {
    try {
        \App\Models\Database::getInstance()->query(
            "UPDATE shops SET status='active', suspension_reason=NULL, suspension_until=NULL
             WHERE status='suspended' AND suspension_until IS NOT NULL AND suspension_until < NOW()"
        );
    } catch (\Exception $e) {}
    $_SESSION['_susp_ck'] = time();
}

foreach ($middleware as $mw) {
    switch ($mw) {
        case 'auth':
            if (!$user) { $_SESSION['intended'] = $uri; header('Location: ' . base_path() . '/login'); exit; }
            if (in_array($user['status'] ?? 'active', ['suspended','banned'])) {
                session_destroy(); header('Location: ' . base_path() . '/login?bloqueado=1'); exit;
            }
            break;
        case 'guest':
            if ($user) {
                header('Location: ' . base_path() . match($user['role'] ?? 'client') {
                    'superadmin' => '/admin', 'shop_owner' => '/panel', 'employee' => '/mi-panel', default => '/mis-turnos',
                }); exit;
            }
            break;
        case 'shop_owner':
            if (!$user || !in_array($user['role'], ['shop_owner','superadmin'])) {
                http_response_code(403); $content = APP_PATH . '/Views/errors/403.php'; $pageTitle = 'Acceso denegado';
                require APP_PATH . '/Views/partials/layout.php'; exit;
            }
            break;
        case 'superadmin':
            if (!$user || $user['role'] !== 'superadmin') {
                http_response_code(403); $content = APP_PATH . '/Views/errors/403.php'; $pageTitle = 'Acceso denegado';
                require APP_PATH . '/Views/partials/layout.php'; exit;
            }
            break;
        case 'client_only':
            if ($user && $user['role'] !== 'client') {
                http_response_code(403); $content = APP_PATH . '/Views/errors/403.php';
                $pageTitle = 'Solo clientes pueden reservar turnos';
                require APP_PATH . '/Views/partials/layout.php'; exit;
            }
            break;
        case 'client_only_role':
            if (!$user || $user['role'] !== 'client') {
                $dest = match($user['role'] ?? '') { 'superadmin'=>'/admin','shop_owner'=>'/panel','employee'=>'/mi-panel',default=>'/' };
                header('Location: ' . base_path() . $dest); exit;
            }
            break;
        case 'employee_role':
            if (!$user || $user['role'] !== 'employee') {
                http_response_code(403); $content = APP_PATH . '/Views/errors/403.php'; $pageTitle = 'Acceso denegado';
                require APP_PATH . '/Views/partials/layout.php'; exit;
            }
            break;
    }
}

[$controllerName, $actionName] = explode('@', $handler);
$controllerClass = "App\\Controllers\\{$controllerName}";
if (!class_exists($controllerClass)) {
    http_response_code(500); error_log("Controller not found: {$controllerClass}");
    $content = APP_PATH . '/Views/errors/500.php'; $pageTitle = 'Error interno';
    require APP_PATH . '/Views/partials/layout.php'; exit;
}
$controller = new $controllerClass();
if (!method_exists($controller, $actionName)) {
    http_response_code(500); error_log("Action not found: {$controllerClass}@{$actionName}");
    $content = APP_PATH . '/Views/errors/500.php'; $pageTitle = 'Error interno';
    require APP_PATH . '/Views/partials/layout.php'; exit;
}
$controller->$actionName(...array_values($params));
