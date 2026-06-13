<?php
namespace App\Models;

class User extends BaseModel {
    protected string $table = 'users';
    public function findByEmail(string $email): array|false { return $this->findBy('email', $email); }
    public function verifyPassword(string $plain, string $hash): bool { return password_verify($plain, $hash); }
    public function hashPassword(string $plain): string { return password_hash($plain, PASSWORD_BCRYPT, ['cost'=>12]); }
    public function generateToken(): string { return bin2hex(random_bytes(32)); }
}

class Shop extends BaseModel {
    protected string $table = 'shops';
    public function findBySlug(string $slug): array|false { return $this->findBy('slug', $slug); }

    public static function typeLabel(?string $type): string {
        return match($type ?? '') {
            'barbershop'=>'Barbería','salon'=>'Salón de Belleza','mixed'=>'Mixto / Unisex',
            'nails'=>'Manicura & Uñas','spa'=>'Spa & Relajación','tattoo'=>'Tatuajes & Piercings',
            'makeup'=>'Maquillaje & Estética',default=>'Otro'
        };
    }
    public static function audienceLabel(?string $aud): string {
        return match($aud ?? '') { 'men'=>'Caballeros','women'=>'Damas',default=>'Unisex' };
    }
    public static function typeEmoji(?string $type): string {
        return match($type ?? '') { 'barbershop'=>'💈','salon'=>'💅','nails'=>'💅','spa'=>'🧖','tattoo'=>'🎨','makeup'=>'💄',default=>'✂️' };
    }

    public function search(string $query, string $city, string $type, int $page, int $perPage, array $extra=[]): array {
        $where  = ['s.status="active"','s.is_shadowbanned=0'];
        $params = [];
        if ($query) { $where[]='(s.name LIKE ? OR s.description LIKE ? OR s.city LIKE ?)'; $params=array_merge($params,["%$query%","%$query%","%$query%"]); }
        if ($city)  { $where[]='s.city=?';              $params[]=$city; }
        if ($type)  { $where[]='s.type=?';              $params[]=$type; }
        if (!empty($extra['audience'])) { $where[]='s.target_audience=?'; $params[]=$extra['audience']; }
        $whereStr = implode(' AND ',$where);
        $offset   = ($page-1)*$perPage;
        $countSt  = $this->db->prepare("SELECT COUNT(*) FROM shops s WHERE {$whereStr}");
        $countSt->execute($params);
        $total = (int)$countSt->fetchColumn();
        $st = $this->db->prepare("SELECT s.*, (SELECT filename FROM shop_photos p WHERE p.shop_id=s.id ORDER BY sort_order LIMIT 1) AS first_photo FROM shops s WHERE {$whereStr} ORDER BY s.featured DESC,s.rating_avg DESC,s.rating_count DESC LIMIT {$perPage} OFFSET {$offset}");
        $st->execute($params);
        return ['data'=>$st->fetchAll(),'total'=>$total,'per_page'=>$perPage,'current_page'=>$page,'last_page'=>max(1,(int)ceil($total/$perPage))];
    }

    public function generateSlug(string $name): string {
        $slug = slugify($name); $base=$slug; $i=1;
        while($this->findBySlug($slug)){$slug="{$base}-{$i}";$i++;}
        return $slug;
    }

    public function updateRating(int $shopId): void {
        $this->db->prepare("UPDATE shops s SET rating_avg=(SELECT IFNULL(AVG(r.rating),0) FROM reviews r WHERE r.shop_id=s.id AND r.is_visible=1),rating_count=(SELECT COUNT(*) FROM reviews r WHERE r.shop_id=s.id AND r.is_visible=1) WHERE s.id=?")->execute([$shopId]);
    }

    public function logModeration(int $shopId, int $adminId, string $action, ?string $reason=null, ?int $days=null, ?string $expiresAt=null): void {
        $this->db->prepare("INSERT INTO shop_moderation_log (shop_id,admin_id,action,reason,duration_days,expires_at) VALUES (?,?,?,?,?,?)")->execute([$shopId,$adminId,$action,$reason,$days,$expiresAt]);
    }

