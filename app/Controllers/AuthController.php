<?php
// ============================================================
//  app/Controllers/AuthController.php
// ============================================================
namespace App\Controllers;

use App\Models\User;
use App\Helpers\Mailer;

class AuthController extends BaseController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // ══════════════════════════════════════════════════════════
    //  LOGIN
    // ══════════════════════════════════════════════════════════

    public function loginForm(): void
    {
        $this->view('auth/login', [
            'pageTitle' => 'Iniciar sesión',
            'csrf'      => csrf(),
        ]);
    }

    public function login(): void
    {
        if (!verifyCsrf($this->input('_csrf', ''))) {
            $this->flash('error', 'Token de seguridad inválido. Intentá de nuevo.');
            $this->redirect('/login');
            return;
        }

        $email    = trim($this->input('email', ''));
        $password = $this->input('password', '');

        // Validación básica
        $errors = $this->validate(
            ['email' => $email, 'password' => $password],
            ['email' => 'required|email', 'password' => 'required']
        );

        if ($errors) {
            $this->flash('error', 'Completá todos los campos correctamente.');
            $this->redirect('/login');
            return;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !$this->userModel->verifyPassword($password, $user['password_hash'])) {
            $this->flash('error', 'Email o contraseña incorrectos.');
            $this->redirect('/login');
            return;
        }

        if ($user['status'] !== 'active') {
            // Auto-lift suspension if time expired
            if ($user['status'] === 'suspended' && !empty($user['suspended_until'])) {
                if (strtotime($user['suspended_until']) <= time()) {
                    $this->userModel->update($user['id'], [
                        'status'          => 'active',
                        'suspended_until' => null,
                        'ban_reason'      => null,
                    ]);
                    // Allow login — continue below
                    goto login_ok;
                }
                $until  = date('d/m/Y \a\l\a\s H:i', strtotime($user['suspended_until']));
                $reason = $user['ban_reason'] ? ' Motivo: ' . $user['ban_reason'] . '.' : '';
                $this->flash('error', "Tu cuenta está suspendida hasta el {$until}.{$reason}");
            } elseif ($user['status'] === 'banned') {
                $reason = $user['ban_reason'] ? ' Motivo: ' . $user['ban_reason'] . '.' : '';
                $this->flash('error', "Tu cuenta ha sido baneada de forma permanente.{$reason} Contactá al soporte si creés que es un error.");
            } else {
                $this->flash('error', 'Tu cuenta no está activa. Contactá al soporte.');
            }
            $this->redirect('/login');
            return;
        }
        login_ok:

        if (!$user['email_verified']) {
            $this->flash('error', 'Debés verificar tu email antes de iniciar sesión. Revisá tu bandeja de entrada.');
            $this->redirect('/login');
            return;
        }

        // Crear sesión
        $this->createSession($user);

        // Actualizar último login
        $this->userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);

        // Redirigir al destino previo o al panel
        $intended = $_SESSION['intended'] ?? null;
        unset($_SESSION['intended']);

        $this->flash('success', '¡Bienvenido, ' . explode(' ', $user['name'])[0] . '!');
        $this->redirect($intended ?: $this->dashboardUrl($user['role']));
    }

    // ══════════════════════════════════════════════════════════
    //  REGISTRO
    // ══════════════════════════════════════════════════════════

    public function registerForm(): void
    {
        $this->view('auth/register', [
            'pageTitle' => 'Crear cuenta',
            'csrf'      => csrf(),
        ]);
    }

    public function register(): void
    {
        if (!verifyCsrf($this->input('_csrf', ''))) {
            $this->flash('error', 'Token de seguridad inválido.');
            $this->redirect('/registro');
            return;
        }

        $name      = sanitize($this->input('name', ''));
        $email     = trim(strtolower($this->input('email', '')));
        $phone     = sanitize($this->input('phone', ''));
        $password  = $this->input('password', '');
        $password2 = $this->input('password_confirm', '');
        $role      = $this->input('role', 'client'); // client | shop_owner
        $terms     = $this->input('terms');

        // Validaciones
        $errors = [];

        if (!$name || mb_strlen($name) < 2)
            $errors[] = 'El nombre debe tener al menos 2 caracteres.';

        if (!isValidEmail($email))
            $errors[] = 'El email no es válido.';

        if (mb_strlen($password) < 8)
            $errors[] = 'La contraseña debe tener al menos 8 caracteres.';

        if ($password !== $password2)
            $errors[] = 'Las contraseñas no coinciden.';

        if (!in_array($role, ['client', 'shop_owner']))
            $errors[] = 'Tipo de cuenta inválido.';

        if (!$terms)
            $errors[] = 'Debés aceptar los términos y condiciones.';

        if ($this->userModel->findByEmail($email))
            $errors[] = 'Ya existe una cuenta con ese email.';

        if ($errors) {
            foreach ($errors as $e) $this->flash('error', $e);
            $_SESSION['register_old'] = compact('name','email','phone','role');
            $this->redirect('/registro');
            return;
        }

        // Crear usuario
        $token  = $this->userModel->generateToken();
        $userId = $this->userModel->insert([
            'name'           => $name,
            'email'          => $email,
            'phone'          => $phone ?: null,
            'password_hash'  => $this->userModel->hashPassword($password),
            'role'           => $role,
            'email_verified' => 0,
            'verify_token'   => $token,
            'status'         => 'active',
        ]);

        // Enviar email de verificación
        Mailer::sendVerification($email, $name, $token);

        $this->flash('success', '¡Cuenta creada! Revisá tu email para verificar tu cuenta antes de iniciar sesión.');
        $this->redirect('/login');
    }

    // ══════════════════════════════════════════════════════════
    //  VERIFICACIÓN DE EMAIL
    // ══════════════════════════════════════════════════════════

    public function verify(string $token): void
    {
        $user = $this->userModel->findBy('verify_token', $token);

        if (!$user) {
            $this->flash('error', 'El enlace de verificación es inválido o ya fue usado.');
            $this->redirect('/login');
            return;
        }

        $this->userModel->update($user['id'], [
            'email_verified' => 1,
            'verify_token'   => null,
        ]);

        // Auto-login después de verificar
        $this->createSession($user);
        $this->flash('success', '✓ Email verificado correctamente. ¡Bienvenido!');
        $this->redirect($this->dashboardUrl($user['role']));
    }

    // ══════════════════════════════════════════════════════════
    //  RECUPERAR CONTRASEÑA
    // ══════════════════════════════════════════════════════════

    public function forgotForm(): void
    {
        $this->view('auth/forgot', [
            'pageTitle' => 'Recuperar contraseña',
            'csrf'      => csrf(),
        ]);
    }

    public function forgot(): void
    {
        if (!verifyCsrf($this->input('_csrf', ''))) {
            $this->flash('error', 'Token inválido.');
            $this->redirect('/recuperar');
            return;
        }

        $email = trim(strtolower($this->input('email', '')));

        if (!isValidEmail($email)) {
            $this->flash('error', 'Ingresá un email válido.');
            $this->redirect('/recuperar');
            return;
        }

        $user = $this->userModel->findByEmail($email);

        // Siempre mostrar el mismo mensaje (evitar enumeración de emails)
        $this->flash('success', 'Si existe una cuenta con ese email, recibirás un enlace para restablecer tu contraseña.');

        if ($user) {
            $token   = $this->userModel->generateToken();
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $this->userModel->update($user['id'], [
                'reset_token'   => $token,
                'reset_expires' => $expires,
            ]);

            Mailer::sendPasswordReset($email, $user['name'], $token);
        }

        $this->redirect('/recuperar');
    }

    public function resetForm(string $token): void
    {
        $user = $this->userModel->findBy('reset_token', $token);

        if (!$user || $user['reset_expires'] < date('Y-m-d H:i:s')) {
            $this->flash('error', 'El enlace expiró o es inválido. Solicitá uno nuevo.');
            $this->redirect('/recuperar');
            return;
        }

        $this->view('auth/reset', [
            'pageTitle' => 'Nueva contraseña',
            'token'     => $token,
            'csrf'      => csrf(),
        ]);
    }

    public function reset(string $token): void
    {
        if (!verifyCsrf($this->input('_csrf', ''))) {
            $this->flash('error', 'Token inválido.');
            $this->redirect("/reset/{$token}");
            return;
        }

        $user = $this->userModel->findBy('reset_token', $token);

        if (!$user || $user['reset_expires'] < date('Y-m-d H:i:s')) {
            $this->flash('error', 'El enlace expiró. Solicitá uno nuevo.');
            $this->redirect('/recuperar');
            return;
        }

        $password  = $this->input('password', '');
        $password2 = $this->input('password_confirm', '');

        if (mb_strlen($password) < 8) {
            $this->flash('error', 'La contraseña debe tener al menos 8 caracteres.');
            $this->redirect("/reset/{$token}");
            return;
        }

        if ($password !== $password2) {
            $this->flash('error', 'Las contraseñas no coinciden.');
            $this->redirect("/reset/{$token}");
            return;
        }

        $this->userModel->update($user['id'], [
            'password_hash' => $this->userModel->hashPassword($password),
            'reset_token'   => null,
            'reset_expires' => null,
        ]);

        $this->flash('success', '¡Contraseña actualizada! Ya podés iniciar sesión.');
        $this->redirect('/login');
    }

    // ══════════════════════════════════════════════════════════
    //  LOGOUT
    // ══════════════════════════════════════════════════════════

    public function logout(): void
    {
        $name = $_SESSION['user']['name'] ?? '';
        session_destroy();
        session_start();
        $this->flash('success', 'Hasta pronto, ' . explode(' ', $name)[0] . '.');
        $this->redirect('/');
    }

    // ══════════════════════════════════════════════════════════
    //  Helpers privados
    // ══════════════════════════════════════════════════════════

    private function createSession(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role'],
            'avatar'=> $user['avatar'] ?? null,
        ];
    }

    private function dashboardUrl(string $role): string
    {
        return match($role) {
            'superadmin' => '/admin',
            'shop_owner' => '/panel',
            'employee'   => '/mi-panel',
            default      => '/mis-turnos',
        };
    }
}
