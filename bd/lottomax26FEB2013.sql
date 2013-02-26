-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 26-02-2013 a las 14:52:20
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
  `numero` int(11) NOT NULL,
  `id_sorteo` int(11) NOT NULL DEFAULT '0',
  `monto_cupo` int(11) NOT NULL COMMENT '0=Numero Bloqueado o Agotado',
  `id_tipo_jugada` int(11) NOT NULL,
  `id_zodiacal` int(11) NOT NULL,
  `fecha_desde` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `fecha_hasta` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_cupo_especial`),
  KEY `FK_id_sorteo` (`id_sorteo`),
  KEY `id_tipo_jugada` (`id_tipo_jugada`),
  KEY `id_zodiacal` (`id_zodiacal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `cupo_especial`
--

INSERT INTO `cupo_especial` (`id_cupo_especial`, `numero`, `id_sorteo`, `monto_cupo`, `id_tipo_jugada`, `id_zodiacal`, `fecha_desde`, `fecha_hasta`) VALUES
(1, 313, 3, 500, 3, 9, '2013-02-26 04:30:00', '2013-02-28 04:30:00'),
(2, 147, 2, 15, 1, 0, '2013-02-21 09:00:00', '2013-11-01 08:59:59'),
(3, 148, 2, 0, 1, 0, '2013-02-21 09:00:00', '2013-11-01 08:59:59');

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
(1, 222, 1),
(2, 100, 2),
(3, 555, 3),
(4, 80, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ticket`
--

CREATE TABLE IF NOT EXISTS `detalle_ticket` (
  `id_detalle_ticket` int(11) NOT NULL DEFAULT '0',
  `id_ticket` int(11) NOT NULL,
  `numero` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `id_sorteo` int(11) NOT NULL,
  `hora_sorteo` time NOT NULL,
  `id_zodiacal` int(11) NOT NULL,
  `id_tipo_jugada` int(11) NOT NULL DEFAULT '0',
  `monto` decimal(10,2) NOT NULL,
  KEY `id_detalle_ticket` (`id_detalle_ticket`),
  KEY `FK_id_ticket` (`id_ticket`),
  KEY `FK_id_tipo_jugada` (`id_tipo_jugada`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Detalle ticket';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incompletos_agotados`
--

CREATE TABLE IF NOT EXISTS `incompletos_agotados` (
  `id_incompletos_agotados` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `numero` int(11) NOT NULL,
  `id_sorteo` int(11) NOT NULL,
  `id_tipo_jugada` int(11) NOT NULL,
  `id_zodiacal` int(11) NOT NULL,
  `monto_restante` int(11) NOT NULL,
  `incompleto` int(11) NOT NULL COMMENT '0=completo, 1=Incompleto, 2=Agotado',
  PRIMARY KEY (`id_incompletos_agotados`),
  KEY `id_sorteo` (`id_sorteo`),
  KEY `id_tipo_jugada` (`id_tipo_jugada`),
  KEY `id_zodiacal` (`id_zodiacal`)
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `numeros_jugados`
--

INSERT INTO `numeros_jugados` (`id_numero_jugados`, `fecha`, `numero`, `id_sorteo`, `id_tipo_jugada`, `id_zodiacal`, `monto_restante`) VALUES
(1, '2013-02-22', 222, 1, 1, 0, 30),
(2, '2013-02-22', 333, 1, 1, 0, 30);

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
(4, 120, 4, 1),
(5, 30, 5, 1);

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
-- Volcado de datos para la tabla `sorteos`
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
  `pagado` int(11) NOT NULL,
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
  `monto_restante` int(11) NOT NULL,
  `incompleto` int(11) NOT NULL COMMENT '0=completo, 1=Incompleto, 2=Agotado',
  `monto` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_ticket_transaccional`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Ticket Transaccional' AUTO_INCREMENT=79 ;

--
-- Volcado de datos para la tabla `ticket_transaccional`
--

INSERT INTO `ticket_transaccional` (`id_ticket_transaccional`, `numero`, `id_sorteo`, `id_zodiacal`, `id_tipo_jugada`, `monto_restante`, `incompleto`, `monto`) VALUES
(18, '569', 1, 0, 0, 0, 0, 2.00),
(19, '365', 2, 0, 0, 0, 0, 25.00),
(75, '152', 1, 0, 0, 0, 0, 10.55),
(76, '155', 1, 0, 0, 0, 0, 10.55),
(77, '125', 4, 0, 0, 0, 0, 12.00),
(78, '125', 7, 0, 0, 0, 0, 12.00);

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
  ADD CONSTRAINT `FK_id_ticket` FOREIGN KEY (`id_ticket`) REFERENCES `ticket` (`id_ticket`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_id_tipo_jugada` FOREIGN KEY (`id_tipo_jugada`) REFERENCES `tipo_jugadas` (`id_tipo_jugada`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `incompletos_agotados`
--
ALTER TABLE `incompletos_agotados`
  ADD CONSTRAINT `incompletos_agotados_ibfk_1` FOREIGN KEY (`id_sorteo`) REFERENCES `sorteos` (`id_sorteo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `incompletos_agotados_ibfk_2` FOREIGN KEY (`id_tipo_jugada`) REFERENCES `tipo_jugadas` (`id_tipo_jugada`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `incompletos_agotados_ibfk_3` FOREIGN KEY (`id_zodiacal`) REFERENCES `tipo_jugadas` (`id_tipo_jugada`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Filtros para la tabla `sorteos`
--
ALTER TABLE `sorteos`
  ADD CONSTRAINT `FK_id_loteria` FOREIGN KEY (`id_loteria`) REFERENCES `loterias` (`id_loteria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_zodiacal` FOREIGN KEY (`zodiacal`) REFERENCES `zodiacal` (`Id_zodiacal`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `FK_id_perfil` FOREIGN KEY (`id_perfil`) REFERENCES `perfil` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
