-- phpMyAdmin SQL Dump
-- version 4.2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 10, 2014 at 03:17 PM
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

--
-- Dumping data for table `cupo_general`
--

INSERT INTO `cupo_general` (`id_cupo_general`, `monto_cupo`, `id_tipo_jugada`) VALUES
(1, 30, 1),
(2, 50, 2),
(3, 1, 3),
(4, 5, 4);

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

--
-- Dumping data for table `impresora_taquillas`
--

INSERT INTO `impresora_taquillas` (`id_impresora_taquillas`, `id_taquilla`, `nombre_vendedor_ticket`, `cortar_ticket`, `lineas_saltar_antes`, `lineas_saltar_despues`, `ver_numeros_incompletos`, `ver_numeros_agotados`) VALUES
(2, 2, 1, 0, 0, 0, 1, 1),
(3, 1, 1, 0, 0, 0, 1, 1),
(9, 3, 1, 0, 0, 0, 1, 1);

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

--
-- Dumping data for table `perfil`
--

INSERT INTO `perfil` (`id_perfil`, `nombre_perfil`) VALUES
(1, 'Administrador'),
(2, 'Banquero'),
(3, 'Intermediario'),
(4, 'Vendedor');

--
-- Dumping data for table `prueba`
--

INSERT INTO `prueba` (`id_prueba`, `date`) VALUES
(1, '2014-02-20 14:06:50'),
(2, '2014-02-20 14:19:39'),
(3, '2014-02-20 14:19:53');

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

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id_status`, `status_nombre`) VALUES
(0, 'Inactivo'),
(1, 'Activo'),
(2, 'Modificado');

--
-- Dumping data for table `taquillas`
--

INSERT INTO `taquillas` (`id_taquilla`, `numero_taquilla`, `status`) VALUES
(1, '01', 1),
(2, '02', 1),
(3, '03', 1),
(4, '04', 1);

--
-- Dumping data for table `tipo_jugadas`
--

INSERT INTO `tipo_jugadas` (`id_tipo_jugada`, `nombre_jugada`, `zodiacal`, `triple`, `status`) VALUES
(1, 'Triple', 0, 1, 1),
(2, 'Terminal', 0, 0, 1),
(3, 'Tripletazo', 1, 1, 1),
(4, 'Terminalazo', 1, 0, 1),
(5, 'Aproximacion', 0, 2, 1);

--
-- Dumping data for table `tipo_sorteo`
--

INSERT INTO `tipo_sorteo` (`id_tipo_sorteo`, `letra_tipo_sorteo`) VALUES
(1, 'A'),
(2, 'B'),
(3, 'C'),
(4, 'Z');

--
-- Dumping data for table `turno`
--

INSERT INTO `turno` (`id_turno`, `nom_turno`, `pre_turno`) VALUES
(1, 'Manana', 'M'),
(2, 'Tarde', 'T'),
(3, 'Noche', 'N');

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `id_perfil`, `nombre_usuario`, `email_usuario`, `login_usuario`, `clave_usuario`, `id_status_usuario`) VALUES
(1, 1, 'Gerzahim Salas', 'rasce88@gmail.com', 'admin', 'admin1', 1),
(3, 1, 'Yumijaika Oduber', 'rasce88@gmail.com', 'yumi', '1234', 1),
(4, 4, 'Yiserly Fagundez', 'yiserly_17@hotmail.com', 'yiserly', '1234', 1),
(5, 4, 'Jasmin Burgo', 'jasmin_burgo@hotmail.com', 'jasmin', '1234', 1),
(6, 4, 'Daryeli Barrios', 'daryeli_barrios@hotmail.com', 'daryeli', '1234', 1);

--
-- Dumping data for table `usuarios_taquillas`
--

INSERT INTO `usuarios_taquillas` (`id_usuario_taquilla`, `id_usuario`, `id_taquilla`, `time_ping`) VALUES
(570, 4, 3, '2014-05-16 08:23:57'),
(604, 5, 2, '2014-05-29 11:58:40'),
(727, 1, 1, '2014-09-10 00:27:55');

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
