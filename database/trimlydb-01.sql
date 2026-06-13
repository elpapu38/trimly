-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 09-06-2026 a las 05:01:48
-- Versión del servidor: 8.4.3
-- Versión de PHP: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `trimly`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `appointments`
--

CREATE TABLE `appointments` (
  `id` int UNSIGNED NOT NULL,
  `shop_id` int UNSIGNED NOT NULL,
  `employee_id` int UNSIGNED NOT NULL,
  `service_id` int UNSIGNED NOT NULL,
  `client_id` int UNSIGNED DEFAULT NULL,
  `client_name` varchar(100) NOT NULL,
  `client_email` varchar(150) NOT NULL,
  `client_phone` varchar(30) DEFAULT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `duration_min` smallint UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `deposit_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','confirmed','cancelled_client','cancelled_shop','completed','no_show') NOT NULL DEFAULT 'pending',
  `payment_option` enum('online','on_site') NOT NULL DEFAULT 'on_site',
  `payment_status` enum('unpaid','deposit_paid','fully_paid','refunded') NOT NULL DEFAULT 'unpaid',
  `payment_ref` varchar(100) DEFAULT NULL,
  `notes` text,
  `internal_notes` text,
  `reminder_sent` tinyint(1) NOT NULL DEFAULT '0',
  `confirm_token` varchar(80) DEFAULT NULL,
  `cancel_token` varchar(80) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `audit_log`
--

CREATE TABLE `audit_log` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `action` varchar(80) NOT NULL,
  `entity` varchar(50) DEFAULT NULL,
  `entity_id` int UNSIGNED DEFAULT NULL,
  `payload` json DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clients`
--

CREATE TABLE `clients` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `employees`
--

CREATE TABLE `employees` (
  `id` int UNSIGNED NOT NULL,
  `shop_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `bio` text,
  `avatar` varchar(255) DEFAULT NULL,
  `specialty` varchar(150) DEFAULT NULL,
  `instagram` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `sort_order` tinyint NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `employees`
--

INSERT INTO `employees` (`id`, `shop_id`, `user_id`, `name`, `bio`, `avatar`, `specialty`, `instagram`, `status`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Miguel Torres', 'Barbero con 10 años de experiencia. Especialista en fade y degradados modernos.', NULL, 'Fade & Degradado', NULL, 'active', 1, '2026-06-08 23:26:34', '2026-06-08 23:26:34'),
(2, 1, NULL, 'Pablo Ríos', 'Maestro del afeitado clásico con navaja. Arreglo y diseño de barba artesanal.', NULL, 'Barba & Navaja', NULL, 'active', 2, '2026-06-08 23:26:34', '2026-06-08 23:26:34'),
(3, 1, NULL, 'Lucas Romero', 'Experto en cortes modernos, coloración y tratamientos capilares premium.', NULL, 'Coloración & Tratamientos', NULL, 'active', 3, '2026-06-08 23:26:34', '2026-06-08 23:26:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `employee_hours`
--

CREATE TABLE `employee_hours` (
  `id` int UNSIGNED NOT NULL,
  `employee_id` int UNSIGNED NOT NULL,
  `day_of_week` tinyint NOT NULL,
  `opens_at` time DEFAULT NULL,
  `closes_at` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `employee_hours`
--

INSERT INTO `employee_hours` (`id`, `employee_id`, `day_of_week`, `opens_at`, `closes_at`) VALUES
(1, 1, 1, '09:00:00', '20:00:00'),
(2, 1, 2, '09:00:00', '20:00:00'),
(3, 1, 3, '09:00:00', '20:00:00'),
(4, 1, 4, '09:00:00', '20:00:00'),
(5, 1, 5, '09:00:00', '20:00:00'),
(6, 1, 6, '09:00:00', '14:00:00'),
(7, 2, 1, '09:00:00', '20:00:00'),
(8, 2, 2, '09:00:00', '20:00:00'),
(9, 2, 3, '09:00:00', '20:00:00'),
(10, 2, 4, '09:00:00', '20:00:00'),
(11, 2, 5, '09:00:00', '20:00:00'),
(12, 2, 6, '09:00:00', '14:00:00'),
(13, 3, 1, '14:00:00', '20:00:00'),
(14, 3, 2, '14:00:00', '20:00:00'),
(15, 3, 3, '14:00:00', '20:00:00'),
(16, 3, 4, '14:00:00', '20:00:00'),
(17, 3, 5, '14:00:00', '20:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `employee_services`
--

CREATE TABLE `employee_services` (
  `employee_id` int UNSIGNED NOT NULL,
  `service_id` int UNSIGNED NOT NULL,
  `custom_price` decimal(10,2) DEFAULT NULL,
  `custom_duration` smallint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `employee_services`
--

INSERT INTO `employee_services` (`employee_id`, `service_id`, `custom_price`, `custom_duration`) VALUES
(1, 1, NULL, NULL),
(1, 2, NULL, NULL),
(1, 3, NULL, NULL),
(1, 4, NULL, NULL),
(1, 6, NULL, NULL),
(1, 7, NULL, NULL),
(2, 1, NULL, NULL),
(2, 3, NULL, NULL),
(2, 4, NULL, NULL),
(2, 5, NULL, NULL),
(2, 6, NULL, NULL),
(2, 7, NULL, NULL),
(3, 1, NULL, NULL),
(3, 2, NULL, NULL),
(3, 3, NULL, NULL),
(3, 8, NULL, NULL),
(3, 9, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favorites`
--

CREATE TABLE `favorites` (
  `user_id` int UNSIGNED NOT NULL,
  `shop_id` int UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notifications`
--

CREATE TABLE `notifications` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(150) NOT NULL,
  `body` text,
  `action_url` varchar(300) DEFAULT NULL,
  `data` json DEFAULT NULL,
  `read_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reviews`
--

CREATE TABLE `reviews` (
  `id` int UNSIGNED NOT NULL,
  `appointment_id` int UNSIGNED NOT NULL,
  `shop_id` int UNSIGNED NOT NULL,
  `client_id` int UNSIGNED DEFAULT NULL,
  `rating` tinyint UNSIGNED NOT NULL,
  `rating_cleanliness` tinyint DEFAULT NULL,
  `rating_punctuality` tinyint DEFAULT NULL,
  `rating_value` tinyint DEFAULT NULL,
  `comment` text,
  `reply` text,
  `reply_at` datetime DEFAULT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  `report_count` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `flagged_at` datetime DEFAULT NULL,
  `helpful_count` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `review_reports`
--

CREATE TABLE `review_reports` (
  `id` int UNSIGNED NOT NULL,
  `review_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `reason` enum('spam','offensive','fake','irrelevant','other') NOT NULL DEFAULT 'other',
  `note` varchar(300) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `schedule_blocks`
--

CREATE TABLE `schedule_blocks` (
  `id` int UNSIGNED NOT NULL,
  `shop_id` int UNSIGNED NOT NULL,
  `employee_id` int UNSIGNED DEFAULT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `reason` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `services`
--

CREATE TABLE `services` (
  `id` int UNSIGNED NOT NULL,
  `shop_id` int UNSIGNED NOT NULL,
  `category_id` int UNSIGNED DEFAULT NULL,
  `name` varchar(120) NOT NULL,
  `description` text,
  `duration_min` smallint UNSIGNED NOT NULL DEFAULT '30',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `deposit_pct` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` tinyint NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `services`
--

INSERT INTO `services` (`id`, `shop_id`, `category_id`, `name`, `description`, `duration_min`, `price`, `deposit_pct`, `image`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Corte Clásico', 'Corte tradicional con tijera o máquina a elección.', 30, 3500.00, 0, NULL, 1, 1, '2026-06-08 23:26:34', '2026-06-08 23:26:34'),
(2, 1, 1, 'Degradado / Fade', 'Degradado suave a cero o a skin, con acabado impecable.', 45, 4500.00, 0, NULL, 1, 2, '2026-06-08 23:26:34', '2026-06-08 23:26:34'),
(3, 1, 1, 'Corte + Lavado', 'Corte incluye shampoo, acondicionador y secado.', 50, 5000.00, 0, NULL, 1, 3, '2026-06-08 23:26:34', '2026-06-08 23:26:34'),
(4, 1, 2, 'Arreglo de Barba', 'Perfilado y arreglo preciso con navaja.', 30, 3000.00, 0, NULL, 1, 1, '2026-06-08 23:26:34', '2026-06-08 23:26:34'),
(5, 1, 2, 'Afeitado Completo', 'Afeitado clásico con toalla caliente, espuma y navaja.', 40, 4000.00, 0, NULL, 1, 2, '2026-06-08 23:26:34', '2026-06-08 23:26:34'),
(6, 1, 3, 'Corte + Barba', 'Combo completo: corte de cabello y arreglo de barba.', 60, 7000.00, 20, NULL, 1, 1, '2026-06-08 23:26:34', '2026-06-08 23:26:34'),
(7, 1, 3, 'Corte + Barba + Ceja', 'Combo premium con diseño de cejas incluido.', 75, 8500.00, 20, NULL, 1, 2, '2026-06-08 23:26:34', '2026-06-08 23:26:34'),
(8, 1, 4, 'Keratina Express', 'Tratamiento alisador con resultado inmediato. Dura hasta 3 meses.', 90, 15000.00, 30, NULL, 1, 1, '2026-06-08 23:26:34', '2026-06-08 23:26:34'),
(9, 1, 4, 'Hidratación Profunda', 'Mascarilla nutritiva para cabello seco y dañado.', 45, 6000.00, 0, NULL, 1, 2, '2026-06-08 23:26:34', '2026-06-08 23:26:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `service_categories`
--

CREATE TABLE `service_categories` (
  `id` int UNSIGNED NOT NULL,
  `shop_id` int UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `sort_order` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `service_categories`
--

INSERT INTO `service_categories` (`id`, `shop_id`, `name`, `sort_order`) VALUES
(1, 1, 'Corte de Cabello', 1),
(2, 1, 'Barba', 2),
(3, 1, 'Combos', 3),
(4, 1, 'Tratamientos', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shops`
--

CREATE TABLE `shops` (
  `id` int UNSIGNED NOT NULL,
  `owner_id` int UNSIGNED NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(130) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('barbershop','salon','mixed','nails','spa','tattoo','makeup','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'barbershop',
  `target_audience` enum('men','women','unisex') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unisex',
  `description` text COLLATE utf8mb4_unicode_ci,
  `specialties` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amenities` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Argentina',
  `postal_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `currency` char(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ARS',
  `rating_avg` decimal(3,2) NOT NULL DEFAULT '0.00',
  `rating_count` int UNSIGNED NOT NULL DEFAULT '0',
  `status` enum('pending','active','suspended','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `plan` enum('free','pro','premium') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'free',
  `plan_expires` date DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `views_count` int UNSIGNED NOT NULL DEFAULT '0',
  `is_shadowbanned` tinyint(1) NOT NULL DEFAULT '0',
  `suspension_reason` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `suspension_until` datetime DEFAULT NULL,
  `ban_reason` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `shops`
--

INSERT INTO `shops` (`id`, `owner_id`, `name`, `slug`, `type`, `target_audience`, `description`, `specialties`, `amenities`, `phone`, `email`, `website`, `instagram`, `facebook`, `whatsapp`, `logo`, `cover_image`, `address`, `city`, `province`, `country`, `postal_code`, `latitude`, `longitude`, `currency`, `rating_avg`, `rating_count`, `status`, `plan`, `plan_expires`, `verified`, `featured`, `views_count`, `is_shadowbanned`, `suspension_reason`, `suspension_until`, `ban_reason`, `created_at`, `updated_at`) VALUES
(1, 2, 'Barbería El Navajo', 'barberia-el-navajo', 'barbershop', 'men', 'La mejor barbería de Viedma. Cortes clásicos y modernos, afeitado con navaja y arreglo de barba. Atención personalizada y ambiente de primera.', 'Fade,Degradado,Barba,Navaja,Keratina,Coloración', NULL, '+5492920111222', 'info@elnavajo.com', NULL, NULL, NULL, NULL, NULL, NULL, 'San Martín 450', 'Viedma', 'Río Negro', 'Argentina', NULL, -40.8135000, -62.9967000, 'ARS', 0.00, 0, 'active', 'pro', NULL, 1, 0, 3, 0, NULL, NULL, NULL, '2026-06-08 23:26:34', '2026-06-09 01:57:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shop_closures`
--

CREATE TABLE `shop_closures` (
  `id` int UNSIGNED NOT NULL,
  `shop_id` int UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `reason` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shop_hours`
--

CREATE TABLE `shop_hours` (
  `id` int UNSIGNED NOT NULL,
  `shop_id` int UNSIGNED NOT NULL,
  `day_of_week` tinyint NOT NULL,
  `opens_at` time DEFAULT NULL,
  `closes_at` time DEFAULT NULL,
  `break_start` time DEFAULT NULL,
  `break_end` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `shop_hours`
--

INSERT INTO `shop_hours` (`id`, `shop_id`, `day_of_week`, `opens_at`, `closes_at`, `break_start`, `break_end`) VALUES
(1, 1, 0, NULL, NULL, NULL, NULL),
(2, 1, 1, '09:00:00', '20:00:00', NULL, NULL),
(3, 1, 2, '09:00:00', '20:00:00', NULL, NULL),
(4, 1, 3, '09:00:00', '20:00:00', NULL, NULL),
(5, 1, 4, '09:00:00', '20:00:00', NULL, NULL),
(6, 1, 5, '09:00:00', '20:00:00', NULL, NULL),
(7, 1, 6, '09:00:00', '14:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shop_moderation_log`
--

CREATE TABLE `shop_moderation_log` (
  `id` int UNSIGNED NOT NULL,
  `shop_id` int UNSIGNED NOT NULL,
  `admin_id` int UNSIGNED NOT NULL,
  `action` enum('approved','suspended','unsuspended','banned','unbanned','shadowban','unshadowban','featured','unfeatured','verified','note') NOT NULL,
  `reason` text,
  `duration_days` int DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shop_photos`
--

CREATE TABLE `shop_photos` (
  `id` int UNSIGNED NOT NULL,
  `shop_id` int UNSIGNED NOT NULL,
  `filename` varchar(255) NOT NULL,
  `caption` varchar(200) DEFAULT NULL,
  `sort_order` tinyint NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('superadmin','shop_owner','employee','client') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'client',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT '0',
  `verify_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `status` enum('active','suspended','banned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password_hash`, `role`, `avatar`, `email_verified`, `verify_token`, `reset_token`, `reset_expires`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@trimly.com', NULL, '$2y$12$zAPIJXQLQ.wLDy4yOBtF/.lnwMTZ.rX4PRT3D54cWfRxRIkn5N52W', 'superadmin', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-08 23:38:38', '2026-06-08 23:26:34', '2026-06-08 23:38:38'),
(2, 'Carlos Mendez', 'carlos@barberia.com', '+5492920123456', '$2y$12$CHQ64fq7XabBAs9iFgn9nO0PoS4O37NilGbABlhszMhrYCfX/gxMy', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-08 23:26:34', '2026-06-08 23:26:34'),
(3, 'SAD', 'ejemplo1@ejemplo.com', '5492920256833', '$2y$12$mFn/kGOa89IsbAskVIoRW.xyx3FivZtlmn.0.ckKNr9X1x0K1x5y6', 'shop_owner', NULL, 0, '0bd224f3f8d0b1bd037da67bed8ed22c1e9a89ac8a6a6c16e4b5ea7ccde54a8b', NULL, NULL, 'active', NULL, '2026-06-09 02:00:06', '2026-06-09 02:00:06'),
(4, 'Pablo Ríos', 'waza@waza.com', '5492920256834', '$2y$12$H6HK9b/iI2d4uOs2jZ9ZAu8YaJUYDggjl0MrrOpNxgUkd7mHSZ/pK', 'shop_owner', NULL, 0, 'bdd3b0cfa8f4353f44b5f9064b9c4867183ed55013a03f2ef7ea2edc63c12c2e', NULL, NULL, 'active', NULL, '2026-06-09 02:01:10', '2026-06-09 02:01:10');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `idx_date` (`date`),
  ADD KEY `idx_shop_date` (`shop_id`,`date`),
  ADD KEY `idx_emp_date` (`employee_id`,`date`),
  ADD KEY `idx_status` (`status`);

--
-- Indices de la tabla `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_email` (`email`);

--
-- Indices de la tabla `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_id` (`shop_id`);

--
-- Indices de la tabla `employee_hours`
--
ALTER TABLE `employee_hours`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_emp_day` (`employee_id`,`day_of_week`);

--
-- Indices de la tabla `employee_services`
--
ALTER TABLE `employee_services`
  ADD PRIMARY KEY (`employee_id`,`service_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indices de la tabla `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`user_id`,`shop_id`),
  ADD KEY `shop_id` (`shop_id`);

--
-- Indices de la tabla `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `appointment_id` (`appointment_id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `idx_shop` (`shop_id`);

--
-- Indices de la tabla `review_reports`
--
ALTER TABLE `review_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `review_id` (`review_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `schedule_blocks`
--
ALTER TABLE `schedule_blocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_id` (`shop_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indices de la tabla `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_id` (`shop_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indices de la tabla `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_id` (`shop_id`);

--
-- Indices de la tabla `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `owner_id` (`owner_id`),
  ADD KEY `idx_city` (`city`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_status` (`status`);

--
-- Indices de la tabla `shop_closures`
--
ALTER TABLE `shop_closures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_id` (`shop_id`);

--
-- Indices de la tabla `shop_hours`
--
ALTER TABLE `shop_hours`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_shop_day` (`shop_id`,`day_of_week`);

--
-- Indices de la tabla `shop_moderation_log`
--
ALTER TABLE `shop_moderation_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `idx_shop` (`shop_id`);

--
-- Indices de la tabla `shop_photos`
--
ALTER TABLE `shop_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_id` (`shop_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `employee_hours`
--
ALTER TABLE `employee_hours`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `review_reports`
--
ALTER TABLE `review_reports`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `schedule_blocks`
--
ALTER TABLE `schedule_blocks`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `services`
--
ALTER TABLE `services`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `shop_closures`
--
ALTER TABLE `shop_closures`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `shop_hours`
--
ALTER TABLE `shop_hours`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `shop_moderation_log`
--
ALTER TABLE `shop_moderation_log`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `shop_photos`
--
ALTER TABLE `shop_photos`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `appointments_ibfk_4` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `employee_hours`
--
ALTER TABLE `employee_hours`
  ADD CONSTRAINT `employee_hours_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `employee_services`
--
ALTER TABLE `employee_services`
  ADD CONSTRAINT `employee_services_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `review_reports`
--
ALTER TABLE `review_reports`
  ADD CONSTRAINT `review_reports_ibfk_1` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_reports_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `schedule_blocks`
--
ALTER TABLE `schedule_blocks`
  ADD CONSTRAINT `schedule_blocks_ibfk_1` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedule_blocks_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `services_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `service_categories`
--
ALTER TABLE `service_categories`
  ADD CONSTRAINT `service_categories_ibfk_1` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `shops`
--
ALTER TABLE `shops`
  ADD CONSTRAINT `shops_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

--
-- Filtros para la tabla `shop_closures`
--
ALTER TABLE `shop_closures`
  ADD CONSTRAINT `shop_closures_ibfk_1` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `shop_hours`
--
ALTER TABLE `shop_hours`
  ADD CONSTRAINT `shop_hours_ibfk_1` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `shop_moderation_log`
--
ALTER TABLE `shop_moderation_log`
  ADD CONSTRAINT `shop_moderation_log_ibfk_1` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shop_moderation_log_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

--
-- Filtros para la tabla `shop_photos`
--
ALTER TABLE `shop_photos`
  ADD CONSTRAINT `shop_photos_ibfk_1` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
