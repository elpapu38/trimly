<?php
namespace App\Controllers;
use App\Models\{Shop, User, Appointment, Review};

class AdminController extends BaseController
{
    private Shop $shopModel;
    private User $userModel;
    private Review $reviewModel;

    public function __construct() {
        $this->shopModel   = new Shop();
        $this->userModel   = new User();
        $this->reviewModel = new Review();
    }

    public function index(): void {
        $db = \App\Models\Database::getInstance();
        $stats = [
            'total_shops'    => (int)$db->query("SELECT COUNT(*) FROM shops")->fetchColumn(),
            'active_shops'   => (int)$db->query("SELECT COUNT(*) FROM shops WHERE status='active' AND is_shadowbanned=0")->fetchColumn(),
            'pending_shops'  => (int)$db->query("SELECT COUNT(*) FROM shops WHERE status='pending'")->fetchColumn(),
            'suspended_shops'=> (int)$db->query("SELECT COUNT(*) FROM shops WHERE status='suspended'")->fetchColumn(),
            'shadowbanned'   => (int)$db->query("SELECT COUNT(*) FROM shops WHERE is_shadowbanned=1")->fetchColumn(),
            'total_users'    => (int)$db->query("SELECT COUNT(*) FROM users WHERE role='client'")->fetchColumn(),
            'total_owners'   => (int)$db->query("SELECT COUNT(*) FROM users WHERE role='shop_owner'")->fetchColumn(),
            'total_appts'    => (int)$db->query("SELECT COUNT(*) FROM appointments")->fetchColumn(),
            'month_appts'    => (int)$db->query("SELECT COUNT(*) FROM appointments WHERE MONTH(date)=MONTH(NOW()) AND YEAR(date)=YEAR(NOW())")->fetchColumn(),
            'month_revenue'  => (float)$db->query("SELECT IFNULL(SUM(price),0) FROM appointments WHERE status='completed' AND MONTH(date)=MONTH(NOW()) AND YEAR(date)=YEAR(NOW())")->fetchColumn(),
            'flagged_reviews'=> (int)$db->query("SELECT COUNT(*) FROM reviews WHERE report_count > 0 AND is_visible=1")->fetchColumn(),
        ];
        $pendingShops = $this->shopModel->paginate(1, 8, 'status = "pending"');
        $recentUsers  = $db->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 8")->fetchAll();
        $revenue = $db->query("SELECT DATE_FORMAT(date,'%Y-%m') AS ym, SUM(price) AS rev, COUNT(*) AS cnt FROM appointments WHERE status='completed' AND date >= DATE_SUB(NOW(), INTERVAL 6 MONTH) GROUP BY ym ORDER BY ym")->fetchAll();
        $topShops = $db->query("SELECT s.id, s.name, s.city, s.rating_avg, s.views_count, COUNT(a.id) AS month_appts FROM shops s LEFT JOIN appointments a ON a.shop_id=s.id AND MONTH(a.date)=MONTH(NOW()) AND a.status='completed' WHERE s.status='active' GROUP BY s.id ORDER BY month_appts DESC, s.rating_avg DESC LIMIT 5")->fetchAll();
        $recentMod = $db->query("SELECT ml.*, s.name AS shop_name, u.name AS admin_name FROM shop_moderation_log ml JOIN shops s ON s.id=ml.shop_id JOIN users u ON u.id=ml.admin_id ORDER BY ml.created_at DESC LIMIT 10")->fetchAll();
        $this->view('admin/index', compact('stats','pendingShops','recentUsers','revenue','topShops','recentMod') + ['pageTitle'=>'Panel Admin — Trimly']);
    }

    public function shops(): void {
        $page   = max(1,(int)$this->input('page',1));
        $status = $this->input('status','');
        $search = $this->input('q','');
        $type   = $this->input('type','');
        $where  = ['1']; $params = [];
        if ($status) { $where[] = 'status = ?'; $params[] = $status; }
        if ($type)   { $where[] = 'type = ?';   $params[] = $type; }
        if ($search) { $where[] = '(name LIKE ? OR city LIKE ?)'; $params = array_merge($params,["%$search%","%$search%"]); }
        $result = $this->shopModel->paginate($page, 20, implode(' AND ',$where), $params);
        $this->view('admin/shops', ['pageTitle'=>'Locales — Admin','shops'=>$result['data'],'pagination'=>$result,'filter'=>compact('status','search','type')]);
    }

