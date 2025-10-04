-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-10-2025 a las 01:54:35
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
(1, 36, 'Admin', 'jmrm19722@gmail.com', 'Inicio de sesión', '2025-09-13 18:42:18'),
(2, 36, 'Admin', 'jmrm19722@gmail.com', 'Inicio de sesión', '2025-10-01 23:16:11'),
(3, 36, 'Admin', 'jmrm19722@gmail.com', 'Inicio de sesión', '2025-10-03 07:29:09'),
(4, 36, 'Admin', 'jmrm19722@gmail.com', 'Inicio de sesión', '2025-10-03 16:31:44'),
(5, 36, 'Admin', 'jmrm19722@gmail.com', 'Inicio de sesión', '2025-10-04 14:23:08'),
(6, 36, 'Admin', 'jmrm19722@gmail.com', 'Inicio de sesión', '2025-10-04 14:23:09'),
(7, 36, 'Admin', 'jmrm19722@gmail.com', 'Inicio de sesión', '2025-10-04 14:23:09'),
(8, 36, 'Admin', 'jmrm19722@gmail.com', 'Inicio de sesión', '2025-10-04 17:58:32'),
(9, 36, 'Admin', 'jmrm19722@gmail.com', 'Inicio de sesión', '2025-10-04 17:58:32'),
(10, 36, 'Admin', 'jmrm19722@gmail.com', 'Inicio de sesión', '2025-10-04 17:58:32');

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
  `seccion` varchar(50) NOT NULL,
  `materia_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bloques_completo`
--

INSERT INTO `bloques_completo` (`id`, `horario_id`, `dia`, `bloque_hora`, `nivel`, `seccion`, `materia_id`) VALUES
(1, 2, 'Lunes', '7:00 - 12:00 AM', '1° grado', 'U', 6),
(2, 2, 'Martes', '7:00 - 12:00 AM', '2° grado', 'U', 7),
(3, 2, 'Miércoles', '7:00 - 12:00 AM', '4° grado', 'U', 10),
(4, 2, 'Jueves', '7:00 - 12:00 AM', '3° grado', 'U', 11),
(5, 2, 'Viernes', '7:00 - 12:00 AM', 'Todos', 'U', 9);

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
  `seccion` varchar(50) NOT NULL,
  `materia_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bloques_parcial`
--

INSERT INTO `bloques_parcial` (`id`, `horario_id`, `dia`, `hora`, `nivel`, `seccion`, `materia_id`) VALUES
(1, 1, 'Lunes', '7:00 AM - 9:30 AM', '2° nivel', 'U', 2);

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
  `tipo` enum('parcial','tiempo_completo') NOT NULL,
  `total_horas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `horarios`
--

INSERT INTO `horarios` (`id`, `cedula`, `tipo`, `total_horas`) VALUES
(1, 'V-30091390', 'parcial', 2),
(2, 'V-30091390', 'tiempo_completo', NULL);

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
(1, 'Carmen', 'Delgado', 'V-30091390', '', 'jmrm19722@gmail.com', '2004-01-15', '2024-09-15', 'Directora', '', NULL, 'Parcial', 1);

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
(36, 'Admin', 'administrador', 'jmrm19722@gmail.com', '$2y$10$U1ECopeHcnzXZoQuQM3eKu6u9AB0yi6c8OqCpRfBAzERASqeT2Dga', NULL, NULL);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_completo_materia` (`materia_id`);

--
-- Indices de la tabla `bloques_parcial`
--
ALTER TABLE `bloques_parcial`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_parcial_materia` (`materia_id`);

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
  MODIFY `id_personal` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `bloques_completo`
--
ALTER TABLE `bloques_completo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `bloques_parcial`
--
ALTER TABLE `bloques_parcial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id_personal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bloques_completo`
--
ALTER TABLE `bloques_completo`
  ADD CONSTRAINT `fk_completo_materia` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`);

--
-- Filtros para la tabla `bloques_parcial`
--
ALTER TABLE `bloques_parcial`
  ADD CONSTRAINT `fk_parcial_materia` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`);

--
-- Filtros para la tabla `usuario_roles`
--
ALTER TABLE `usuario_roles`
  ADD CONSTRAINT `usuario_roles_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
