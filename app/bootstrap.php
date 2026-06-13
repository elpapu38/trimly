<?php
if (!defined('BASE_PATH')) define('BASE_PATH', dirname(__DIR__));
if (!defined('APP_PATH'))  define('APP_PATH',  BASE_PATH . '/app');
if (!defined('PUB_PATH'))  define('PUB_PATH',  BASE_PATH);

// Load .env
$envFile = BASE_PATH . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if (!$line || $line[0] === '#' || !str_contains($line, '=')) continue;
        [$key, $val] = array_map('trim', explode('=', $line, 2));
        $val = trim($val, '"\'');
        if (!getenv($key)) { putenv("{$key}={$val}"); $_ENV[$key] = $val; }
    }
}

$config = require BASE_PATH . '/config/app.php';
date_default_timezone_set($config['timezone'] ?? 'America/Argentina/Buenos_Aires');

// ── Error handling: NEVER show errors to users ────────────────
$debug = filter_var($config['debug'] ?? false, FILTER_VALIDATE_BOOLEAN);
ini_set('display_errors',         '0');   // never display
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);
ini_set('log_errors',  '1');
ini_set('error_log',   BASE_PATH . '/storage/logs/error.log');

// Custom error handler — log everything, show friendly page
set_error_handler(function(int $errno, string $errstr, string $errfile, int $errline): bool {
    if ($errno === E_DEPRECATED || $errno === E_USER_DEPRECATED) {
        // Log deprecated but don't crash
        error_log("[DEPRECATED] {$errstr} in {$errfile}:{$errline}");
        return true;
    }
    if ($errno === E_NOTICE || $errno === E_USER_NOTICE) {
        error_log("[NOTICE] {$errstr} in {$errfile}:{$errline}");
        return true;
    }
    error_log("[ERROR {$errno}] {$errstr} in {$errfile}:{$errline}");
    return false; // let PHP handle fatal
});

set_exception_handler(function(\Throwable $e): void {
    error_log('[EXCEPTION] ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    if (headers_sent()) { echo '<p style="display:none">Internal error</p>'; exit; }
    http_response_code(500);
    if (defined('APP_PATH') && file_exists(APP_PATH . '/Views/errors/500.php')) {
        $pageTitle = 'Error interno';
        $content   = APP_PATH . '/Views/errors/500.php';
        if (file_exists(APP_PATH . '/Views/partials/layout.php')) {
            require APP_PATH . '/Views/partials/layout.php';
        } else {
            require APP_PATH . '/Views/errors/500.php';
        }
    } else {
        echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Error</title></head><body style="font-family:sans-serif;background:#0d0d0d;color:#eee;text-align:center;padding:80px"><h1>Ocurrió un error</h1><p>Estamos trabajando para solucionarlo.</p><a href="/" style="color:#C9A84C">← Volver al inicio</a></body></html>';
    }
    exit;
});

// Helpers
require_once APP_PATH . '/Helpers/Helpers.php';

// PSR-4 Autoloader
spl_autoload_register(function (string $class): void {
    $map = [
        'App\\Controllers\\' => APP_PATH . '/Controllers/',
        'App\\Middleware\\'  => APP_PATH . '/Middleware/',
        'App\\Helpers\\'     => APP_PATH . '/Helpers/',
    ];
    foreach ($map as $prefix => $dir) {
        if (str_starts_with($class, $prefix)) {
            $file = $dir . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
            if (file_exists($file)) require_once $file;
            return;
        }
    }
});

// Load all models upfront (all in one file)
require_once APP_PATH . '/Models/Database.php';
require_once APP_PATH . '/Models/BaseModel.php';
require_once APP_PATH . '/Models/Models.php';

// Session
if (session_status() === PHP_SESSION_NONE) {
    $sess = $config['session'] ?? [];
    session_name($sess['name'] ?? 'trimly_session');
    ini_set('session.gc_maxlifetime', (string)($sess['lifetime'] ?? 7200));
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_strict_mode', '1');
    session_start();
}
