<?php
// ============================================================
//  app/Controllers/ShopDashController.php
// ============================================================
namespace App\Controllers;

use App\Models\Shop;
use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Service;
use App\Models\Review;

class ShopDashController extends BaseController
{
    private Shop        $shopModel;
    private Appointment $appointmentModel;
    private Employee    $employeeModel;
    private Service     $serviceModel;
    private Review      $reviewModel;

    public function __construct()
    {
        $this->shopModel        = new Shop();
        $this->appointmentModel = new Appointment();
        $this->employeeModel    = new Employee();
        $this->serviceModel     = new Service();
        $this->reviewModel      = new Review();
    }

    private function getOwnerShop(): array
    {
        $user = $this->auth();
        if ($user['role'] === 'superadmin') {
            // Admin puede gestionar cualquier local; usar _shop_id de sesión
            $shopId = $_SESSION['admin_shop_id'] ?? 0;
            $shop   = $shopId ? $this->shopModel->find($shopId) : null;
        } else {
            $shop = $this->shopModel->findBy('owner_id', $user['id']);
        }

        if (!$shop) {
            $this->flash('info', 'Primero registrá tu local.');
            $this->redirect('/registrar-local');
        }

        return $shop;
    }

    // ── Dashboard principal ───────────────────────────────────
    public function index(): void
    {
        $shop  = $this->getOwnerShop();
        $today = date('Y-m-d');

        $todayAppts   = $this->appointmentModel->getByShop($shop['id'], $today);
        $employees    = $this->employeeModel->getByShop($shop['id']);
        $reviews      = $this->reviewModel->getByShop($shop['id'], 1, 5);

        // Stats rápidas del mes
        $db       = \App\Models\Database::getInstance();
        $monthSt  = $db->prepare("SELECT
            COUNT(*) AS total,
            SUM(CASE WHEN status='completed' THEN 1 ELSE 0 END) AS completed,
            SUM(CASE WHEN status='cancelled_client' OR status='cancelled_shop' THEN 1 ELSE 0 END) AS cancelled,
            SUM(CASE WHEN status='completed' THEN price ELSE 0 END) AS revenue
            FROM appointments
            WHERE shop_id = ? AND MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE())");
        $monthSt->execute([$shop['id']]);
        $monthStats = $monthSt->fetch();

        $this->view('shop_dash/index', [
            'pageTitle'   => 'Panel — ' . $shop['name'],
            'shop'        => $shop,
            'todayAppts'  => $todayAppts,
            'employees'   => $employees,
            'recentReviews' => $reviews['data'],
            'monthStats'  => $monthStats,
            'today'       => $today,
        ]);
    }

    // ── Agenda (calendario) ──────────────────────────────────
    public function agenda(): void
    {
        $shop      = $this->getOwnerShop();
        $employees = $this->employeeModel->getByShop($shop['id']);

        $this->view('shop_dash/agenda', [
            'pageTitle' => 'Agenda — ' . $shop['name'],
            'shop'      => $shop,
            'employees' => $employees,
        ]);
    }

    // ── Lista de turnos ───────────────────────────────────────
    public function appointments(): void
    {
        $shop   = $this->getOwnerShop();
        $date   = $this->input('date', date('Y-m-d'));
        $status = $this->input('status', '');

        $appts = $this->appointmentModel->getByShop($shop['id'], $date, $status);

        if ($this->isAjax()) {
            $this->json(['appointments' => $appts]);
        }

        $this->view('shop_dash/appointments', [
            'pageTitle'    => 'Turnos — ' . $shop['name'],
            'shop'         => $shop,
            'appointments' => $appts,
            'selectedDate' => $date,
            'selectedStatus' => $status,
        ]);
    }

    // ── Cambiar estado de un turno ────────────────────────────
    public function updateStatus(int $id): void
    {
        $shop   = $this->getOwnerShop();
        $appt   = $this->appointmentModel->find($id);

        if (!$appt || $appt['shop_id'] !== $shop['id']) {
            $this->json(['error' => 'Turno no encontrado'], 404);
        }

        $newStatus = $this->input('status');
        $allowed   = ['confirmed','cancelled_shop','completed','no_show','pending'];

        if (!in_array($newStatus, $allowed)) {
            $this->json(['error' => 'Estado no válido'], 400);
        }

        $this->appointmentModel->update($id, [
            'status'         => $newStatus,
            'internal_notes' => sanitize($this->input('internal_notes', $appt['internal_notes'] ?? '')),
        ]);

        // Si se completó, marcar reminder como enviado
        if ($newStatus === 'completed') {
            $this->appointmentModel->update($id, ['reminder_sent' => 1]);
        }

        $this->json(['success' => true, 'status' => $newStatus]);
    }

    // ── API: agenda para FullCalendar ─────────────────────────
    public function agendaApi(): void
    {
        $shop  = $this->getOwnerShop();
        $start = $this->input('start', date('Y-m-01'));
        $end   = $this->input('end', date('Y-m-t'));
        $empId = (int) $this->input('employee_id', 0);

        $db     = \App\Models\Database::getInstance();
        $sql    = "SELECT a.*, e.name AS employee_name, s.name AS service_name
                   FROM appointments a
                   JOIN employees e ON e.id = a.employee_id
                   JOIN services s  ON s.id = a.service_id
                   WHERE a.shop_id = ? AND a.date BETWEEN ? AND ?
                   AND a.status NOT IN ('cancelled_client','cancelled_shop')";
        $params = [$shop['id'], $start, $end];

        if ($empId) {
            $sql    .= ' AND a.employee_id = ?';
            $params[] = $empId;
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $appts = $stmt->fetchAll();

        $statusColors = [
            'pending'   => '#C9A84C',
            'confirmed' => '#22c55e',
            'completed' => '#3b82f6',
            'no_show'   => '#6b7280',
        ];

        $events = array_map(function ($a) use ($statusColors) {
            return [
                'id'              => $a['id'],
                'title'           => $a['client_name'] . ' — ' . $a['service_name'],
                'start'           => $a['date'] . 'T' . $a['start_time'],
                'end'             => $a['date'] . 'T' . $a['end_time'],
                'backgroundColor' => $statusColors[$a['status']] ?? '#C9A84C',
                'extendedProps'   => [
                    'employee'    => $a['employee_name'],
                    'status'      => $a['status'],
                    'phone'       => $a['client_phone'],
                    'price'       => $a['price'],
                ],
            ];
        }, $appts);

        $this->json($events);
    }
}