    public function getModerationLog(int $shopId, int $limit=20): array {
        return $this->raw("SELECT ml.*,u.name AS admin_name FROM shop_moderation_log ml JOIN users u ON u.id=ml.admin_id WHERE ml.shop_id=? ORDER BY ml.created_at DESC LIMIT {$limit}",[$shopId]);
    }

    public function incrementViews(int $shopId): void {
        $this->db->prepare("UPDATE shops SET views_count=views_count+1 WHERE id=?")->execute([$shopId]);
    }
}

class Employee extends BaseModel {
    protected string $table = 'employees';

    public function getByShop(int $shopId, bool $activeOnly=true): array {
        $extra = $activeOnly ? 'AND e.status="active"' : '';
        return $this->raw("SELECT e.*,GROUP_CONCAT(es.service_id) AS service_ids FROM employees e LEFT JOIN employee_services es ON es.employee_id=e.id WHERE e.shop_id=? {$extra} GROUP BY e.id ORDER BY e.sort_order,e.name",[$shopId]);
    }

    public function getAvailableForService(int $shopId, int $serviceId): array {
        return $this->raw("SELECT e.* FROM employees e JOIN employee_services es ON es.employee_id=e.id WHERE e.shop_id=? AND es.service_id=? AND e.status='active' ORDER BY e.sort_order,e.name",[$shopId,$serviceId]);
    }

    // ← FIX: método getHours que faltaba
    public function getHours(int $employeeId): array {
        return $this->raw("SELECT * FROM employee_hours WHERE employee_id=? ORDER BY day_of_week",[$employeeId]);
    }

    public function assignService(int $employeeId, int $serviceId): void {
        $this->db->prepare("INSERT IGNORE INTO employee_services (employee_id,service_id) VALUES (?,?)")->execute([$employeeId,$serviceId]);
    }

    public function revokeService(int $employeeId, int $serviceId): void {
        $this->db->prepare("DELETE FROM employee_services WHERE employee_id=? AND service_id=?")->execute([$employeeId,$serviceId]);
    }
}

class Service extends BaseModel {
    protected string $table = 'services';

    public function getByShop(int $shopId, bool $withCat=true): array {
        $sql = $withCat
            ? "SELECT s.*,c.name AS category_name FROM services s LEFT JOIN service_categories c ON c.id=s.category_id WHERE s.shop_id=? AND s.is_active=1 ORDER BY c.sort_order,s.sort_order,s.name"
            : "SELECT * FROM services WHERE shop_id=? AND is_active=1 ORDER BY sort_order,name";
        return $this->raw($sql,[$shopId]);
    }

    public function getGroupedByCategory(int $shopId): array {
        $grouped=[];
        foreach($this->getByShop($shopId,true) as $row){
            $grouped[$row['category_name']??'Sin categoría'][]=$row;
        }
        return $grouped;
    }
}

class Appointment extends BaseModel {
    protected string $table = 'appointments';

    public function getAvailableSlots(int $employeeId, int $serviceId, string $date): array {
        // Reject past dates entirely
        if (strtotime($date) < strtotime(date('Y-m-d'))) return [];

        $st=$this->db->prepare("SELECT s.duration_min,COALESCE(eh.opens_at,sh2.opens_at) AS opens,COALESCE(eh.closes_at,sh2.closes_at) AS closes FROM services s JOIN employees e ON e.id=? JOIN shops sh ON sh.id=e.shop_id JOIN shop_hours sh2 ON sh2.shop_id=sh.id AND sh2.day_of_week=DAYOFWEEK(?)-1 LEFT JOIN employee_hours eh ON eh.employee_id=? AND eh.day_of_week=DAYOFWEEK(?)-1 WHERE s.id=? LIMIT 1");
        $st->execute([$employeeId,$date,$employeeId,$date,$serviceId]);
        $info=$st->fetch();
        if(!$info||!$info['opens']||!$info['closes']||$info['duration_min']<=0)return[];
        $busy=$this->raw("SELECT start_time,end_time FROM appointments WHERE employee_id=? AND date=? AND status NOT IN ('cancelled_client','cancelled_shop') ORDER BY start_time",[$employeeId,$date]);
        $blocks=$this->raw("SELECT start_time,end_time FROM schedule_blocks WHERE (employee_id=? OR employee_id IS NULL) AND date=?",[$employeeId,$date]);
        $occupied=array_merge($busy,$blocks);
        $slots=[];$duration=(int)$info['duration_min'];
        $current=strtotime("{$date} {$info['opens']}");
        $end=strtotime("{$date} {$info['closes']}") - $duration*60;

        // If today, advance start past current time (round up to next 15-min mark)
        $isToday = ($date === date('Y-m-d'));
        if ($isToday) {
            $now = time();
            // Round up to next 15-min boundary, add 5 min buffer
            $minStart = $now + 5 * 60;
            $rounded  = (int)(ceil($minStart / (15*60)) * (15*60));
            if ($rounded > $current) $current = $rounded;
        }

        while($current<=$end){
            $ss=date('H:i',$current);$se=date('H:i',$current+$duration*60);
            $ok=true;
            foreach($occupied as $o){if($ss<$o['end_time']&&$se>$o['start_time']){$ok=false;break;}}
            if($ok)$slots[]=['start'=>$ss,'end'=>$se];
            $current+=15*60;
        }
        return $slots;
    }

