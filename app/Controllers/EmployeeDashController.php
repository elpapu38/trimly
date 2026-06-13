<?php
// ============================================================
//  app/Controllers/EmployeeDashController.php
//  Panel personal para empleados logueados
// ============================================================
namespace App\Controllers;

use App\Models\Employee;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Shop;

class EmployeeDashController extends BaseController
{
    private Employee    $employeeModel;
    private Appointment $appointmentModel;
    private Service     $serviceModel;
    private Shop        $shopModel;

    public function __construct()
    {
        $this->employeeModel    = new Employee();
        $this->appointmentModel = new Appointment();
        $this->serviceModel     = new Service();
        $this->shopModel        = new Shop();
    }

    // Obtener el registro de empleado vinculado al usuario logueado
    private function getEmployee(): array
    {
        $user     = $this->auth();
        $db       = \App\Models\Database::getInstance();
        $stmt     = $db->prepare('SELECT e.*, sh.name AS shop_name, sh.slug AS shop_slug, sh.currency
                                   FROM employees e JOIN shops sh ON sh.id = e.shop_id
                                   WHERE e.user_id = ? AND e.status = "active" LIMIT 1');
        $stmt->execute([$user['id']]);
        $employee = $stmt->fetch();
        if (!$employee) {
            $this->flash('error', 'Tu cuenta de empleado no está vinculada a ningún local activo.');
            $this->redirect('/login');
        }
        return $employee;
    }

    // ── Dashboard ─────────────────────────────────────────────
    public function index(): void
    {
        $emp   = $this->getEmployee();
        $today = date('Y-m-d');
        $db    = \App\Models\Database::getInstance();

        $todayAppts = $this->appointmentModel->raw(
            "SELECT a.*, s.name AS service_name, s.duration_min
             FROM appointments a JOIN services s ON s.id = a.service_id
             WHERE a.employee_id = ? AND a.date = ?
             AND a.status NOT IN ('cancelled_client','cancelled_shop')
             ORDER BY a.start_time",
            [$emp['id'], $today]
        );

        // Stats del mes
        $st = $db->prepare("SELECT
            COUNT(*) AS total,
            SUM(CASE WHEN status='completed' THEN 1 ELSE 0 END) AS completed,
            SUM(CASE WHEN status='completed' THEN price ELSE 0 END) AS revenue
            FROM appointments WHERE employee_id = ?
            AND MONTH(date)=MONTH(CURRENT_DATE()) AND YEAR(date)=YEAR(CURRENT_DATE())");
        $st->execute([$emp['id']]);
        $monthStats = $st->fetch();

        $this->view('employee_dash/index', [
            'pageTitle'   => 'Mi panel — ' . $emp['name'],
            'employee'    => $emp,
            'todayAppts'  => $todayAppts,
            'monthStats'  => $monthStats,
            'today'       => $today,
        ]);
    }

    // ── Turnos del empleado ───────────────────────────────────
    public function appointments(): void
    {
        $emp    = $this->getEmployee();
        $date   = $this->input('date', date('Y-m-d'));
        $status = $this->input('status', '');

        // ── Turnos del empleado en el día ─────────────────────
        $extra  = $status ? 'AND a.status = ?' : '';
        $params = $status ? [$emp['id'], $date, $status] : [$emp['id'], $date];
        $appts  = $this->appointmentModel->raw(
            "SELECT a.*, s.name AS service_name, s.duration_min
             FROM appointments a JOIN services s ON s.id = a.service_id
             WHERE a.employee_id = ? AND a.date = ? {$extra}
             ORDER BY a.start_time",
            $params
        );

        // ── Vista semanal (lun–dom de la semana del día seleccionado) ──
        $dayOfWeek   = (int) date('N', strtotime($date)); // 1=Mon 7=Sun
        $weekStart   = date('Y-m-d', strtotime($date . ' -' . ($dayOfWeek - 1) . ' days'));
        $weekEnd     = date('Y-m-d', strtotime($weekStart . ' +6 days'));
        $weekAppts   = $this->appointmentModel->raw(
            "SELECT a.date, a.start_time, a.end_time, a.status, s.name AS service_name, a.client_name
             FROM appointments a JOIN services s ON s.id = a.service_id
             WHERE a.employee_id = ? AND a.date BETWEEN ? AND ?
             AND a.status NOT IN ('cancelled_client','cancelled_shop')
             ORDER BY a.date, a.start_time",
            [$emp['id'], $weekStart, $weekEnd]
        );
        // Index by date
        $weekMap = [];
        foreach ($weekAppts as $wa) $weekMap[$wa['date']][] = $wa;

        // ── Turnos sin empleado asignado del mismo local ──────
        $openAppts = $this->appointmentModel->raw(
            "SELECT a.*, s.name AS service_name, s.duration_min
             FROM appointments a JOIN services s ON s.id = a.service_id
             WHERE a.shop_id = ? AND (a.employee_id IS NULL OR a.employee_id = 0)
             AND a.date >= CURDATE() AND a.status IN ('pending','confirmed')
             ORDER BY a.date, a.start_time",
            [$emp['shop_id']]
        );

        $config   = require BASE_PATH . '/config/app.php';
        $statuses = $config['appointment_statuses'];

        $this->view('employee_dash/appointments', [
            'pageTitle'      => 'Mis turnos',
            'employee'       => $emp,
            'appointments'   => $appts,
            'selectedDate'   => $date,
            'selectedStatus' => $status,
            'statuses'       => $statuses,
            'weekMap'        => $weekMap,
            'weekStart'      => $weekStart,
            'weekEnd'        => $weekEnd,
            'openAppts'      => $openAppts,
        ]);
    }

    // ── Cambiar estado de un turno (solo los propios) ─────────
    public function updateStatus(int $id): void
    {
        $emp  = $this->getEmployee();
        $appt = $this->appointmentModel->find($id);

        if (!$appt || (int)$appt['employee_id'] !== (int)$emp['id']) {
            $this->jsonError('Turno no encontrado.', 404);
        }

        $newStatus = $this->input('status');
        $allowed   = ['confirmed', 'completed', 'no_show', 'cancelled_shop'];
        if (!in_array($newStatus, $allowed)) {
            $this->jsonError('Estado no válido.', 400);
        }

        $this->appointmentModel->update($id, ['status' => $newStatus]);
        $this->json(['success' => true, 'status' => $newStatus]);
    }

    // ── Cargar turno manual ───────────────────────────────────
    public function newAppointment(): void
    {
        $emp      = $this->getEmployee();
        $services = $this->employeeModel->raw(
            "SELECT s.* FROM services s
             JOIN employee_services es ON es.service_id = s.id
             WHERE es.employee_id = ? AND s.is_active = 1 ORDER BY s.name",
            [$emp['id']]
        );

        $this->view('employee_dash/new_appointment', [
            'pageTitle' => 'Cargar turno',
            'employee'  => $emp,
            'services'  => $services,
        ]);
    }

    public function storeAppointment(): void
    {
        $emp = $this->getEmployee();

        if (!verifyCsrf($this->input('_csrf', ''))) {
            $this->jsonError('Token inválido.');
        }

        $serviceId = (int) $this->input('service_id');
        $date      = $this->input('date');
        $startTime = $this->input('start_time');
        $clientName= sanitize($this->input('client_name', ''));
        $clientPhone = sanitize($this->input('client_phone', ''));

        if (!$serviceId || !$date || !$startTime || !$clientName) {
            $this->jsonError('Completá todos los campos obligatorios.');
        }

        // No permitir fechas pasadas
        if (strtotime($date) < strtotime('today')) {
            $this->jsonError('La fecha no puede ser en el pasado.');
        }
        if ($date === date('Y-m-d') && strtotime("$date $startTime") < time()) {
            $this->jsonError('El horario ya pasó.');
        }

        $service = $this->serviceModel->find($serviceId);
        if (!$service) { $this->jsonError('Servicio no válido.'); }

        // Bloquear emails de cuentas que no pueden recibir turnos
        $clientEmail = strtolower(sanitize($this->input('client_email', '')));
        if ($clientEmail) {
            $db2   = \App\Models\Database::getInstance();
            $check = $db2->prepare("SELECT role FROM users WHERE email = ? LIMIT 1");
            $check->execute([$clientEmail]);
            $found = $check->fetch();
            if ($found && in_array($found['role'], ['superadmin', 'shop_owner', 'employee'])) {
                $this->jsonError('Ese usuario no puede recibir turnos (cuenta de negocio o empleado).');
            }
        }

        $endTime = date('H:i', strtotime($startTime) + $service['duration_min'] * 60);

        // Verificar que el slot esté libre para este empleado
        if ($this->appointmentModel->isSlotTaken($emp['id'], $date, $startTime, $endTime)) {
            $this->jsonError('Ya tenés un turno en ese horario.');
        }

        $db = \App\Models\Database::getInstance();
        $id = $this->appointmentModel->insert([
            'shop_id'      => $emp['shop_id'],
            'employee_id'  => $emp['id'],
            'service_id'   => $serviceId,
            'client_name'  => $clientName,
            'client_email' => strtolower(sanitize($this->input('client_email', 'manual@trimly.local'))),
            'client_phone' => $clientPhone,
            'date'         => $date,
            'start_time'   => $startTime,
            'end_time'     => $endTime,
            'duration_min' => $service['duration_min'],
            'price'        => $service['price'],
            'deposit_amount' => 0,
            'status'       => 'confirmed',
            'payment_option' => 'on_site',
            'payment_status' => 'unpaid',
            'notes'        => sanitize($this->input('notes', '')),
            'confirm_token'=> bin2hex(random_bytes(20)),
            'cancel_token' => bin2hex(random_bytes(20)),
        ]);

        $this->json(['success' => true, 'id' => $id]);
    }

    // ── Mis servicios ─────────────────────────────────────────
    public function services(): void
    {
        $emp      = $this->getEmployee();
        $all      = $this->serviceModel->raw(
            "SELECT * FROM services WHERE shop_id = ? AND is_active = 1 ORDER BY name",
            [$emp['shop_id']]
        );
        $assigned = $this->employeeModel->raw(
            "SELECT service_id FROM employee_services WHERE employee_id = ?",
            [$emp['id']]
        );
        $assignedIds = array_column($assigned, 'service_id');

        $this->view('employee_dash/services', [
            'pageTitle'   => 'Mis servicios',
            'employee'    => $emp,
            'allServices' => $all,
            'assignedIds' => $assignedIds,
        ]);
    }

    public function updateServices(): void
    {
        $emp = $this->getEmployee();
        if (!verifyCsrf($this->input('_csrf', ''))) {
            $this->jsonError('Token inválido.');
        }

        $db         = \App\Models\Database::getInstance();
        $serviceIds = array_map('intval', $_POST['service_ids'] ?? []);

        // Verify all services belong to the same shop
        if ($serviceIds) {
            $placeholders = implode(',', array_fill(0, count($serviceIds), '?'));
            $stmt = $db->prepare("SELECT id FROM services WHERE id IN ({$placeholders}) AND shop_id = ?");
            $stmt->execute([...$serviceIds, $emp['shop_id']]);
            $validIds = array_column($stmt->fetchAll(), 'id');
        } else {
            $validIds = [];
        }

        $db->prepare("DELETE FROM employee_services WHERE employee_id = ?")->execute([$emp['id']]);
        foreach ($validIds as $sid) {
            $db->prepare("INSERT IGNORE INTO employee_services (employee_id, service_id) VALUES (?,?)")
               ->execute([$emp['id'], $sid]);
        }

        $this->json(['success' => true, 'assigned' => count($validIds)]);
    }

    // ── Mi perfil ─────────────────────────────────────────────
    public function profile(): void
    {
        $emp = $this->getEmployee();
        $this->view('employee_dash/profile', [
            'pageTitle' => 'Mi perfil',
            'employee'  => $emp,
        ]);
    }

    public function updateProfile(): void
    {
        $emp  = $this->getEmployee();
        $user = $this->auth();

        if (!verifyCsrf($this->input('_csrf', ''))) {
            $this->flash('error', 'Token inválido.'); $this->redirect('/mi-panel/perfil');
        }

        $errors = $this->validate($_POST, [
            'name'      => 'required|min:2|max:100',
            'specialty' => 'max:150',
            'instagram' => 'max:100',
        ]);
        if ($errors) {
            $_SESSION['form_errors'] = $errors;
            $this->redirect('/mi-panel/perfil');
        }

        $data = [
            'name'      => sanitize($this->input('name')),
            'bio'       => sanitize($this->input('bio', '')),
            'specialty' => sanitize($this->input('specialty', '')),
            'instagram' => ltrim(trim($this->input('instagram', '')), '@'),
        ];

        if (!empty($_FILES['avatar']['name'])) {
            $avatar = uploadImage($_FILES['avatar'], 'employees');
            if ($avatar) $data['avatar'] = $avatar;
        }

        $this->employeeModel->update($emp['id'], $data);

        // Also update users.name
        $db = \App\Models\Database::getInstance();
        $db->prepare("UPDATE users SET name = ? WHERE id = ?")->execute([$data['name'], $user['id']]);
        $_SESSION['user']['name'] = $data['name'];

        $this->flash('success', 'Perfil actualizado.');
        $this->redirect('/mi-panel/perfil');
    }

    // ── Mis fotos / portfolio ─────────────────────────────────
    public function photos(): void
    {
        $emp    = $this->getEmployee();
        $db     = \App\Models\Database::getInstance();
        $photos = $db->prepare(
            "SELECT * FROM employee_photos WHERE employee_id = ? ORDER BY created_at DESC"
        );
        $photos->execute([$emp['id']]);

        $this->view('employee_dash/photos', [
            'pageTitle' => 'Mis fotos',
            'employee'  => $emp,
            'photos'    => $photos->fetchAll(),
        ]);
    }

    public function uploadPhoto(): void
    {
        $emp = $this->getEmployee();
        $db  = \App\Models\Database::getInstance();

        if (empty($_FILES['photo']['name'])) {
            $this->jsonError('No se recibió ningún archivo.', 400);
        }

        $path = uploadImage($_FILES['photo'], 'employees');
        if (!$path) {
            $this->jsonError('Archivo no válido (JPG, PNG, WebP — máx. 5 MB).', 400);
        }

        $caption = sanitize($this->input('caption', ''));
        $stmt = $db->prepare(
            "INSERT INTO employee_photos (employee_id, filename, caption) VALUES (?,?,?)"
        );
        $stmt->execute([$emp['id'], $path, $caption]);
        $photoId = (int) $db->lastInsertId();

        $this->json(['success' => true, 'id' => $photoId, 'url' => upload_url($path), 'filename' => $path]);
    }

    public function deletePhoto(int $id): void
    {
        $emp = $this->getEmployee();
        $db  = \App\Models\Database::getInstance();

        $stmt = $db->prepare("SELECT * FROM employee_photos WHERE id = ? AND employee_id = ?");
        $stmt->execute([$id, $emp['id']]);
        $photo = $stmt->fetch();

        if (!$photo) { $this->jsonError('Foto no encontrada.', 404); }

        $file = PUB_PATH . '/uploads/' . $photo['filename'];
        if (file_exists($file)) @unlink($file);

        $db->prepare("DELETE FROM employee_photos WHERE id = ?")->execute([$id]);
        $this->json(['success' => true]);
    }
}
