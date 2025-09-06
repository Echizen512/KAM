-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-09-2025 a las 20:17:44
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `base_kam`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `id_personal` int(11) NOT NULL,
  `cedula_personal` varchar(15) NOT NULL,
  `fecha_asistencia` date NOT NULL,
  `hora_entrada` time DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `tipo_asistencia` enum('manual','huella') DEFAULT 'manual',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `plantilla_huella` longtext DEFAULT NULL,
  `estado_asistencia` enum('confirmada','pendiente','rechazada') DEFAULT 'pendiente',
  `id_sesion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `asistencia`
--

INSERT INTO `asistencia` (`id_personal`, `cedula_personal`, `fecha_asistencia`, `hora_entrada`, `hora_salida`, `tipo_asistencia`, `creado_en`, `actualizado_en`, `plantilla_huella`, `estado_asistencia`, `id_sesion`) VALUES
(6, 'V-31500713', '2025-03-09', '23:45:46', '23:46:09', 'manual', '2025-03-10 03:45:47', '2025-03-10 03:46:10', NULL, 'pendiente', NULL),
(7, 'V-31500713', '2025-03-10', '00:02:55', '00:03:17', 'manual', '2025-03-10 04:02:55', '2025-03-10 04:03:18', NULL, 'pendiente', NULL),
(8, 'V-28431911', '2025-03-10', '00:07:03', '00:07:19', 'manual', '2025-03-10 04:07:04', '2025-03-10 04:07:21', NULL, 'pendiente', NULL),
(9, 'V-32410778', '2025-03-10', '00:11:28', '00:11:51', 'manual', '2025-03-10 04:11:30', '2025-03-10 04:11:52', NULL, 'pendiente', NULL),
(10, 'V-17246672', '2025-03-10', '00:23:44', '00:36:04', 'manual', '2025-03-10 04:23:45', '2025-03-10 04:36:05', NULL, 'pendiente', NULL),
(11, 'V-3896345', '2025-03-10', '00:34:42', '00:34:49', 'manual', '2025-03-10 04:34:43', '2025-03-10 04:34:50', NULL, 'pendiente', NULL),
(12, 'V-7220772', '2025-03-10', '00:35:20', '00:35:33', 'manual', '2025-03-10 04:35:21', '2025-03-10 04:35:34', NULL, 'pendiente', NULL),
(13, 'V-18552119', '2025-03-10', '00:44:41', '00:44:58', 'manual', '2025-03-10 04:44:42', '2025-03-10 04:44:59', NULL, 'pendiente', NULL),
(14, 'V-10361498', '2025-03-10', '16:57:45', '16:58:03', 'manual', '2025-03-10 20:57:46', '2025-03-10 20:58:04', NULL, 'pendiente', NULL),
(15, 'V-8576270', '2025-03-10', '21:02:51', '21:03:11', 'manual', '2025-03-11 01:02:52', '2025-03-11 01:03:12', NULL, 'pendiente', NULL),
(17, 'V-31500713', '2025-03-11', '21:35:15', '21:37:28', 'manual', '2025-03-12 01:35:16', '2025-03-12 01:37:30', NULL, 'pendiente', NULL),
(21, 'V-28431911', '2025-03-13', '15:45:39', '15:46:15', 'manual', '2025-03-13 19:45:41', '2025-03-13 19:46:16', NULL, 'pendiente', NULL),
(22, 'V-32410778', '2025-03-13', '15:47:24', '15:48:11', 'manual', '2025-03-13 19:47:25', '2025-03-13 19:48:12', NULL, 'pendiente', NULL),
(24, 'V-31500713', '2025-03-15', '13:28:35', '13:28:56', 'manual', '2025-03-15 17:28:36', '2025-03-15 17:28:57', NULL, 'pendiente', NULL),
(25, 'V-28431911', '2025-03-15', '21:24:56', '21:32:23', 'manual', '2025-03-16 01:24:57', '2025-03-16 01:32:24', NULL, 'pendiente', NULL),
(26, 'V-3896345', '2025-03-15', '21:42:40', '21:47:50', 'manual', '2025-03-16 01:42:40', '2025-03-16 01:47:51', NULL, 'pendiente', NULL),
(27, 'V-32410778', '2025-03-15', '21:50:56', '21:59:47', 'manual', '2025-03-16 01:50:57', '2025-03-16 01:59:48', NULL, 'pendiente', NULL),
(28, 'V-7220772', '2025-03-15', '22:05:05', '22:10:14', 'manual', '2025-03-16 02:05:06', '2025-03-16 02:10:15', NULL, 'pendiente', NULL),
(29, 'V-17246672', '2025-03-15', '22:57:42', '22:59:52', 'manual', '2025-03-16 02:57:42', '2025-03-16 02:59:52', NULL, 'pendiente', NULL),
(31, 'V-28433911', '2025-03-16', '19:25:51', '19:26:22', 'manual', '2025-03-16 23:25:52', '2025-03-16 23:26:23', NULL, 'pendiente', NULL),
(32, 'V-32410778', '2025-03-16', '19:36:01', '19:36:21', 'manual', '2025-03-16 23:36:02', '2025-03-16 23:36:22', NULL, 'pendiente', NULL),
(33, 'V-31500713', '2025-03-17', '20:29:00', '20:29:17', 'manual', '2025-03-18 00:29:01', '2025-03-18 00:29:18', NULL, 'pendiente', NULL),
(35, 'V-28443926', '2025-03-17', '20:30:36', NULL, 'manual', '2025-03-18 00:30:37', '2025-03-18 00:30:37', NULL, 'pendiente', NULL),
(37, 'V-31500713', '2025-03-19', '21:58:14', '21:58:21', 'manual', '2025-03-20 01:58:15', '2025-03-20 01:58:21', NULL, 'pendiente', NULL),
(38, 'V-31500713', '2025-03-21', '10:11:10', '10:11:21', 'manual', '2025-03-21 14:11:11', '2025-03-21 14:11:22', NULL, 'pendiente', NULL),
(39, 'V-32410778', '2025-03-21', '10:13:32', '10:13:39', 'manual', '2025-03-21 14:13:33', '2025-03-21 14:13:40', NULL, 'pendiente', NULL),
(40, 'V-32410778', '2025-03-22', '19:17:52', '19:18:09', 'manual', '2025-03-22 23:17:57', '2025-03-22 23:18:13', NULL, 'pendiente', NULL),
(41, 'V-28431911', '2025-03-26', '22:03:21', '22:04:11', 'manual', '2025-03-27 02:03:22', '2025-03-27 02:04:12', NULL, 'pendiente', NULL),
(47, 'V-31500713', '2025-03-31', '19:38:50', '19:39:10', 'manual', '2025-03-31 23:38:51', '2025-03-31 23:39:11', NULL, 'pendiente', NULL),
(48, 'V-31500713', '2025-04-07', '10:21:42', '10:21:47', 'manual', '2025-04-07 14:21:43', '2025-04-07 14:21:47', NULL, 'pendiente', NULL),
(86, '', '2025-04-07', '22:06:18', NULL, 'huella', '2025-04-08 02:06:20', '2025-04-08 02:06:20', NULL, 'pendiente', NULL),
(87, 'V-3150071', '2025-04-07', '22:06:57', NULL, 'manual', '2025-04-08 02:06:58', '2025-04-08 02:06:58', NULL, 'pendiente', NULL),
(88, 'V-31500713', '2025-04-08', '10:23:59', '10:24:11', 'manual', '2025-04-08 14:24:01', '2025-04-08 14:24:12', NULL, 'pendiente', NULL),
(89, 'entrada', '2025-04-08', '12:34:50', NULL, 'manual', '2025-04-08 16:34:50', '2025-04-08 16:34:50', NULL, 'pendiente', NULL),
(90, 'V-3896345', '2025-08-20', '22:26:56', NULL, 'manual', '2025-08-21 02:26:58', '2025-08-21 02:26:58', NULL, 'pendiente', NULL),
(91, 'entrada', '2025-08-22', '21:24:53', NULL, 'manual', '2025-08-23 01:24:53', '2025-08-23 01:24:53', NULL, 'pendiente', NULL),
(92, 'V-31500713', '2025-08-22', '21:26:28', '21:36:56', 'manual', '2025-08-23 01:26:30', '2025-08-23 01:36:57', NULL, 'pendiente', NULL),
(93, 'V-31500713', '2025-08-22', '21:26:35', '21:36:56', 'manual', '2025-08-23 01:26:36', '2025-08-23 01:36:57', NULL, 'pendiente', NULL),
(94, 'V-17246672', '2025-08-26', '08:02:30', NULL, 'manual', '2025-08-26 12:02:31', '2025-08-26 12:02:31', NULL, 'pendiente', NULL),
(95, 'V-8815415', '2025-08-27', '17:29:24', NULL, 'manual', '2025-08-27 21:29:27', '2025-08-27 21:29:27', NULL, 'pendiente', NULL),
(96, 'V-7220772', '2025-09-03', '19:12:30', '19:13:38', 'manual', '2025-09-03 23:12:31', '2025-09-03 23:13:42', NULL, 'pendiente', NULL),
(97, 'V-4368163', '2025-09-03', '21:40:35', NULL, 'manual', '2025-09-04 01:40:37', '2025-09-04 01:40:37', NULL, 'pendiente', NULL),
(98, 'V-7151988', '2025-09-03', '21:45:20', NULL, 'manual', '2025-09-04 01:45:21', '2025-09-04 01:45:21', NULL, 'pendiente', NULL),
(99, 'V-3896345', '2025-09-03', '21:54:26', NULL, 'manual', '2025-09-04 01:54:27', '2025-09-04 01:54:27', NULL, 'pendiente', NULL),
(100, 'V-8815415', '2025-09-06', '11:37:38', '11:38:46', 'manual', '2025-09-06 15:37:39', '2025-09-06 15:38:47', NULL, 'pendiente', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nombre_usuario` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `accion` varchar(255) NOT NULL,
  `fecha_hora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `bitacora`
--

INSERT INTO `bitacora` (`id`, `usuario_id`, `nombre_usuario`, `correo`, `accion`, `fecha_hora`) VALUES
(59, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-03-24 13:30:05'),
(60, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-03-24 13:37:46'),
(61, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-03-24 13:42:36'),
(62, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-03-24 19:01:51'),
(63, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-03-24 19:20:24'),
(64, 24, 'Marianny', 'marian@gmail.com', 'Inicio de sesiÃ³n', '2025-03-25 01:38:45'),
(65, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-03-25 03:59:37'),
(66, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-03-25 08:37:24'),
(67, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-03-25 08:43:35'),
(68, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-03-26 17:12:37'),
(69, 25, 'karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-03-26 17:32:06'),
(70, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-03-26 18:42:44'),
(71, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-03-26 22:32:19'),
(72, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-03-27 16:30:09'),
(73, 23, 'Alexandra', 'xandrapini@gmail.com', 'Inicio de sesiÃ³n', '2025-03-27 18:41:59'),
(74, 24, 'Marianny', 'marian@gmail.com', 'Inicio de sesiÃ³n', '2025-03-27 18:42:44'),
(75, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-03-27 18:43:32'),
(77, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-03-31 20:49:47'),
(79, 25, 'Karla', 'karlaad142@gmail.com', 'Añadio personal ', '2025-03-31 20:55:26'),
(80, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-04-01 19:15:47'),
(81, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-04-02 20:34:26'),
(82, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-04-02 20:34:27'),
(83, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-04-02 20:34:28'),
(84, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-04-02 20:34:28'),
(85, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-04-02 20:34:29'),
(86, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-04-02 20:34:29'),
(87, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-04-02 20:34:30'),
(88, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-04-02 20:37:21'),
(89, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-04-03 19:32:37'),
(90, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-04-07 20:39:07'),
(91, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-04-07 21:56:01'),
(92, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-04-07 22:03:35'),
(93, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-04-08 10:20:27'),
(94, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-04-09 09:57:31'),
(95, 25, 'Karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-04-09 09:57:33'),
(96, 25, 'karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-04-09 11:34:04'),
(97, 25, 'karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-04-09 11:34:07'),
(98, 25, 'karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-04-09 11:34:13'),
(99, 25, 'karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-04-09 11:34:14'),
(100, 25, 'karla', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-04-09 11:34:14'),
(101, 23, 'Alexandra', 'xandrapini@gmail.com', 'Inicio de sesiÃ³n', '2025-06-02 22:27:04'),
(102, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-06-06 10:19:51'),
(103, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-06-07 20:15:42'),
(104, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-06-11 17:58:49'),
(105, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-06-13 19:37:01'),
(106, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-06-13 19:37:18'),
(107, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-06-16 13:55:13'),
(108, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-06-18 17:49:38'),
(109, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-06-23 11:13:49'),
(110, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-07-03 11:41:16'),
(111, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-07-10 20:46:41'),
(112, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-07-10 20:46:43'),
(113, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-07-13 22:57:05'),
(114, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-07-14 19:05:42'),
(115, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesiÃ³n', '2025-07-14 19:06:03'),
(116, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-22 21:42:54'),
(117, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-23 09:44:40'),
(118, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-23 10:54:01'),
(119, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-23 15:39:46'),
(120, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-23 15:39:53'),
(121, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-23 15:39:54'),
(122, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-23 15:40:24'),
(123, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-23 15:40:50'),
(124, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-23 16:52:00'),
(125, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-23 18:52:50'),
(126, 24, 'Marianny', 'marian@gmail.com', 'Inicio de sesión', '2025-08-24 08:15:33'),
(127, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-24 08:16:36'),
(128, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-24 08:32:12'),
(129, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-24 21:11:43'),
(130, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-24 21:11:43'),
(131, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-24 21:11:43'),
(132, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-24 21:11:51'),
(133, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-25 00:26:40'),
(134, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-25 08:57:16'),
(135, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-25 09:15:51'),
(136, 26, 'Echizen5135', 'jmrm19722@gmail.com', 'Inicio de sesión', '2025-08-25 16:53:37'),
(137, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-26 08:03:05'),
(138, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-26 08:03:06'),
(139, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-26 09:09:12'),
(140, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-26 09:19:48'),
(141, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-27 17:30:15'),
(142, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-08-27 17:39:49'),
(143, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-09-03 19:01:47'),
(144, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-09-03 19:12:45'),
(145, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-09-03 19:13:54'),
(146, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-09-03 19:13:55'),
(147, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-09-03 21:40:57'),
(148, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-09-03 21:41:02'),
(149, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-09-03 21:45:45'),
(150, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-09-03 21:55:05'),
(151, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-09-05 11:57:19'),
(152, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-09-05 12:20:13'),
(153, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-09-05 12:22:43'),
(154, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-09-05 12:39:39'),
(155, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-09-05 17:04:08'),
(156, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-09-06 11:17:51'),
(157, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-09-06 11:37:54'),
(158, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-09-06 11:39:08'),
(159, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-09-06 11:42:26'),
(160, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-09-06 11:44:47'),
(161, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-09-06 11:54:24'),
(162, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-09-06 11:58:42'),
(163, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-09-06 12:05:59'),
(164, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-09-06 12:11:11'),
(165, 31, 'Admin', 'jmrm19722@gmail.com', 'Inicio de sesión', '2025-09-06 12:19:20'),
(166, 25, 'kil', 'karlaad142@gmail.com', 'Inicio de sesión', '2025-09-06 13:07:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bloques_completo`
--

