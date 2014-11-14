<<<<<<< .mine
-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 12-11-2014 a las 15:37:30
-- Versión del servidor: 5.5.24-log
-- Versión de PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!401010 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `lottomax`
--
CREATE DATABASE `lottomax` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `lottomax`;

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
  `monto_restante` decimal(10,2) NOT NULL,
  `monto_faltante` decimal(10,2) NOT NULL,
  KEY `id_detalle_ticket` (`id_detalle_ticket`),
  KEY `FK_id_ticket` (`id_ticket`),
  KEY `FK_id_tipo_jugada` (`id_tipo_jugada`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Detalle ticket' AUTO_INCREMENT=1767455 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ticket_diario`
--

CREATE TABLE IF NOT EXISTS `detalle_ticket_diario` (
  `id_detalle_ticket_diario` int(11) NOT NULL AUTO_INCREMENT,
  `id_ticket_diario` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `numero` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `id_sorteo` int(11) NOT NULL,
  `fecha_sorteo` date NOT NULL,
  `id_zodiacal` int(11) NOT NULL,
  `id_tipo_jugada` int(11) NOT NULL DEFAULT '0',
  `monto` decimal(10,2) NOT NULL,
  `premiado` int(11) NOT NULL DEFAULT '0',
  `total_premiado` int(11) NOT NULL,
  `monto_restante` decimal(10,2) NOT NULL,
  `monto_faltante` decimal(10,2) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '1=Activo 0=Desactivado Para los cupos',
  KEY `id_detalle_ticket_diario` (`id_detalle_ticket_diario`),
  KEY `FK_id_detalle_ticket_diario` (`id_ticket_diario`),
  KEY `FK_id_tipo_jugada` (`id_tipo_jugada`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Detalle ticket diario' AUTO_INCREMENT=1768258 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dias_semana`
--

CREATE TABLE IF NOT EXISTS `dias_semana` (
  `id_dias_semana` int(11) NOT NULL,
  `nombre_dia_semana` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `abv_dia_semana` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_dias_semana`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  `incompleto` int(11) NOT NULL COMMENT '0=completo, 1=Incompleto, 2=Agotado',
  PRIMARY KEY (`id_incompletos_agotados`),
  KEY `id_sorteo` (`id_sorteo`),
  KEY `id_tipo_jugada` (`id_tipo_jugada`),
  KEY `id_zodiacal` (`id_zodiacal`),
  KEY `FK_id_tickets` (`id_ticket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='guarda numeros incompletos y agotados' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jugadas_especiales`
--

CREATE TABLE IF NOT EXISTS `jugadas_especiales` (
  `id_jugada_especial` int(11) NOT NULL AUTO_INCREMENT,
  `id_tipo_jugada_especial` int(1) NOT NULL,
  `numero_inicio` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `id_sorteos` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `numero_final` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `numero_solicitado` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `id_zodiacales` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `id_taquilla` int(1) NOT NULL,
  `id_insert_taquilla` int(11) NOT NULL,
  PRIMARY KEY (`id_jugada_especial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `loterias`
--

CREATE TABLE IF NOT EXISTS `loterias` (
  `id_loteria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_loteria` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL COMMENT '0=Inactivo, 1=Activo',
  `fecha_desde` date NOT NULL,
  `fecha_hasta` date NOT NULL,
  `id_dias_semana` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `status_especial` int(1) NOT NULL COMMENT 'Cuando se activa o desactiva por fechas',
  `bajado` int(1) NOT NULL COMMENT 'Indica si los Cambios Fueron Bajados',
  PRIMARY KEY (`id_loteria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Listados de Loterias' AUTO_INCREMENT=13 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=261 ;

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
  `tipo_comision` int(1) NOT NULL COMMENT '1= comisión por ventas brutas 2= Comisión por ganancia',
  UNIQUE KEY `id_parametros` (`id_parametros`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE IF NOT EXISTS `perfil` (
  `id_perfil` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_perfil` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_perfil`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prueba`
--

CREATE TABLE IF NOT EXISTS `prueba` (
  `id_prueba` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id_prueba`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='tabla de prueba' AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=40520 ;

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
  `id_dias_semana` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `id_tipo_sorteo` int(11) NOT NULL,
  UNIQUE KEY `id_sorrteo` (`id_sorteo`),
  KEY `FK_zodiacal` (`zodiacal`),
  KEY `FK_id_loteria` (`id_loteria`),
  KEY `turno_fk` (`id_turno`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=109 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `id_status` int(11) NOT NULL,
  `status_nombre` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

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
  `fecha_hora_pagado` datetime DEFAULT NULL,
  `taquilla_pagado` int(1) DEFAULT NULL,
  `usuario_pagado` int(1) DEFAULT NULL,
  PRIMARY KEY (`id_ticket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ticket';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_diario`
--

CREATE TABLE IF NOT EXISTS `ticket_diario` (
  `id_ticket_diario` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
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
  `taquilla_pagado` int(1) DEFAULT NULL,
  `usuario_pagado` int(1) DEFAULT NULL,
  PRIMARY KEY (`id_ticket_diario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ticket_diario';

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
  `monto_restante` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_ticket_transaccional`),
  KEY `id_taquilla` (`id_taquilla`),
  KEY `id_zodiacal` (`id_zodiacal`),
  KEY `id_sorteo` (`id_sorteo`),
  KEY `id_tipo_jugada` (`id_tipo_jugada`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Ticket Transaccional' AUTO_INCREMENT=449 ;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_sorteo`
--

CREATE TABLE IF NOT EXISTS `tipo_sorteo` (
  `id_tipo_sorteo` int(11) NOT NULL,
  `letra_tipo_sorteo` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  KEY `id_tipo_sorteo` (`id_tipo_sorteo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_jugada_especial`
--

CREATE TABLE IF NOT EXISTS `tipos_jugada_especial` (
  `id_tipo_jugada_especial` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_jugada_especial` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_tipo_jugada_especial`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=62 ;

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
-- Filtros para la tabla `detalle_ticket_diario`
--
ALTER TABLE `detalle_ticket_diario`
  ADD CONSTRAINT `detalle_ticket_diario_ibfk_1` FOREIGN KEY (`id_ticket_diario`) REFERENCES `ticket_diario` (`id_ticket_diario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_ticket_diario_ibfk_2` FOREIGN KEY (`id_tipo_jugada`) REFERENCES `tipo_jugadas` (`id_tipo_jugada`) ON DELETE CASCADE ON UPDATE CASCADE;

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
=======
-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 12-11-2014 a las 15:37:30
-- Versión del servidor: 5.5.24-log
-- Versión de PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `lottomax`
--

USE `lottomax`;

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
  `monto_restante` decimal(10,2) NOT NULL,
  `monto_faltante` decimal(10,2) NOT NULL,
  KEY `id_detalle_ticket` (`id_detalle_ticket`),
  KEY `FK_id_ticket` (`id_ticket`),
  KEY `FK_id_tipo_jugada` (`id_tipo_jugada`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Detalle ticket' AUTO_INCREMENT=1767455 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ticket_diario`
--

CREATE TABLE IF NOT EXISTS `detalle_ticket_diario` (
  `id_detalle_ticket_diario` int(11) NOT NULL AUTO_INCREMENT,
  `id_ticket_diario` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `numero` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `id_sorteo` int(11) NOT NULL,
  `fecha_sorteo` date NOT NULL,
  `id_zodiacal` int(11) NOT NULL,
  `id_tipo_jugada` int(11) NOT NULL DEFAULT '0',
  `monto` decimal(10,2) NOT NULL,
  `premiado` int(11) NOT NULL DEFAULT '0',
  `total_premiado` int(11) NOT NULL,
  `monto_restante` decimal(10,2) NOT NULL,
  `monto_faltante` decimal(10,2) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '1=Activo 0=Desactivado Para los cupos',
  KEY `id_detalle_ticket_diario` (`id_detalle_ticket_diario`),
  KEY `FK_id_detalle_ticket_diario` (`id_ticket_diario`),
  KEY `FK_id_tipo_jugada` (`id_tipo_jugada`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Detalle ticket diario' AUTO_INCREMENT=1768258 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dias_semana`
--

CREATE TABLE IF NOT EXISTS `dias_semana` (
  `id_dias_semana` int(11) NOT NULL,
  `nombre_dia_semana` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `abv_dia_semana` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_dias_semana`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  `incompleto` int(11) NOT NULL COMMENT '0=completo, 1=Incompleto, 2=Agotado',
  PRIMARY KEY (`id_incompletos_agotados`),
  KEY `id_sorteo` (`id_sorteo`),
  KEY `id_tipo_jugada` (`id_tipo_jugada`),
  KEY `id_zodiacal` (`id_zodiacal`),
  KEY `FK_id_tickets` (`id_ticket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='guarda numeros incompletos y agotados' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jugadas_especiales`
--

CREATE TABLE IF NOT EXISTS `jugadas_especiales` (
  `id_jugada_especial` int(11) NOT NULL AUTO_INCREMENT,
  `id_tipo_jugada_especial` int(1) NOT NULL,
  `numero_inicio` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `id_sorteos` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `numero_final` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `numero_solicitado` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `id_zodiacales` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `id_taquilla` int(1) NOT NULL,
  `id_insert_taquilla` int(11) NOT NULL,
  PRIMARY KEY (`id_jugada_especial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `loterias`
--

CREATE TABLE IF NOT EXISTS `loterias` (
  `id_loteria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_loteria` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL COMMENT '0=Inactivo, 1=Activo',
  `fecha_desde` date NOT NULL,
  `fecha_hasta` date NOT NULL,
  `id_dias_semana` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `status_especial` int(1) NOT NULL COMMENT 'Cuando se activa o desactiva por fechas',
  `bajado` int(1) NOT NULL COMMENT 'Indica si los Cambios Fueron Bajados',
  PRIMARY KEY (`id_loteria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Listados de Loterias' AUTO_INCREMENT=13 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=261 ;

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
  `tipo_comision` int(1) NOT NULL COMMENT '1= comisión por ventas brutas 2= Comisión por ganancia',
  UNIQUE KEY `id_parametros` (`id_parametros`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE IF NOT EXISTS `perfil` (
  `id_perfil` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_perfil` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_perfil`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prueba`
--

CREATE TABLE IF NOT EXISTS `prueba` (
  `id_prueba` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id_prueba`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='tabla de prueba' AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=40520 ;

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
  `id_dias_semana` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `id_tipo_sorteo` int(11) NOT NULL,
  UNIQUE KEY `id_sorrteo` (`id_sorteo`),
  KEY `FK_zodiacal` (`zodiacal`),
  KEY `FK_id_loteria` (`id_loteria`),
  KEY `turno_fk` (`id_turno`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=109 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `id_status` int(11) NOT NULL,
  `status_nombre` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

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
  `fecha_hora_pagado` datetime DEFAULT NULL,
  `taquilla_pagado` int(1) DEFAULT NULL,
  `usuario_pagado` int(1) DEFAULT NULL,
  PRIMARY KEY (`id_ticket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ticket';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_diario`
--

CREATE TABLE IF NOT EXISTS `ticket_diario` (
  `id_ticket_diario` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
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
  `taquilla_pagado` int(1) DEFAULT NULL,
  `usuario_pagado` int(1) DEFAULT NULL,
  PRIMARY KEY (`id_ticket_diario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ticket_diario';

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
  `monto_restante` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_ticket_transaccional`),
  KEY `id_taquilla` (`id_taquilla`),
  KEY `id_zodiacal` (`id_zodiacal`),
  KEY `id_sorteo` (`id_sorteo`),
  KEY `id_tipo_jugada` (`id_tipo_jugada`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Ticket Transaccional' AUTO_INCREMENT=449 ;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_sorteo`
--

CREATE TABLE IF NOT EXISTS `tipo_sorteo` (
  `id_tipo_sorteo` int(11) NOT NULL,
  `letra_tipo_sorteo` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  KEY `id_tipo_sorteo` (`id_tipo_sorteo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_jugada_especial`
--

CREATE TABLE IF NOT EXISTS `tipos_jugada_especial` (
  `id_tipo_jugada_especial` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_jugada_especial` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_tipo_jugada_especial`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=62 ;

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
-- Filtros para la tabla `detalle_ticket_diario`
--
ALTER TABLE `detalle_ticket_diario`
  ADD CONSTRAINT `detalle_ticket_diario_ibfk_1` FOREIGN KEY (`id_ticket_diario`) REFERENCES `ticket_diario` (`id_ticket_diario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_ticket_diario_ibfk_2` FOREIGN KEY (`id_tipo_jugada`) REFERENCES `tipo_jugadas` (`id_tipo_jugada`) ON DELETE CASCADE ON UPDATE CASCADE;

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
>>>>>>> .r429
