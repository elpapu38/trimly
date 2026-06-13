<?php
// ============================================================
//  app/Controllers/EmployeeController.php
// ============================================================
namespace App\Controllers;

use App\Models\Shop;
use App\Models\Employee;
use App\Models\Service;

class EmployeeController extends BaseController
{
    private Shop     $shopModel;
    private Employee $employeeModel;
    private Service  $serviceModel;

    public function __construct()
    {
        $this->shopModel     = new Shop();
        $this->employeeModel = new Employee();
        $this->serviceModel  = new Service();
    }

    private function getOwnerShop(): array
    {
        $user = $this->auth();
        $shop = $this->shopModel->findBy('owner_id', $user['id']);
        if (!$shop && $user['role'] === 'superadmin') {
            $shop = $this->shopModel->find((int)($_SESSION['admin_shop_id'] ?? 0));
        }
        if (!$shop) { $this->flash('info', 'Registrá tu local primero.'); $this->redirect('/registrar-local'); }
        return $shop;
    }

    public function index(): void
    {
        $shop      = $this->getOwnerShop();
        $employees = $this->employeeModel->getByShop($shop['id'], false);

        $this->view('shop_dash/employees/index', [
            'pageTitle' => 'Empleados — ' . $shop['name'],
            'shop'      => $shop,
            'employees' => $employees,
        ]);
    }

    public function create(): void
    {
        $shop     = $this->getOwnerShop();
        $services = $this->serviceModel->getByShop($shop['id'], false);

        $this->view('shop_dash/employees/form', [
            'pageTitle' => 'Nuevo empleado',
            'shop'      => $shop,
            'employee'  => [],
            'services'  => $services,
            'assigned'  => [],
        ]);
    }

    public function store(): void
    {
        $shop = $this->getOwnerShop();

        if (!verifyCsrf($this->input('_csrf', ''))) {
            $this->flash('error', 'Token inválido.');
            $this->redirect('/panel/empleados/nuevo');
        }

        $errors = $this->validate($_POST, [
            'name'      => 'required|min:2|max:100',
            'specialty' => 'max:150',
            'instagram' => 'max:100',
        ]);

        if ($errors) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_old']    = $_POST;
            $this->redirect('/panel/empleados/nuevo');
        }

        $data = [
            'shop_id'    => $shop['id'],
            'name'       => sanitize($this->input('name')),
            'bio'        => sanitize($this->input('bio', '')),
            'specialty'  => sanitize($this->input('specialty', '')),
            'instagram'  => sanitize($this->input('instagram', '')),
            'status'     => 'active',
            'sort_order' => (int) $this->input('sort_order', 0),
        ];

        if (!empty($_FILES['avatar']['name'])) {
            $avatar = uploadImage($_FILES['avatar'], 'employees');
            if ($avatar) $data['avatar'] = $avatar;
        }

        $empId = $this->employeeModel->insert($data);

        // Asignar servicios
        $serviceIds = $_POST['service_ids'] ?? [];
        foreach ($serviceIds as $sid) {
            $this->employeeModel->assignService($empId, (int) $sid);
        }

        // Horarios
        $this->saveEmployeeHours($empId);

