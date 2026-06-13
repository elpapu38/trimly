-- ============================================================
--  TRIMLY — Schema completo v2.1
--  MySQL 8+ / MariaDB 10.5+
--  Credenciales: admin@trimly.com / admin123
--                carlos@barberia.com / carlos123
-- ============================================================
SET FOREIGN_KEY_CHECKS = 0;
SET NAMES utf8mb4;

DROP TABLE IF EXISTS audit_log;
DROP TABLE IF EXISTS review_reports;
DROP TABLE IF EXISTS favorites;
DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS schedule_blocks;
DROP TABLE IF EXISTS appointments;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS employee_services;
DROP TABLE IF EXISTS employee_hours;
DROP TABLE IF EXISTS employees;
DROP TABLE IF EXISTS service_categories;
DROP TABLE IF EXISTS services;
DROP TABLE IF EXISTS shop_closures;
DROP TABLE IF EXISTS shop_hours;
DROP TABLE IF EXISTS shop_photos;
DROP TABLE IF EXISTS shop_moderation_log;
DROP TABLE IF EXISTS shops;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(100) NOT NULL,
    email         VARCHAR(150) NOT NULL UNIQUE,
    phone         VARCHAR(30)  NULL,
    password_hash VARCHAR(255) NOT NULL,
    role          ENUM('superadmin','shop_owner','employee','client') NOT NULL DEFAULT 'client',
    avatar        VARCHAR(255) NULL,
    email_verified TINYINT(1)  NOT NULL DEFAULT 0,
    verify_token  VARCHAR(100) NULL,
    reset_token   VARCHAR(100) NULL,
    reset_expires DATETIME     NULL,
    status        ENUM('active','suspended','banned') NOT NULL DEFAULT 'active',
    last_login    DATETIME     NULL,
    created_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role  (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE shops (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    owner_id         INT UNSIGNED  NOT NULL,
    name             VARCHAR(120)  NOT NULL,
    slug             VARCHAR(130)  NOT NULL UNIQUE,
    type             ENUM('barbershop','salon','mixed','nails','spa','tattoo','makeup','other') NOT NULL DEFAULT 'barbershop',
    target_audience  ENUM('men','women','unisex') NOT NULL DEFAULT 'unisex',
    description      TEXT          NULL,
    specialties      VARCHAR(500)  NULL,
    amenities        VARCHAR(500)  NULL,
    phone            VARCHAR(30)   NULL,
    email            VARCHAR(150)  NULL,
    website          VARCHAR(255)  NULL,
    instagram        VARCHAR(100)  NULL,
    facebook         VARCHAR(100)  NULL,
    whatsapp         VARCHAR(30)   NULL,
    logo             VARCHAR(255)  NULL,
    cover_image      VARCHAR(255)  NULL,
    address          VARCHAR(255)  NULL,
    city             VARCHAR(100)  NULL,
    province         VARCHAR(100)  NULL,
    country          VARCHAR(80)   NOT NULL DEFAULT 'Argentina',
    postal_code      VARCHAR(20)   NULL,
    latitude         DECIMAL(10,7) NULL,
    longitude        DECIMAL(10,7) NULL,
    currency         CHAR(3)       NOT NULL DEFAULT 'ARS',
    rating_avg       DECIMAL(3,2)  NOT NULL DEFAULT 0.00,
    rating_count     INT UNSIGNED  NOT NULL DEFAULT 0,
    status           ENUM('pending','active','suspended','closed') NOT NULL DEFAULT 'pending',
    plan             ENUM('free','pro','premium') NOT NULL DEFAULT 'free',
    plan_expires     DATE          NULL,
    verified         TINYINT(1)    NOT NULL DEFAULT 0,
    featured         TINYINT(1)    NOT NULL DEFAULT 0,
    views_count      INT UNSIGNED  NOT NULL DEFAULT 0,
    is_shadowbanned  TINYINT(1)    NOT NULL DEFAULT 0,
    suspension_reason VARCHAR(500) NULL,
    suspension_until DATETIME      NULL,
    ban_reason       VARCHAR(500)  NULL,
    created_at       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_city   (city),
    INDEX idx_type   (type),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE shop_moderation_log (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    shop_id       INT UNSIGNED NOT NULL,
    admin_id      INT UNSIGNED NOT NULL,
    action        ENUM('approved','suspended','unsuspended','banned','unbanned','shadowban','unshadowban','featured','unfeatured','verified','note') NOT NULL,
    reason        TEXT NULL,
    duration_days INT  NULL,
    expires_at    DATETIME NULL,
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (shop_id)  REFERENCES shops(id) ON DELETE CASCADE,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_shop (shop_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE shop_photos (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    shop_id    INT UNSIGNED  NOT NULL,
    filename   VARCHAR(255)  NOT NULL,
    caption    VARCHAR(200)  NULL,
    sort_order TINYINT       NOT NULL DEFAULT 0,
    created_at DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (shop_id) REFERENCES shops(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE shop_hours (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    shop_id     INT UNSIGNED NOT NULL,
    day_of_week TINYINT      NOT NULL,
    opens_at    TIME         NULL,
    closes_at   TIME         NULL,
    break_start TIME         NULL,
    break_end   TIME         NULL,
    FOREIGN KEY (shop_id) REFERENCES shops(id) ON DELETE CASCADE,
    UNIQUE KEY uq_shop_day (shop_id, day_of_week)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE shop_closures (
    id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    shop_id  INT UNSIGNED NOT NULL,
    date     DATE         NOT NULL,
    reason   VARCHAR(150) NULL,
    FOREIGN KEY (shop_id) REFERENCES shops(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE employees (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    shop_id    INT UNSIGNED NOT NULL,
    user_id    INT UNSIGNED NULL,
    name       VARCHAR(100) NOT NULL,
    bio        TEXT         NULL,
    avatar     VARCHAR(255) NULL,
    specialty  VARCHAR(150) NULL,
    instagram  VARCHAR(100) NULL,
    status     ENUM('active','inactive') NOT NULL DEFAULT 'active',
    sort_order TINYINT      NOT NULL DEFAULT 0,
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (shop_id) REFERENCES shops(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE employee_hours (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id INT UNSIGNED NOT NULL,
    day_of_week TINYINT      NOT NULL,
    opens_at    TIME         NULL,
    closes_at   TIME         NULL,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    UNIQUE KEY uq_emp_day (employee_id, day_of_week)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE service_categories (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    shop_id    INT UNSIGNED NOT NULL,
    name       VARCHAR(80)  NOT NULL,
    sort_order TINYINT      NOT NULL DEFAULT 0,
    FOREIGN KEY (shop_id) REFERENCES shops(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE services (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    shop_id      INT UNSIGNED      NOT NULL,
    category_id  INT UNSIGNED      NULL,
    name         VARCHAR(120)      NOT NULL,
    description  TEXT              NULL,
    duration_min SMALLINT UNSIGNED NOT NULL DEFAULT 30,
    price        DECIMAL(10,2)     NOT NULL DEFAULT 0.00,
    deposit_pct  TINYINT UNSIGNED  NOT NULL DEFAULT 0,
    image        VARCHAR(255)      NULL,
    is_active    TINYINT(1)        NOT NULL DEFAULT 1,
    sort_order   TINYINT           NOT NULL DEFAULT 0,
    created_at   DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (shop_id)     REFERENCES shops(id)              ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES service_categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE employee_services (
    employee_id     INT UNSIGNED  NOT NULL,
    service_id      INT UNSIGNED  NOT NULL,
    custom_price    DECIMAL(10,2) NULL,
    custom_duration SMALLINT      NULL,
    PRIMARY KEY (employee_id, service_id),
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id)  REFERENCES services(id)  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE clients (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    INT UNSIGNED NULL,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL,
    phone      VARCHAR(30)  NULL,
    notes      TEXT         NULL,
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE appointments (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    shop_id          INT UNSIGNED      NOT NULL,
    employee_id      INT UNSIGNED      NOT NULL,
    service_id       INT UNSIGNED      NOT NULL,
    client_id        INT UNSIGNED      NULL,
    client_name      VARCHAR(100)      NOT NULL,
    client_email     VARCHAR(150)      NOT NULL,
    client_phone     VARCHAR(30)       NULL,
    date             DATE              NOT NULL,
    start_time       TIME              NOT NULL,
    end_time         TIME              NOT NULL,
    duration_min     SMALLINT UNSIGNED NOT NULL,
    price            DECIMAL(10,2)     NOT NULL DEFAULT 0.00,
    deposit_amount   DECIMAL(10,2)     NOT NULL DEFAULT 0.00,
    status           ENUM('pending','confirmed','cancelled_client','cancelled_shop','completed','no_show') NOT NULL DEFAULT 'pending',
    payment_option   ENUM('online','on_site') NOT NULL DEFAULT 'on_site',
    payment_status   ENUM('unpaid','deposit_paid','fully_paid','refunded') NOT NULL DEFAULT 'unpaid',
    payment_ref      VARCHAR(100) NULL,
    notes            TEXT         NULL,
    internal_notes   TEXT         NULL,
    reminder_sent    TINYINT(1)   NOT NULL DEFAULT 0,
    confirm_token    VARCHAR(80)  NULL,
    cancel_token     VARCHAR(80)  NULL,
    created_at       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (shop_id)     REFERENCES shops(id)     ON DELETE RESTRICT,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE RESTRICT,
    FOREIGN KEY (service_id)  REFERENCES services(id)  ON DELETE RESTRICT,
    FOREIGN KEY (client_id)   REFERENCES clients(id)   ON DELETE SET NULL,
    INDEX idx_date      (date),
    INDEX idx_shop_date (shop_id, date),
    INDEX idx_emp_date  (employee_id, date),
    INDEX idx_status    (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE schedule_blocks (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    shop_id     INT UNSIGNED NOT NULL,
    employee_id INT UNSIGNED NULL,
    date        DATE         NOT NULL,
    start_time  TIME         NOT NULL,
    end_time    TIME         NOT NULL,
    reason      VARCHAR(150) NULL,
    FOREIGN KEY (shop_id)     REFERENCES shops(id)     ON DELETE CASCADE,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE reviews (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    appointment_id      INT UNSIGNED     NOT NULL UNIQUE,
    shop_id             INT UNSIGNED     NOT NULL,
    client_id           INT UNSIGNED     NULL,
    rating              TINYINT UNSIGNED NOT NULL,
    rating_cleanliness  TINYINT          NULL,
    rating_punctuality  TINYINT          NULL,
    rating_value        TINYINT          NULL,
    comment             TEXT             NULL,
    reply               TEXT             NULL,
    reply_at            DATETIME         NULL,
    is_visible          TINYINT(1)       NOT NULL DEFAULT 1,
    report_count        TINYINT UNSIGNED NOT NULL DEFAULT 0,
    flagged_at          DATETIME         NULL,
    helpful_count       INT UNSIGNED     NOT NULL DEFAULT 0,
    created_at          DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE,
    FOREIGN KEY (shop_id)        REFERENCES shops(id)        ON DELETE CASCADE,
    FOREIGN KEY (client_id)      REFERENCES clients(id)      ON DELETE SET NULL,
    INDEX idx_shop (shop_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE review_reports (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    review_id  INT UNSIGNED NOT NULL,
    user_id    INT UNSIGNED NULL,
    reason     ENUM('spam','offensive','fake','irrelevant','other') NOT NULL DEFAULT 'other',
    note       VARCHAR(300) NULL,
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (review_id) REFERENCES reviews(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id)   REFERENCES users(id)   ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE favorites (
    user_id    INT UNSIGNED NOT NULL,
    shop_id    INT UNSIGNED NOT NULL,
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, shop_id),
    FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
    FOREIGN KEY (shop_id)  REFERENCES shops(id)  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE notifications (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    INT UNSIGNED NOT NULL,
    type       VARCHAR(50)  NOT NULL,
    title      VARCHAR(150) NOT NULL,
    body       TEXT         NULL,
    action_url VARCHAR(300) NULL,
    data       JSON         NULL,
    read_at    DATETIME     NULL,
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE audit_log (
    id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    INT UNSIGNED NULL,
    action     VARCHAR(80)  NOT NULL,
    entity     VARCHAR(50)  NULL,
    entity_id  INT UNSIGNED NULL,
    payload    JSON         NULL,
    ip         VARCHAR(45)  NULL,
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- DATOS DE EJEMPLO
-- ============================================================

INSERT INTO users (name,email,password_hash,role,email_verified,status) VALUES
('Super Admin','admin@trimly.com','$2y$12$zAPIJXQLQ.wLDy4yOBtF/.lnwMTZ.rX4PRT3D54cWfRxRIkn5N52W','superadmin',1,'active');

INSERT INTO users (name,email,phone,password_hash,role,email_verified,status) VALUES
('Carlos Mendez','carlos@barberia.com','+5492920123456','$2y$12$CHQ64fq7XabBAs9iFgn9nO0PoS4O37NilGbABlhszMhrYCfX/gxMy','shop_owner',1,'active');

INSERT INTO shops (owner_id,name,slug,type,target_audience,description,specialties,phone,email,address,city,province,latitude,longitude,status,plan,verified) VALUES
(2,'Barbería El Navajo','barberia-el-navajo','barbershop','men',
'La mejor barbería de Viedma. Cortes clásicos y modernos, afeitado con navaja y arreglo de barba. Atención personalizada y ambiente de primera.',
'Fade,Degradado,Barba,Navaja,Keratina,Coloración',
'+5492920111222','info@elnavajo.com',
'San Martín 450','Viedma','Río Negro',
-40.8135,-62.9967,'active','pro',1);

INSERT INTO shop_hours (shop_id,day_of_week,opens_at,closes_at) VALUES
(1,0,NULL,NULL),(1,1,'09:00','20:00'),(1,2,'09:00','20:00'),
(1,3,'09:00','20:00'),(1,4,'09:00','20:00'),(1,5,'09:00','20:00'),
(1,6,'09:00','14:00');

INSERT INTO employees (shop_id,name,bio,specialty,status,sort_order) VALUES
(1,'Miguel Torres','Barbero con 10 años de experiencia. Especialista en fade y degradados modernos.','Fade & Degradado','active',1),
(1,'Pablo Ríos','Maestro del afeitado clásico con navaja. Arreglo y diseño de barba artesanal.','Barba & Navaja','active',2),
(1,'Lucas Romero','Experto en cortes modernos, coloración y tratamientos capilares premium.','Coloración & Tratamientos','active',3);

INSERT INTO service_categories (shop_id,name,sort_order) VALUES
(1,'Corte de Cabello',1),(1,'Barba',2),(1,'Combos',3),(1,'Tratamientos',4);

INSERT INTO services (shop_id,category_id,name,description,duration_min,price,deposit_pct,is_active,sort_order) VALUES
(1,1,'Corte Clásico','Corte tradicional con tijera o máquina a elección.',30,3500.00,0,1,1),
(1,1,'Degradado / Fade','Degradado suave a cero o a skin, con acabado impecable.',45,4500.00,0,1,2),
(1,1,'Corte + Lavado','Corte incluye shampoo, acondicionador y secado.',50,5000.00,0,1,3),
(1,2,'Arreglo de Barba','Perfilado y arreglo preciso con navaja.',30,3000.00,0,1,1),
(1,2,'Afeitado Completo','Afeitado clásico con toalla caliente, espuma y navaja.',40,4000.00,0,1,2),
(1,3,'Corte + Barba','Combo completo: corte de cabello y arreglo de barba.',60,7000.00,20,1,1),
(1,3,'Corte + Barba + Ceja','Combo premium con diseño de cejas incluido.',75,8500.00,20,1,2),
(1,4,'Keratina Express','Tratamiento alisador con resultado inmediato. Dura hasta 3 meses.',90,15000.00,30,1,1),
(1,4,'Hidratación Profunda','Mascarilla nutritiva para cabello seco y dañado.',45,6000.00,0,1,2);

INSERT INTO employee_services (employee_id,service_id) VALUES
(1,1),(1,2),(1,3),(1,4),(1,6),(1,7),
(2,1),(2,3),(2,4),(2,5),(2,6),(2,7),
(3,1),(3,2),(3,3),(3,8),(3,9);

INSERT INTO employee_hours (employee_id,day_of_week,opens_at,closes_at) VALUES
(1,1,'09:00','20:00'),(1,2,'09:00','20:00'),(1,3,'09:00','20:00'),
(1,4,'09:00','20:00'),(1,5,'09:00','20:00'),(1,6,'09:00','14:00'),
(2,1,'09:00','20:00'),(2,2,'09:00','20:00'),(2,3,'09:00','20:00'),
(2,4,'09:00','20:00'),(2,5,'09:00','20:00'),(2,6,'09:00','14:00'),
(3,1,'14:00','20:00'),(3,2,'14:00','20:00'),(3,3,'14:00','20:00'),
(3,4,'14:00','20:00'),(3,5,'14:00','20:00');