    public function shopDetail(int $id): void {
        $shop = $this->shopModel->find($id);
        if (!$shop) { $this->flash('error','Local no encontrado.'); $this->redirect('/admin/locales'); }
        $owner  = $this->userModel->find($shop['owner_id']);
        $db     = \App\Models\Database::getInstance();
        $st     = $db->prepare("SELECT COUNT(*) AS total, SUM(CASE WHEN status='completed' THEN 1 ELSE 0 END) AS completed, SUM(CASE WHEN status='completed' THEN price ELSE 0 END) AS revenue FROM appointments WHERE shop_id=?");
        $st->execute([$id]);
        $modLog  = $this->shopModel->getModerationLog($id, 30);
        $reviews = $this->reviewModel->getByShop($id, 1, 5);
        $this->view('admin/shop_detail', ['pageTitle'=>$shop['name'].' — Admin','shop'=>$shop,'owner'=>$owner,'stats'=>$st->fetch(),'modLog'=>$modLog,'reviews'=>$reviews['data']]);
    }

    public function moderateShop(int $id): void {
        $shop   = $this->shopModel->find($id);
        if (!$shop) { $this->jsonError('Local no encontrado',404); return; }
        $action = $this->input('action');
        $reason = sanitize($this->input('reason',''));
        $days   = (int)$this->input('days',0);
        $admin  = $this->auth();
        $allowed = ['approve','suspend','unsuspend','ban','unban','shadowban','unshadowban','feature','unfeature','verify','note'];
        if (!in_array($action,$allowed)) { $this->jsonError('Acción no válida',400); return; }
        $updates = []; $logAction = ''; $expiresAt = null;
        switch ($action) {
            case 'approve':
                $updates = ['status'=>'active']; $logAction = 'approved';
                try { $owner=(new User())->find($shop['owner_id']); (new \App\Helpers\Mailer())->sendShopApproved($shop,$owner); } catch(\Exception $e){}
                break;
            case 'suspend':
                $expiresAt = $days>0 ? date('Y-m-d H:i:s',strtotime("+{$days} days")) : null;
                $pub       = (int)($this->input('suspension_public', 1));
                $updates   = [
                    'status'             => 'suspended',
                    'suspension_reason'  => $reason,
                    'suspension_until'   => $expiresAt,
                    'suspension_public'  => $pub,
                ];
                $logAction = 'suspended'; break;
            case 'unsuspend':
                $updates = [
                    'status'            => 'active',
                    'suspension_reason' => null,
                    'suspension_until'  => null,
                    'suspension_public' => 1,
                ];
                $logAction = 'unsuspended'; break;
            case 'ban':       $updates=['status'=>'suspended','ban_reason'=>$reason]; $logAction='banned'; break;
            case 'unban':     $updates=['status'=>'active','ban_reason'=>null]; $logAction='unbanned'; break;
            case 'shadowban': $updates=['is_shadowbanned'=>1]; $logAction='shadowban'; break;
            case 'unshadowban':$updates=['is_shadowbanned'=>0]; $logAction='unshadowban'; break;
            case 'feature':   $updates=['featured'=>1]; $logAction='featured'; break;
            case 'unfeature': $updates=['featured'=>0]; $logAction='unfeatured'; break;
            case 'verify':    $updates=['verified'=>1]; $logAction='verified'; break;
            case 'note':      $logAction='note'; break;
        }
        if ($updates) $this->shopModel->update($id,$updates);
        $this->shopModel->logModeration($id,$admin['id'],$logAction,$reason?:null,$days?:null,$expiresAt);
        $this->json(['success'=>true,'action'=>$logAction]);
    }

    public function users(): void {
        $page   = max(1,(int)$this->input('page',1));
        $role   = $this->input('role','');
        $status = $this->input('status','');
        $search = $this->input('q','');
        $where  = ['1']; $params = [];
        if ($role)   { $where[]='role = ?';   $params[]=$role; }
        if ($status) { $where[]='status = ?'; $params[]=$status; }
        if ($search) { $where[]='(name LIKE ? OR email LIKE ?)'; $params=array_merge($params,["%$search%","%$search%"]); }
        $result = $this->userModel->paginate($page,25,implode(' AND ',$where),$params);
        $this->view('admin/users',['pageTitle'=>'Usuarios — Admin','users'=>$result['data'],'pagination'=>$result,'filter'=>compact('role','status','search')]);
    }

