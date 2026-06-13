<?php
// ============================================================
//  config/app.php — Configuración central de Trimly
// ============================================================

return [
    'name'     => 'Trimly',
    'version'  => '1.0.0',
    'url'      => getenv('APP_URL') ?: 'http://localhost',
    'env'      => getenv('APP_ENV') ?: 'development',
    'debug'    => getenv('APP_DEBUG') ?: true,
    'timezone' => 'America/Argentina/Buenos_Aires',
    'locale'   => 'es_AR',

    // Sesión
    'session' => [
        'name'     => 'trimly_sess',
        'lifetime' => 7200,   // 2 horas
        'path'     => '/',
    ],

    // Uploads
    'upload' => [
        'max_size'   => 5 * 1024 * 1024,   // 5 MB
        'allowed'    => ['jpg','jpeg','png','webp'],
        'path'       => __DIR__ . '/../public/uploads/',
        'url'        => '/uploads/',
    ],

    // Paginación
    'per_page' => 12,

    // Email (SMTP)
    'mail' => [
        'driver'   => 'smtp',
        'host'     => getenv('MAIL_HOST')     ?: '',
        'port'     => getenv('MAIL_PORT')     ?: 587,
        'username' => getenv('MAIL_USER')     ?: '',
        'password' => getenv('MAIL_PASS')     ?: '',
        'from'     => getenv('MAIL_FROM')     ?: 'noreply@trimly.com',
        'from_name'=> 'Trimly',
    ],

    // MercadoPago
    'mercadopago' => [
        'public_key'   => getenv('MP_PUBLIC_KEY')  ?: '',
        'access_token' => getenv('MP_ACCESS_TOKEN') ?: '',
        'sandbox'      => true,
    ],

    // Roles
    'roles' => [
        'superadmin' => 'Super Administrador',
        'shop_owner' => 'Dueño de Local',
        'employee'   => 'Empleado',
        'client'     => 'Cliente',
    ],

    // Estado de turnos → etiquetas y colores
    'appointment_statuses' => [
        'pending'           => ['label' => 'Pendiente',              'color' => 'yellow'],
        'confirmed'         => ['label' => 'Confirmado',             'color' => 'green'],
        'cancelled_client'  => ['label' => 'Cancelado por cliente',  'color' => 'red'],
        'cancelled_shop'    => ['label' => 'Cancelado por el local', 'color' => 'red'],
        'completed'         => ['label' => 'Completado',             'color' => 'blue'],
        'no_show'           => ['label' => 'No se presentó',         'color' => 'gray'],
    ],
];
