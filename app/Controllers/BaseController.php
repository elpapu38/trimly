<?php
// ============================================================
//  app/Controllers/BaseController.php
// ============================================================
namespace App\Controllers;

abstract class BaseController
{
    // ── Vistas ────────────────────────────────────────────────

    protected function view(string $template, array $viewData = [], int $status = 200): void
    {
        http_response_code($status);
        extract($viewData, EXTR_SKIP);
        $viewFile = APP_PATH . "/Views/{$template}.php";
        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View not found: {$template}");
        }
        $content = $viewFile;
        require APP_PATH . '/Views/partials/layout.php';
    }

    // ── JSON ──────────────────────────────────────────────────

    protected function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    protected function jsonError(string $message, int $status = 400): void
    {
        $this->json(['error' => $message], $status);
    }

    protected function jsonSuccess(string $message, array $extra = []): void
    {
        $this->json(array_merge(['success' => true, 'message' => $message], $extra));
    }

    // ── Redirección ───────────────────────────────────────────

    protected function redirect(string $url, int $status = 302): never
    {
        // Si la URL es relativa (empieza con /), prepend el base path
        if (str_starts_with($url, '/') && !str_starts_with($url, '//')) {
            $url = base_path() . $url;
        }
        header("Location: {$url}", true, $status);
        exit;
    }

    protected function back(): never
    {
        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }

    // ── Flash messages ────────────────────────────────────────

    protected function flash(string $type, string $message): void
    {
        $_SESSION['flash'][$type][] = $message;
    }

    protected function getFlash(): array
    {
        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $flash;
    }

    // ── Validación básica ─────────────────────────────────────

    protected function validate(array $data, array $rules): array
    {
        $errors = [];
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            foreach (explode('|', $rule) as $r) {
                [$ruleName, $param] = array_pad(explode(':', $r, 2), 2, null);
                switch ($ruleName) {
                    case 'required':
                        if ($value === null || $value === '') {
                            $errors[$field][] = "El campo {$field} es obligatorio.";
                        }
                        break;
                    case 'email':
                        if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$field][] = 'Email inválido.';
                        }
                        break;
                    case 'min':
                        if ($value !== null && mb_strlen((string)$value) < (int)$param) {
                            $errors[$field][] = "Mínimo {$param} caracteres.";
                        }
                        break;
                    case 'max':
                        if ($value !== null && mb_strlen((string)$value) > (int)$param) {
                            $errors[$field][] = "Máximo {$param} caracteres.";
                        }
                        break;
                    case 'numeric':
                        if ($value !== null && $value !== '' && !is_numeric($value)) {
                            $errors[$field][] = 'Debe ser un número.';
                        }
                        break;
                    case 'in':
                        if ($value !== null && $value !== '' && !in_array($value, explode(',', $param ?? ''))) {
                            $errors[$field][] = 'Valor no permitido.';
                        }
                        break;
                }
            }
        }
        return $errors;
    }

    // ── Auth helpers ──────────────────────────────────────────

    protected function auth(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    protected function requireAuth(string ...$roles): void
    {
        $user = $this->auth();
        if (!$user) {
            $this->flash('error', 'Debés iniciar sesión.');
            $this->redirect('/login');
        }
        if ($roles && !in_array($user['role'], $roles)) {
            http_response_code(403);
            $pageTitle = 'Acceso denegado';
            require APP_PATH . '/Views/errors/403.php';
            exit;
        }
    }

    // ── Request helpers ───────────────────────────────────────

    protected function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function isPost(): bool { return $_SERVER['REQUEST_METHOD'] === 'POST'; }
    protected function isGet():  bool { return $_SERVER['REQUEST_METHOD'] === 'GET';  }
    protected function isAjax(): bool
    {
        return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
    }
}
