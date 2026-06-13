<?php
// ============================================================
//  app/Helpers/Mailer.php
// ============================================================
namespace App\Helpers;

class Mailer
{
    private static function send(string $to, string $subject, string $html): bool
    {
        $cfg     = require BASE_PATH . '/config/app.php';
        $from    = $cfg['mail']['from'];
        $name    = $cfg['mail']['from_name'];
        $headers = implode("\r\n", [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            "From: {$name} <{$from}>",
            "Reply-To: {$from}",
        ]);
        return mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $html, $headers);
    }

    private static function wrap(string $title, string $body, string $cta = '', string $ctaUrl = ''): string
    {
        $appUrl = rtrim(getenv('APP_URL') ?: 'http://localhost', '/');
        $btn    = $cta
            ? "<div style='text-align:center;margin:28px 0'><a href='{$ctaUrl}' style='background:#C9A84C;color:#0D0D0D;font-weight:700;font-size:14px;padding:14px 32px;border-radius:10px;text-decoration:none;display:inline-block'>{$cta}</a></div>"
            : '';
        return "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'></head>
        <body style='margin:0;padding:0;background:#F5F0E8;font-family:Arial,sans-serif'>
          <div style='max-width:560px;margin:40px auto;background:#0D0D0D;border-radius:16px;overflow:hidden'>
            <div style='padding:24px 32px;border-bottom:1px solid #1A1A1A;text-align:center'>
              <span style='font-size:20px;font-weight:700;color:#F5F0E8;font-family:Georgia,serif'>Trimly<span style='color:#C9A84C'>.</span></span>
            </div>
            <div style='padding:32px'>
              <h2 style='color:#F5F0E8;font-size:20px;margin:0 0 16px;font-family:Georgia,serif'>{$title}</h2>
              <div style='color:#A0A0A0;font-size:14px;line-height:1.7'>{$body}</div>
              {$btn}
              <p style='color:#484848;font-size:11px;margin-top:24px'>Si no esperabas este email, podés ignorarlo. El enlace expira en 1 hora.</p>
            </div>
            <div style='padding:16px 32px;border-top:1px solid #1A1A1A;text-align:center'>
              <p style='color:#484848;font-size:11px;margin:0'>© " . date('Y') . " Trimly · <a href='{$appUrl}' style='color:#C9A84C'>trimly.com.ar</a></p>
            </div>
          </div>
        </body></html>";
    }

    public static function sendVerification(string $email, string $name, string $token): bool
    {
        $link  = rtrim(getenv('APP_URL') ?: 'http://localhost', '/') . "/verificar/{$token}";
        $first = explode(' ', $name)[0];
        $body  = "<p>Hola <strong style='color:#F5F0E8'>{$first}</strong>,</p>
                  <p>Gracias por registrarte en Trimly. Hacé clic abajo para activar tu cuenta.</p>
                  <p><a href='{$link}' style='color:#C9A84C;word-break:break-all'>{$link}</a></p>";
        return self::send($email, 'Verificá tu cuenta en Trimly', self::wrap('Verificá tu cuenta ✉', $body, 'Verificar cuenta', $link));
    }

    public static function sendPasswordReset(string $email, string $name, string $token): bool
    {
        $link  = rtrim(getenv('APP_URL') ?: 'http://localhost', '/') . "/reset/{$token}";
        $first = explode(' ', $name)[0];
        $body  = "<p>Hola <strong style='color:#F5F0E8'>{$first}</strong>,</p>
                  <p>Recibimos una solicitud para restablecer tu contraseña. El enlace expira en <strong style='color:#F5F0E8'>1 hora</strong>.</p>
                  <p><a href='{$link}' style='color:#C9A84C;word-break:break-all'>{$link}</a></p>";
        return self::send($email, 'Restablecé tu contraseña — Trimly', self::wrap('Restablecer contraseña 🔑', $body, 'Crear nueva contraseña', $link));
    }

    // Alias público para BookingController
    public function sendBookingConfirmation(array $appt, array $shop): bool
    {
        $appt['shop_name'] = $shop['name'];
        return self::sendAppointmentConfirmed(
            $appt['client_email'],
            $appt['client_name'],
            $appt,
            $appt['cancel_token']
        );
    }

    public function sendShopRegistered(array $shop, array $owner): bool
    {
        $cfg  = require BASE_PATH . '/config/app.php';
        $admin = $cfg['mail']['from'];
        $name  = htmlspecialchars($shop['name']);
        $city  = htmlspecialchars($shop['city']);
        $link  = rtrim(getenv('APP_URL') ?: 'http://localhost', '/') . '/admin/locales';
        $body  = "<p>Nuevo local registrado en Trimly:</p>
                  <p><strong style='color:#F5F0E8'>{$name}</strong> — {$city}</p>
                  <p>Dueño: " . htmlspecialchars($owner['name']) . " (" . htmlspecialchars($owner['email']) . ")</p>";
        return self::send($admin, "Nuevo local pendiente: {$name}", self::wrap('Nuevo local registrado', $body, 'Revisar en Admin', $link));
    }

    public function sendShopApproved(array $shop, array $owner): bool
    {
        $appUrl = rtrim(getenv('APP_URL') ?: 'http://localhost', '/');
        $name   = htmlspecialchars($shop['name']);
        $first  = explode(' ', $owner['name'])[0];
        $panel  = $appUrl . '/panel';
        $body   = "<p>Hola <strong style='color:#F5F0E8'>{$first}</strong>,</p>
                   <p>¡Tu local <strong style='color:#C9A84C'>{$name}</strong> fue aprobado y ya está visible en Trimly!</p>
                   <p>Ahora podés cargar tus servicios, empleados y empezar a recibir reservas.</p>";
        return self::send($owner['email'], "¡Tu local fue aprobado en Trimly!", self::wrap('Local aprobado ✓', $body, 'Ir al panel', $panel));
    }

    public static function sendAppointmentConfirmed(string $email, string $clientName, array $appt, string $cancelToken): bool
    {
        $cancel = rtrim(getenv('APP_URL') ?: 'http://localhost', '/') . "/cancelar/{$cancelToken}";
        $first  = explode(' ', $clientName)[0];
        $date   = fecha($appt['date']);
        $time   = hora($appt['start_time']);
        $shop   = htmlspecialchars($appt['shop_name'] ?? '');
        $svc    = htmlspecialchars($appt['service_name'] ?? '');
        $emp    = htmlspecialchars($appt['employee_name'] ?? '');
        $price  = money((float)($appt['price'] ?? 0));
        $body   = "<p>Hola <strong style='color:#F5F0E8'>{$first}</strong>,</p>
                   <p>Tu turno quedó confirmado:</p>
                   <div style='background:#111;border:1px solid #1A1A1A;border-radius:10px;padding:16px;margin:16px 0'>
                     <table style='width:100%;border-collapse:collapse'>
                       <tr><td style='padding:5px 0;color:#707070;font-size:12px'>Local</td><td style='padding:5px 0;color:#F5F0E8;text-align:right'><strong>{$shop}</strong></td></tr>
                       <tr><td style='padding:5px 0;color:#707070;font-size:12px'>Servicio</td><td style='padding:5px 0;color:#F5F0E8;text-align:right'>{$svc}</td></tr>
                       <tr><td style='padding:5px 0;color:#707070;font-size:12px'>Profesional</td><td style='padding:5px 0;color:#F5F0E8;text-align:right'>{$emp}</td></tr>
                       <tr><td style='padding:5px 0;color:#707070;font-size:12px'>Fecha</td><td style='padding:5px 0;color:#C9A84C;text-align:right;font-weight:700'>{$date} a las {$time}</td></tr>
                       <tr><td style='padding:5px 0;color:#707070;font-size:12px'>Total</td><td style='padding:5px 0;color:#F5F0E8;text-align:right'>{$price}</td></tr>
                     </table>
                   </div>
                   <p>Para cancelar tu turno: <a href='{$cancel}' style='color:#C9A84C'>{$cancel}</a></p>";
        return self::send($email, "Turno confirmado — {$shop}", self::wrap('Turno confirmado ✓', $body));
    }
}
