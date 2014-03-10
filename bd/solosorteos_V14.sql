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
  `id_tipo_sorteo` int(11) NOT NULL COMMENT 'Letra del Sorteo',
  UNIQUE KEY `id_sorrteo` (`id_sorteo`),
  KEY `FK_zodiacal` (`zodiacal`),
  KEY `FK_id_loteria` (`id_loteria`),
  KEY `turno_fk` (`id_turno`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=106 ;

--
-- Volcado de datos para la tabla `sorteos`
--

INSERT INTO `sorteos` (`id_sorteo`, `id_loteria`, `nombre_sorteo`, `hora_sorteo`, `id_turno`, `zodiacal`, `tradicional`, `status`, `id_tipo_sorteo`) VALUES
(1, 1, 'ZAMORANO A 12PM', '12:00:00', 1, 0, 0, 0, 1),
(2, 1, 'ZAMORANO C 12PM', '12:00:00', 1, 0, 0, 0, 3),
(3, 1, 'ASTRO ZAMORANO 12PM', '12:00:00', 1, 1, 0, 0, 4),
(4, 2, 'TACHIRA A 12PM', '12:00:00', 1, 0, 1, 1, 1),
(5, 2, 'TACHIRA B 12PM', '12:00:00', 1, 0, 1, 1, 2),
(6, 2, 'TACHIRA C 12PM', '12:00:00', 1, 0, 0, 1, 3),
(7, 2, 'SIGNO TACHIRA 12PM', '12:00:00', 1, 1, 0, 1, 4),
(8, 3, 'ZODIACAL A 12:45PM', '12:45:00', 1, 0, 1, 1, 1),
(9, 3, 'ZODIACAL B 12:45PM', '12:45:00', 1, 0, 1, 1, 2),
(10, 3, 'ZODIACAL SIGNO 12:45PM', '12:45:00', 1, 1, 0, 1, 4),
(11, 4, 'LEON A 12:30PM', '12:30:00', 1, 0, 1, 1, 1),
(12, 4, 'LEON B 12:30PM', '12:30:00', 1, 0, 1, 1, 2),
(13, 4, 'LEON C 12:30PM', '12:30:00', 1, 0, 0, 1, 3),
(14, 4, 'TRIPLETON 12:30PM', '12:30:00', 1, 1, 0, 1, 4),
(15, 6, 'ZULIA A 12:45PM', '12:45:00', 1, 0, 1, 1, 1),
(16, 6, 'ZULIA B 12:45PM', '12:45:00', 1, 0, 1, 1, 2),
(17, 6, 'ZULIA C 12:45PM', '12:45:00', 1, 0, 0, 1, 3),
(18, 6, 'ZODIACO DEL ZULIA 12:45PM', '12:45:00', 1, 1, 0, 1, 4),
(19, 5, 'CHANCE A 1PM', '13:00:00', 1, 0, 1, 1, 1),
(20, 5, 'CHANCE B 1PM', '13:00:00', 1, 0, 1, 1, 2),
(21, 5, 'CHANCE C 1PM', '13:00:00', 1, 0, 0, 1, 3),
(22, 5, 'ASTRAL 1PM', '13:00:00', 1, 1, 0, 1, 4),
(23, 1, 'ZAMORANO A 4PM', '16:00:00', 2, 0, 0, 0, 1),
(24, 1, 'ZAMORANO C 4PM', '16:00:00', 2, 0, 0, 0, 3),
(25, 1, 'ASTRO ZAMORANO 4PM', '16:00:00', 2, 1, 0, 0, 4),
(26, 4, 'LEON A 4:30PM', '16:30:00', 2, 0, 1, 1, 1),
(27, 4, 'LEON B 4:30PM', '16:30:00', 2, 0, 1, 1, 2),
(28, 4, 'LEON C 4:30PM', '16:30:00', 2, 0, 0, 1, 3),
(29, 4, 'TRIPLETON 4:30PM', '16:30:00', 2, 1, 0, 1, 4),
(30, 5, 'CHANCE A 4:30PM', '16:30:00', 2, 0, 1, 1, 1),
(31, 5, 'CHANCE B 4:30PM', '16:30:00', 2, 0, 1, 1, 2),
(32, 5, 'CHANCE C 4:30PM', '16:30:00', 2, 0, 0, 1, 3),
(33, 5, 'ASTRAL 4:30PM', '16:30:00', 2, 1, 0, 1, 4),
(34, 3, 'ZODIACAL A 4:45PM', '16:45:00', 2, 0, 1, 1, 1),
(35, 3, 'ZODIACAL B 4:45PM', '16:45:00', 2, 0, 1, 1, 2),
(36, 3, 'ZODIACAL SIGNO 4:45PM', '16:45:00', 2, 1, 0, 1, 4),
(37, 6, 'ZULIA A 4:45PM', '16:45:00', 2, 0, 1, 1, 1),
(38, 6, 'ZULIA B 4:45PM', '16:45:00', 2, 0, 1, 1, 2),
(39, 6, 'ZULIA C 4:45PM', '16:45:00', 2, 0, 0, 1, 3),
(40, 6, 'ZODIACO DEL ZULIA 4:45PM', '16:45:00', 2, 1, 0, 1, 4),
(41, 2, 'TACHIRA A 5PM', '17:00:00', 2, 0, 1, 1, 1),
(42, 2, 'TACHIRA B 5PM', '17:00:00', 2, 0, 1, 1, 2),
(43, 2, 'TACHIRA C 5PM', '17:00:00', 2, 0, 0, 1, 3),
(44, 2, 'SIGNO TACHIRA 5PM', '17:00:00', 2, 1, 0, 1, 4),
(45, 1, 'ZAMORANO A 7PM', '19:00:00', 3, 0, 0, 0, 1),
(46, 1, 'ZAMORANO C 7PM', '19:00:00', 3, 0, 0, 0, 3),
(47, 1, 'ASTRO ZAMORANO 7PM', '19:00:00', 3, 1, 0, 0, 4),
(48, 3, 'ZODIACAL A 7:45PM', '19:45:00', 3, 0, 1, 1, 1),
(49, 3, 'ZODIACAL B 7:45PM', '19:45:00', 3, 0, 1, 1, 2),
(50, 3, 'ZODIACAL SIGNO 7:45PM', '19:45:00', 3, 1, 0, 1, 4),
(51, 4, 'LEON A 7:30PM', '19:30:00', 3, 0, 1, 1, 1),
(52, 4, 'LEON B 7:30PM', '19:30:00', 3, 0, 1, 1, 2),
(53, 4, 'LEON C 7:30PM', '19:30:00', 3, 0, 0, 1, 3),
(54, 4, 'TRIPLETON 7:30PM', '19:30:00', 3, 1, 0, 1, 4),
(55, 6, 'ZULIA A 7:45PM', '19:45:00', 3, 0, 1, 1, 1),
(56, 6, 'ZULIA B 7:45PM', '19:45:00', 3, 0, 1, 1, 2),
(57, 6, 'ZULIA C 7:45PM', '19:45:00', 3, 0, 0, 1, 3),
(58, 6, 'ZODIACO DEL ZULIA 7:45PM', '19:45:00', 3, 1, 0, 1, 4),
(59, 5, 'CHANCE A 8PM', '20:00:00', 3, 0, 1, 1, 1),
(60, 5, 'CHANCE B 8PM', '20:00:00', 3, 0, 1, 1, 2),
(61, 5, 'CHANCE C 8PM', '20:00:00', 3, 0, 0, 1, 3),
(62, 5, 'ASTRAL 8PM', '20:00:00', 3, 1, 0, 1, 4),
(63, 2, 'TACHIRA A 9PM', '21:00:00', 3, 0, 1, 1, 1),
(64, 2, 'TACHIRA B 9PM', '21:00:00', 3, 0, 1, 1, 2),
(65, 2, 'TACHIRA C 9PM', '21:00:00', 3, 0, 0, 1, 3),
(66, 2, 'SIGNO TACHIRA 9PM', '21:00:00', 3, 1, 0, 1, 4),
(67, 7, 'TRIPLEMANIA A 1PM', '13:00:00', 1, 0, 0, 1, 1),
(68, 7, 'TRIPLEMANIA B 1PM', '13:00:00', 1, 0, 0, 1, 2),
(69, 7, 'TRIPLEMANIA C 1PM', '13:00:00', 1, 0, 0, 1, 3),
(70, 7, 'MANIA ZODIACAL 1PM', '13:00:00', 1, 1, 0, 1, 4),
(71, 7, 'TRIPLEMANIA A 4:30PM', '16:30:00', 2, 0, 0, 1, 1),
(72, 7, 'TRIPLEMANIA B 4:30PM', '16:30:00', 2, 0, 0, 1, 2),
(73, 7, 'TRIPLEMANIA C 4:30PM', '16:30:00', 2, 0, 0, 1, 3),
(74, 7, 'MANIA ZODIACAL 4:30PM', '16:30:00', 2, 1, 0, 1, 4),
(75, 7, 'TRIPLEMANIA A 7:45PM', '19:45:00', 3, 0, 0, 1, 1),
(76, 7, 'TRIPLEMANIA B 7:45PM', '19:45:00', 3, 0, 0, 1, 2),
(77, 7, 'TRIPLEMANIA C 7:45PM', '19:45:00', 3, 0, 0, 1, 3),
(78, 7, 'MANIA ZODIACAL 7:45PM', '19:45:00', 3, 1, 0, 1, 4),
(79, 8, 'MULTITRIPLE A 12:40PM', '12:40:00', 1, 0, 0, 1, 1),
(80, 8, 'MULTITRIPLE B 12:40PM', '12:40:00', 1, 0, 0, 1, 2),
(81, 8, 'MULTITRIPLE C 12:40PM', '12:40:00', 1, 0, 0, 1, 3),
(82, 8, 'MULTITRIPLE A 4:40PM', '16:40:00', 2, 0, 0, 1, 1),
(83, 8, 'MULTITRIPLE B 4:40PM', '16:40:00', 2, 0, 0, 1, 2),
(84, 8, 'MULTITRIPLE C 4:40PM', '16:40:00', 2, 0, 0, 1, 3),
(85, 8, 'MULTISIGNO 12:40PM', '12:40:00', 1, 1, 0, 1, 4),
(86, 8, 'MULTISIGNO 4:40PM', '16:40:00', 2, 1, 0, 1, 4),
(87, 8, 'MULTITRIPLE A 7:40PM', '19:40:00', 3, 0, 0, 1, 1),
(88, 8, 'MULTITRIPLE B 7:40PM', '19:40:00', 3, 0, 0, 1, 2),
(89, 8, 'MULTITRIPLE C 7:40PM', '19:40:00', 3, 0, 0, 1, 3),
(90, 8, 'MULTISIGNO 7:40PM', '19:40:00', 3, 1, 0, 1, 4),
(91, 9, 'TRILLONARIO A 1:10PM', '13:10:00', 1, 0, 0, 1, 1),
(92, 9, 'TRILLONARIO B 1:10PM', '13:10:00', 1, 0, 0, 1, 2),
(93, 9, 'TRILLONARIO C 1:10PM', '13:10:00', 1, 0, 0, 1, 3),
(94, 9, 'TRILLON ZODIACAL 1:10PM', '13:10:00', 1, 1, 0, 1, 4),
(95, 9, 'TRILLONARIO A 4:40PM', '16:40:00', 2, 0, 0, 1, 1),
(96, 9, 'TRILLONARIO B 4:40PM', '16:40:00', 2, 0, 0, 1, 2),
(97, 9, 'TRILLONARIO C 4:40PM', '16:40:00', 2, 0, 0, 1, 3),
(98, 9, 'TRILLON ZODIACAL 4:40PM', '16:40:00', 2, 1, 0, 1, 4),
(99, 9, 'TRILLONARIO A 7:35PM', '19:35:00', 3, 0, 0, 1, 1),
(100, 9, 'TRILLONARIO B 7:35PM', '19:35:00', 3, 0, 0, 1, 2),
(101, 9, 'TRILLONARIO C 7:35PM', '19:35:00', 3, 0, 0, 1, 3),
(102, 9, 'TRILLON ZODIACAL 7:35PM', '19:35:00', 3, 1, 0, 1, 4),
(103, 3, 'ZODIACAL C 12:45PM', '12:45:00', 1, 0, 0, 1, 3),
(104, 3, 'ZODIACAL C 4:45PM', '16:45:00', 2, 0, 0, 1, 3),
(105, 3, 'ZODIACAL C 7:45PM', '19:45:00', 3, 0, 0, 1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_sorteo`
--

CREATE TABLE IF NOT EXISTS `tipo_sorteo` (
  `id_tipo_sorteo` int(11) NOT NULL,
  `letra_tipo_sorteo` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  KEY `id_tipo_sorteo` (`id_tipo_sorteo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_sorteo`
--

INSERT INTO `tipo_sorteo` (`id_tipo_sorteo`, `letra_tipo_sorteo`) VALUES
(1, 'A'),
(2, 'B'),
(3, 'C'),
(4, 'Z');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `sorteos`
--
ALTER TABLE `sorteos`
  ADD CONSTRAINT `FK_id_loteria` FOREIGN KEY (`id_loteria`) REFERENCES `loterias` (`id_loteria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_zodiacal` FOREIGN KEY (`zodiacal`) REFERENCES `zodiacal` (`Id_zodiacal`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `turno_fk` FOREIGN KEY (`id_turno`) REFERENCES `turno` (`id_turno`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
