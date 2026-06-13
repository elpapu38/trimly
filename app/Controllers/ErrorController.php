<?php
// ============================================================
//  app/Controllers/ErrorController.php
// ============================================================
namespace App\Controllers;

class ErrorController extends BaseController
{
    public function notFound(): void
    {
        $this->view('errors/404', ['pageTitle' => 'Página no encontrada'], 404);
    }

    public function forbidden(): void
    {
        $this->view('errors/403', ['pageTitle' => 'Acceso denegado'], 403);
    }

    public function serverError(): void
    {
        $this->view('errors/500', ['pageTitle' => 'Error interno'], 500);
    }
}
