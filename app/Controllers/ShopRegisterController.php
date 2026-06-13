<?php
// ============================================================
//  app/Controllers/ShopRegisterController.php
// ============================================================
namespace App\Controllers;

use App\Models\Shop;

class ShopRegisterController extends BaseController
{
    private Shop $shopModel;

    public function __construct()
    {
        $this->shopModel = new Shop();
    }

    public function form(): void
    {
        $user = $this->auth();

        // Si ya tiene local, redirigir al panel
        $existing = $this->shopModel->findBy('owner_id', $user['id']);
        if ($existing) {
            $this->flash('info', 'Ya tenés un local registrado.');
            $this->redirect('/panel');
        }

        $this->view('shop_register/form', [
            'pageTitle' => 'Registrar mi local',
        ]);
    }

    public function store(): void
    {
        $user = $this->auth();

        if (!verifyCsrf($this->input('_csrf', ''))) {
            $this->flash('error', 'Token inválido.');
            $this->redirect('/registrar-local');
        }

        $errors = $this->validate($_POST, [
            'name'    => 'required|min:2|max:120',
            'type'    => 'required|in:barbershop,salon,mixed',
            'city'    => 'required|max:100',
            'address' => 'required|max:255',
            'phone'   => 'required|max:30',
        ]);

        if ($errors) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_old']    = $_POST;
            $this->redirect('/registrar-local');
        }

        $name = sanitize($this->input('name'));
        $slug = $this->shopModel->generateSlug($name);

        $data = [
            'owner_id'    => $user['id'],
            'name'        => $name,
            'slug'        => $slug,
            'type'        => $this->input('type'),
            'description' => sanitize($this->input('description', '')),
            'phone'       => sanitize($this->input('phone')),
            'email'       => strtolower(trim($this->input('contact_email', $user['email']))),
            'address'     => sanitize($this->input('address')),
            'city'        => sanitize($this->input('city')),
            'province'    => sanitize($this->input('province', '')),
            'country'     => 'Argentina',
            'status'      => 'pending',  // Admin debe aprobar
            'plan'        => 'free',
        ];

        // Logo upload
        if (!empty($_FILES['logo']['name'])) {
            $logo = uploadImage($_FILES['logo'], 'shops');
            if ($logo) $data['logo'] = $logo;
        }

        $shopId = $this->shopModel->insert($data);

        // Cambiar rol del usuario a shop_owner
        $userModel = new \App\Models\User();
        $userModel->update($user['id'], ['role' => 'shop_owner']);
        $_SESSION['user']['role'] = 'shop_owner';

        // Horarios por defecto (Lun-Sáb 9-18)
        $db = \App\Models\Database::getInstance();
        for ($d = 1; $d <= 6; $d++) {
            $db->prepare("INSERT INTO shop_hours (shop_id, day_of_week, opens_at, closes_at) VALUES (?,?,?,?)")
               ->execute([$shopId, $d, '09:00:00', '18:00:00']);
        }
        // Domingo cerrado
        $db->prepare("INSERT INTO shop_hours (shop_id, day_of_week, opens_at, closes_at) VALUES (?,?,NULL,NULL)")
           ->execute([$shopId, 0]);

        try {
            $mailer = new \App\Helpers\Mailer();
            $mailer->sendShopRegistered($data, $user);
        } catch (\Exception $e) {}

        $this->flash('success', 'Tu local fue registrado. Está pendiente de aprobación por el administrador. Te avisaremos por email.');
        $this->redirect('/panel');
    }
}
