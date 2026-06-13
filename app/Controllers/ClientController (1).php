<?php
// ============================================================
//  app/Controllers/ClientController.php
// ============================================================
namespace App\Controllers;

use App\Models\Appointment;
use App\Models\Review;
use App\Models\Shop;
use App\Models\Service;
use App\Models\Employee;
use App\Models\User;

class ClientController extends BaseController
{
    private Appointment $appointmentModel;
    private Review      $reviewModel;
    private Shop        $shopModel;
    private User        $userModel;

    public function __construct()
    {
        $this->appointmentModel = new Appointment();
        $this->reviewModel      = new Review();
        $this->shopModel        = new Shop();
        $this->userModel        = new User();
    }

    // ── Dashboard ─────────────────────────────────────────────
    public function dashboard(): void
    {
        $user  = $this->auth();
        $appts = $this->appointmentModel->getForClient($user['email']);

        $upcoming = array_filter($appts, fn($a) =>
            strtotime($a['date'] . ' ' . $a['start_time']) >= time() &&
            !in_array($a['status'], ['cancelled_client','cancelled_shop'])
        );
        $past = array_filter($appts, fn($a) =>
            strtotime($a['date'] . ' ' . $a['start_time']) < time() ||
            in_array($a['status'], ['cancelled_client','cancelled_shop','completed'])
        );

        $this->view('client/dashboard', [
            'pageTitle' => 'Mi cuenta',
            'userRow'   => $user,
            'upcoming'  => array_values($upcoming),
            'past'      => array_values($past),
        ]);
    }

    // ── Lista de turnos ───────────────────────────────────────
    public function appointments(): void
    {
        $user  = $this->auth();
        $appts = $this->appointmentModel->getForClient($user['email']);

        $upcoming = array_filter($appts, fn($a) =>
            strtotime($a['date'] . ' ' . $a['start_time']) >= time() &&
            !in_array($a['status'], ['cancelled_client','cancelled_shop'])
        );
        $past = array_filter($appts, fn($a) =>
            strtotime($a['date'] . ' ' . $a['start_time']) < time() ||
            in_array($a['status'], ['cancelled_client','cancelled_shop','completed'])
        );

        $this->view('client/dashboard', [
            'pageTitle' => 'Mis turnos',
            'userRow'   => $user,
            'upcoming'  => array_values($upcoming),
            'past'      => array_values($past),
        ]);
    }

    // ── Cancelar turno ────────────────────────────────────────
    public function cancel(int $id): void
    {
        $user = $this->auth();
        $appt = $this->appointmentModel->find($id);

        if (!$appt || strtolower($appt['client_email']) !== strtolower($user['email'])) {
            $this->flash('error', 'Turno no encontrado.');
            $this->redirect('/mis-turnos');
        }

        if (in_array($appt['status'], ['cancelled_client','cancelled_shop','completed'])) {
            $this->flash('error', 'Este turno ya no puede cancelarse.');
            $this->redirect('/mis-turnos');
        }

        // 2 horas de anticipación mínima
        $apptTime = strtotime($appt['date'] . ' ' . $appt['start_time']);
        if ($apptTime - time() < 7200) {
            $this->flash('error', 'Solo podés cancelar con al menos 2 horas de anticipación.');
            $this->redirect('/mis-turnos');
        }

        $this->appointmentModel->update($id, ['status' => 'cancelled_client']);
        $this->flash('success', 'Turno cancelado correctamente.');
        $this->redirect('/mis-turnos');
    }

    // ── Seguimiento sin cuenta ────────────────────────────────
    public function track(): void
    {
        $appts = [];
        $email = '';

        if ($this->isPost()) {
            $email = strtolower(trim($this->input('email', '')));
            if ($email && isValidEmail($email)) {
                $appts = $this->appointmentModel->getForClient($email);
            } else {
                $this->flash('error', 'Ingresá un email válido.');
            }
        }

        $this->view('client/track', [
            'pageTitle'    => 'Seguimiento de turnos',
            'appointments' => $appts,
            'email'        => $email,
        ]);
    }

    // ── Perfil del cliente ────────────────────────────────────
    public function profile(): void
    {
        $user = $this->auth();

        if ($this->isPost()) {
            if (!verifyCsrf($this->input('_csrf', ''))) {
                $this->flash('error', 'Token inválido.');
                $this->redirect('/mi-cuenta');
            }

            $errors = $this->validate($_POST, [
                'name'  => 'required|min:2|max:100',
                'phone' => 'max:30',
            ]);

            if (!$errors) {
                $data = ['name' => sanitize($this->input('name')), 'phone' => sanitize($this->input('phone', ''))];

                // Cambio de contraseña opcional
                $newPass = $this->input('new_password');
                if ($newPass) {
                    if (strlen($newPass) < 8) {
                        $errors['new_password'][] = 'La contraseña debe tener al menos 8 caracteres.';
                    } else {
                        $dbUser = $this->userModel->find($user['id']);
                        if (!$this->userModel->verifyPassword($this->input('current_password', ''), $dbUser['password_hash'])) {
                            $errors['current_password'][] = 'Contraseña actual incorrecta.';
                        } else {
                            $data['password_hash'] = $this->userModel->hashPassword($newPass);
                        }
                    }
                }

                if (!$errors) {
                    $this->userModel->update($user['id'], $data);
                    $_SESSION['user']['name'] = $data['name'];
                    $this->flash('success', 'Perfil actualizado.');
                    $this->redirect('/mi-cuenta');
                }
            }

            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_old']    = $_POST;
            $this->redirect('/mi-cuenta');
        }

        $this->view('client/dashboard', [
            'pageTitle' => 'Mi cuenta',
            'userRow'   => $this->userModel->find($user['id']),
            'upcoming'  => [],
            'past'      => [],
        ]);
    }

    // ── Dejar reseña ──────────────────────────────────────────
    public function review(int $appointmentId): void
    {
        $user = $this->auth();

        if (!verifyCsrf($this->input('_csrf', ''))) {
            $this->jsonError('Sesión expirada. Recargá la página e intentá de nuevo.', 403);
        }

        $appt = $this->appointmentModel->find($appointmentId);

        if (!$appt || strtolower($appt['client_email']) !== strtolower($user['email'])) {
            $this->json(['error' => 'No autorizado'], 403);
        }

        if ($appt['status'] !== 'completed') {
            $this->json(['error' => 'Solo podés reseñar turnos completados'], 400);
        }

        // Ya existe reseña?
        $existing = $this->reviewModel->findBy('appointment_id', $appointmentId);
        if ($existing) {
            $this->json(['error' => 'Ya dejaste una reseña para este turno'], 400);
        }

        $rating  = (int) $this->input('rating');
        $comment = sanitize($this->input('comment', ''));

        if ($rating < 1 || $rating > 5) {
            $this->json(['error' => 'Puntaje inválido'], 400);
        }

        $this->reviewModel->insert([
            'appointment_id' => $appointmentId,
            'shop_id'        => $appt['shop_id'],
            'client_id'      => $appt['client_id'] ?? null,
            'rating'         => $rating,
            'comment'        => $comment,
            'is_visible'     => 1,
        ]);

        $this->shopModel->updateRating($appt['shop_id']);
        $this->json(['success' => true, 'message' => 'Reseña publicada. ¡Gracias!']);
    }
}
