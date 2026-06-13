<?php
// app/Controllers/AccountController.php
// Gestión de cuenta: cambiar email, contraseña, eliminar cuenta
namespace App\Controllers;

use App\Models\User;

class AccountController extends BaseController
{
    private User $userModel;

    public function __construct() { $this->userModel = new User(); }

    // ── Cambiar contraseña ────────────────────────────────────
    public function changePasswordForm(): void
    {
        $user = $this->auth() ?? $this->redirect('/login');
        $this->view('account/change_password', ['pageTitle' => 'Cambiar contraseña', 'user' => $user]);
    }

    public function changePassword(): void
    {
        $user = $this->auth() ?? $this->redirect('/login');
        if (!verifyCsrf($this->input('_csrf',''))) {
            $this->flash('error','Token inválido.'); $this->redirect('/cuenta/contrasena'); return;
        }
        $current = $this->input('current_password','');
        $new     = $this->input('new_password','');
        $confirm = $this->input('confirm_password','');

        $full = $this->userModel->find($user['id']);
        if (!$this->userModel->verifyPassword($current, $full['password_hash'])) {
            $this->flash('error','La contraseña actual es incorrecta.'); $this->redirect('/cuenta/contrasena'); return;
        }
        if (strlen($new) < 8) {
            $this->flash('error','La nueva contraseña debe tener al menos 8 caracteres.'); $this->redirect('/cuenta/contrasena'); return;
        }
        if ($new !== $confirm) {
            $this->flash('error','Las contraseñas no coinciden.'); $this->redirect('/cuenta/contrasena'); return;
        }
        $this->userModel->update($user['id'], ['password_hash' => $this->userModel->hashPassword($new)]);
        $this->flash('success','Contraseña actualizada correctamente.');
        $this->redirect($this->backUrl($user['role']));
    }

    // ── Cambiar email ─────────────────────────────────────────
    public function changeEmailForm(): void
    {
        $user = $this->auth() ?? $this->redirect('/login');
        $this->view('account/change_email', ['pageTitle' => 'Cambiar email', 'user' => $user]);
    }

    public function changeEmail(): void
    {
        $user = $this->auth() ?? $this->redirect('/login');
        if (!verifyCsrf($this->input('_csrf',''))) {
            $this->flash('error','Token inválido.'); $this->redirect('/cuenta/email'); return;
        }
        $newEmail = strtolower(trim($this->input('new_email','')));
        $password = $this->input('password','');

        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            $this->flash('error','Email no válido.'); $this->redirect('/cuenta/email'); return;
        }
        $full = $this->userModel->find($user['id']);
        if (!$this->userModel->verifyPassword($password, $full['password_hash'])) {
            $this->flash('error','Contraseña incorrecta.'); $this->redirect('/cuenta/email'); return;
        }
        if ($this->userModel->findByEmail($newEmail)) {
            $this->flash('error','Ese email ya está en uso.'); $this->redirect('/cuenta/email'); return;
        }
        $this->userModel->update($user['id'], ['email' => $newEmail, 'email_verified' => 0]);
        $_SESSION['user']['email'] = $newEmail;
        $this->flash('success','Email actualizado. Necesitás verificarlo de nuevo.');
        $this->redirect($this->backUrl($user['role']));
    }

    // ── Eliminar cuenta (no superadmin) ──────────────────────
    public function deleteAccountForm(): void
    {
        $user = $this->auth() ?? $this->redirect('/login');
        if ($user['role'] === 'superadmin') { $this->redirect('/'); }
        $this->view('account/delete_account', ['pageTitle' => 'Eliminar cuenta', 'user' => $user]);
    }

    public function deleteAccount(): void
    {
        $user = $this->auth() ?? $this->redirect('/login');
        if ($user['role'] === 'superadmin') { $this->redirect('/'); }
        if (!verifyCsrf($this->input('_csrf',''))) {
            $this->flash('error','Token inválido.'); $this->redirect('/cuenta/eliminar'); return;
        }
        $password = $this->input('password','');
        $full     = $this->userModel->find($user['id']);
        if (!$this->userModel->verifyPassword($password, $full['password_hash'])) {
            $this->flash('error','Contraseña incorrecta.'); $this->redirect('/cuenta/eliminar'); return;
        }

        $db = \App\Models\Database::getInstance();

        // Anonymize reviews (keep content for shop stats)
        $db->prepare("UPDATE reviews SET client_note='[usuario eliminado]' WHERE appointment_id IN
            (SELECT id FROM appointments WHERE client_email=?)")->execute([$full['email']]);

        // Cancel pending/confirmed future appointments
        $db->prepare("UPDATE appointments SET status='cancelled_client'
            WHERE client_email=? AND date >= CURDATE()
            AND status IN ('pending','confirmed')")->execute([$full['email']]);

        // If employee: unlink + demote
        if ($user['role'] === 'employee') {
            $db->prepare("UPDATE employees SET user_id=NULL WHERE user_id=?")->execute([$user['id']]);
        }

        // If shop_owner: mark shop inactive
        if ($user['role'] === 'shop_owner') {
            $db->prepare("UPDATE shops SET status='inactive' WHERE owner_id=?")->execute([$user['id']]);
        }

        // Anonymize user
        $db->prepare("UPDATE users SET
            name='[eliminado]',
            email=CONCAT('deleted_',id,'@trimly.local'),
            phone=NULL, avatar=NULL,
            password_hash='',
            status='banned',
            ban_reason='Cuenta eliminada por el propio usuario'
            WHERE id=?")->execute([$user['id']]);

        session_destroy();
        header('Location: ' . base_path() . '/');
        exit;
    }

    private function backUrl(string $role): string {
        return match($role) {
            'superadmin' => '/admin',
            'shop_owner' => '/panel',
            'employee'   => '/mi-panel',
            default      => '/mis-turnos',
        };
    }
}
