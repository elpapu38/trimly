<?php
// ============================================================
//  app/Controllers/ReportController.php
// ============================================================
namespace App\Controllers;

class ReportController extends BaseController
{
    public function store(string $slug): void
    {
        $user = $this->auth();
        if (!$user) {
            $this->jsonError('Debés iniciar sesión para reportar.', 401);
        }

        // Dueños de local y empleados no pueden reportar locales
        if (in_array($user['role'], ['shop_owner', 'employee', 'superadmin'])) {
            $this->jsonError('Tu tipo de cuenta no puede reportar locales.', 403);
        }

        // Verificar CSRF — el fetch manda _csrf en el body
        $token = $this->input('_csrf', '');
        if (!verifyCsrf($token)) {
            $this->jsonError('Sesión expirada. Recargá la página e intentá de nuevo.');
        }

        $shopModel = new \App\Models\Shop();
        $shop      = $shopModel->findBySlug($slug);
        if (!$shop) {
            $this->jsonError('Local no encontrado.', 404);
        }

        $reason = trim($this->input('reason', ''));
        $note   = trim($this->input('note', ''));

        $allowed = ['spam', 'fake', 'offensive', 'closed', 'other'];
        if (!in_array($reason, $allowed)) {
            $this->jsonError('Motivo de reporte inválido.');
        }

        $db = \App\Models\Database::getInstance();

        // Evitar reportes duplicados del mismo usuario al mismo local en los últimos 7 días
        $existing = $db->prepare(
            "SELECT id FROM shop_reports
             WHERE shop_id = ? AND user_id = ?
             AND created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)"
        );
        $existing->execute([$shop['id'], $user['id']]);
        if ($existing->fetchColumn()) {
            $this->jsonError('Ya reportaste este local recientemente. Revisaremos tu reporte anterior.');
        }

        $stmt = $db->prepare(
            "INSERT INTO shop_reports (shop_id, user_id, reason, note, created_at)
             VALUES (?, ?, ?, ?, NOW())"
        );
        $stmt->execute([
            $shop['id'],
            $user['id'],
            $reason,
            $note ? substr($note, 0, 500) : null,
        ]);

        $this->jsonSuccess('Gracias por tu reporte. Lo revisaremos a la brevedad.');
    }
}
