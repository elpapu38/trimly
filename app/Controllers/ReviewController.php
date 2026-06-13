<?php
// ============================================================
//  app/Controllers/ReviewController.php
// ============================================================
namespace App\Controllers;

use App\Models\Shop;
use App\Models\Review;

class ReviewController extends BaseController
{
    private Shop   $shopModel;
    private Review $reviewModel;

    public function __construct()
    {
        $this->shopModel   = new Shop();
        $this->reviewModel = new Review();
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

    public function shopIndex(): void
    {
        $shop  = $this->getOwnerShop();
        $page  = (int) ($this->input('page', 1));
        $result = $this->reviewModel->getByShop($shop['id'], $page, 15);

        $this->view('shop_dash/reviews', [
            'pageTitle' => 'Reseñas — ' . $shop['name'],
            'shop'      => $shop,
            'reviews'   => $result['data'],
            'pagination'=> $result,
        ]);
    }

    public function reply(int $id): void
    {
        $shop   = $this->getOwnerShop();
        $review = $this->reviewModel->find($id);

        if (!$review || $review['shop_id'] !== $shop['id']) {
            $this->json(['error' => 'Reseña no encontrada'], 404);
        }

        $reply = sanitize($this->input('reply', ''));
        if (strlen($reply) < 2) {
            $this->json(['error' => 'La respuesta es demasiado corta'], 400);
        }

        $this->reviewModel->update($id, ['reply' => $reply]);
        $this->json(['success' => true, 'reply' => $reply]);
    }

    public function toggle(int $id): void
    {
        $shop   = $this->getOwnerShop();
        $review = $this->reviewModel->find($id);

        if (!$review || $review['shop_id'] !== $shop['id']) {
            $this->json(['error' => 'No autorizado'], 403);
        }

        $newVisible = $review['is_visible'] ? 0 : 1;
        $this->reviewModel->update($id, ['is_visible' => $newVisible]);
        $this->shopModel->updateRating($shop['id']);
        $this->json(['success' => true, 'is_visible' => $newVisible]);
    }

    // ── POST /resena/reportar — cualquier usuario logueado ────
    public function report(): void
    {
        $user = $this->auth();
        if (!$user) { $this->jsonError('Debés iniciar sesión.', 401); }

        if (!verifyCsrf($this->input('_csrf', ''))) {
            $this->jsonError('Sesión expirada. Recargá la página.');
        }

        $reviewId = (int) $this->input('review_id', 0);
        $reason   = trim($this->input('reason', ''));

        $allowed = ['spam', 'fake', 'offensive', 'irrelevant', 'other'];
        if (!$reviewId || !in_array($reason, $allowed)) {
            $this->jsonError('Datos inválidos.');
        }

        $review = $this->reviewModel->find($reviewId);
        if (!$review || !$review['is_visible']) {
            $this->jsonError('Reseña no encontrada.');
        }

        $db = \App\Models\Database::getInstance();

        // Evitar reportes duplicados del mismo usuario a la misma reseña
        $exists = $db->prepare(
            "SELECT id FROM review_reports WHERE review_id = ? AND user_id = ?"
        );
        $exists->execute([$reviewId, $user['id']]);
        if ($exists->fetchColumn()) {
            $this->jsonError('Ya reportaste esta reseña anteriormente.');
        }

        $db->prepare(
            "INSERT INTO review_reports (review_id, user_id, reason, created_at) VALUES (?, ?, ?, NOW())"
        )->execute([$reviewId, $user['id'], $reason]);

        // Incrementar contador y marcar como flagged si supera umbral (3 reportes)
        $db->prepare(
            "UPDATE reviews SET report_count = report_count + 1,
             flagged_at = CASE WHEN report_count + 1 >= 3 AND flagged_at IS NULL THEN NOW() ELSE flagged_at END
             WHERE id = ?"
        )->execute([$reviewId]);

        $this->jsonSuccess('Reseña reportada. El equipo la revisará pronto.');
    }
}
