<?php
// ============================================================
//  app/Controllers/StatsController.php
// ============================================================
namespace App\Controllers;

use App\Models\Shop;

class StatsController extends BaseController
{
    private Shop $shopModel;

    public function __construct()
    {
        $this->shopModel = new Shop();
    }

    private function getOwnerShop(): array
    {
        $user = $this->auth();
        $shop = $this->shopModel->findBy('owner_id', $user['id']);
        if (!$shop && $user['role'] === 'superadmin') {
            $shop = $this->shopModel->find((int)($_SESSION['admin_shop_id'] ?? 0));
        }
        if (!$shop) { $this->redirect('/panel'); }
        return $shop;
    }

    public function index(): void
    {
        $shop  = $this->getOwnerShop();
        $db    = \App\Models\Database::getInstance();
        $year  = (int) $this->input('year', date('Y'));

        // Ingresos y turnos por mes
        $monthly = $db->prepare("SELECT
            MONTH(date) AS month,
            COUNT(*) AS total_appts,
            SUM(CASE WHEN status='completed' THEN 1 ELSE 0 END) AS completed,
            SUM(CASE WHEN status='completed' THEN price ELSE 0 END) AS revenue,
            SUM(CASE WHEN status IN ('cancelled_client','cancelled_shop') THEN 1 ELSE 0 END) AS cancelled
            FROM appointments
            WHERE shop_id = ? AND YEAR(date) = ?
            GROUP BY MONTH(date) ORDER BY month");
        $monthly->execute([$shop['id'], $year]);
        $monthlyData = $monthly->fetchAll();

        // Servicios más solicitados
        $topServices = $db->prepare("SELECT s.name, COUNT(*) AS cnt, SUM(a.price) AS revenue
            FROM appointments a JOIN services s ON s.id = a.service_id
            WHERE a.shop_id = ? AND a.status = 'completed' AND YEAR(a.date) = ?
            GROUP BY s.id ORDER BY cnt DESC LIMIT 8");
        $topServices->execute([$shop['id'], $year]);

        // Empleados con más turnos
        $topEmployees = $db->prepare("SELECT e.name, COUNT(*) AS cnt, SUM(a.price) AS revenue
            FROM appointments a JOIN employees e ON e.id = a.employee_id
            WHERE a.shop_id = ? AND a.status = 'completed' AND YEAR(a.date) = ?
            GROUP BY e.id ORDER BY cnt DESC");
        $topEmployees->execute([$shop['id'], $year]);

        // Totales del año
        $totals = $db->prepare("SELECT
            COUNT(*) AS total,
            SUM(CASE WHEN status='completed' THEN 1 ELSE 0 END) AS completed,
            SUM(CASE WHEN status='completed' THEN price ELSE 0 END) AS revenue,
            SUM(CASE WHEN status IN ('cancelled_client','cancelled_shop') THEN 1 ELSE 0 END) AS cancelled
            FROM appointments WHERE shop_id = ? AND YEAR(date) = ?");
        $totals->execute([$shop['id'], $year]);

        // Construir array de 12 meses (con ceros para los vacíos)
        $byMonth = array_fill(1, 12, ['month'=>0,'total_appts'=>0,'completed'=>0,'revenue'=>0,'cancelled'=>0]);
        foreach ($monthlyData as $row) {
            $byMonth[(int)$row['month']] = $row;
        }

        $this->view('shop_dash/stats', [
            'pageTitle'    => 'Estadísticas — ' . $shop['name'],
            'shop'         => $shop,
            'year'         => $year,
            'byMonth'      => array_values($byMonth),
            'topServices'  => $topServices->fetchAll(),
            'topEmployees' => $topEmployees->fetchAll(),
            'totals'       => $totals->fetch(),
        ]);
    }
}
