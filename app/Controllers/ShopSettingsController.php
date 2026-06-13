<?php
namespace App\Controllers;
use App\Models\Shop;

class ShopSettingsController extends BaseController
{
    private Shop $shopModel;
    public function __construct() { $this->shopModel = new Shop(); }

    private function getOwnerShop(): array {
        $user = $this->auth();
        $shop = $this->shopModel->findBy('owner_id', $user['id']);
        if (!$shop) { $this->flash('info','Primero registrá tu local.'); $this->redirect('/registrar-local'); }
        return $shop;
    }

    public function edit(): void {
        $shop = $this->getOwnerShop();
        $db   = \App\Models\Database::getInstance();
        $hours  = $db->prepare('SELECT * FROM shop_hours  WHERE shop_id=? ORDER BY day_of_week'); $hours->execute([$shop['id']]);
        $photos = $db->prepare('SELECT * FROM shop_photos WHERE shop_id=? ORDER BY sort_order');  $photos->execute([$shop['id']]);
        $this->view('shop_dash/settings', ['pageTitle'=>'Configuración — '.$shop['name'],'shop'=>$shop,'hours'=>$hours->fetchAll(),'photos'=>$photos->fetchAll()]);
    }

    public function update(): void {
        $shop = $this->getOwnerShop();
        if (!verifyCsrf($this->input('_csrf',''))) { $this->flash('error','Token inválido.'); $this->redirect('/panel/local'); }

        $validTypes = ['barbershop','salon','mixed','nails','spa','tattoo','makeup','other'];
        $errors = $this->validate($_POST, [
            'name'    => 'required|min:2|max:120',
            'city'    => 'required|max:100',
            'phone'   => 'max:30',
            'email'   => 'email',
        ]);
        if ($errors) { $_SESSION['form_errors']=$errors; $this->redirect('/panel/local'); }

        $type = in_array($this->input('type'), $validTypes) ? $this->input('type') : 'barbershop';
        $audience = in_array($this->input('target_audience'),['men','women','unisex']) ? $this->input('target_audience') : 'unisex';

        $data = [
            'name'            => sanitize($this->input('name')),
            'type'            => $type,
            'target_audience' => $audience,
            'description'     => sanitize($this->input('description','')),
            'specialties'     => sanitize($this->input('specialties','')),
            'amenities'       => sanitize($this->input('amenities','')),
            'phone'           => sanitize($this->input('phone','')),
            'email'           => strtolower(trim($this->input('email',''))),
            'website'         => trim($this->input('website','')),
            'instagram'       => ltrim(trim($this->input('instagram','')), '@'),
            'facebook'        => trim($this->input('facebook','')),
            'whatsapp'        => sanitize($this->input('whatsapp','')),
            'address'         => sanitize($this->input('address','')),
            'city'            => sanitize($this->input('city','')),
            'province'        => sanitize($this->input('province','')),
            'postal_code'     => sanitize($this->input('postal_code','')),
            'latitude'        => $this->input('latitude','') !== '' ? (float)$this->input('latitude') : null,
            'longitude'       => $this->input('longitude','') !== '' ? (float)$this->input('longitude') : null,
        ];

        if (!empty($_FILES['logo']['name'])) {
            $p = uploadImage($_FILES['logo'], 'shops');
            if ($p) $data['logo'] = $p;
        }
        if (!empty($_FILES['cover_image']['name'])) {
            $p = uploadImage($_FILES['cover_image'], 'shops');
            if ($p) $data['cover_image'] = $p;
        }

        $this->shopModel->update($shop['id'], $data);
        $this->flash('success','Información actualizada.');
        $this->redirect('/panel/local');
    }

    public function saveHours(): void {
        $shop = $this->getOwnerShop();
        $db   = \App\Models\Database::getInstance();
        $days   = $_POST['days']        ?? [];
        $open   = $_POST['opens_at']    ?? [];
        $close  = $_POST['closes_at']   ?? [];
        $bStart = $_POST['break_start'] ?? [];
        $bEnd   = $_POST['break_end']   ?? [];
        $db->prepare('DELETE FROM shop_hours WHERE shop_id=?')->execute([$shop['id']]);
        for ($d=0; $d<=6; $d++) {
            $db->prepare('INSERT INTO shop_hours (shop_id,day_of_week,opens_at,closes_at,break_start,break_end) VALUES (?,?,?,?,?,?)')
               ->execute([$shop['id'],$d, isset($days[$d])?($open[$d]??null):null, isset($days[$d])?($close[$d]??null):null, $bStart[$d]??null, $bEnd[$d]??null]);
        }
        $this->flash('success','Horarios guardados.');
        $this->redirect('/panel/local');
    }

    public function uploadPhoto(): void {
        $shop = $this->getOwnerShop();
        $db   = \App\Models\Database::getInstance();
        if (empty($_FILES['photo']['name'])) { $this->jsonError('No se recibió ningún archivo',400); return; }
        $path = uploadImage($_FILES['photo'], 'shops');
        if (!$path) { $this->jsonError('Archivo no válido (JPG, PNG, WebP — máx. 5 MB)',400); return; }
        $orderSt = $db->prepare('SELECT IFNULL(MAX(sort_order),0)+1 FROM shop_photos WHERE shop_id=?');
        $orderSt->execute([$shop['id']]);
        $order = (int)$orderSt->fetchColumn();
        $stmt = $db->prepare('INSERT INTO shop_photos (shop_id,filename,caption,sort_order) VALUES (?,?,?,?)');
        $stmt->execute([$shop['id'],$path,sanitize($this->input('caption','')),$order]);
        $this->json(['success'=>true,'photo'=>['id'=>(int)$db->lastInsertId(),'filename'=>$path,'sort_order'=>$order],'url'=>upload_url($path)]);
    }

    public function deletePhoto(int $id): void {
        $shop = $this->getOwnerShop();
        $db   = \App\Models\Database::getInstance();
        $photo = $db->prepare('SELECT * FROM shop_photos WHERE id=? AND shop_id=?');
        $photo->execute([$id,$shop['id']]);
        $row = $photo->fetch();
        if (!$row) { $this->jsonError('Foto no encontrada',404); return; }
        $file = PUB_PATH . '/uploads/' . $row['filename'];
        if (file_exists($file)) @unlink($file);
        $db->prepare('DELETE FROM shop_photos WHERE id=?')->execute([$id]);
        $this->json(['success'=>true]);
    }
}
