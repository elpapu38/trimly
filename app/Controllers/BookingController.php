<?php
// ============================================================
//  app/Controllers/BookingController.php
// ============================================================
namespace App\Controllers;

use App\Models\Shop;
use App\Models\Service;
use App\Models\Employee;
use App\Models\Appointment;

class BookingController extends BaseController
{
    private Shop        $shopModel;
    private Service     $serviceModel;
    private Employee    $employeeModel;
    private Appointment $appointmentModel;

    public function __construct()
    {
        $this->shopModel        = new Shop();
        $this->serviceModel     = new Service();
        $this->employeeModel    = new Employee();
        $this->appointmentModel = new Appointment();
    }

    // ── Paso 1: Elegir servicio ───────────────────────────────
    public function step1(string $slug): void
    {
        $shop = $this->shopModel->findBySlug($slug);
        if (!$shop || $shop['status'] !== 'active') {
            $this->redirect('/');
        }

        // Limpiar sesión de reserva anterior para este local
        if (($_SESSION['booking']['shop_id'] ?? null) !== $shop['id']) {
            unset($_SESSION['booking']);
        }

        $services = $this->serviceModel->getGroupedByCategory($shop['id']);

        $this->view('booking/step1', [
            'pageTitle' => 'Reservar turno — ' . $shop['name'],
            'shop'      => $shop,
            'services'  => $services,
            'booking'   => $_SESSION['booking'] ?? [],
        ]);
    }

    // ── POST: guardar servicio elegido ────────────────────────
    public function setService(string $slug): void
    {
        $shop = $this->shopModel->findBySlug($slug);
        if (!$shop) $this->redirect('/');

        $serviceId = (int) $this->input('service_id');
        $service   = $this->serviceModel->find($serviceId);

        if (!$service || $service['shop_id'] !== $shop['id']) {
            $this->flash('error', 'Servicio no válido.');
            $this->redirect("/reservar/$slug");
        }

        $_SESSION['booking'] = [
            'shop_id'    => $shop['id'],
            'shop_slug'  => $slug,
            'service_id' => $service['id'],
            'service'    => $service,
        ];

        $this->redirect("/reservar/$slug/empleado");
    }

    // ── Paso 2: Elegir empleado ───────────────────────────────
    public function setEmployee(string $slug): void
    {
        $this->requireBookingStep($slug, 'service_id');
        $shop     = $this->shopModel->findBySlug($slug);
        $booking  = $_SESSION['booking'];

        if ($this->isPost()) {
            $employeeId = (int) $this->input('employee_id');

            if ($employeeId === 0) {
                // "Cualquier empleado" — se asignará al confirmar
                $_SESSION['booking']['employee_id']   = 0;
                $_SESSION['booking']['employee_name'] = 'Cualquiera disponible';
            } else {
                $employee = $this->employeeModel->find($employeeId);
                if (!$employee || $employee['shop_id'] !== $shop['id']) {
                    $this->flash('error', 'Empleado no válido.');
                    $this->redirect("/reservar/$slug/empleado");
                }
                $_SESSION['booking']['employee_id']   = $employee['id'];
                $_SESSION['booking']['employee_name'] = $employee['name'];
            }

            $this->redirect("/reservar/$slug/horario");
        }

        $employees = $this->employeeModel->getAvailableForService($shop['id'], $booking['service_id']);

        $this->view('booking/step2', [
            'pageTitle' => 'Elegir profesional — ' . $shop['name'],
            'shop'      => $shop,
            'employees' => $employees,
            'booking'   => $booking,
        ]);
    }

    // ── Paso 3: Elegir fecha y horario ───────────────────────
    public function step3(string $slug): void
    {
        $this->requireBookingStep($slug, 'employee_id');
        $shop    = $this->shopModel->findBySlug($slug);
        $booking = $_SESSION['booking'];

        $this->view('booking/step3', [
            'pageTitle' => 'Elegir horario — ' . $shop['name'],
            'shop'      => $shop,
            'booking'   => $booking,
        ]);
    }

