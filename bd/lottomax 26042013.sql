-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-04-2013 a las 15:56:05
-- Versión del servidor: 5.5.10
-- Versión de PHP: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `lottomax`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cupo_especial`
--

CREATE TABLE IF NOT EXISTS `cupo_especial` (
  `id_cupo_especial` int(11) NOT NULL AUTO_INCREMENT,
  `numero` int(11) NOT NULL,
  `id_sorteo` int(11) NOT NULL DEFAULT '0',
  `monto_cupo` int(11) NOT NULL COMMENT '0=Numero Bloqueado o Agotado',
  `id_tipo_jugada` int(11) NOT NULL,
  `id_zodiacal` int(11) NOT NULL DEFAULT '0',
  `fecha_desde` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `fecha_hasta` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_cupo_especial`),
  KEY `FK_id_sorteo` (`id_sorteo`),
  KEY `id_tipo_jugada` (`id_tipo_jugada`),
  KEY `id_zodiacal` (`id_zodiacal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=24 ;

--
-- Volcar la base de datos para la tabla `cupo_especial`
--

INSERT INTO `cupo_especial` (`id_cupo_especial`, `numero`, `id_sorteo`, `monto_cupo`, `id_tipo_jugada`, `id_zodiacal`, `fecha_desde`, `fecha_hasta`) VALUES
(17, 111, 2, 100, 1, 0, '2013-03-03 23:30:00', '2013-03-23 23:30:00'),
(19, 11, 2, 10, 2, 0, '2013-03-03 23:30:00', '2013-03-23 23:30:00'),
(20, 22, 6, 20, 4, 9, '2013-03-03 23:30:00', '2013-03-23 23:30:00'),
(21, 222, 6, 200, 3, 9, '2013-03-03 23:30:00', '2013-03-23 23:30:00'),
(22, 125, 4, 20, 1, 0, '2013-04-11 23:30:00', '2013-04-29 23:30:00'),
(23, 789, 5, 155, 1, 0, '2013-04-28 23:30:00', '2013-04-29 23:30:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cupo_general`
--

CREATE TABLE IF NOT EXISTS `cupo_general` (
  `id_cupo_general` int(11) NOT NULL AUTO_INCREMENT,
  `monto_cupo` int(11) NOT NULL,
  `id_tipo_jugada` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_cupo_general`),
  KEY `FK_id_tipo_jugadas` (`id_tipo_jugada`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `cupo_general`
--

INSERT INTO `cupo_general` (`id_cupo_general`, `monto_cupo`, `id_tipo_jugada`) VALUES
(1, 600, 1),
(2, 100, 2),
(3, 500, 3),
(4, 80, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ticket`
--

CREATE TABLE IF NOT EXISTS `detalle_ticket` (
  `id_detalle_ticket` int(11) NOT NULL AUTO_INCREMENT,
  `id_ticket` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `numero` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `id_sorteo` int(11) NOT NULL,
  `hora_sorteo` time NOT NULL,
  `id_zodiacal` int(11) NOT NULL,
  `id_tipo_jugada` int(11) NOT NULL DEFAULT '0',
  `monto` decimal(10,2) NOT NULL,
  KEY `id_detalle_ticket` (`id_detalle_ticket`),
  KEY `FK_id_ticket` (`id_ticket`),
  KEY `FK_id_tipo_jugada` (`id_tipo_jugada`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Detalle ticket' AUTO_INCREMENT=40 ;

--
-- Volcar la base de datos para la tabla `detalle_ticket`
--

INSERT INTO `detalle_ticket` (`id_detalle_ticket`, `id_ticket`, `numero`, `id_sorteo`, `hora_sorteo`, `id_zodiacal`, `id_tipo_jugada`, `monto`) VALUES
(1, '20130329120001', '547', 6, '13:00:00', 7, 3, 100.00),
(2, '20130329120001', '547', 6, '13:00:00', 6, 3, 100.00),
(3, '20130329120001', '547', 6, '13:00:00', 5, 3, 100.00),
(4, '20130329120001', '547', 6, '13:00:00', 4, 3, 100.00),
(5, '20130329120001', '547', 6, '13:00:00', 3, 3, 100.00),
(6, '20130329120001', '547', 6, '13:00:00', 2, 3, 100.00),
(7, '20130329120001', '547', 1, '18:00:00', 0, 1, 100.00),
(8, '20130329120001', '547', 3, '16:30:00', 0, 1, 100.00),
(9, '20130329120001', '547', 2, '13:00:00', 0, 1, 100.00),
(10, '20130329120001', '547', 7, '12:00:00', 0, 1, 100.00),
(11, '20130329120001', '547', 5, '12:00:00', 0, 1, 100.00),
(12, '20130329120001', '547', 4, '12:00:00', 0, 1, 100.00),
(13, '20130329120001', '256', 6, '13:00:00', 7, 3, 10.00),
(14, '20130329120001', '256', 6, '13:00:00', 6, 3, 10.00),
(15, '20130329120001', '256', 6, '13:00:00', 5, 3, 10.00),
(16, '20130329120001', '256', 6, '13:00:00', 4, 3, 10.00),
(17, '20130329120001', '256', 6, '13:00:00', 3, 3, 10.00),
(18, '20130329120001', '256', 6, '13:00:00', 2, 3, 10.00),
(19, '20130329120001', '256', 1, '18:00:00', 0, 1, 10.00),
(20, '20130329120001', '256', 3, '16:30:00', 0, 1, 10.00),
(21, '20130329120001', '256', 2, '13:00:00', 0, 1, 10.00),
(22, '20130329120001', '256', 7, '12:00:00', 0, 1, 10.00),
(23, '20130329120001', '256', 5, '12:00:00', 0, 1, 10.00),
(24, '20130329120001', '256', 4, '12:00:00', 0, 1, 10.00),
(25, '20130329120002', '547', 6, '13:00:00', 6, 3, 50.00),
(26, '20130329120002', '547', 6, '13:00:00', 5, 3, 50.00),
(27, '20130329120002', '547', 6, '13:00:00', 4, 3, 50.00),
(28, '20130329120002', '547', 6, '13:00:00', 3, 3, 50.00),
(29, '20130329120002', '547', 6, '13:00:00', 2, 3, 50.00),
(30, '20130329120002', '547', 1, '18:00:00', 0, 1, 50.00),
(31, '20130329120002', '547', 3, '16:30:00', 0, 1, 50.00),
(32, '20130329120002', '547', 2, '13:00:00', 0, 1, 50.00),
(33, '20130329120002', '547', 7, '12:00:00', 0, 1, 50.00),
(34, '20130329120002', '547', 5, '12:00:00', 0, 1, 50.00),
(35, '20130329120002', '547', 4, '12:00:00', 0, 1, 50.00),
(36, '20130329120003', '123', 4, '12:00:00', 0, 1, 600.00),
(37, '20130329120004', '658', 3, '16:30:00', 0, 1, 20.00),
(38, '20130329120004', '254', 3, '16:30:00', 0, 1, 20.00),
(39, '20130329120004', '124', 3, '16:30:00', 0, 1, 20.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impresora_taquillas`
--

CREATE TABLE IF NOT EXISTS `impresora_taquillas` (
  `id_impresora_taquillas` int(11) NOT NULL AUTO_INCREMENT,
  `id_taquilla` int(11) NOT NULL,
  `nombre_vendedor_ticket` int(11) NOT NULL,
  `cortar_ticket` int(11) NOT NULL,
  `lineas_saltar_antes` int(11) NOT NULL,
  `lineas_saltar_despues` int(11) NOT NULL,
  `ver_numeros_incompletos` int(11) NOT NULL,
  `ver_numeros_agotados` int(11) NOT NULL,
  PRIMARY KEY (`id_impresora_taquillas`),
  UNIQUE KEY `id_taquilla` (`id_taquilla`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Configuracion de Impresora' AUTO_INCREMENT=10 ;

--
-- Volcar la base de datos para la tabla `impresora_taquillas`
--

INSERT INTO `impresora_taquillas` (`id_impresora_taquillas`, `id_taquilla`, `nombre_vendedor_ticket`, `cortar_ticket`, `lineas_saltar_antes`, `lineas_saltar_despues`, `ver_numeros_incompletos`, `ver_numeros_agotados`) VALUES
(2, 2, 1, 0, 0, 7, 1, 1),
(3, 1, 1, 0, 0, 7, 1, 1),
(9, 3, 1, 0, 0, 7, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incompletos_agotados`
--

CREATE TABLE IF NOT EXISTS `incompletos_agotados` (
  `id_incompletos_agotados` int(11) NOT NULL AUTO_INCREMENT,
  `id_ticket` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `numero` int(11) NOT NULL,
  `id_sorteo` int(11) NOT NULL,
  `id_tipo_jugada` int(11) NOT NULL,
  `id_zodiacal` int(11) NOT NULL,
  `monto_restante` decimal(10,2) NOT NULL,
  `incompleto` int(11) NOT NULL COMMENT '0=completo, 1=Incompleto, 2=Agotado',
  PRIMARY KEY (`id_incompletos_agotados`),
  KEY `id_sorteo` (`id_sorteo`),
  KEY `id_tipo_jugada` (`id_tipo_jugada`),
  KEY `id_zodiacal` (`id_zodiacal`),
  KEY `FK_id_tickets` (`id_ticket`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='guarda numeros incompletos y agotados' AUTO_INCREMENT=20 ;

--
-- Volcar la base de datos para la tabla `incompletos_agotados`
--

INSERT INTO `incompletos_agotados` (`id_incompletos_agotados`, `id_ticket`, `fecha`, `numero`, `id_sorteo`, `id_tipo_jugada`, `id_zodiacal`, `monto_restante`, `incompleto`) VALUES
(16, '20130329120003', '2013-03-29 12:03:00', 123, 4, 1, 0, 250.00, 1),
(17, '20130329120003', '0000-00-00 00:00:00', 333, 4, 1, 0, 25.00, 2),
(18, '20130329120003', '0000-00-00 00:00:00', 47, 3, 2, 0, 20.00, 1),
(19, '20130329120003', '0000-00-00 00:00:00', 156, 7, 3, 4, 588.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `loterias`
--

CREATE TABLE IF NOT EXISTS `loterias` (
  `id_loteria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_loteria` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL COMMENT '0=Inactivo, 1=Activo',
  PRIMARY KEY (`id_loteria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Listados de Loterias' AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `loterias`
--

INSERT INTO `loterias` (`id_loteria`, `nombre_loteria`, `status`) VALUES
(1, 'CHANCE', 1),
(2, 'TRIPLE TACHIRA', 1),
(3, 'CHANCE ASTRAL', 1),
(4, 'TRIPLE ZAMORANO', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `numeros_jugados`
--

CREATE TABLE IF NOT EXISTS `numeros_jugados` (
  `id_numero_jugados` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `numero` int(11) NOT NULL,
  `id_sorteo` int(11) NOT NULL,
  `id_tipo_jugada` int(11) NOT NULL,
  `id_zodiacal` int(11) NOT NULL,
  `monto_restante` int(11) NOT NULL,
  PRIMARY KEY (`id_numero_jugados`),
  KEY `id_sorteo` (`id_sorteo`),
  KEY `id_tipo_jugada` (`id_tipo_jugada`),
  KEY `id_zodiacal` (`id_zodiacal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=100 ;

--
-- Volcar la base de datos para la tabla `numeros_jugados`
--

INSERT INTO `numeros_jugados` (`id_numero_jugados`, `fecha`, `numero`, `id_sorteo`, `id_tipo_jugada`, `id_zodiacal`, `monto_restante`) VALUES
(3, '2013-03-04', 222, 6, 3, 9, 0),
(4, '2013-03-04', 22, 6, 4, 9, 10),
(10, '2013-03-14', 11, 2, 2, 0, 0),
(17, '2013-03-14', 111, 2, 1, 0, 0),
(18, '2013-03-14', 313, 2, 1, 0, 570),
(19, '2013-03-14', 232, 2, 1, 0, 0),
(20, '2013-03-14', 11, 4, 2, 0, 88),
(21, '2013-03-14', 222, 2, 1, 0, 588),
(22, '2013-03-14', 233, 2, 1, 0, 588),
(23, '2013-03-14', 313, 6, 3, 9, 0),
(24, '2013-03-14', 22, 2, 2, 0, 88),
(25, '2013-03-14', 22, 4, 2, 0, 88),
(26, '2013-03-14', 768, 3, 1, 0, 597),
(27, '2013-03-14', 768, 7, 1, 0, 597),
(28, '2013-03-14', 31, 5, 2, 0, 79),
(29, '2013-03-14', 31, 4, 2, 0, 79),
(30, '2013-03-14', 321, 5, 1, 0, 580),
(31, '2013-03-14', 312, 5, 1, 0, 580),
(32, '2013-03-14', 231, 5, 1, 0, 580),
(33, '2013-03-14', 213, 5, 1, 0, 580),
(34, '2013-03-14', 132, 5, 1, 0, 580),
(35, '2013-03-14', 123, 5, 1, 0, 580),
(36, '2013-03-29', 884, 6, 3, 4, 490),
(37, '2013-03-29', 884, 6, 3, 3, 490),
(38, '2013-03-29', 884, 6, 3, 2, 490),
(39, '2013-03-29', 884, 6, 3, 1, 490),
(40, '2013-03-29', 111, 6, 3, 11, 490),
(41, '2013-03-29', 111, 6, 3, 10, 490),
(42, '2013-03-29', 111, 6, 3, 9, 490),
(43, '2013-03-29', 111, 6, 3, 8, 490),
(44, '2013-03-29', 111, 6, 3, 7, 490),
(45, '2013-03-29', 111, 6, 3, 6, 490),
(46, '2013-03-29', 111, 6, 3, 5, 490),
(47, '2013-03-29', 111, 6, 3, 4, 490),
(48, '2013-03-29', 111, 6, 3, 3, 490),
(49, '2013-03-29', 111, 6, 3, 2, 490),
(50, '2013-03-29', 111, 6, 3, 1, 490),
(51, '2013-03-29', 555, 3, 1, 0, 590),
(52, '2013-03-29', 555, 2, 1, 0, 590),
(53, '2013-03-29', 555, 7, 1, 0, 590),
(54, '2013-03-29', 999, 3, 1, 0, 590),
(55, '2013-03-29', 999, 2, 1, 0, 590),
(56, '2013-03-29', 999, 7, 1, 0, 590),
(57, '2013-03-29', 777, 3, 1, 0, 590),
(58, '2013-03-29', 777, 2, 1, 0, 590),
(59, '2013-03-29', 777, 7, 1, 0, 590),
(60, '2013-03-29', 888, 3, 1, 0, 590),
(61, '2013-03-29', 888, 2, 1, 0, 590),
(62, '2013-03-29', 888, 7, 1, 0, 590),
(63, '2013-03-29', 157, 3, 1, 0, 590),
(64, '2013-03-29', 157, 2, 1, 0, 590),
(65, '2013-03-29', 157, 7, 1, 0, 590),
(66, '2013-03-29', 369, 5, 1, 0, 590),
(67, '2013-03-29', 369, 4, 1, 0, 590),
(68, '2013-03-29', 255, 5, 1, 0, 590),
(69, '2013-03-29', 255, 4, 1, 0, 590),
(70, '2013-03-29', 214, 5, 1, 0, 590),
(71, '2013-03-29', 214, 4, 1, 0, 590),
(72, '2013-03-29', 547, 6, 3, 7, 400),
(73, '2013-03-29', 547, 6, 3, 6, 350),
(74, '2013-03-29', 547, 6, 3, 5, 350),
(75, '2013-03-29', 547, 6, 3, 4, 350),
(76, '2013-03-29', 547, 6, 3, 3, 350),
(77, '2013-03-29', 547, 6, 3, 2, 350),
(78, '2013-03-29', 547, 1, 1, 0, 450),
(79, '2013-03-29', 547, 3, 1, 0, 450),
(80, '2013-03-29', 547, 2, 1, 0, 450),
(81, '2013-03-29', 547, 7, 1, 0, 450),
(82, '2013-03-29', 547, 5, 1, 0, 450),
(83, '2013-03-29', 547, 4, 1, 0, 450),
(84, '2013-03-29', 256, 6, 3, 7, 490),
(85, '2013-03-29', 256, 6, 3, 6, 490),
(86, '2013-03-29', 256, 6, 3, 5, 490),
(87, '2013-03-29', 256, 6, 3, 4, 490),
(88, '2013-03-29', 256, 6, 3, 3, 490),
(89, '2013-03-29', 256, 6, 3, 2, 490),
(90, '2013-03-29', 256, 1, 1, 0, 590),
(91, '2013-03-29', 256, 3, 1, 0, 590),
(92, '2013-03-29', 256, 2, 1, 0, 590),
(93, '2013-03-29', 256, 7, 1, 0, 590),
(94, '2013-03-29', 256, 5, 1, 0, 590),
(95, '2013-03-29', 256, 4, 1, 0, 590),
(96, '2013-03-29', 123, 4, 1, 0, 0),
(97, '2013-03-29', 658, 3, 1, 0, 580),
(98, '2013-03-29', 254, 3, 1, 0, 580),
(99, '2013-03-29', 124, 3, 1, 0, 580);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parametros`
--

CREATE TABLE IF NOT EXISTS `parametros` (
  `id_parametros` int(11) NOT NULL AUTO_INCREMENT,
  `id_agencia` int(11) NOT NULL,
  `nombre_agencia` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tiempo_cierre_sorteos` int(11) NOT NULL COMMENT 'Expresado en minutos',
  `tiempo_anulacion_ticket` int(11) NOT NULL COMMENT 'Expresado en minutos',
  `tiempo_vigencia_ticket` int(11) NOT NULL COMMENT 'Expresado en dias',
  UNIQUE KEY `id_parametros` (`id_parametros`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `parametros`
--

INSERT INTO `parametros` (`id_parametros`, `id_agencia`, `nombre_agencia`, `tiempo_cierre_sorteos`, `tiempo_anulacion_ticket`, `tiempo_vigencia_ticket`) VALUES
(1, 1, 'La Tostadita', 10, 2, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE IF NOT EXISTS `perfil` (
  `id_perfil` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_perfil` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_perfil`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `perfil`
--

INSERT INTO `perfil` (`id_perfil`, `nombre_perfil`) VALUES
(1, 'Administrador'),
(2, 'Banquero'),
(3, 'Intermediario'),
(4, 'Vendedor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relacion_pagos`
--

CREATE TABLE IF NOT EXISTS `relacion_pagos` (
  `id_relacion_pagos` int(11) NOT NULL AUTO_INCREMENT,
  `monto` int(11) NOT NULL,
  `id_tipo_jugada` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0=Inactivo, 1=Activo',
  PRIMARY KEY (`id_relacion_pagos`),
  KEY `id_tipo_jugada` (`id_tipo_jugada`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Volcar la base de datos para la tabla `relacion_pagos`
--

INSERT INTO `relacion_pagos` (`id_relacion_pagos`, `monto`, `id_tipo_jugada`, `status`) VALUES
(1, 800, 1, 1),
(2, 50, 2, 1),
(3, 6000, 3, 1),
(4, 120, 4, 1),
(5, 30, 5, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resultados`
--

CREATE TABLE IF NOT EXISTS `resultados` (
  `id_resultados` int(11) NOT NULL AUTO_INCREMENT,
  `id_sorteo` int(11) NOT NULL,
  `zodiacal` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  PRIMARY KEY (`id_resultados`),
  KEY `id_sorteo` (`id_sorteo`),
  KEY `zodiacal` (`zodiacal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Volcar la base de datos para la tabla `resultados`
--

INSERT INTO `resultados` (`id_resultados`, `id_sorteo`, `zodiacal`, `numero`, `fecha_hora`) VALUES
(1, 1, 0, 568, '2013-04-16 00:00:00'),
(2, 2, 0, 456, '2013-04-22 00:00:00'),
(3, 3, 0, 345, '2013-04-22 00:00:00'),
(6, 6, 9, 313, '2013-04-22 00:00:00'),
(7, 4, 0, 344, '2013-04-22 00:00:00'),
(8, 5, 0, 567, '2013-04-22 00:00:00'),
(9, 2, 0, 444, '2013-04-16 00:00:00'),
(10, 1, 0, 999, '2013-04-22 00:00:00'),
(11, 7, 0, 333, '2013-04-22 00:00:00'),
(12, 3, 0, 321, '2013-04-26 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sorteos`
--

CREATE TABLE IF NOT EXISTS `sorteos` (
  `id_sorteo` int(11) NOT NULL AUTO_INCREMENT,
  `id_loteria` int(11) NOT NULL DEFAULT '0',
  `nombre_sorteo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `hora_sorteo` time NOT NULL,
  `zodiacal` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0=Inactivo, 1=Activo',
  UNIQUE KEY `id_sorrteo` (`id_sorteo`),
  KEY `FK_zodiacal` (`zodiacal`),
  KEY `FK_id_loteria` (`id_loteria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Volcar la base de datos para la tabla `sorteos`
--

INSERT INTO `sorteos` (`id_sorteo`, `id_loteria`, `nombre_sorteo`, `hora_sorteo`, `zodiacal`, `status`) VALUES
(1, 1, 'CHANCE A 1PM', '18:00:00', 0, 1),
(2, 1, 'CHANCE B 1PM', '13:00:00', 0, 1),
(3, 1, 'CHANCE A 4:30PM', '16:30:00', 0, 1),
(4, 2, 'TACHIRA A 12:00', '12:00:00', 0, 1),
(5, 2, 'TACHIRA B 12:00', '12:00:00', 0, 1),
(6, 3, 'CHANCE ASTRAL 1PM', '13:00:00', 1, 1),
(7, 4, 'ZAMORANO 12:00', '12:00:00', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `id_status` int(11) NOT NULL,
  `status_nombre` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcar la base de datos para la tabla `status`
--

INSERT INTO `status` (`id_status`, `status_nombre`) VALUES
(0, 'Inactivo'),
(1, 'Activo'),
(2, 'Modificado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `taquillas`
--

CREATE TABLE IF NOT EXISTS `taquillas` (
  `id_taquilla` int(11) NOT NULL AUTO_INCREMENT,
  `numero_taquilla` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL COMMENT ' 0=Inactivo, 1=Activo',
  PRIMARY KEY (`id_taquilla`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Volcar la base de datos para la tabla `taquillas`
--

INSERT INTO `taquillas` (`id_taquilla`, `numero_taquilla`, `status`) VALUES
(1, '01', 1),
(2, '02', 1),
(3, '3', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket`
--

CREATE TABLE IF NOT EXISTS `ticket` (
  `id_ticket` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `serial` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `taquilla` int(11) NOT NULL,
  `total_ticket` decimal(10,2) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `premiado` int(11) NOT NULL,
  `pagado` int(11) NOT NULL,
  PRIMARY KEY (`id_ticket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ticket';

--
-- Volcar la base de datos para la tabla `ticket`
--

INSERT INTO `ticket` (`id_ticket`, `serial`, `fecha_hora`, `taquilla`, `total_ticket`, `id_usuario`, `premiado`, `pagado`) VALUES
('20130329120001', '9915181552', '2013-03-29 12:03:00', 2, 1320.00, 1, 0, 0),
('20130329120002', '8389311205', '2013-03-29 12:03:00', 2, 550.00, 1, 0, 0),
('20130329120003', '9852868339', '2013-03-29 12:03:00', 1, 600.00, 1, 0, 0),
('20130329120004', '2000194991', '2013-03-29 02:03:00', 2, 60.00, 1, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_transaccional`
--

CREATE TABLE IF NOT EXISTS `ticket_transaccional` (
  `id_ticket_transaccional` int(11) NOT NULL AUTO_INCREMENT,
  `numero` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `id_sorteo` int(11) NOT NULL,
  `id_zodiacal` int(11) NOT NULL,
  `id_tipo_jugada` int(11) NOT NULL,
  `monto_faltante` int(11) NOT NULL,
  `incompleto` int(11) NOT NULL COMMENT '0=completo, 1=Incompleto, 2=Agotado',
  `monto` decimal(10,2) NOT NULL,
  `id_taquilla` int(11) NOT NULL,
  PRIMARY KEY (`id_ticket_transaccional`),
  KEY `id_taquilla` (`id_taquilla`),
  KEY `id_zodiacal` (`id_zodiacal`),
  KEY `id_sorteo` (`id_sorteo`),
  KEY `id_tipo_jugada` (`id_tipo_jugada`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Ticket Transaccional' AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `ticket_transaccional`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_jugadas`
--

CREATE TABLE IF NOT EXISTS `tipo_jugadas` (
  `id_tipo_jugada` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_jugada` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `zodiacal` int(11) NOT NULL COMMENT ' 0=No es Zodiacal, 1=Si es Zodiacal',
  `triple` int(11) NOT NULL COMMENT ' 0=es Terminal, 1=es Triple, 2= aproximacion',
  `status` int(11) NOT NULL COMMENT '0=Inactivo, 1=Activo',
  PRIMARY KEY (`id_tipo_jugada`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Volcar la base de datos para la tabla `tipo_jugadas`
--

INSERT INTO `tipo_jugadas` (`id_tipo_jugada`, `nombre_jugada`, `zodiacal`, `triple`, `status`) VALUES
(1, 'Triple', 0, 1, 1),
(2, 'Terminal', 0, 0, 1),
(3, 'Tripletazo', 1, 1, 1),
(4, 'Terminalazo', 1, 0, 1),
(5, 'Aproximacion', 0, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` int(10) NOT NULL AUTO_INCREMENT,
  `id_perfil` int(10) DEFAULT NULL,
  `nombre_usuario` varchar(80) DEFAULT NULL,
  `email_usuario` varchar(100) DEFAULT NULL,
  `login_usuario` varchar(10) DEFAULT NULL,
  `clave_usuario` varchar(10) DEFAULT NULL,
  `id_status_usuario` int(11) DEFAULT NULL COMMENT '0=Inactivo, 1=Activo',
  PRIMARY KEY (`id_usuario`),
  KEY `FK_id_perfil` (`id_perfil`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcar la base de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `id_perfil`, `nombre_usuario`, `email_usuario`, `login_usuario`, `clave_usuario`, `id_status_usuario`) VALUES
(1, 1, 'Gerzahim Salas', 'rasce88@gmail.com', 'admin', 'admin1', 1),
(3, 3, 'Rasce Salas', 'rasce88@gmail.com', 'rasce88', '123', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_taquillas`
--

CREATE TABLE IF NOT EXISTS `usuarios_taquillas` (
  `id_usuario_taquilla` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_taquilla` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario_taquilla`),
  KEY `id_taquilla` (`id_taquilla`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=65 ;

--
-- Volcar la base de datos para la tabla `usuarios_taquillas`
--

INSERT INTO `usuarios_taquillas` (`id_usuario_taquilla`, `id_usuario`, `id_taquilla`) VALUES
(64, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zodiacal`
--

CREATE TABLE IF NOT EXISTS `zodiacal` (
  `Id_zodiacal` int(11) NOT NULL,
  `nombre_zodiacal` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `pre_zodiacal` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `Id_zodiacal` (`Id_zodiacal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Signo Zodiacal';

--
-- Volcar la base de datos para la tabla `zodiacal`
--

INSERT INTO `zodiacal` (`Id_zodiacal`, `nombre_zodiacal`, `pre_zodiacal`) VALUES
(0, 'No Zodiacal', ''),
(1, 'Acuario', 'Acu'),
(2, 'Aries', 'Ari'),
(3, 'Cancer', 'Can'),
(4, 'Capricornio', 'Cap'),
(5, 'Escorpio', 'Esc'),
(6, 'Geminis', 'Gem'),
(7, 'Leo', 'Leo'),
(8, 'Libra', 'Lib'),
(9, 'Piscis', 'Pis'),
(10, 'Sagitario', 'Sag'),
(11, 'Tauro', 'Tau'),
(12, 'Virgo', 'Vir');

--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `cupo_especial`
--
ALTER TABLE `cupo_especial`
  ADD CONSTRAINT `cupo_especial_ibfk_1` FOREIGN KEY (`id_tipo_jugada`) REFERENCES `tipo_jugadas` (`id_tipo_jugada`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cupo_especial_ibfk_2` FOREIGN KEY (`id_zodiacal`) REFERENCES `zodiacal` (`Id_zodiacal`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_id_sorteo` FOREIGN KEY (`id_sorteo`) REFERENCES `sorteos` (`id_sorteo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cupo_general`
--
ALTER TABLE `cupo_general`
  ADD CONSTRAINT `FK_id_tipo_jugadas` FOREIGN KEY (`id_tipo_jugada`) REFERENCES `tipo_jugadas` (`id_tipo_jugada`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_ticket`
--
ALTER TABLE `detalle_ticket`
  ADD CONSTRAINT `detalle_ticket_ibfk_1` FOREIGN KEY (`id_ticket`) REFERENCES `ticket` (`id_ticket`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_ticket_ibfk_2` FOREIGN KEY (`id_tipo_jugada`) REFERENCES `tipo_jugadas` (`id_tipo_jugada`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `impresora_taquillas`
--
ALTER TABLE `impresora_taquillas`
  ADD CONSTRAINT `impresora_taquillas_ibfk_1` FOREIGN KEY (`id_taquilla`) REFERENCES `taquillas` (`id_taquilla`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `incompletos_agotados`
--
ALTER TABLE `incompletos_agotados`
  ADD CONSTRAINT `incompletos_agotados_ibfk_1` FOREIGN KEY (`id_sorteo`) REFERENCES `sorteos` (`id_sorteo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `incompletos_agotados_ibfk_2` FOREIGN KEY (`id_tipo_jugada`) REFERENCES `tipo_jugadas` (`id_tipo_jugada`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `incompletos_agotados_ibfk_3` FOREIGN KEY (`id_zodiacal`) REFERENCES `zodiacal` (`Id_zodiacal`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `incompletos_agotados_ibfk_4` FOREIGN KEY (`id_ticket`) REFERENCES `ticket` (`id_ticket`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `numeros_jugados`
--
ALTER TABLE `numeros_jugados`
  ADD CONSTRAINT `numeros_jugados_ibfk_1` FOREIGN KEY (`id_sorteo`) REFERENCES `sorteos` (`id_sorteo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `numeros_jugados_ibfk_2` FOREIGN KEY (`id_tipo_jugada`) REFERENCES `tipo_jugadas` (`id_tipo_jugada`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `numeros_jugados_ibfk_3` FOREIGN KEY (`id_zodiacal`) REFERENCES `zodiacal` (`Id_zodiacal`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `relacion_pagos`
--
ALTER TABLE `relacion_pagos`
  ADD CONSTRAINT `relacion_pagos_ibfk_1` FOREIGN KEY (`id_tipo_jugada`) REFERENCES `tipo_jugadas` (`id_tipo_jugada`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `resultados`
--
ALTER TABLE `resultados`
  ADD CONSTRAINT `resultados_ibfk_1` FOREIGN KEY (`id_sorteo`) REFERENCES `sorteos` (`id_sorteo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `resultados_ibfk_2` FOREIGN KEY (`zodiacal`) REFERENCES `zodiacal` (`Id_zodiacal`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `sorteos`
--
ALTER TABLE `sorteos`
  ADD CONSTRAINT `FK_id_loteria` FOREIGN KEY (`id_loteria`) REFERENCES `loterias` (`id_loteria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_zodiacal` FOREIGN KEY (`zodiacal`) REFERENCES `zodiacal` (`Id_zodiacal`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ticket_transaccional`
--
ALTER TABLE `ticket_transaccional`
  ADD CONSTRAINT `ticket_transaccional_ibfk_1` FOREIGN KEY (`id_sorteo`) REFERENCES `sorteos` (`id_sorteo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ticket_transaccional_ibfk_2` FOREIGN KEY (`id_zodiacal`) REFERENCES `zodiacal` (`Id_zodiacal`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ticket_transaccional_ibfk_3` FOREIGN KEY (`id_tipo_jugada`) REFERENCES `tipo_jugadas` (`id_tipo_jugada`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ticket_transaccional_ibfk_4` FOREIGN KEY (`id_taquilla`) REFERENCES `taquillas` (`id_taquilla`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `FK_id_perfil` FOREIGN KEY (`id_perfil`) REFERENCES `perfil` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE;
