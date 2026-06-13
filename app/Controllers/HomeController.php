<?php
namespace App\Controllers;
use App\Models\Shop;

class HomeController extends BaseController
{
    private Shop $shopModel;
    public function __construct() { $this->shopModel = new Shop(); }

    public function index(): void {
        $featured = $this->shopModel->raw("SELECT s.*, (SELECT filename FROM shop_photos p WHERE p.shop_id=s.id ORDER BY sort_order LIMIT 1) AS first_photo FROM shops s WHERE s.status='active' AND s.is_shadowbanned=0 ORDER BY s.featured DESC, s.rating_avg DESC, s.rating_count DESC LIMIT 6");
        $cities   = $this->shopModel->raw("SELECT city, COUNT(*) AS total FROM shops WHERE status='active' AND is_shadowbanned=0 AND city IS NOT NULL GROUP BY city ORDER BY total DESC LIMIT 8");
        $apptRow  = $this->shopModel->rawOne("SELECT COUNT(*) AS total FROM appointments WHERE status IN ('confirmed','completed')");
        $stats    = ['shops'=>$this->shopModel->count("status='active' AND is_shadowbanned=0"),'cities'=>count($cities),'appointments'=>$apptRow?(int)$apptRow['total']:0];
        $this->view('home/index', compact('featured','cities','stats') + ['pageTitle'=>'Trimly — Encontrá tu turno']);
    }

    public function search(): void {
        $query    = trim($this->input('q',''));
        $city     = trim($this->input('ciudad',''));
        $type     = trim($this->input('tipo',''));
        $audience = trim($this->input('audiencia',''));
        $page     = max(1,(int)$this->input('pagina',1));
        $validTypes = ['barbershop','salon','mixed','nails','spa','tattoo','makeup','other',''];
        if(!in_array($type,$validTypes))$type='';
        if(!in_array($audience,['men','women','unisex','']))$audience='';
        $results = $this->shopModel->search($query,$city,$type,$page,9,['audience'=>$audience]);
        $cities  = $this->shopModel->raw("SELECT city,COUNT(*) AS total FROM shops WHERE status='active' AND is_shadowbanned=0 AND city IS NOT NULL GROUP BY city ORDER BY total DESC LIMIT 20");
        $this->view('home/search', array_merge($results, ['pageTitle'=>'Buscar locales — Trimly','query'=>$query,'city'=>$city,'type'=>$type,'audience'=>$audience,'cities'=>$cities]));
    }
}
