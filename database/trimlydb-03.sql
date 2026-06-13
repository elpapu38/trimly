-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 12-06-2026 a las 23:39:54
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

--
-- Volcado de datos para la tabla `appointments`
--

INSERT INTO `appointments` (`id`, `shop_id`, `employee_id`, `service_id`, `client_id`, `client_name`, `client_email`, `client_phone`, `date`, `start_time`, `end_time`, `duration_min`, `price`, `deposit_amount`, `status`, `payment_option`, `payment_status`, `payment_ref`, `notes`, `internal_notes`, `reminder_sent`, `confirm_token`, `cancel_token`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 3, 'Javier Molina', 'javier.molina@hotmail.com', '+5492920100003', '2026-05-20', '09:00:00', '09:30:00', 30, 3500.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-cancel-001', '2026-05-18 10:00:00', '2026-05-20 09:35:00'),
(2, 1, 2, 4, 1, 'Martín Acosta', 'martin.acosta@gmail.com', '+5492920100001', '2026-05-22', '10:00:00', '10:30:00', 30, 3000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-cancel-002', '2026-05-20 11:00:00', '2026-05-22 10:35:00'),
(3, 1, 1, 6, 4, 'Camila Díaz', 'camila.diaz@gmail.com', '+5492920100004', '2026-05-28', '11:00:00', '12:00:00', 60, 7000.00, 1400.00, 'completed', 'on_site', 'fully_paid', NULL, NULL, NULL, 1, NULL, 'tok-cancel-003', '2026-05-26 09:00:00', '2026-05-28 12:05:00'),
(4, 1, 3, 8, 4, 'Camila Díaz', 'camila.diaz@gmail.com', '+5492920100004', '2026-06-02', '14:00:00', '15:30:00', 90, 15000.00, 4500.00, 'completed', 'online', 'fully_paid', 'MP-123456', NULL, NULL, 1, NULL, 'tok-cancel-004', '2026-05-30 10:00:00', '2026-06-02 15:35:00'),
(5, 1, 2, 5, 3, 'Javier Molina', 'javier.molina@hotmail.com', '+5492920100003', '2026-06-04', '09:00:00', '09:40:00', 40, 4000.00, 0.00, 'no_show', 'on_site', 'unpaid', NULL, NULL, 'Cliente no se presentó sin cancelar.', 1, NULL, 'tok-cancel-005', '2026-06-02 14:00:00', '2026-06-04 10:00:00'),
(6, 1, 1, 2, 1, 'Martín Acosta', 'martin.acosta@gmail.com', '+5492920100001', '2026-06-10', '09:00:00', '09:45:00', 45, 4500.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, '', 1, 'tok-confirm-006', 'tok-cancel-006', '2026-06-08 08:00:00', '2026-06-09 02:22:25'),
(7, 1, 2, 4, 5, 'Nicolás Ferreyra', 'nico.ferreyra@gmail.com', '+5492920100005', '2026-06-10', '10:00:00', '10:30:00', 30, 3000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, '', 1, 'tok-confirm-007', 'tok-cancel-007', '2026-06-09 07:00:00', '2026-06-10 20:05:03'),
(8, 1, 3, 9, 2, 'Sofía Ruiz', 'sofia.ruiz@gmail.com', '+5492920100002', '2026-06-11', '14:00:00', '14:45:00', 45, 6000.00, 0.00, 'cancelled_shop', 'on_site', 'unpaid', NULL, 'Quiere mascarilla de keratina.', '', 0, 'tok-confirm-008', 'tok-cancel-008', '2026-06-08 15:00:00', '2026-06-11 22:22:11'),
(9, 1, 1, 7, 1, 'Martín Acosta', 'martin.acosta@gmail.com', '+5492920100001', '2026-06-12', '11:00:00', '12:15:00', 75, 8500.00, 1700.00, 'completed', 'online', 'deposit_paid', 'MP-789012', NULL, '', 1, 'tok-confirm-009', 'tok-cancel-009', '2026-06-09 08:00:00', '2026-06-12 18:28:28'),
(10, 1, 2, 6, 3, 'Javier Molina', 'javier.molina@hotmail.com', '+5492920100003', '2026-06-13', '09:00:00', '10:00:00', 60, 7000.00, 1400.00, 'cancelled_client', 'online', 'refunded', 'MP-CANCEL', NULL, NULL, 0, NULL, 'tok-cancel-010', '2026-06-07 09:00:00', '2026-06-08 10:00:00'),
(11, 2, 4, 12, 2, 'Sofía Ruiz', 'sofia.ruiz@gmail.com', '+5492920100002', '2026-05-30', '10:00:00', '11:30:00', 90, 18000.00, 5400.00, 'completed', 'online', 'fully_paid', 'MP-222001', NULL, NULL, 1, NULL, 'tok-cancel-011', '2026-05-28 09:00:00', '2026-05-30 11:35:00'),
(12, 2, 5, 14, 4, 'Camila Díaz', 'camila.diaz@gmail.com', '+5492920100004', '2026-06-05', '09:00:00', '11:00:00', 120, 22000.00, 6600.00, 'completed', 'online', 'fully_paid', 'MP-222002', 'Quiere alisado total.', NULL, 1, NULL, 'tok-cancel-012', '2026-06-03 10:00:00', '2026-06-05 11:05:00'),
(13, 2, 4, 13, 2, 'Sofía Ruiz', 'sofia.ruiz@gmail.com', '+5492920100002', '2026-06-14', '10:00:00', '12:00:00', 120, 25000.00, 7500.00, 'confirmed', 'online', 'deposit_paid', 'MP-222003', 'Balayage, fotos de referencia enviadas por WA.', NULL, 0, 'tok-confirm-013', 'tok-cancel-013', '2026-06-09 09:00:00', '2026-06-09 09:00:00'),
(14, 3, 6, 15, 4, 'Camila Díaz', 'camila.diaz@gmail.com', '+5492920100004', '2026-05-25', '14:00:00', '15:00:00', 60, 8000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-cancel-014', '2026-05-23 11:00:00', '2026-05-25 15:05:00'),
(15, 3, 7, 17, 2, 'Sofía Ruiz', 'sofia.ruiz@gmail.com', '+5492920100002', '2026-06-01', '11:00:00', '12:30:00', 90, 12000.00, 2400.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-cancel-015', '2026-05-29 10:00:00', '2026-06-01 12:35:00'),
(16, 3, 6, 19, 4, 'Camila Díaz', 'camila.diaz@gmail.com', '+5492920100004', '2026-06-15', '14:00:00', '15:00:00', 60, 7000.00, 0.00, 'pending', 'on_site', 'unpaid', NULL, 'Nail art flores japonesas.', NULL, 0, 'tok-confirm-016', 'tok-cancel-016', '2026-06-09 10:00:00', '2026-06-09 10:00:00'),
(17, 4, 8, 20, 1, 'Martín Acosta', 'martin.acosta@gmail.com', '+5492920100001', '2026-05-26', '10:00:00', '11:00:00', 60, 12000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-cancel-017', '2026-05-24 09:00:00', '2026-05-26 11:05:00'),
(18, 4, 9, 21, 5, 'Nicolás Ferreyra', 'nico.ferreyra@gmail.com', '+5492920100005', '2026-06-07', '11:00:00', '12:00:00', 60, 13000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-cancel-018', '2026-06-05 10:00:00', '2026-06-07 12:05:00'),
(19, 4, 8, 24, 2, 'Sofía Ruiz', 'sofia.ruiz@gmail.com', '+5492920100002', '2026-06-12', '09:00:00', '10:00:00', 60, 10000.00, 0.00, 'confirmed', 'on_site', 'unpaid', NULL, NULL, NULL, 0, 'tok-confirm-019', 'tok-cancel-019', '2026-06-09 11:00:00', '2026-06-09 11:00:00'),
(20, 5, 10, 26, 3, 'Javier Molina', 'javier.molina@hotmail.com', '+5492920100003', '2026-06-04', '14:00:00', '16:00:00', 120, 35000.00, 17500.00, 'completed', 'online', 'fully_paid', 'MP-555001', 'Lobo en el brazo, blackwork.', NULL, 1, NULL, 'tok-cancel-020', '2026-06-01 10:00:00', '2026-06-04 16:05:00'),
(21, 5, 11, 28, 5, 'Nicolás Ferreyra', 'nico.ferreyra@gmail.com', '+5492920100005', '2026-06-08', '12:00:00', '12:30:00', 30, 8000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Piercing helix izquierdo.', NULL, 1, NULL, 'tok-cancel-021', '2026-06-06 09:00:00', '2026-06-08 12:35:00'),
(22, 5, 10, 27, 1, 'Martín Acosta', 'martin.acosta@gmail.com', '+5492920100001', '2026-06-16', '13:00:00', '17:00:00', 240, 80000.00, 40000.00, 'confirmed', 'online', 'deposit_paid', 'MP-555002', 'Manga completa. Diseño ya aprobado.', NULL, 0, 'tok-confirm-022', 'tok-cancel-022', '2026-06-09 08:30:00', '2026-06-09 08:30:00'),
(23, 6, 12, 29, 5, 'Nicolás Ferreyra', 'nico.ferreyra@gmail.com', '+5492920100005', '2026-06-06', '10:00:00', '10:45:00', 45, 6000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-cancel-023', '2026-06-04 09:00:00', '2026-06-06 10:50:00'),
(24, 6, 12, 30, 3, 'Javier Molina', 'javier.molina@hotmail.com', '+5492920100003', '2026-06-11', '11:00:00', '12:00:00', 60, 9000.00, 0.00, 'confirmed', 'on_site', 'unpaid', NULL, NULL, NULL, 0, 'tok-confirm-024', 'tok-cancel-024', '2026-06-09 09:30:00', '2026-06-09 09:30:00'),
(25, 6, 12, 31, 2, 'Sofía Ruiz', 'sofia.ruiz@gmail.com', '+5492920100002', '2026-06-13', '14:00:00', '15:15:00', 75, 14000.00, 2800.00, 'pending', 'online', 'deposit_paid', 'MP-666001', NULL, NULL, 0, 'tok-confirm-025', 'tok-cancel-025', '2026-06-09 12:00:00', '2026-06-09 12:00:00'),
(26, 8, 13, 32, 7, 'Florencia Acosta', 'flor@gmail.com', '+5492920400002', '2026-05-19', '10:00:00', '11:30:00', 90, 12000.00, 2400.00, 'completed', 'on_site', 'fully_paid', NULL, NULL, NULL, 1, NULL, 'can_sv01', '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(27, 8, 13, 33, 6, 'Nicolás Gómez', 'nico@gmail.com', '+5492920400001', '2026-05-20', '09:00:00', '11:00:00', 120, 18000.00, 5400.00, 'completed', 'on_site', 'deposit_paid', NULL, NULL, NULL, 1, NULL, 'can_sv02', '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(28, 8, 14, 37, 9, 'Marina Castro', 'marina@gmail.com', '+5492920400004', '2026-05-21', '10:00:00', '12:00:00', 120, 22000.00, 6600.00, 'completed', 'on_site', 'deposit_paid', NULL, NULL, NULL, 1, NULL, 'can_sv03', '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(29, 8, 14, 39, 4, 'Camila Díaz', 'camila.diaz@gmail.com', '+5492920100004', '2026-05-22', '15:00:00', '16:00:00', 60, 6000.00, 0.00, 'completed', 'on_site', 'fully_paid', NULL, NULL, NULL, 1, NULL, 'can_sv04', '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(30, 8, 13, 35, 5, 'Nicolás Ferreyra', 'nico.ferreyra@gmail.com', '+5492920100005', '2026-05-23', '11:00:00', '11:45:00', 45, 5000.00, 0.00, 'completed', 'on_site', 'fully_paid', NULL, NULL, NULL, 1, NULL, 'can_sv05', '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(31, 9, 15, 41, 6, 'Nicolás Gómez', 'nico@gmail.com', '+5492920400001', '2026-05-24', '10:00:00', '11:00:00', 60, 9000.00, 0.00, 'completed', 'on_site', 'fully_paid', NULL, NULL, NULL, 1, NULL, 'can_sz01', '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(32, 9, 16, 44, 7, 'Florencia Acosta', 'flor@gmail.com', '+5492920400002', '2026-05-25', '11:00:00', '12:15:00', 75, 10000.00, 0.00, 'completed', 'on_site', 'fully_paid', NULL, NULL, NULL, 1, NULL, 'can_sz02', '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(33, 9, 15, 43, 8, 'Diego Fernández', 'diego@gmail.com', '+5492920400003', '2026-05-26', '14:00:00', '15:30:00', 90, 14000.00, 2800.00, 'completed', 'on_site', 'deposit_paid', NULL, NULL, NULL, 1, NULL, 'can_sz03', '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(34, 8, 13, 32, 7, 'Florencia Acosta', 'flor@gmail.com', '+5492920400002', '2026-06-12', '09:00:00', '10:30:00', 90, 12000.00, 2400.00, 'pending', 'on_site', 'unpaid', NULL, NULL, NULL, 0, 'tok_sv_f01', 'can_sv_f01', '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(35, 9, 15, 42, 9, 'Marina Castro', 'marina@gmail.com', '+5492920400004', '2026-06-15', '15:00:00', '16:15:00', 75, 11000.00, 0.00, 'confirmed', 'on_site', 'unpaid', NULL, NULL, NULL, 0, 'tok_sz_f01', 'can_sz_f01', '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(36, 4, 8, 20, 12, 'Cliente Test', 'cliente.test@trimly.com', '12434342423423423', '2026-06-11', '10:00:00', '11:00:00', 60, 12000.00, 0.00, 'pending', 'on_site', 'unpaid', NULL, 'corte insanoso', NULL, 0, 'e03a356b299ee892de61a868dc032d3a5fd7f862', '1c83a0750e3c1c9470d70c0b32ece1288155d883', '2026-06-10 19:55:47', '2026-06-10 19:55:47'),
(37, 8, 14, 35, 13, 'Cliente Test', 'cliente.test@trimly.com', '', '2026-06-10', '10:45:00', '11:30:00', 45, 5000.00, 0.00, 'pending', 'on_site', 'unpaid', NULL, '', NULL, 0, 'f08766a30abbbe41d70120104e44afb6fbf444f1', '713a942e875e58cd4801a17c8389ac1d7d3c855c', '2026-06-10 20:03:17', '2026-06-10 20:03:17'),
(38, 1, 1, 1, 14, 'Cliente Test', 'cliente.test@trimly.com', '', '2026-06-12', '13:30:00', '14:00:00', 30, 3500.00, 0.00, 'completed', 'online', 'unpaid', NULL, '', '', 1, '8a1fdd41f0dd574e9cd7b7bdadde474051a47853', '3700c884322d5c52a843ea9ab9bb18ad133b28a4', '2026-06-11 00:15:57', '2026-06-11 00:16:26'),
(39, 1, 17, 4, NULL, 'Cliente Test', 'cliente.test@trimly.com', '241242355', '2026-06-13', '14:00:00', '14:30:00', 30, 3000.00, 0.00, 'cancelled_shop', 'on_site', 'unpaid', NULL, 'corte barbudo', NULL, 0, '1e9ca8ec07ffb39b9fcc006199f915dac7e00ce0', '0cf4bce620a3a8b1280d7d33f2e73d2624b6964b', '2026-06-11 22:50:44', '2026-06-11 22:52:53'),
(40, 1, 17, 7, 15, 'Cliente Test', 'cliente.test@trimly.com', '', '2026-06-12', '16:45:00', '18:00:00', 75, 8500.00, 0.00, 'confirmed', 'on_site', 'unpaid', NULL, '', '', 0, 'd664234215f7306ec3fdc980cd83afac3471d91d', 'eb02726cfe512f637cb663044cf802900750dc42', '2026-06-11 23:00:11', '2026-06-12 18:32:56'),
(41, 1, 17, 4, NULL, 'carrlos', 'carlos@barberia.com', '', '2026-06-15', '14:00:00', '14:30:00', 30, 3000.00, 0.00, 'cancelled_shop', 'on_site', 'unpaid', NULL, 'assa', NULL, 0, '9575a9d54c0c1883c7076a3a886311fe4cc5355b', '71e6657cb4b55b38caf38d268f6ad810a9706701', '2026-06-11 23:17:12', '2026-06-11 23:17:26'),
(42, 1, 3, 8, 16, 'Cliente Test', 'cliente.test@trimly.com', '12434342423423423', '2026-06-15', '14:00:00', '15:30:00', 90, 15000.00, 0.00, 'pending', 'online', 'unpaid', NULL, 'corte insanoso', NULL, 0, 'ae5c8af9bfb180b4af81909be54a1eda468cef07', '7f611a43007be86d527bf95e86e625b9ccfc7499', '2026-06-12 18:18:08', '2026-06-12 18:18:08');

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

--
-- Volcado de datos para la tabla `audit_log`
--

INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity`, `entity_id`, `payload`, `ip`, `created_at`) VALUES
(1, 1, 'shop_approved', 'shop', 1, '{\"shop_name\": \"Barbería El Navajo\"}', '127.0.0.1', '2026-06-08 23:30:00'),
(2, 1, 'shop_approved', 'shop', 2, '{\"shop_name\": \"Salón Vale\"}', '127.0.0.1', '2026-06-01 12:00:00'),
(3, 1, 'shop_verified', 'shop', 2, '{\"shop_name\": \"Salón Vale\"}', '127.0.0.1', '2026-06-01 12:05:00'),
(4, 1, 'shop_featured', 'shop', 2, '{\"shop_name\": \"Salón Vale\"}', '127.0.0.1', '2026-06-01 12:10:00'),
(5, 1, 'shop_approved', 'shop', 4, '{\"shop_name\": \"Spa Fernanda\"}', '127.0.0.1', '2026-06-01 15:00:00'),
(6, 1, 'shop_featured', 'shop', 4, '{\"shop_name\": \"Spa Fernanda\"}', '127.0.0.1', '2026-06-01 15:05:00'),
(7, 1, 'user_login', 'user', 1, '{\"email\": \"admin@trimly.com\"}', '127.0.0.1', '2026-06-08 23:38:38');

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

--
-- Volcado de datos para la tabla `clients`
--

INSERT INTO `clients` (`id`, `user_id`, `name`, `email`, `phone`, `notes`, `created_at`) VALUES
(1, 10, 'Martín Acosta', 'martin.acosta@gmail.com', '+5492920100001', NULL, '2026-05-15 08:00:00'),
(2, 11, 'Sofía Ruiz', 'sofia.ruiz@gmail.com', '+5492920100002', 'Alérgica al níquel.', '2026-05-20 09:00:00'),
(3, 12, 'Javier Molina', 'javier.molina@hotmail.com', '+5492920100003', NULL, '2026-05-10 10:00:00'),
(4, 13, 'Camila Díaz', 'camila.diaz@gmail.com', '+5492920100004', NULL, '2026-05-25 11:00:00'),
(5, 14, 'Nicolás Ferreyra', 'nico.ferreyra@gmail.com', '+5492920100005', NULL, '2026-06-01 08:00:00'),
(6, 20, 'Nicolás Gómez', 'nico@gmail.com', '+5492920400001', NULL, '2026-06-10 01:39:36'),
(7, 21, 'Florencia Acosta', 'flor@gmail.com', '+5492920400002', NULL, '2026-06-10 01:39:36'),
(8, 22, 'Diego Fernández', 'diego@gmail.com', '+5492920400003', NULL, '2026-06-10 01:39:36'),
(9, 23, 'Marina Castro', 'marina@gmail.com', '+5492920400004', NULL, '2026-06-10 01:39:36'),
(10, 24, 'Tomás Ibáñez', 'tomas@gmail.com', '+5492920400005', NULL, '2026-06-10 01:39:36'),
(11, 25, 'Cliente Test', 'cliente.test@trimly.com', '+5492920500001', NULL, '2026-06-10 01:55:51'),
(12, NULL, 'Cliente Test', 'cliente.test@trimly.com', '12434342423423423', NULL, '2026-06-10 19:55:47'),
(13, NULL, 'Cliente Test', 'cliente.test@trimly.com', '', NULL, '2026-06-10 20:03:17'),
(14, NULL, 'Cliente Test', 'cliente.test@trimly.com', '', NULL, '2026-06-11 00:15:57'),
(15, NULL, 'Cliente Test', 'cliente.test@trimly.com', '', NULL, '2026-06-11 23:00:11'),
(16, NULL, 'Cliente Test', 'cliente.test@trimly.com', '12434342423423423', NULL, '2026-06-12 18:18:08');

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
(3, 1, NULL, 'Lucas Romero', 'Experto en cortes modernos, coloración y tratamientos capilares premium.', NULL, 'Coloración & Tratamientos', NULL, 'active', 3, '2026-06-08 23:26:34', '2026-06-08 23:26:34'),
(4, 2, NULL, 'Valentina Greco', 'Dueña y estilista principal. 8 años de experiencia en coloración y cortes de autor.', NULL, 'Coloración & Mechas', 'valentina.estilista', 'active', 1, '2026-06-01 09:00:00', '2026-06-01 09:00:00'),
(5, 2, NULL, 'Brenda Castillo', 'Especialista en keratina y tratamientos alisadores. También acepta trabajos de peinado.', NULL, 'Keratina & Alisado', NULL, 'active', 2, '2026-06-01 09:00:00', '2026-06-01 09:00:00'),
(6, 3, NULL, 'Ramón Ibáñez', 'Nail artist con más de 5 años de experiencia en gel y nail art.', NULL, 'Nail Art & Gel', 'nails.ramon', 'active', 1, '2026-06-02 11:00:00', '2026-06-02 11:00:00'),
(7, 3, NULL, 'Carla Medina', 'Especialista en acrílico y extensiones. Siempre al día con las últimas tendencias.', NULL, 'Acrílico & Extensiones', NULL, 'active', 2, '2026-06-02 11:00:00', '2026-06-02 11:00:00'),
(8, 4, NULL, 'Fernanda Sosa', 'Masajista terapéutica certificada. Experta en masaje sueco y drenaje linfático.', NULL, 'Masaje Terapéutico', 'spa.fernanda', 'active', 1, '2026-06-01 12:00:00', '2026-06-01 12:00:00'),
(9, 4, NULL, 'Horacio Vega', 'Especialista en masajes deportivos y tratamientos para dolores musculares.', NULL, 'Masaje Deportivo', NULL, 'active', 2, '2026-06-01 12:00:00', '2026-06-01 12:00:00'),
(10, 5, NULL, 'Diego Pereyra', 'Tatuador con 12 años en el rubro. Realismo y blackwork son su fuerte.', NULL, 'Realismo & Blackwork', 'inkdiego.official', 'active', 1, '2026-05-28 09:00:00', '2026-05-28 09:00:00'),
(11, 5, NULL, 'Antonella Quirós', 'Artista de piercings y tatuajes finos. Especialista en acuarela y lettering.', NULL, 'Acuarela & Lettering', 'anto.tattoo', 'active', 2, '2026-05-28 09:00:00', '2026-05-28 09:00:00'),
(12, 6, NULL, 'Luciana Bravo', 'Dueña y estilista. Formada en Buenos Aires y París. Cortes modernos para todos.', NULL, 'Corte & Estilo', 'mixedcuts.luci', 'active', 1, '2026-06-03 10:00:00', '2026-06-03 10:00:00'),
(13, 8, 16, 'Sofía López', 'Especialista en coloraciones y mechas con 8 años de experiencia. Apasionada por el balayage.', NULL, 'Coloración & Mechas', NULL, 'active', 1, '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(14, 8, 17, 'Antonella Vera', 'Experta en keratinas, tratamientos capilares y uñas en gel.', NULL, 'Tratamientos & Uñas', NULL, 'active', 2, '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(15, 9, 19, 'Camila Díaz', 'Masajista certificada con formación en técnicas orientales y piedras calientes.', NULL, 'Masajes Terapéuticos', NULL, 'active', 1, '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(16, 9, NULL, 'Matías Herrera', 'Especialista en tratamientos faciales y corporales. Diplomado en cosmetología.', NULL, 'Faciales & Corporales', NULL, 'active', 2, '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(17, 1, 27, 'insanos eticos', 'wazaaaaaaaaaaaa', 'employees/img_6a2b6601bd4329.47190921.png', '', 'korven__group', 'active', 0, '2026-06-11 22:28:17', '2026-06-11 22:51:15');

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
(17, 3, 5, '14:00:00', '20:00:00'),
(18, 4, 1, '09:00:00', '19:00:00'),
(19, 4, 2, '09:00:00', '19:00:00'),
(20, 4, 3, '09:00:00', '19:00:00'),
(21, 4, 4, '09:00:00', '19:00:00'),
(22, 4, 5, '09:00:00', '19:00:00'),
(23, 4, 6, '09:00:00', '14:00:00'),
(24, 5, 1, '09:00:00', '19:00:00'),
(25, 5, 2, '09:00:00', '19:00:00'),
(26, 5, 3, '09:00:00', '19:00:00'),
(27, 5, 4, '09:00:00', '19:00:00'),
(28, 5, 5, '09:00:00', '19:00:00'),
(29, 6, 2, '10:00:00', '19:00:00'),
(30, 6, 3, '10:00:00', '19:00:00'),
(31, 6, 4, '10:00:00', '19:00:00'),
(32, 6, 5, '10:00:00', '19:00:00'),
(33, 6, 6, '10:00:00', '15:00:00'),
(34, 7, 2, '10:00:00', '19:00:00'),
(35, 7, 3, '10:00:00', '19:00:00'),
(36, 7, 4, '10:00:00', '19:00:00'),
(37, 7, 5, '10:00:00', '19:00:00'),
(38, 7, 6, '10:00:00', '15:00:00'),
(39, 8, 1, '09:00:00', '20:00:00'),
(40, 8, 2, '09:00:00', '20:00:00'),
(41, 8, 3, '09:00:00', '20:00:00'),
(42, 8, 4, '09:00:00', '20:00:00'),
(43, 8, 5, '09:00:00', '20:00:00'),
(44, 8, 0, '10:00:00', '18:00:00'),
(45, 9, 1, '09:00:00', '20:00:00'),
(46, 9, 2, '09:00:00', '20:00:00'),
(47, 9, 3, '09:00:00', '20:00:00'),
(48, 9, 4, '09:00:00', '20:00:00'),
(49, 9, 5, '09:00:00', '20:00:00'),
(50, 10, 3, '12:00:00', '21:00:00'),
(51, 10, 4, '12:00:00', '21:00:00'),
(52, 10, 5, '12:00:00', '21:00:00'),
(53, 10, 6, '12:00:00', '21:00:00'),
(54, 10, 0, '12:00:00', '21:00:00'),
(55, 11, 3, '12:00:00', '21:00:00'),
(56, 11, 4, '12:00:00', '21:00:00'),
(57, 11, 5, '12:00:00', '21:00:00'),
(58, 11, 6, '12:00:00', '21:00:00'),
(59, 11, 0, '12:00:00', '21:00:00'),
(60, 12, 1, '10:00:00', '18:00:00'),
(61, 12, 2, '10:00:00', '18:00:00'),
(62, 12, 3, '10:00:00', '18:00:00'),
(63, 12, 4, '10:00:00', '18:00:00'),
(64, 12, 5, '10:00:00', '18:00:00'),
(65, 12, 6, '10:00:00', '14:00:00'),
(66, 13, 1, '09:00:00', '19:30:00'),
(67, 13, 2, '09:00:00', '19:30:00'),
(68, 13, 3, '09:00:00', '19:30:00'),
(69, 13, 4, '09:00:00', '19:30:00'),
(70, 13, 5, '09:00:00', '19:30:00'),
(71, 13, 6, '09:00:00', '14:00:00'),
(72, 14, 2, '10:00:00', '19:30:00'),
(73, 14, 3, '10:00:00', '19:30:00'),
(74, 14, 4, '10:00:00', '19:30:00'),
(75, 14, 5, '10:00:00', '19:30:00'),
(76, 14, 6, '09:00:00', '14:00:00'),
(77, 15, 1, '10:00:00', '20:00:00'),
(78, 15, 2, '10:00:00', '20:00:00'),
(79, 15, 3, '10:00:00', '20:00:00'),
(80, 15, 4, '10:00:00', '20:00:00'),
(81, 15, 5, '10:00:00', '20:00:00'),
(82, 15, 6, '10:00:00', '15:00:00'),
(83, 16, 1, '10:00:00', '20:00:00'),
(84, 16, 2, '10:00:00', '20:00:00'),
(85, 16, 3, '10:00:00', '20:00:00'),
(86, 16, 4, '10:00:00', '20:00:00'),
(87, 16, 5, '10:00:00', '20:00:00'),
(88, 17, 1, '09:00:00', '18:00:00'),
(89, 17, 2, '09:00:00', '18:00:00'),
(90, 17, 3, '09:00:00', '18:00:00'),
(91, 17, 4, '09:00:00', '18:00:00'),
(92, 17, 5, '09:00:00', '18:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `employee_photos`
--

CREATE TABLE `employee_photos` (
  `id` int UNSIGNED NOT NULL,
  `employee_id` int UNSIGNED NOT NULL,
  `filename` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `employee_photos`
--

INSERT INTO `employee_photos` (`id`, `employee_id`, `filename`, `caption`, `created_at`) VALUES
(1, 17, 'employees/img_6a2b68708159a1.35396217.png', 'miren mi gran trabajo', '2026-06-11 23:01:20'),
(2, 17, 'employees/img_6a2b68807edee2.36861377.png', 'taperfade clasicooo para este campeooon', '2026-06-11 23:01:36'),
(3, 17, 'employees/img_6a2b697467e325.72640183.png', '', '2026-06-11 23:05:40'),
(4, 17, 'employees/img_6a2b697990a209.64659582.png', '', '2026-06-11 23:05:45'),
(5, 17, 'employees/img_6a2b697d85c6b2.44291810.png', '', '2026-06-11 23:05:49'),
(6, 17, 'employees/img_6a2b6983284054.61701515.png', '', '2026-06-11 23:05:55'),
(7, 17, 'employees/img_6a2b6987ef4bc4.21706294.png', '', '2026-06-11 23:05:59'),
(8, 17, 'employees/img_6a2b698c1268f9.04417447.png', '', '2026-06-11 23:06:04'),
(9, 17, 'employees/img_6a2c7b53eca339.12168594.png', '', '2026-06-12 18:34:11'),
(10, 17, 'employees/img_6a2c7b5829eae2.91255937.png', '', '2026-06-12 18:34:16'),
(11, 17, 'employees/img_6a2c7b5db0e4f2.70380238.png', '', '2026-06-12 18:34:21'),
(12, 17, 'employees/img_6a2c7b61550892.35646698.png', '', '2026-06-12 18:34:25'),
(13, 17, 'employees/img_6a2c7b65631298.57119339.png', '', '2026-06-12 18:34:29'),
(14, 17, 'employees/img_6a2c7b70573985.06725645.png', '', '2026-06-12 18:34:40'),
(15, 17, 'employees/img_6a2c7b7486ac92.38249040.png', '', '2026-06-12 18:34:44'),
(16, 17, 'employees/img_6a2c7b781fbdb9.25986279.png', '', '2026-06-12 18:34:48');

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
(3, 9, NULL, NULL),
(4, 10, NULL, NULL),
(4, 11, NULL, NULL),
(4, 12, NULL, NULL),
(4, 13, NULL, NULL),
(4, 14, NULL, NULL),
(5, 10, NULL, NULL),
(5, 11, NULL, NULL),
(5, 14, NULL, NULL),
(6, 15, NULL, NULL),
(6, 16, NULL, NULL),
(6, 18, NULL, NULL),
(6, 19, NULL, NULL),
(7, 15, NULL, NULL),
(7, 16, NULL, NULL),
(7, 17, NULL, NULL),
(7, 18, NULL, NULL),
(7, 19, NULL, NULL),
(8, 20, NULL, NULL),
(8, 22, NULL, NULL),
(8, 23, NULL, NULL),
(8, 24, NULL, NULL),
(9, 20, NULL, NULL),
(9, 21, NULL, NULL),
(9, 22, NULL, NULL),
(9, 23, NULL, NULL),
(10, 25, NULL, NULL),
(10, 26, NULL, NULL),
(10, 27, NULL, NULL),
(11, 25, NULL, NULL),
(11, 26, NULL, NULL),
(11, 28, NULL, NULL),
(12, 29, NULL, NULL),
(12, 30, NULL, NULL),
(12, 31, NULL, NULL),
(13, 32, NULL, NULL),
(13, 33, NULL, NULL),
(13, 34, NULL, NULL),
(13, 35, NULL, NULL),
(13, 36, NULL, NULL),
(14, 35, NULL, NULL),
(14, 37, NULL, NULL),
(14, 38, NULL, NULL),
(14, 39, NULL, NULL),
(14, 40, NULL, NULL),
(15, 41, NULL, NULL),
(15, 42, NULL, NULL),
(15, 43, NULL, NULL),
(16, 44, NULL, NULL),
(16, 45, NULL, NULL),
(16, 46, NULL, NULL),
(17, 1, NULL, NULL),
(17, 2, NULL, NULL),
(17, 3, NULL, NULL),
(17, 4, NULL, NULL),
(17, 5, NULL, NULL),
(17, 6, NULL, NULL),
(17, 7, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favorites`
--

CREATE TABLE `favorites` (
  `user_id` int UNSIGNED NOT NULL,
  `shop_id` int UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `favorites`
--

INSERT INTO `favorites` (`user_id`, `shop_id`, `created_at`) VALUES
(10, 1, '2026-05-21 09:00:00'),
(10, 4, '2026-05-27 10:00:00'),
(10, 5, '2026-06-05 08:00:00'),
(11, 2, '2026-05-31 10:00:00'),
(11, 3, '2026-06-01 21:00:00'),
(12, 1, '2026-05-23 10:00:00'),
(13, 2, '2026-06-06 11:00:00'),
(13, 3, '2026-05-26 10:00:00'),
(14, 5, '2026-06-09 07:30:00'),
(14, 6, '2026-06-07 10:00:00');

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

--
-- Volcado de datos para la tabla `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `body`, `action_url`, `data`, `read_at`, `created_at`) VALUES
(1, 10, 'booking_confirmed', 'Turno confirmado', 'Tu turno en Barbería El Navajo para el 10/06 a las 09:00 fue confirmado.', '/mis-turnos', NULL, '2026-06-08 09:00:00', '2026-06-08 08:05:00'),
(2, 10, 'reminder', 'Recordatorio de turno', 'Mañana tenés turno en Barbería El Navajo a las 09:00. ¡No lo olvides!', '/mis-turnos', NULL, NULL, '2026-06-09 08:00:00'),
(3, 11, 'booking_confirmed', 'Turno confirmado', 'Tu turno en Salón Vale para el 14/06 a las 10:00 fue confirmado.', '/mis-turnos', NULL, NULL, '2026-06-09 09:05:00'),
(4, 14, 'booking_new', 'Nuevo turno recibido', 'Nicolás Ferreyra reservó Corte Clásico para el 10/06 a las 10:00.', '/panel/turnos', NULL, '2026-06-09 07:30:00', '2026-06-09 07:02:00'),
(5, 5, 'review_new', 'Nueva reseña en tu local', 'Sofía Ruiz dejó 5 estrellas en Salón Vale. ¡Excelente trabajo!', '/panel/resenas', NULL, NULL, '2026-05-30 20:05:00'),
(6, 8, 'review_new', 'Nueva reseña en tu local', 'Javier Molina dejó 5 estrellas en Ink & Diego Tattoo.', '/panel/resenas', NULL, NULL, '2026-06-04 21:05:00'),
(7, 2, 'appointment_cancelled', 'Turno cancelado', 'Javier Molina canceló su turno del 13/06 a las 09:00.', '/panel/turnos', NULL, '2026-06-08 11:00:00', '2026-06-08 10:02:00'),
(8, 1, 'shop_pending', 'Nuevo local pendiente', 'Barbería del Norte fue registrada y espera aprobación.', '/admin/locales', NULL, '2026-06-09 02:05:00', '2026-06-09 02:01:00');

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

--
-- Volcado de datos para la tabla `reviews`
--

INSERT INTO `reviews` (`id`, `appointment_id`, `shop_id`, `client_id`, `rating`, `rating_cleanliness`, `rating_punctuality`, `rating_value`, `comment`, `reply`, `reply_at`, `is_visible`, `report_count`, `flagged_at`, `helpful_count`, `created_at`) VALUES
(1, 1, 1, 3, 5, 5, 5, 5, 'Excelente corte, Miguel es un genio con el fade. Me dejó exactamente como quería. Vuelvo seguro.', 'Gracias Javier! Te esperamos.', '2026-05-21 10:00:00', 1, 0, NULL, 3, '2026-05-20 20:00:00'),
(2, 2, 1, 1, 4, 4, 5, 4, 'Muy buen servicio. Pablo dominó la barba de diez. Solo tardó un poco más de lo esperado.', NULL, NULL, 1, 0, NULL, 1, '2026-05-22 18:00:00'),
(3, 3, 1, 4, 5, 5, 5, 5, 'El combo corte+barba es imbatible. Los chicos son profesionales de verdad. Recomiendo 100%.', 'Muchas gracias Camila! Siempre un placer.', '2026-05-29 09:00:00', 1, 0, NULL, 5, '2026-05-28 20:00:00'),
(4, 4, 1, 4, 5, 5, 4, 4, 'La keratina quedó increíble, Lucas sabe lo que hace. Solo el precio es un poco alto pero vale la pena.', 'Gracias por tu confianza! Te esperamos.', '2026-06-03 10:00:00', 1, 0, NULL, 2, '2026-06-02 21:00:00'),
(5, 11, 2, 2, 5, 5, 5, 4, 'Valentina hizo la coloración perfecta. El salón es hermoso y muy limpio. Precio justo para la calidad.', 'Gracias Sofía! Fue un placer tenerte.', '2026-05-31 10:00:00', 1, 0, NULL, 4, '2026-05-30 19:00:00'),
(6, 12, 2, 4, 4, 5, 4, 3, 'La keratina quedó muy bien. Brenda es amable y prolija. Me pareció algo caro pero el resultado justifica.', NULL, NULL, 1, 0, NULL, 1, '2026-06-05 20:00:00'),
(7, 14, 3, 4, 5, 5, 5, 5, 'Ramón es un artista. Gel impecable, duró más de 3 semanas sin despegarse. Ya saqué otro turno.', 'Gracias Camila! Tu preferencia nos da mucha alegría.', '2026-05-26 10:00:00', 1, 0, NULL, 6, '2026-05-25 21:00:00'),
(8, 15, 3, 2, 5, 5, 5, 4, 'Carla es excelente con el acrílico. El resultado fue hermoso y muy duradero. Muy recomendada!', NULL, NULL, 1, 0, NULL, 2, '2026-06-01 20:00:00'),
(9, 17, 4, 1, 5, 5, 5, 5, 'El masaje sueco de Fernanda fue lo mejor que me pasó en meses. El ambiente es relajante y el trato excelente.', 'Gracias Martín! Te esperamos pronto.', '2026-05-27 10:00:00', 1, 0, NULL, 7, '2026-05-26 19:00:00'),
(10, 18, 4, 5, 5, 5, 5, 4, 'Masaje deportivo top. Horacio sabe dónde están los nudos. Salí nuevo. El único pero: sacar turno cuesta un poco.', NULL, NULL, 1, 0, NULL, 3, '2026-06-07 20:00:00'),
(11, 20, 5, 3, 5, 5, 4, 4, 'Diego tatuó exactamente lo que pedí. El blackwork quedó increíble. Muy prolijo y el lugar está impecable.', 'Gracias Javier! Fue un placer. A ver la cicatrización!', '2026-06-05 10:00:00', 1, 0, NULL, 8, '2026-06-04 21:00:00'),
(12, 21, 5, 5, 4, 5, 5, 4, 'Anto hizo el piercing perfecto, rapidísimo y sin casi dolor. El cuidado post piercing fue explicado al detalle.', NULL, NULL, 1, 0, NULL, 2, '2026-06-08 20:00:00'),
(13, 23, 6, 5, 4, 4, 4, 5, 'Luci cortó genial, muy buen precio para Bariloche. El estudio tiene una vista hermosa. Vuelvo cuando regrese.', 'Gracias Nicolás! Cuando vuelvas te esperamos!', '2026-06-07 10:00:00', 1, 0, NULL, 1, '2026-06-06 19:00:00'),
(14, 26, 8, 7, 5, 5, 5, 5, 'Sofía es una artista. La coloración quedó increíble, mejor que en Buenos Aires.', 'Gracias Florencia! Te esperamos pronto.', '2026-06-10 01:39:36', 1, 0, NULL, 4, '2026-06-10 01:39:36'),
(15, 27, 8, 6, 5, 5, 5, 4, 'El balayage quedó natural y hermoso. Voy a volver seguro!', NULL, NULL, 1, 0, NULL, 2, '2026-06-10 01:39:36'),
(16, 28, 8, 9, 4, 5, 4, 3, 'La keratina quedó muy bien, pero tardó más de lo prometido.', NULL, NULL, 1, 0, NULL, 1, '2026-06-10 01:39:36'),
(17, 29, 8, 4, 2, 3, 4, 2, 'El gel me duró solo 5 días. Muy decepcionante para el precio.', 'Lamentamos tu experiencia. Te contactaremos para resolverlo.', '2026-06-10 01:39:36', 1, 0, NULL, 0, '2026-06-10 01:39:36'),
(18, 30, 8, 5, 5, 5, 5, 5, 'Corte perfecto, muy buen ambiente y precio justo.', NULL, NULL, 1, 0, NULL, 3, '2026-06-10 01:39:36'),
(19, 31, 9, 6, 5, 5, 5, 5, 'El masaje relajante fue lo mejor que probé. Me fui renovado.', 'Gracias Nicolás! Te esperamos pronto.', '2026-06-10 01:39:36', 1, 0, NULL, 5, '2026-06-10 01:39:36'),
(20, 32, 9, 7, 4, 5, 5, 4, 'Muy profesional el tratamiento. El ambiente es súper tranquilizador.', NULL, NULL, 1, 0, NULL, 2, '2026-06-10 01:39:36'),
(21, 33, 9, 8, 5, 5, 5, 4, 'Las piedras calientes son una experiencia única. Volvería mil veces.', 'Gracias Diego! Fue un placer atenderte.', '2026-06-10 01:39:36', 1, 0, NULL, 6, '2026-06-10 01:39:36'),
(22, 38, 1, 14, 3, NULL, NULL, NULL, 'assa', 'gracias por tu observacion, esperamos que vuelvas pronto', NULL, 1, 0, NULL, 0, '2026-06-11 00:16:54');

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

--
-- Volcado de datos para la tabla `review_reports`
--

INSERT INTO `review_reports` (`id`, `review_id`, `user_id`, `reason`, `note`, `created_at`) VALUES
(1, 17, 20, 'fake', 'El gel dura poco si el cliente no lo cuida.', '2026-06-10 01:39:36'),
(2, 17, 21, 'offensive', 'Comentario injusto y agresivo.', '2026-06-10 01:39:36'),
(3, 17, 22, 'spam', NULL, '2026-06-10 01:39:36'),
(4, 13, 25, 'spam', NULL, '2026-06-11 00:18:03');

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

--
-- Volcado de datos para la tabla `schedule_blocks`
--

INSERT INTO `schedule_blocks` (`id`, `shop_id`, `employee_id`, `date`, `start_time`, `end_time`, `reason`) VALUES
(1, 1, 3, '2026-06-10', '09:00:00', '14:00:00', 'Reservado para evento privado'),
(2, 4, NULL, '2026-06-13', '00:00:00', '23:59:00', 'Feriado local — spa cerrado'),
(3, 5, 10, '2026-06-15', '12:00:00', '15:00:00', 'Reunión de diseño con cliente');

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
(9, 1, 4, 'Hidratación Profunda', 'Mascarilla nutritiva para cabello seco y dañado.', 45, 6000.00, 0, NULL, 1, 2, '2026-06-08 23:26:34', '2026-06-08 23:26:34'),
(10, 2, 5, 'Corte Femenino', 'Corte con tijera, lavado y secado incluido.', 45, 5500.00, 0, NULL, 1, 1, '2026-06-01 09:00:00', '2026-06-01 09:00:00'),
(11, 2, 5, 'Peinado para evento', 'Peinado elaborado para fiestas, bodas o reuniones especiales.', 60, 9000.00, 20, NULL, 1, 2, '2026-06-01 09:00:00', '2026-06-01 09:00:00'),
(12, 2, 6, 'Coloración completa', 'Tintura completa con marcas de primera calidad.', 90, 18000.00, 30, NULL, 1, 1, '2026-06-01 09:00:00', '2026-06-01 09:00:00'),
(13, 2, 6, 'Mechas', 'Mechas californianas o balayage a elección.', 120, 25000.00, 30, NULL, 1, 2, '2026-06-01 09:00:00', '2026-06-01 09:00:00'),
(14, 2, 7, 'Keratina Brasileña', 'Alisado definitivo. Dura hasta 4 meses. Incluye lavado y secado.', 120, 22000.00, 30, NULL, 1, 1, '2026-06-01 09:00:00', '2026-06-01 09:00:00'),
(15, 3, 8, 'Gel completo', 'Esmaltado en gel para uñas naturales. Dura 3 semanas.', 60, 8000.00, 0, NULL, 1, 1, '2026-06-02 11:00:00', '2026-06-02 11:00:00'),
(16, 3, 8, 'Retoque gel', 'Relleno y retoque de esmaltado en gel.', 40, 5000.00, 0, NULL, 1, 2, '2026-06-02 11:00:00', '2026-06-02 11:00:00'),
(17, 3, 9, 'Acrílico completo', 'Extensión o escultura en acrílico. Resultado duradero.', 90, 12000.00, 20, NULL, 1, 1, '2026-06-02 11:00:00', '2026-06-02 11:00:00'),
(18, 3, 10, 'Nail Art básico', 'Diseño artístico sencillo en hasta 2 uñas.', 30, 3000.00, 0, NULL, 1, 1, '2026-06-02 11:00:00', '2026-06-02 11:00:00'),
(19, 3, 10, 'Nail Art completo', 'Diseño artístico en las 10 uñas. Consultar diseño.', 60, 7000.00, 0, NULL, 1, 2, '2026-06-02 11:00:00', '2026-06-02 11:00:00'),
(20, 4, 11, 'Masaje Sueco', 'Masaje relajante de cuerpo completo. 60 minutos de bienestar.', 60, 12000.00, 0, NULL, 1, 1, '2026-06-01 12:00:00', '2026-06-01 12:00:00'),
(21, 4, 11, 'Masaje Deportivo', 'Descontracturante para recuperación muscular. Ideal post-entreno.', 60, 13000.00, 0, NULL, 1, 2, '2026-06-01 12:00:00', '2026-06-01 12:00:00'),
(22, 4, 11, 'Masaje de 30 min', 'Versión express del masaje sueco para zonas específicas.', 30, 7500.00, 0, NULL, 1, 3, '2026-06-01 12:00:00', '2026-06-01 12:00:00'),
(23, 4, 12, 'Exfoliación corporal', 'Exfoliación con sales y aceites naturales. Renueva la piel.', 50, 9000.00, 0, NULL, 1, 1, '2026-06-01 12:00:00', '2026-06-01 12:00:00'),
(24, 4, 13, 'Facial Hidratante', 'Limpieza + hidratación profunda con productos veganos.', 60, 10000.00, 0, NULL, 1, 1, '2026-06-01 12:00:00', '2026-06-01 12:00:00'),
(25, 5, 14, 'Tatuaje pequeño', 'Hasta 5cm. Precio base, puede variar según complejidad. Consulta gratis.', 60, 15000.00, 50, NULL, 1, 1, '2026-05-28 09:00:00', '2026-05-28 09:00:00'),
(26, 5, 14, 'Tatuaje mediano', '5 a 15cm. Precio base. Consulta el diseño antes de reservar.', 120, 35000.00, 50, NULL, 1, 2, '2026-05-28 09:00:00', '2026-05-28 09:00:00'),
(27, 5, 14, 'Tatuaje grande', 'Más de 15cm o piezas elaboradas. Sesión completa.', 240, 80000.00, 50, NULL, 1, 3, '2026-05-28 09:00:00', '2026-05-28 09:00:00'),
(28, 5, 15, 'Piercing simple', 'Oreja, nariz o labio. Incluye joya de titanio hipoalergénica.', 30, 8000.00, 0, NULL, 1, 1, '2026-05-28 09:00:00', '2026-05-28 09:00:00'),
(29, 6, 16, 'Corte Unisex', 'Corte adaptado a cualquier tipo de cabello y estilo.', 45, 6000.00, 0, NULL, 1, 1, '2026-06-03 10:00:00', '2026-06-03 10:00:00'),
(30, 6, 16, 'Corte + Barba', 'Corte completo más arreglo y perfilado de barba.', 60, 9000.00, 0, NULL, 1, 2, '2026-06-03 10:00:00', '2026-06-03 10:00:00'),
(31, 6, 17, 'Coloración express', 'Tintura rápida, un solo tono.', 75, 14000.00, 20, NULL, 1, 1, '2026-06-03 10:00:00', '2026-06-03 10:00:00'),
(32, 8, 18, 'Coloración Completa', 'Coloración de raíz a puntas con productos Schwarzkopf.', 90, 12000.00, 20, NULL, 1, 1, '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(33, 8, 18, 'Mechas Balayage', 'Técnica balayage con efecto natural degradado.', 120, 18000.00, 30, NULL, 1, 2, '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(34, 8, 18, 'Retoque de Raíz', 'Retoque de crecimiento de raíz.', 60, 8000.00, 0, NULL, 1, 3, '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(35, 8, 19, 'Corte de Cabello', 'Corte con lavado y secado incluido.', 45, 5000.00, 0, NULL, 1, 1, '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(36, 8, 19, 'Peinado para Evento', 'Peinado profesional para fiestas y eventos.', 60, 9000.00, 0, NULL, 1, 2, '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(37, 8, 20, 'Keratina Brasileña', 'Alisado semipermanente con keratina de alta calidad.', 120, 22000.00, 30, NULL, 1, 1, '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(38, 8, 20, 'Hidratación Profunda', 'Mascarilla nutritiva con proteínas y keratina.', 50, 7000.00, 0, NULL, 1, 2, '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(39, 8, 21, 'Manicura Gel', 'Esmalte en gel con duración de hasta 3 semanas.', 60, 6000.00, 0, NULL, 1, 1, '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(40, 8, 21, 'Pedicura Completa', 'Pedicura con exfoliación y esmalte a elección.', 75, 7500.00, 0, NULL, 1, 2, '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(41, 9, 22, 'Masaje Relajante', 'Masaje de cuerpo completo con aceites esenciales.', 60, 9000.00, 0, NULL, 1, 1, '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(42, 9, 22, 'Masaje Descontracturante', 'Técnica profunda para liberar tensiones musculares.', 75, 11000.00, 0, NULL, 1, 2, '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(43, 9, 22, 'Masaje con Piedras Calientes', 'Terapia con basalto volcánico y aceites.', 90, 14000.00, 20, NULL, 1, 3, '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(44, 9, 23, 'Limpieza Facial Profunda', 'Limpieza + extracción + mascarilla + hidratación.', 75, 10000.00, 0, NULL, 1, 1, '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(45, 9, 23, 'Facial Vitamina C', 'Tratamiento antioxidante iluminador.', 60, 12000.00, 0, NULL, 1, 2, '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(46, 9, 24, 'Envoltura Corporal', 'Envoltura con barro y algas marinas.', 90, 13000.00, 20, NULL, 1, 1, '2026-06-10 01:39:36', '2026-06-10 01:39:36');

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
(4, 1, 'Tratamientos', 4),
(5, 2, 'Corte & Peinado', 1),
(6, 2, 'Coloración', 2),
(7, 2, 'Tratamientos', 3),
(8, 3, 'Uñas Gel', 1),
(9, 3, 'Uñas Acrílico', 2),
(10, 3, 'Nail Art', 3),
(11, 4, 'Masajes', 1),
(12, 4, 'Tratamientos Corporales', 2),
(13, 4, 'Faciales', 3),
(14, 5, 'Tatuajes', 1),
(15, 5, 'Piercings', 2),
(16, 6, 'Corte', 1),
(17, 6, 'Color & Tratamiento', 2),
(18, 8, 'Coloración', 1),
(19, 8, 'Corte & Peinado', 2),
(20, 8, 'Tratamientos', 3),
(21, 8, 'Uñas', 4),
(22, 9, 'Masajes', 1),
(23, 9, 'Faciales', 2),
(24, 9, 'Corporales', 3);

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
(1, 2, 'Barbería El Navajo', 'barberia-el-navajo', 'barbershop', 'men', 'La mejor barbería de Viedma. Cortes clásicos y modernos, afeitado con navaja y arreglo de barba. Atención personalizada y ambiente de primera.', 'Fade,Degradado,Barba,Navaja,Keratina,Coloración', '', '+5492920111222', 'info@elnavajo.com', '', '', '', '', 'shops/img_6a2a2cb9c62672.68205480.png', 'shops/img_6a27a3d927b3c3.66540191.jpg', 'Domingo De Oro 561', 'Patagones', 'Buenos Aires', 'Argentina', '8504', -40.7950575, -62.9618096, 'ARS', 4.40, 5, 'active', 'pro', NULL, 1, 0, 19, 0, NULL, NULL, NULL, '2026-06-08 23:26:34', '2026-06-12 18:48:43'),
(2, 5, 'Salón Vale', 'salon-vale', 'salon', 'women', 'Salón de belleza femenino con ambiente moderno y acogedor. Especialistas en coloración, corte y tratamientos capilares.', 'Coloración,Mechas,Keratina,Corte,Peinado', 'WiFi,Estacionamiento,Café gratis', '+5492920334455', 'vale@salonvale.com', NULL, 'salon.vale', NULL, '+5492920334455', NULL, NULL, 'Av. Rivadavia 1200', 'Viedma', 'Río Negro', 'Argentina', '8500', -40.8122000, -62.9935000, 'ARS', 4.50, 2, 'active', 'pro', '2027-01-01', 1, 1, 91, 0, NULL, NULL, NULL, '2026-06-01 09:00:00', '2026-06-11 23:30:49'),
(3, 6, 'Nails by Ramón', 'nails-by-ramon', 'nails', 'women', 'Estudio de uñas profesional. Gel, acrílico, nail art y más. Resultados que duran.', 'Gel,Acrílico,Nail Art,Semipermanente,Decoración', 'Música ambiente,Té y café', '+5492920445566', 'ramon@nailsramon.com', NULL, 'nails.ramon', NULL, '+5492920445566', NULL, NULL, 'Saavedra 340', 'Viedma', 'Río Negro', 'Argentina', '8500', -40.8098000, -62.9910000, 'ARS', 5.00, 2, 'active', 'free', NULL, 0, 0, 45, 0, NULL, NULL, NULL, '2026-06-02 11:00:00', '2026-06-11 23:30:54'),
(4, 7, 'Spa Fernanda', 'spa-fernanda', 'spa', 'unisex', 'Espacio de bienestar y relajación. Masajes, aromaterapia y tratamientos corporales para desconectarte del estrés.', 'Masajes,Aromaterapia,Exfoliación,Tratamientos Faciales', 'Estacionamiento,Ducha,Música relajante,Aromas', '+5492920556677', 'fer@spafernanda.com', NULL, 'spafernanda', NULL, '+5492920556677', NULL, NULL, 'Belgrano 870', 'Patagones', 'Buenos Aires', 'Argentina', '8504', -40.7967000, -62.9815000, 'ARS', 5.00, 2, 'active', 'premium', '2027-06-01', 1, 1, 137, 0, NULL, NULL, NULL, '2026-06-01 12:00:00', '2026-06-11 00:17:07'),
(5, 8, 'Ink & Diego Tattoo', 'ink-diego-tattoo', 'tattoo', 'unisex', 'Estudio de tatuajes y piercings en Buenos Aires. Diseños únicos, máxima higiene. Consulta sin cargo.', 'Realismo,Blackwork,Acuarela,Piercings,Lettering', 'Portfolio digital,Zona de espera,Música', '+5491166778899', 'diego@inkdiego.com', 'https://inkdiego.com.ar', 'inkdiego.tattoo', NULL, '+5491166778899', NULL, NULL, 'Corrientes 4500', 'Buenos Aires', 'CABA', 'Argentina', '1195', -34.6037000, -58.4116000, 'ARS', 4.50, 2, 'active', 'pro', '2026-12-01', 1, 0, 213, 0, NULL, NULL, NULL, '2026-05-28 09:00:00', '2026-06-11 23:26:58'),
(6, 9, 'MixedCuts Luciana', 'mixedcuts-luciana', 'mixed', 'unisex', 'Peluquería unisex en Bariloche. Cortes modernos para todos. Sin distinción de género, solo buen trabajo.', 'Corte,Color,Barba,Peinado,Tratamientos', 'WiFi,Vista al lago,Ambiente cálido', '+5492920667788', 'luci@mixedluci.com', NULL, 'mixedcuts.bariloche', NULL, '+5492920667788', NULL, NULL, 'Mitre 200', 'Bariloche', 'Río Negro', 'Argentina', '8400', -41.1335000, -71.3103000, 'ARS', 4.00, 1, 'active', 'free', NULL, 1, 0, 72, 0, NULL, NULL, NULL, '2026-06-03 10:00:00', '2026-06-12 18:49:07'),
(7, 3, 'Barbería del Norte', 'barberia-del-norte', 'barbershop', 'men', 'Nueva barbería en el norte de Viedma. Cortes modernos y clásicos a precios accesibles.', 'Fade,Corte Clásico,Barba', NULL, '+5492920256833', 'ejemplo1@ejemplo.com', NULL, NULL, NULL, NULL, NULL, NULL, 'Av. Norte 123', 'Viedma', 'Río Negro', 'Argentina', '8500', -40.7980000, -62.9850000, 'ARS', 0.00, 0, 'active', 'free', NULL, 1, 0, 1, 0, NULL, NULL, NULL, '2026-06-09 02:00:00', '2026-06-10 19:22:10'),
(8, 15, 'Salón Valentina', 'salon-valentina', 'salon', 'women', 'El salón de belleza más completo de Viedma. Coloraciones, mechas, tratamientos y mucho más.', 'Coloración,Mechas,Keratina,Corte,Peinado', 'WiFi,Café gratis', '+5492920222001', 'vale@salonvalentina.com', NULL, NULL, NULL, NULL, NULL, NULL, 'Av. Zatti 820', 'Viedma', 'Río Negro', 'Argentina', '8500', -40.8110000, -62.9940000, 'ARS', 4.20, 5, 'active', 'pro', '2027-06-01', 1, 1, 49, 0, NULL, NULL, NULL, '2026-06-10 01:39:35', '2026-06-11 22:21:39'),
(9, 18, 'Spa Zen', 'spa-zen', 'spa', 'women', 'Espacio de relajación y bienestar. Masajes, tratamientos faciales y aromaterapia.', 'Masajes,Aromaterapia,Tratamientos Faciales,Corporales', 'Música relajante,Aromas', '+5492920333001', 'rod@spazen.com', NULL, NULL, NULL, NULL, NULL, NULL, 'Colón 1245', 'Viedma', 'Río Negro', 'Argentina', '8500', -40.8155000, -62.9995000, 'ARS', 4.67, 3, 'active', 'free', NULL, 1, 0, 26, 0, NULL, NULL, NULL, '2026-06-10 01:39:35', '2026-06-12 18:18:14'),
(10, 26, 'Peluquería Test', 'peluqueria-test', 'mixed', 'unisex', '', '', '', '+5492920500002', 'negocio.test@trimly.com', '', '', '', '', NULL, NULL, '', 'Viedma', 'Río Negro', 'Argentina', '', NULL, NULL, 'ARS', 0.00, 0, 'pending', 'free', NULL, 0, 0, 0, 0, NULL, NULL, NULL, '2026-06-10 01:55:51', '2026-06-10 01:57:58');

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

--
-- Volcado de datos para la tabla `shop_closures`
--

INSERT INTO `shop_closures` (`id`, `shop_id`, `date`, `reason`) VALUES
(1, 2, '2026-06-19', 'Feriado nacional — local cerrado'),
(2, 3, '2026-06-20', 'Vacaciones de Ramón'),
(3, 3, '2026-06-21', 'Vacaciones de Ramón'),
(4, 6, '2026-06-17', 'Evento local Bariloche — cierre anticipado');

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
(7, 1, 6, '09:00:00', '14:00:00', NULL, NULL),
(8, 2, 0, NULL, NULL, NULL, NULL),
(9, 2, 1, '09:00:00', '19:00:00', NULL, NULL),
(10, 2, 2, '09:00:00', '19:00:00', NULL, NULL),
(11, 2, 3, '09:00:00', '19:00:00', NULL, NULL),
(12, 2, 4, '09:00:00', '19:00:00', NULL, NULL),
(13, 2, 5, '09:00:00', '19:00:00', NULL, NULL),
(14, 2, 6, '09:00:00', '14:00:00', NULL, NULL),
(15, 3, 0, NULL, NULL, NULL, NULL),
(16, 3, 1, NULL, NULL, NULL, NULL),
(17, 3, 2, '10:00:00', '19:00:00', NULL, NULL),
(18, 3, 3, '10:00:00', '19:00:00', NULL, NULL),
(19, 3, 4, '10:00:00', '19:00:00', NULL, NULL),
(20, 3, 5, '10:00:00', '19:00:00', NULL, NULL),
(21, 3, 6, '10:00:00', '15:00:00', NULL, NULL),
(22, 4, 0, '10:00:00', '18:00:00', NULL, NULL),
(23, 4, 1, '09:00:00', '20:00:00', '13:00:00', '14:00:00'),
(24, 4, 2, '09:00:00', '20:00:00', '13:00:00', '14:00:00'),
(25, 4, 3, '09:00:00', '20:00:00', '13:00:00', '14:00:00'),
(26, 4, 4, '09:00:00', '20:00:00', '13:00:00', '14:00:00'),
(27, 4, 5, '09:00:00', '20:00:00', '13:00:00', '14:00:00'),
(28, 4, 6, '10:00:00', '16:00:00', NULL, NULL),
(29, 5, 0, '12:00:00', '21:00:00', NULL, NULL),
(30, 5, 1, NULL, NULL, NULL, NULL),
(31, 5, 2, NULL, NULL, NULL, NULL),
(32, 5, 3, '12:00:00', '21:00:00', NULL, NULL),
(33, 5, 4, '12:00:00', '21:00:00', NULL, NULL),
(34, 5, 5, '12:00:00', '21:00:00', NULL, NULL),
(35, 5, 6, '12:00:00', '21:00:00', NULL, NULL),
(36, 6, 0, NULL, NULL, NULL, NULL),
(37, 6, 1, '10:00:00', '18:00:00', NULL, NULL),
(38, 6, 2, '10:00:00', '18:00:00', NULL, NULL),
(39, 6, 3, '10:00:00', '18:00:00', NULL, NULL),
(40, 6, 4, '10:00:00', '18:00:00', NULL, NULL),
(41, 6, 5, '10:00:00', '18:00:00', NULL, NULL),
(42, 6, 6, '10:00:00', '14:00:00', NULL, NULL),
(43, 8, 0, NULL, NULL, NULL, NULL),
(44, 8, 1, '09:00:00', '19:30:00', NULL, NULL),
(45, 8, 2, '09:00:00', '19:30:00', NULL, NULL),
(46, 8, 3, '09:00:00', '19:30:00', NULL, NULL),
(47, 8, 4, '09:00:00', '19:30:00', NULL, NULL),
(48, 8, 5, '09:00:00', '19:30:00', NULL, NULL),
(49, 8, 6, '09:00:00', '14:00:00', NULL, NULL),
(50, 9, 0, NULL, NULL, NULL, NULL),
(51, 9, 1, '10:00:00', '20:00:00', NULL, NULL),
(52, 9, 2, '10:00:00', '20:00:00', NULL, NULL),
(53, 9, 3, '10:00:00', '20:00:00', NULL, NULL),
(54, 9, 4, '10:00:00', '20:00:00', NULL, NULL),
(55, 9, 5, '10:00:00', '20:00:00', NULL, NULL),
(56, 9, 6, '10:00:00', '15:00:00', NULL, NULL),
(57, 10, 0, '09:00:00', '20:00:00', '00:00:00', '00:00:00'),
(58, 10, 1, '09:00:00', '20:00:00', '00:00:00', '00:00:00'),
(59, 10, 2, '09:00:00', '20:00:00', '00:00:00', '00:00:00'),
(60, 10, 3, '09:00:00', '20:00:00', '00:00:00', '00:00:00'),
(61, 10, 4, '09:00:00', '20:00:00', '00:00:00', '00:00:00'),
(62, 10, 5, '09:00:00', '20:00:00', '00:00:00', '00:00:00'),
(63, 10, 6, '09:00:00', '20:00:00', '00:00:00', '00:00:00');

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

--
-- Volcado de datos para la tabla `shop_moderation_log`
--

INSERT INTO `shop_moderation_log` (`id`, `shop_id`, `admin_id`, `action`, `reason`, `duration_days`, `expires_at`, `created_at`) VALUES
(1, 7, 1, 'approved', NULL, NULL, NULL, '2026-06-09 02:18:01'),
(2, 7, 1, 'verified', NULL, NULL, NULL, '2026-06-09 02:18:11'),
(3, 3, 1, 'featured', NULL, NULL, NULL, '2026-06-09 02:18:25'),
(4, 3, 1, 'unfeatured', NULL, NULL, NULL, '2026-06-09 02:18:41');

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

--
-- Volcado de datos para la tabla `shop_photos`
--

INSERT INTO `shop_photos` (`id`, `shop_id`, `filename`, `caption`, `sort_order`, `created_at`) VALUES
(3, 2, 'shops/salon-vale-1.jpg', 'Salón principal', 1, '2026-06-01 10:00:00'),
(4, 2, 'shops/salon-vale-2.jpg', 'Zona de coloración', 2, '2026-06-01 10:00:00'),
(5, 3, 'shops/nails-ramon-1.jpg', 'Muestra de trabajo', 1, '2026-06-02 12:00:00'),
(6, 4, 'shops/spa-fernanda-1.jpg', 'Sala de masajes', 1, '2026-06-01 13:00:00'),
(7, 4, 'shops/spa-fernanda-2.jpg', 'Sala de tratamientos', 2, '2026-06-01 13:00:00'),
(8, 5, 'shops/ink-diego-1.jpg', 'Portfolio realismo', 1, '2026-05-28 10:00:00'),
(9, 5, 'shops/ink-diego-2.jpg', 'Portfolio blackwork', 2, '2026-05-28 10:00:00'),
(10, 6, 'shops/mixedcuts-1.jpg', 'Local con vista al lago', 1, '2026-06-03 11:00:00'),
(11, 1, 'shops/img_6a27a34ac6ade1.27614490.jpg', '', 1, '2026-06-09 02:23:22'),
(12, 1, 'shops/img_6a27a3583b48d3.59311863.jpg', '', 2, '2026-06-09 02:23:36'),
(13, 1, 'shops/img_6a27a35f1b6c31.21809281.png', '', 3, '2026-06-09 02:23:43'),
(14, 1, 'shops/img_6a2a27d9a7a684.92709229.png', '', 4, '2026-06-11 00:13:29'),
(15, 1, 'shops/img_6a2a27eba99497.34192667.png', '', 5, '2026-06-11 00:13:47'),
(16, 1, 'shops/img_6a2a27f722a947.58361975.png', '', 6, '2026-06-11 00:13:59'),
(17, 1, 'shops/img_6a2a27f72f21b5.12956998.png', '', 7, '2026-06-11 00:13:59'),
(18, 1, 'shops/img_6a2a27f733ad31.79002941.png', '', 8, '2026-06-11 00:13:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shop_reports`
--

CREATE TABLE `shop_reports` (
  `id` int UNSIGNED NOT NULL,
  `shop_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `reason` enum('spam','fake','offensive','closed','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `note` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `shop_reports`
--

INSERT INTO `shop_reports` (`id`, `shop_id`, `user_id`, `reason`, `note`, `created_at`) VALUES
(1, 1, 2, 'fake', NULL, '2026-06-11 00:15:19');

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
(1, 'Super Admin', 'admin@trimly.com', NULL, '$2y$12$zAPIJXQLQ.wLDy4yOBtF/.lnwMTZ.rX4PRT3D54cWfRxRIkn5N52W', 'superadmin', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-12 18:47:10', '2026-06-08 23:26:34', '2026-06-12 18:47:10'),
(2, 'Carlos Mendez', 'carlos@barberia.com', '+5492920123456', '$2y$12$CHQ64fq7XabBAs9iFgn9nO0PoS4O37NilGbABlhszMhrYCfX/gxMy', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-12 18:40:55', '2026-06-08 23:26:34', '2026-06-12 18:40:55'),
(3, 'SAD', 'ejemplo1@ejemplo.com', '5492920256833', '$2y$12$mFn/kGOa89IsbAskVIoRW.xyx3FivZtlmn.0.ckKNr9X1x0K1x5y6', 'shop_owner', NULL, 1, '0bd224f3f8d0b1bd037da67bed8ed22c1e9a89ac8a6a6c16e4b5ea7ccde54a8b', NULL, NULL, 'active', NULL, '2026-06-09 02:00:06', '2026-06-11 22:47:17'),
(4, 'Pablo Ríos', 'waza@waza.com', '5492920256834', '$2y$12$H6HK9b/iI2d4uOs2jZ9ZAu8YaJUYDggjl0MrrOpNxgUkd7mHSZ/pK', 'shop_owner', NULL, 0, 'bdd3b0cfa8f4353f44b5f9064b9c4867183ed55013a03f2ef7ea2edc63c12c2e', NULL, NULL, 'active', NULL, '2026-06-09 02:01:10', '2026-06-09 02:01:10'),
(5, 'Valentina Greco', 'vale@salonvale.com', '+5492920334455', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-08 10:00:00', '2026-06-01 09:00:00', '2026-06-08 10:00:00'),
(6, 'Ramón Ibáñez', 'ramon@nailsramon.com', '+5492920445566', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-07 14:00:00', '2026-06-02 11:00:00', '2026-06-07 14:00:00'),
(7, 'Fernanda Sosa', 'fer@spafernanda.com', '+5492920556677', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-06 16:30:00', '2026-06-01 12:00:00', '2026-06-06 16:30:00'),
(8, 'Diego Pereyra', 'diego@inkdiego.com', '+5491166778899', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-05 18:00:00', '2026-05-28 09:00:00', '2026-06-05 18:00:00'),
(9, 'Luciana Bravo', 'luci@mixedluci.com', '+5492920667788', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-09 08:00:00', '2026-06-03 10:00:00', '2026-06-09 08:00:00'),
(10, 'Martín Acosta', 'martin.acosta@gmail.com', '+5492920100001', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-09 09:00:00', '2026-05-15 08:00:00', '2026-06-09 09:00:00'),
(11, 'Sofía Ruiz', 'sofia.ruiz@gmail.com', '+5492920100002', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-08 11:00:00', '2026-05-20 09:00:00', '2026-06-08 11:00:00'),
(12, 'Javier Molina', 'javier.molina@hotmail.com', '+5492920100003', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-07 10:00:00', '2026-05-10 10:00:00', '2026-06-07 10:00:00'),
(13, 'Camila Díaz', 'camila.diaz@gmail.com', '+5492920100004', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-08 15:00:00', '2026-05-25 11:00:00', '2026-06-08 15:00:00'),
(14, 'Nicolás Ferreyra', 'nico.ferreyra@gmail.com', '+5492920100005', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-09 07:30:00', '2026-06-01 08:00:00', '2026-06-11 22:56:12'),
(15, 'Valentina Ruiz', 'vale@salonvalentina.com', '+5492920222001', '$2y$12$sPbsZy3n2kLvwPPuTyXdKuZ6C7C3PaNzZkHfNJ95VJheFHVXaBzgu', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-10 01:28:09', '2026-06-10 01:28:09'),
(16, 'Sofía López', 'sofia@salonvalentina.com', '+5492920222002', '$2y$12$sPbsZy3n2kLvwPPuTyXdKuZ6C7C3PaNzZkHfNJ95VJheFHVXaBzgu', 'employee', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-10 01:28:09', '2026-06-10 01:28:09'),
(17, 'Antonella Vera', 'anto@salonvalentina.com', '+5492920222003', '$2y$12$sPbsZy3n2kLvwPPuTyXdKuZ6C7C3PaNzZkHfNJ95VJheFHVXaBzgu', 'employee', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-10 01:28:09', '2026-06-10 01:28:09'),
(18, 'Rodrigo Paz', 'rod@spazen.com', '+5492920333001', '$2y$12$sPbsZy3n2kLvwPPuTyXdKuZ6C7C3PaNzZkHfNJ95VJheFHVXaBzgu', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-10 01:28:09', '2026-06-10 01:28:09'),
(19, 'Camila Díaz', 'cami@spazen.com', '+5492920333002', '$2y$12$sPbsZy3n2kLvwPPuTyXdKuZ6C7C3PaNzZkHfNJ95VJheFHVXaBzgu', 'employee', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-10 01:28:09', '2026-06-10 01:28:09'),
(20, 'Nicolás Gómez', 'nico@gmail.com', '+5492920400001', '$2y$12$sPbsZy3n2kLvwPPuTyXdKuZ6C7C3PaNzZkHfNJ95VJheFHVXaBzgu', 'client', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-10 01:28:09', '2026-06-10 01:28:09'),
(21, 'Florencia Acosta', 'flor@gmail.com', '+5492920400002', '$2y$12$sPbsZy3n2kLvwPPuTyXdKuZ6C7C3PaNzZkHfNJ95VJheFHVXaBzgu', 'client', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-10 01:28:09', '2026-06-10 01:28:09'),
(22, 'Diego Fernández', 'diego@gmail.com', '+5492920400003', '$2y$12$sPbsZy3n2kLvwPPuTyXdKuZ6C7C3PaNzZkHfNJ95VJheFHVXaBzgu', 'client', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-10 01:28:09', '2026-06-10 01:28:09'),
(23, 'Marina Castro', 'marina@gmail.com', '+5492920400004', '$2y$12$sPbsZy3n2kLvwPPuTyXdKuZ6C7C3PaNzZkHfNJ95VJheFHVXaBzgu', 'client', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-10 01:28:09', '2026-06-10 01:28:09'),
(24, 'Tomás Ibáñez', 'tomas@gmail.com', '+5492920400005', '$2y$12$sPbsZy3n2kLvwPPuTyXdKuZ6C7C3PaNzZkHfNJ95VJheFHVXaBzgu', 'client', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-10 01:28:09', '2026-06-10 01:28:09'),
(25, 'Cliente Test', 'cliente.test@trimly.com', '', '$2y$12$5YzClYLvAK80/NOSMh9udO2G80RCqxVtv5qnPT1xPM8ucctNNCyae', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-12 18:21:16', '2026-06-10 01:55:51', '2026-06-12 18:21:16'),
(26, 'Negocio Test', 'negocio.test@trimly.com', '+5492920500002', '$2y$12$YSJd8YC63E3PgaXdc9bIGeF1.ok1OUxnUZn57buIoBduvx4lC3Ya2', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-10 02:00:59', '2026-06-10 01:55:51', '2026-06-10 02:00:59'),
(27, 'insanos eticos', 'lucas1@gmail.com', '+5492920111222', '$2y$12$hCUgrYqWVzBIFLM/ttTKu.BOwM93r2X59Z9Xycctq9Jx4LLEmZ0k.', 'employee', NULL, 0, '2b8cab181624c1346175c16e4881c4d14902b33472cd4db1eb658215e50ebeff', NULL, NULL, 'active', '2026-06-12 18:33:17', '2026-06-11 22:46:30', '2026-06-12 18:40:32');

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
-- Indices de la tabla `employee_photos`
--
ALTER TABLE `employee_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_employee` (`employee_id`);

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
-- Indices de la tabla `shop_reports`
--
ALTER TABLE `shop_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_shop` (`shop_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_reason` (`reason`);

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `employee_hours`
--
ALTER TABLE `employee_hours`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT de la tabla `employee_photos`
--
ALTER TABLE `employee_photos`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `review_reports`
--
ALTER TABLE `review_reports`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `schedule_blocks`
--
ALTER TABLE `schedule_blocks`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `services`
--
ALTER TABLE `services`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `shop_closures`
--
ALTER TABLE `shop_closures`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `shop_hours`
--
ALTER TABLE `shop_hours`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de la tabla `shop_moderation_log`
--
ALTER TABLE `shop_moderation_log`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `shop_photos`
--
ALTER TABLE `shop_photos`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `shop_reports`
--
ALTER TABLE `shop_reports`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

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
-- Filtros para la tabla `employee_photos`
--
ALTER TABLE `employee_photos`
  ADD CONSTRAINT `employee_photos_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

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

--
-- Filtros para la tabla `shop_reports`
--
ALTER TABLE `shop_reports`
  ADD CONSTRAINT `shop_reports_ibfk_1` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shop_reports_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
