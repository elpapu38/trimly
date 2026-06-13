<?php
// ============================================================
//  app/Helpers/Helpers.php
// ============================================================

function base_path(): string {
    static $base = null;
    if ($base === null) {
        $appUrl = rtrim(getenv('APP_URL') ?: 'http://localhost', '/');
        $parsed = parse_url($appUrl);
        $base   = rtrim($parsed['path'] ?? '', '/');
    }
    return $base;
}

function url(string $path = ''): string {
    return rtrim(getenv('APP_URL') ?: 'http://localhost', '/') . '/' . ltrim($path, '/');
}

function path(string $route = ''): string {
    return base_path() . '/' . ltrim($route, '/');
}

function asset(string $file): string {
    return path('assets/' . ltrim($file, '/'));
}

function upload_url(string $file): string {
    return path('uploads/' . ltrim($file, '/'));
}

function e(mixed $val): string {
    return htmlspecialchars((string)($val ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// money() — null-safe, no crash
function money(float|int|null $amount, string $currency = 'ARS'): string {
    if ($amount === null) return '—';
    $amount = (float)$amount;
    return match($currency) {
        'ARS'   => '$ '   . number_format($amount, 2, ',', '.'),
        'USD'   => 'U$S ' . number_format($amount, 2, '.', ','),
        default => number_format($amount, 2),
    };
}

function fecha(string|null $date, string $format = 'd/m/Y'): string {
    if (!$date) return '—';
    return date($format, strtotime($date));
}

function hora(string|null $time): string {
    if (!$time) return '—';
    return date('H:i', strtotime($time));
}

function diasSemana(): array {
    return ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
}

function diasSemanaCorto(): array {
    return ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'];
}

function duracionTexto(int $minutos): string {
    if ($minutos < 60) return "{$minutos} min";
    $h = intdiv($minutos, 60); $m = $minutos % 60;
    return $m ? "{$h}h {$m}min" : "{$h}h";
}

function slugify(string $text): string {
    $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
    return trim(strtolower(preg_replace('/[^A-Za-z0-9]+/', '-', $text)), '-');
}

function truncate(string $text, int $length = 120): string {
    return mb_strlen($text) > $length ? mb_substr($text, 0, $length) . '…' : $text;
}

function initials(string $name): string {
    $parts = array_filter(explode(' ', $name));
    return strtoupper(substr($parts[0] ?? '', 0, 1) . substr($parts[1] ?? '', 0, 1));
}

function isValidEmail(string $email): bool {
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}

function sanitize(string $input): string {
    return trim(strip_tags($input));
}

function csrf(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrf(string $token): bool {
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

function uploadImage(array $file, string $folder, int $maxBytes = 5242880): string|false {
    if (!isset($file['tmp_name']) || empty($file['name'])) return false;
    $allowed = ['image/jpeg','image/png','image/webp','image/gif'];
    // Re-check mime from actual file
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mime, $allowed)) return false;
    if ($file['size'] > $maxBytes) return false;
    $ext  = match($mime) { 'image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif',default=>'jpg' };
    $name = uniqid('img_', true) . '.' . $ext;
    $dest = PUB_PATH . "/uploads/{$folder}";
    if (!is_dir($dest)) mkdir($dest, 0755, true);
    return move_uploaded_file($file['tmp_name'], "{$dest}/{$name}") ? "{$folder}/{$name}" : false;
}

function stars(float $rating, bool $html = true): string {
    $full = (int)floor($rating); $empty = 5 - $full;
    if (!$html) return str_repeat('★', $full) . str_repeat('☆', $empty);
    return str_repeat('<span class="text-amber-400">★</span>', $full)
         . str_repeat('<span class="text-ink-600">★</span>', $empty);
}

function flash_messages(): string {
    $flash = $_SESSION['flash'] ?? []; unset($_SESSION['flash']);
    $html = ''; $colors = ['success'=>'emerald','error'=>'red','info'=>'blue','warning'=>'amber'];
    foreach ($flash as $type => $messages) {
        $c = $colors[$type] ?? 'gray';
        foreach ($messages as $msg) {
            $html .= "<div class=\"mb-2 px-4 py-3 rounded-xl bg-{$c}-900/50 border border-{$c}-500/30 text-{$c}-300 text-sm pointer-events-auto\">".e($msg)."</div>";
        }
    }
    return $html;
}
