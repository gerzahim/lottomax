-- phpMyAdmin SQL Dump
-- version 4.2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 10, 2014 at 05:52 PM
-- Server version: 5.6.17-log
-- PHP Version: 5.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `lottomax`
--

-- --------------------------------------------------------

--
-- Table structure for table `cupo_especial`
--

CREATE TABLE IF NOT EXISTS `cupo_especial` (
`id_cupo_especial` int(11) NOT NULL,
  `numero` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `id_sorteo` int(11) NOT NULL DEFAULT '0',
  `monto_cupo` int(11) NOT NULL COMMENT '0=Numero Bloqueado o Agotado',
  `id_tipo_jugada` int(11) NOT NULL,
  `id_zodiacal` int(11) NOT NULL DEFAULT '0',
  `fecha_desde` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `fecha_hasta` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cupo_general`
--

CREATE TABLE IF NOT EXISTS `cupo_general` (
`id_cupo_general` int(11) NOT NULL,
  `monto_cupo` int(11) NOT NULL,
  `id_tipo_jugada` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `cupo_general`
--

INSERT INTO `cupo_general` (`id_cupo_general`, `monto_cupo`, `id_tipo_jugada`) VALUES
(1, 30, 1),
(2, 50, 2),
(3, 1, 3),
(4, 5, 4);

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `detalle_ticket` (
`id_detalle_ticket` int(11) NOT NULL,
  `id_ticket` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `numero` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `id_sorteo` int(11) NOT NULL,
  `fecha_sorteo` date NOT NULL,
  `id_zodiacal` int(11) NOT NULL,
  `id_tipo_jugada` int(11) NOT NULL DEFAULT '0',
  `monto` decimal(10,2) NOT NULL,
  `premiado` int(11) NOT NULL DEFAULT '0',
  `total_premiado` int(11) NOT NULL DEFAULT '0',
  `monto_restante` decimal(10,2) NOT NULL,
  `monto_faltante` decimal(10,2) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Detalle ticket' AUTO_INCREMENT=1;

-- --------------------------------------------------------


--
-- Table structure for table `dias_semana`
--

CREATE TABLE IF NOT EXISTS `dias_semana` (
  `id_dias_semana` int(11) NOT NULL,
  `nombre_dia_semana` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `abv_dia_semana` varchar(4) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dias_semana`
--

INSERT INTO `dias_semana` (`id_dias_semana`, `nombre_dia_semana`, `abv_dia_semana`) VALUES
(0, 'Domingo', 'dom'),
(1, 'Lunes', 'lun'),
(2, 'Martes', 'mar'),
(3, 'Miercoles', 'mie'),
(4, 'Jueves', 'jue'),
(5, 'Viernes', 'vie'),
(6, 'Sabado', 'sab');

-- --------------------------------------------------------

--
-- Table structure for table `impresora_taquillas`
--

CREATE TABLE IF NOT EXISTS `impresora_taquillas` (
`id_impresora_taquillas` int(11) NOT NULL,
  `id_taquilla` int(11) NOT NULL,
  `nombre_vendedor_ticket` int(11) NOT NULL,
  `cortar_ticket` int(11) NOT NULL,
  `lineas_saltar_antes` int(11) NOT NULL,
  `lineas_saltar_despues` int(11) NOT NULL,
  `ver_numeros_incompletos` int(11) NOT NULL,
  `ver_numeros_agotados` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Configuracion de Impresora' AUTO_INCREMENT=10 ;

--
-- Dumping data for table `impresora_taquillas`
--

INSERT INTO `impresora_taquillas` (`id_impresora_taquillas`, `id_taquilla`, `nombre_vendedor_ticket`, `cortar_ticket`, `lineas_saltar_antes`, `lineas_saltar_despues`, `ver_numeros_incompletos`, `ver_numeros_agotados`) VALUES
(2, 2, 1, 0, 0, 0, 1, 1),
(3, 1, 1, 0, 0, 0, 1, 1),
(9, 3, 1, 0, 0, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `incompletos_agotados`
--

CREATE TABLE IF NOT EXISTS `incompletos_agotados` (
`id_incompletos_agotados` int(11) NOT NULL,
  `id_ticket` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `numero` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `id_sorteo` int(11) NOT NULL,
  `id_tipo_jugada` int(11) NOT NULL,
  `id_zodiacal` int(11) NOT NULL,
  `monto_restante` decimal(10,2) NOT NULL,
  `incompleto` int(11) NOT NULL COMMENT '0=completo, 1=Incompleto, 2=Agotado, 3=Con esta Jugada Se Agota'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='guarda numeros incompletos y agotados' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `loterias`
--

CREATE TABLE IF NOT EXISTS `loterias` (
`id_loteria` int(11) NOT NULL,
  `nombre_loteria` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL COMMENT '0=Inactivo, 1=Activo',
  `fecha_desde` date NOT NULL,
  `fecha_hasta` date NOT NULL,
  `id_dias_semana` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `status_especial` int(1) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Listados de Loterias' AUTO_INCREMENT=13 ;

--
-- Dumping data for table `loterias`
--

INSERT INTO `loterias` (`id_loteria`, `nombre_loteria`, `status`, `fecha_desde`, `fecha_hasta`, `id_dias_semana`, `status_especial`) VALUES
(1, 'ZAMORANO', 0, '0000-00-00', '0000-00-00', '1,2,3,4,5,6', 0),
(2, 'TACHIRA', 1, '0000-00-00', '0000-00-00', '1,2,3,4,5,6', 0),
(3, 'ZODIACAL', 0, '0000-00-00', '0000-00-00', '1,2,3,4,5,6', 0),
(4, 'LEON', 1, '0000-00-00', '0000-00-00', '1,2,3,4,5,6', 0),
(5, 'CHANCE', 1, '0000-00-00', '0000-00-00', '1,2,3,4,5,6', 0),
(6, 'ZULIA', 1, '0000-00-00', '0000-00-00', '1,2,3,4,5,6', 0),
(7, 'TRIPLEMANIA', 1, '0000-00-00', '0000-00-00', '1,2,3,4,5,6', 0),
(8, 'MULTITRIPLE', 1, '0000-00-00', '0000-00-00', '1,2,3,4,5,6', 0),
(9, 'TRILLONARIO', 1, '0000-00-00', '0000-00-00', '1,2,3,4,5,6', 0),
(10, 'PAR MILLONARIO', 1, '0000-00-00', '0000-00-00', '0', 0),
(11, 'ESPECIAL CANTADO', 1, '0000-00-00', '0000-00-00', '', 1),
(12, 'CASH 3', 1, '0000-00-00', '0000-00-00', '0,1,2,3,4,5,6', 0);

-- --------------------------------------------------------

--
-- Table structure for table `numeros_jugados`
--

CREATE TABLE IF NOT EXISTS `numeros_jugados` (
`id_numero_jugados` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `numero` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `id_sorteo` int(11) NOT NULL,
  `id_tipo_jugada` int(11) NOT NULL,
  `id_zodiacal` int(11) NOT NULL,
  `monto_restante` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `parametros`
--

CREATE TABLE IF NOT EXISTS `parametros` (
`id_parametros` int(11) NOT NULL,
  `id_agencia` int(11) NOT NULL,
  `nombre_agencia` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tiempo_cierre_sorteos` int(11) NOT NULL COMMENT 'Expresado en minutos',
  `tiempo_anulacion_ticket` int(11) NOT NULL COMMENT 'Expresado en minutos',
  `tiempo_vigencia_ticket` int(11) NOT NULL COMMENT 'Expresado en dias',
  `aprox_abajo` tinyint(1) NOT NULL DEFAULT '0',
  `aprox_arriba` tinyint(1) NOT NULL DEFAULT '0',
  `comision_agencia` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `parametros`
--

INSERT INTO `parametros` (`id_parametros`, `id_agencia`, `nombre_agencia`, `tiempo_cierre_sorteos`, `tiempo_anulacion_ticket`, `tiempo_vigencia_ticket`, `aprox_abajo`, `aprox_arriba`, `comision_agencia`) VALUES
(1, 2, 'El Valle', 10, 5, 3, 1, 1, 22);

-- --------------------------------------------------------

--
-- Table structure for table `perfil`
--

CREATE TABLE IF NOT EXISTS `perfil` (
`id_perfil` int(11) NOT NULL,
  `nombre_perfil` varchar(20) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `perfil`
--

INSERT INTO `perfil` (`id_perfil`, `nombre_perfil`) VALUES
(1, 'Administrador'),
(2, 'Banquero'),
(3, 'Intermediario'),
(4, 'Vendedor');

-- --------------------------------------------------------

--
-- Table structure for table `prueba`
--

CREATE TABLE IF NOT EXISTS `prueba` (
`id_prueba` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='tabla de prueba' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `prueba`
--

INSERT INTO `prueba` (`id_prueba`, `date`) VALUES
(1, '2014-02-20 14:06:50'),
(2, '2014-02-20 14:19:39'),
(3, '2014-02-20 14:19:53');

-- --------------------------------------------------------

--
-- Table structure for table `relacion_pagos`
--

CREATE TABLE IF NOT EXISTS `relacion_pagos` (
`id_relacion_pagos` int(11) NOT NULL,
  `monto` int(11) NOT NULL,
  `id_tipo_jugada` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0=Inactivo, 1=Activo'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `relacion_pagos`
--

INSERT INTO `relacion_pagos` (`id_relacion_pagos`, `monto`, `id_tipo_jugada`, `status`) VALUES
(1, 800, 1, 1),
(2, 60, 2, 1),
(3, 6000, 3, 1),
(4, 600, 4, 1),
(5, 10, 5, 1);

--
-- Table structure for table `resultados`
--

CREATE TABLE IF NOT EXISTS `resultados` (
`id_resultados` int(11) NOT NULL,
  `id_sorteo` int(11) NOT NULL,
  `zodiacal` int(11) NOT NULL,
  `numero` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_hora` date NOT NULL,
  `bajado` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sorteos`
--

CREATE TABLE IF NOT EXISTS `sorteos` (
`id_sorteo` int(11) NOT NULL,
  `id_loteria` int(11) NOT NULL DEFAULT '0',
  `nombre_sorteo` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `hora_sorteo` time NOT NULL,
  `id_turno` int(1) NOT NULL,
  `zodiacal` int(11) NOT NULL,
  `tradicional` int(1) NOT NULL COMMENT '0=No Tradicional, 1= Si Tradicional',
  `status` int(11) NOT NULL COMMENT '0=Inactivo, 1=Activo',
  `id_tipo_sorteo` int(1) NOT NULL,
  `id_dias_semana` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=110 ;

--
-- Dumping data for table `sorteos`
--

INSERT INTO `sorteos` (`id_sorteo`, `id_loteria`, `nombre_sorteo`, `hora_sorteo`, `id_turno`, `zodiacal`, `tradicional`, `status`, `id_tipo_sorteo`, `id_dias_semana`) VALUES
(1, 1, 'ZAMORANO A 12PM', '12:00:00', 1, 0, 0, 0, 1, '1,2,3,4,5,6'),
(2, 1, 'ZAMORANO C 12PM', '12:00:00', 1, 0, 0, 0, 3, '1,2,3,4,5,6'),
(3, 1, 'ASTRO ZAMORANO 12PM', '12:00:00', 1, 1, 0, 0, 4, '1,2,3,4,5,6'),
(4, 2, 'TACHIRA A 12PM', '12:00:00', 1, 0, 1, 1, 1, '1,2,3,4,5,6'),
(5, 2, 'TACHIRA B 12PM', '12:00:00', 1, 0, 1, 1, 2, '1,2,3,4,5,6'),
(6, 2, 'TACHIRA C 12PM', '12:00:00', 1, 0, 0, 1, 3, '1,2,3,4,5,6'),
(7, 2, 'SIGNO TACHIRA 12PM', '12:00:00', 1, 1, 0, 1, 4, '1,2,3,4,5,6'),
(8, 3, 'ZODIACAL A 12:45PM', '12:45:00', 1, 0, 1, 0, 1, '1,2,3,4,5,6'),
(9, 3, 'ZODIACAL B 12:45PM', '12:45:00', 1, 0, 1, 0, 2, '1,2,3,4,5,6'),
(10, 3, 'ZODIACAL SIGNO 12:45PM', '12:45:00', 1, 1, 0, 0, 4, '1,2,3,4,5,6'),
(11, 4, 'LEON A 12:30PM', '12:30:00', 1, 0, 1, 1, 1, '1,2,3,4,5,6'),
(12, 4, 'LEON B 12:30PM', '12:30:00', 1, 0, 1, 1, 2, '1,2,3,4,5,6'),
(13, 4, 'LEON C 12:30PM', '12:30:00', 1, 0, 0, 1, 3, '1,2,3,4,5,6'),
(14, 4, 'TRIPLETON 12:30PM', '12:30:00', 1, 1, 0, 1, 4, '1,2,3,4,5,6'),
(15, 6, 'ZULIA A 12:45PM', '12:45:00', 1, 0, 1, 1, 1, '1,2,3,4,5,6'),
(16, 6, 'ZULIA B 12:45PM', '12:45:00', 1, 0, 1, 1, 2, '1,2,3,4,5,6'),
(17, 6, 'ZULIA C 12:45PM', '12:45:00', 1, 0, 0, 1, 3, '1,2,3,4,5,6'),
(18, 6, 'ZODIACO DEL ZULIA 12:45PM', '12:45:00', 1, 1, 0, 1, 4, '1,2,3,4,5,6'),
(19, 5, 'CHANCE A 1PM', '13:00:00', 1, 0, 1, 1, 1, '1,2,3,4,5,6'),
(20, 5, 'CHANCE B 1PM', '13:00:00', 1, 0, 1, 1, 2, '1,2,3,4,5,6'),
(21, 5, 'CHANCE C 1PM', '13:00:00', 1, 0, 0, 1, 3, '1,2,3,4,5,6'),
(22, 5, 'ASTRAL 1PM', '13:00:00', 1, 1, 0, 1, 4, '1,2,3,4,5,6'),
(23, 1, 'ZAMORANO A 4PM', '16:00:00', 2, 0, 0, 0, 1, '1,2,3,4,5,6'),
(24, 1, 'ZAMORANO C 4PM', '16:00:00', 2, 0, 0, 0, 3, '1,2,3,4,5,6'),
(25, 1, 'ASTRO ZAMORANO 4PM', '16:00:00', 2, 1, 0, 0, 4, '1,2,3,4,5,6'),
(26, 4, 'LEON A 4:30PM', '16:30:00', 2, 0, 1, 1, 1, '1,2,3,4,5,6'),
(27, 4, 'LEON B 4:30PM', '16:30:00', 2, 0, 1, 1, 2, '1,2,3,4,5,6'),
(28, 4, 'LEON C 4:30PM', '16:30:00', 2, 0, 0, 1, 3, '1,2,3,4,5,6'),
(29, 4, 'TRIPLETON 4:30PM', '16:30:00', 2, 1, 0, 1, 4, '1,2,3,4,5,6'),
(30, 5, 'CHANCE A 4:30PM', '16:30:00', 2, 0, 1, 1, 1, '1,2,3,4,5,6'),
(31, 5, 'CHANCE B 4:30PM', '16:30:00', 2, 0, 1, 1, 2, '1,2,3,4,5,6'),
(32, 5, 'CHANCE C 4:30PM', '16:30:00', 2, 0, 0, 1, 3, '1,2,3,4,5,6'),
(33, 5, 'ASTRAL 4:30PM', '16:30:00', 2, 1, 0, 1, 4, '1,2,3,4,5,6'),
(34, 3, 'ZODIACAL A 4:45PM', '16:45:00', 2, 0, 1, 0, 1, '1,2,3,4,5,6'),
(35, 3, 'ZODIACAL B 4:45PM', '16:45:00', 2, 0, 1, 0, 2, '1,2,3,4,5,6'),
(36, 3, 'ZODIACAL SIGNO 4:45PM', '16:45:00', 2, 1, 0, 0, 4, '1,2,3,4,5,6'),
(37, 6, 'ZULIA A 4:45PM', '16:45:00', 2, 0, 1, 1, 1, '1,2,3,4,5,6'),
(38, 6, 'ZULIA B 4:45PM', '16:45:00', 2, 0, 1, 1, 2, '1,2,3,4,5,6'),
(39, 6, 'ZULIA C 4:45PM', '16:45:00', 2, 0, 0, 1, 3, '1,2,3,4,5,6'),
(40, 6, 'ZODIACO DEL ZULIA 4:45PM', '16:45:00', 2, 1, 0, 1, 4, '1,2,3,4,5,6'),
(41, 2, 'TACHIRA A 5PM', '17:00:00', 2, 0, 1, 1, 1, '1,2,3,4,5,6'),
(42, 2, 'TACHIRA B 5PM', '17:00:00', 2, 0, 1, 1, 2, '1,2,3,4,5,6'),
(43, 2, 'TACHIRA C 5PM', '17:00:00', 2, 0, 0, 1, 3, '1,2,3,4,5,6'),
(44, 2, 'SIGNO TACHIRA 5PM', '17:00:00', 2, 1, 0, 1, 4, '1,2,3,4,5,6'),
(45, 1, 'ZAMORANO A 7PM', '19:00:00', 3, 0, 0, 0, 1, '1,2,3,4,5,6'),
(46, 1, 'ZAMORANO C 7PM', '19:00:00', 3, 0, 0, 0, 3, '1,2,3,4,5,6'),
(47, 1, 'ASTRO ZAMORANO 7PM', '19:00:00', 3, 1, 0, 0, 4, '1,2,3,4,5,6'),
(48, 3, 'ZODIACAL A 7:45PM', '19:45:00', 3, 0, 1, 0, 1, '0,1,2,3,4,5,6'),
(49, 3, 'ZODIACAL B 7:45PM', '19:45:00', 3, 0, 1, 0, 2, '0,1,2,3,4,5,6'),
(50, 3, 'ZODIACAL SIGNO 7:45PM', '19:45:00', 3, 1, 0, 0, 4, '0,1,2,3,4,5,6'),
(51, 4, 'LEON A 7:45PM', '19:45:00', 3, 0, 1, 1, 1, '0,1,2,3,4,5,6'),
(52, 4, 'LEON B 7:45PM', '19:45:00', 3, 0, 1, 1, 2, '0,1,2,3,4,5,6'),
(53, 4, 'LEON C 7:45PM', '19:45:00', 3, 0, 0, 1, 3, '0,1,2,3,4,5,6'),
(54, 4, 'TRIPLETON 7:45PM', '19:45:00', 3, 1, 0, 1, 4, '0,1,2,3,4,5,6'),
(55, 6, 'ZULIA A 7:45PM', '19:45:00', 3, 0, 1, 1, 1, '0,1,2,3,4,5,6'),
(56, 6, 'ZULIA B 7:45PM', '19:45:00', 3, 0, 1, 1, 2, '0,1,2,3,4,5,6'),
(57, 6, 'ZULIA C 7:45PM', '19:45:00', 3, 0, 0, 1, 3, '0,1,2,3,4,5,6'),
(58, 6, 'ZODIACO DEL ZULIA 7:45PM', '19:45:00', 3, 1, 0, 1, 4, '0,1,2,3,4,5,6'),
(59, 5, 'CHANCE A 8PM', '20:00:00', 3, 0, 1, 1, 1, '0,1,2,3,4,5,6'),
(60, 5, 'CHANCE B 8PM', '20:00:00', 3, 0, 1, 1, 2, '0,1,2,3,4,5,6'),
(61, 5, 'CHANCE C 8PM', '20:00:00', 3, 0, 0, 1, 3, '0,1,2,3,4,5,6'),
(62, 5, 'ASTRAL 8PM', '20:00:00', 3, 1, 0, 1, 4, '0,1,2,3,4,5,6'),
(63, 2, 'TACHIRA A 9PM', '21:00:00', 3, 0, 1, 1, 1, '1,2,3,4,5,6'),
(64, 2, 'TACHIRA B 9PM', '21:00:00', 3, 0, 1, 1, 2, '1,2,3,4,5,6'),
(65, 2, 'TACHIRA C 9PM', '21:00:00', 3, 0, 0, 1, 3, '1,2,3,4,5,6'),
(66, 2, 'SIGNO TACHIRA 9PM', '21:00:00', 3, 1, 0, 1, 4, '1,2,3,4,5,6'),
(67, 7, 'TRIPLEMANIA A 1PM', '13:00:00', 1, 0, 0, 1, 1, '1,2,3,4,5,6'),
(68, 7, 'TRIPLEMANIA B 1PM', '13:00:00', 1, 0, 0, 1, 2, '1,2,3,4,5,6'),
(69, 7, 'TRIPLEMANIA C 1PM', '13:00:00', 1, 0, 0, 1, 3, '1,2,3,4,5,6'),
(70, 7, 'MANIA ZODIACAL 1PM', '13:00:00', 1, 1, 0, 1, 4, '1,2,3,4,5,6'),
(71, 7, 'TRIPLEMANIA A 4:30PM', '16:30:00', 2, 0, 0, 1, 1, '0,1,2,3,4,5,6'),
(72, 7, 'TRIPLEMANIA B 4:30PM', '16:30:00', 2, 0, 0, 1, 2, '0,1,2,3,4,5,6'),
(73, 7, 'TRIPLEMANIA C 4:30PM', '16:30:00', 2, 0, 0, 1, 3, '0,1,2,3,4,5,6'),
(74, 7, 'MANIA ZODIACAL 4:30PM', '16:30:00', 2, 1, 0, 1, 4, '0,1,2,3,4,5,6'),
(75, 7, 'TRIPLEMANIA A 7:45PM', '19:45:00', 3, 0, 0, 1, 1, '1,2,3,4,5,6'),
(76, 7, 'TRIPLEMANIA B 7:45PM', '19:45:00', 3, 0, 0, 1, 2, '1,2,3,4,5,6'),
(77, 7, 'TRIPLEMANIA C 7:45PM', '19:45:00', 3, 0, 0, 1, 3, '1,2,3,4,5,6'),
(78, 7, 'MANIA ZODIACAL 7:45PM', '19:45:00', 3, 1, 0, 1, 4, '1,2,3,4,5,6'),
(79, 8, 'MULTITRIPLE A 12:40PM', '12:40:00', 1, 0, 0, 1, 1, '1,2,3,4,5,6'),
(80, 8, 'MULTITRIPLE B 12:40PM', '12:40:00', 1, 0, 0, 1, 2, '1,2,3,4,5,6'),
(81, 8, 'MULTITRIPLE C 12:40PM', '12:40:00', 1, 0, 0, 1, 3, '1,2,3,4,5,6'),
(82, 8, 'MULTITRIPLE A 4:40PM', '16:40:00', 2, 0, 0, 1, 1, '0,1,2,3,4,5,6'),
(83, 8, 'MULTITRIPLE B 4:40PM', '16:40:00', 2, 0, 0, 1, 2, '0,1,2,3,4,5,6'),
(84, 8, 'MULTITRIPLE C 4:40PM', '16:40:00', 2, 0, 0, 1, 3, '0,1,2,3,4,5,6'),
(85, 8, 'MULTISIGNO 12:40PM', '12:40:00', 1, 1, 0, 1, 4, '1,2,3,4,5,6'),
(86, 8, 'MULTISIGNO 4:40PM', '16:40:00', 2, 1, 0, 1, 4, '0,1,2,3,4,5,6'),
(87, 8, 'MULTITRIPLE A 7:40PM', '19:40:00', 3, 0, 0, 1, 1, '1,2,3,4,5,6'),
(88, 8, 'MULTITRIPLE B 7:40PM', '19:40:00', 3, 0, 0, 1, 2, '1,2,3,4,5,6'),
(89, 8, 'MULTITRIPLE C 7:40PM', '19:40:00', 3, 0, 0, 1, 3, '1,2,3,4,5,6'),
(90, 8, 'MULTISIGNO 7:40PM', '19:40:00', 3, 1, 0, 1, 4, '1,2,3,4,5,6'),
(91, 9, 'TRILLONARIO A 1:10PM', '13:10:00', 1, 0, 0, 1, 1, '1,2,3,4,5,6'),
(92, 9, 'TRILLONARIO B 1:10PM', '13:10:00', 1, 0, 0, 1, 2, '1,2,3,4,5,6'),
(93, 9, 'TRILLONARIO C 1:10PM', '13:10:00', 1, 0, 0, 1, 3, '1,2,3,4,5,6'),
(94, 9, 'TRILLON ZODIACAL 1:10PM', '13:10:00', 1, 1, 0, 1, 4, '1,2,3,4,5,6'),
(95, 9, 'TRILLONARIO A 4:40PM', '16:40:00', 2, 0, 0, 1, 1, '0,1,2,3,4,5,6'),
(96, 9, 'TRILLONARIO B 4:40PM', '16:40:00', 2, 0, 0, 1, 2, '0,1,2,3,4,5,6'),
(97, 9, 'TRILLONARIO C 4:40PM', '16:40:00', 2, 0, 0, 1, 3, '0,1,2,3,4,5,6'),
(98, 9, 'TRILLON ZODIACAL 4:40PM', '16:40:00', 2, 1, 0, 1, 4, '0,1,2,3,4,5,6'),
(99, 9, 'TRILLONARIO A 7:35PM', '19:35:00', 3, 0, 0, 1, 1, '1,2,3,4,5,6'),
(100, 9, 'TRILLONARIO B 7:35PM', '19:35:00', 3, 0, 0, 1, 2, '1,2,3,4,5,6'),
(101, 9, 'TRILLONARIO C 7:35PM', '19:35:00', 3, 0, 0, 1, 3, '1,2,3,4,5,6'),
(102, 9, 'TRILLON ZODIACAL 7:35PM', '19:35:00', 3, 1, 0, 1, 4, '1,2,3,4,5,6'),
(103, 3, 'ZODIACAL C 12:45PM', '12:45:00', 1, 0, 0, 0, 3, '1,2,3,4,5,6'),
(104, 3, 'ZODIACAL C 4:45PM', '16:45:00', 2, 0, 0, 0, 3, '1,2,3,4,5,6'),
(105, 3, 'ZODIACAL C 7:45PM', '19:45:00', 3, 0, 0, 0, 3, '0,1,2,3,4,5,6'),
(106, 10, 'PAR MILL A 7:30PM', '19:30:00', 3, 0, 0, 1, 1, '0'),
(107, 10, 'PAR MILL B 7:30PM', '19:30:00', 3, 0, 0, 1, 2, '0'),
(108, 11, 'ESPECIAL CANTADO A 7:30PM', '19:30:00', 3, 0, 0, 1, 1, '0'),
(109, 12, 'CASH 3', '21:00:00', 3, 0, 0, 0, 1, '0,1,2,3,4,5,6');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `id_status` int(11) NOT NULL,
  `status_nombre` varchar(15) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id_status`, `status_nombre`) VALUES
(0, 'Inactivo'),
(1, 'Activo'),
(2, 'Modificado');

-- --------------------------------------------------------

--
-- Table structure for table `taquillas`
--

CREATE TABLE IF NOT EXISTS `taquillas` (
`id_taquilla` int(11) NOT NULL,
  `numero_taquilla` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL COMMENT ' 0=Inactivo, 1=Activo'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `taquillas`
--

INSERT INTO `taquillas` (`id_taquilla`, `numero_taquilla`, `status`) VALUES
(1, '01', 1),
(2, '02', 1),
(3, '03', 1),
(4, '04', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
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
  `fecha_hora_pagado` datetime DEFAULT NULL,
  `taquilla_pagado` int(11) DEFAULT NULL,
  `usuario_pagado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ticket' AUTO_INCREMENT=1 ;

--
-- Table structure for table `ticket_transaccional`
--

CREATE TABLE IF NOT EXISTS `ticket_transaccional` (
`id_ticket_transaccional` int(11) NOT NULL,
  `numero` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `id_sorteo` int(11) NOT NULL,
  `id_zodiacal` int(11) NOT NULL,
  `id_tipo_jugada` int(11) NOT NULL,
  `monto_faltante` decimal(10,2) NOT NULL,
  `incompleto` int(11) NOT NULL COMMENT '0=completo, 1=Incompleto, 2=Agotado',
  `monto` decimal(10,2) NOT NULL,
  `id_taquilla` int(11) NOT NULL,
  `id_insert_jugada` int(11) NOT NULL,
  `monto_restante` decimal(10,2) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Ticket Transaccional' AUTO_INCREMENT=558 ;


--
-- Table structure for table `tipo_jugadas`
--

CREATE TABLE IF NOT EXISTS `tipo_jugadas` (
`id_tipo_jugada` int(11) NOT NULL,
  `nombre_jugada` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `zodiacal` int(11) NOT NULL COMMENT ' 0=No es Zodiacal, 1=Si es Zodiacal',
  `triple` int(11) NOT NULL COMMENT ' 0=es Terminal, 1=es Triple, 2= aproximacion',
  `status` int(11) NOT NULL COMMENT '0=Inactivo, 1=Activo'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tipo_jugadas`
--

INSERT INTO `tipo_jugadas` (`id_tipo_jugada`, `nombre_jugada`, `zodiacal`, `triple`, `status`) VALUES
(1, 'Triple', 0, 1, 1),
(2, 'Terminal', 0, 0, 1),
(3, 'Tripletazo', 1, 1, 1),
(4, 'Terminalazo', 1, 0, 1),
(5, 'Aproximacion', 0, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tipo_sorteo`
--

CREATE TABLE IF NOT EXISTS `tipo_sorteo` (
  `id_tipo_sorteo` int(11) NOT NULL,
  `letra_tipo_sorteo` varchar(1) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tipo_sorteo`
--

INSERT INTO `tipo_sorteo` (`id_tipo_sorteo`, `letra_tipo_sorteo`) VALUES
(1, 'A'),
(2, 'B'),
(3, 'C'),
(4, 'Z');

-- --------------------------------------------------------

--
-- Table structure for table `turno`
--

CREATE TABLE IF NOT EXISTS `turno` (
  `id_turno` int(1) NOT NULL,
  `nom_turno` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `pre_turno` enum('M','T','N') COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Turneo de los Sorteos';

--
-- Dumping data for table `turno`
--

INSERT INTO `turno` (`id_turno`, `nom_turno`, `pre_turno`) VALUES
(1, 'Manana', 'M'),
(2, 'Tarde', 'T'),
(3, 'Noche', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
`id_usuario` int(10) NOT NULL,
  `id_perfil` int(10) DEFAULT NULL,
  `nombre_usuario` varchar(80) DEFAULT NULL,
  `email_usuario` varchar(100) DEFAULT NULL,
  `login_usuario` varchar(10) DEFAULT NULL,
  `clave_usuario` varchar(10) DEFAULT NULL,
  `id_status_usuario` int(11) DEFAULT NULL COMMENT '0=Inactivo, 1=Activo'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `id_perfil`, `nombre_usuario`, `email_usuario`, `login_usuario`, `clave_usuario`, `id_status_usuario`) VALUES
(1, 1, 'Gerzahim Salas', 'rasce88@gmail.com', 'admin', 'admin1', 1),
(3, 1, 'Yumijaika Oduber', 'rasce88@gmail.com', 'yumi', '1234', 1),
(4, 4, 'Yiserly Fagundez', 'yiserly_17@hotmail.com', 'yiserly', '1234', 1),
(5, 4, 'Jasmin Burgo', 'jasmin_burgo@hotmail.com', 'jasmin', '1234', 1),
(6, 4, 'Daryeli Barrios', 'daryeli_barrios@hotmail.com', 'daryeli', '1234', 1);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios_taquillas`
--

CREATE TABLE IF NOT EXISTS `usuarios_taquillas` (
`id_usuario_taquilla` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_taquilla` int(11) NOT NULL,
  `time_ping` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=729 ;

--
-- Dumping data for table `usuarios_taquillas`
--

INSERT INTO `usuarios_taquillas` (`id_usuario_taquilla`, `id_usuario`, `id_taquilla`, `time_ping`) VALUES
(570, 4, 3, '2014-05-16 08:23:57'),
(604, 5, 2, '2014-05-29 11:58:40'),
(727, 1, 1, '2014-09-10 00:27:55');

-- --------------------------------------------------------

--
-- Table structure for table `zodiacal`
--

CREATE TABLE IF NOT EXISTS `zodiacal` (
  `Id_zodiacal` int(11) NOT NULL,
  `nombre_zodiacal` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `pre_zodiacal` varchar(3) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Signo Zodiacal';

--
-- Dumping data for table `zodiacal`
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
-- Indexes for dumped tables
--

--
-- Indexes for table `cupo_especial`
--
ALTER TABLE `cupo_especial`
 ADD PRIMARY KEY (`id_cupo_especial`), ADD KEY `FK_id_sorteo` (`id_sorteo`), ADD KEY `id_tipo_jugada` (`id_tipo_jugada`), ADD KEY `id_zodiacal` (`id_zodiacal`);

--
-- Indexes for table `cupo_general`
--
ALTER TABLE `cupo_general`
 ADD PRIMARY KEY (`id_cupo_general`), ADD KEY `FK_id_tipo_jugadas` (`id_tipo_jugada`);

--
-- Indexes for table `dias_semana`
--
ALTER TABLE `dias_semana`
 ADD PRIMARY KEY (`id_dias_semana`);

--
-- Indexes for table `impresora_taquillas`
--
ALTER TABLE `impresora_taquillas`
 ADD PRIMARY KEY (`id_impresora_taquillas`), ADD UNIQUE KEY `id_taquilla` (`id_taquilla`);

--
-- Indexes for table `incompletos_agotados`
--
ALTER TABLE `incompletos_agotados`
 ADD PRIMARY KEY (`id_incompletos_agotados`), ADD KEY `id_sorteo` (`id_sorteo`), ADD KEY `id_tipo_jugada` (`id_tipo_jugada`), ADD KEY `id_zodiacal` (`id_zodiacal`), ADD KEY `FK_id_tickets` (`id_ticket`);

--
-- Indexes for table `loterias`
--
ALTER TABLE `loterias`
 ADD PRIMARY KEY (`id_loteria`);

--
-- Indexes for table `numeros_jugados`
--
ALTER TABLE `numeros_jugados`
 ADD PRIMARY KEY (`id_numero_jugados`), ADD KEY `id_sorteo` (`id_sorteo`), ADD KEY `id_tipo_jugada` (`id_tipo_jugada`), ADD KEY `id_zodiacal` (`id_zodiacal`);

--
-- Indexes for table `parametros`
--
ALTER TABLE `parametros`
 ADD UNIQUE KEY `id_parametros` (`id_parametros`);

--
-- Indexes for table `perfil`
--
ALTER TABLE `perfil`
 ADD PRIMARY KEY (`id_perfil`);

--
-- Indexes for table `prueba`
--
ALTER TABLE `prueba`
 ADD PRIMARY KEY (`id_prueba`);

--
-- Indexes for table `relacion_pagos`
--
ALTER TABLE `relacion_pagos`
 ADD PRIMARY KEY (`id_relacion_pagos`), ADD KEY `id_tipo_jugada` (`id_tipo_jugada`);

--
-- Indexes for table `sorteos`
--
ALTER TABLE `sorteos`
 ADD UNIQUE KEY `id_sorrteo` (`id_sorteo`), ADD KEY `FK_zodiacal` (`zodiacal`), ADD KEY `FK_id_loteria` (`id_loteria`), ADD KEY `turno_fk` (`id_turno`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
 ADD PRIMARY KEY (`id_status`);

--
-- Indexes for table `taquillas`
--
ALTER TABLE `taquillas`
 ADD PRIMARY KEY (`id_taquilla`);

--
-- Indexes for table `ticket_transaccional`
--
ALTER TABLE `ticket_transaccional`
 ADD PRIMARY KEY (`id_ticket_transaccional`), ADD KEY `id_taquilla` (`id_taquilla`), ADD KEY `id_zodiacal` (`id_zodiacal`), ADD KEY `id_sorteo` (`id_sorteo`), ADD KEY `id_tipo_jugada` (`id_tipo_jugada`);

--
-- Indexes for table `tipo_jugadas`
--
ALTER TABLE `tipo_jugadas`
 ADD PRIMARY KEY (`id_tipo_jugada`);

--
-- Indexes for table `tipo_sorteo`
--
ALTER TABLE `tipo_sorteo`
 ADD KEY `id_tipo_sorteo` (`id_tipo_sorteo`);

--
-- Indexes for table `turno`
--
ALTER TABLE `turno`
 ADD PRIMARY KEY (`id_turno`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
 ADD PRIMARY KEY (`id_usuario`), ADD KEY `FK_id_perfil` (`id_perfil`);

--
-- Indexes for table `usuarios_taquillas`
--
ALTER TABLE `usuarios_taquillas`
 ADD PRIMARY KEY (`id_usuario_taquilla`), ADD KEY `id_taquilla` (`id_taquilla`);

--
-- Indexes for table `zodiacal`
--
ALTER TABLE `zodiacal`
 ADD UNIQUE KEY `Id_zodiacal` (`Id_zodiacal`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cupo_especial`
--
ALTER TABLE `cupo_especial`
MODIFY `id_cupo_especial` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cupo_general`
--
ALTER TABLE `cupo_general`
MODIFY `id_cupo_general` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `impresora_taquillas`
--
ALTER TABLE `impresora_taquillas`
MODIFY `id_impresora_taquillas` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `incompletos_agotados`
--
ALTER TABLE `incompletos_agotados`
MODIFY `id_incompletos_agotados` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `loterias`
--
ALTER TABLE `loterias`
MODIFY `id_loteria` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `numeros_jugados`
--
ALTER TABLE `numeros_jugados`
MODIFY `id_numero_jugados` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `parametros`
--
ALTER TABLE `parametros`
MODIFY `id_parametros` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `perfil`
--
ALTER TABLE `perfil`
MODIFY `id_perfil` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `prueba`
--
ALTER TABLE `prueba`
MODIFY `id_prueba` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `relacion_pagos`
--
ALTER TABLE `relacion_pagos`
MODIFY `id_relacion_pagos` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `sorteos`
--
ALTER TABLE `sorteos`
MODIFY `id_sorteo` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=110;
--
-- AUTO_INCREMENT for table `taquillas`
--
ALTER TABLE `taquillas`
MODIFY `id_taquilla` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `ticket_transaccional`
--
ALTER TABLE `ticket_transaccional`
MODIFY `id_ticket_transaccional` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=558;
--
-- AUTO_INCREMENT for table `tipo_jugadas`
--
ALTER TABLE `tipo_jugadas`
MODIFY `id_tipo_jugada` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
MODIFY `id_usuario` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `usuarios_taquillas`
--
ALTER TABLE `usuarios_taquillas`
MODIFY `id_usuario_taquilla` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=729;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `cupo_especial`
--
ALTER TABLE `cupo_especial`
ADD CONSTRAINT `cupo_especial_ibfk_1` FOREIGN KEY (`id_tipo_jugada`) REFERENCES `tipo_jugadas` (`id_tipo_jugada`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `cupo_especial_ibfk_2` FOREIGN KEY (`id_zodiacal`) REFERENCES `zodiacal` (`Id_zodiacal`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `FK_id_sorteo` FOREIGN KEY (`id_sorteo`) REFERENCES `sorteos` (`id_sorteo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cupo_general`
--
ALTER TABLE `cupo_general`
ADD CONSTRAINT `FK_id_tipo_jugadas` FOREIGN KEY (`id_tipo_jugada`) REFERENCES `tipo_jugadas` (`id_tipo_jugada`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `impresora_taquillas`
--
ALTER TABLE `impresora_taquillas`
ADD CONSTRAINT `impresora_taquillas_ibfk_1` FOREIGN KEY (`id_taquilla`) REFERENCES `taquillas` (`id_taquilla`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `incompletos_agotados`
--
ALTER TABLE `incompletos_agotados`
ADD CONSTRAINT `incompletos_agotados_ibfk_1` FOREIGN KEY (`id_sorteo`) REFERENCES `sorteos` (`id_sorteo`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `incompletos_agotados_ibfk_2` FOREIGN KEY (`id_tipo_jugada`) REFERENCES `tipo_jugadas` (`id_tipo_jugada`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `incompletos_agotados_ibfk_3` FOREIGN KEY (`id_zodiacal`) REFERENCES `zodiacal` (`Id_zodiacal`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `incompletos_agotados_ibfk_4` FOREIGN KEY (`id_ticket`) REFERENCES `ticket` (`id_ticket`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `numeros_jugados`
--
ALTER TABLE `numeros_jugados`
ADD CONSTRAINT `numeros_jugados_ibfk_1` FOREIGN KEY (`id_sorteo`) REFERENCES `sorteos` (`id_sorteo`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `numeros_jugados_ibfk_2` FOREIGN KEY (`id_tipo_jugada`) REFERENCES `tipo_jugadas` (`id_tipo_jugada`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `numeros_jugados_ibfk_3` FOREIGN KEY (`id_zodiacal`) REFERENCES `zodiacal` (`Id_zodiacal`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `relacion_pagos`
--
ALTER TABLE `relacion_pagos`
ADD CONSTRAINT `relacion_pagos_ibfk_1` FOREIGN KEY (`id_tipo_jugada`) REFERENCES `tipo_jugadas` (`id_tipo_jugada`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sorteos`
--
ALTER TABLE `sorteos`
ADD CONSTRAINT `FK_id_loteria` FOREIGN KEY (`id_loteria`) REFERENCES `loterias` (`id_loteria`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `FK_zodiacal` FOREIGN KEY (`zodiacal`) REFERENCES `zodiacal` (`Id_zodiacal`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `turno_fk` FOREIGN KEY (`id_turno`) REFERENCES `turno` (`id_turno`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ticket_transaccional`
--
ALTER TABLE `ticket_transaccional`
ADD CONSTRAINT `ticket_transaccional_ibfk_1` FOREIGN KEY (`id_sorteo`) REFERENCES `sorteos` (`id_sorteo`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `ticket_transaccional_ibfk_2` FOREIGN KEY (`id_zodiacal`) REFERENCES `zodiacal` (`Id_zodiacal`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `ticket_transaccional_ibfk_3` FOREIGN KEY (`id_tipo_jugada`) REFERENCES `tipo_jugadas` (`id_tipo_jugada`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `ticket_transaccional_ibfk_4` FOREIGN KEY (`id_taquilla`) REFERENCES `taquillas` (`id_taquilla`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `usuario`
--
ALTER TABLE `usuario`
ADD CONSTRAINT `FK_id_perfil` FOREIGN KEY (`id_perfil`) REFERENCES `perfil` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
