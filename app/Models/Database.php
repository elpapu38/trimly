<?php
// ============================================================
//  app/Models/Database.php — Singleton PDO
// ============================================================
namespace App\Models;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $cfg = require BASE_PATH . '/config/database.php';
            $dsn = "mysql:host={$cfg['host']};port={$cfg['port']};dbname={$cfg['database']};charset={$cfg['charset']}";
            try {
                self::$instance = new PDO($dsn, $cfg['username'], $cfg['password'], $cfg['options']);
            } catch (PDOException $e) {
                error_log('Trimly DB Error: ' . $e->getMessage());
                http_response_code(500);
                echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Error de conexión</title>
                <style>body{background:#0d0d0d;color:#eee;font-family:sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;}
                .box{text-align:center;padding:40px;border:1px solid #333;border-radius:16px;max-width:500px;}
                h1{color:#C9A84C;margin-bottom:12px;}p{color:#888;margin:8px 0;font-size:14px;}
                .err{background:#1a0000;border:1px solid #5a0000;border-radius:8px;padding:12px;margin:16px 0;font-family:monospace;font-size:13px;color:#ff6b6b;text-align:left;}</style>
                </head><body><div class="box">
                <h1>⚠ Error de base de datos</h1>
                <p>No se pudo conectar a MySQL. Verificá que:</p>
                <p>• MySQL esté corriendo en Laragon (botón verde)</p>
                <p>• La base de datos <strong>trimly</strong> exista</p>
                <p>• Las credenciales en <strong>.env</strong> sean correctas</p>
                <div class="err">' . htmlspecialchars($e->getMessage()) . '</div>
                </div></body></html>';
                exit;
            }
        }
        return self::$instance;
    }

    // Prevenir clonación / instanciación directa
    private function __construct() {}
    private function __clone()    {}
    public function __wakeup()    { throw new \Exception('Cannot unserialize singleton'); }
}
