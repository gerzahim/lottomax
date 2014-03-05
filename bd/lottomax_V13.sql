-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 05-03-2014 a las 15:57:39
-- Versión del servidor: 5.5.16
-- Versión de PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


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
  `numero` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
-- Volcado de datos para la tabla `cupo_general`
--

INSERT INTO `cupo_general` (`id_cupo_general`, `monto_cupo`, `id_tipo_jugada`) VALUES
(1, 30, 1),
(2, 50, 2),
(3, 1, 3),
(4, 5, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ticket`
--

CREATE TABLE IF NOT EXISTS `detalle_ticket` (
  `id_detalle_ticket` int(11) NOT NULL AUTO_INCREMENT,
  `id_ticket` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `numero` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `id_sorteo` int(11) NOT NULL,
  `fecha_sorteo` date NOT NULL,
  `id_zodiacal` int(11) NOT NULL,
  `id_tipo_jugada` int(11) NOT NULL DEFAULT '0',
  `monto` decimal(10,2) NOT NULL,
  `premiado` int(11) NOT NULL DEFAULT '0',
  `total_premiado` int(11) NOT NULL DEFAULT '0',
  KEY `id_detalle_ticket` (`id_detalle_ticket`),
  KEY `FK_id_ticket` (`id_ticket`),
  KEY `FK_id_tipo_jugada` (`id_tipo_jugada`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Detalle ticket' AUTO_INCREMENT=1 ;

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
-- Volcado de datos para la tabla `impresora_taquillas`
--

INSERT INTO `impresora_taquillas` (`id_impresora_taquillas`, `id_taquilla`, `nombre_vendedor_ticket`, `cortar_ticket`, `lineas_saltar_antes`, `lineas_saltar_despues`, `ver_numeros_incompletos`, `ver_numeros_agotados`) VALUES
(2, 2, 1, 0, 0, 0, 1, 1),
(3, 1, 1, 0, 0, 0, 1, 1),
(9, 3, 1, 0, 0, 0, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incompletos_agotados`
--

CREATE TABLE IF NOT EXISTS `incompletos_agotados` (
  `id_incompletos_agotados` int(11) NOT NULL AUTO_INCREMENT,
  `id_ticket` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `numero` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `id_sorteo` int(11) NOT NULL,
  `id_tipo_jugada` int(11) NOT NULL,
  `id_zodiacal` int(11) NOT NULL,
  `monto_restante` decimal(10,2) NOT NULL,
  `incompleto` int(11) NOT NULL COMMENT '0=completo, 1=Incompleto, 2=Agotado, 3=Con esta Jugada Se Agota',
  PRIMARY KEY (`id_incompletos_agotados`),
  KEY `id_sorteo` (`id_sorteo`),
  KEY `id_tipo_jugada` (`id_tipo_jugada`),
  KEY `id_zodiacal` (`id_zodiacal`),
  KEY `FK_id_tickets` (`id_ticket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='guarda numeros incompletos y agotados' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `loterias`
--

CREATE TABLE IF NOT EXISTS `loterias` (
  `id_loteria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_loteria` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL COMMENT '0=Inactivo, 1=Activo',
  PRIMARY KEY (`id_loteria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Listados de Loterias' AUTO_INCREMENT=10 ;

--
-- Volcado de datos para la tabla `loterias`
--

INSERT INTO `loterias` (`id_loteria`, `nombre_loteria`, `status`) VALUES
(1, 'ZAMORANO', 0),
(2, 'TACHIRA', 1),
(3, 'ZODIACAL', 1),
(4, 'LEON', 1),
(5, 'CHANCE', 1),
(6, 'ZULIA', 1),
(7, 'TRIPLEMANIA', 1),
(8, 'MULTITRIPLE', 1),
(9, 'TRILLONARIO', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `numeros_jugados`
--

CREATE TABLE IF NOT EXISTS `numeros_jugados` (
  `id_numero_jugados` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `numero` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `id_sorteo` int(11) NOT NULL,
  `id_tipo_jugada` int(11) NOT NULL,
  `id_zodiacal` int(11) NOT NULL,
  `monto_restante` int(11) NOT NULL,
  PRIMARY KEY (`id_numero_jugados`),
  KEY `id_sorteo` (`id_sorteo`),
  KEY `id_tipo_jugada` (`id_tipo_jugada`),
  KEY `id_zodiacal` (`id_zodiacal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
  `aprox_abajo` tinyint(1) NOT NULL DEFAULT '0',
  `aprox_arriba` tinyint(1) NOT NULL DEFAULT '0',
  `comision_agencia` int(11) NOT NULL,
  UNIQUE KEY `id_parametros` (`id_parametros`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `parametros`
--

INSERT INTO `parametros` (`id_parametros`, `id_agencia`, `nombre_agencia`, `tiempo_cierre_sorteos`, `tiempo_anulacion_ticket`, `tiempo_vigencia_ticket`, `aprox_abajo`, `aprox_arriba`, `comision_agencia`) VALUES
(1, 1, 'Makamindres', 10, 5, 3, 1, 1, 22);

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
-- Volcado de datos para la tabla `perfil`
--

INSERT INTO `perfil` (`id_perfil`, `nombre_perfil`) VALUES
(1, 'Administrador'),
(2, 'Banquero'),
(3, 'Intermediario'),
(4, 'Vendedor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prueba`
--

CREATE TABLE IF NOT EXISTS `prueba` (
  `id_prueba` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id_prueba`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='tabla de prueba' AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `prueba`
--

INSERT INTO `prueba` (`id_prueba`, `date`) VALUES
(1, '2014-02-20 14:06:50'),
(2, '2014-02-20 14:19:39'),
(3, '2014-02-20 14:19:53');

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
-- Volcado de datos para la tabla `relacion_pagos`
--

INSERT INTO `relacion_pagos` (`id_relacion_pagos`, `monto`, `id_tipo_jugada`, `status`) VALUES
(1, 800, 1, 1),
(2, 60, 2, 1),
(3, 6000, 3, 1),
(4, 600, 4, 1),
(5, 10, 5, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resultados`
--

CREATE TABLE IF NOT EXISTS `resultados` (
  `id_resultados` int(11) NOT NULL AUTO_INCREMENT,
  `id_sorteo` int(11) NOT NULL,
  `zodiacal` int(11) NOT NULL,
  `numero` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_hora` date NOT NULL,
  `bajado` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_resultados`),
  KEY `id_sorteo` (`id_sorteo`),
  KEY `zodiacal` (`zodiacal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sorteos`
--

CREATE TABLE IF NOT EXISTS `sorteos` (
  `id_sorteo` int(11) NOT NULL AUTO_INCREMENT,
  `id_loteria` int(11) NOT NULL DEFAULT '0',
  `nombre_sorteo` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `hora_sorteo` time NOT NULL,
  `id_turno` int(1) NOT NULL,
  `zodiacal` int(11) NOT NULL,
  `tradicional` int(1) NOT NULL COMMENT '0=No Tradicional, 1= Si Tradicional',
  `status` int(11) NOT NULL COMMENT '0=Inactivo, 1=Activo',
  `tipoc` int(1) NOT NULL,
  UNIQUE KEY `id_sorrteo` (`id_sorteo`),
  KEY `FK_zodiacal` (`zodiacal`),
  KEY `FK_id_loteria` (`id_loteria`),
  KEY `turno_fk` (`id_turno`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=106 ;

--
-- Volcado de datos para la tabla `sorteos`
--

INSERT INTO `sorteos` (`id_sorteo`, `id_loteria`, `nombre_sorteo`, `hora_sorteo`, `id_turno`, `zodiacal`, `tradicional`, `status`, `tipoc`) VALUES
(1, 1, 'ZAMORANO A 12PM', '12:00:00', 1, 0, 0, 0, 0),
(2, 1, 'ZAMORANO C 12PM', '12:00:00', 1, 0, 0, 0, 1),
(3, 1, 'ASTRO ZAMORANO 12PM', '12:00:00', 1, 1, 0, 0, 0),
(4, 2, 'TACHIRA A 12PM', '12:00:00', 1, 0, 1, 1, 0),
(5, 2, 'TACHIRA B 12PM', '12:00:00', 1, 0, 1, 1, 0),
(6, 2, 'TACHIRA C 12PM', '12:00:00', 1, 0, 0, 1, 1),
(7, 2, 'SIGNO TACHIRA 12PM', '12:00:00', 1, 1, 0, 1, 0),
(8, 3, 'ZODIACAL A 12:45PM', '12:45:00', 1, 0, 1, 1, 0),
(9, 3, 'ZODIACAL B 12:45PM', '12:45:00', 1, 0, 1, 1, 0),
(10, 3, 'ZODIACAL SIGNO 12:45PM', '12:45:00', 1, 1, 0, 1, 0),
(11, 4, 'LEON A 12:30PM', '12:30:00', 1, 0, 1, 1, 0),
(12, 4, 'LEON B 12:30PM', '12:30:00', 1, 0, 1, 1, 0),
(13, 4, 'LEON C 12:30PM', '12:30:00', 1, 0, 0, 1, 1),
(14, 4, 'TRIPLETON 12:30PM', '12:30:00', 1, 1, 0, 1, 0),
(15, 6, 'ZULIA A 12:45PM', '12:45:00', 1, 0, 1, 1, 0),
(16, 6, 'ZULIA B 12:45PM', '12:45:00', 1, 0, 1, 1, 0),
(17, 6, 'ZULIA C 12:45PM', '12:45:00', 1, 0, 0, 1, 1),
(18, 6, 'ZODIACO DEL ZULIA 12:45PM', '12:45:00', 1, 1, 0, 1, 0),
(19, 5, 'CHANCE A 1PM', '13:00:00', 1, 0, 1, 1, 0),
(20, 5, 'CHANCE B 1PM', '13:00:00', 1, 0, 1, 1, 0),
(21, 5, 'CHANCE C 1PM', '13:00:00', 1, 0, 0, 1, 1),
(22, 5, 'ASTRAL 1PM', '13:00:00', 1, 1, 0, 1, 0),
(23, 1, 'ZAMORANO A 4PM', '16:00:00', 2, 0, 0, 0, 0),
(24, 1, 'ZAMORANO C 4PM', '16:00:00', 2, 0, 0, 0, 1),
(25, 1, 'ASTRO ZAMORANO 4PM', '16:00:00', 2, 1, 0, 0, 0),
(26, 4, 'LEON A 4:30PM', '16:30:00', 2, 0, 1, 1, 0),
(27, 4, 'LEON B 4:30PM', '16:30:00', 2, 0, 1, 1, 0),
(28, 4, 'LEON C 4:30PM', '16:30:00', 2, 0, 0, 1, 1),
(29, 4, 'TRIPLETON 4:30PM', '16:30:00', 2, 1, 0, 1, 0),
(30, 5, 'CHANCE A 4:30PM', '16:30:00', 2, 0, 1, 1, 0),
(31, 5, 'CHANCE B 4:30PM', '16:30:00', 2, 0, 1, 1, 0),
(32, 5, 'CHANCE C 4:30PM', '16:30:00', 2, 0, 0, 1, 1),
(33, 5, 'ASTRAL 4:30PM', '16:30:00', 2, 1, 0, 1, 0),
(34, 3, 'ZODIACAL A 4:45PM', '16:45:00', 2, 0, 1, 1, 0),
(35, 3, 'ZODIACAL B 4:45PM', '16:45:00', 2, 0, 1, 1, 0),
(36, 3, 'ZODIACAL SIGNO 4:45PM', '16:45:00', 2, 1, 0, 1, 0),
(37, 6, 'ZULIA A 4:45PM', '16:45:00', 2, 0, 1, 1, 0),
(38, 6, 'ZULIA B 4:45PM', '16:45:00', 2, 0, 1, 1, 0),
(39, 6, 'ZULIA C 4:45PM', '16:45:00', 2, 0, 0, 1, 1),
(40, 6, 'ZODIACO DEL ZULIA 4:45PM', '16:45:00', 2, 1, 0, 1, 0),
(41, 2, 'TACHIRA A 5PM', '17:00:00', 2, 0, 1, 1, 0),
(42, 2, 'TACHIRA B 5PM', '17:00:00', 2, 0, 1, 1, 0),
(43, 2, 'TACHIRA C 5PM', '17:00:00', 2, 0, 0, 1, 1),
(44, 2, 'SIGNO TACHIRA 5PM', '17:00:00', 2, 1, 0, 1, 0),
(45, 1, 'ZAMORANO A 7PM', '19:00:00', 3, 0, 0, 0, 0),
(46, 1, 'ZAMORANO C 7PM', '19:00:00', 3, 0, 0, 0, 1),
(47, 1, 'ASTRO ZAMORANO 7PM', '19:00:00', 3, 1, 0, 0, 0),
(48, 3, 'ZODIACAL A 7:45PM', '19:45:00', 3, 0, 1, 1, 0),
(49, 3, 'ZODIACAL B 7:45PM', '19:45:00', 3, 0, 1, 1, 0),
(50, 3, 'ZODIACAL SIGNO 7:45PM', '19:45:00', 3, 1, 0, 1, 0),
(51, 4, 'LEON A 7:30PM', '19:30:00', 3, 0, 1, 1, 0),
(52, 4, 'LEON B 7:30PM', '19:30:00', 3, 0, 1, 1, 0),
(53, 4, 'LEON C 7:30PM', '19:30:00', 3, 0, 0, 1, 1),
(54, 4, 'TRIPLETON 7:30PM', '19:30:00', 3, 1, 0, 1, 0),
(55, 6, 'ZULIA A 7:45PM', '19:45:00', 3, 0, 1, 1, 0),
(56, 6, 'ZULIA B 7:45PM', '19:45:00', 3, 0, 1, 1, 0),
(57, 6, 'ZULIA C 7:45PM', '19:45:00', 3, 0, 0, 1, 1),
(58, 6, 'ZODIACO DEL ZULIA 7:45PM', '19:45:00', 3, 1, 0, 1, 0),
(59, 5, 'CHANCE A 8PM', '20:00:00', 3, 0, 1, 1, 0),
(60, 5, 'CHANCE B 8PM', '20:00:00', 3, 0, 1, 1, 0),
(61, 5, 'CHANCE C 8PM', '20:00:00', 3, 0, 0, 1, 1),
(62, 5, 'ASTRAL 8PM', '20:00:00', 3, 1, 0, 1, 0),
(63, 2, 'TACHIRA A 9PM', '21:00:00', 3, 0, 1, 1, 0),
(64, 2, 'TACHIRA B 9PM', '21:00:00', 3, 0, 1, 1, 0),
(65, 2, 'TACHIRA C 9PM', '21:00:00', 3, 0, 0, 1, 1),
(66, 2, 'SIGNO TACHIRA 9PM', '21:00:00', 3, 1, 0, 1, 0),
(67, 7, 'TRIPLEMANIA A 1PM', '13:00:00', 1, 0, 0, 1, 0),
(68, 7, 'TRIPLEMANIA B 1PM', '13:00:00', 1, 0, 0, 1, 0),
(69, 7, 'TRIPLEMANIA C 1PM', '13:00:00', 1, 0, 0, 1, 1),
(70, 7, 'MANIA ZODIACAL 1PM', '13:00:00', 1, 1, 0, 1, 0),
(71, 7, 'TRIPLEMANIA A 4:30PM', '16:30:00', 2, 0, 0, 1, 0),
(72, 7, 'TRIPLEMANIA B 4:30PM', '16:30:00', 2, 0, 0, 1, 0),
(73, 7, 'TRIPLEMANIA C 4:30PM', '16:30:00', 2, 0, 0, 1, 1),
(74, 7, 'MANIA ZODIACAL 4:30PM', '16:30:00', 2, 1, 0, 1, 0),
(75, 7, 'TRIPLEMANIA A 7:45PM', '19:45:00', 3, 0, 0, 1, 0),
(76, 7, 'TRIPLEMANIA B 7:45PM', '19:45:00', 3, 0, 0, 1, 0),
(77, 7, 'TRIPLEMANIA C 7:45PM', '19:45:00', 3, 0, 0, 1, 1),
(78, 7, 'MANIA ZODIACAL 7:45PM', '19:45:00', 3, 1, 0, 1, 0),
(79, 8, 'MULTITRIPLE A 12:40PM', '12:40:00', 1, 0, 0, 1, 0),
(80, 8, 'MULTITRIPLE B 12:40PM', '12:40:00', 1, 0, 0, 1, 0),
(81, 8, 'MULTITRIPLE C 12:40PM', '12:40:00', 1, 0, 0, 1, 1),
(82, 8, 'MULTITRIPLE A 4:40PM', '16:40:00', 2, 0, 0, 1, 0),
(83, 8, 'MULTITRIPLE B 4:40PM', '16:40:00', 2, 0, 0, 1, 0),
(84, 8, 'MULTITRIPLE C 4:40PM', '16:40:00', 2, 0, 0, 1, 1),
(85, 8, 'MULTISIGNO 12:40PM', '12:40:00', 1, 1, 0, 1, 0),
(86, 8, 'MULTISIGNO 4:40PM', '16:40:00', 2, 1, 0, 1, 0),
(87, 8, 'MULTITRIPLE A 7:40PM', '19:40:00', 3, 0, 0, 1, 0),
(88, 8, 'MULTITRIPLE B 7:40PM', '19:40:00', 3, 0, 0, 1, 0),
(89, 8, 'MULTITRIPLE C 7:40PM', '19:40:00', 3, 0, 0, 1, 1),
(90, 8, 'MULTISIGNO 7:40PM', '19:40:00', 3, 1, 0, 1, 0),
(91, 9, 'TRILLONARIO A 1:10PM', '13:10:00', 1, 0, 0, 1, 0),
(92, 9, 'TRILLONARIO B 1:10PM', '13:10:00', 1, 0, 0, 1, 0),
(93, 9, 'TRILLONARIO C 1:10PM', '13:10:00', 1, 0, 0, 1, 1),
(94, 9, 'TRILLON ZODIACAL 1:10PM', '13:10:00', 1, 1, 0, 1, 0),
(95, 9, 'TRILLONARIO A 4:40PM', '16:40:00', 2, 0, 0, 1, 0),
(96, 9, 'TRILLONARIO B 4:40PM', '16:40:00', 2, 0, 0, 1, 0),
(97, 9, 'TRILLONARIO C 4:40PM', '16:40:00', 2, 0, 0, 1, 1),
(98, 9, 'TRILLON ZODIACAL 4:40PM', '16:40:00', 2, 1, 0, 1, 0),
(99, 9, 'TRILLONARIO A 7:35PM', '19:35:00', 3, 0, 0, 1, 0),
(100, 9, 'TRILLONARIO B 7:35PM', '19:35:00', 3, 0, 0, 1, 0),
(101, 9, 'TRILLONARIO C 7:35PM', '19:35:00', 3, 0, 0, 1, 1),
(102, 9, 'TRILLON ZODIACAL 7:35PM', '19:35:00', 3, 1, 0, 1, 0),
(103, 3, 'ZODIACAL C 12:45PM', '12:45:00', 1, 0, 0, 1, 1),
(104, 3, 'ZODIACAL C 4:45PM', '16:45:00', 2, 0, 0, 1, 1),
(105, 3, 'ZODIACAL C 7:45PM', '19:45:00', 3, 0, 0, 1, 1);

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
-- Volcado de datos para la tabla `status`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `taquillas`
--

INSERT INTO `taquillas` (`id_taquilla`, `numero_taquilla`, `status`) VALUES
(1, '01', 1),
(2, '02', 1),
(3, '03', 1),
(4, '04', 1);

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
  `total_premiado` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '0=Inactivo, 1=Activo',
  `fecha_hora_anulacion` datetime NOT NULL,
  `taquilla_anulacion` int(11) NOT NULL,
  `subido` int(11) NOT NULL DEFAULT '0',
  `verificado` int(11) NOT NULL DEFAULT '0',
  `impreso` int(11) NOT NULL DEFAULT '0' COMMENT '0= No Impreso, 1= Impreso, 2=Reimpreso',
  PRIMARY KEY (`id_ticket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ticket';

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
  `monto_faltante` decimal(10,2) NOT NULL,
  `incompleto` int(11) NOT NULL COMMENT '0=completo, 1=Incompleto, 2=Agotado',
  `monto` decimal(10,2) NOT NULL,
  `id_taquilla` int(11) NOT NULL,
  `id_insert_jugada` int(11) NOT NULL,
  PRIMARY KEY (`id_ticket_transaccional`),
  KEY `id_taquilla` (`id_taquilla`),
  KEY `id_zodiacal` (`id_zodiacal`),
  KEY `id_sorteo` (`id_sorteo`),
  KEY `id_tipo_jugada` (`id_tipo_jugada`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Ticket Transaccional' AUTO_INCREMENT=1 ;

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
-- Volcado de datos para la tabla `tipo_jugadas`
--

INSERT INTO `tipo_jugadas` (`id_tipo_jugada`, `nombre_jugada`, `zodiacal`, `triple`, `status`) VALUES
(1, 'Triple', 0, 1, 1),
(2, 'Terminal', 0, 0, 1),
(3, 'Tripletazo', 1, 1, 1),
(4, 'Terminalazo', 1, 0, 1),
(5, 'Aproximacion', 0, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno`
--

CREATE TABLE IF NOT EXISTS `turno` (
  `id_turno` int(1) NOT NULL,
  `nom_turno` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `pre_turno` enum('M','T','N') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_turno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Turneo de los Sorteos';

--
-- Volcado de datos para la tabla `turno`
--

INSERT INTO `turno` (`id_turno`, `nom_turno`, `pre_turno`) VALUES
(1, 'Manana', 'M'),
(2, 'Tarde', 'T'),
(3, 'Noche', 'N');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `id_perfil`, `nombre_usuario`, `email_usuario`, `login_usuario`, `clave_usuario`, `id_status_usuario`) VALUES
(1, 1, 'Gerzahim Salas', 'rasce88@gmail.com', 'admin', 'admin1', 1),
(3, 4, 'Yumijaika Oduber', 'rasce88@gmail.com', 'yumi', '1234', 1),
(4, 4, 'Yiserly Fagundez', 'yiserly_17@hotmail.com', 'yiserly', '1234', 1),
(5, 4, 'Jasmin Burgo', 'jasmin_burgo@hotmail.com', 'jasmin', '1234', 1),
(6, 4, 'Daryeli Barrios', 'daryeli_barrios@hotmail.com', 'daryeli', '1234', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_taquillas`
--

CREATE TABLE IF NOT EXISTS `usuarios_taquillas` (
  `id_usuario_taquilla` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_taquilla` int(11) NOT NULL,
  `time_ping` datetime DEFAULT NULL,
  PRIMARY KEY (`id_usuario_taquilla`),
  KEY `id_taquilla` (`id_taquilla`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=166 ;

--
-- Volcado de datos para la tabla `usuarios_taquillas`
--

INSERT INTO `usuarios_taquillas` (`id_usuario_taquilla`, `id_usuario`, `id_taquilla`, `time_ping`) VALUES
(159, 5, 2, '2014-03-05 07:02:45'),
(163, 6, 1, '2014-03-05 07:31:55'),
(164, 4, 3, '2014-03-05 10:06:15'),
(165, 1, 4, '2014-03-05 15:47:30');

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
-- Volcado de datos para la tabla `zodiacal`
--

INSERT INTO `zodiacal` (`Id_zodiacal`, `nombre_zodiacal`, `pre_zodiacal`) VALUES
(0, 'No Zodiacal', '**'),
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
-- Restricciones para tablas volcadas
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
  ADD CONSTRAINT `FK_zodiacal` FOREIGN KEY (`zodiacal`) REFERENCES `zodiacal` (`Id_zodiacal`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `turno_fk` FOREIGN KEY (`id_turno`) REFERENCES `turno` (`id_turno`) ON DELETE CASCADE ON UPDATE CASCADE;

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