        $this->flash('success', 'Empleado creado correctamente.');
        $this->redirect('/panel/empleados');
    }

    public function edit(int $id): void
    {
        $shop     = $this->getOwnerShop();
        $employee = $this->employeeModel->find($id);

        if (!$employee || $employee['shop_id'] !== $shop['id']) {
            $this->flash('error', 'Empleado no encontrado.');
            $this->redirect('/panel/empleados');
        }

        $services = $this->serviceModel->getByShop($shop['id'], false);
        $assigned = $this->employeeModel->raw(
            "SELECT service_id FROM employee_services WHERE employee_id = ?", [$id]
        );
        $assignedIds = array_column($assigned, 'service_id');

        $hours = $this->employeeModel->getHours($id);

        $this->view('shop_dash/employees/form', [
            'pageTitle' => 'Editar — ' . $employee['name'],
            'shop'      => $shop,
            'employee'  => $employee,
            'services'  => $services,
            'assigned'  => $assignedIds,
            'hours'     => $hours,
        ]);
    }

    public function update(int $id): void
    {
        $shop     = $this->getOwnerShop();
        $employee = $this->employeeModel->find($id);

        if (!$employee || $employee['shop_id'] !== $shop['id']) {
            $this->flash('error', 'Empleado no encontrado.');
            $this->redirect('/panel/empleados');
        }

        if (!verifyCsrf($this->input('_csrf', ''))) {
            $this->flash('error', 'Token inválido.');
            $this->redirect("/panel/empleados/$id");
        }

        $errors = $this->validate($_POST, [
            'name' => 'required|min:2|max:100',
        ]);

        if ($errors) {
            $_SESSION['form_errors'] = $errors;
            $this->redirect("/panel/empleados/$id");
        }

        $data = [
            'name'       => sanitize($this->input('name')),
            'bio'        => sanitize($this->input('bio', '')),
            'specialty'  => sanitize($this->input('specialty', '')),
            'instagram'  => sanitize($this->input('instagram', '')),
            'status'     => $this->input('status', 'active'),
            'sort_order' => (int) $this->input('sort_order', 0),
        ];

        if (!empty($_FILES['avatar']['name'])) {
            $avatar = uploadImage($_FILES['avatar'], 'employees');
            if ($avatar) $data['avatar'] = $avatar;
        }

        $this->employeeModel->update($id, $data);

        // Actualizar servicios
        $db = \App\Models\Database::getInstance();
        $db->prepare("DELETE FROM employee_services WHERE employee_id = ?")->execute([$id]);
        $serviceIds = $_POST['service_ids'] ?? [];
        foreach ($serviceIds as $sid) {
            $this->employeeModel->assignService($id, (int) $sid);
        }

        // Horarios
        $this->saveEmployeeHours($id);

        $this->flash('success', 'Empleado actualizado.');
        $this->redirect('/panel/empleados');
    }

    public function delete(int $id): void
    {
        $shop     = $this->getOwnerShop();
        $employee = $this->employeeModel->find($id);

        if (!$employee || $employee['shop_id'] !== $shop['id']) {
            $this->flash('error', 'Empleado no encontrado.');
            $this->redirect('/panel/empleados');
        }

        // Soft delete
        $this->employeeModel->update($id, ['status' => 'inactive']);
        $this->flash('success', 'Empleado desactivado.');
        $this->redirect('/panel/empleados');
    }

    // ── Vincular/desvincular cuenta de usuario ────────────────
    public function linkAccount(int $id): void
    {
        $shop     = $this->getOwnerShop();
        $employee = $this->employeeModel->find($id);

        if (!$employee || $employee['shop_id'] !== $shop['id']) {
            $this->jsonError('Empleado no encontrado.', 404);
        }

        $email = strtolower(trim($this->input('email', '')));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->jsonError('Email no válido.');
        }

        $db   = \App\Models\Database::getInstance();
        $stmt = $db->prepare("SELECT id, name, role FROM users WHERE email = ? AND status = 'active' LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            $this->jsonError('No existe un usuario activo con ese email.');
        }

        // Only allow 'client' or 'employee' role users to be linked
        if (!in_array($user['role'], ['client', 'employee'])) {
            $this->jsonError('Ese usuario no puede ser vinculado como empleado.');
        }

        // Check not already linked to another employee
        $check = $db->prepare("SELECT id FROM employees WHERE user_id = ? AND id != ?");
        $check->execute([$user['id'], $id]);
        if ($check->fetch()) {
            $this->jsonError('Ese usuario ya está vinculado a otro empleado.');
        }

        // Link user and promote role to employee
        $this->employeeModel->update($id, ['user_id' => $user['id']]);
        $db->prepare("UPDATE users SET role = 'employee' WHERE id = ?")->execute([$user['id']]);

        $this->json(['success' => true, 'user_name' => $user['name'], 'user_id' => $user['id']]);
    }

    public function unlinkAccount(int $id): void
    {
        $shop     = $this->getOwnerShop();
        $employee = $this->employeeModel->find($id);

        if (!$employee || $employee['shop_id'] !== $shop['id']) {
            $this->jsonError('Empleado no encontrado.', 404);
        }

        if (!$employee['user_id']) {
            $this->jsonError('Este empleado no tiene cuenta vinculada.');
        }

        $db = \App\Models\Database::getInstance();
        // Demote role back to client
        $db->prepare("UPDATE users SET role = 'client' WHERE id = ?")->execute([$employee['user_id']]);
        $this->employeeModel->update($id, ['user_id' => null]);

        $this->json(['success' => true]);
    }

    private function saveEmployeeHours(int $empId): void
    {
        $db    = \App\Models\Database::getInstance();
        $days  = $_POST['emp_days']   ?? [];
        $open  = $_POST['emp_opens']  ?? [];
        $close = $_POST['emp_closes'] ?? [];

        $db->prepare("DELETE FROM employee_hours WHERE employee_id = ?")->execute([$empId]);

        for ($d = 0; $d <= 6; $d++) {
            if (isset($days[$d]) && !empty($open[$d]) && !empty($close[$d])) {
                $db->prepare("INSERT INTO employee_hours (employee_id, day_of_week, opens_at, closes_at) VALUES (?,?,?,?)")
                   ->execute([$empId, $d, $open[$d], $close[$d]]);
            }
        }
    }
}
