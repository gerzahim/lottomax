-- phpMyAdmin SQL Dump
-- version 4.2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 10, 2014 at 03:11 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `detalle_ticket`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Detalle ticket' AUTO_INCREMENT=1316518 ;

-- --------------------------------------------------------

--
-- Table structure for table `dias_semana`
--

CREATE TABLE IF NOT EXISTS `dias_semana` (
  `id_dias_semana` int(11) NOT NULL,
  `nombre_dia_semana` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `abv_dia_semana` varchar(4) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `perfil`
--

CREATE TABLE IF NOT EXISTS `perfil` (
`id_perfil` int(11) NOT NULL,
  `nombre_perfil` varchar(20) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `prueba`
--

CREATE TABLE IF NOT EXISTS `prueba` (
`id_prueba` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='tabla de prueba' AUTO_INCREMENT=4 ;

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

-- --------------------------------------------------------

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17509 ;

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

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `id_status` int(11) NOT NULL,
  `status_nombre` varchar(15) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `taquillas`
--

CREATE TABLE IF NOT EXISTS `taquillas` (
`id_taquilla` int(11) NOT NULL,
  `numero_taquilla` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL COMMENT ' 0=Inactivo, 1=Activo'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ticket';

-- --------------------------------------------------------

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

-- --------------------------------------------------------

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

-- --------------------------------------------------------

--
-- Table structure for table `tipo_sorteo`
--

CREATE TABLE IF NOT EXISTS `tipo_sorteo` (
  `id_tipo_sorteo` int(11) NOT NULL,
  `letra_tipo_sorteo` varchar(1) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `turno`
--

CREATE TABLE IF NOT EXISTS `turno` (
  `id_turno` int(1) NOT NULL,
  `nom_turno` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `pre_turno` enum('M','T','N') COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Turneo de los Sorteos';

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
-- Indexes for table `detalle_ticket`
--
ALTER TABLE `detalle_ticket`
 ADD KEY `id_detalle_ticket` (`id_detalle_ticket`), ADD KEY `FK_id_ticket` (`id_ticket`), ADD KEY `FK_id_tipo_jugada` (`id_tipo_jugada`);

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
-- Indexes for table `resultados`
--
ALTER TABLE `resultados`
 ADD PRIMARY KEY (`id_resultados`), ADD KEY `id_sorteo` (`id_sorteo`), ADD KEY `zodiacal` (`zodiacal`);

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
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
 ADD PRIMARY KEY (`id_ticket`);

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
-- AUTO_INCREMENT for table `detalle_ticket`
--
ALTER TABLE `detalle_ticket`
MODIFY `id_detalle_ticket` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1316518;
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
-- AUTO_INCREMENT for table `resultados`
--
ALTER TABLE `resultados`
MODIFY `id_resultados` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17509;
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
-- Constraints for table `detalle_ticket`
--
ALTER TABLE `detalle_ticket`
ADD CONSTRAINT `detalle_ticket_ibfk_1` FOREIGN KEY (`id_ticket`) REFERENCES `ticket` (`id_ticket`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `detalle_ticket_ibfk_2` FOREIGN KEY (`id_tipo_jugada`) REFERENCES `tipo_jugadas` (`id_tipo_jugada`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Constraints for table `resultados`
--
ALTER TABLE `resultados`
ADD CONSTRAINT `resultados_ibfk_1` FOREIGN KEY (`id_sorteo`) REFERENCES `sorteos` (`id_sorteo`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `resultados_ibfk_2` FOREIGN KEY (`zodiacal`) REFERENCES `zodiacal` (`Id_zodiacal`) ON DELETE CASCADE ON UPDATE CASCADE;

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
