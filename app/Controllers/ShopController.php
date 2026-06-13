<?php
namespace App\Controllers;
use App\Models\{Shop, Employee, Service, Review};

class ShopController extends BaseController
{
    private Shop $shopModel;
    private Employee $employeeModel;
    private Service $serviceModel;
    private Review $reviewModel;

    public function __construct() {
        $this->shopModel     = new Shop();
        $this->employeeModel = new Employee();
        $this->serviceModel  = new Service();
        $this->reviewModel   = new Review();
    }

    public function show(string $slug): void {
        $shop = $this->shopModel->findBySlug($slug);
        if (!$shop || $shop['status'] !== 'active' || $shop['is_shadowbanned']) {
            http_response_code(404); $this->view('errors/404'); return;
        }

        // Track view (throttle per session)
        $viewKey = 'view_' . $shop['id'];
        if (empty($_SESSION[$viewKey])) {
            $this->shopModel->incrementViews($shop['id']);
            $_SESSION[$viewKey] = time();
        }

        $photos = $this->shopModel->raw('SELECT * FROM shop_photos WHERE shop_id=? ORDER BY sort_order',[$shop['id']]);
        $rawHours = $this->shopModel->raw('SELECT * FROM shop_hours WHERE shop_id=? ORDER BY day_of_week',[$shop['id']]);
        $hoursByDay = [];
        foreach($rawHours as $h) $hoursByDay[(int)$h['day_of_week']] = $h;

        $servicesGrouped = $this->serviceModel->getGroupedByCategory($shop['id']);
        $totalServices   = array_sum(array_map('count',$servicesGrouped));
        $employees       = $this->employeeModel->getByShop($shop['id'],true);

        // Load portfolio photos for each employee
        $db = \App\Models\Database::getInstance();
        foreach ($employees as &$emp) {
            $pstmt = $db->prepare("SELECT * FROM employee_photos WHERE employee_id = ? ORDER BY created_at DESC LIMIT 6");
            $pstmt->execute([$emp['id']]);
            $emp['portfolio'] = $pstmt->fetchAll();
        }
        unset($emp);
        $reviewSort      = in_array($this->input('resenas','newest'),['newest','highest','lowest']) ? $this->input('resenas','newest') : 'newest';
        $reviews         = $this->reviewModel->getByShop($shop['id'],1,6,$reviewSort);
        $ratingBreakdown = $this->reviewModel->getRatingBreakdown($shop['id']);
        $minPrice        = $this->shopModel->rawOne('SELECT MIN(price) AS min_p FROM services WHERE shop_id=? AND is_active=1',[$shop['id']]);

        $canReview = false; $isFavorite = false;
        $user = $this->auth();
        if ($user) {
            $pending = $this->shopModel->rawOne("SELECT a.id FROM appointments a LEFT JOIN reviews r ON r.appointment_id=a.id WHERE a.shop_id=? AND a.client_email=? AND a.status='completed' AND r.id IS NULL LIMIT 1",[$shop['id'],$user['email']]);
            $canReview = (bool)$pending;
            $fav = $this->shopModel->rawOne("SELECT 1 FROM favorites WHERE user_id=? AND shop_id=?",[$user['id'],$shop['id']]);
            $isFavorite = (bool)$fav;
        }

        $todayDow   = (int)date('w');
        $todayHours = $hoursByDay[$todayDow] ?? null;
        $isOpenNow  = false;
        if($todayHours && $todayHours['opens_at'] && $todayHours['closes_at']){
            $now = date('H:i'); $isOpenNow = $now>=$todayHours['opens_at'] && $now<$todayHours['closes_at'];
        }

        $this->view('shop/show', compact('shop','photos','hoursByDay','servicesGrouped','totalServices','employees','reviews','ratingBreakdown','minPrice','canReview','isOpenNow','todayDow','isFavorite','user','reviewSort'));
    }

    public function reviews(string $slug): void {
        $shop = $this->shopModel->findBySlug($slug);
        if (!$shop) { $this->jsonError('Local no encontrado',404); return; }
        $page = max(1,(int)($this->input('pagina',2)));
        $sort = in_array($this->input('orden','newest'),['newest','highest','lowest']) ? $this->input('orden','newest') : 'newest';
        $this->json($this->reviewModel->getByShop($shop['id'],$page,6,$sort));
    }

    public function services(string $slug): void {
        $shop = $this->shopModel->findBySlug($slug);
        if (!$shop) { $this->jsonError('Local no encontrado',404); return; }
        $this->json($this->serviceModel->getGroupedByCategory($shop['id']));
    }

    public function toggleFavorite(string $slug): void {
        $shop = $this->shopModel->findBySlug($slug);
        if (!$shop) { $this->jsonError('Local no encontrado',404); return; }
        $user = $this->auth();
        $db   = \App\Models\Database::getInstance();
        $fav  = $this->shopModel->rawOne("SELECT 1 FROM favorites WHERE user_id=? AND shop_id=?",[$user['id'],$shop['id']]);
        if ($fav) {
            $db->prepare("DELETE FROM favorites WHERE user_id=? AND shop_id=?")->execute([$user['id'],$shop['id']]);
            $this->json(['success'=>true,'favorited'=>false]);
        } else {
            $db->prepare("INSERT IGNORE INTO favorites (user_id,shop_id) VALUES (?,?)")->execute([$user['id'],$shop['id']]);
            $this->json(['success'=>true,'favorited'=>true]);
        }
    }
}