    public function getByShop(int $shopId, string $date, string $status=''): array {
        $extra=$status?'AND a.status=?':'';
        $params=$status?[$shopId,$date,$status]:[$shopId,$date];
        return $this->raw("SELECT a.*,e.name AS employee_name,s.name AS service_name FROM appointments a JOIN employees e ON e.id=a.employee_id JOIN services s ON s.id=a.service_id WHERE a.shop_id=? AND a.date=? {$extra} ORDER BY a.start_time",$params);
    }

    public function getForClient(string $email): array {
        return $this->raw("SELECT a.*,sh.name AS shop_name,sh.slug AS shop_slug,e.name AS employee_name,s.name AS service_name, (SELECT COUNT(*) FROM reviews r WHERE r.appointment_id=a.id) AS has_review FROM appointments a JOIN shops sh ON sh.id=a.shop_id JOIN employees e ON e.id=a.employee_id JOIN services s ON s.id=a.service_id WHERE a.client_email=? ORDER BY a.date DESC,a.start_time DESC",[strtolower(trim($email))]);
    }

    public function isSlotTaken(int $employeeId, string $date, string $start, string $end, ?int $excludeId=null): bool {
        $sql="SELECT 1 FROM appointments WHERE employee_id=? AND date=? AND status NOT IN ('cancelled_client','cancelled_shop') AND start_time<? AND end_time>?";
        $params=[$employeeId,$date,$end,$start];
        if($excludeId){$sql.=' AND id!=?';$params[]=$excludeId;}
        $st=$this->db->prepare($sql);$st->execute($params);return(bool)$st->fetchColumn();
    }
}

class Review extends BaseModel {
    protected string $table = 'reviews';

    public function getByShop(int $shopId, int $page=1, int $perPage=10, string $sort='newest'): array {
        $offset=($page-1)*$perPage;
        $total=$this->count('shop_id=? AND is_visible=1',[$shopId]);
        $orderBy = match($sort) {
            'highest' => 'r.rating DESC, r.created_at DESC',
            'lowest'  => 'r.rating ASC,  r.created_at DESC',
            default   => 'r.created_at DESC',  // newest
        };
        $data=$this->raw("SELECT r.*,a.client_name,a.client_email FROM reviews r JOIN appointments a ON a.id=r.appointment_id WHERE r.shop_id=? AND r.is_visible=1 ORDER BY {$orderBy} LIMIT {$perPage} OFFSET {$offset}",[$shopId]);
        return['data'=>$data,'total'=>$total,'per_page'=>$perPage,'current_page'=>$page,'last_page'=>max(1,(int)ceil($total/$perPage)),'sort'=>$sort];
    }

    public function getRatingBreakdown(int $shopId): array {
        $rows=$this->raw("SELECT rating,COUNT(*) AS cnt FROM reviews WHERE shop_id=? AND is_visible=1 GROUP BY rating ORDER BY rating DESC",[$shopId]);
        $breakdown=array_fill(1,5,0);$total=0;
        foreach($rows as $r){$breakdown[(int)$r['rating']]=(int)$r['cnt'];$total+=(int)$r['cnt'];}
        return['breakdown'=>$breakdown,'total'=>$total];
    }
}