CREATE TABLE `bloques_completo` (
  `id` int(11) NOT NULL,
  `horario_id` int(11) NOT NULL,
  `dia` varchar(20) NOT NULL,
  `bloque_hora` varchar(50) NOT NULL,
  `nivel` varchar(50) NOT NULL,
  `seccion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bloques_parcial`
--

CREATE TABLE `bloques_parcial` (
  `id` int(11) NOT NULL,
  `horario_id` int(11) NOT NULL,
  `dia` varchar(20) NOT NULL,
  `hora` varchar(50) NOT NULL,
  `nivel` varchar(50) NOT NULL,
  `seccion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bloques_parcial`
--

INSERT INTO `bloques_parcial` (`id`, `horario_id`, `dia`, `hora`, `nivel`, `seccion`) VALUES
(1, 1, 'Lunes', '7:00 AM - 9:30 AM', '1° nivel', '1'),
(2, 2, 'Lunes', '7:00 AM - 9:30 AM', '1° nivel', '1'),
(3, 3, 'Martes', '7:00 AM - 9:30 AM', '2° grado', '1'),
(4, 4, 'Miércoles', '7:00 AM - 9:30 AM', '3° año', '2'),
(5, 5, 'Martes', '7:00 AM - 9:30 AM', '1° nivel', '1'),
(6, 6, 'Lunes', '7:00 AM - 9:30 AM', '2° grado', '1'),
(7, 7, 'Lunes', '7:00 AM - 9:30 AM', '2° año', '2'),
(8, 8, 'Lunes', '7:00 AM - 9:30 AM', '1° nivel', '1'),
(9, 9, 'Lunes', '7:00 AM - 9:30 AM', '2° grado', '1'),
(10, 10, 'Martes', '7:00 AM - 9:30 AM', '1° año', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE `cargo` (
  `id` int(100) NOT NULL,
  `codigo_cargo` text NOT NULL,
  `nombre_cargo` text NOT NULL,
  `descripcion_cargo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grado y seccion`
--

CREATE TABLE `grado y seccion` (
  `id` int(100) NOT NULL,
  `codigo_grado_seccion` text NOT NULL,
  `nombre_grado_seccion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id` int(11) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `materia_id` int(11) NOT NULL,
  `tipo` enum('parcial','tiempo_completo') NOT NULL,
  `total_horas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `nivel` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`id`, `nombre`, `nivel`) VALUES
(1, 'Ingles Preescolar', 1),
(2, 'Educación Física Preescolar', 1),
(3, 'Matemática Preescolar', 1),
(4, 'Manos a la Siembra', 1),
(5, 'Comunicación y Representación', 1),
(6, 'Matemáticas Primaria', 2),
(7, 'Lengua Primaria', 2),
(8, 'Ciencias Naturales', 2),
(9, 'Ciencias Sociales', 2),
(10, 'Educación Física Primaria', 2),
(11, 'Ingles Primaria', 2),
(12, 'Física', 3),
(13, 'Química', 3),
(14, 'Biología', 3),
(15, 'Formación para la Soberania', 3),
(16, 'GHC', 3),
(17, 'Educación Física Media General', 3),
(18, 'Inglés Media General', 3),
(19, 'Matemática Media General', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id_personal` int(11) NOT NULL,
  `cedula_personal` varchar(50) NOT NULL,
  `fecha_permiso` date NOT NULL,
  `Tipo_reposo` int(50) NOT NULL,
  `Descripcion` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos_modulos`
--

CREATE TABLE `permisos_modulos` (
  `id` int(11) NOT NULL,
  `nivel_usuario` varchar(50) NOT NULL,
  `modulo` varchar(100) NOT NULL,
  `permitido` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `permisos_modulos`
--

INSERT INTO `permisos_modulos` (`id`, `nivel_usuario`, `modulo`, `permitido`) VALUES
(1, 'Administrador', 'personal.php', 1),
(2, 'Administrador', 'asistencia.php', 1),
(3, 'Administrador', 'horarios.php', 1),
(4, 'Administrador', 'Reportes.php', 1),
(5, 'Administrador', 'Permisos.php', 1),
(6, 'Administrador', 'ayuda.php', 1),
(7, 'Administrador', 'mantenibilidad.php', 1),
(8, 'Secretaria', 'MODULOS_REPORTES.php', 1),
(9, 'Secretaria', 'formato.php', 1),
(10, 'RRHH', 'PERSONAL.php', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id_personal` int(11) NOT NULL,
  `nombre_personal` varchar(255) NOT NULL,
  `apellido_personal` varchar(255) NOT NULL,
  `cedula_personal` varchar(50) NOT NULL,
  `titulo_personal` varchar(255) NOT NULL,
  `correo_personal` varchar(255) NOT NULL,
  `nacimiento_personal` date NOT NULL,
  `ingreso_personal` date NOT NULL,
  `cargo_personal` varchar(255) NOT NULL,
  `huella_dactilar` mediumblob NOT NULL,
  `nacionalidad_personal` varchar(100) DEFAULT NULL,
  `tipo` enum('Parcial','Tiempo Completo') NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id_personal`, `nombre_personal`, `apellido_personal`, `cedula_personal`, `titulo_personal`, `correo_personal`, `nacimiento_personal`, `ingreso_personal`, `cargo_personal`, `huella_dactilar`, `nacionalidad_personal`, `tipo`, `activo`) VALUES
(40, 'Reina', 'De Biel', 'V-3896345', '', 'Debiel08@gmail.com', '1956-03-14', '2010-05-02', 'Director', '', NULL, 'Parcial', 1),
(41, 'Manuel', 'Biel', 'V-7220772', 'licenciado', 'bielmanu@gmail.com', '1962-03-18', '2000-05-02', 'Director', '', NULL, 'Parcial', 1),
(42, 'Anile', 'Biel', 'V-17246672', 'licenciado', 'bielani@gmail.com', '1984-09-29', '2002-04-12', 'Contador', '', NULL, 'Parcial', 0),
(43, 'Naydelis', 'Biel', 'V-18552119', 'licenciado', 'naybiel@gmail.com', '1986-09-28', '2005-04-06', 'RRHH', '', NULL, 'Parcial', 1),
(44, 'Elides', 'Murillo', 'V-10361498', 'licenciado', 'munoz@gmail.com', '1970-11-13', '2000-04-02', 'Administradora', '', NULL, 'Parcial', 1),
(45, 'Carmen', 'Delgado', 'V-8576270', 'licenciado', 'carmen@gmail.com', '1960-03-23', '2020-09-02', 'Director', '', NULL, 'Parcial', 1),
(46, 'Marisela', 'Bolivar', 'V-4368163', 'licenciado', 'marise@gmail.com', '1955-11-18', '2022-02-12', 'Coordinadora', '', NULL, 'Parcial', 1),
(47, 'Gloria', 'Camacho', 'V-7151988', 'licenciado', 'gloricama@gmail.com', '1960-07-27', '2019-02-22', 'Asistente administrativo', '', NULL, 'Parcial', 1),
(48, 'Marlene', 'Camacho', 'V-7165420', 'licenciado', 'narle67@gmail.com', '1962-04-23', '2020-05-22', 'Aux.Inicial', '', NULL, 'Parcial', 1),
(49, 'Judiangel', 'Bello', 'V-20066074', '', 'judianhel@gmail.com', '1984-04-30', '2018-04-26', 'Maestra', '', NULL, 'Parcial', 1),
(50, 'Yeilin', 'Cruz', 'V-18367569', 'tsu', 'yeuz1212@gmail.com', '1992-02-25', '2023-09-21', 'Maestra', '', NULL, 'Parcial', 1),
(51, 'Aura', 'Arcia', 'V-8815415', 'licenciado', 'aurimi@gmail.com', '1967-02-21', '2022-05-25', 'Maestra', '', NULL, 'Parcial', 0),
(52, 'Luddelis', 'Vazquez', 'V-15600408', 'licenciado', 'ludehy@gmail.com', '1983-05-11', '2021-03-26', 'Maestra', '', NULL, 'Parcial', 1),
(53, 'Hilda', 'Aponte', 'V-16012813', 'licenciado', 'hildaaponte@gmail.com', '1981-10-28', '2020-06-25', 'Maestra', '', NULL, 'Parcial', 1),
(54, 'Carmen', 'Perdomo', 'V-3936259', 'licenciado', 'carmencita@gmail.com', '1952-11-20', '2020-01-05', 'Maestro', '', NULL, 'Parcial', 1),
(55, 'Enedina', 'Colmenares', 'V-6152787', 'licenciado', 'endina@gmail.com', '1964-04-02', '2022-02-15', 'Maestra', '', NULL, 'Parcial', 1),
(56, 'Rosa', 'Cordovez', 'V-8588854', 'bachiller', 'rosicor0@gmail.com', '1965-02-03', '2023-05-26', 'Obrero', '', NULL, 'Parcial', 1),
(57, 'Emma', 'Noguera', 'V-8581945', 'bachiller', 'emmasa@gmail.com', '1960-06-17', '2015-02-05', 'Obrero', '', NULL, 'Parcial', 1),
(58, 'Alberto', 'Colorado', 'V-12119997', 'bachiller', 'albert@gmail.com', '1969-06-07', '2024-05-26', 'Administradora', '', NULL, 'Parcial', 0),
(59, 'Jorge', 'Dente', 'V-11180843', 'bachiller', 'dnetal@gmail.com', '1973-02-03', '2019-02-28', 'Mensajero', '', NULL, 'Parcial', 1),
(60, 'Maribel', 'Valera', 'V-13700623', 'tsu', 'maribel@gmail.com', '1976-11-22', '2020-04-20', 'Secretaria', '', NULL, 'Parcial', 1),
(61, 'Eliana', 'Calcurian', 'V-12810130', '', 'eli2345@gmail.com', '1976-05-12', '2020-08-29', 'Secretaria', '', NULL, 'Parcial', 1),
(62, 'Mirna', 'Maestre', 'V-8817274', 'licenciado', 'mirne04@gmail.com', '1967-02-19', '2020-04-10', 'Coordinadora', '', NULL, 'Parcial', 1),
(63, 'Ana', 'Ricaurte', 'V-10358492', 'licenciado', 'jkr1006@gmail.com', '1969-07-26', '2022-07-08', 'Prof.Matematica/fisica', '', NULL, 'Parcial', 0),
(64, 'Esequiel', 'Henriques', 'V-10355279', 'licenciado', 'esequie98@gmail.com', '1970-11-20', '2020-02-22', 'Prof.Sociales', '', NULL, 'Parcial', 1),
(65, 'Yolimer', 'Aponte', 'V-10969805', 'licenciado', 'yolimar5564@gmail.com', '1985-09-22', '2022-04-02', 'Prof.Biologia', '', NULL, 'Parcial', 1),
(66, 'Neyvis', 'Arteaga', 'V-15055765', 'licenciado', 'neyarteaga@gmail.com', '1981-10-11', '2022-03-20', 'Prof.Ingles', '', NULL, 'Parcial', 1),
(67, 'Samuel', 'Diaz', 'V-27630320', 'licenciado', 'samudiaz8@gmail.com', '1997-03-28', '2022-05-22', 'Prof.Quimica', '', NULL, 'Parcial', 1),
(68, 'Maria', 'Gil', 'V-4115075', 'licenciado', 'maria152@gmail.com', '1952-04-10', '2019-03-22', 'Castellano y Proyecto', '', NULL, 'Parcial', 1),
(73, 'Marisela', 'Gil', 'V-30577365', 'bachiller', 'marise123@gmail.com', '2000-12-12', '2025-01-12', 'Maestra', '', NULL, 'Parcial', 1),
(87, 'Karla', 'Diaz', 'V-3150071', 'ingeniero', 'karlaad142@gmail.com', '2000-03-02', '2024-06-22', 'Contador', 0x4269626c696f74656361204a4e49206361726761646120636f7272656374616d656e74652e, 'V', 'Parcial', 1),
(88, 'Maria', 'Diaz', 'V-31500713', 'ingeniero', 'karlaad142@gmail.com', '2000-03-02', '2024-04-03', 'Contador', '', 'V', 'Parcial', 1),
(89, 'Maria', 'Diaz', 'V-3150071', 'ingeniero', 'karlaad142@gmail.com', '2000-03-02', '2024-02-03', 'Contador', 0x4269626c696f74656361204a4e49206361726761646120636f7272656374616d656e74652e, 'V', 'Parcial', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `nivel_usuario` varchar(50) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `token_recuperacion` varchar(64) DEFAULT NULL,
  `expiracion_token` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `nivel_usuario`, `correo`, `contrasena`, `token_recuperacion`, `expiracion_token`) VALUES
(23, 'Alexandra', 'Secretaria', 'xandrapini@gmail.com', '$2y$10$OJSxUNI3bMaNpJDWJ6KvU./6KFnFkV9xcQsw7TNSxZUl7ysmw9S9K', NULL, NULL),
(24, 'Marianny', 'RRHH', 'marian@gmail.com', '$2y$10$OJSxUNI3bMaNpJDWJ6KvU./6KFnFkV9xcQsw7TNSxZUl7ysmw9S9K', NULL, NULL),
(25, 'kil', 'administrador', 'karlaad142@gmail.com', '$2y$10$OJSxUNI3bMaNpJDWJ6KvU./6KFnFkV9xcQsw7TNSxZUl7ysmw9S9K', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_roles`
--

CREATE TABLE `usuario_roles` (
  `usuario_id` int(11) DEFAULT NULL,
  `rol_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`id_personal`),
  ADD UNIQUE KEY `cedula_personal` (`cedula_personal`,`fecha_asistencia`,`hora_entrada`);

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bloques_completo`
--
ALTER TABLE `bloques_completo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bloques_parcial`
--
ALTER TABLE `bloques_parcial`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`codigo_cargo`(50));

--
-- Indices de la tabla `grado y seccion`
--
ALTER TABLE `grado y seccion`
  ADD PRIMARY KEY (`codigo_grado_seccion`(50));

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id_personal`);

--
-- Indices de la tabla `permisos_modulos`
--
ALTER TABLE `permisos_modulos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id_personal`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario_roles`
--
ALTER TABLE `usuario_roles`
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `rol_id` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `id_personal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT de la tabla `bloques_completo`
--
ALTER TABLE `bloques_completo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bloques_parcial`
--
ALTER TABLE `bloques_parcial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `materias`
--
ALTER TABLE `materias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id_personal` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permisos_modulos`
--
ALTER TABLE `permisos_modulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id_personal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `usuario_roles`
--
ALTER TABLE `usuario_roles`
  ADD CONSTRAINT `usuario_roles_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
