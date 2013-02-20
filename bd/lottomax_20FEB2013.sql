-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 20-02-2013 a las 17:41:13
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
-- Estructura de tabla para la tabla `bloqueo_numero`
--

CREATE TABLE IF NOT EXISTS `bloqueo_numero` (
  `id_bloqueo` int(11) NOT NULL AUTO_INCREMENT,
  `bloqueo_numero` int(11) NOT NULL,
  PRIMARY KEY (`id_bloqueo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Lista de Numeros Bloqueados' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ticket`
--

CREATE TABLE IF NOT EXISTS `detalle_ticket` (
  `id_detalle_ticket` int(11) NOT NULL,
  `id_ticket` int(11) NOT NULL,
  `numero` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `id_sorteo` int(11) NOT NULL,
  `hora_sorteo` time NOT NULL,
  `id_zodiacal` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  KEY `id_detalle_ticket` (`id_detalle_ticket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Detalle ticket';

--
-- Volcado de datos para la tabla `detalle_ticket`
--

INSERT INTO `detalle_ticket` (`id_detalle_ticket`, `id_ticket`, `numero`, `id_sorteo`, `hora_sorteo`, `id_zodiacal`, `monto`) VALUES
(0, 124606, '047', 2, '00:00:00', 0, 15558888.22);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `loterias`
--

CREATE TABLE IF NOT EXISTS `loterias` (
  `id_loteria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_loteria` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id_loteria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Listados de Loterias' AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `loterias`
--

INSERT INTO `loterias` (`id_loteria`, `nombre_loteria`, `status`) VALUES
(1, 'CHANCE', 1),
(2, 'TRIPLE TACHIRA', 1),
(3, 'CHANCE ASTRAL', 1),
(4, 'TRIPLE ZAMORANO', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parametros`
--

CREATE TABLE IF NOT EXISTS `parametros` (
  `id_parametros` int(11) NOT NULL,
  `id_agencia` int(11) NOT NULL,
  `nombre_agencia` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `taquilla` int(11) NOT NULL,
  UNIQUE KEY `id_parametros` (`id_parametros`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `parametros`
--

INSERT INTO `parametros` (`id_parametros`, `id_agencia`, `nombre_agencia`, `taquilla`) VALUES
(1, 1, 'La Tostadita', 1);

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
-- Estructura de tabla para la tabla `sorteos`
--

CREATE TABLE IF NOT EXISTS `sorteos` (
  `id_sorteo` int(11) NOT NULL AUTO_INCREMENT,
  `id_loteria` int(11) NOT NULL,
  `nombre_sorteo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `hora_sorteo` time NOT NULL,
  `zodiacal` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  UNIQUE KEY `id_sorrteo` (`id_sorteo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `sorteos`
--

INSERT INTO `sorteos` (`id_sorteo`, `id_loteria`, `nombre_sorteo`, `hora_sorteo`, `zodiacal`, `status`) VALUES
(1, 1, 'CHANCE A 1PM', '13:00:00', 0, 1),
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
-- Volcado de datos para la tabla `status`
--

INSERT INTO `status` (`id_status`, `status_nombre`) VALUES
(0, 'Inactivo'),
(1, 'Activo'),
(2, 'Modificado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket`
--

CREATE TABLE IF NOT EXISTS `ticket` (
  `id_ticket` int(11) NOT NULL,
  `serial` int(11) NOT NULL,
  `fecha_hora` time NOT NULL,
  `taquilla` int(11) NOT NULL,
  `total_ticket` decimal(10,2) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `premiado` int(11) NOT NULL,
  `pagado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ticket';

--
-- Volcado de datos para la tabla `ticket`
--

INSERT INTO `ticket` (`id_ticket`, `serial`, `fecha_hora`, `taquilla`, `total_ticket`, `id_usuario`, `premiado`, `pagado`) VALUES
(124606, 58985561, '14:01:00', 1, 0.00, 0, 0, 0),
(124607, 58874442, '16:49:32', 1, 0.00, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_transaccional`
--

CREATE TABLE IF NOT EXISTS `ticket_transaccional` (
  `id_ticket_transaccional` int(11) NOT NULL AUTO_INCREMENT,
  `numero` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `id_sorteo` int(11) NOT NULL,
  `id_zodiacal` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_ticket_transaccional`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Ticket Transaccional' AUTO_INCREMENT=79 ;

--
-- Volcado de datos para la tabla `ticket_transaccional`
--

INSERT INTO `ticket_transaccional` (`id_ticket_transaccional`, `numero`, `id_sorteo`, `id_zodiacal`, `monto`) VALUES
(18, '569', 1, 0, 2.00),
(19, '365', 2, 0, 25.00),
(20, '321', 2, 0, 25.00),
(21, '256', 2, 0, 25.00),
(22, '45', 2, 0, 10.00),
(23, '25', 1, 0, 5.00),
(24, '25', 2, 0, 5.00),
(25, '458', 1, 0, 2.00),
(26, '458', 2, 0, 2.00),
(27, '25', 1, 0, 10.00),
(28, '25', 2, 0, 10.00),
(29, '256', 1, 0, 40.00),
(30, '256', 2, 0, 40.00),
(31, '256', 1, 0, 23.00),
(32, '256', 2, 0, 23.00),
(33, '741', 2, 0, 10.00),
(34, '125', 1, 0, 20.00),
(35, '425', 1, 0, 10.00),
(36, '45', 1, 0, 10.00),
(37, '25', 1, 0, 10.00),
(38, '125', 1, 0, 20.00),
(39, '455', 2, 0, 20.00),
(40, '455', 3, 3, 20.00),
(41, '455', 3, 4, 20.00),
(42, '124', 1, 0, 20.00),
(43, '124', 2, 0, 20.00),
(44, '124', 3, 1, 20.00),
(45, '124', 3, 2, 20.00),
(46, '124', 3, 3, 20.00),
(47, '124', 3, 4, 20.00),
(48, '124', 3, 5, 20.00),
(49, '124', 3, 6, 20.00),
(50, '124', 3, 7, 20.00),
(51, '124', 3, 8, 20.00),
(52, '124', 3, 9, 20.00),
(53, '124', 3, 10, 20.00),
(54, '124', 3, 11, 20.00),
(55, '124', 3, 12, 20.00),
(56, '122', 1, 0, 20.00),
(57, '122', 2, 0, 20.00),
(58, '120', 1, 0, 20.00),
(59, '120', 1, 0, 20.00),
(60, '12', 1, 0, 20.00),
(61, '15', 1, 0, 10.00),
(62, '14', 1, 0, 10.00),
(63, '10', 1, 0, 22.00),
(64, '10', 1, 0, 2.00),
(65, '12', 1, 0, 22.00),
(66, '125', 1, 0, 22.00),
(67, '14', 1, 0, 22.00),
(68, '125', 1, 0, 20.00),
(69, '10', 1, 0, 20.00),
(70, '10', 1, 0, 20.00),
(71, '10', 1, 0, 20.00),
(72, '10', 1, 0, 10.00),
(73, '10', 1, 0, 10.00),
(74, '125', 1, 0, 10.50),
(75, '152', 1, 0, 10.55),
(76, '155', 1, 0, 10.55),
(77, '125', 4, 0, 12.00),
(78, '125', 7, 0, 12.00);

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
  `id_status_usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `id_perfil`, `nombre_usuario`, `email_usuario`, `login_usuario`, `clave_usuario`, `id_status_usuario`) VALUES
(1, 1, 'Gerzahim Salas', 'rasce88@gmail.com', 'admin', 'admin1', 1),
(3, 3, 'Rasce Salas', 'rasce88@gmail.com', 'rasce88', '123', 1);

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