    public function setSlot(string $slug): void
    {
        $this->requireBookingStep($slug, 'employee_id');

        $date  = $this->input('date');
        $start = $this->input('start_time');

        if (!$date || !$start) {
            $this->flash('error', 'Seleccioná fecha y horario.');
            $this->redirect("/reservar/$slug/horario");
        }

        // Validar fecha no pasada
        if (strtotime($date) < strtotime('today')) {
            $this->flash('error', 'La fecha no puede ser en el pasado.');
            $this->redirect("/reservar/$slug/horario");
        }

        // Validar que si es hoy el horario sea futuro (con 5 min de buffer)
        if ($date === date('Y-m-d') && strtotime($date . ' ' . $start) < (time() + 5 * 60)) {
            $this->flash('error', 'Ese horario ya pasó. Por favor elegí uno futuro.');
            $this->redirect("/reservar/$slug/horario");
        }

        $_SESSION['booking']['date']       = $date;
        $_SESSION['booking']['start_time'] = $start;

        $this->redirect("/reservar/$slug/confirmar");
    }

    // ── Paso 4: Confirmación con datos personales ─────────────
    public function step4(string $slug): void
    {
        $this->requireBookingStep($slug, 'start_time');
        $shop    = $this->shopModel->findBySlug($slug);
        $booking = $_SESSION['booking'];

        // Pre-fill user data if logged in
        $user = $this->auth();

        $service     = $booking['service'] ?? [];
        $depositPct  = (int)($service['deposit_pct'] ?? 0);
        $depositAmt  = $depositPct > 0 ? round($service['price'] * $depositPct / 100, 2) : 0;

        $this->view('booking/step4', [
            'pageTitle'  => 'Confirmar turno — ' . $shop['name'],
            'shop'       => $shop,
            'booking'    => $booking,
            'user'       => $user,
            'depositPct' => $depositPct,
            'depositAmt' => $depositAmt,
        ]);
    }

    public function confirm(string $slug): void
    {
        $this->requireBookingStep($slug, 'start_time');

        $shop    = $this->shopModel->findBySlug($slug);
        $booking = $_SESSION['booking'];

        // Validar CSRF
        if (!verifyCsrf($this->input('_csrf', ''))) {
            $this->flash('error', 'Token inválido. Intentá de nuevo.');
            $this->redirect("/reservar/$slug/confirmar");
        }

        $errors = $this->validate($_POST, [
            'client_name'  => 'required|min:2|max:100',
            'client_email' => 'required|email',
            'client_phone' => 'max:30',
            'payment_option' => 'required|in:online,on_site',
        ]);

        if ($errors) {
            $_SESSION['form_errors']  = $errors;
            $_SESSION['form_old']     = $_POST;
            $this->redirect("/reservar/$slug/confirmar");
        }

        $service  = $this->serviceModel->find($booking['service_id']);
        $empId    = (int) $booking['employee_id'];

        // Si eligió "cualquiera", asignar el primer disponible
        if ($empId === 0) {
            $avail = $this->employeeModel->getAvailableForService($shop['id'], $service['id']);
            if (empty($avail)) {
                $this->flash('error', 'No hay profesionales disponibles en este momento.');
                $this->redirect("/reservar/$slug");
            }
            $empId = (int) $avail[0]['id'];
        }

        $date      = $booking['date'];
        $startTime = $booking['start_time'];
        $endTime   = date('H:i', strtotime($startTime) + $service['duration_min'] * 60);

        // Verificar que el slot sigue libre
        if ($this->appointmentModel->isSlotTaken($empId, $date, $startTime, $endTime)) {
            $this->flash('error', 'Ese horario ya fue tomado. Por favor elegí otro.');
            $this->redirect("/reservar/$slug/horario");
        }

        // Crear registro de cliente
        $db         = \App\Models\Database::getInstance();
        $clientStmt = $db->prepare("INSERT INTO clients (name, email, phone) VALUES (?,?,?) ON DUPLICATE KEY UPDATE name=VALUES(name), phone=VALUES(phone)");
        $clientStmt->execute([
            sanitize($this->input('client_name')),
            strtolower(trim($this->input('client_email'))),
            sanitize($this->input('client_phone', '')),
        ]);
        $clientId = (int) $db->lastInsertId() ?: null;

        $token        = bin2hex(random_bytes(20));
        $cancelToken  = bin2hex(random_bytes(20));

        $appointmentId = $this->appointmentModel->insert([
            'shop_id'        => $shop['id'],
            'employee_id'    => $empId,
            'service_id'     => $service['id'],
            'client_id'      => $clientId,
            'client_name'    => sanitize($this->input('client_name')),
            'client_email'   => strtolower(trim($this->input('client_email'))),
            'client_phone'   => sanitize($this->input('client_phone', '')),
            'date'           => $date,
            'start_time'     => $startTime,
            'end_time'       => $endTime,
            'duration_min'   => $service['duration_min'],
            'price'          => $service['price'],
            'deposit_amount' => (function() use ($service) {
                $opt = $this->input('payment_option');
                $pct = (int)($service['deposit_pct'] ?? 0);
                if ($opt === 'deposit' && $pct > 0) {
                    return round($service['price'] * $pct / 100, 2);
                }
                return 0;
            })(),
            'status'         => 'pending',
            'payment_option' => $this->input('payment_option'),
            'payment_status' => 'unpaid',
            'notes'          => sanitize($this->input('notes', '')),
            'confirm_token'  => $token,
            'cancel_token'   => $cancelToken,
        ]);

        // Enviar email de confirmación
        try {
            $mailer = new \App\Helpers\Mailer();
            $appt   = $this->appointmentModel->find($appointmentId);
            $mailer->sendBookingConfirmation($appt, $shop);
        } catch (\Exception $e) {
            // No bloquear si falla el email
        }

        // Limpiar sesión de booking
        unset($_SESSION['booking']);

        $this->redirect("/reservar/gracias/$token");
    }

