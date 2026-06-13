<?php
// ============================================================
//  app/Controllers/ServiceController.php
// ============================================================
namespace App\Controllers;

use App\Models\Shop;
use App\Models\Service;

class ServiceController extends BaseController
{
    private Shop    $shopModel;
    private Service $serviceModel;

    public function __construct()
    {
        $this->shopModel    = new Shop();
        $this->serviceModel = new Service();
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

    private function getCategories(int $shopId): array
    {
        $db = \App\Models\Database::getInstance();
        $st = $db->prepare("SELECT * FROM service_categories WHERE shop_id = ? ORDER BY sort_order, name");
        $st->execute([$shopId]);
        return $st->fetchAll();
    }

    public function index(): void
    {
        $shop     = $this->getOwnerShop();
        $grouped  = $this->serviceModel->getGroupedByCategory($shop['id']);
        $categories = $this->getCategories($shop['id']);

        $this->view('shop_dash/services/index', [
            'pageTitle'  => 'Servicios — ' . $shop['name'],
            'shop'       => $shop,
            'grouped'    => $grouped,
            'categories' => $categories,
        ]);
    }

    public function create(): void
    {
        $shop       = $this->getOwnerShop();
        $categories = $this->getCategories($shop['id']);

        $this->view('shop_dash/services/form', [
            'pageTitle'  => 'Nuevo servicio',
            'shop'       => $shop,
            'service'    => [],
            'categories' => $categories,
        ]);
    }

    public function store(): void
    {
        $shop = $this->getOwnerShop();

        if (!verifyCsrf($this->input('_csrf', ''))) {
            $this->flash('error', 'Token inválido.');
            $this->redirect('/panel/servicios/nuevo');
        }

        $errors = $this->validate($_POST, [
            'name'         => 'required|min:2|max:120',
            'duration_min' => 'required|numeric',
            'price'        => 'required|numeric',
        ]);

        if ($errors) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_old']    = $_POST;
            $this->redirect('/panel/servicios/nuevo');
        }

        // Crear categoría nueva si se ingresó
        $categoryId = (int) $this->input('category_id');
        $newCat     = sanitize($this->input('new_category', ''));
        if (!$categoryId && $newCat) {
            $db = \App\Models\Database::getInstance();
            $st = $db->prepare("INSERT INTO service_categories (shop_id, name, sort_order) VALUES (?,?,?)");
            $st->execute([$shop['id'], $newCat, 0]);
            $categoryId = (int) $db->lastInsertId();
        }

        $data = [
            'shop_id'      => $shop['id'],
            'category_id'  => $categoryId ?: null,
            'name'         => sanitize($this->input('name')),
            'description'  => sanitize($this->input('description', '')),
            'duration_min' => (int) $this->input('duration_min'),
            'price'        => (float) $this->input('price'),
            'deposit_pct'  => (int) $this->input('deposit_pct', 0),
            'is_active'    => 1,
            'sort_order'   => (int) $this->input('sort_order', 0),
        ];

        if (!empty($_FILES['image']['name'])) {
            $img = uploadImage($_FILES['image'], 'services');
            if ($img) $data['image'] = $img;
        }

        $this->serviceModel->insert($data);
        $this->flash('success', 'Servicio creado correctamente.');
        $this->redirect('/panel/servicios');
    }

    public function edit(int $id): void
    {
        $shop    = $this->getOwnerShop();
        $service = $this->serviceModel->find($id);

        if (!$service || $service['shop_id'] !== $shop['id']) {
            $this->flash('error', 'Servicio no encontrado.');
            $this->redirect('/panel/servicios');
        }

        $categories = $this->getCategories($shop['id']);

        $this->view('shop_dash/services/form', [
            'pageTitle'  => 'Editar — ' . $service['name'],
            'shop'       => $shop,
            'service'    => $service,
            'categories' => $categories,
        ]);
    }

    public function update(int $id): void
    {
        $shop    = $this->getOwnerShop();
        $service = $this->serviceModel->find($id);

        if (!$service || $service['shop_id'] !== $shop['id']) {
            $this->flash('error', 'Servicio no encontrado.');
            $this->redirect('/panel/servicios');
        }

        if (!verifyCsrf($this->input('_csrf', ''))) {
            $this->flash('error', 'Token inválido.');
            $this->redirect("/panel/servicios/$id");
        }

        $errors = $this->validate($_POST, [
            'name'         => 'required|min:2|max:120',
            'duration_min' => 'required|numeric',
            'price'        => 'required|numeric',
        ]);

        if ($errors) {
            $_SESSION['form_errors'] = $errors;
            $this->redirect("/panel/servicios/$id");
        }

        $categoryId = (int) $this->input('category_id');
        $newCat     = sanitize($this->input('new_category', ''));
        if (!$categoryId && $newCat) {
            $db = \App\Models\Database::getInstance();
            $st = $db->prepare("INSERT INTO service_categories (shop_id, name, sort_order) VALUES (?,?,?)");
            $st->execute([$shop['id'], $newCat, 0]);
            $categoryId = (int) $db->lastInsertId();
        }

        $data = [
            'category_id'  => $categoryId ?: null,
            'name'         => sanitize($this->input('name')),
            'description'  => sanitize($this->input('description', '')),
            'duration_min' => (int) $this->input('duration_min'),
            'price'        => (float) $this->input('price'),
            'deposit_pct'  => (int) $this->input('deposit_pct', 0),
            'is_active'    => (int) $this->input('is_active', 1),
            'sort_order'   => (int) $this->input('sort_order', 0),
        ];

        if (!empty($_FILES['image']['name'])) {
            $img = uploadImage($_FILES['image'], 'services');
            if ($img) $data['image'] = $img;
        }

        $this->serviceModel->update($id, $data);
        $this->flash('success', 'Servicio actualizado.');
        $this->redirect('/panel/servicios');
    }

    public function delete(int $id): void
    {
        $shop    = $this->getOwnerShop();
        $service = $this->serviceModel->find($id);

        if (!$service || $service['shop_id'] !== $shop['id']) {
            $this->flash('error', 'Servicio no encontrado.');
            $this->redirect('/panel/servicios');
        }

        $this->serviceModel->update($id, ['is_active' => 0]);
        $this->flash('success', 'Servicio desactivado.');
        $this->redirect('/panel/servicios');
    }
}