    public function userStatus(int $id): void {
        $action = $this->input('action', $this->input('status', ''));
        $target = $this->userModel->find($id);
        if (!$target || $target['role']==='superadmin') { $this->jsonError('No permitido',403); return; }

        $reason  = sanitize($this->input('reason', ''));
        $days    = (int) $this->input('days', 0);

        if ($action === 'delete') {
            // Soft-delete banned user — preserve reviews and completed appointments
            $db = \App\Models\Database::getInstance();
            // Anonymize personal data
            $db->prepare("UPDATE users SET name='[eliminado]', email=CONCAT('deleted_',id,'@trimly.local'),
                phone=NULL, avatar=NULL, status='banned', ban_reason='Cuenta eliminada por admin'
                WHERE id=? AND role != 'superadmin'")->execute([$id]);
            // Cancel pending/confirmed appointments
            $db->prepare("UPDATE appointments SET status='cancelled_shop'
                WHERE (client_email=(SELECT email FROM users WHERE id=?) OR employee_id=?)
                AND status IN ('pending','confirmed')")->execute([$id,$id]);
            $this->json(['success'=>true,'action'=>'deleted']);
            return;
        }

        if (!in_array($action,['active','suspended','banned'])) { $this->jsonError('Estado no válido',400); return; }

        // Check if suspended_until column exists before using it
        $db2 = \App\Models\Database::getInstance();
        $hasSuspendedUntil = (bool) $db2->query(
            "SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='users' AND COLUMN_NAME='suspended_until'"
        )->fetchColumn();

        if ($action === 'active') {
            $sql = $hasSuspendedUntil
                ? "UPDATE users SET status='active', ban_reason=NULL, suspended_until=NULL WHERE id=?"
                : "UPDATE users SET status='active', ban_reason=NULL WHERE id=?";
            $db2->prepare($sql)->execute([$id]);
        } elseif ($action === 'suspended') {
            $until = ($days > 0) ? date('Y-m-d H:i:s', strtotime("+{$days} days")) : null;
            if ($hasSuspendedUntil) {
                $db2->prepare("UPDATE users SET status='suspended', ban_reason=?, suspended_until=? WHERE id=?")
                    ->execute([$reason ?: null, $until, $id]);
            } else {
                $db2->prepare("UPDATE users SET status='suspended', ban_reason=? WHERE id=?")
                    ->execute([$reason ?: null, $id]);
            }
        } elseif ($action === 'banned') {
            if ($hasSuspendedUntil) {
                $db2->prepare("UPDATE users SET status='banned', ban_reason=?, suspended_until=NULL WHERE id=?")
                    ->execute([$reason ?: null, $id]);
            } else {
                $db2->prepare("UPDATE users SET status='banned', ban_reason=? WHERE id=?")
                    ->execute([$reason ?: null, $id]);
            }
        }

        $this->json(['success'=>true, 'action'=>$action]);
    }

    public function reviews(): void {
        $db     = \App\Models\Database::getInstance();
        $page   = max(1,(int)$this->input('page',1));
        $offset = ($page-1)*20;
        $total  = (int)$db->query("SELECT COUNT(*) FROM reviews WHERE report_count > 0")->fetchColumn();
        $st     = $db->prepare("SELECT r.*, a.client_name, sh.name AS shop_name FROM reviews r JOIN appointments a ON a.id=r.appointment_id JOIN shops sh ON sh.id=r.shop_id WHERE r.report_count > 0 ORDER BY r.report_count DESC, r.created_at DESC LIMIT 20 OFFSET {$offset}");
        $st->execute();
        $this->view('admin/reviews',['pageTitle'=>'Reseñas reportadas','reviews'=>$st->fetchAll(),'total'=>$total,'page'=>$page,'lastPage'=>(int)ceil($total/20)]);
    }

    public function moderateReview(int $id): void {
        $action = $this->input('action');
        $db     = \App\Models\Database::getInstance();

        if ($action === 'hide') {
            $this->reviewModel->update($id, ['is_visible' => 0]);
        } elseif ($action === 'show') {
            $this->reviewModel->update($id, ['is_visible' => 1, 'report_count' => 0, 'flagged_at' => null]);
        } elseif ($action === 'delete') {
            $this->reviewModel->delete($id);
        } elseif ($action === 'warn') {
            // Send warning: record in user ban_reason without banning
            $reason  = sanitize($this->input('reason', 'Tu reseña fue reportada y revisada por el equipo de Trimly.'));
            $review  = $this->reviewModel->find($id);
            if ($review) {
                // Get user email from appointment
                $stmt = $db->prepare("SELECT a.client_email FROM appointments a WHERE a.id = ? LIMIT 1");
                $stmt->execute([$review['appointment_id']]);
                $appt = $stmt->fetch();
                if ($appt) {
                    $db->prepare("UPDATE users SET ban_reason = CONCAT(IFNULL(ban_reason,''), ' | ADVERTENCIA: ', ?) WHERE email = ?")
                       ->execute([$reason, $appt['client_email']]);
                }
                // Also hide the review
                $this->reviewModel->update($id, ['is_visible' => 0]);
            }
        }
        $this->json(['success' => true]);
    }

    // ── Períodos de estadísticas personalizados ──────────────
    public function statPeriods(): void {
        $db      = \App\Models\Database::getInstance();
        $periods = $db->query("SELECT sp.*, u.name AS creator FROM stat_periods sp JOIN users u ON u.id = sp.created_by ORDER BY sp.date_from DESC")->fetchAll();
        $this->view('admin/stat_periods', ['pageTitle' => 'Períodos de estadísticas', 'periods' => $periods]);
    }

    public function createStatPeriod(): void {
        $db   = \App\Models\Database::getInstance();
        $user = $this->auth();
        $name = sanitize($this->input('name',''));
        $from = $this->input('date_from','');
        $to   = $this->input('date_to','');
        if (!$name || !$from || !$to || $from > $to) { $this->jsonError('Datos inválidos.'); }
        $db->prepare("INSERT INTO stat_periods (name,date_from,date_to,created_by) VALUES (?,?,?,?)")
           ->execute([$name, $from, $to, $user['id']]);
        $this->json(['success' => true, 'id' => (int)$db->lastInsertId()]);
    }

    public function deleteStatPeriod(int $id): void {
        $db = \App\Models\Database::getInstance();
        $db->prepare("DELETE FROM stat_periods WHERE id = ?")->execute([$id]);
        $this->json(['success' => true]);
    }

    public function stats(): void {
        $db   = \App\Models\Database::getInstance();
        $year = (int)$this->input('year',date('Y'));
        $monthly = $db->prepare("SELECT MONTH(date) AS month, COUNT(*) AS total, SUM(CASE WHEN status='completed' THEN 1 ELSE 0 END) AS completed, SUM(CASE WHEN status='completed' THEN price ELSE 0 END) AS revenue FROM appointments WHERE YEAR(date)=? GROUP BY MONTH(date) ORDER BY month");
        $monthly->execute([$year]);
        $byMonth = array_fill(1,12,['month'=>0,'total'=>0,'completed'=>0,'revenue'=>0]);
        foreach ($monthly->fetchAll() as $row) $byMonth[(int)$row['month']] = $row;
        $topShops = $db->prepare("SELECT sh.name, sh.city, sh.type, COUNT(*) AS total, SUM(a.price) AS revenue FROM appointments a JOIN shops sh ON sh.id=a.shop_id WHERE a.status='completed' AND YEAR(a.date)=? GROUP BY sh.id ORDER BY revenue DESC LIMIT 10");
        $topShops->execute([$year]);
        $byType = $db->query("SELECT type, COUNT(*) AS cnt FROM shops WHERE status='active' GROUP BY type ORDER BY cnt DESC")->fetchAll();
        $byCity = $db->query("SELECT city, COUNT(*) AS cnt FROM shops WHERE status='active' AND city IS NOT NULL GROUP BY city ORDER BY cnt DESC LIMIT 10")->fetchAll();
        $growth = $db->query("SELECT DATE_FORMAT(created_at,'%Y-%m') AS ym, COUNT(*) AS new_users FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH) GROUP BY ym ORDER BY ym")->fetchAll();
        $this->view('admin/stats',['pageTitle'=>'Estadísticas globales','year'=>$year,'byMonth'=>array_values($byMonth),'topShops'=>$topShops->fetchAll(),'byType'=>$byType,'byCity'=>$byCity,'growth'=>$growth]);
    }
}