    // ── Gracias ───────────────────────────────────────────────
    public function thanks(string $token): void
    {
        $appt = $this->appointmentModel->findBy('confirm_token', $token);
        if (!$appt) $this->redirect('/');

        $shop    = $this->shopModel->find($appt['shop_id']);
        $service = $this->serviceModel->find($appt['service_id']);
        $emp     = $this->employeeModel->find($appt['employee_id']);

        $this->view('booking/thanks', [
            'pageTitle'   => '¡Turno reservado!',
            'appointment' => $appt,
            'shop'        => $shop,
            'service'     => $service,
            'employee'    => $emp,
        ]);
    }

    // ── API: empleados disponibles para un servicio (AJAX) ───
    // Usado cuando employee_id=0 ("cualquiera") en step3
    public function apiEmployeesForService(): void
    {
        $slug      = $_GET['shop_slug'] ?? '';
        $serviceId = (int) ($_GET['service_id'] ?? 0);

        $shop = $this->shopModel->findBySlug($slug);
        if (!$shop || !$serviceId) {
            $this->json(['employees' => []]);
        }

        $employees = $this->employeeModel->getAvailableForService($shop['id'], $serviceId);
        $this->json(['employees' => $employees]);
    }

    // ── API: slots disponibles (AJAX) ─────────────────────────
    public function apiSlots(): void
    {
        $employeeId = (int) ($_GET['employee_id'] ?? 0);
        $serviceId  = (int) ($_GET['service_id'] ?? 0);
        $date       = $_GET['date'] ?? '';

        if (!$employeeId || !$serviceId || !$date) {
            $this->json(['slots' => []]);
        }

        // Verificar que no sea fecha pasada
        if (strtotime($date) < strtotime('today')) {
            $this->json(['slots' => [], 'error' => 'Fecha pasada']);
        }

        $slots = $this->appointmentModel->getAvailableSlots($employeeId, $serviceId, $date);
        $this->json(['slots' => $slots, 'date' => $date]);
    }

    // ── Cancelar por token ────────────────────────────────────
    public function cancelByToken(string $token): void
    {
        $appt = $this->appointmentModel->findBy('cancel_token', $token);
        if (!$appt) {
            $this->flash('error', 'Token de cancelación inválido.');
            $this->redirect('/');
        }

        if (in_array($appt['status'], ['cancelled_client','cancelled_shop','completed'])) {
            $this->flash('info', 'Este turno ya está cancelado o completado.');
            $this->redirect('/');
        }

        // Verificar que el turno sea futuro
        $apptDateTime = strtotime($appt['date'] . ' ' . $appt['start_time']);
        if ($apptDateTime < time()) {
            $this->flash('error', 'No podés cancelar un turno pasado.');
            $this->redirect('/');
        }

        $this->appointmentModel->update($appt['id'], ['status' => 'cancelled_client']);

        $shop = $this->shopModel->find($appt['shop_id']);
        $this->view('booking/cancelled', [
            'pageTitle'   => 'Turno cancelado',
            'appointment' => $appt,
            'shop'        => $shop,
        ]);
    }

    // ── Helper privado ────────────────────────────────────────
    private function requireBookingStep(string $slug, string $requiredKey): void
    {
        $booking = $_SESSION['booking'] ?? [];
        if (empty($booking['shop_id']) || !isset($booking[$requiredKey])) {
            $this->redirect("/reservar/$slug");
        }
    }
}
