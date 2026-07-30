-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 22-06-2026 a las 21:01:03
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
  `payment_option` enum('online','on_site','deposit') NOT NULL DEFAULT 'on_site',
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
(40, 1, 17, 7, 15, 'Cliente Test', 'cliente.test@trimly.com', '', '2026-06-12', '16:45:00', '18:00:00', 75, 8500.00, 0.00, 'cancelled_shop', 'on_site', 'unpaid', NULL, '', '', 0, 'd664234215f7306ec3fdc980cd83afac3471d91d', 'eb02726cfe512f637cb663044cf802900750dc42', '2026-06-11 23:00:11', '2026-06-14 20:25:22'),
(41, 1, 17, 4, NULL, 'carrlos', 'carlos@barberia.com', '', '2026-06-15', '14:00:00', '14:30:00', 30, 3000.00, 0.00, 'cancelled_shop', 'on_site', 'unpaid', NULL, 'assa', NULL, 0, '9575a9d54c0c1883c7076a3a886311fe4cc5355b', '71e6657cb4b55b38caf38d268f6ad810a9706701', '2026-06-11 23:17:12', '2026-06-11 23:17:26'),
(42, 1, 3, 8, 16, 'Cliente Test', 'cliente.test@trimly.com', '12434342423423423', '2026-06-15', '14:00:00', '15:30:00', 90, 15000.00, 0.00, 'pending', 'online', 'unpaid', NULL, 'corte insanoso', NULL, 0, 'ae5c8af9bfb180b4af81909be54a1eda468cef07', '7f611a43007be86d527bf95e86e625b9ccfc7499', '2026-06-12 18:18:08', '2026-06-12 18:18:08'),
(43, 1, 17, 1, 17, 'Cliente Test', 'cliente.test@trimly.com', '12434342423423423', '2026-06-23', '09:15:00', '09:45:00', 30, 3500.00, 0.00, 'pending', 'on_site', 'unpaid', NULL, '', NULL, 0, '4c54c549b85b033778064d1b795fc134fd55cfee', '9ed8908282f51aa966885c46096a5d1098448cf8', '2026-06-13 00:47:28', '2026-06-13 00:47:28'),
(44, 4, 8, 20, 18, 'Cliente Test', 'admin@trimly.com', '12434342423423423', '2026-07-24', '09:30:00', '10:30:00', 60, 12000.00, 0.00, 'pending', 'on_site', 'unpaid', NULL, '', NULL, 0, 'efd292b0c9ba04e0b8c809f3a7a1aac26199d2ca', '896ce59e10aebe83ed991fcfd5e0a432047f74b9', '2026-06-13 00:48:20', '2026-06-13 00:48:20'),
(45, 1, 17, 6, 21, 'Cliente Test', 'cliente.test@trimly.com', '', '2026-06-19', '12:00:00', '13:00:00', 60, 7000.00, 0.00, 'completed', 'online', 'unpaid', NULL, '', NULL, 0, '10cda4aec89e3338455e463927fcaf6b6cc36f81', 'e0ba4a1d937fdfe61cad9d31b8f6e32624447efe', '2026-06-14 20:19:01', '2026-06-14 20:25:51'),
(46, 2, 4, 10, 22, 'Cliente Test', 'cliente.test@trimly.com', '', '2026-06-15', '12:00:00', '12:45:00', 45, 5500.00, 0.00, 'pending', 'on_site', 'unpaid', NULL, '', NULL, 0, 'c73f2a43e22ec1ec8d74b8fa65440363bb8e406c', 'e92e732fb20627a780fbdb3a4492fc45d49db147', '2026-06-14 20:19:25', '2026-06-14 20:19:25'),
(47, 1, 17, 5, 11, 'Cliente Test', 'cliente.test@trimly.com', '', '2026-06-17', '17:15:00', '17:55:00', 40, 4000.00, 0.00, 'pending', 'on_site', 'unpaid', NULL, '', NULL, 0, 'fcce7ce5f054d16d604730dd908ff99e8fe30e79', '5bbd22c3b9f6c5e12abe95bf9b2454fdb1fc0c81', '2026-06-16 13:36:19', '2026-06-16 13:36:19'),
(48, 1, 17, 7, 11, 'Cliente Test', 'cliente.test@trimly.com', '', '2026-06-17', '09:00:00', '10:15:00', 75, 8500.00, 0.00, 'pending', 'online', 'unpaid', NULL, '', NULL, 0, 'db2d920744edb5b629f0e33280376dc1f6eda8a5', '7d5dcb273026618a82439aa416a21e60dc7629ef', '2026-06-16 13:36:34', '2026-06-16 13:36:34'),
(49, 1, 17, 6, 11, 'Cliente Test', 'cliente.test@trimly.com', '', '2026-06-19', '10:00:00', '11:00:00', 60, 7000.00, 1400.00, 'pending', 'deposit', 'unpaid', NULL, '', NULL, 0, '2d31717154d73c7981c82c601010d0679aeb3451', '48e1e7d6805a40946e30be1a44c6e9759f11c836', '2026-06-18 01:32:49', '2026-06-18 01:32:49'),
(50, 12, 19, 51, 35, 'Abril Suárez', 'abril.suarez12@gmail.com', '+549292011012', '2026-06-14', '14:30:00', '15:10:00', 40, 5000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Primera vez en el local.', NULL, 1, NULL, 'tok-canc-0050', '2026-06-10 14:00:00', '2026-06-14 04:00:00'),
(51, 15, 25, 63, 30, 'Milagros Rivas', 'milagros.rivas7@gmail.com', '+549292011007', '2026-06-05', '11:30:00', '12:30:00', 60, 18000.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-900001', 'Cliente pidió referencia por WhatsApp.', NULL, 1, NULL, 'tok-canc-0051', '2026-05-30 18:00:00', '2026-06-05 02:00:00'),
(52, 14, 22, 62, 59, 'Isabella Medina', 'isabella.medina36@gmail.com', '+549292011036', '2026-06-01', '13:00:00', '15:00:00', 120, 28000.00, 0.00, 'cancelled_shop', 'online', 'unpaid', NULL, NULL, NULL, 0, 'tok-conf-0052', 'tok-canc-0052', '2026-05-28 18:00:00', '2026-05-28 18:00:00'),
(53, 13, 21, 55, 42, 'Agostina Martínez', 'agostina.martinez19@gmail.com', '+549292011019', '2026-06-21', '13:45:00', '15:15:00', 90, 14000.00, 0.00, 'cancelled_client', 'deposit', 'unpaid', NULL, NULL, NULL, 1, 'tok-conf-0053', 'tok-canc-0053', '2026-06-15 22:00:00', '2026-06-15 22:00:00'),
(54, 14, 23, 60, 62, 'Thiago Sánchez', 'thiago.sanchez39@gmail.com', '+549292011039', '2026-06-25', '13:45:00', '14:45:00', 60, 14000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Trae diseño propio.', NULL, 1, NULL, 'tok-canc-0054', '2026-06-21 21:00:00', '2026-06-25 03:00:00'),
(55, 15, 25, 66, 51, 'Victoria Romero', 'victoria.romero28@gmail.com', '+549292011028', '2026-06-17', '12:45:00', '15:15:00', 150, 45000.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-900005', 'Trae diseño propio.', NULL, 1, NULL, 'tok-canc-0055', '2026-06-13 16:00:00', '2026-06-17 05:00:00'),
(56, 13, 20, 56, 42, 'Agostina Martínez', 'agostina.martinez19@gmail.com', '+549292011019', '2026-06-03', '12:15:00', '13:15:00', 60, 9000.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-900006', NULL, NULL, 1, NULL, 'tok-canc-0056', '2026-05-31 17:00:00', '2026-06-03 04:00:00'),
(57, 14, 23, 60, 47, 'Maximiliano Sosa', 'maximiliano.sosa24@gmail.com', '+549292011024', '2026-06-20', '15:15:00', '16:15:00', 60, 14000.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-900007', NULL, NULL, 1, NULL, 'tok-canc-0057', '2026-06-18 18:00:00', '2026-06-20 01:00:00'),
(58, 15, 25, 63, 58, 'Martina Núñez', 'martina.nuñez35@gmail.com', '+549292011035', '2026-06-04', '10:45:00', '11:45:00', 60, 18000.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, 'Cliente pidió referencia por WhatsApp.', NULL, 1, NULL, 'tok-canc-0058', '2026-05-29 16:00:00', '2026-06-04 04:00:00'),
(59, 14, 23, 62, 33, 'Agustín Vega', 'agustin.vega10@gmail.com', '+549292011010', '2026-06-19', '16:30:00', '18:30:00', 120, 28000.00, 0.00, 'confirmed', 'on_site', 'unpaid', NULL, 'Primera vez en el local.', NULL, 1, 'tok-conf-0059', 'tok-canc-0059', '2026-06-16 20:00:00', '2026-06-16 20:00:00'),
(60, 11, 18, 48, 40, 'Abril Benítez', 'abril.benitez17@gmail.com', '+549292011017', '2026-06-10', '14:00:00', '15:00:00', 60, 8500.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc-0060', '2026-06-07 18:00:00', '2026-06-10 05:00:00'),
(61, 12, 19, 54, 49, 'Renata Silva', 'renata.silva26@gmail.com', '+549292011026', '2026-06-10', '17:45:00', '18:05:00', 20, 2500.00, 0.00, 'cancelled_shop', 'online', 'unpaid', NULL, 'Trae diseño propio.', NULL, 0, 'tok-conf-0061', 'tok-canc-0061', '2026-06-05 18:00:00', '2026-06-05 18:00:00'),
(62, 11, 18, 50, 23, 'Valentina Ortiz', 'valentina.ortiz0@gmail.com', '+549292011000', '2026-06-11', '13:45:00', '14:35:00', 50, 7000.00, 0.00, 'cancelled_shop', 'on_site', 'unpaid', NULL, 'Primera vez en el local.', NULL, 1, 'tok-conf-0062', 'tok-canc-0062', '2026-06-08 20:00:00', '2026-06-08 20:00:00'),
(63, 14, 22, 62, 44, 'Renata González', 'renata.gonzalez21@gmail.com', '+549292011021', '2026-06-14', '11:45:00', '13:45:00', 120, 28000.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-900013', NULL, NULL, 1, NULL, 'tok-canc-0063', '2026-06-09 15:00:00', '2026-06-14 04:00:00'),
(64, 11, 18, 48, 52, 'Mateo Ibarra', 'mateo.ibarra29@gmail.com', '+549292011029', '2026-05-31', '09:30:00', '10:30:00', 60, 8500.00, 0.00, 'cancelled_shop', 'on_site', 'unpaid', NULL, 'Cliente pidió referencia por WhatsApp.', NULL, 0, 'tok-conf-0064', 'tok-canc-0064', '2026-05-26 19:00:00', '2026-05-26 19:00:00'),
(65, 14, 23, 62, 39, 'Mía Molina', 'mia.molina16@gmail.com', '+549292011016', '2026-05-25', '16:00:00', '18:00:00', 120, 28000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc-0065', '2026-05-22 14:00:00', '2026-05-25 00:00:00'),
(66, 11, 18, 48, 24, 'Morena López', 'morena.lopez1@gmail.com', '+549292011001', '2026-06-28', '11:15:00', '12:15:00', 60, 8500.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, 'Primera vez en el local.', NULL, 1, NULL, 'tok-canc-0066', '2026-06-26 15:00:00', '2026-06-28 01:00:00'),
(67, 13, 21, 56, 61, 'Lucía Medina', 'lucia.medina38@gmail.com', '+549292011038', '2026-06-27', '10:15:00', '11:15:00', 60, 9000.00, 0.00, 'pending', 'online', 'unpaid', NULL, 'Primera vez en el local.', NULL, 0, 'tok-conf-0067', 'tok-canc-0067', '2026-06-23 15:00:00', '2026-06-23 15:00:00'),
(68, 14, 22, 59, 60, 'Isabella Benítez', 'isabella.benitez37@gmail.com', '+549292011037', '2026-06-29', '12:00:00', '13:00:00', 60, 11000.00, 0.00, 'pending', 'online', 'unpaid', NULL, 'Primera vez en el local.', NULL, 0, 'tok-conf-0068', 'tok-canc-0068', '2026-06-25 16:00:00', '2026-06-25 16:00:00'),
(69, 13, 20, 57, 23, 'Valentina Ortiz', 'valentina.ortiz0@gmail.com', '+549292011000', '2026-06-15', '16:00:00', '16:45:00', 45, 6000.00, 0.00, 'cancelled_shop', 'on_site', 'unpaid', NULL, NULL, NULL, 1, 'tok-conf-0069', 'tok-canc-0069', '2026-06-12 18:00:00', '2026-06-12 18:00:00'),
(70, 15, 25, 66, 52, 'Mateo Ibarra', 'mateo.ibarra29@gmail.com', '+549292011029', '2026-06-16', '18:30:00', '21:00:00', 150, 45000.00, 0.00, 'cancelled_client', 'on_site', 'unpaid', NULL, NULL, NULL, 0, 'tok-conf-0070', 'tok-canc-0070', '2026-06-12 17:00:00', '2026-06-12 17:00:00'),
(71, 14, 23, 61, 24, 'Morena López', 'morena.lopez1@gmail.com', '+549292011001', '2026-06-20', '14:15:00', '15:00:00', 45, 8500.00, 0.00, 'no_show', 'on_site', 'unpaid', NULL, 'Cliente pidió referencia por WhatsApp.', NULL, 1, 'tok-conf-0071', 'tok-canc-0071', '2026-06-16 20:00:00', '2026-06-16 20:00:00'),
(72, 13, 21, 55, 56, 'Emma Romero', 'emma.romero33@gmail.com', '+549292011033', '2026-06-01', '10:15:00', '11:45:00', 90, 14000.00, 0.00, 'cancelled_shop', 'deposit', 'unpaid', NULL, 'Primera vez en el local.', NULL, 0, 'tok-conf-0072', 'tok-canc-0072', '2026-05-27 14:00:00', '2026-05-27 14:00:00'),
(73, 14, 22, 59, 41, 'Valentina Cabrera', 'valentina.cabrera18@gmail.com', '+549292011018', '2026-06-03', '15:15:00', '16:15:00', 60, 11000.00, 0.00, 'pending', 'on_site', 'unpaid', NULL, 'Cliente pidió referencia por WhatsApp.', NULL, 1, 'tok-conf-0073', 'tok-canc-0073', '2026-05-28 16:00:00', '2026-05-28 16:00:00'),
(74, 14, 23, 61, 52, 'Mateo Ibarra', 'mateo.ibarra29@gmail.com', '+549292011029', '2026-06-06', '13:30:00', '14:15:00', 45, 8500.00, 0.00, 'confirmed', 'online', 'unpaid', NULL, 'Trae diseño propio.', NULL, 0, 'tok-conf-0074', 'tok-canc-0074', '2026-06-02 23:00:00', '2026-06-02 23:00:00'),
(75, 12, 19, 52, 53, 'Facundo Ortiz', 'facundo.ortiz30@gmail.com', '+549292011030', '2026-06-06', '18:30:00', '19:05:00', 35, 4000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc-0075', '2026-06-02 21:00:00', '2026-06-06 02:00:00'),
(76, 13, 20, 56, 40, 'Abril Benítez', 'abril.benitez17@gmail.com', '+549292011017', '2026-05-22', '09:30:00', '10:30:00', 60, 9000.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, 'Cliente pidió referencia por WhatsApp.', NULL, 1, NULL, 'tok-canc-0076', '2026-05-21 00:00:00', '2026-05-22 04:00:00'),
(77, 14, 23, 62, 44, 'Renata González', 'renata.gonzalez21@gmail.com', '+549292011021', '2026-05-31', '09:30:00', '11:30:00', 120, 28000.00, 0.00, 'no_show', 'online', 'unpaid', NULL, 'Primera vez en el local.', NULL, 1, 'tok-conf-0077', 'tok-canc-0077', '2026-05-29 18:00:00', '2026-05-29 18:00:00'),
(78, 11, 18, 48, 32, 'Isabella Acosta', 'isabella.acosta9@gmail.com', '+549292011009', '2026-06-25', '13:00:00', '14:00:00', 60, 8500.00, 0.00, 'confirmed', 'online', 'unpaid', NULL, 'Trae diseño propio.', NULL, 1, 'tok-conf-0078', 'tok-canc-0078', '2026-06-19 15:00:00', '2026-06-19 15:00:00'),
(79, 12, 19, 54, 51, 'Victoria Romero', 'victoria.romero28@gmail.com', '+549292011028', '2026-06-08', '18:45:00', '19:05:00', 20, 2500.00, 0.00, 'pending', 'online', 'unpaid', NULL, NULL, NULL, 0, 'tok-conf-0079', 'tok-canc-0079', '2026-06-05 14:00:00', '2026-06-05 14:00:00'),
(80, 13, 20, 56, 38, 'Julieta Luna', 'julieta.luna15@gmail.com', '+549292011015', '2026-05-31', '17:00:00', '18:00:00', 60, 9000.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-900030', 'Trae diseño propio.', NULL, 1, NULL, 'tok-canc-0080', '2026-05-26 17:00:00', '2026-05-31 05:00:00'),
(81, 14, 23, 59, 37, 'Catalina Suárez', 'catalina.suarez14@gmail.com', '+549292011014', '2026-06-07', '13:45:00', '14:45:00', 60, 11000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc-0081', '2026-06-03 14:00:00', '2026-06-07 04:00:00'),
(82, 14, 22, 60, 32, 'Isabella Acosta', 'isabella.acosta9@gmail.com', '+549292011009', '2026-06-06', '11:00:00', '12:00:00', 60, 14000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Trae diseño propio.', NULL, 1, NULL, 'tok-canc-0082', '2026-06-02 15:00:00', '2026-06-06 05:00:00'),
(83, 13, 21, 55, 52, 'Mateo Ibarra', 'mateo.ibarra29@gmail.com', '+549292011029', '2026-06-08', '15:30:00', '17:00:00', 90, 14000.00, 0.00, 'no_show', 'deposit', 'unpaid', NULL, NULL, NULL, 1, 'tok-conf-0083', 'tok-canc-0083', '2026-06-06 15:00:00', '2026-06-06 15:00:00'),
(84, 14, 23, 61, 24, 'Morena López', 'morena.lopez1@gmail.com', '+549292011001', '2026-05-25', '12:00:00', '12:45:00', 45, 8500.00, 0.00, 'pending', 'online', 'unpaid', NULL, 'Primera vez en el local.', NULL, 0, 'tok-conf-0084', 'tok-canc-0084', '2026-05-20 16:00:00', '2026-05-20 16:00:00'),
(85, 13, 20, 58, 54, 'Renata Martínez', 'renata.martinez31@gmail.com', '+549292011031', '2026-05-25', '16:30:00', '17:30:00', 60, 7500.00, 0.00, 'cancelled_shop', 'on_site', 'unpaid', NULL, 'Cliente pidió referencia por WhatsApp.', NULL, 1, 'tok-conf-0085', 'tok-canc-0085', '2026-05-23 22:00:00', '2026-05-23 22:00:00'),
(86, 14, 23, 61, 48, 'Isabella Suárez', 'isabella.suarez25@gmail.com', '+549292011025', '2026-06-24', '09:45:00', '10:30:00', 45, 8500.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Primera vez en el local.', NULL, 1, NULL, 'tok-canc-0086', '2026-06-20 19:00:00', '2026-06-24 00:00:00'),
(87, 15, 24, 66, 49, 'Renata Silva', 'renata.silva26@gmail.com', '+549292011026', '2026-05-23', '12:30:00', '15:00:00', 150, 45000.00, 0.00, 'no_show', 'deposit', 'unpaid', NULL, 'Cliente pidió referencia por WhatsApp.', NULL, 1, 'tok-conf-0087', 'tok-canc-0087', '2026-05-21 21:00:00', '2026-05-21 21:00:00'),
(88, 15, 24, 65, 51, 'Victoria Romero', 'victoria.romero28@gmail.com', '+549292011028', '2026-06-20', '10:00:00', '10:45:00', 45, 15000.00, 0.00, 'confirmed', 'on_site', 'unpaid', NULL, 'Trae diseño propio.', NULL, 1, 'tok-conf-0088', 'tok-canc-0088', '2026-06-15 00:00:00', '2026-06-15 00:00:00'),
(89, 14, 22, 60, 30, 'Milagros Rivas', 'milagros.rivas7@gmail.com', '+549292011007', '2026-06-18', '10:15:00', '11:15:00', 60, 14000.00, 0.00, 'no_show', 'on_site', 'unpaid', NULL, 'Primera vez en el local.', NULL, 1, 'tok-conf-0089', 'tok-canc-0089', '2026-06-12 20:00:00', '2026-06-12 20:00:00'),
(90, 14, 23, 60, 52, 'Mateo Ibarra', 'mateo.ibarra29@gmail.com', '+549292011029', '2026-06-24', '11:45:00', '12:45:00', 60, 14000.00, 0.00, 'confirmed', 'on_site', 'unpaid', NULL, 'Cliente pidió referencia por WhatsApp.', NULL, 0, 'tok-conf-0090', 'tok-canc-0090', '2026-06-20 18:00:00', '2026-06-20 18:00:00'),
(91, 15, 25, 63, 41, 'Valentina Cabrera', 'valentina.cabrera18@gmail.com', '+549292011018', '2026-06-08', '18:45:00', '19:45:00', 60, 18000.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, 'Cliente pidió referencia por WhatsApp.', NULL, 1, NULL, 'tok-canc-0091', '2026-06-02 17:00:00', '2026-06-08 02:00:00'),
(92, 15, 25, 66, 43, 'Isabella Castro', 'isabella.castro20@gmail.com', '+549292011020', '2026-06-01', '12:45:00', '15:15:00', 150, 45000.00, 0.00, 'confirmed', 'deposit', 'deposit_paid', 'MP-900042', NULL, NULL, 1, 'tok-conf-0092', 'tok-canc-0092', '2026-05-27 18:00:00', '2026-05-27 18:00:00'),
(93, 14, 22, 60, 55, 'Mateo Cabrera', 'mateo.cabrera32@gmail.com', '+549292011032', '2026-06-26', '14:00:00', '15:00:00', 60, 14000.00, 0.00, 'no_show', 'online', 'unpaid', NULL, NULL, NULL, 1, 'tok-conf-0093', 'tok-canc-0093', '2026-06-20 17:00:00', '2026-06-20 17:00:00'),
(94, 12, 19, 52, 27, 'Agostina Suárez', 'agostina.suarez4@gmail.com', '+549292011004', '2026-06-19', '13:30:00', '14:05:00', 35, 4000.00, 0.00, 'cancelled_shop', 'online', 'unpaid', NULL, 'Cliente pidió referencia por WhatsApp.', NULL, 1, 'tok-conf-0094', 'tok-canc-0094', '2026-06-13 18:00:00', '2026-06-13 18:00:00'),
(192, 7, 37, 87, 29, 'Morena Martínez', 'morena.martinez6@gmail.com', '+549292011006', '2026-04-30', '16:30:00', '17:00:00', 30, 4200.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc3-0192', '2026-04-25 20:00:00', '2026-04-30 02:00:00'),
(193, 7, 37, 87, 55, 'Mateo Cabrera', 'mateo.cabrera32@gmail.com', '+549292011032', '2026-06-14', '14:30:00', '15:00:00', 30, 4200.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, 'Primera visita al local.', NULL, 1, NULL, 'tok-canc3-0193', '2026-06-09 23:00:00', '2026-06-14 03:00:00'),
(194, 7, 37, 87, 37, 'Catalina Suárez', 'catalina.suarez14@gmail.com', '+549292011014', '2026-05-14', '11:00:00', '11:30:00', 30, 4200.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-700194', 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc3-0194', '2026-05-07 15:00:00', '2026-05-14 03:00:00'),
(195, 7, 37, 90, 52, 'Mateo Ibarra', 'mateo.ibarra29@gmail.com', '+549292011029', '2026-05-25', '15:15:00', '16:15:00', 60, 8500.00, 1700.00, 'completed', 'on_site', 'deposit_paid', NULL, 'Primera visita al local.', NULL, 1, NULL, 'tok-canc3-0195', '2026-05-24 00:00:00', '2026-05-25 01:00:00'),
(196, 7, 37, 90, 36, 'Agostina Sánchez', 'agostina.sanchez13@gmail.com', '+549292011013', '2026-06-11', '09:30:00', '10:30:00', 60, 8500.00, 1700.00, 'completed', 'on_site', 'deposit_paid', NULL, 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc3-0196', '2026-06-09 17:00:00', '2026-06-11 04:00:00'),
(197, 7, 36, 88, 48, 'Isabella Suárez', 'isabella.suarez25@gmail.com', '+549292011025', '2026-05-26', '11:45:00', '12:30:00', 45, 5800.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-700197', 'Primera visita al local.', NULL, 1, NULL, 'tok-canc3-0197', '2026-05-25 00:00:00', '2026-05-26 03:00:00'),
(198, 7, 37, 89, 10, 'Tomás Ibáñez', 'tomas@gmail.com', '+5492920400005', '2026-04-30', '14:45:00', '15:15:00', 30, 4000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Primera visita al local.', NULL, 1, NULL, 'tok-canc3-0198', '2026-04-24 21:00:00', '2026-04-30 03:00:00'),
(199, 7, 37, 90, 25, 'Leandro García', 'leandro.garcia2@gmail.com', '+549292011002', '2026-05-09', '17:15:00', '18:15:00', 60, 8500.00, 1700.00, 'completed', 'deposit', 'fully_paid', 'MP-700199', NULL, NULL, 1, NULL, 'tok-canc3-0199', '2026-05-07 18:00:00', '2026-05-09 00:00:00'),
(200, 7, 36, 90, 61, 'Lucía Medina', 'lucia.medina38@gmail.com', '+549292011038', '2026-05-29', '18:15:00', '19:15:00', 60, 8500.00, 1700.00, 'completed', 'on_site', 'fully_paid', NULL, 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc3-0200', '2026-05-24 15:00:00', '2026-05-29 02:00:00'),
(201, 7, 36, 90, 56, 'Emma Romero', 'emma.romero33@gmail.com', '+549292011033', '2026-06-17', '12:45:00', '13:45:00', 60, 8500.00, 1700.00, 'completed', 'online', 'fully_paid', 'MP-700201', NULL, NULL, 1, NULL, 'tok-canc3-0201', '2026-06-14 14:00:00', '2026-06-17 04:00:00'),
(202, 7, 37, 89, 12, 'Cliente Test', 'cliente.test@trimly.com', '12434342423423423', '2026-05-22', '11:30:00', '12:00:00', 30, 4000.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc3-0202', '2026-05-17 18:00:00', '2026-05-22 02:00:00'),
(203, 7, 36, 87, 57, 'Ezequiel Romero', 'ezequiel.romero34@gmail.com', '+549292011034', '2026-05-29', '14:45:00', '15:15:00', 30, 4200.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc3-0203', '2026-05-27 19:00:00', '2026-05-29 01:00:00'),
(204, 7, 37, 88, 7, 'Florencia Acosta', 'flor@gmail.com', '+5492920400002', '2026-05-16', '10:45:00', '11:30:00', 45, 5800.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc3-0204', '2026-05-14 14:00:00', '2026-05-16 00:00:00'),
(205, 7, 37, 88, 17, 'Cliente Test', 'cliente.test@trimly.com', '12434342423423423', '2026-05-16', '10:00:00', '10:45:00', 45, 5800.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc3-0205', '2026-05-12 18:00:00', '2026-05-16 00:00:00'),
(206, 7, 36, 90, 21, 'Cliente Test', 'cliente.test@trimly.com', '', '2026-06-07', '12:45:00', '13:45:00', 60, 8500.00, 1700.00, 'completed', 'deposit', 'fully_paid', 'MP-700206', 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc3-0206', '2026-05-31 19:00:00', '2026-06-07 04:00:00'),
(207, 7, 36, 89, 50, 'Antonella Martínez', 'antonella.martinez27@gmail.com', '+549292011027', '2026-05-26', '14:00:00', '14:30:00', 30, 4000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc3-0207', '2026-05-23 16:00:00', '2026-05-26 00:00:00'),
(208, 7, 36, 89, 27, 'Agostina Suárez', 'agostina.suarez4@gmail.com', '+549292011004', '2026-05-24', '15:15:00', '15:45:00', 30, 4000.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc3-0208', '2026-05-17 21:00:00', '2026-05-24 01:00:00'),
(209, 7, 36, 87, 18, 'Cliente Test', 'admin@trimly.com', '12434342423423423', '2026-06-03', '16:45:00', '17:15:00', 30, 4200.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc3-0209', '2026-05-30 22:00:00', '2026-06-03 04:00:00'),
(210, 7, 36, 88, 31, 'Mía Ibarra', 'mia.ibarra8@gmail.com', '+549292011008', '2026-05-03', '12:15:00', '13:00:00', 45, 5800.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, 'Primera visita al local.', NULL, 1, NULL, 'tok-canc3-0210', '2026-04-26 19:00:00', '2026-05-03 01:00:00'),
(211, 7, 36, 90, 10, 'Tomás Ibáñez', 'tomas@gmail.com', '+5492920400005', '2026-05-31', '09:30:00', '10:30:00', 60, 8500.00, 1700.00, 'completed', 'deposit', 'fully_paid', 'MP-700211', 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc3-0211', '2026-05-24 22:00:00', '2026-05-31 00:00:00'),
(212, 7, 36, 90, 25, 'Leandro García', 'leandro.garcia2@gmail.com', '+549292011002', '2026-06-03', '17:15:00', '18:15:00', 60, 8500.00, 1700.00, 'completed', 'online', 'fully_paid', 'MP-700212', 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc3-0212', '2026-06-01 22:00:00', '2026-06-03 03:00:00'),
(213, 7, 37, 89, 9, 'Marina Castro', 'marina@gmail.com', '+5492920400004', '2026-05-24', '12:00:00', '12:30:00', 30, 4000.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc3-0213', '2026-05-19 19:00:00', '2026-05-24 04:00:00'),
(214, 7, 36, 89, 5, 'Nicolás Ferreyra', 'nico.ferreyra@gmail.com', '+5492920100005', '2026-05-23', '13:15:00', '13:45:00', 30, 4000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc3-0214', '2026-05-17 15:00:00', '2026-05-23 01:00:00'),
(215, 7, 37, 87, 23, 'Valentina Ortiz', 'valentina.ortiz0@gmail.com', '+549292011000', '2026-05-30', '16:00:00', '16:30:00', 30, 4200.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Primera visita al local.', NULL, 1, NULL, 'tok-canc3-0215', '2026-05-26 14:00:00', '2026-05-30 03:00:00'),
(216, 7, 37, 89, 21, 'Cliente Test', 'cliente.test@trimly.com', '', '2026-06-15', '16:45:00', '17:15:00', 30, 4000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc3-0216', '2026-06-12 21:00:00', '2026-06-15 03:00:00'),
(217, 7, 36, 90, 18, 'Cliente Test', 'admin@trimly.com', '12434342423423423', '2026-05-21', '10:30:00', '11:30:00', 60, 8500.00, 1700.00, 'completed', 'on_site', 'deposit_paid', NULL, 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc3-0217', '2026-05-17 22:00:00', '2026-05-21 04:00:00'),
(218, 7, 36, 89, 44, 'Renata González', 'renata.gonzalez21@gmail.com', '+549292011021', '2026-05-26', '14:00:00', '14:30:00', 30, 4000.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, 'Primera visita al local.', NULL, 1, NULL, 'tok-canc3-0218', '2026-05-19 14:00:00', '2026-05-26 04:00:00'),
(219, 7, 37, 88, 22, 'Cliente Test', 'cliente.test@trimly.com', '', '2026-06-06', '18:45:00', '19:30:00', 45, 5800.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc3-0219', '2026-06-01 19:00:00', '2026-06-06 00:00:00'),
(220, 7, 37, 89, 6, 'Nicolás Gómez', 'nico@gmail.com', '+5492920400001', '2026-06-04', '12:00:00', '12:30:00', 30, 4000.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-700220', 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc3-0220', '2026-06-01 19:00:00', '2026-06-04 01:00:00'),
(221, 7, 37, 90, 3, 'Javier Molina', 'javier.molina@hotmail.com', '+5492920100003', '2026-05-11', '17:15:00', '18:15:00', 60, 8500.00, 1700.00, 'completed', 'deposit', 'deposit_paid', 'MP-700221', 'Primera visita al local.', NULL, 1, NULL, 'tok-canc3-0221', '2026-05-07 19:00:00', '2026-05-11 02:00:00'),
(222, 10, 39, 94, 13, 'Cliente Test', 'cliente.test@trimly.com', '', '2026-05-23', '13:45:00', '14:35:00', 50, 7200.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc3-0222', '2026-05-20 15:00:00', '2026-05-23 00:00:00'),
(223, 10, 39, 93, 12, 'Cliente Test', 'cliente.test@trimly.com', '12434342423423423', '2026-05-04', '17:15:00', '18:45:00', 90, 19500.00, 5850.00, 'completed', 'on_site', 'deposit_paid', NULL, NULL, NULL, 1, NULL, 'tok-canc3-0223', '2026-04-27 23:00:00', '2026-05-04 04:00:00'),
(224, 10, 38, 93, 24, 'Morena López', 'morena.lopez1@gmail.com', '+549292011001', '2026-06-10', '15:15:00', '16:45:00', 90, 19500.00, 5850.00, 'completed', 'on_site', 'fully_paid', NULL, 'Primera visita al local.', NULL, 1, NULL, 'tok-canc3-0224', '2026-06-03 22:00:00', '2026-06-10 01:00:00'),
(225, 10, 39, 92, 30, 'Milagros Rivas', 'milagros.rivas7@gmail.com', '+549292011007', '2026-05-10', '11:00:00', '11:30:00', 30, 4500.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Primera visita al local.', NULL, 1, NULL, 'tok-canc3-0225', '2026-05-03 22:00:00', '2026-05-10 03:00:00'),
(226, 10, 39, 92, 58, 'Martina Núñez', 'martina.nuñez35@gmail.com', '+549292011035', '2026-05-20', '11:00:00', '11:30:00', 30, 4500.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc3-0226', '2026-05-19 00:00:00', '2026-05-20 00:00:00'),
(227, 10, 39, 92, 54, 'Renata Martínez', 'renata.martinez31@gmail.com', '+549292011031', '2026-05-10', '12:15:00', '12:45:00', 30, 4500.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, 'Primera visita al local.', NULL, 1, NULL, 'tok-canc3-0227', '2026-05-07 19:00:00', '2026-05-10 01:00:00'),
(228, 10, 39, 94, 6, 'Nicolás Gómez', 'nico@gmail.com', '+5492920400001', '2026-05-13', '12:00:00', '12:50:00', 50, 7200.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-700228', NULL, NULL, 1, NULL, 'tok-canc3-0228', '2026-05-09 14:00:00', '2026-05-13 04:00:00'),
(229, 10, 39, 93, 48, 'Isabella Suárez', 'isabella.suarez25@gmail.com', '+549292011025', '2026-05-23', '15:00:00', '16:30:00', 90, 19500.00, 5850.00, 'completed', 'deposit', 'deposit_paid', 'MP-700229', NULL, NULL, 1, NULL, 'tok-canc3-0229', '2026-05-19 19:00:00', '2026-05-23 03:00:00'),
(230, 10, 39, 93, 62, 'Thiago Sánchez', 'thiago.sanchez39@gmail.com', '+549292011039', '2026-06-12', '10:45:00', '12:15:00', 90, 19500.00, 5850.00, 'completed', 'deposit', 'deposit_paid', 'MP-700230', NULL, NULL, 1, NULL, 'tok-canc3-0230', '2026-06-06 16:00:00', '2026-06-12 04:00:00'),
(231, 10, 39, 94, 35, 'Abril Suárez', 'abril.suarez12@gmail.com', '+549292011012', '2026-05-01', '11:30:00', '12:20:00', 50, 7200.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc3-0231', '2026-04-29 23:00:00', '2026-05-01 03:00:00'),
(232, 10, 39, 92, 21, 'Cliente Test', 'cliente.test@trimly.com', '', '2026-06-14', '16:15:00', '16:45:00', 30, 4500.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc3-0232', '2026-06-11 16:00:00', '2026-06-14 02:00:00'),
(233, 10, 39, 94, 34, 'Pilar Cabrera', 'pilar.cabrera11@gmail.com', '+549292011011', '2026-05-01', '16:15:00', '17:05:00', 50, 7200.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc3-0233', '2026-04-25 00:00:00', '2026-05-01 04:00:00'),
(234, 10, 38, 91, 37, 'Catalina Suárez', 'catalina.suarez14@gmail.com', '+549292011014', '2026-05-23', '12:00:00', '12:45:00', 45, 6200.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc3-0234', '2026-05-18 18:00:00', '2026-05-23 00:00:00'),
(235, 10, 39, 93, 6, 'Nicolás Gómez', 'nico@gmail.com', '+5492920400001', '2026-05-16', '11:00:00', '12:30:00', 90, 19500.00, 5850.00, 'completed', 'on_site', 'fully_paid', NULL, 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc3-0235', '2026-05-11 16:00:00', '2026-05-16 00:00:00'),
(236, 10, 39, 91, 11, 'Cliente Test', 'cliente.test@trimly.com', '', '2026-06-10', '10:30:00', '11:15:00', 45, 6200.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc3-0236', '2026-06-08 20:00:00', '2026-06-10 04:00:00'),
(237, 10, 39, 92, 35, 'Abril Suárez', 'abril.suarez12@gmail.com', '+549292011012', '2026-05-25', '16:00:00', '16:30:00', 30, 4500.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc3-0237', '2026-05-24 00:00:00', '2026-05-25 03:00:00'),
(238, 10, 38, 93, 26, 'Ezequiel Rivas', 'ezequiel.rivas3@gmail.com', '+549292011003', '2026-05-17', '11:45:00', '13:15:00', 90, 19500.00, 5850.00, 'completed', 'online', 'fully_paid', 'MP-700238', NULL, NULL, 1, NULL, 'tok-canc3-0238', '2026-05-15 19:00:00', '2026-05-17 00:00:00'),
(239, 10, 38, 92, 1, 'Martín Acosta', 'martin.acosta@gmail.com', '+5492920100001', '2026-05-24', '10:45:00', '11:15:00', 30, 4500.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, 'Primera visita al local.', NULL, 1, NULL, 'tok-canc3-0239', '2026-05-17 15:00:00', '2026-05-24 02:00:00'),
(240, 10, 39, 92, 61, 'Lucía Medina', 'lucia.medina38@gmail.com', '+549292011038', '2026-05-19', '18:15:00', '18:45:00', 30, 4500.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc3-0240', '2026-05-12 16:00:00', '2026-05-19 00:00:00'),
(241, 10, 39, 91, 5, 'Nicolás Ferreyra', 'nico.ferreyra@gmail.com', '+5492920100005', '2026-05-08', '09:30:00', '10:15:00', 45, 6200.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc3-0241', '2026-05-03 20:00:00', '2026-05-08 00:00:00'),
(242, 10, 38, 93, 29, 'Morena Martínez', 'morena.martinez6@gmail.com', '+549292011006', '2026-05-04', '15:30:00', '17:00:00', 90, 19500.00, 5850.00, 'completed', 'deposit', 'deposit_paid', 'MP-700242', 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc3-0242', '2026-04-27 20:00:00', '2026-05-04 02:00:00'),
(243, 10, 38, 93, 46, 'Lucía Romero', 'lucia.romero23@gmail.com', '+549292011023', '2026-06-17', '15:15:00', '16:45:00', 90, 19500.00, 5850.00, 'completed', 'on_site', 'fully_paid', NULL, NULL, NULL, 1, NULL, 'tok-canc3-0243', '2026-06-15 21:00:00', '2026-06-17 03:00:00'),
(244, 10, 38, 92, 33, 'Agustín Vega', 'agustin.vega10@gmail.com', '+549292011010', '2026-05-19', '09:15:00', '09:45:00', 30, 4500.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc3-0244', '2026-05-16 18:00:00', '2026-05-19 04:00:00'),
(245, 10, 39, 93, 54, 'Renata Martínez', 'renata.martinez31@gmail.com', '+549292011031', '2026-05-26', '12:30:00', '14:00:00', 90, 19500.00, 5850.00, 'completed', 'on_site', 'deposit_paid', NULL, NULL, NULL, 1, NULL, 'tok-canc3-0245', '2026-05-21 16:00:00', '2026-05-26 01:00:00'),
(246, 10, 38, 92, 14, 'Cliente Test', 'cliente.test@trimly.com', '', '2026-05-11', '16:45:00', '17:15:00', 30, 4500.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc3-0246', '2026-05-08 18:00:00', '2026-05-11 04:00:00'),
(247, 10, 39, 91, 27, 'Agostina Suárez', 'agostina.suarez4@gmail.com', '+549292011004', '2026-05-15', '14:30:00', '15:15:00', 45, 6200.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-700247', NULL, NULL, 1, NULL, 'tok-canc3-0247', '2026-05-13 23:00:00', '2026-05-15 02:00:00'),
(248, 10, 38, 93, 16, 'Cliente Test', 'cliente.test@trimly.com', '12434342423423423', '2026-05-09', '16:45:00', '18:15:00', 90, 19500.00, 5850.00, 'completed', 'online', 'fully_paid', 'MP-700248', NULL, NULL, 1, NULL, 'tok-canc3-0248', '2026-05-03 18:00:00', '2026-05-09 01:00:00'),
(249, 10, 39, 91, 4, 'Camila Díaz', 'camila.diaz@gmail.com', '+5492920100004', '2026-05-26', '11:45:00', '12:30:00', 45, 6200.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-700249', NULL, NULL, 1, NULL, 'tok-canc3-0249', '2026-05-22 16:00:00', '2026-05-26 04:00:00'),
(250, 10, 38, 93, 60, 'Isabella Benítez', 'isabella.benitez37@gmail.com', '+549292011037', '2026-06-17', '10:45:00', '12:15:00', 90, 19500.00, 5850.00, 'completed', 'online', 'fully_paid', 'MP-700250', 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc3-0250', '2026-06-11 15:00:00', '2026-06-17 02:00:00'),
(251, 10, 39, 94, 19, 'Cliente Test', 'cliente.test@trimly.com', '', '2026-05-26', '13:15:00', '14:05:00', 50, 7200.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc3-0251', '2026-05-23 19:00:00', '2026-05-26 04:00:00'),
(252, 20, 49, 111, 79, 'Bruno Klein', 'bruno.klein.client166@trimly-demo.com', '+5493513588395', '2026-04-25', '11:45:00', '12:45:00', 60, 16000.00, 8000.00, 'completed', 'deposit', 'fully_paid', 'MP-600252', NULL, NULL, 1, NULL, 'tok-canc4-0252', '2026-04-23 17:00:00', '2026-04-25 03:00:00'),
(253, 17, 43, 102, 75, 'Marcos Bustos', 'marcos.bustos.client162@trimly-demo.com', '+5493422609347', '2026-05-05', '18:30:00', '19:30:00', 60, 7000.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-600253', 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc4-0253', '2026-05-01 18:00:00', '2026-05-05 01:00:00'),
(254, 16, 40, 97, 30, 'Milagros Rivas', 'milagros.rivas7@gmail.com', '+549292011007', '2026-06-18', '15:30:00', '16:00:00', 30, 3800.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-600254', NULL, NULL, 1, NULL, 'tok-canc4-0254', '2026-06-15 20:00:00', '2026-06-18 00:00:00'),
(255, 18, 45, 103, 62, 'Thiago Sánchez', 'thiago.sanchez39@gmail.com', '+549292011039', '2026-05-16', '13:45:00', '14:45:00', 60, 12000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc4-0255', '2026-05-13 20:00:00', '2026-05-16 03:00:00'),
(256, 19, 46, 107, 52, 'Mateo Ibarra', 'mateo.ibarra29@gmail.com', '+549292011029', '2026-05-23', '15:30:00', '16:15:00', 45, 6000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc4-0256', '2026-05-20 17:00:00', '2026-05-23 02:00:00'),
(257, 18, 45, 104, 46, 'Lucía Romero', 'lucia.romero23@gmail.com', '+549292011023', '2026-05-12', '10:45:00', '12:00:00', 75, 14000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc4-0257', '2026-05-08 15:00:00', '2026-05-12 04:00:00'),
(258, 6, 12, 29, 16, 'Cliente Test', 'cliente.test@trimly.com', '12434342423423423', '2026-05-29', '14:15:00', '15:00:00', 45, 6000.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-600258', NULL, NULL, 1, NULL, 'tok-canc4-0258', '2026-05-22 21:00:00', '2026-05-29 04:00:00'),
(259, 20, 48, 111, 61, 'Lucía Medina', 'lucia.medina38@gmail.com', '+549292011038', '2026-04-27', '15:00:00', '16:00:00', 60, 16000.00, 8000.00, 'completed', 'online', 'fully_paid', 'MP-600259', NULL, NULL, 1, NULL, 'tok-canc4-0259', '2026-04-22 19:00:00', '2026-04-27 02:00:00'),
(260, 17, 43, 99, 80, 'Ciro Bustos', 'ciro.bustos.client167@trimly-demo.com', '+5492617707293', '2026-04-29', '12:45:00', '14:15:00', 90, 13000.00, 2600.00, 'completed', 'deposit', 'fully_paid', 'MP-600260', 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc4-0260', '2026-04-28 00:00:00', '2026-04-29 01:00:00'),
(261, 16, 40, 96, 68, 'Karina Cardozo', 'karina.cardozo.client155@trimly-demo.com', '+5493517136346', '2026-06-12', '17:45:00', '18:30:00', 45, 5500.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc4-0261', '2026-06-09 16:00:00', '2026-06-12 01:00:00'),
(262, 19, 47, 108, 21, 'Cliente Test', 'cliente.test@trimly.com', '', '2026-06-13', '15:30:00', '16:00:00', 30, 4500.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc4-0262', '2026-06-07 21:00:00', '2026-06-13 02:00:00'),
(263, 20, 48, 112, 77, 'Kevin Fonseca', 'kevin.fonseca.client164@trimly-demo.com', '+5493419642750', '2026-04-26', '16:30:00', '18:30:00', 120, 32000.00, 16000.00, 'completed', 'deposit', 'deposit_paid', 'MP-600263', 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc4-0263', '2026-04-24 00:00:00', '2026-04-26 02:00:00'),
(264, 2, 4, 14, 72, 'Bianca Leiva', 'bianca.leiva.client159@trimly-demo.com', '+5492615404127', '2026-05-30', '12:30:00', '14:30:00', 120, 22000.00, 6600.00, 'completed', 'deposit', 'fully_paid', 'MP-600264', 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc4-0264', '2026-05-25 18:00:00', '2026-05-30 02:00:00'),
(265, 17, 42, 99, 82, 'Hernán Iturbe', 'hernan.iturbe.client169@trimly-demo.com', '+5492613557157', '2026-05-15', '16:00:00', '17:30:00', 90, 13000.00, 2600.00, 'completed', 'on_site', 'fully_paid', NULL, 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc4-0265', '2026-05-12 16:00:00', '2026-05-15 04:00:00'),
(266, 6, 12, 31, 50, 'Antonella Martínez', 'antonella.martinez27@gmail.com', '+549292011027', '2026-05-17', '11:15:00', '12:30:00', 75, 14000.00, 2800.00, 'completed', 'on_site', 'deposit_paid', NULL, NULL, NULL, 1, NULL, 'tok-canc4-0266', '2026-05-11 20:00:00', '2026-05-17 00:00:00'),
(267, 18, 44, 105, 12, 'Cliente Test', 'cliente.test@trimly.com', '12434342423423423', '2026-04-28', '12:00:00', '13:00:00', 60, 11000.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-600267', NULL, NULL, 1, NULL, 'tok-canc4-0267', '2026-04-26 18:00:00', '2026-04-28 00:00:00'),
(268, 4, 9, 21, 2, 'Sofía Ruiz', 'sofia.ruiz@gmail.com', '+5492920100002', '2026-06-05', '17:30:00', '18:30:00', 60, 13000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc4-0268', '2026-05-30 00:00:00', '2026-06-05 02:00:00'),
(269, 12, 19, 52, 22, 'Cliente Test', 'cliente.test@trimly.com', '', '2026-04-30', '18:15:00', '18:50:00', 35, 4000.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-600269', NULL, NULL, 1, NULL, 'tok-canc4-0269', '2026-04-26 23:00:00', '2026-04-30 02:00:00');
INSERT INTO `appointments` (`id`, `shop_id`, `employee_id`, `service_id`, `client_id`, `client_name`, `client_email`, `client_phone`, `date`, `start_time`, `end_time`, `duration_min`, `price`, `deposit_amount`, `status`, `payment_option`, `payment_status`, `payment_ref`, `notes`, `internal_notes`, `reminder_sent`, `confirm_token`, `cancel_token`, `created_at`, `updated_at`) VALUES
(270, 3, 6, 18, 22, 'Cliente Test', 'cliente.test@trimly.com', '', '2026-06-11', '09:30:00', '10:00:00', 30, 3000.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-600270', 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc4-0270', '2026-06-04 15:00:00', '2026-06-11 02:00:00'),
(271, 5, 10, 26, 85, 'Elián Iturbe', 'elian.iturbe.client172@trimly-demo.com', '+5493519724183', '2026-05-14', '15:00:00', '17:00:00', 120, 35000.00, 17500.00, 'completed', 'deposit', 'fully_paid', 'MP-600271', 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc4-0271', '2026-05-11 19:00:00', '2026-05-14 04:00:00'),
(272, 11, 18, 48, 20, 'Cliente Test', 'cliente.test@trimly.com', '', '2026-06-12', '16:00:00', '17:00:00', 60, 8500.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc4-0272', '2026-06-09 21:00:00', '2026-06-12 04:00:00'),
(273, 19, 47, 108, 29, 'Morena Martínez', 'morena.martinez6@gmail.com', '+549292011006', '2026-05-13', '09:15:00', '09:45:00', 30, 4500.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc4-0273', '2026-05-10 16:00:00', '2026-05-13 01:00:00'),
(274, 16, 40, 97, 82, 'Hernán Iturbe', 'hernan.iturbe.client169@trimly-demo.com', '+5492613557157', '2026-06-07', '13:30:00', '14:00:00', 30, 3800.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-600274', NULL, NULL, 1, NULL, 'tok-canc4-0274', '2026-06-01 20:00:00', '2026-06-07 03:00:00'),
(275, 19, 46, 110, 76, 'Hernán Fonseca', 'hernan.fonseca.client163@trimly-demo.com', '+5493513724040', '2026-06-04', '12:00:00', '14:00:00', 120, 24000.00, 7200.00, 'completed', 'online', 'fully_paid', 'MP-600275', NULL, NULL, 1, NULL, 'tok-canc4-0275', '2026-05-29 22:00:00', '2026-06-04 02:00:00'),
(276, 11, 18, 47, 49, 'Renata Silva', 'renata.silva26@gmail.com', '+549292011026', '2026-04-27', '13:45:00', '14:30:00', 45, 6000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc4-0276', '2026-04-21 19:00:00', '2026-04-27 02:00:00'),
(277, 17, 43, 101, 12, 'Cliente Test', 'cliente.test@trimly.com', '12434342423423423', '2026-06-09', '18:15:00', '19:15:00', 60, 8500.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Primera visita al local.', NULL, 1, NULL, 'tok-canc4-0277', '2026-06-02 22:00:00', '2026-06-09 02:00:00'),
(278, 12, 19, 51, 44, 'Renata González', 'renata.gonzalez21@gmail.com', '+549292011021', '2026-06-08', '15:30:00', '16:10:00', 40, 5000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc4-0278', '2026-06-06 16:00:00', '2026-06-08 04:00:00'),
(279, 18, 45, 104, 68, 'Karina Cardozo', 'karina.cardozo.client155@trimly-demo.com', '+5493517136346', '2026-04-30', '14:30:00', '15:45:00', 75, 14000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc4-0279', '2026-04-27 00:00:00', '2026-04-30 02:00:00'),
(280, 5, 10, 28, 58, 'Martina Núñez', 'martina.nuñez35@gmail.com', '+549292011035', '2026-05-27', '13:15:00', '13:45:00', 30, 8000.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-600280', NULL, NULL, 1, NULL, 'tok-canc4-0280', '2026-05-20 22:00:00', '2026-05-27 03:00:00'),
(281, 6, 12, 29, 65, 'Iara Juárez', 'iara.juarez.client152@trimly-demo.com', '+5493512387122', '2026-05-22', '09:00:00', '09:45:00', 45, 6000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc4-0281', '2026-05-19 18:00:00', '2026-05-22 03:00:00'),
(282, 9, 15, 45, 31, 'Mía Ibarra', 'mia.ibarra8@gmail.com', '+549292011008', '2026-06-12', '16:45:00', '17:45:00', 60, 12000.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc4-0282', '2026-06-10 16:00:00', '2026-06-12 04:00:00'),
(283, 20, 48, 113, 43, 'Isabella Castro', 'isabella.castro20@gmail.com', '+549292011020', '2026-06-18', '11:00:00', '14:00:00', 180, 50000.00, 25000.00, 'completed', 'on_site', 'fully_paid', NULL, NULL, NULL, 1, NULL, 'tok-canc4-0283', '2026-06-12 14:00:00', '2026-06-18 03:00:00'),
(284, 4, 9, 20, 33, 'Agustín Vega', 'agustin.vega10@gmail.com', '+549292011010', '2026-05-19', '13:30:00', '14:30:00', 60, 12000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc4-0284', '2026-05-13 15:00:00', '2026-05-19 03:00:00'),
(285, 16, 41, 98, 44, 'Renata González', 'renata.gonzalez21@gmail.com', '+549292011021', '2026-06-18', '17:45:00', '18:45:00', 60, 8000.00, 1600.00, 'completed', 'on_site', 'fully_paid', NULL, NULL, NULL, 1, NULL, 'tok-canc4-0285', '2026-06-13 21:00:00', '2026-06-18 00:00:00'),
(286, 13, 21, 56, 78, 'Bianca Espinoza', 'bianca.espinoza.client165@trimly-demo.com', '+5493511845270', '2026-06-13', '15:45:00', '16:45:00', 60, 9000.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-600286', 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc4-0286', '2026-06-06 20:00:00', '2026-06-13 04:00:00'),
(287, 2, 5, 11, 64, 'Kevin Iturbe', 'kevin.iturbe.client151@trimly-demo.com', '+5493517186637', '2026-06-05', '15:45:00', '16:45:00', 60, 9000.00, 1800.00, 'completed', 'on_site', 'fully_paid', NULL, 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc4-0287', '2026-06-01 20:00:00', '2026-06-05 03:00:00'),
(288, 3, 6, 18, 80, 'Ciro Bustos', 'ciro.bustos.client167@trimly-demo.com', '+5492617707293', '2026-06-04', '11:45:00', '12:15:00', 30, 3000.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, 'Primera visita al local.', NULL, 1, NULL, 'tok-canc4-0288', '2026-06-01 20:00:00', '2026-06-04 01:00:00'),
(289, 13, 21, 56, 49, 'Renata Silva', 'renata.silva26@gmail.com', '+549292011026', '2026-05-26', '15:30:00', '16:30:00', 60, 9000.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-600289', 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc4-0289', '2026-05-22 20:00:00', '2026-05-26 02:00:00'),
(290, 6, 12, 30, 51, 'Victoria Romero', 'victoria.romero28@gmail.com', '+549292011028', '2026-05-22', '13:30:00', '14:30:00', 60, 9000.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-600290', NULL, NULL, 1, NULL, 'tok-canc4-0290', '2026-05-15 23:00:00', '2026-05-22 00:00:00'),
(291, 2, 5, 14, 82, 'Hernán Iturbe', 'hernan.iturbe.client169@trimly-demo.com', '+5492613557157', '2026-04-28', '17:00:00', '19:00:00', 120, 22000.00, 6600.00, 'completed', 'online', 'fully_paid', 'MP-600291', NULL, NULL, 1, NULL, 'tok-canc4-0291', '2026-04-22 15:00:00', '2026-04-28 04:00:00'),
(292, 17, 42, 99, 73, 'Elián Heredia', 'elian.heredia.client160@trimly-demo.com', '+5493419063545', '2026-05-31', '13:15:00', '14:45:00', 90, 13000.00, 2600.00, 'completed', 'deposit', 'deposit_paid', 'MP-600292', NULL, NULL, 1, NULL, 'tok-canc4-0292', '2026-05-26 18:00:00', '2026-05-31 00:00:00'),
(293, 20, 48, 112, 86, 'Marcos Juárez', 'marcos.juarez.client173@trimly-demo.com', '+5493414512459', '2026-05-28', '17:15:00', '19:15:00', 120, 32000.00, 16000.00, 'completed', 'on_site', 'deposit_paid', NULL, NULL, NULL, 1, NULL, 'tok-canc4-0293', '2026-05-26 20:00:00', '2026-05-28 04:00:00'),
(294, 5, 11, 27, 73, 'Elián Heredia', 'elian.heredia.client160@trimly-demo.com', '+5493419063545', '2026-05-29', '11:45:00', '15:45:00', 240, 80000.00, 40000.00, 'completed', 'online', 'fully_paid', 'MP-600294', NULL, NULL, 1, NULL, 'tok-canc4-0294', '2026-05-27 20:00:00', '2026-05-29 00:00:00'),
(295, 3, 6, 17, 42, 'Agostina Martínez', 'agostina.martinez19@gmail.com', '+549292011019', '2026-05-15', '17:45:00', '19:15:00', 90, 12000.00, 2400.00, 'completed', 'on_site', 'deposit_paid', NULL, NULL, NULL, 1, NULL, 'tok-canc4-0295', '2026-05-08 18:00:00', '2026-05-15 04:00:00'),
(296, 16, 41, 97, 16, 'Cliente Test', 'cliente.test@trimly.com', '12434342423423423', '2026-05-21', '11:30:00', '12:00:00', 30, 3800.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-600296', 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc4-0296', '2026-05-15 16:00:00', '2026-05-21 03:00:00'),
(297, 11, 18, 48, 81, 'Marcos Diaz', 'marcos.diaz.client168@trimly-demo.com', '+5492617465140', '2026-04-27', '15:15:00', '16:15:00', 60, 8500.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc4-0297', '2026-04-22 14:00:00', '2026-04-27 01:00:00'),
(298, 15, 25, 64, 59, 'Isabella Medina', 'isabella.medina36@gmail.com', '+549292011036', '2026-05-20', '10:30:00', '12:00:00', 90, 28000.00, 14000.00, 'completed', 'deposit', 'deposit_paid', 'MP-600298', NULL, NULL, 1, NULL, 'tok-canc4-0298', '2026-05-15 18:00:00', '2026-05-20 01:00:00'),
(299, 19, 46, 108, 4, 'Camila Díaz', 'camila.diaz@gmail.com', '+5492920100004', '2026-05-19', '15:15:00', '15:45:00', 30, 4500.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc4-0299', '2026-05-17 19:00:00', '2026-05-19 01:00:00'),
(300, 4, 8, 24, 77, 'Kevin Fonseca', 'kevin.fonseca.client164@trimly-demo.com', '+5493419642750', '2026-05-11', '14:00:00', '15:00:00', 60, 10000.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-600300', NULL, NULL, 1, NULL, 'tok-canc4-0300', '2026-05-09 17:00:00', '2026-05-11 04:00:00'),
(301, 12, 19, 54, 78, 'Bianca Espinoza', 'bianca.espinoza.client165@trimly-demo.com', '+5493511845270', '2026-06-17', '18:15:00', '18:35:00', 20, 2500.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Primera visita al local.', NULL, 1, NULL, 'tok-canc4-0301', '2026-06-13 19:00:00', '2026-06-17 02:00:00'),
(302, 18, 45, 106, 45, 'Valentina Domínguez', 'valentina.dominguez22@gmail.com', '+549292011022', '2026-04-28', '15:45:00', '17:45:00', 120, 26000.00, 7800.00, 'completed', 'online', 'fully_paid', 'MP-600302', 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc4-0302', '2026-04-21 21:00:00', '2026-04-28 01:00:00'),
(303, 9, 16, 42, 85, 'Elián Iturbe', 'elian.iturbe.client172@trimly-demo.com', '+5493519724183', '2026-04-28', '16:00:00', '17:15:00', 75, 11000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc4-0303', '2026-04-22 23:00:00', '2026-04-28 01:00:00'),
(304, 9, 16, 45, 25, 'Leandro García', 'leandro.garcia2@gmail.com', '+549292011002', '2026-04-28', '17:15:00', '18:15:00', 60, 12000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Primera visita al local.', NULL, 1, NULL, 'tok-canc4-0304', '2026-04-22 00:00:00', '2026-04-28 00:00:00'),
(305, 20, 48, 111, 53, 'Facundo Ortiz', 'facundo.ortiz30@gmail.com', '+549292011030', '2026-06-14', '16:15:00', '17:15:00', 60, 16000.00, 8000.00, 'completed', 'on_site', 'fully_paid', NULL, 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc4-0305', '2026-06-11 23:00:00', '2026-06-14 03:00:00'),
(306, 8, 14, 38, 3, 'Javier Molina', 'javier.molina@hotmail.com', '+5492920100003', '2026-04-29', '18:45:00', '19:35:00', 50, 7000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc4-0306', '2026-04-27 15:00:00', '2026-04-29 02:00:00'),
(307, 6, 12, 31, 21, 'Cliente Test', 'cliente.test@trimly.com', '', '2026-05-29', '10:30:00', '11:45:00', 75, 14000.00, 2800.00, 'completed', 'on_site', 'fully_paid', NULL, NULL, NULL, 1, NULL, 'tok-canc4-0307', '2026-05-26 15:00:00', '2026-05-29 01:00:00'),
(308, 3, 6, 18, 35, 'Abril Suárez', 'abril.suarez12@gmail.com', '+549292011012', '2026-06-16', '18:45:00', '19:15:00', 30, 3000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc4-0308', '2026-06-09 16:00:00', '2026-06-16 01:00:00'),
(309, 15, 24, 66, 74, 'Ciro Espinoza', 'ciro.espinoza.client161@trimly-demo.com', '+5492611160882', '2026-06-04', '17:15:00', '19:45:00', 150, 45000.00, 22500.00, 'completed', 'online', 'fully_paid', 'MP-600309', 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc4-0309', '2026-05-29 00:00:00', '2026-06-04 00:00:00'),
(310, 16, 41, 98, 78, 'Bianca Espinoza', 'bianca.espinoza.client165@trimly-demo.com', '+5493511845270', '2026-05-24', '09:45:00', '10:45:00', 60, 8000.00, 1600.00, 'completed', 'deposit', 'deposit_paid', 'MP-600310', NULL, NULL, 1, NULL, 'tok-canc4-0310', '2026-05-17 21:00:00', '2026-05-24 04:00:00'),
(311, 4, 8, 20, 74, 'Ciro Espinoza', 'ciro.espinoza.client161@trimly-demo.com', '+5492611160882', '2026-05-22', '16:45:00', '17:45:00', 60, 12000.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, 'Primera visita al local.', NULL, 1, NULL, 'tok-canc4-0311', '2026-05-15 18:00:00', '2026-05-22 01:00:00'),
(312, 2, 4, 10, 48, 'Isabella Suárez', 'isabella.suarez25@gmail.com', '+549292011025', '2026-04-25', '16:00:00', '16:45:00', 45, 5500.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc4-0312', '2026-04-20 19:00:00', '2026-04-25 03:00:00'),
(313, 5, 10, 27, 28, 'Franco Ibarra', 'franco.ibarra5@gmail.com', '+549292011005', '2026-06-18', '14:15:00', '18:15:00', 240, 80000.00, 40000.00, 'completed', 'online', 'fully_paid', 'MP-600313', 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc4-0313', '2026-06-11 22:00:00', '2026-06-18 04:00:00'),
(314, 12, 19, 51, 83, 'Marcos Diaz', 'marcos.diaz.client170@trimly-demo.com', '+5492613913695', '2026-05-08', '10:15:00', '10:55:00', 40, 5000.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-600314', NULL, NULL, 1, NULL, 'tok-canc4-0314', '2026-05-04 00:00:00', '2026-05-08 04:00:00'),
(315, 19, 47, 107, 75, 'Marcos Bustos', 'marcos.bustos.client162@trimly-demo.com', '+5493422609347', '2026-05-06', '12:15:00', '13:00:00', 45, 6000.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc4-0315', '2026-05-03 20:00:00', '2026-05-06 01:00:00'),
(316, 13, 20, 57, 71, 'Ciro Fonseca', 'ciro.fonseca.client158@trimly-demo.com', '+5493427062072', '2026-05-09', '10:45:00', '11:30:00', 45, 6000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc4-0316', '2026-05-06 16:00:00', '2026-05-09 00:00:00'),
(317, 18, 45, 103, 44, 'Renata González', 'renata.gonzalez21@gmail.com', '+549292011021', '2026-05-30', '10:00:00', '11:00:00', 60, 12000.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc4-0317', '2026-05-24 15:00:00', '2026-05-30 02:00:00'),
(318, 11, 18, 50, 8, 'Diego Fernández', 'diego@gmail.com', '+5492920400003', '2026-06-11', '12:00:00', '12:50:00', 50, 7000.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc4-0318', '2026-06-06 23:00:00', '2026-06-11 00:00:00'),
(319, 17, 43, 101, 39, 'Mía Molina', 'mia.molina16@gmail.com', '+549292011016', '2026-06-10', '15:30:00', '16:30:00', 60, 8500.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc4-0319', '2026-06-06 23:00:00', '2026-06-10 01:00:00'),
(320, 9, 16, 44, 82, 'Hernán Iturbe', 'hernan.iturbe.client169@trimly-demo.com', '+5492613557157', '2026-06-07', '12:45:00', '14:00:00', 75, 10000.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc4-0320', '2026-06-04 21:00:00', '2026-06-07 01:00:00'),
(321, 20, 49, 113, 66, 'Gael Espinoza', 'gael.espinoza.client153@trimly-demo.com', '+5492617273405', '2026-04-30', '09:15:00', '12:15:00', 180, 50000.00, 25000.00, 'completed', 'on_site', 'fully_paid', NULL, 'Primera visita al local.', NULL, 1, NULL, 'tok-canc4-0321', '2026-04-26 22:00:00', '2026-04-30 03:00:00'),
(322, 2, 5, 13, 48, 'Isabella Suárez', 'isabella.suarez25@gmail.com', '+549292011025', '2026-05-29', '09:00:00', '11:00:00', 120, 25000.00, 7500.00, 'completed', 'on_site', 'deposit_paid', NULL, NULL, NULL, 1, NULL, 'tok-canc4-0322', '2026-05-24 17:00:00', '2026-05-29 02:00:00'),
(323, 11, 18, 47, 73, 'Elián Heredia', 'elian.heredia.client160@trimly-demo.com', '+5493419063545', '2026-05-16', '12:30:00', '13:15:00', 45, 6000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc4-0323', '2026-05-12 15:00:00', '2026-05-16 01:00:00'),
(324, 16, 40, 96, 26, 'Ezequiel Rivas', 'ezequiel.rivas3@gmail.com', '+549292011003', '2026-06-03', '14:15:00', '15:00:00', 45, 5500.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Primera visita al local.', NULL, 1, NULL, 'tok-canc4-0324', '2026-05-28 16:00:00', '2026-06-03 01:00:00'),
(325, 12, 19, 51, 50, 'Antonella Martínez', 'antonella.martinez27@gmail.com', '+549292011027', '2026-04-30', '14:00:00', '14:40:00', 40, 5000.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc4-0325', '2026-04-26 20:00:00', '2026-04-30 04:00:00'),
(326, 19, 47, 108, 36, 'Agostina Sánchez', 'agostina.sanchez13@gmail.com', '+549292011013', '2026-04-26', '15:15:00', '15:45:00', 30, 4500.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-600326', NULL, NULL, 1, NULL, 'tok-canc4-0326', '2026-04-21 19:00:00', '2026-04-26 00:00:00'),
(327, 4, 9, 23, 31, 'Mía Ibarra', 'mia.ibarra8@gmail.com', '+549292011008', '2026-05-09', '17:00:00', '17:50:00', 50, 9000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, NULL, NULL, 1, NULL, 'tok-canc4-0327', '2026-05-06 20:00:00', '2026-05-09 00:00:00'),
(328, 15, 25, 65, 49, 'Renata Silva', 'renata.silva26@gmail.com', '+549292011026', '2026-06-02', '10:00:00', '10:45:00', 45, 15000.00, 7500.00, 'completed', 'on_site', 'fully_paid', NULL, 'Primera visita al local.', NULL, 1, NULL, 'tok-canc4-0328', '2026-05-27 18:00:00', '2026-06-02 03:00:00'),
(329, 1, 2, 1, 8, 'Diego Fernández', 'diego@gmail.com', '+5492920400003', '2026-05-13', '15:00:00', '15:30:00', 30, 3500.00, 0.00, 'completed', 'deposit', 'unpaid', NULL, 'Pidió referencia previa por WhatsApp.', NULL, 1, NULL, 'tok-canc4-0329', '2026-05-09 21:00:00', '2026-05-13 02:00:00'),
(330, 3, 6, 17, 73, 'Elián Heredia', 'elian.heredia.client160@trimly-demo.com', '+5493419063545', '2026-05-16', '14:30:00', '16:00:00', 90, 12000.00, 2400.00, 'completed', 'deposit', 'deposit_paid', 'MP-600330', 'Primera visita al local.', NULL, 1, NULL, 'tok-canc4-0330', '2026-05-12 14:00:00', '2026-05-16 03:00:00'),
(331, 5, 11, 25, 2, 'Sofía Ruiz', 'sofia.ruiz@gmail.com', '+5492920100002', '2026-05-04', '15:45:00', '16:45:00', 60, 15000.00, 7500.00, 'completed', 'on_site', 'deposit_paid', NULL, NULL, NULL, 1, NULL, 'tok-canc4-0331', '2026-04-28 22:00:00', '2026-05-04 03:00:00'),
(332, 6, 12, 30, 60, 'Isabella Benítez', 'isabella.benitez37@gmail.com', '+549292011037', '2026-05-14', '10:30:00', '11:30:00', 60, 9000.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Primera visita al local.', NULL, 1, NULL, 'tok-canc4-0332', '2026-05-08 00:00:00', '2026-05-14 03:00:00'),
(333, 17, 42, 99, 61, 'Lucía Medina', 'lucia.medina38@gmail.com', '+549292011038', '2026-06-02', '10:15:00', '11:45:00', 90, 13000.00, 2600.00, 'completed', 'on_site', 'fully_paid', NULL, 'Primera visita al local.', NULL, 1, NULL, 'tok-canc4-0333', '2026-05-29 23:00:00', '2026-06-02 02:00:00'),
(334, 8, 14, 37, 48, 'Isabella Suárez', 'isabella.suarez25@gmail.com', '+549292011025', '2026-05-16', '15:45:00', '17:45:00', 120, 22000.00, 6600.00, 'completed', 'on_site', 'fully_paid', NULL, 'Primera visita al local.', NULL, 1, NULL, 'tok-canc4-0334', '2026-05-12 17:00:00', '2026-05-16 03:00:00'),
(335, 18, 45, 106, 41, 'Valentina Cabrera', 'valentina.cabrera18@gmail.com', '+549292011018', '2026-06-05', '13:00:00', '15:00:00', 120, 26000.00, 7800.00, 'completed', 'deposit', 'fully_paid', 'MP-600335', 'Primera visita al local.', NULL, 1, NULL, 'tok-canc4-0335', '2026-06-02 21:00:00', '2026-06-05 03:00:00'),
(336, 13, 21, 58, 8, 'Diego Fernández', 'diego@gmail.com', '+5492920400003', '2026-05-05', '13:45:00', '14:45:00', 60, 7500.00, 0.00, 'completed', 'on_site', 'unpaid', NULL, 'Primera visita al local.', NULL, 1, NULL, 'tok-canc4-0336', '2026-04-29 00:00:00', '2026-05-05 01:00:00'),
(337, 11, 18, 49, 62, 'Thiago Sánchez', 'thiago.sanchez39@gmail.com', '+549292011039', '2026-06-08', '14:30:00', '16:30:00', 120, 25000.00, 7500.00, 'completed', 'online', 'fully_paid', 'MP-600337', 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc4-0337', '2026-06-04 18:00:00', '2026-06-08 03:00:00'),
(338, 15, 25, 64, 71, 'Ciro Fonseca', 'ciro.fonseca.client158@trimly-demo.com', '+5493427062072', '2026-05-23', '11:30:00', '13:00:00', 90, 28000.00, 14000.00, 'completed', 'deposit', 'deposit_paid', 'MP-600338', 'Primera visita al local.', NULL, 1, NULL, 'tok-canc4-0338', '2026-05-20 19:00:00', '2026-05-23 03:00:00'),
(339, 17, 43, 100, 2, 'Sofía Ruiz', 'sofia.ruiz@gmail.com', '+5492920100002', '2026-05-18', '17:45:00', '18:30:00', 45, 6500.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-600339', 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc4-0339', '2026-05-16 20:00:00', '2026-05-18 03:00:00'),
(340, 18, 44, 105, 65, 'Iara Juárez', 'iara.juarez.client152@trimly-demo.com', '+5493512387122', '2026-06-04', '11:00:00', '12:00:00', 60, 11000.00, 0.00, 'completed', 'online', 'fully_paid', 'MP-600340', 'Cliente llegó puntual.', NULL, 1, NULL, 'tok-canc4-0340', '2026-05-28 16:00:00', '2026-06-04 02:00:00'),
(341, 2, 4, 13, 39, 'Mía Molina', 'mia.molina16@gmail.com', '+549292011016', '2026-06-11', '16:00:00', '18:00:00', 120, 25000.00, 7500.00, 'completed', 'on_site', 'deposit_paid', NULL, NULL, NULL, 1, NULL, 'tok-canc4-0341', '2026-06-09 16:00:00', '2026-06-11 00:00:00');

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
(11, 25, 'Cliente Test', 'cliente.test@trimly.com', '', NULL, '2026-06-10 01:55:51'),
(12, NULL, 'Cliente Test', 'cliente.test@trimly.com', '12434342423423423', NULL, '2026-06-10 19:55:47'),
(13, NULL, 'Cliente Test', 'cliente.test@trimly.com', '', NULL, '2026-06-10 20:03:17'),
(14, NULL, 'Cliente Test', 'cliente.test@trimly.com', '', NULL, '2026-06-11 00:15:57'),
(15, NULL, 'Cliente Test', 'cliente.test@trimly.com', '', NULL, '2026-06-11 23:00:11'),
(16, NULL, 'Cliente Test', 'cliente.test@trimly.com', '12434342423423423', NULL, '2026-06-12 18:18:08'),
(17, NULL, 'Cliente Test', 'cliente.test@trimly.com', '12434342423423423', NULL, '2026-06-13 00:47:28'),
(18, NULL, 'Cliente Test', 'admin@trimly.com', '12434342423423423', NULL, '2026-06-13 00:48:20'),
(19, NULL, 'Cliente Test', 'cliente.test@trimly.com', '', NULL, '2026-06-14 20:18:32'),
(20, NULL, 'Cliente Test', 'cliente.test@trimly.com', '', NULL, '2026-06-14 20:18:56'),
(21, NULL, 'Cliente Test', 'cliente.test@trimly.com', '', NULL, '2026-06-14 20:19:01'),
(22, NULL, 'Cliente Test', 'cliente.test@trimly.com', '', NULL, '2026-06-14 20:19:25'),
(23, 28, 'Valentina Ortiz', 'valentina.ortiz0@gmail.com', '+549292011000', NULL, '2026-05-18 09:16:00'),
(24, 29, 'Morena López', 'morena.lopez1@gmail.com', '+549292011001', 'Piel sensible.', '2026-06-07 13:25:00'),
(25, 30, 'Leandro García', 'leandro.garcia2@gmail.com', '+549292011002', 'Piel sensible.', '2026-06-05 07:44:00'),
(26, 31, 'Ezequiel Rivas', 'ezequiel.rivas3@gmail.com', '+549292011003', NULL, '2026-05-18 02:00:00'),
(27, 32, 'Agostina Suárez', 'agostina.suarez4@gmail.com', '+549292011004', NULL, '2026-05-10 08:22:00'),
(28, 33, 'Franco Ibarra', 'franco.ibarra5@gmail.com', '+549292011005', 'Cliente frecuente.', '2026-05-23 20:23:00'),
(29, 34, 'Morena Martínez', 'morena.martinez6@gmail.com', '+549292011006', NULL, '2026-05-25 03:52:00'),
(30, 35, 'Milagros Rivas', 'milagros.rivas7@gmail.com', '+549292011007', 'Piel sensible.', '2026-05-14 00:01:00'),
(31, 36, 'Mía Ibarra', 'mia.ibarra8@gmail.com', '+549292011008', 'Alérgica a productos con amoniaco.', '2026-05-15 06:29:00'),
(32, 37, 'Isabella Acosta', 'isabella.acosta9@gmail.com', '+549292011009', 'Piel sensible.', '2026-05-23 09:23:00'),
(33, 38, 'Agustín Vega', 'agustin.vega10@gmail.com', '+549292011010', 'Piel sensible.', '2026-05-16 05:36:00'),
(34, 39, 'Pilar Cabrera', 'pilar.cabrera11@gmail.com', '+549292011011', NULL, '2026-05-21 02:35:00'),
(35, 40, 'Abril Suárez', 'abril.suarez12@gmail.com', '+549292011012', 'Alérgica a productos con amoniaco.', '2026-05-05 07:37:00'),
(36, 41, 'Agostina Sánchez', 'agostina.sanchez13@gmail.com', '+549292011013', NULL, '2026-06-11 15:59:00'),
(37, 42, 'Catalina Suárez', 'catalina.suarez14@gmail.com', '+549292011014', 'Piel sensible.', '2026-05-09 11:02:00'),
(38, 43, 'Julieta Luna', 'julieta.luna15@gmail.com', '+549292011015', 'Alérgica a productos con amoniaco.', '2026-06-07 13:07:00'),
(39, 44, 'Mía Molina', 'mia.molina16@gmail.com', '+549292011016', 'Prefiere turnos por la tarde.', '2026-05-04 04:26:00'),
(40, 45, 'Abril Benítez', 'abril.benitez17@gmail.com', '+549292011017', 'Cliente frecuente.', '2026-06-08 17:41:00'),
(41, 46, 'Valentina Cabrera', 'valentina.cabrera18@gmail.com', '+549292011018', 'Alérgica a productos con amoniaco.', '2026-05-08 22:53:00'),
(42, 47, 'Agostina Martínez', 'agostina.martinez19@gmail.com', '+549292011019', NULL, '2026-05-19 15:24:00'),
(43, 48, 'Isabella Castro', 'isabella.castro20@gmail.com', '+549292011020', NULL, '2026-05-07 21:28:00'),
(44, 49, 'Renata González', 'renata.gonzalez21@gmail.com', '+549292011021', 'Pidió que lo llamen antes de confirmar.', '2026-05-24 06:02:00'),
(45, 50, 'Valentina Domínguez', 'valentina.dominguez22@gmail.com', '+549292011022', NULL, '2026-05-21 16:16:00'),
(46, 51, 'Lucía Romero', 'lucia.romero23@gmail.com', '+549292011023', 'Cliente frecuente.', '2026-05-04 08:01:00'),
(47, 52, 'Maximiliano Sosa', 'maximiliano.sosa24@gmail.com', '+549292011024', 'Alérgica a productos con amoniaco.', '2026-05-05 18:27:00'),
(48, 53, 'Isabella Suárez', 'isabella.suarez25@gmail.com', '+549292011025', NULL, '2026-06-03 20:13:00'),
(49, 54, 'Renata Silva', 'renata.silva26@gmail.com', '+549292011026', 'Pidió que lo llamen antes de confirmar.', '2026-05-20 15:08:00'),
(50, 55, 'Antonella Martínez', 'antonella.martinez27@gmail.com', '+549292011027', NULL, '2026-05-16 09:32:00'),
(51, 56, 'Victoria Romero', 'victoria.romero28@gmail.com', '+549292011028', NULL, '2026-05-01 03:18:00'),
(52, 57, 'Mateo Ibarra', 'mateo.ibarra29@gmail.com', '+549292011029', NULL, '2026-05-22 05:33:00'),
(53, 58, 'Facundo Ortiz', 'facundo.ortiz30@gmail.com', '+549292011030', NULL, '2026-06-06 20:54:00'),
(54, 59, 'Renata Martínez', 'renata.martinez31@gmail.com', '+549292011031', 'Piel sensible.', '2026-05-07 23:06:00'),
(55, 60, 'Mateo Cabrera', 'mateo.cabrera32@gmail.com', '+549292011032', 'Pidió que lo llamen antes de confirmar.', '2026-06-11 20:48:00'),
(56, 61, 'Emma Romero', 'emma.romero33@gmail.com', '+549292011033', NULL, '2026-05-13 07:11:00'),
(57, 62, 'Ezequiel Romero', 'ezequiel.romero34@gmail.com', '+549292011034', 'Cliente frecuente.', '2026-05-05 16:14:00'),
(58, 63, 'Martina Núñez', 'martina.nuñez35@gmail.com', '+549292011035', 'Cliente frecuente.', '2026-06-04 00:20:00'),
(59, 64, 'Isabella Medina', 'isabella.medina36@gmail.com', '+549292011036', 'Prefiere turnos por la tarde.', '2026-06-01 16:11:00'),
(60, 65, 'Isabella Benítez', 'isabella.benitez37@gmail.com', '+549292011037', NULL, '2026-05-01 15:11:00'),
(61, 66, 'Lucía Medina', 'lucia.medina38@gmail.com', '+549292011038', 'Piel sensible.', '2026-06-15 01:25:00'),
(62, 67, 'Thiago Sánchez', 'thiago.sanchez39@gmail.com', '+549292011039', NULL, '2026-05-04 20:36:00'),
(63, 150, 'Helena Diaz', 'helena.diaz.client150@trimly-demo.com', '+5493418355025', NULL, '2026-06-06 21:36:00'),
(64, 151, 'Kevin Iturbe', 'kevin.iturbe.client151@trimly-demo.com', '+5493517186637', NULL, '2026-06-10 13:11:00'),
(65, 152, 'Iara Juárez', 'iara.juarez.client152@trimly-demo.com', '+5493512387122', NULL, '2026-05-25 17:06:00'),
(66, 153, 'Gael Espinoza', 'gael.espinoza.client153@trimly-demo.com', '+5492617273405', NULL, '2026-05-14 16:38:00'),
(67, 154, 'Bianca Leiva', 'bianca.leiva.client154@trimly-demo.com', '+5492615195742', NULL, '2026-05-03 17:37:00'),
(68, 155, 'Karina Cardozo', 'karina.cardozo.client155@trimly-demo.com', '+5493517136346', NULL, '2026-05-16 19:52:00'),
(69, 156, 'Estefanía Fonseca', 'estefania.fonseca.client156@trimly-demo.com', '+5493516784655', NULL, '2026-04-25 22:32:00'),
(70, 157, 'Daniela Leiva', 'daniela.leiva.client157@trimly-demo.com', '+5492615961129', NULL, '2026-04-28 15:58:00'),
(71, 158, 'Ciro Fonseca', 'ciro.fonseca.client158@trimly-demo.com', '+5493427062072', NULL, '2026-05-23 01:03:00'),
(72, 159, 'Bianca Leiva', 'bianca.leiva.client159@trimly-demo.com', '+5492615404127', NULL, '2026-05-14 00:42:00'),
(73, 160, 'Elián Heredia', 'elian.heredia.client160@trimly-demo.com', '+5493419063545', NULL, '2026-06-09 05:58:00'),
(74, 161, 'Ciro Espinoza', 'ciro.espinoza.client161@trimly-demo.com', '+5492611160882', NULL, '2026-05-16 15:50:00'),
(75, 162, 'Marcos Bustos', 'marcos.bustos.client162@trimly-demo.com', '+5493422609347', NULL, '2026-05-26 19:20:00'),
(76, 163, 'Hernán Fonseca', 'hernan.fonseca.client163@trimly-demo.com', '+5493513724040', NULL, '2026-05-02 22:36:00'),
(77, 164, 'Kevin Fonseca', 'kevin.fonseca.client164@trimly-demo.com', '+5493419642750', NULL, '2026-05-10 03:31:00'),
(78, 165, 'Bianca Espinoza', 'bianca.espinoza.client165@trimly-demo.com', '+5493511845270', NULL, '2026-06-06 06:17:00'),
(79, 166, 'Bruno Klein', 'bruno.klein.client166@trimly-demo.com', '+5493513588395', NULL, '2026-05-29 00:38:00'),
(80, 167, 'Ciro Bustos', 'ciro.bustos.client167@trimly-demo.com', '+5492617707293', NULL, '2026-05-31 17:12:00'),
(81, 168, 'Marcos Diaz', 'marcos.diaz.client168@trimly-demo.com', '+5492617465140', NULL, '2026-06-09 20:52:00'),
(82, 169, 'Hernán Iturbe', 'hernan.iturbe.client169@trimly-demo.com', '+5492613557157', NULL, '2026-05-24 04:47:00'),
(83, 170, 'Marcos Diaz', 'marcos.diaz.client170@trimly-demo.com', '+5492613913695', NULL, '2026-04-29 02:35:00'),
(84, 171, 'Julián Espinoza', 'julian.espinoza.client171@trimly-demo.com', '+5493419152260', NULL, '2026-05-28 07:28:00'),
(85, 172, 'Elián Iturbe', 'elian.iturbe.client172@trimly-demo.com', '+5493519724183', NULL, '2026-06-09 01:15:00'),
(86, 173, 'Marcos Juárez', 'marcos.juarez.client173@trimly-demo.com', '+5493414512459', NULL, '2026-05-03 04:22:00'),
(87, 174, 'Giuliana Fonseca', 'giuliana.fonseca.client174@trimly-demo.com', '+5493419445632', NULL, '2026-05-07 08:05:00');

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
(17, 1, 27, 'insanos eticos', 'wazaaaaaaaaaaaa', 'employees/img_6a2b6601bd4329.47190921.png', '', 'korven__group', 'active', 0, '2026-06-11 22:28:17', '2026-06-11 22:51:15'),
(18, 11, 73, 'Damián Romero', 'Especialista en corte & color con varios años de experiencia.', NULL, 'Corte & Color', NULL, 'active', 1, '2026-06-11 00:00:00', '2026-06-11 00:00:00'),
(19, 12, 74, 'Martina Domínguez', 'Especialista en barba & navaja con varios años de experiencia.', NULL, 'Barba & Navaja', NULL, 'active', 1, '2026-06-11 00:00:00', '2026-06-11 00:00:00'),
(20, 13, 75, 'Damián Rivas', 'Especialista en acrílico & 3d con varios años de experiencia.', NULL, 'Acrílico & 3D', NULL, 'active', 1, '2026-05-20 00:00:00', '2026-05-20 00:00:00'),
(21, 13, 76, 'Delfina Sánchez', 'Especialista en acrílico & 3d con varios años de experiencia.', NULL, 'Acrílico & 3D', NULL, 'active', 2, '2026-05-20 00:00:00', '2026-05-20 00:00:00'),
(22, 14, 77, 'Agostina Romero', 'Especialista en faciales con varios años de experiencia.', NULL, 'Faciales', NULL, 'active', 1, '2026-06-09 00:00:00', '2026-06-09 00:00:00'),
(23, 14, 78, 'Facundo Cabrera', 'Especialista en faciales con varios años de experiencia.', NULL, 'Faciales', NULL, 'active', 2, '2026-06-09 00:00:00', '2026-06-09 00:00:00'),
(24, 15, 79, 'Antonella Flores', 'Especialista en fine line con varios años de experiencia.', NULL, 'Fine Line', NULL, 'active', 1, '2026-05-18 00:00:00', '2026-05-18 00:00:00'),
(25, 15, 80, 'Mía García', 'Especialista en geométrico & lettering con varios años de experiencia.', NULL, 'Geométrico & Lettering', NULL, 'active', 2, '2026-05-18 00:00:00', '2026-05-18 00:00:00'),
(36, 7, 131, 'Julián Iturbe', 'Especialista en fade & diseño con buena trayectoria en el rubro.', NULL, 'Fade & Diseño', NULL, 'active', 1, '2026-06-09 03:10:00', '2026-06-09 03:10:00'),
(37, 7, 132, 'Jazmín Juárez', 'Especialista en barba & navaja con buena trayectoria en el rubro.', NULL, 'Barba & Navaja', NULL, 'active', 2, '2026-06-09 03:10:00', '2026-06-09 03:10:00'),
(38, 10, 133, 'Iara Heredia', 'Especialista en corte & color con buena trayectoria en el rubro.', NULL, 'Corte & Color', NULL, 'active', 1, '2026-06-10 03:05:51', '2026-06-10 03:05:51'),
(39, 10, 134, 'Kevin Bustos', 'Especialista en estilismo general con buena trayectoria en el rubro.', NULL, 'Estilismo General', NULL, 'active', 2, '2026-06-10 03:05:51', '2026-06-10 03:05:51'),
(40, 16, 140, 'Bruno Bustos', 'Especialista en fade & diseño con sólida trayectoria en el rubro.', NULL, 'Fade & Diseño', NULL, 'active', 1, '2026-04-20 10:00:00', '2026-04-20 10:00:00'),
(41, 16, 141, 'Bianca Alvarez', 'Especialista en barba & navaja con sólida trayectoria en el rubro.', NULL, 'Barba & Navaja', NULL, 'active', 2, '2026-04-20 10:00:00', '2026-04-20 10:00:00'),
(42, 17, 142, 'Clara Juárez', 'Especialista en esculpidas & 3d con sólida trayectoria en el rubro.', NULL, 'Esculpidas & 3D', NULL, 'active', 1, '2026-04-25 11:00:00', '2026-04-25 11:00:00'),
(43, 17, 143, 'Marcos Diaz', 'Especialista en nail art con sólida trayectoria en el rubro.', NULL, 'Nail Art', NULL, 'active', 2, '2026-04-25 11:00:00', '2026-04-25 11:00:00'),
(44, 18, 144, 'Ciro Juárez', 'Especialista en masajes terapéuticos con sólida trayectoria en el rubro.', NULL, 'Masajes Terapéuticos', NULL, 'active', 1, '2026-04-28 12:00:00', '2026-04-28 12:00:00'),
(45, 18, 145, 'Kevin Fonseca', 'Especialista en faciales con sólida trayectoria en el rubro.', NULL, 'Faciales', NULL, 'active', 2, '2026-04-28 12:00:00', '2026-04-28 12:00:00'),
(46, 19, 146, 'Kevin Leiva', 'Especialista en corte & color con sólida trayectoria en el rubro.', NULL, 'Corte & Color', NULL, 'active', 1, '2026-05-02 10:30:00', '2026-05-02 10:30:00'),
(47, 19, 147, 'Florencia Klein', 'Especialista en estilismo general con sólida trayectoria en el rubro.', NULL, 'Estilismo General', NULL, 'active', 2, '2026-05-02 10:30:00', '2026-05-02 10:30:00'),
(48, 20, 148, 'Ciro Diaz', 'Especialista en blackwork & realismo con sólida trayectoria en el rubro.', NULL, 'Blackwork & Realismo', NULL, 'active', 1, '2026-05-06 15:00:00', '2026-05-06 15:00:00'),
(49, 20, 149, 'Iara Giménez', 'Especialista en fine line & piercing con sólida trayectoria en el rubro.', NULL, 'Fine Line & Piercing', NULL, 'active', 2, '2026-05-06 15:00:00', '2026-05-06 15:00:00');

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
(92, 17, 5, '09:00:00', '18:00:00'),
(93, 18, 1, '09:00:00', '19:00:00'),
(94, 18, 2, '09:00:00', '19:00:00'),
(95, 18, 3, '09:00:00', '19:00:00'),
(96, 18, 4, '09:00:00', '19:00:00'),
(97, 18, 5, '09:00:00', '19:00:00'),
(98, 18, 6, '10:00:00', '15:00:00'),
(99, 19, 1, '09:00:00', '19:00:00'),
(100, 19, 2, '09:00:00', '19:00:00'),
(101, 19, 3, '09:00:00', '19:00:00'),
(102, 19, 4, '09:00:00', '19:00:00'),
(103, 19, 5, '09:00:00', '19:00:00'),
(104, 20, 1, '09:00:00', '19:00:00'),
(105, 20, 2, '09:00:00', '19:00:00'),
(106, 20, 3, '09:00:00', '19:00:00'),
(107, 20, 4, '09:00:00', '19:00:00'),
(108, 20, 5, '09:00:00', '19:00:00'),
(109, 20, 6, '10:00:00', '15:00:00'),
(110, 21, 1, '09:00:00', '19:00:00'),
(111, 21, 2, '09:00:00', '19:00:00'),
(112, 21, 3, '09:00:00', '19:00:00'),
(113, 21, 4, '09:00:00', '19:00:00'),
(114, 21, 5, '09:00:00', '19:00:00'),
(115, 21, 6, '10:00:00', '15:00:00'),
(116, 22, 1, '09:00:00', '19:00:00'),
(117, 22, 2, '09:00:00', '19:00:00'),
(118, 22, 3, '09:00:00', '19:00:00'),
(119, 22, 4, '09:00:00', '19:00:00'),
(120, 22, 5, '09:00:00', '19:00:00'),
(121, 22, 6, '10:00:00', '15:00:00'),
(122, 23, 1, '09:00:00', '19:00:00'),
(123, 23, 2, '09:00:00', '19:00:00'),
(124, 23, 3, '09:00:00', '19:00:00'),
(125, 23, 4, '09:00:00', '19:00:00'),
(126, 23, 5, '09:00:00', '19:00:00'),
(127, 23, 6, '10:00:00', '15:00:00'),
(128, 24, 1, '09:00:00', '19:00:00'),
(129, 24, 2, '09:00:00', '19:00:00'),
(130, 24, 3, '09:00:00', '19:00:00'),
(131, 24, 4, '09:00:00', '19:00:00'),
(132, 24, 5, '09:00:00', '19:00:00'),
(133, 25, 1, '09:00:00', '19:00:00'),
(134, 25, 2, '09:00:00', '19:00:00'),
(135, 25, 3, '09:00:00', '19:00:00'),
(136, 25, 4, '09:00:00', '19:00:00'),
(137, 25, 5, '09:00:00', '19:00:00'),
(138, 25, 6, '10:00:00', '15:00:00'),
(199, 36, 1, '09:00:00', '19:00:00'),
(200, 36, 2, '09:00:00', '19:00:00'),
(201, 36, 3, '09:00:00', '19:00:00'),
(202, 36, 4, '09:00:00', '19:00:00'),
(203, 36, 5, '09:00:00', '19:00:00'),
(204, 36, 6, '09:00:00', '14:00:00'),
(205, 37, 1, '09:00:00', '19:00:00'),
(206, 37, 2, '09:00:00', '19:00:00'),
(207, 37, 3, '09:00:00', '19:00:00'),
(208, 37, 4, '09:00:00', '19:00:00'),
(209, 37, 5, '09:00:00', '19:00:00'),
(210, 37, 6, '09:00:00', '14:00:00'),
(211, 38, 1, '09:00:00', '19:00:00'),
(212, 38, 2, '09:00:00', '19:00:00'),
(213, 38, 3, '09:00:00', '19:00:00'),
(214, 38, 4, '09:00:00', '19:00:00'),
(215, 38, 5, '09:00:00', '19:00:00'),
(216, 38, 6, '09:00:00', '14:00:00'),
(217, 39, 1, '09:00:00', '19:00:00'),
(218, 39, 2, '09:00:00', '19:00:00'),
(219, 39, 3, '09:00:00', '19:00:00'),
(220, 39, 4, '09:00:00', '19:00:00'),
(221, 39, 5, '09:00:00', '19:00:00'),
(222, 39, 6, '09:00:00', '14:00:00'),
(223, 40, 1, '09:00:00', '19:00:00'),
(224, 40, 2, '09:00:00', '19:00:00'),
(225, 40, 3, '09:00:00', '19:00:00'),
(226, 40, 4, '09:00:00', '19:00:00'),
(227, 40, 5, '09:00:00', '19:00:00'),
(228, 40, 6, '09:00:00', '14:00:00'),
(229, 41, 1, '09:00:00', '19:00:00'),
(230, 41, 2, '09:00:00', '19:00:00'),
(231, 41, 3, '09:00:00', '19:00:00'),
(232, 41, 4, '09:00:00', '19:00:00'),
(233, 41, 5, '09:00:00', '19:00:00'),
(234, 41, 6, '09:00:00', '14:00:00'),
(235, 42, 1, '09:00:00', '19:00:00'),
(236, 42, 2, '09:00:00', '19:00:00'),
(237, 42, 3, '09:00:00', '19:00:00'),
(238, 42, 4, '09:00:00', '19:00:00'),
(239, 42, 5, '09:00:00', '19:00:00'),
(240, 42, 6, '09:00:00', '14:00:00'),
(241, 43, 1, '09:00:00', '19:00:00'),
(242, 43, 2, '09:00:00', '19:00:00'),
(243, 43, 3, '09:00:00', '19:00:00'),
(244, 43, 4, '09:00:00', '19:00:00'),
(245, 43, 5, '09:00:00', '19:00:00'),
(246, 43, 6, '09:00:00', '14:00:00'),
(247, 44, 1, '09:00:00', '19:00:00'),
(248, 44, 2, '09:00:00', '19:00:00'),
(249, 44, 3, '09:00:00', '19:00:00'),
(250, 44, 4, '09:00:00', '19:00:00'),
(251, 44, 5, '09:00:00', '19:00:00'),
(252, 44, 6, '09:00:00', '14:00:00'),
(253, 45, 1, '09:00:00', '19:00:00'),
(254, 45, 2, '09:00:00', '19:00:00'),
(255, 45, 3, '09:00:00', '19:00:00'),
(256, 45, 4, '09:00:00', '19:00:00'),
(257, 45, 5, '09:00:00', '19:00:00'),
(258, 45, 6, '09:00:00', '14:00:00'),
(259, 46, 1, '09:00:00', '19:00:00'),
(260, 46, 2, '09:00:00', '19:00:00'),
(261, 46, 3, '09:00:00', '19:00:00'),
(262, 46, 4, '09:00:00', '19:00:00'),
(263, 46, 5, '09:00:00', '19:00:00'),
(264, 46, 6, '09:00:00', '14:00:00'),
(265, 47, 1, '09:00:00', '19:00:00'),
(266, 47, 2, '09:00:00', '19:00:00'),
(267, 47, 3, '09:00:00', '19:00:00'),
(268, 47, 4, '09:00:00', '19:00:00'),
(269, 47, 5, '09:00:00', '19:00:00'),
(270, 47, 6, '09:00:00', '14:00:00'),
(271, 48, 1, '09:00:00', '19:00:00'),
(272, 48, 2, '09:00:00', '19:00:00'),
(273, 48, 3, '09:00:00', '19:00:00'),
(274, 48, 4, '09:00:00', '19:00:00'),
(275, 48, 5, '09:00:00', '19:00:00'),
(276, 48, 6, '09:00:00', '14:00:00'),
(277, 49, 1, '09:00:00', '19:00:00'),
(278, 49, 2, '09:00:00', '19:00:00'),
(279, 49, 3, '09:00:00', '19:00:00'),
(280, 49, 4, '09:00:00', '19:00:00'),
(281, 49, 5, '09:00:00', '19:00:00'),
(282, 49, 6, '09:00:00', '14:00:00');

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
(14, 17, 'employees/img_6a2c7b70573985.06725645.png', '', '2026-06-12 18:34:40'),
(15, 17, 'employees/img_6a2c7b7486ac92.38249040.png', '', '2026-06-12 18:34:44'),
(17, 17, 'employees/img_6a2cd60a9f4f70.61877542.png', '', '2026-06-13 01:01:14'),
(18, 17, 'employees/img_6a2cd610185800.32814350.png', '', '2026-06-13 01:01:20'),
(19, 17, 'employees/img_6a2cd616308ea7.37679282.png', '', '2026-06-13 01:01:26'),
(20, 17, 'employees/img_6a2cd61af32177.89889581.png', '', '2026-06-13 01:01:30'),
(21, 17, 'employees/img_6a2cd620244496.97413779.png', '', '2026-06-13 01:01:36'),
(22, 17, 'employees/img_6a2cd624b36195.34480054.png', '', '2026-06-13 01:01:40'),
(23, 17, 'employees/img_6a2cd62cc7bc34.26210831.png', '', '2026-06-13 01:01:48'),
(26, 17, 'employees/img_6a2cd715769fa0.20983387.png', 'el negro teklaaaa', '2026-06-13 01:05:41'),
(27, 17, 'employees/img_6a2cd7222f2733.16235617.png', 'los pelucas sapeeeeen', '2026-06-13 01:05:54'),
(28, 17, 'employees/img_6a33757d4ffe65.10682433.png', 'miren mi gran trabajo', '2026-06-18 01:35:09');

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
(17, 7, NULL, NULL),
(18, 47, NULL, NULL),
(18, 48, NULL, NULL),
(18, 49, NULL, NULL),
(18, 50, NULL, NULL),
(19, 51, NULL, NULL),
(19, 52, NULL, NULL),
(19, 53, NULL, NULL),
(19, 54, NULL, NULL),
(20, 55, NULL, NULL),
(20, 56, NULL, NULL),
(20, 57, NULL, NULL),
(20, 58, NULL, NULL),
(21, 55, NULL, NULL),
(21, 56, NULL, NULL),
(21, 57, NULL, NULL),
(21, 58, NULL, NULL),
(22, 59, NULL, NULL),
(22, 60, NULL, NULL),
(22, 61, NULL, NULL),
(22, 62, NULL, NULL),
(23, 59, NULL, NULL),
(23, 60, NULL, NULL),
(23, 61, NULL, NULL),
(23, 62, NULL, NULL),
(24, 63, NULL, NULL),
(24, 64, NULL, NULL),
(24, 65, NULL, NULL),
(24, 66, NULL, NULL),
(25, 63, NULL, NULL),
(25, 64, NULL, NULL),
(25, 65, NULL, NULL),
(25, 66, NULL, NULL),
(36, 87, NULL, NULL),
(36, 88, NULL, NULL),
(36, 89, NULL, NULL),
(36, 90, NULL, NULL),
(37, 87, NULL, NULL),
(37, 88, NULL, NULL),
(37, 89, NULL, NULL),
(37, 90, NULL, NULL),
(38, 91, NULL, NULL),
(38, 92, NULL, NULL),
(38, 93, NULL, NULL),
(38, 94, NULL, NULL),
(39, 91, NULL, NULL),
(39, 92, NULL, NULL),
(39, 93, NULL, NULL),
(39, 94, NULL, NULL),
(40, 95, NULL, NULL),
(40, 96, NULL, NULL),
(40, 97, NULL, NULL),
(40, 98, NULL, NULL),
(41, 95, NULL, NULL),
(41, 96, NULL, NULL),
(41, 97, NULL, NULL),
(41, 98, NULL, NULL),
(42, 99, NULL, NULL),
(42, 100, NULL, NULL),
(42, 101, NULL, NULL),
(42, 102, NULL, NULL),
(43, 99, NULL, NULL),
(43, 100, NULL, NULL),
(43, 101, NULL, NULL),
(43, 102, NULL, NULL),
(44, 103, NULL, NULL),
(44, 104, NULL, NULL),
(44, 105, NULL, NULL),
(44, 106, NULL, NULL),
(45, 103, NULL, NULL),
(45, 104, NULL, NULL),
(45, 105, NULL, NULL),
(45, 106, NULL, NULL),
(46, 107, NULL, NULL),
(46, 108, NULL, NULL),
(46, 109, NULL, NULL),
(46, 110, NULL, NULL),
(47, 107, NULL, NULL),
(47, 108, NULL, NULL),
(47, 109, NULL, NULL),
(47, 110, NULL, NULL),
(48, 111, NULL, NULL),
(48, 112, NULL, NULL),
(48, 113, NULL, NULL),
(48, 114, NULL, NULL),
(49, 111, NULL, NULL),
(49, 112, NULL, NULL),
(49, 113, NULL, NULL),
(49, 114, NULL, NULL);

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
(14, 6, '2026-06-07 10:00:00'),
(28, 1, '2026-05-30 18:00:00'),
(29, 11, '2026-06-14 12:00:00'),
(31, 13, '2026-06-10 16:00:00'),
(34, 6, '2026-06-07 17:00:00'),
(35, 6, '2026-05-16 04:00:00'),
(35, 8, '2026-05-19 15:00:00'),
(36, 4, '2026-05-31 05:00:00'),
(40, 12, '2026-06-13 02:00:00'),
(41, 6, '2026-05-16 01:00:00'),
(41, 12, '2026-05-28 18:00:00'),
(41, 14, '2026-06-05 04:00:00'),
(42, 5, '2026-05-17 23:00:00'),
(45, 5, '2026-06-04 12:00:00'),
(48, 11, '2026-05-26 08:00:00'),
(49, 14, '2026-05-23 18:00:00'),
(50, 11, '2026-06-09 08:00:00'),
(55, 6, '2026-05-21 15:00:00'),
(56, 9, '2026-06-07 16:00:00'),
(56, 12, '2026-05-20 10:00:00'),
(60, 5, '2026-06-11 03:00:00'),
(61, 15, '2026-06-13 20:00:00'),
(65, 12, '2026-06-12 16:00:00'),
(66, 4, '2026-06-02 14:00:00'),
(66, 11, '2026-06-01 04:00:00'),
(66, 13, '2026-05-19 04:00:00');

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
(8, 1, 'shop_pending', 'Nuevo local pendiente', 'Barbería del Norte fue registrada y espera aprobación.', '/admin/locales', NULL, '2026-06-09 02:05:00', '2026-06-09 02:01:00'),
(9, 72, 'booking_confirmed', 'Turno confirmado', 'Tu turno fue confirmado. ¡Te esperamos!', '/mis-turnos', NULL, NULL, '2026-06-16 20:00:00'),
(10, 63, 'reminder', 'Recordatorio de turno', 'Tenés un turno próximamente. ¡No lo olvides!', '/mis-turnos', NULL, NULL, '2026-06-02 14:00:00'),
(11, 54, 'booking_new', 'Nuevo turno recibido', 'Se registró un nuevo turno en tu local.', '/panel/turnos', NULL, '2026-06-15 15:42:00', '2026-06-15 14:00:00'),
(12, 48, 'review_new', 'Nueva reseña en tu local', 'Un cliente dejó una nueva reseña.', '/panel/turnos', NULL, '2026-05-30 23:41:00', '2026-05-30 21:00:00'),
(13, 71, 'review_new', 'Nueva reseña en tu local', 'Un cliente dejó una nueva reseña.', '/panel/turnos', NULL, NULL, '2026-05-22 02:00:00'),
(14, 33, 'review_new', 'Nueva reseña en tu local', 'Un cliente dejó una nueva reseña.', '/panel/turnos', NULL, NULL, '2026-05-23 23:00:00'),
(15, 36, 'appointment_cancelled', 'Turno cancelado', 'Un turno fue cancelado.', '/mis-turnos', NULL, NULL, '2026-05-21 18:00:00'),
(16, 63, 'booking_new', 'Nuevo turno recibido', 'Se registró un nuevo turno en tu local.', '/panel/turnos', NULL, '2026-06-10 10:18:00', '2026-06-10 03:00:00'),
(17, 31, 'booking_new', 'Nuevo turno recibido', 'Se registró un nuevo turno en tu local.', '/panel/turnos', NULL, '2026-06-08 18:56:00', '2026-06-08 09:00:00'),
(18, 60, 'reminder', 'Recordatorio de turno', 'Tenés un turno próximamente. ¡No lo olvides!', '/mis-turnos', NULL, '2026-05-24 22:55:00', '2026-05-24 21:00:00'),
(19, 50, 'appointment_cancelled', 'Turno cancelado', 'Un turno fue cancelado.', '/mis-turnos', NULL, NULL, '2026-05-31 03:00:00'),
(20, 64, 'reminder', 'Recordatorio de turno', 'Tenés un turno próximamente. ¡No lo olvides!', '/mis-turnos', NULL, NULL, '2026-06-14 13:00:00'),
(21, 67, 'appointment_cancelled', 'Turno cancelado', 'Un turno fue cancelado.', '/mis-turnos', NULL, '2026-06-11 00:38:00', '2026-06-10 20:00:00'),
(22, 29, 'reminder', 'Recordatorio de turno', 'Tenés un turno próximamente. ¡No lo olvides!', '/mis-turnos', NULL, NULL, '2026-05-28 22:00:00'),
(23, 49, 'booking_new', 'Nuevo turno recibido', 'Se registró un nuevo turno en tu local.', '/panel/turnos', NULL, NULL, '2026-05-20 05:00:00'),
(24, 64, 'review_new', 'Nueva reseña en tu local', 'Un cliente dejó una nueva reseña.', '/panel/turnos', NULL, NULL, '2026-05-22 04:00:00'),
(25, 29, 'booking_confirmed', 'Turno confirmado', 'Tu turno fue confirmado. ¡Te esperamos!', '/mis-turnos', NULL, '2026-06-12 23:14:00', '2026-06-12 16:00:00'),
(26, 57, 'booking_new', 'Nuevo turno recibido', 'Se registró un nuevo turno en tu local.', '/panel/turnos', NULL, '2026-05-25 16:37:00', '2026-05-25 11:00:00'),
(27, 64, 'appointment_cancelled', 'Turno cancelado', 'Un turno fue cancelado.', '/mis-turnos', NULL, '2026-05-22 01:55:00', '2026-05-22 01:00:00'),
(28, 71, 'booking_confirmed', 'Turno confirmado', 'Tu turno fue confirmado. ¡Te esperamos!', '/mis-turnos', NULL, NULL, '2026-05-28 14:00:00');

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
(22, 38, 1, 14, 3, NULL, NULL, NULL, 'assa', 'gracias por tu observacion, esperamos que vuelvas pronto', NULL, 1, 0, NULL, 0, '2026-06-11 00:16:54'),
(23, 45, 1, 21, 5, NULL, NULL, NULL, 'te luciste fachaaa', 'olvidate perro', NULL, 1, 0, NULL, 0, '2026-06-14 20:30:23'),
(24, 82, 14, 32, 5, 5, 5, 5, 'Excelente atención, el resultado superó mis expectativas. Totalmente recomendable.', NULL, NULL, 1, 0, NULL, 3, '2026-06-03 20:00:00'),
(25, 51, 15, 30, 5, 4, 5, 5, 'Un golazo de atención, me sentí muy cómodo todo el tiempo.', 'Muchas gracias por la confianza, fue un placer atenderte.', '2026-06-01 02:00:00', 1, 0, NULL, 7, '2026-05-31 20:00:00'),
(26, 54, 14, 62, 3, 3, 4, 3, 'Buen servicio en general, aunque tardaron un poco más de lo esperado.', 'Muchas gracias por la confianza, fue un placer atenderte.', '2026-06-24 01:00:00', 1, 0, NULL, 0, '2026-06-23 04:00:00'),
(27, 55, 15, 51, 4, 4, 3, 5, 'El resultado estuvo bien, el precio me pareció algo elevado.', NULL, NULL, 1, 0, NULL, 5, '2026-06-15 02:00:00'),
(28, 66, 11, 24, 3, 4, 3, 3, 'El resultado estuvo bien, el precio me pareció algo elevado.', NULL, NULL, 1, 0, NULL, 6, '2026-06-27 19:00:00'),
(29, 80, 13, 38, 4, 4, 4, 4, 'Buen servicio en general, aunque tardaron un poco más de lo esperado.', 'Muchas gracias por la confianza, fue un placer atenderte.', '2026-05-28 18:00:00', 1, 0, NULL, 0, '2026-05-27 23:00:00'),
(30, 56, 13, 42, 3, 3, 3, 4, 'Cumplió con lo prometido, nada extraordinario pero correcto.', NULL, NULL, 1, 0, NULL, 4, '2026-06-01 21:00:00'),
(31, 81, 14, 37, 4, 4, 4, 4, 'Buen servicio en general, aunque tardaron un poco más de lo esperado.', NULL, NULL, 1, 0, NULL, 1, '2026-06-04 17:00:00'),
(32, 86, 14, 48, 3, 4, 3, 3, 'Cumplió con lo prometido, nada extraordinario pero correcto.', '¡Gracias por tu comentario! Te esperamos pronto.', '2026-06-23 01:00:00', 1, 0, NULL, 2, '2026-06-21 23:00:00'),
(33, 58, 15, 58, 5, 5, 5, 5, 'Quedé encantada con el resultado, volveré sin dudas.', NULL, NULL, 1, 0, NULL, 8, '2026-05-30 22:00:00'),
(34, 65, 14, 39, 4, 4, 5, 4, 'El resultado estuvo bien, el precio me pareció algo elevado.', NULL, NULL, 1, 0, NULL, 5, '2026-05-23 16:00:00'),
(35, 63, 14, 44, 4, 4, 5, 4, 'El resultado estuvo bien, el precio me pareció algo elevado.', '¡Gracias por tu comentario! Te esperamos pronto.', '2026-06-11 18:00:00', 1, 0, NULL, 1, '2026-06-10 17:00:00'),
(36, 57, 14, 47, 4, 3, 5, 4, 'Buen servicio en general, aunque tardaron un poco más de lo esperado.', NULL, NULL, 1, 0, NULL, 4, '2026-06-20 02:00:00'),
(37, 50, 12, 35, 5, 5, 4, 5, 'Quedé encantada con el resultado, volveré sin dudas.', '¡Gracias por tu comentario! Te esperamos pronto.', '2026-06-12 08:00:00', 1, 0, NULL, 3, '2026-06-12 00:00:00'),
(38, 76, 13, 40, 4, 5, 3, 4, 'Cumplió con lo prometido, nada extraordinario pero correcto.', NULL, NULL, 1, 0, NULL, 0, '2026-05-22 04:00:00'),
(39, 75, 12, 53, 3, 3, 3, 3, 'El resultado estuvo bien, el precio me pareció algo elevado.', NULL, NULL, 1, 0, NULL, 6, '2026-06-03 22:00:00'),
(40, 60, 11, 40, 5, 5, 5, 4, 'Un golazo de atención, me sentí muy cómodo todo el tiempo.', NULL, NULL, 1, 0, NULL, 7, '2026-06-09 03:00:00'),
(41, 91, 15, 41, 4, 5, 5, 4, 'El resultado estuvo bien, el precio me pareció algo elevado.', '¡Gracias por tu comentario! Te esperamos pronto.', '2026-06-04 05:00:00', 1, 0, NULL, 5, '2026-06-03 22:00:00'),
(142, 192, 7, 29, 4, 4, 4, 5, 'Muy buena atención en general, aunque demoraron un poco más de lo previsto.', NULL, NULL, 1, 0, NULL, 2, '2026-04-28 21:00:00'),
(143, 193, 7, 55, 5, 5, 5, 5, 'Recomendadísimo, el detalle y la dedicación se nota en el resultado final.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-06-15 22:00:00', 1, 0, NULL, 6, '2026-06-14 07:00:00'),
(144, 194, 7, 37, 5, 5, 5, 5, 'Quedé totalmente satisfecho con el resultado, superó lo que esperaba.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-05-15 08:00:00', 1, 0, NULL, 10, '2026-05-14 02:00:00'),
(145, 195, 7, 52, 3, 3, 4, 4, 'Resultado aceptable, esperaba un poco más por el precio.', NULL, NULL, 1, 0, NULL, 8, '2026-05-27 03:00:00'),
(146, 196, 7, 36, 5, 4, 5, 5, 'Quedé totalmente satisfecho con el resultado, superó lo que esperaba.', NULL, NULL, 1, 0, NULL, 5, '2026-06-16 00:00:00'),
(147, 197, 7, 48, 5, 5, 4, 4, 'Recomendadísimo, el detalle y la dedicación se nota en el resultado final.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-05-31 01:00:00', 1, 0, NULL, 8, '2026-05-30 12:00:00'),
(148, 198, 7, 10, 5, 4, 5, 5, 'Me sentí muy cómodo todo el tiempo, ambiente cálido y profesional.', NULL, NULL, 1, 0, NULL, 2, '2026-04-30 01:00:00'),
(149, 199, 7, 25, 4, 4, 4, 5, 'Buen trato y resultado prolijo, repetiría sin dudas.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-05-10 09:00:00', 1, 0, NULL, 5, '2026-05-09 02:00:00'),
(150, 200, 7, 61, 4, 4, 4, 3, 'Muy buena atención en general, aunque demoraron un poco más de lo previsto.', NULL, NULL, 1, 0, NULL, 10, '2026-05-28 15:00:00'),
(151, 201, 7, 56, 4, 4, 5, 5, 'El resultado quedó muy bien, el precio me pareció correcto para la calidad.', 'Gracias por la confianza, fue un placer atenderte.', '2026-06-21 17:00:00', 1, 0, NULL, 1, '2026-06-20 16:00:00'),
(152, 202, 7, 12, 5, 5, 4, 5, 'Me sentí muy cómodo todo el tiempo, ambiente cálido y profesional.', NULL, NULL, 1, 0, NULL, 3, '2026-05-21 19:00:00'),
(153, 203, 7, 57, 5, 5, 5, 5, 'Me sentí muy cómodo todo el tiempo, ambiente cálido y profesional.', NULL, NULL, 1, 0, NULL, 9, '2026-05-29 20:00:00'),
(154, 204, 7, 7, 4, 4, 4, 3, 'El resultado quedó muy bien, el precio me pareció correcto para la calidad.', NULL, NULL, 1, 0, NULL, 4, '2026-05-20 15:00:00'),
(155, 205, 7, 17, 5, 5, 5, 5, 'Una experiencia excelente de principio a fin, el equipo es muy profesional y atento.', 'Agradecemos tu visita, esperamos verte de nuevo pronto.', '2026-05-15 07:00:00', 1, 0, NULL, 9, '2026-05-13 19:00:00'),
(156, 206, 7, 21, 5, 4, 5, 5, 'Recomendadísimo, el detalle y la dedicación se nota en el resultado final.', 'Agradecemos tu visita, esperamos verte de nuevo pronto.', '2026-06-03 10:00:00', 1, 0, NULL, 4, '2026-06-02 06:00:00'),
(157, 207, 7, 50, 5, 5, 5, 4, 'Una experiencia excelente de principio a fin, el equipo es muy profesional y atento.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-05-26 04:00:00', 1, 0, NULL, 6, '2026-05-24 18:00:00'),
(158, 208, 7, 27, 5, 5, 5, 5, 'Puntualidad perfecta y un trabajo prolijo, no tengo quejas.', NULL, NULL, 1, 0, NULL, 3, '2026-05-23 07:00:00'),
(159, 209, 7, 18, 4, 4, 4, 3, 'Muy buena atención en general, aunque demoraron un poco más de lo previsto.', NULL, NULL, 1, 0, NULL, 10, '2026-06-05 02:00:00'),
(160, 210, 7, 31, 4, 5, 5, 4, 'Buen trato y resultado prolijo, repetiría sin dudas.', 'Gracias por la confianza, fue un placer atenderte.', '2026-05-01 06:00:00', 1, 0, NULL, 3, '2026-04-30 00:00:00'),
(161, 211, 7, 10, 5, 4, 5, 5, 'Puntualidad perfecta y un trabajo prolijo, no tengo quejas.', 'Agradecemos tu visita, esperamos verte de nuevo pronto.', '2026-05-27 16:00:00', 1, 0, NULL, 9, '2026-05-27 09:00:00'),
(162, 212, 7, 25, 5, 5, 5, 5, 'El mejor lugar de la zona, siempre vuelvo y siempre recomiendo.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-06-08 20:00:00', 1, 0, NULL, 8, '2026-06-08 07:00:00'),
(163, 213, 7, 9, 4, 4, 3, 3, 'Muy buena atención en general, aunque demoraron un poco más de lo previsto.', 'Gracias por la confianza, fue un placer atenderte.', '2026-05-23 13:00:00', 1, 0, NULL, 8, '2026-05-23 03:00:00'),
(164, 214, 7, 5, 5, 4, 5, 5, 'El mejor lugar de la zona, siempre vuelvo y siempre recomiendo.', NULL, NULL, 1, 0, NULL, 9, '2026-05-19 02:00:00'),
(165, 215, 7, 23, 5, 5, 5, 5, 'Una experiencia excelente de principio a fin, el equipo es muy profesional y atento.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-05-31 22:00:00', 1, 0, NULL, 5, '2026-05-31 02:00:00'),
(166, 216, 7, 21, 4, 5, 4, 4, 'Buen trato y resultado prolijo, repetiría sin dudas.', 'Agradecemos tu visita, esperamos verte de nuevo pronto.', '2026-06-18 23:00:00', 1, 0, NULL, 7, '2026-06-17 09:00:00'),
(167, 217, 7, 18, 5, 5, 5, 5, 'Quedé totalmente satisfecho con el resultado, superó lo que esperaba.', NULL, NULL, 1, 0, NULL, 6, '2026-05-19 02:00:00'),
(168, 218, 7, 44, 5, 5, 5, 4, 'El mejor lugar de la zona, siempre vuelvo y siempre recomiendo.', NULL, NULL, 1, 0, NULL, 4, '2026-05-22 14:00:00'),
(169, 219, 7, 22, 4, 3, 4, 5, 'Muy buena atención en general, aunque demoraron un poco más de lo previsto.', NULL, NULL, 1, 0, NULL, 8, '2026-06-05 00:00:00'),
(170, 220, 7, 6, 5, 5, 5, 4, 'Puntualidad perfecta y un trabajo prolijo, no tengo quejas.', 'Gracias por la confianza, fue un placer atenderte.', '2026-06-08 05:00:00', 1, 0, NULL, 0, '2026-06-07 23:00:00'),
(171, 221, 7, 3, 5, 5, 4, 5, 'Atención impecable y resultado de primer nivel, vale cada peso.', 'Agradecemos tu visita, esperamos verte de nuevo pronto.', '2026-05-12 03:00:00', 1, 0, NULL, 5, '2026-05-10 19:00:00'),
(172, 222, 10, 13, 4, 4, 5, 4, 'Buen trato y resultado prolijo, repetiría sin dudas.', NULL, NULL, 1, 0, NULL, 10, '2026-05-25 16:00:00'),
(173, 223, 10, 12, 5, 5, 5, 5, 'Quedé totalmente satisfecho con el resultado, superó lo que esperaba.', 'Gracias por la confianza, fue un placer atenderte.', '2026-05-03 17:00:00', 1, 0, NULL, 7, '2026-05-02 07:00:00'),
(174, 224, 10, 24, 5, 5, 5, 5, 'Puntualidad perfecta y un trabajo prolijo, no tengo quejas.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-06-08 06:00:00', 1, 0, NULL, 8, '2026-06-07 10:00:00'),
(175, 225, 10, 30, 5, 5, 4, 5, 'Me sentí muy cómodo todo el tiempo, ambiente cálido y profesional.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-05-08 10:00:00', 1, 0, NULL, 10, '2026-05-07 01:00:00'),
(176, 226, 10, 58, 4, 4, 3, 5, 'Buen trato y resultado prolijo, repetiría sin dudas.', 'Agradecemos tu visita, esperamos verte de nuevo pronto.', '2026-05-21 11:00:00', 1, 0, NULL, 10, '2026-05-20 02:00:00'),
(177, 227, 10, 54, 3, 3, 3, 3, 'El servicio estuvo bien pero tardaron más de lo que avisaron.', NULL, NULL, 1, 0, NULL, 10, '2026-05-12 02:00:00'),
(178, 228, 10, 6, 5, 5, 4, 5, 'Quedé totalmente satisfecho con el resultado, superó lo que esperaba.', NULL, NULL, 1, 0, NULL, 10, '2026-05-13 17:00:00'),
(179, 229, 10, 48, 5, 5, 5, 4, 'Quedé totalmente satisfecho con el resultado, superó lo que esperaba.', NULL, NULL, 1, 0, NULL, 10, '2026-05-22 00:00:00'),
(180, 230, 10, 62, 4, 3, 5, 3, 'Buen trato y resultado prolijo, repetiría sin dudas.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-06-08 04:00:00', 1, 0, NULL, 6, '2026-06-08 01:00:00'),
(181, 231, 10, 35, 5, 4, 5, 5, 'Recomendadísimo, el detalle y la dedicación se nota en el resultado final.', 'Agradecemos tu visita, esperamos verte de nuevo pronto.', '2026-05-01 04:00:00', 1, 0, NULL, 4, '2026-04-30 23:00:00'),
(182, 232, 10, 21, 5, 5, 4, 5, 'Quedé totalmente satisfecho con el resultado, superó lo que esperaba.', NULL, NULL, 1, 0, NULL, 9, '2026-06-13 23:00:00'),
(183, 233, 10, 34, 4, 5, 3, 4, 'Buen trato y resultado prolijo, repetiría sin dudas.', 'Gracias por la confianza, fue un placer atenderte.', '2026-04-30 23:00:00', 1, 0, NULL, 9, '2026-04-30 05:00:00'),
(184, 234, 10, 37, 4, 4, 3, 4, 'Local cómodo y buena atención, solo mejoraría la puntualidad.', NULL, NULL, 1, 0, NULL, 2, '2026-05-19 21:00:00'),
(185, 235, 10, 6, 3, 2, 3, 4, 'Resultado aceptable, esperaba un poco más por el precio.', NULL, NULL, 1, 0, NULL, 0, '2026-05-17 02:00:00'),
(186, 236, 10, 11, 5, 5, 5, 5, 'El mejor lugar de la zona, siempre vuelvo y siempre recomiendo.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-06-16 18:00:00', 1, 0, NULL, 0, '2026-06-15 07:00:00'),
(187, 237, 10, 35, 5, 5, 5, 5, 'Atención impecable y resultado de primer nivel, vale cada peso.', NULL, NULL, 1, 0, NULL, 8, '2026-05-29 02:00:00'),
(188, 238, 10, 26, 3, 3, 3, 3, 'Resultado aceptable, esperaba un poco más por el precio.', NULL, NULL, 1, 0, NULL, 7, '2026-05-18 02:00:00'),
(189, 239, 10, 1, 3, 2, 3, 3, 'Cumplió con lo esperado, nada espectacular pero correcto.', NULL, NULL, 1, 0, NULL, 1, '2026-05-24 00:00:00'),
(190, 240, 10, 61, 5, 4, 5, 5, 'Puntualidad perfecta y un trabajo prolijo, no tengo quejas.', 'Gracias por la confianza, fue un placer atenderte.', '2026-05-20 13:00:00', 1, 0, NULL, 2, '2026-05-18 23:00:00'),
(191, 241, 10, 5, 4, 3, 5, 3, 'El resultado quedó muy bien, el precio me pareció correcto para la calidad.', NULL, NULL, 1, 0, NULL, 1, '2026-05-10 06:00:00'),
(192, 242, 10, 29, 4, 4, 3, 3, 'Local cómodo y buena atención, solo mejoraría la puntualidad.', NULL, NULL, 1, 0, NULL, 3, '2026-05-01 01:00:00'),
(193, 243, 10, 46, 4, 5, 4, 4, 'Local cómodo y buena atención, solo mejoraría la puntualidad.', 'Gracias por la confianza, fue un placer atenderte.', '2026-06-22 01:00:00', 1, 0, NULL, 7, '2026-06-21 05:00:00'),
(194, 244, 10, 33, 5, 4, 5, 4, 'Atención impecable y resultado de primer nivel, vale cada peso.', NULL, NULL, 1, 0, NULL, 9, '2026-05-21 04:00:00'),
(195, 245, 10, 54, 5, 5, 5, 5, 'Atención impecable y resultado de primer nivel, vale cada peso.', NULL, NULL, 1, 0, NULL, 0, '2026-05-26 21:00:00'),
(196, 246, 10, 14, 5, 5, 4, 5, 'Me sentí muy cómodo todo el tiempo, ambiente cálido y profesional.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-05-10 09:00:00', 1, 0, NULL, 9, '2026-05-09 19:00:00'),
(197, 247, 10, 27, 5, 5, 5, 5, 'Atención impecable y resultado de primer nivel, vale cada peso.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-05-20 13:00:00', 1, 0, NULL, 1, '2026-05-20 00:00:00'),
(198, 248, 10, 16, 5, 5, 4, 5, 'Atención impecable y resultado de primer nivel, vale cada peso.', NULL, NULL, 1, 0, NULL, 7, '2026-05-05 01:00:00'),
(199, 249, 10, 4, 5, 5, 5, 5, 'El mejor lugar de la zona, siempre vuelvo y siempre recomiendo.', 'Gracias por la confianza, fue un placer atenderte.', '2026-05-25 09:00:00', 1, 0, NULL, 1, '2026-05-23 18:00:00'),
(200, 250, 10, 60, 5, 5, 5, 5, 'Me sentí muy cómodo todo el tiempo, ambiente cálido y profesional.', NULL, NULL, 1, 0, NULL, 0, '2026-06-14 18:00:00'),
(201, 251, 10, 19, 3, 3, 3, 3, 'El servicio estuvo bien pero tardaron más de lo que avisaron.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-05-28 23:00:00', 1, 0, NULL, 10, '2026-05-28 22:00:00'),
(202, 252, 20, 79, 5, 5, 4, 5, 'El mejor lugar de la zona, siempre vuelvo y siempre recomiendo.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-04-27 06:00:00', 1, 0, NULL, 0, '2026-04-25 19:00:00'),
(203, 253, 17, 75, 3, 3, 2, 3, 'Cumplió con lo esperado, nada espectacular pero correcto.', NULL, NULL, 1, 0, NULL, 6, '2026-05-08 02:00:00'),
(204, 254, 16, 30, 5, 4, 5, 4, 'Una experiencia excelente de principio a fin, el equipo es muy profesional y atento.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-06-22 19:00:00', 1, 0, NULL, 6, '2026-06-21 07:00:00'),
(205, 255, 18, 62, 5, 4, 5, 5, 'Una experiencia excelente de principio a fin, el equipo es muy profesional y atento.', 'Gracias por la confianza, fue un placer atenderte.', '2026-05-17 09:00:00', 1, 0, NULL, 3, '2026-05-16 21:00:00'),
(206, 256, 19, 52, 5, 5, 5, 4, 'Atención impecable y resultado de primer nivel, vale cada peso.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-05-26 13:00:00', 1, 0, NULL, 7, '2026-05-25 03:00:00'),
(207, 257, 18, 46, 5, 4, 5, 5, 'Una experiencia excelente de principio a fin, el equipo es muy profesional y atento.', 'Gracias por la confianza, fue un placer atenderte.', '2026-05-10 02:00:00', 1, 0, NULL, 9, '2026-05-10 01:00:00'),
(208, 258, 6, 16, 5, 5, 5, 4, 'Una experiencia excelente de principio a fin, el equipo es muy profesional y atento.', NULL, NULL, 1, 0, NULL, 5, '2026-05-28 02:00:00'),
(209, 259, 20, 61, 3, 3, 2, 3, 'Resultado aceptable, esperaba un poco más por el precio.', 'Agradecemos tu visita, esperamos verte de nuevo pronto.', '2026-04-29 04:00:00', 1, 0, NULL, 2, '2026-04-27 23:00:00'),
(210, 260, 17, 80, 5, 5, 5, 5, 'El mejor lugar de la zona, siempre vuelvo y siempre recomiendo.', 'Gracias por la confianza, fue un placer atenderte.', '2026-05-02 12:00:00', 1, 0, NULL, 9, '2026-05-02 11:00:00'),
(211, 261, 16, 68, 5, 5, 4, 5, 'Atención impecable y resultado de primer nivel, vale cada peso.', 'Gracias por la confianza, fue un placer atenderte.', '2026-06-12 12:00:00', 1, 0, NULL, 10, '2026-06-11 20:00:00'),
(212, 262, 19, 21, 5, 4, 4, 5, 'Superó mis expectativas, ya reservé mi próximo turno.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-06-14 16:00:00', 1, 0, NULL, 7, '2026-06-14 01:00:00'),
(213, 263, 20, 77, 5, 4, 4, 5, 'Superó mis expectativas, ya reservé mi próximo turno.', 'Gracias por la confianza, fue un placer atenderte.', '2026-04-29 15:00:00', 1, 0, NULL, 6, '2026-04-28 11:00:00'),
(214, 264, 2, 72, 4, 5, 4, 4, 'Local cómodo y buena atención, solo mejoraría la puntualidad.', NULL, NULL, 1, 0, NULL, 7, '2026-05-29 02:00:00'),
(215, 265, 17, 82, 3, 4, 3, 3, 'Cumplió con lo esperado, nada espectacular pero correcto.', 'Gracias por la confianza, fue un placer atenderte.', '2026-05-16 20:00:00', 1, 0, NULL, 6, '2026-05-15 22:00:00'),
(216, 266, 6, 50, 5, 4, 5, 5, 'Puntualidad perfecta y un trabajo prolijo, no tengo quejas.', NULL, NULL, 1, 0, NULL, 2, '2026-05-15 08:00:00'),
(217, 267, 18, 12, 3, 3, 3, 3, 'Cumplió con lo esperado, nada espectacular pero correcto.', 'Agradecemos tu visita, esperamos verte de nuevo pronto.', '2026-05-03 14:00:00', 1, 0, NULL, 5, '2026-05-02 19:00:00'),
(218, 268, 4, 2, 5, 5, 5, 5, 'Superó mis expectativas, ya reservé mi próximo turno.', 'Agradecemos tu visita, esperamos verte de nuevo pronto.', '2026-06-01 07:00:00', 1, 0, NULL, 6, '2026-05-31 12:00:00'),
(219, 269, 12, 22, 5, 4, 4, 4, 'El mejor lugar de la zona, siempre vuelvo y siempre recomiendo.', NULL, NULL, 1, 0, NULL, 7, '2026-05-02 23:00:00'),
(220, 270, 3, 22, 5, 5, 4, 5, 'Una experiencia excelente de principio a fin, el equipo es muy profesional y atento.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-06-07 06:00:00', 1, 0, NULL, 0, '2026-06-05 22:00:00'),
(221, 271, 5, 85, 5, 4, 5, 5, 'El mejor lugar de la zona, siempre vuelvo y siempre recomiendo.', NULL, NULL, 1, 0, NULL, 8, '2026-05-15 23:00:00'),
(222, 272, 11, 20, 5, 4, 4, 5, 'Puntualidad perfecta y un trabajo prolijo, no tengo quejas.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-06-17 14:00:00', 1, 0, NULL, 10, '2026-06-16 04:00:00'),
(223, 273, 19, 29, 5, 5, 5, 5, 'Atención impecable y resultado de primer nivel, vale cada peso.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-05-18 05:00:00', 1, 0, NULL, 4, '2026-05-17 00:00:00'),
(224, 274, 16, 82, 5, 4, 5, 5, 'Recomendadísimo, el detalle y la dedicación se nota en el resultado final.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-06-04 02:00:00', 1, 0, NULL, 8, '2026-06-03 08:00:00'),
(225, 275, 19, 76, 4, 4, 5, 5, 'El resultado quedó muy bien, el precio me pareció correcto para la calidad.', NULL, NULL, 1, 0, NULL, 5, '2026-06-02 06:00:00'),
(226, 276, 11, 49, 4, 4, 3, 4, 'El resultado quedó muy bien, el precio me pareció correcto para la calidad.', 'Gracias por la confianza, fue un placer atenderte.', '2026-04-26 08:00:00', 1, 0, NULL, 6, '2026-04-24 21:00:00'),
(227, 277, 17, 12, 5, 4, 4, 4, 'Una experiencia excelente de principio a fin, el equipo es muy profesional y atento.', NULL, NULL, 1, 0, NULL, 7, '2026-06-07 08:00:00'),
(228, 278, 12, 44, 4, 5, 5, 4, 'Local cómodo y buena atención, solo mejoraría la puntualidad.', NULL, NULL, 1, 0, NULL, 4, '2026-06-10 00:00:00'),
(229, 279, 18, 68, 3, 3, 2, 3, 'El servicio estuvo bien pero tardaron más de lo que avisaron.', 'Gracias por la confianza, fue un placer atenderte.', '2026-05-01 10:00:00', 1, 0, NULL, 10, '2026-04-30 06:00:00'),
(230, 280, 5, 58, 3, 3, 3, 4, 'Cumplió con lo esperado, nada espectacular pero correcto.', NULL, NULL, 1, 0, NULL, 0, '2026-05-26 10:00:00'),
(231, 281, 6, 65, 4, 5, 4, 5, 'Local cómodo y buena atención, solo mejoraría la puntualidad.', 'Gracias por la confianza, fue un placer atenderte.', '2026-05-26 07:00:00', 1, 0, NULL, 8, '2026-05-25 02:00:00'),
(232, 282, 9, 31, 4, 3, 4, 4, 'Muy buena atención en general, aunque demoraron un poco más de lo previsto.', 'Agradecemos tu visita, esperamos verte de nuevo pronto.', '2026-06-14 08:00:00', 1, 0, NULL, 2, '2026-06-12 21:00:00'),
(233, 283, 20, 43, 3, 3, 3, 4, 'El servicio estuvo bien pero tardaron más de lo que avisaron.', NULL, NULL, 1, 0, NULL, 4, '2026-06-16 19:00:00'),
(234, 284, 4, 33, 3, 3, 4, 3, 'El servicio estuvo bien pero tardaron más de lo que avisaron.', NULL, NULL, 1, 0, NULL, 8, '2026-05-18 16:00:00'),
(235, 285, 16, 44, 5, 5, 5, 5, 'Una experiencia excelente de principio a fin, el equipo es muy profesional y atento.', 'Gracias por la confianza, fue un placer atenderte.', '2026-06-17 22:00:00', 1, 0, NULL, 5, '2026-06-16 22:00:00'),
(236, 286, 13, 78, 3, 4, 3, 2, 'El servicio estuvo bien pero tardaron más de lo que avisaron.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-06-14 09:00:00', 1, 0, NULL, 0, '2026-06-13 06:00:00'),
(237, 287, 2, 64, 5, 5, 5, 5, 'El mejor lugar de la zona, siempre vuelvo y siempre recomiendo.', NULL, NULL, 1, 0, NULL, 1, '2026-06-05 23:00:00'),
(238, 288, 3, 80, 4, 4, 4, 3, 'Muy buena atención en general, aunque demoraron un poco más de lo previsto.', 'Gracias por la confianza, fue un placer atenderte.', '2026-06-07 10:00:00', 1, 0, NULL, 0, '2026-06-06 07:00:00'),
(239, 289, 13, 49, 4, 5, 4, 5, 'Buen trato y resultado prolijo, repetiría sin dudas.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-05-28 15:00:00', 1, 0, NULL, 8, '2026-05-27 06:00:00'),
(240, 290, 6, 51, 4, 5, 4, 5, 'Muy buena atención en general, aunque demoraron un poco más de lo previsto.', 'Agradecemos tu visita, esperamos verte de nuevo pronto.', '2026-05-22 14:00:00', 1, 0, NULL, 6, '2026-05-22 04:00:00'),
(241, 291, 2, 82, 3, 3, 3, 2, 'Cumplió con lo esperado, nada espectacular pero correcto.', NULL, NULL, 1, 0, NULL, 6, '2026-04-28 03:00:00'),
(242, 292, 17, 73, 5, 5, 5, 4, 'Puntualidad perfecta y un trabajo prolijo, no tengo quejas.', 'Agradecemos tu visita, esperamos verte de nuevo pronto.', '2026-06-02 08:00:00', 1, 0, NULL, 5, '2026-05-31 22:00:00'),
(243, 293, 20, 86, 4, 4, 4, 4, 'El resultado quedó muy bien, el precio me pareció correcto para la calidad.', NULL, NULL, 1, 0, NULL, 0, '2026-05-31 00:00:00'),
(244, 294, 5, 73, 5, 5, 5, 5, 'Quedé totalmente satisfecho con el resultado, superó lo que esperaba.', NULL, NULL, 1, 0, NULL, 5, '2026-05-30 01:00:00'),
(245, 295, 3, 42, 5, 5, 4, 5, 'Recomendadísimo, el detalle y la dedicación se nota en el resultado final.', 'Agradecemos tu visita, esperamos verte de nuevo pronto.', '2026-05-11 08:00:00', 1, 0, NULL, 2, '2026-05-09 18:00:00'),
(246, 296, 16, 16, 3, 3, 4, 4, 'Cumplió con lo esperado, nada espectacular pero correcto.', 'Gracias por la confianza, fue un placer atenderte.', '2026-05-18 07:00:00', 1, 0, NULL, 4, '2026-05-17 18:00:00'),
(247, 297, 11, 81, 5, 5, 5, 5, 'Me sentí muy cómodo todo el tiempo, ambiente cálido y profesional.', NULL, NULL, 1, 0, NULL, 4, '2026-04-27 17:00:00'),
(248, 298, 15, 59, 5, 5, 5, 4, 'Superó mis expectativas, ya reservé mi próximo turno.', NULL, NULL, 1, 0, NULL, 2, '2026-05-18 06:00:00'),
(249, 299, 19, 4, 5, 5, 4, 4, 'Quedé totalmente satisfecho con el resultado, superó lo que esperaba.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-05-23 13:00:00', 1, 0, NULL, 6, '2026-05-21 22:00:00'),
(250, 300, 4, 77, 5, 5, 5, 5, 'Puntualidad perfecta y un trabajo prolijo, no tengo quejas.', NULL, NULL, 1, 0, NULL, 0, '2026-05-15 00:00:00'),
(251, 301, 12, 78, 5, 5, 5, 4, 'Me sentí muy cómodo todo el tiempo, ambiente cálido y profesional.', 'Gracias por la confianza, fue un placer atenderte.', '2026-06-18 14:00:00', 1, 0, NULL, 6, '2026-06-18 00:00:00'),
(252, 302, 18, 45, 5, 5, 5, 4, 'El mejor lugar de la zona, siempre vuelvo y siempre recomiendo.', 'Agradecemos tu visita, esperamos verte de nuevo pronto.', '2026-04-25 13:00:00', 1, 0, NULL, 5, '2026-04-25 06:00:00'),
(253, 303, 9, 85, 4, 3, 4, 4, 'El resultado quedó muy bien, el precio me pareció correcto para la calidad.', 'Gracias por la confianza, fue un placer atenderte.', '2026-04-29 14:00:00', 1, 0, NULL, 9, '2026-04-29 00:00:00'),
(254, 304, 9, 25, 5, 4, 4, 5, 'Recomendadísimo, el detalle y la dedicación se nota en el resultado final.', NULL, NULL, 1, 0, NULL, 6, '2026-04-26 01:00:00'),
(255, 305, 20, 53, 3, 3, 3, 2, 'Resultado aceptable, esperaba un poco más por el precio.', NULL, NULL, 1, 0, NULL, 0, '2026-06-15 02:00:00'),
(256, 306, 8, 3, 5, 5, 4, 5, 'Puntualidad perfecta y un trabajo prolijo, no tengo quejas.', NULL, NULL, 1, 0, NULL, 8, '2026-05-04 02:00:00'),
(257, 307, 6, 21, 4, 5, 3, 4, 'Buen trato y resultado prolijo, repetiría sin dudas.', NULL, NULL, 1, 0, NULL, 4, '2026-05-30 18:00:00'),
(258, 308, 3, 35, 5, 5, 4, 5, 'Una experiencia excelente de principio a fin, el equipo es muy profesional y atento.', NULL, NULL, 1, 0, NULL, 1, '2026-06-14 00:00:00'),
(259, 309, 15, 74, 4, 5, 4, 4, 'El resultado quedó muy bien, el precio me pareció correcto para la calidad.', NULL, NULL, 1, 0, NULL, 3, '2026-05-31 04:00:00'),
(260, 310, 16, 78, 5, 5, 4, 5, 'Me sentí muy cómodo todo el tiempo, ambiente cálido y profesional.', 'Gracias por la confianza, fue un placer atenderte.', '2026-05-23 13:00:00', 1, 0, NULL, 2, '2026-05-22 23:00:00'),
(261, 311, 4, 74, 5, 5, 5, 5, 'Quedé totalmente satisfecho con el resultado, superó lo que esperaba.', NULL, NULL, 1, 0, NULL, 5, '2026-05-19 00:00:00'),
(262, 312, 2, 48, 5, 5, 5, 5, 'Puntualidad perfecta y un trabajo prolijo, no tengo quejas.', 'Gracias por la confianza, fue un placer atenderte.', '2026-04-25 03:00:00', 1, 0, NULL, 0, '2026-04-24 03:00:00'),
(263, 313, 5, 28, 4, 4, 5, 4, 'El resultado quedó muy bien, el precio me pareció correcto para la calidad.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-06-18 12:00:00', 1, 0, NULL, 7, '2026-06-17 06:00:00'),
(264, 314, 12, 83, 5, 5, 5, 5, 'Quedé totalmente satisfecho con el resultado, superó lo que esperaba.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-05-06 04:00:00', 1, 0, NULL, 2, '2026-05-05 10:00:00'),
(265, 315, 19, 75, 5, 5, 4, 4, 'Quedé totalmente satisfecho con el resultado, superó lo que esperaba.', 'Agradecemos tu visita, esperamos verte de nuevo pronto.', '2026-05-08 20:00:00', 1, 0, NULL, 2, '2026-05-08 07:00:00'),
(266, 316, 13, 71, 5, 5, 5, 5, 'Me sentí muy cómodo todo el tiempo, ambiente cálido y profesional.', NULL, NULL, 1, 0, NULL, 9, '2026-05-07 22:00:00'),
(267, 317, 18, 44, 5, 5, 5, 5, 'Puntualidad perfecta y un trabajo prolijo, no tengo quejas.', NULL, NULL, 1, 0, NULL, 0, '2026-05-27 02:00:00'),
(268, 318, 11, 8, 5, 5, 5, 5, 'Recomendadísimo, el detalle y la dedicación se nota en el resultado final.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-06-08 10:00:00', 1, 0, NULL, 9, '2026-06-08 00:00:00'),
(269, 319, 17, 39, 5, 5, 5, 5, 'Puntualidad perfecta y un trabajo prolijo, no tengo quejas.', NULL, NULL, 1, 0, NULL, 1, '2026-06-09 08:00:00'),
(270, 320, 9, 82, 5, 5, 5, 5, 'Puntualidad perfecta y un trabajo prolijo, no tengo quejas.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-06-06 19:00:00', 1, 0, NULL, 10, '2026-06-06 07:00:00'),
(271, 321, 20, 66, 4, 4, 4, 4, 'Muy buena atención en general, aunque demoraron un poco más de lo previsto.', NULL, NULL, 1, 0, NULL, 9, '2026-04-28 23:00:00'),
(272, 322, 2, 48, 5, 5, 5, 5, 'El mejor lugar de la zona, siempre vuelvo y siempre recomiendo.', NULL, NULL, 1, 0, NULL, 7, '2026-05-26 22:00:00'),
(273, 323, 11, 73, 3, 3, 3, 4, 'Cumplió con lo esperado, nada espectacular pero correcto.', 'Gracias por la confianza, fue un placer atenderte.', '2026-05-16 18:00:00', 1, 0, NULL, 0, '2026-05-15 22:00:00'),
(274, 324, 16, 26, 3, 4, 3, 3, 'Cumplió con lo esperado, nada espectacular pero correcto.', NULL, NULL, 1, 0, NULL, 1, '2026-06-03 19:00:00'),
(275, 325, 12, 50, 5, 5, 5, 5, 'Superó mis expectativas, ya reservé mi próximo turno.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-05-01 01:00:00', 1, 0, NULL, 10, '2026-04-30 02:00:00'),
(276, 326, 19, 36, 5, 5, 4, 5, 'Superó mis expectativas, ya reservé mi próximo turno.', 'Agradecemos tu visita, esperamos verte de nuevo pronto.', '2026-04-27 20:00:00', 1, 0, NULL, 7, '2026-04-27 00:00:00'),
(277, 327, 4, 31, 4, 4, 3, 4, 'Local cómodo y buena atención, solo mejoraría la puntualidad.', 'Agradecemos tu visita, esperamos verte de nuevo pronto.', '2026-05-09 17:00:00', 1, 0, NULL, 4, '2026-05-09 04:00:00'),
(278, 328, 15, 49, 5, 5, 5, 5, 'Quedé totalmente satisfecho con el resultado, superó lo que esperaba.', NULL, NULL, 1, 0, NULL, 5, '2026-05-30 01:00:00'),
(279, 329, 1, 8, 5, 5, 5, 4, 'Recomendadísimo, el detalle y la dedicación se nota en el resultado final.', NULL, NULL, 1, 0, NULL, 10, '2026-05-15 08:00:00'),
(280, 330, 3, 73, 4, 3, 3, 5, 'Muy buena atención en general, aunque demoraron un poco más de lo previsto.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-05-20 04:00:00', 1, 0, NULL, 1, '2026-05-18 18:00:00'),
(281, 331, 5, 2, 4, 3, 4, 3, 'Local cómodo y buena atención, solo mejoraría la puntualidad.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-05-02 18:00:00', 1, 0, NULL, 3, '2026-05-01 05:00:00'),
(282, 332, 6, 60, 5, 5, 5, 4, 'El mejor lugar de la zona, siempre vuelvo y siempre recomiendo.', NULL, NULL, 1, 0, NULL, 2, '2026-05-11 00:00:00'),
(283, 333, 17, 61, 5, 5, 5, 5, 'Puntualidad perfecta y un trabajo prolijo, no tengo quejas.', 'Agradecemos tu visita, esperamos verte de nuevo pronto.', '2026-06-01 17:00:00', 1, 0, NULL, 6, '2026-05-31 03:00:00'),
(284, 334, 8, 48, 3, 2, 3, 3, 'El servicio estuvo bien pero tardaron más de lo que avisaron.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-05-18 02:00:00', 1, 0, NULL, 5, '2026-05-17 21:00:00'),
(285, 335, 18, 41, 4, 3, 4, 4, 'Buen trato y resultado prolijo, repetiría sin dudas.', 'Gracias por la confianza, fue un placer atenderte.', '2026-06-06 17:00:00', 1, 0, NULL, 2, '2026-06-05 07:00:00'),
(286, 336, 13, 8, 5, 5, 5, 5, 'Me sentí muy cómodo todo el tiempo, ambiente cálido y profesional.', NULL, NULL, 1, 0, NULL, 1, '2026-05-04 11:00:00'),
(287, 337, 11, 62, 5, 4, 5, 5, 'Superó mis expectativas, ya reservé mi próximo turno.', 'Agradecemos tu visita, esperamos verte de nuevo pronto.', '2026-06-09 07:00:00', 1, 0, NULL, 10, '2026-06-08 19:00:00'),
(288, 338, 15, 71, 4, 4, 5, 3, 'Local cómodo y buena atención, solo mejoraría la puntualidad.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-05-25 01:00:00', 1, 0, NULL, 3, '2026-05-24 21:00:00'),
(289, 339, 17, 2, 3, 4, 3, 3, 'Resultado aceptable, esperaba un poco más por el precio.', NULL, NULL, 1, 0, NULL, 9, '2026-05-21 02:00:00'),
(290, 340, 18, 65, 4, 3, 3, 3, 'Buen trato y resultado prolijo, repetiría sin dudas.', 'Gracias por la confianza, fue un placer atenderte.', '2026-06-02 01:00:00', 1, 0, NULL, 7, '2026-06-01 00:00:00'),
(291, 341, 2, 39, 5, 5, 4, 5, 'Atención impecable y resultado de primer nivel, vale cada peso.', '¡Muchas gracias por tu comentario! Te esperamos pronto.', '2026-06-12 08:00:00', 1, 0, NULL, 1, '2026-06-11 17:00:00');

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
(46, 9, 24, 'Envoltura Corporal', 'Envoltura con barro y algas marinas.', 90, 13000.00, 20, NULL, 1, 1, '2026-06-10 01:39:36', '2026-06-10 01:39:36'),
(47, 11, 25, 'Corte Moderno', 'Corte personalizado según tipo de rostro y textura.', 45, 6000.00, 0, NULL, 1, 1, '2026-06-10 00:00:00', '2026-06-10 00:00:00'),
(48, 11, 26, 'Corte + Barba', 'Combo de corte y arreglo de barba.', 60, 8500.00, 0, NULL, 1, 2, '2026-06-10 00:00:00', '2026-06-10 00:00:00'),
(49, 11, 25, 'Coloración Fantasía', 'Color creativo con tonos vivos.', 120, 25000.00, 30, NULL, 1, 3, '2026-06-10 00:00:00', '2026-06-10 00:00:00'),
(50, 11, 26, 'Tratamiento Capilar', 'Hidratación profunda con productos premium.', 50, 7000.00, 0, NULL, 1, 4, '2026-06-10 00:00:00', '2026-06-10 00:00:00'),
(51, 12, 27, 'Fade Clásico', 'Degradado preciso a máquina.', 40, 5000.00, 0, NULL, 1, 1, '2026-06-10 00:00:00', '2026-06-10 00:00:00'),
(52, 12, 28, 'Barba Completa', 'Perfilado, afeitado y diseño con navaja.', 35, 4000.00, 0, NULL, 1, 2, '2026-06-10 00:00:00', '2026-06-10 00:00:00'),
(53, 12, 27, 'Corte + Barba Premium', 'Combo completo con toalla caliente.', 70, 9000.00, 20, NULL, 1, 3, '2026-06-10 00:00:00', '2026-06-10 00:00:00'),
(54, 12, 28, 'Diseño de Líneas', 'Diseño artístico en el corte.', 20, 2500.00, 0, NULL, 1, 4, '2026-06-10 00:00:00', '2026-06-10 00:00:00'),
(55, 13, 29, 'Esculpidas Acrílico', 'Uñas esculpidas con acrílico de alta calidad.', 90, 14000.00, 20, NULL, 1, 1, '2026-05-19 00:00:00', '2026-05-19 00:00:00'),
(56, 13, 30, 'Nail Art 3D', 'Decoración tridimensional personalizada.', 60, 9000.00, 0, NULL, 1, 2, '2026-05-19 00:00:00', '2026-05-19 00:00:00'),
(57, 13, 29, 'Esmaltado Semipermanente', 'Esmaltado de larga duración.', 45, 6000.00, 0, NULL, 1, 3, '2026-05-19 00:00:00', '2026-05-19 00:00:00'),
(58, 13, 30, 'Retoque de Esculpidas', 'Mantenimiento y relleno.', 60, 7500.00, 0, NULL, 1, 4, '2026-05-19 00:00:00', '2026-05-19 00:00:00'),
(59, 14, 31, 'Masaje Relajante 60min', 'Masaje de cuerpo completo con aceites esenciales.', 60, 11000.00, 0, NULL, 1, 1, '2026-06-08 00:00:00', '2026-06-08 00:00:00'),
(60, 14, 32, 'Facial Antiage', 'Tratamiento facial con ácido hialurónico.', 60, 14000.00, 20, NULL, 1, 2, '2026-06-08 00:00:00', '2026-06-08 00:00:00'),
(61, 14, 31, 'Reflexología Podal', 'Masaje terapéutico en pies.', 45, 8500.00, 0, NULL, 1, 3, '2026-06-08 00:00:00', '2026-06-08 00:00:00'),
(62, 14, 32, 'Ritual Spa Completo', 'Masaje + facial + exfoliación.', 120, 28000.00, 30, NULL, 1, 4, '2026-06-08 00:00:00', '2026-06-08 00:00:00'),
(63, 15, 33, 'Tatuaje Fine Line', 'Diseño minimalista de líneas finas.', 60, 18000.00, 50, NULL, 1, 1, '2026-05-17 00:00:00', '2026-05-17 00:00:00'),
(64, 15, 34, 'Tatuaje Geométrico', 'Diseños geométricos personalizados.', 90, 28000.00, 50, NULL, 1, 2, '2026-05-17 00:00:00', '2026-05-17 00:00:00'),
(65, 15, 33, 'Tatuaje Lettering', 'Frases y tipografías a medida.', 45, 15000.00, 50, NULL, 1, 3, '2026-05-17 00:00:00', '2026-05-17 00:00:00'),
(66, 15, 34, 'Cover Up', 'Cobertura de tatuajes existentes.', 150, 45000.00, 50, NULL, 1, 4, '2026-05-17 00:00:00', '2026-05-17 00:00:00'),
(87, 7, 45, 'Corte Clásico', 'Corte tradicional con tijera o máquina a elección.', 30, 4200.00, 0, NULL, 1, 1, '2026-06-09 02:10:00', '2026-06-09 02:10:00'),
(88, 7, 46, 'Fade Moderno', 'Degradado preciso con acabado prolijo.', 45, 5800.00, 0, NULL, 1, 2, '2026-06-09 02:10:00', '2026-06-09 02:10:00'),
(89, 7, 45, 'Barba Completa', 'Perfilado, afeitado y diseño con navaja.', 30, 4000.00, 0, NULL, 1, 3, '2026-06-09 02:10:00', '2026-06-09 02:10:00'),
(90, 7, 46, 'Corte + Barba', 'Combo de corte y arreglo de barba.', 60, 8500.00, 20, NULL, 1, 4, '2026-06-09 02:10:00', '2026-06-09 02:10:00'),
(91, 10, 47, 'Corte Mujer', 'Corte con lavado y secado incluido.', 45, 6200.00, 0, NULL, 1, 1, '2026-06-10 02:05:51', '2026-06-10 02:05:51'),
(92, 10, 48, 'Corte Hombre', 'Corte clásico o moderno a elección.', 30, 4500.00, 0, NULL, 1, 2, '2026-06-10 02:05:51', '2026-06-10 02:05:51'),
(93, 10, 47, 'Coloración Completa', 'Tintura de raíz a puntas.', 90, 19500.00, 30, NULL, 1, 3, '2026-06-10 02:05:51', '2026-06-10 02:05:51'),
(94, 10, 48, 'Tratamiento Capilar', 'Hidratación profunda con productos premium.', 50, 7200.00, 0, NULL, 1, 4, '2026-06-10 02:05:51', '2026-06-10 02:05:51'),
(95, 16, 49, 'Corte Clásico', 'Corte tradicional con tijera o máquina a elección.', 30, 4000.00, 0, NULL, 1, 1, '2026-04-20 09:30:00', '2026-04-20 09:30:00'),
(96, 16, 50, 'Fade Premium', 'Degradado de alta precisión con acabado a navaja.', 45, 5500.00, 0, NULL, 1, 2, '2026-04-20 09:30:00', '2026-04-20 09:30:00'),
(97, 16, 49, 'Barba Completa', 'Perfilado, afeitado y diseño con navaja.', 30, 3800.00, 0, NULL, 1, 3, '2026-04-20 09:30:00', '2026-04-20 09:30:00'),
(98, 16, 50, 'Corte + Barba', 'Combo completo de corte y arreglo de barba.', 60, 8000.00, 20, NULL, 1, 4, '2026-04-20 09:30:00', '2026-04-20 09:30:00'),
(99, 17, 51, 'Esculpidas Acrílico', 'Uñas esculpidas con acrílico premium.', 90, 13000.00, 20, NULL, 1, 1, '2026-04-25 10:30:00', '2026-04-25 10:30:00'),
(100, 17, 52, 'Semipermanente', 'Esmaltado semipermanente de larga duración.', 45, 6500.00, 0, NULL, 1, 2, '2026-04-25 10:30:00', '2026-04-25 10:30:00'),
(101, 17, 51, 'Nail Art Personalizado', 'Diseños artísticos a pedido.', 60, 8500.00, 0, NULL, 1, 3, '2026-04-25 10:30:00', '2026-04-25 10:30:00'),
(102, 17, 52, 'Pedicura Spa', 'Pedicura completa con exfoliación.', 60, 7000.00, 0, NULL, 1, 4, '2026-04-25 10:30:00', '2026-04-25 10:30:00'),
(103, 18, 53, 'Masaje Relajante', 'Masaje de cuerpo completo con aceites esenciales.', 60, 12000.00, 0, NULL, 1, 1, '2026-04-28 11:30:00', '2026-04-28 11:30:00'),
(104, 18, 54, 'Masaje Descontracturante', 'Técnica profunda para liberar tensiones.', 75, 14000.00, 0, NULL, 1, 2, '2026-04-28 11:30:00', '2026-04-28 11:30:00'),
(105, 18, 53, 'Facial Hidratante', 'Limpieza profunda e hidratación con productos premium.', 60, 11000.00, 0, NULL, 1, 3, '2026-04-28 11:30:00', '2026-04-28 11:30:00'),
(106, 18, 54, 'Ritual de Bienestar', 'Masaje + facial + aromaterapia.', 120, 26000.00, 30, NULL, 1, 4, '2026-04-28 11:30:00', '2026-04-28 11:30:00'),
(107, 19, 55, 'Corte Mujer', 'Corte con lavado y secado incluido.', 45, 6000.00, 0, NULL, 1, 1, '2026-05-02 10:00:00', '2026-05-02 10:00:00'),
(108, 19, 56, 'Corte Hombre', 'Corte clásico o moderno a elección.', 30, 4500.00, 0, NULL, 1, 2, '2026-05-02 10:00:00', '2026-05-02 10:00:00'),
(109, 19, 55, 'Coloración Completa', 'Tintura de raíz a puntas.', 90, 19000.00, 30, NULL, 1, 3, '2026-05-02 10:00:00', '2026-05-02 10:00:00'),
(110, 19, 56, 'Balayage', 'Técnica de mechas con efecto natural.', 120, 24000.00, 30, NULL, 1, 4, '2026-05-02 10:00:00', '2026-05-02 10:00:00'),
(111, 20, 57, 'Tatuaje Pequeño', 'Hasta 5cm, diseño simple.', 60, 16000.00, 50, NULL, 1, 1, '2026-05-06 14:30:00', '2026-05-06 14:30:00'),
(112, 20, 58, 'Tatuaje Mediano', '5 a 15cm, sesión completa.', 120, 32000.00, 50, NULL, 1, 2, '2026-05-06 14:30:00', '2026-05-06 14:30:00'),
(113, 20, 57, 'Tatuaje Blackwork', 'Diseño sólido en negro, gran formato.', 180, 50000.00, 50, NULL, 1, 3, '2026-05-06 14:30:00', '2026-05-06 14:30:00'),
(114, 20, 58, 'Piercing', 'Incluye joya de titanio hipoalergénica.', 30, 7500.00, 0, NULL, 1, 4, '2026-05-06 14:30:00', '2026-05-06 14:30:00');

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
(24, 9, 'Corporales', 3),
(25, 11, 'Cortes', 1),
(26, 11, 'Color & Tratamientos', 2),
(27, 12, 'Cortes & Fade', 1),
(28, 12, 'Barba & Afeitado', 2),
(29, 13, 'Esculpidas', 1),
(30, 13, 'Nail Art', 2),
(31, 14, 'Masajes', 1),
(32, 14, 'Faciales & Corporales', 2),
(33, 15, 'Tatuajes Pequeños', 1),
(34, 15, 'Tatuajes Grandes', 2),
(45, 7, 'Cortes & Fade', 1),
(46, 7, 'Barba & Afeitado', 2),
(47, 10, 'Cortes', 1),
(48, 10, 'Color & Tratamientos', 2),
(49, 16, 'Cortes & Fade', 1),
(50, 16, 'Barba & Afeitado', 2),
(51, 17, 'Esculpidas & Semipermanente', 1),
(52, 17, 'Nail Art', 2),
(53, 18, 'Masajes', 1),
(54, 18, 'Faciales & Corporales', 2),
(55, 19, 'Cortes', 1),
(56, 19, 'Color & Tratamientos', 2),
(57, 20, 'Tatuajes', 1),
(58, 20, 'Piercings', 2);

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
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `suspension_public` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `shops`
--

INSERT INTO `shops` (`id`, `owner_id`, `name`, `slug`, `type`, `target_audience`, `description`, `specialties`, `amenities`, `phone`, `email`, `website`, `instagram`, `facebook`, `whatsapp`, `logo`, `cover_image`, `address`, `city`, `province`, `country`, `postal_code`, `latitude`, `longitude`, `currency`, `rating_avg`, `rating_count`, `status`, `plan`, `plan_expires`, `verified`, `featured`, `views_count`, `is_shadowbanned`, `suspension_reason`, `suspension_until`, `ban_reason`, `created_at`, `updated_at`, `suspension_public`) VALUES
(1, 2, 'Barbería El Navajo', 'barberia-el-navajo', 'barbershop', 'men', 'La mejor barbería de Viedma. Cortes clásicos y modernos, afeitado con navaja y arreglo de barba. Atención personalizada y ambiente de primera.', 'Fade,Degradado,Barba,Navaja,Keratina,Coloración', '', '+5492920111222', 'info@elnavajo.com', '', '', '', '', 'shops/img_6a2a2cb9c62672.68205480.png', 'shops/img_6a27a3d927b3c3.66540191.jpg', 'Domingo De Oro 561', 'Patagones', 'Buenos Aires', 'Argentina', '8504', -40.7950575, -62.9618096, 'ARS', 4.57, 7, 'active', 'pro', NULL, 1, 0, 35, 0, NULL, NULL, NULL, '2026-06-08 23:26:34', '2026-06-22 18:00:34', 1),
(2, 5, 'Salón Vale', 'salon-vale', 'salon', 'women', 'Salón de belleza femenino con ambiente moderno y acogedor. Especialistas en coloración, corte y tratamientos capilares.', 'Coloración,Mechas,Keratina,Corte,Peinado', 'WiFi,Estacionamiento,Café gratis', '+5492920334455', 'vale@salonvale.com', NULL, 'salon.vale', NULL, '+5492920334455', NULL, NULL, 'Av. Rivadavia 1200', 'Viedma', 'Río Negro', 'Argentina', '8500', -40.8122000, -62.9935000, 'ARS', 4.50, 8, 'active', 'pro', '2027-01-01', 1, 1, 94, 0, NULL, NULL, NULL, '2026-06-01 09:00:00', '2026-06-20 01:13:42', 1),
(3, 6, 'Nails by Ramón', 'nails-by-ramon', 'nails', 'women', 'Estudio de uñas profesional. Gel, acrílico, nail art y más. Resultados que duran.', 'Gel,Acrílico,Nail Art,Semipermanente,Decoración', 'Música ambiente,Té y café', '+5492920445566', 'ramon@nailsramon.com', NULL, 'nails.ramon', NULL, '+5492920445566', NULL, NULL, 'Saavedra 340', 'Viedma', 'Río Negro', 'Argentina', '8500', -40.8098000, -62.9910000, 'ARS', 4.71, 7, 'active', 'free', NULL, 0, 0, 46, 0, NULL, NULL, NULL, '2026-06-02 11:00:00', '2026-06-20 01:11:45', 1),
(4, 7, 'Spa Fernanda', 'spa-fernanda', 'spa', 'unisex', 'Espacio de bienestar y relajación. Masajes, aromaterapia y tratamientos corporales para desconectarte del estrés.', 'Masajes,Aromaterapia,Exfoliación,Tratamientos Faciales', 'Estacionamiento,Ducha,Música relajante,Aromas', '+5492920556677', 'fer@spafernanda.com', NULL, 'spafernanda', NULL, '+5492920556677', NULL, NULL, 'Belgrano 870', 'Patagones', 'Buenos Aires', 'Argentina', '8504', -40.7967000, -62.9815000, 'ARS', 4.57, 7, 'active', 'premium', '2027-06-01', 1, 0, 140, 0, NULL, NULL, NULL, '2026-06-01 12:00:00', '2026-06-20 01:11:45', 1),
(5, 8, 'Ink & Diego Tattoo', 'ink-diego-tattoo', 'tattoo', 'unisex', 'Estudio de tatuajes y piercings en Buenos Aires. Diseños únicos, máxima higiene. Consulta sin cargo.', 'Realismo,Blackwork,Acuarela,Piercings,Lettering', 'Portfolio digital,Zona de espera,Música', '+5491166778899', 'diego@inkdiego.com', 'https://inkdiego.com.ar', 'inkdiego.tattoo', NULL, '+5491166778899', NULL, NULL, 'Corrientes 4500', 'Buenos Aires', 'CABA', 'Argentina', '1195', -34.6037000, -58.4116000, 'ARS', 4.29, 7, 'active', 'pro', '2026-12-01', 1, 0, 214, 0, NULL, NULL, NULL, '2026-05-28 09:00:00', '2026-06-20 01:11:45', 1),
(6, 9, 'MixedCuts Luciana', 'mixedcuts-luciana', 'mixed', 'unisex', 'Peluquería unisex en Bariloche. Cortes modernos para todos. Sin distinción de género, solo buen trabajo.', 'Corte,Color,Barba,Peinado,Tratamientos', 'WiFi,Vista al lago,Ambiente cálido', '+5492920667788', 'luci@mixedluci.com', NULL, 'mixedcuts.bariloche', NULL, '+5492920667788', NULL, NULL, 'Mitre 200', 'Bariloche', 'Río Negro', 'Argentina', '8400', -41.1335000, -71.3103000, 'ARS', 4.43, 7, 'active', 'free', NULL, 1, 0, 74, 0, NULL, NULL, NULL, '2026-06-03 10:00:00', '2026-06-20 01:11:45', 1),
(7, 3, 'Barbería del Norte', 'barberia-del-norte', 'barbershop', 'men', 'Nueva barbería en el norte de Viedma. Cortes modernos y clásicos a precios accesibles.', 'Fade,Corte Clásico,Barba', NULL, '+5492920256833', 'ejemplo1@ejemplo.com', NULL, NULL, NULL, NULL, NULL, NULL, 'Av. Norte 123', 'Viedma', 'Río Negro', 'Argentina', '8500', -40.7980000, -62.9850000, 'ARS', 4.60, 30, 'active', 'free', NULL, 1, 0, 2, 0, NULL, NULL, NULL, '2026-06-09 02:00:00', '2026-06-20 01:03:40', 1),
(8, 15, 'Salón Valentina', 'salon-valentina', 'salon', 'women', 'El salón de belleza más completo de Viedma. Coloraciones, mechas, tratamientos y mucho más.', 'Coloración,Mechas,Keratina,Corte,Peinado', 'WiFi,Café gratis', '+5492920222001', 'vale@salonvalentina.com', NULL, NULL, NULL, NULL, NULL, NULL, 'Av. Zatti 820', 'Viedma', 'Río Negro', 'Argentina', '8500', -40.8110000, -62.9940000, 'ARS', 4.14, 7, 'active', 'pro', '2027-06-01', 1, 1, 53, 0, NULL, NULL, NULL, '2026-06-10 01:39:35', '2026-06-22 18:00:29', 1),
(9, 18, 'Spa Zen', 'spa-zen', 'spa', 'women', 'Espacio de relajación y bienestar. Masajes, tratamientos faciales y aromaterapia.', 'Masajes,Aromaterapia,Tratamientos Faciales,Corporales', 'Música relajante,Aromas', '+5492920333001', 'rod@spazen.com', NULL, NULL, NULL, NULL, NULL, NULL, 'Colón 1245', 'Viedma', 'Río Negro', 'Argentina', '8500', -40.8155000, -62.9995000, 'ARS', 4.57, 7, 'active', 'free', NULL, 1, 0, 28, 0, NULL, NULL, NULL, '2026-06-10 01:39:35', '2026-06-20 01:11:45', 1),
(10, 26, 'Peluquería Test', 'peluqueria-test', 'mixed', 'unisex', '', '', '', '+5492920500002', 'negocio.test@trimly.com', '', '', '', '', NULL, NULL, '', 'Viedma', 'Río Negro', 'Argentina', '', NULL, NULL, 'ARS', 4.40, 30, 'active', 'free', NULL, 0, 0, 1, 0, NULL, NULL, NULL, '2026-06-10 01:55:51', '2026-06-20 01:04:55', 1),
(11, 68, 'Studio Hair Bariloche', 'studio-hair-bariloche', 'mixed', 'unisex', 'Estudio de peluquería moderno en el centro de Bariloche. Cortes, color y tratamientos de autor.', 'Corte,Color,Balayage,Tratamientos', 'WiFi,Café gratis,Estacionamiento', '+5492944100001', 'contacto@studiohairbrc.com', NULL, 'studiohair.brc', NULL, '+5492944100001', NULL, NULL, 'Mitre 540', 'Bariloche', 'Río Negro', 'Argentina', '8400', -41.1340000, -71.3050000, 'ARS', 4.38, 8, 'active', 'free', NULL, 0, 0, 14, 0, NULL, NULL, NULL, '2026-06-10 00:00:00', '2026-06-20 01:11:45', 1),
(12, 69, 'Barba Negra', 'barba-negra', 'barbershop', 'men', 'Barbería clásica con toques modernos. Especialistas en barba y afeitado tradicional.', 'Fade,Barba,Navaja,Diseño', 'WiFi,Cerveza artesanal', '+5492920778899', 'info@barbanegra.com', NULL, 'barbanegra.bbq', NULL, '+5492920778899', NULL, NULL, 'San Martín 410', 'Viedma', 'Río Negro', 'Argentina', '8500', -40.8105000, -62.9890000, 'ARS', 4.57, 7, 'active', 'pro', '2027-03-01', 1, 0, 59, 0, NULL, NULL, NULL, '2026-06-10 00:00:00', '2026-06-20 01:11:45', 1),
(13, 70, 'Glam Nails Studio', 'glam-nails-studio', 'nails', 'women', 'Estudio especializado en uñas esculpidas, nail art y diseños personalizados.', 'Acrílico,Gel,Nail Art,Decoración 3D', 'Música ambiente,Té y café,WiFi', '+5492920889900', 'hola@glamnails.com', NULL, 'glamnails.studio', NULL, '+5492920889900', NULL, NULL, 'Buenos Aires 220', 'Viedma', 'Río Negro', 'Argentina', '8500', -40.8090000, -62.9920000, 'ARS', 4.00, 7, 'active', 'free', NULL, 0, 0, 25, 0, NULL, NULL, NULL, '2026-05-19 00:00:00', '2026-06-20 01:11:45', 1),
(14, 71, 'Zen Wellness Spa', 'zen-wellness-spa', 'spa', 'unisex', 'Centro de bienestar integral. Masajes, faciales y tratamientos corporales relajantes.', 'Masajes,Faciales,Aromaterapia,Reflexología', 'Estacionamiento,Música relajante,Ducha', '+5491155667788', 'info@zenwellness.com.ar', 'https://zenwellness.com.ar', 'zenwellness.spa', NULL, '+5491155667788', NULL, NULL, 'Av. Cabildo 2300', 'Buenos Aires', 'CABA', 'Argentina', '1428', -34.5612000, -58.4598000, 'ARS', 3.86, 7, 'active', 'premium', '2027-02-15', 1, 1, 187, 0, NULL, NULL, NULL, '2026-06-08 00:00:00', '2026-06-20 01:11:45', 1),
(15, 72, 'Inkonnu Tattoo', 'inkonnu-tattoo', 'tattoo', 'unisex', 'Estudio de tatuajes contemporáneo. Diseños minimalistas, fine line y geométricos.', 'Fine Line,Geométrico,Minimalista,Lettering', 'Portfolio digital,Zona de espera', '+5491166990011', 'contacto@inkonnu.com', NULL, 'inkonnu.tattoo', NULL, '+5491166990011', NULL, NULL, 'Honduras 4800', 'Buenos Aires', 'CABA', 'Argentina', '1414', -34.5850000, -58.4310000, 'ARS', 4.50, 8, 'active', 'pro', '2026-12-20', 1, 0, 96, 0, NULL, NULL, NULL, '2026-05-17 00:00:00', '2026-06-20 01:11:45', 1),
(16, 135, 'Barbería Pichincha', 'barberia-pichincha', 'barbershop', 'men', 'Barbería de barrio en el corazón de Pichincha, Rosario. Cortes clásicos, fade y arreglo de barba con mucha onda.', 'Fade,Corte Clásico,Barba,Diseño', 'WiFi,Música en vivo los viernes', '+5493415501122', 'info@barberiapichincha.com.ar', NULL, 'barberia.pichincha', NULL, '+5493415501122', NULL, NULL, 'Bv. Pellegrini 1450', 'Rosario', 'Santa Fe', 'Argentina', '2000', -32.9468000, -60.6393000, 'ARS', 4.43, 7, 'active', 'free', NULL, 0, 0, 1, 0, NULL, NULL, NULL, '2026-04-20 09:00:00', '2026-06-20 01:13:33', 1),
(17, 136, 'Rosario Nails Lounge', 'rosario-nails-lounge', 'nails', 'women', 'Estudio de uñas en pleno centro de Rosario. Esculpidas, semipermanente y nail art a la moda.', 'Esculpidas,Semipermanente,Nail Art,Pedicura', 'Café gratis,WiFi,Música ambiente', '+5493415502233', 'hola@rosarionails.com.ar', NULL, 'rosario.nails.lounge', NULL, '+5493415502233', NULL, NULL, 'Córdoba 1287', 'Rosario', 'Santa Fe', 'Argentina', '2000', -32.9442000, -60.6505000, 'ARS', 4.25, 8, 'active', 'pro', '2027-04-01', 1, 0, 0, 0, NULL, NULL, NULL, '2026-04-25 10:00:00', '2026-06-20 01:11:45', 1),
(18, 137, 'Spa Litoral', 'spa-litoral', 'spa', 'unisex', 'Espacio de bienestar en Santa Fe capital. Masajes, faciales y tratamientos relajantes junto a la costa.', 'Masajes,Faciales,Aromaterapia,Reflexología', 'Estacionamiento,Vista al río,Música relajante', '+5493424441100', 'contacto@spalitoral.com.ar', 'https://spalitoral.com.ar', 'spa.litoral', NULL, '+5493424441100', NULL, NULL, 'Av. Belgrano 2100', 'Santa Fe', 'Santa Fe', 'Argentina', '3000', -31.6420000, -60.7090000, 'ARS', 4.25, 8, 'active', 'premium', '2027-05-01', 1, 1, 1, 0, NULL, NULL, NULL, '2026-04-28 11:00:00', '2026-06-20 01:12:02', 1),
(19, 138, 'Estudio Capilar Nueva Córdoba', 'estudio-capilar-nueva-cordoba', 'mixed', 'unisex', 'Peluquería unisex en el barrio Nueva Córdoba. Cortes, color y tratamientos para todos los estilos.', 'Corte,Color,Balayage,Tratamientos', 'WiFi,Café gratis', '+5493514005566', 'info@capilarnc.com.ar', NULL, 'capilar.nuevacordoba', NULL, '+5493514005566', NULL, NULL, 'Bv. Chacabuco 780', 'Córdoba', 'Córdoba', 'Argentina', '5000', -31.4250000, -64.1880000, 'ARS', 4.86, 7, 'active', 'free', NULL, 0, 0, 1, 0, NULL, NULL, NULL, '2026-05-02 09:30:00', '2026-06-22 18:00:26', 1),
(20, 139, 'Andes Ink Tattoo', 'andes-ink-tattoo', 'tattoo', 'unisex', 'Estudio de tatuajes en Mendoza capital. Especialistas en blackwork, realismo y diseños de autor.', 'Blackwork,Realismo,Lettering,Piercings', 'Portfolio digital,Zona de espera,Música', '+5492615007788', 'contacto@andesink.com.ar', NULL, 'andes.ink.tattoo', NULL, '+5492615007788', NULL, NULL, 'Av. Las Heras 320', 'Mendoza', 'Mendoza', 'Argentina', '5500', -32.8895000, -68.8458000, 'ARS', 3.86, 7, 'active', 'pro', '2026-11-15', 1, 0, 0, 0, NULL, NULL, NULL, '2026-05-06 14:00:00', '2026-06-20 01:11:45', 1);

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
(63, 10, 6, '09:00:00', '20:00:00', '00:00:00', '00:00:00'),
(64, 11, 0, NULL, NULL, NULL, NULL),
(65, 11, 1, '09:00:00', '19:00:00', NULL, NULL),
(66, 11, 2, '09:00:00', '19:00:00', NULL, NULL),
(67, 11, 3, '09:00:00', '19:00:00', NULL, NULL),
(68, 11, 4, '09:00:00', '19:00:00', NULL, NULL),
(69, 11, 5, '09:00:00', '19:00:00', NULL, NULL),
(70, 11, 6, '10:00:00', '15:00:00', NULL, NULL),
(71, 12, 0, NULL, NULL, NULL, NULL),
(72, 12, 1, '09:00:00', '19:00:00', NULL, NULL),
(73, 12, 2, '09:00:00', '19:00:00', NULL, NULL),
(74, 12, 3, '09:00:00', '19:00:00', NULL, NULL),
(75, 12, 4, '09:00:00', '19:00:00', NULL, NULL),
(76, 12, 5, '09:00:00', '19:00:00', NULL, NULL),
(77, 12, 6, '10:00:00', '15:00:00', NULL, NULL),
(78, 13, 0, NULL, NULL, NULL, NULL),
(79, 13, 1, '09:00:00', '19:00:00', NULL, NULL),
(80, 13, 2, '09:00:00', '19:00:00', NULL, NULL),
(81, 13, 3, '09:00:00', '19:00:00', NULL, NULL),
(82, 13, 4, '09:00:00', '19:00:00', NULL, NULL),
(83, 13, 5, '09:00:00', '19:00:00', NULL, NULL),
(84, 13, 6, '10:00:00', '15:00:00', NULL, NULL),
(85, 14, 0, NULL, NULL, NULL, NULL),
(86, 14, 1, '09:00:00', '19:00:00', NULL, NULL),
(87, 14, 2, '09:00:00', '19:00:00', NULL, NULL),
(88, 14, 3, '09:00:00', '19:00:00', NULL, NULL),
(89, 14, 4, '09:00:00', '19:00:00', NULL, NULL),
(90, 14, 5, '09:00:00', '19:00:00', NULL, NULL),
(91, 14, 6, '10:00:00', '15:00:00', NULL, NULL),
(92, 15, 0, NULL, NULL, NULL, NULL),
(93, 15, 1, '09:00:00', '19:00:00', NULL, NULL),
(94, 15, 2, '09:00:00', '19:00:00', NULL, NULL),
(95, 15, 3, '09:00:00', '19:00:00', NULL, NULL),
(96, 15, 4, '09:00:00', '19:00:00', NULL, NULL),
(97, 15, 5, '09:00:00', '19:00:00', NULL, NULL),
(98, 15, 6, '10:00:00', '15:00:00', NULL, NULL),
(134, 7, 0, NULL, NULL, NULL, NULL),
(135, 7, 1, '09:00:00', '19:00:00', NULL, NULL),
(136, 7, 2, '09:00:00', '19:00:00', NULL, NULL),
(137, 7, 3, '09:00:00', '19:00:00', NULL, NULL),
(138, 7, 4, '09:00:00', '19:00:00', NULL, NULL),
(139, 7, 5, '09:00:00', '19:00:00', NULL, NULL),
(140, 7, 6, '09:00:00', '14:00:00', NULL, NULL),
(141, 16, 0, NULL, NULL, NULL, NULL),
(142, 16, 1, '09:00:00', '19:00:00', NULL, NULL),
(143, 16, 2, '09:00:00', '19:00:00', NULL, NULL),
(144, 16, 3, '09:00:00', '19:00:00', NULL, NULL),
(145, 16, 4, '09:00:00', '19:00:00', NULL, NULL),
(146, 16, 5, '09:00:00', '19:00:00', NULL, NULL),
(147, 16, 6, '09:00:00', '14:00:00', NULL, NULL),
(148, 17, 0, NULL, NULL, NULL, NULL),
(149, 17, 1, '09:00:00', '19:00:00', NULL, NULL),
(150, 17, 2, '09:00:00', '19:00:00', NULL, NULL),
(151, 17, 3, '09:00:00', '19:00:00', NULL, NULL),
(152, 17, 4, '09:00:00', '19:00:00', NULL, NULL),
(153, 17, 5, '09:00:00', '19:00:00', NULL, NULL),
(154, 17, 6, '09:00:00', '14:00:00', NULL, NULL),
(155, 18, 0, NULL, NULL, NULL, NULL),
(156, 18, 1, '09:00:00', '19:00:00', NULL, NULL),
(157, 18, 2, '09:00:00', '19:00:00', NULL, NULL),
(158, 18, 3, '09:00:00', '19:00:00', NULL, NULL),
(159, 18, 4, '09:00:00', '19:00:00', NULL, NULL),
(160, 18, 5, '09:00:00', '19:00:00', NULL, NULL),
(161, 18, 6, '09:00:00', '14:00:00', NULL, NULL),
(162, 19, 0, NULL, NULL, NULL, NULL),
(163, 19, 1, '09:00:00', '19:00:00', NULL, NULL),
(164, 19, 2, '09:00:00', '19:00:00', NULL, NULL),
(165, 19, 3, '09:00:00', '19:00:00', NULL, NULL),
(166, 19, 4, '09:00:00', '19:00:00', NULL, NULL),
(167, 19, 5, '09:00:00', '19:00:00', NULL, NULL),
(168, 19, 6, '09:00:00', '14:00:00', NULL, NULL),
(169, 20, 0, NULL, NULL, NULL, NULL),
(170, 20, 1, '09:00:00', '19:00:00', NULL, NULL),
(171, 20, 2, '09:00:00', '19:00:00', NULL, NULL),
(172, 20, 3, '09:00:00', '19:00:00', NULL, NULL),
(173, 20, 4, '09:00:00', '19:00:00', NULL, NULL),
(174, 20, 5, '09:00:00', '19:00:00', NULL, NULL),
(175, 20, 6, '09:00:00', '14:00:00', NULL, NULL);

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
(4, 3, 1, 'unfeatured', NULL, NULL, NULL, '2026-06-09 02:18:41'),
(5, 4, 1, 'suspended', NULL, 7, '2026-06-23 13:10:36', '2026-06-16 13:10:36'),
(6, 6, 1, 'shadowban', NULL, NULL, NULL, '2026-06-16 13:10:40'),
(7, 6, 1, 'unshadowban', NULL, NULL, NULL, '2026-06-16 13:10:43'),
(8, 4, 1, 'unsuspended', NULL, NULL, NULL, '2026-06-18 01:43:02'),
(9, 4, 1, 'suspended', NULL, 7, '2026-06-25 01:43:08', '2026-06-18 01:43:08'),
(10, 4, 1, 'unfeatured', NULL, NULL, NULL, '2026-06-18 01:43:37'),
(11, 1, 1, 'suspended', 'no saben cortar', 7, '2026-06-25 01:43:58', '2026-06-18 01:43:58'),
(12, 4, 1, 'unsuspended', NULL, NULL, NULL, '2026-06-18 01:44:27'),
(13, 1, 1, 'unsuspended', NULL, NULL, NULL, '2026-06-18 01:44:52'),
(14, 10, 1, 'approved', NULL, NULL, NULL, '2026-06-18 01:47:26'),
(15, 1, 1, 'suspended', 'no saben cortar\notra vez...', 11, '2026-06-29 01:47:48', '2026-06-18 01:47:48'),
(16, 1, 1, 'unsuspended', NULL, NULL, NULL, '2026-06-18 12:48:37'),
(17, 1, 1, 'note', 'que onda, me debes el pago de 3 meses\nEn 8 dias te suspendo la actividad', 7, NULL, '2026-06-18 12:50:21'),
(18, 1, 1, 'suspended', 'no aprenden che', 7, '2026-06-26 15:23:42', '2026-06-19 15:23:42'),
(19, 1, 1, 'note', 'por falta de pago otra vez', 7, NULL, '2026-06-19 15:23:57'),
(20, 1, 1, 'unsuspended', NULL, NULL, NULL, '2026-06-19 15:24:40'),
(21, 1, 1, 'suspended', 'aprendan', 7, '2026-06-26 15:24:58', '2026-06-19 15:24:58'),
(22, 1, 1, 'unsuspended', NULL, NULL, NULL, '2026-06-19 15:25:22'),
(23, 1, 1, 'suspended', 'ja', 7, '2026-06-26 15:25:33', '2026-06-19 15:25:33'),
(24, 1, 1, 'note', 'leanme bobis', 7, NULL, '2026-06-19 15:27:35'),
(25, 1, 1, 'unsuspended', NULL, NULL, NULL, '2026-06-19 16:09:20');

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
(18, 1, 'shops/img_6a2a27f733ad31.79002941.png', '', 8, '2026-06-11 00:13:59'),
(19, 11, 'shops/studio-hair-bariloche-1.jpg', 'Local', 1, '2026-06-12 00:00:00'),
(20, 11, 'shops/studio-hair-bariloche-2.jpg', 'Trabajo destacado', 2, '2026-06-13 00:00:00'),
(21, 12, 'shops/barba-negra-1.jpg', 'Local', 1, '2026-06-12 00:00:00'),
(22, 12, 'shops/barba-negra-2.jpg', 'Trabajo destacado', 2, '2026-06-13 00:00:00'),
(23, 13, 'shops/glam-nails-studio-1.jpg', 'Local', 1, '2026-05-21 00:00:00'),
(24, 13, 'shops/glam-nails-studio-2.jpg', 'Trabajo destacado', 2, '2026-05-22 00:00:00'),
(25, 14, 'shops/zen-wellness-spa-1.jpg', 'Local', 1, '2026-06-10 00:00:00'),
(26, 14, 'shops/zen-wellness-spa-2.jpg', 'Trabajo destacado', 2, '2026-06-11 00:00:00'),
(27, 15, 'shops/inkonnu-tattoo-1.jpg', 'Local', 1, '2026-05-19 00:00:00'),
(28, 15, 'shops/inkonnu-tattoo-2.jpg', 'Trabajo destacado', 2, '2026-05-20 00:00:00'),
(39, 7, 'shops/barberia-del-norte-1.jpg', 'Local', 1, '2026-06-11 02:10:00'),
(40, 7, 'shops/barberia-del-norte-2.jpg', 'Trabajo destacado', 2, '2026-06-12 02:10:00'),
(41, 10, 'shops/peluqueria-test-1.jpg', 'Local', 1, '2026-06-12 02:05:51'),
(42, 10, 'shops/peluqueria-test-2.jpg', 'Trabajo destacado', 2, '2026-06-13 02:05:51'),
(43, 16, 'shops/barberia-pichincha-1.jpg', 'Local', 1, '2026-04-22 09:00:00'),
(44, 16, 'shops/barberia-pichincha-2.jpg', 'Trabajo destacado', 2, '2026-04-23 09:00:00'),
(45, 17, 'shops/rosario-nails-lounge-1.jpg', 'Local', 1, '2026-04-27 10:00:00'),
(46, 17, 'shops/rosario-nails-lounge-2.jpg', 'Trabajo destacado', 2, '2026-04-28 10:00:00'),
(47, 18, 'shops/spa-litoral-1.jpg', 'Local', 1, '2026-04-30 11:00:00'),
(48, 18, 'shops/spa-litoral-2.jpg', 'Trabajo destacado', 2, '2026-05-01 11:00:00'),
(49, 19, 'shops/estudio-capilar-nueva-cordoba-1.jpg', 'Local', 1, '2026-05-04 09:30:00'),
(50, 19, 'shops/estudio-capilar-nueva-cordoba-2.jpg', 'Trabajo destacado', 2, '2026-05-05 09:30:00'),
(51, 20, 'shops/andes-ink-tattoo-1.jpg', 'Local', 1, '2026-05-08 14:00:00'),
(52, 20, 'shops/andes-ink-tattoo-2.jpg', 'Trabajo destacado', 2, '2026-05-09 14:00:00');

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
-- Estructura de tabla para la tabla `stat_periods`
--

CREATE TABLE `stat_periods` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `created_by` int UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `stat_periods`
--

INSERT INTO `stat_periods` (`id`, `name`, `date_from`, `date_to`, `created_by`, `created_at`) VALUES
(1, 'eso tilin age', '2026-03-01', '2026-09-01', 1, '2026-06-14 20:14:16');

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
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ban_reason` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `suspended_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password_hash`, `role`, `avatar`, `email_verified`, `verify_token`, `reset_token`, `reset_expires`, `status`, `last_login`, `created_at`, `updated_at`, `ban_reason`, `suspended_until`) VALUES
(1, 'Super Admin', 'admin@trimly.com', NULL, '$2y$12$zAPIJXQLQ.wLDy4yOBtF/.lnwMTZ.rX4PRT3D54cWfRxRIkn5N52W', 'superadmin', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-19 16:09:13', '2026-06-08 23:26:34', '2026-06-19 16:09:13', NULL, NULL),
(2, 'Carlos Mendez', 'carlos@barberia.com', '+5492920123456', '$2y$12$CHQ64fq7XabBAs9iFgn9nO0PoS4O37NilGbABlhszMhrYCfX/gxMy', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-19 15:33:58', '2026-06-08 23:26:34', '2026-06-19 15:33:58', NULL, NULL),
(3, 'SAD', 'ejemplo1@ejemplo.com', '5492920256833', '$2y$12$mFn/kGOa89IsbAskVIoRW.xyx3FivZtlmn.0.ckKNr9X1x0K1x5y6', 'shop_owner', NULL, 1, '0bd224f3f8d0b1bd037da67bed8ed22c1e9a89ac8a6a6c16e4b5ea7ccde54a8b', NULL, NULL, 'active', NULL, '2026-06-09 02:00:06', '2026-06-11 22:47:17', NULL, NULL),
(4, 'Pablo Ríos', 'waza@waza.com', '5492920256834', '$2y$12$H6HK9b/iI2d4uOs2jZ9ZAu8YaJUYDggjl0MrrOpNxgUkd7mHSZ/pK', 'shop_owner', NULL, 0, 'bdd3b0cfa8f4353f44b5f9064b9c4867183ed55013a03f2ef7ea2edc63c12c2e', NULL, NULL, 'active', NULL, '2026-06-09 02:01:10', '2026-06-09 02:01:10', NULL, NULL),
(5, 'Valentina Greco', 'vale@salonvale.com', '+5492920334455', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-08 10:00:00', '2026-06-01 09:00:00', '2026-06-08 10:00:00', NULL, NULL),
(6, 'Ramón Ibáñez', 'ramon@nailsramon.com', '+5492920445566', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-07 14:00:00', '2026-06-02 11:00:00', '2026-06-07 14:00:00', NULL, NULL),
(7, 'Fernanda Sosa', 'fer@spafernanda.com', '+5492920556677', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-06 16:30:00', '2026-06-01 12:00:00', '2026-06-06 16:30:00', NULL, NULL),
(8, 'Diego Pereyra', 'diego@inkdiego.com', '+5491166778899', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-05 18:00:00', '2026-05-28 09:00:00', '2026-06-05 18:00:00', NULL, NULL),
(9, 'Luciana Bravo', 'luci@mixedluci.com', '+5492920667788', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-09 08:00:00', '2026-06-03 10:00:00', '2026-06-09 08:00:00', NULL, NULL),
(10, 'Martín Acosta', 'martin.acosta@gmail.com', '+5492920100001', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-09 09:00:00', '2026-05-15 08:00:00', '2026-06-09 09:00:00', NULL, NULL),
(11, 'Sofía Ruiz', 'sofia.ruiz@gmail.com', '+5492920100002', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-08 11:00:00', '2026-05-20 09:00:00', '2026-06-08 11:00:00', NULL, NULL),
(12, 'Javier Molina', 'javier.molina@hotmail.com', '+5492920100003', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-07 10:00:00', '2026-05-10 10:00:00', '2026-06-07 10:00:00', NULL, NULL),
(13, 'Camila Díaz', 'camila.diaz@gmail.com', '+5492920100004', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-08 15:00:00', '2026-05-25 11:00:00', '2026-06-08 15:00:00', NULL, NULL),
(14, 'Nicolás Ferreyra', 'nico.ferreyra@gmail.com', '+5492920100005', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-09 07:30:00', '2026-06-01 08:00:00', '2026-06-11 22:56:12', NULL, NULL),
(15, 'Valentina Ruiz', 'vale@salonvalentina.com', '+5492920222001', '$2y$12$sPbsZy3n2kLvwPPuTyXdKuZ6C7C3PaNzZkHfNJ95VJheFHVXaBzgu', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-10 01:28:09', '2026-06-10 01:28:09', NULL, NULL),
(16, 'Sofía López', 'sofia@salonvalentina.com', '+5492920222002', '$2y$12$sPbsZy3n2kLvwPPuTyXdKuZ6C7C3PaNzZkHfNJ95VJheFHVXaBzgu', 'employee', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-10 01:28:09', '2026-06-10 01:28:09', NULL, NULL),
(17, 'Antonella Vera', 'anto@salonvalentina.com', '+5492920222003', '$2y$12$sPbsZy3n2kLvwPPuTyXdKuZ6C7C3PaNzZkHfNJ95VJheFHVXaBzgu', 'employee', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-10 01:28:09', '2026-06-10 01:28:09', NULL, NULL),
(18, 'Rodrigo Paz', 'rod@spazen.com', '+5492920333001', '$2y$12$sPbsZy3n2kLvwPPuTyXdKuZ6C7C3PaNzZkHfNJ95VJheFHVXaBzgu', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-10 01:28:09', '2026-06-10 01:28:09', NULL, NULL),
(19, 'Camila Díaz', 'cami@spazen.com', '+5492920333002', '$2y$12$sPbsZy3n2kLvwPPuTyXdKuZ6C7C3PaNzZkHfNJ95VJheFHVXaBzgu', 'employee', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-10 01:28:09', '2026-06-10 01:28:09', NULL, NULL),
(20, 'Nicolás Gómez', 'nico@gmail.com', '+5492920400001', '$2y$12$sPbsZy3n2kLvwPPuTyXdKuZ6C7C3PaNzZkHfNJ95VJheFHVXaBzgu', 'client', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-10 01:28:09', '2026-06-10 01:28:09', NULL, NULL),
(21, 'Florencia Acosta', 'flor@gmail.com', '+5492920400002', '$2y$12$sPbsZy3n2kLvwPPuTyXdKuZ6C7C3PaNzZkHfNJ95VJheFHVXaBzgu', 'client', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-10 01:28:09', '2026-06-10 01:28:09', NULL, NULL),
(22, 'Diego Fernández', 'diego@gmail.com', '+5492920400003', '$2y$12$sPbsZy3n2kLvwPPuTyXdKuZ6C7C3PaNzZkHfNJ95VJheFHVXaBzgu', 'client', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-10 01:28:09', '2026-06-10 01:28:09', NULL, NULL),
(23, 'Marina Castro', 'marina@gmail.com', '+5492920400004', '$2y$12$sPbsZy3n2kLvwPPuTyXdKuZ6C7C3PaNzZkHfNJ95VJheFHVXaBzgu', 'client', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-10 01:28:09', '2026-06-10 01:28:09', NULL, NULL),
(24, 'Tomás Ibáñez', 'tomas@gmail.com', '+5492920400005', '$2y$12$sPbsZy3n2kLvwPPuTyXdKuZ6C7C3PaNzZkHfNJ95VJheFHVXaBzgu', 'client', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-10 01:28:09', '2026-06-10 01:28:09', NULL, NULL),
(25, 'Cliente Test', 'cliente.test@trimly.com', '+5492920111222', '$2y$12$kMSn.d9p3qWEeAGYTr0MU.NFdN2MwAgnVxKOAWf2F9Ft.9Z6n6RSq', 'client', 'avatars/img_6a358a8bcc5966.06820788.png', 1, NULL, NULL, NULL, 'active', '2026-06-19 16:08:21', '2026-06-10 01:55:51', '2026-06-19 16:08:21', NULL, NULL),
(26, 'Negocio Test', 'negocio.test@trimly.com', '+5492920500002', '$2y$12$YSJd8YC63E3PgaXdc9bIGeF1.ok1OUxnUZn57buIoBduvx4lC3Ya2', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-10 02:00:59', '2026-06-10 01:55:51', '2026-06-10 02:00:59', NULL, NULL),
(27, 'insanos eticos', 'lucas@gmail.com', '+5492920111222', '$2y$12$hCUgrYqWVzBIFLM/ttTKu.BOwM93r2X59Z9Xycctq9Jx4LLEmZ0k.', 'employee', NULL, 1, '2b8cab181624c1346175c16e4881c4d14902b33472cd4db1eb658215e50ebeff', NULL, NULL, 'suspended', '2026-06-18 01:34:01', '2026-06-11 22:46:30', '2026-06-18 01:46:25', 'no sabes que hacer', '2026-06-30 01:46:25'),
(28, 'Valentina Ortiz', 'valentina.ortiz0@gmail.com', '+549292011000', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-21 07:14:00', '2026-05-18 07:14:00', '2026-05-18 07:14:00', NULL, NULL),
(29, 'Morena López', 'morena.lopez1@gmail.com', '+549292011001', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-13 13:02:00', '2026-06-07 13:02:00', '2026-06-07 13:02:00', NULL, NULL),
(30, 'Leandro García', 'leandro.garcia2@gmail.com', '+549292011002', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-22 06:45:00', '2026-06-05 06:45:00', '2026-06-05 06:45:00', NULL, NULL),
(31, 'Ezequiel Rivas', 'ezequiel.rivas3@gmail.com', '+549292011003', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-05-18 00:48:00', '2026-05-18 00:48:00', NULL, NULL),
(32, 'Agostina Suárez', 'agostina.suarez4@gmail.com', '+549292011004', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-12 06:48:00', '2026-05-10 06:48:00', '2026-05-10 06:48:00', NULL, NULL),
(33, 'Franco Ibarra', 'franco.ibarra5@gmail.com', '+549292011005', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-05-23 19:16:00', '2026-05-23 19:16:00', NULL, NULL),
(34, 'Morena Martínez', 'morena.martinez6@gmail.com', '+549292011006', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-14 02:35:00', '2026-05-25 02:35:00', '2026-05-25 02:35:00', NULL, NULL),
(35, 'Milagros Rivas', 'milagros.rivas7@gmail.com', '+549292011007', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-20 22:04:00', '2026-05-13 22:04:00', '2026-05-13 22:04:00', NULL, NULL),
(36, 'Mía Ibarra', 'mia.ibarra8@gmail.com', '+549292011008', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-04 03:24:00', '2026-05-15 03:24:00', '2026-05-15 03:24:00', NULL, NULL),
(37, 'Isabella Acosta', 'isabella.acosta9@gmail.com', '+549292011009', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-12 06:42:00', '2026-05-23 06:42:00', '2026-05-23 06:42:00', NULL, NULL),
(38, 'Agustín Vega', 'agustin.vega10@gmail.com', '+549292011010', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-05 05:29:00', '2026-05-16 05:29:00', '2026-05-16 05:29:00', NULL, NULL),
(39, 'Pilar Cabrera', 'pilar.cabrera11@gmail.com', '+549292011011', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-05-21 01:14:00', '2026-05-21 01:14:00', NULL, NULL),
(40, 'Abril Suárez', 'abril.suarez12@gmail.com', '+549292011012', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-05-05 06:58:00', '2026-05-05 06:58:00', NULL, NULL),
(41, 'Agostina Sánchez', 'agostina.sanchez13@gmail.com', '+549292011013', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-11 15:25:00', '2026-06-11 15:25:00', NULL, NULL),
(42, 'Catalina Suárez', 'catalina.suarez14@gmail.com', '+549292011014', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-17 07:47:00', '2026-05-09 07:47:00', '2026-05-09 07:47:00', NULL, NULL),
(43, 'Julieta Luna', 'julieta.luna15@gmail.com', '+549292011015', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-11 12:23:00', '2026-06-07 12:23:00', '2026-06-07 12:23:00', NULL, NULL),
(44, 'Mía Molina', 'mia.molina16@gmail.com', '+549292011016', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-17 03:09:00', '2026-05-04 03:09:00', '2026-05-04 03:09:00', NULL, NULL),
(45, 'Abril Benítez', 'abril.benitez17@gmail.com', '+549292011017', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-25 14:33:00', '2026-06-08 14:33:00', '2026-06-08 14:33:00', NULL, NULL),
(46, 'Valentina Cabrera', 'valentina.cabrera18@gmail.com', '+549292011018', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-16 21:56:00', '2026-05-08 21:56:00', '2026-05-08 21:56:00', NULL, NULL),
(47, 'Agostina Martínez', 'agostina.martinez19@gmail.com', '+549292011019', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-27 13:10:00', '2026-05-19 13:10:00', '2026-05-19 13:10:00', NULL, NULL),
(48, 'Isabella Castro', 'isabella.castro20@gmail.com', '+549292011020', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-05-07 20:19:00', '2026-05-07 20:19:00', NULL, NULL),
(49, 'Renata González', 'renata.gonzalez21@gmail.com', '+549292011021', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-05-24 05:34:00', '2026-05-24 05:34:00', NULL, NULL),
(50, 'Valentina Domínguez', 'valentina.dominguez22@gmail.com', '+549292011022', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-01 15:01:00', '2026-05-21 15:01:00', '2026-05-21 15:01:00', NULL, NULL),
(51, 'Lucía Romero', 'lucia.romero23@gmail.com', '+549292011023', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-06 07:56:00', '2026-05-04 07:56:00', '2026-05-04 07:56:00', NULL, NULL),
(52, 'Maximiliano Sosa', 'maximiliano.sosa24@gmail.com', '+549292011024', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-20 17:49:00', '2026-05-05 17:49:00', '2026-05-05 17:49:00', NULL, NULL),
(53, 'Isabella Suárez', 'isabella.suarez25@gmail.com', '+549292011025', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-03 19:27:00', '2026-06-03 19:27:00', NULL, NULL),
(54, 'Renata Silva', 'renata.silva26@gmail.com', '+549292011026', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-03 12:42:00', '2026-05-20 12:42:00', '2026-05-20 12:42:00', NULL, NULL),
(55, 'Antonella Martínez', 'antonella.martinez27@gmail.com', '+549292011027', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-03 07:04:00', '2026-05-16 07:04:00', '2026-05-16 07:04:00', NULL, NULL),
(56, 'Victoria Romero', 'victoria.romero28@gmail.com', '+549292011028', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-08 02:45:00', '2026-05-01 02:45:00', '2026-05-01 02:45:00', NULL, NULL),
(57, 'Mateo Ibarra', 'mateo.ibarra29@gmail.com', '+549292011029', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-06 02:32:00', '2026-05-22 02:32:00', '2026-05-22 02:32:00', NULL, NULL),
(58, 'Facundo Ortiz', 'facundo.ortiz30@gmail.com', '+549292011030', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-21 18:30:00', '2026-06-06 18:30:00', '2026-06-06 18:30:00', NULL, NULL),
(59, 'Renata Martínez', 'renata.martinez31@gmail.com', '+549292011031', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-20 21:27:00', '2026-05-07 21:27:00', '2026-05-07 21:27:00', NULL, NULL),
(60, 'Mateo Cabrera', 'mateo.cabrera32@gmail.com', '+549292011032', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-21 20:06:00', '2026-06-11 20:06:00', '2026-06-11 20:06:00', NULL, NULL),
(61, 'Emma Romero', 'emma.romero33@gmail.com', '+549292011033', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-26 06:34:00', '2026-05-13 06:34:00', '2026-05-13 06:34:00', NULL, NULL),
(62, 'Ezequiel Romero', 'ezequiel.romero34@gmail.com', '+549292011034', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-05-05 14:51:00', '2026-05-05 14:51:00', NULL, NULL),
(63, 'Martina Núñez', 'martina.nuñez35@gmail.com', '+549292011035', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-04 00:05:00', '2026-06-04 00:05:00', NULL, NULL),
(64, 'Isabella Medina', 'isabella.medina36@gmail.com', '+549292011036', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-06-01 15:13:00', '2026-06-01 15:13:00', NULL, NULL),
(65, 'Isabella Benítez', 'isabella.benitez37@gmail.com', '+549292011037', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', NULL, '2026-05-01 12:16:00', '2026-05-01 12:16:00', NULL, NULL),
(66, 'Lucía Medina', 'lucia.medina38@gmail.com', '+549292011038', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-29 23:50:00', '2026-06-14 23:50:00', '2026-06-14 23:50:00', NULL, NULL),
(67, 'Thiago Sánchez', 'thiago.sanchez39@gmail.com', '+549292011039', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-14 18:47:00', '2026-05-04 18:47:00', '2026-05-04 18:47:00', NULL, NULL),
(68, 'Damián Aguirre', 'owner1.aguirre@trimly-demo.com', '+549292052000', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-06 00:00:00', '2026-05-05 00:00:00', '2026-05-05 00:00:00', NULL, NULL),
(69, 'Guadalupe Pérez', 'owner2.pérez@trimly-demo.com', '+549292052001', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-09 00:00:00', '2026-05-08 00:00:00', '2026-05-08 00:00:00', NULL, NULL),
(70, 'Rodrigo López', 'owner3.lópez@trimly-demo.com', '+549292052002', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-12 00:00:00', '2026-05-11 00:00:00', '2026-05-11 00:00:00', NULL, NULL),
(71, 'Mía Domínguez', 'owner4.dominguez@trimly-demo.com', '+549292052003', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-15 00:00:00', '2026-05-14 00:00:00', '2026-05-14 00:00:00', NULL, NULL),
(72, 'Santino Benítez', 'owner5.benitez@trimly-demo.com', '+549292052004', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-18 00:00:00', '2026-05-17 00:00:00', '2026-05-17 00:00:00', NULL, NULL),
(73, 'Damián Romero', 'empleado1.romero@trimly-demo.com', '+549292063000', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'employee', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-12 00:00:00', '2026-05-10 00:00:00', '2026-05-10 00:00:00', NULL, NULL),
(74, 'Martina Domínguez', 'empleado2.dominguez@trimly-demo.com', '+549292063001', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'employee', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-14 00:00:00', '2026-05-12 00:00:00', '2026-05-12 00:00:00', NULL, NULL),
(75, 'Damián Rivas', 'empleado3.rivas@trimly-demo.com', '+549292063002', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'employee', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-16 00:00:00', '2026-05-14 00:00:00', '2026-05-14 00:00:00', NULL, NULL),
(76, 'Delfina Sánchez', 'empleado4.sánchez@trimly-demo.com', '+549292063003', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'employee', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-18 00:00:00', '2026-05-16 00:00:00', '2026-05-16 00:00:00', NULL, NULL),
(77, 'Agostina Romero', 'empleado5.romero@trimly-demo.com', '+549292063004', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'employee', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-20 00:00:00', '2026-05-18 00:00:00', '2026-05-18 00:00:00', NULL, NULL),
(78, 'Facundo Cabrera', 'empleado6.cabrera@trimly-demo.com', '+549292063005', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'employee', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-22 00:00:00', '2026-05-20 00:00:00', '2026-05-20 00:00:00', NULL, NULL),
(79, 'Antonella Flores', 'empleado7.flores@trimly-demo.com', '+549292063006', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'employee', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-24 00:00:00', '2026-05-22 00:00:00', '2026-05-22 00:00:00', NULL, NULL),
(80, 'Mía García', 'empleado8.garcia@trimly-demo.com', '+549292063007', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'employee', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-26 00:00:00', '2026-05-24 00:00:00', '2026-05-24 00:00:00', NULL, NULL),
(131, 'Julián Iturbe', 'julian.iturbe.employee131@trimly-demo.com', '+5492922721960', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'employee', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-10 03:10:00', '2026-06-09 03:10:00', '2026-06-09 03:10:00', NULL, NULL),
(132, 'Jazmín Juárez', 'jazmin.juarez.employee132@trimly-demo.com', '+5492928056747', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'employee', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-10 03:10:00', '2026-06-09 03:10:00', '2026-06-09 03:10:00', NULL, NULL),
(133, 'Iara Heredia', 'iara.heredia.employee133@trimly-demo.com', '+5492928400346', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'employee', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-11 03:05:51', '2026-06-10 03:05:51', '2026-06-10 03:05:51', NULL, NULL),
(134, 'Kevin Bustos', 'kevin.bustos.employee134@trimly-demo.com', '+5492922857850', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'employee', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-11 03:05:51', '2026-06-10 03:05:51', '2026-06-10 03:05:51', NULL, NULL),
(135, 'Karina Juárez', 'karina.juarez.shop_owner135@trimly-demo.com', '+5492619808632', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', '2026-04-21 09:00:00', '2026-04-20 09:00:00', '2026-04-20 09:00:00', NULL, NULL),
(136, 'Dante Alvarez', 'dante.alvarez.shop_owner136@trimly-demo.com', '+5492617618344', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', '2026-04-26 10:00:00', '2026-04-25 10:00:00', '2026-04-25 10:00:00', NULL, NULL),
(137, 'Daniela Fonseca', 'daniela.fonseca.shop_owner137@trimly-demo.com', '+5493516204339', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', '2026-04-29 11:00:00', '2026-04-28 11:00:00', '2026-04-28 11:00:00', NULL, NULL),
(138, 'Iara Diaz', 'iara.diaz.shop_owner138@trimly-demo.com', '+5493513960615', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-03 09:30:00', '2026-05-02 09:30:00', '2026-05-02 09:30:00', NULL, NULL),
(139, 'Daniela Leiva', 'daniela.leiva.shop_owner139@trimly-demo.com', '+5493429678793', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'shop_owner', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-07 14:00:00', '2026-05-06 14:00:00', '2026-05-06 14:00:00', NULL, NULL),
(140, 'Bruno Bustos', 'bruno.bustos.employee140@trimly-demo.com', '+5493427426673', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'employee', NULL, 1, NULL, NULL, NULL, 'active', '2026-04-21 10:00:00', '2026-04-20 10:00:00', '2026-04-20 10:00:00', NULL, NULL),
(141, 'Bianca Alvarez', 'bianca.alvarez.employee141@trimly-demo.com', '+5493413638883', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'employee', NULL, 1, NULL, NULL, NULL, 'active', '2026-04-21 10:00:00', '2026-04-20 10:00:00', '2026-04-20 10:00:00', NULL, NULL),
(142, 'Clara Juárez', 'clara.juarez.employee142@trimly-demo.com', '+5492612297053', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'employee', NULL, 1, NULL, NULL, NULL, 'active', '2026-04-26 11:00:00', '2026-04-25 11:00:00', '2026-04-25 11:00:00', NULL, NULL),
(143, 'Marcos Diaz', 'marcos.diaz.employee143@trimly-demo.com', '+5493514484881', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'employee', NULL, 1, NULL, NULL, NULL, 'active', '2026-04-26 11:00:00', '2026-04-25 11:00:00', '2026-04-25 11:00:00', NULL, NULL),
(144, 'Ciro Juárez', 'ciro.juarez.employee144@trimly-demo.com', '+5493412376520', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'employee', NULL, 1, NULL, NULL, NULL, 'active', '2026-04-29 12:00:00', '2026-04-28 12:00:00', '2026-04-28 12:00:00', NULL, NULL),
(145, 'Kevin Fonseca', 'kevin.fonseca.employee145@trimly-demo.com', '+5493519267112', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'employee', NULL, 1, NULL, NULL, NULL, 'active', '2026-04-29 12:00:00', '2026-04-28 12:00:00', '2026-04-28 12:00:00', NULL, NULL),
(146, 'Kevin Leiva', 'kevin.leiva.employee146@trimly-demo.com', '+5493411538712', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'employee', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-03 10:30:00', '2026-05-02 10:30:00', '2026-05-02 10:30:00', NULL, NULL),
(147, 'Florencia Klein', 'florencia.klein.employee147@trimly-demo.com', '+5492618600753', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'employee', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-03 10:30:00', '2026-05-02 10:30:00', '2026-05-02 10:30:00', NULL, NULL),
(148, 'Ciro Diaz', 'ciro.diaz.employee148@trimly-demo.com', '+5493426971381', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'employee', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-07 15:00:00', '2026-05-06 15:00:00', '2026-05-06 15:00:00', NULL, NULL),
(149, 'Iara Giménez', 'iara.gimenez.employee149@trimly-demo.com', '+5493516651778', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'employee', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-07 15:00:00', '2026-05-06 15:00:00', '2026-05-06 15:00:00', NULL, NULL),
(150, 'Helena Diaz', 'helena.diaz.client150@trimly-demo.com', '+5493418355025', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-07 19:00:00', '2026-06-06 19:00:00', '2026-06-06 19:00:00', NULL, NULL),
(151, 'Kevin Iturbe', 'kevin.iturbe.client151@trimly-demo.com', '+5493517186637', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-11 13:00:00', '2026-06-10 13:00:00', '2026-06-10 13:00:00', NULL, NULL),
(152, 'Iara Juárez', 'iara.juarez.client152@trimly-demo.com', '+5493512387122', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-26 15:00:00', '2026-05-25 15:00:00', '2026-05-25 15:00:00', NULL, NULL),
(153, 'Gael Espinoza', 'gael.espinoza.client153@trimly-demo.com', '+5492617273405', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-15 15:00:00', '2026-05-14 15:00:00', '2026-05-14 15:00:00', NULL, NULL),
(154, 'Bianca Leiva', 'bianca.leiva.client154@trimly-demo.com', '+5492615195742', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-04 17:00:00', '2026-05-03 17:00:00', '2026-05-03 17:00:00', NULL, NULL),
(155, 'Karina Cardozo', 'karina.cardozo.client155@trimly-demo.com', '+5493517136346', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-17 18:00:00', '2026-05-16 18:00:00', '2026-05-16 18:00:00', NULL, NULL),
(156, 'Estefanía Fonseca', 'estefania.fonseca.client156@trimly-demo.com', '+5493516784655', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-04-26 20:00:00', '2026-04-25 20:00:00', '2026-04-25 20:00:00', NULL, NULL),
(157, 'Daniela Leiva', 'daniela.leiva.client157@trimly-demo.com', '+5492615961129', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-04-29 15:00:00', '2026-04-28 15:00:00', '2026-04-28 15:00:00', NULL, NULL),
(158, 'Ciro Fonseca', 'ciro.fonseca.client158@trimly-demo.com', '+5493427062072', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-23 23:00:00', '2026-05-22 23:00:00', '2026-05-22 23:00:00', NULL, NULL),
(159, 'Bianca Leiva', 'bianca.leiva.client159@trimly-demo.com', '+5492615404127', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-15 00:00:00', '2026-05-14 00:00:00', '2026-05-14 00:00:00', NULL, NULL),
(160, 'Elián Heredia', 'elian.heredia.client160@trimly-demo.com', '+5493419063545', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-10 05:00:00', '2026-06-09 05:00:00', '2026-06-09 05:00:00', NULL, NULL),
(161, 'Ciro Espinoza', 'ciro.espinoza.client161@trimly-demo.com', '+5492611160882', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-17 15:00:00', '2026-05-16 15:00:00', '2026-05-16 15:00:00', NULL, NULL),
(162, 'Marcos Bustos', 'marcos.bustos.client162@trimly-demo.com', '+5493422609347', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-27 17:00:00', '2026-05-26 17:00:00', '2026-05-26 17:00:00', NULL, NULL),
(163, 'Hernán Fonseca', 'hernan.fonseca.client163@trimly-demo.com', '+5493513724040', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-03 22:00:00', '2026-05-02 22:00:00', '2026-05-02 22:00:00', NULL, NULL),
(164, 'Kevin Fonseca', 'kevin.fonseca.client164@trimly-demo.com', '+5493419642750', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-11 03:00:00', '2026-05-10 03:00:00', '2026-05-10 03:00:00', NULL, NULL),
(165, 'Bianca Espinoza', 'bianca.espinoza.client165@trimly-demo.com', '+5493511845270', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-07 04:00:00', '2026-06-06 04:00:00', '2026-06-06 04:00:00', NULL, NULL),
(166, 'Bruno Klein', 'bruno.klein.client166@trimly-demo.com', '+5493513588395', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-29 23:00:00', '2026-05-28 23:00:00', '2026-05-28 23:00:00', NULL, NULL),
(167, 'Ciro Bustos', 'ciro.bustos.client167@trimly-demo.com', '+5492617707293', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-01 15:00:00', '2026-05-31 15:00:00', '2026-05-31 15:00:00', NULL, NULL),
(168, 'Marcos Diaz', 'marcos.diaz.client168@trimly-demo.com', '+5492617465140', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-10 20:00:00', '2026-06-09 20:00:00', '2026-06-09 20:00:00', NULL, NULL),
(169, 'Hernán Iturbe', 'hernan.iturbe.client169@trimly-demo.com', '+5492613557157', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-25 04:00:00', '2026-05-24 04:00:00', '2026-05-24 04:00:00', NULL, NULL),
(170, 'Marcos Diaz', 'marcos.diaz.client170@trimly-demo.com', '+5492613913695', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-04-30 01:00:00', '2026-04-29 01:00:00', '2026-04-29 01:00:00', NULL, NULL),
(171, 'Julián Espinoza', 'julian.espinoza.client171@trimly-demo.com', '+5493419152260', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-29 07:00:00', '2026-05-28 07:00:00', '2026-05-28 07:00:00', NULL, NULL),
(172, 'Elián Iturbe', 'elian.iturbe.client172@trimly-demo.com', '+5493519724183', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-06-09 23:00:00', '2026-06-08 23:00:00', '2026-06-08 23:00:00', NULL, NULL),
(173, 'Marcos Juárez', 'marcos.juarez.client173@trimly-demo.com', '+5493414512459', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-04 04:00:00', '2026-05-03 04:00:00', '2026-05-03 04:00:00', NULL, NULL),
(174, 'Giuliana Fonseca', 'giuliana.fonseca.client174@trimly-demo.com', '+5493419445632', '$2y$12$KX8vLm3pQr7nZ1wYoP2sOeT4uI6jH9kA0fN5bM8cV3xL1dG7tR2q.', 'client', NULL, 1, NULL, NULL, NULL, 'active', '2026-05-08 06:00:00', '2026-05-07 06:00:00', '2026-05-07 06:00:00', NULL, NULL);

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
-- Indices de la tabla `stat_periods`
--
ALTER TABLE `stat_periods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_dates` (`date_from`,`date_to`);

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=342;

--
-- AUTO_INCREMENT de la tabla `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT de la tabla `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `employee_hours`
--
ALTER TABLE `employee_hours`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=283;

--
-- AUTO_INCREMENT de la tabla `employee_photos`
--
ALTER TABLE `employee_photos`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=292;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT de la tabla `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT de la tabla `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `shop_closures`
--
ALTER TABLE `shop_closures`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `shop_hours`
--
ALTER TABLE `shop_hours`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=176;

--
-- AUTO_INCREMENT de la tabla `shop_moderation_log`
--
ALTER TABLE `shop_moderation_log`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `shop_photos`
--
ALTER TABLE `shop_photos`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de la tabla `shop_reports`
--
ALTER TABLE `shop_reports`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `stat_periods`
--
ALTER TABLE `stat_periods`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;

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

--
-- Filtros para la tabla `stat_periods`
--
ALTER TABLE `stat_periods`
  ADD CONSTRAINT `stat_periods_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
