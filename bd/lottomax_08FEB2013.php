<?php
/**
 * Export to PHP Array plugin for PHPMyAdmin
 * @version 0.2b
 */

//
// Database "lottomax"
//

);

// lottomax.detalle_ticket
$detalle_ticket = array(
  array('id_detalle_ticket'=>'0','id_ticket'=>'124606','numero'=>'047','id_sorteo'=>'2','hora_sorteo'=>'00:00:00','id_zodiacal'=>'0','monto'=>'15558888.22')
);

// lottomax.parametros
$parametros = array(
  array('id_parametros'=>'1','id_agencia'=>'1','nombre_agencia'=>'La Tostadita','taquilla'=>'1')
);

// lottomax.perfil
$perfil = array(
  array('id_perfil'=>'1','nombre_perfil'=>'Administrador'),
  array('id_perfil'=>'2','nombre_perfil'=>'Banquero'),
  array('id_perfil'=>'3','nombre_perfil'=>'Intermediario'),
  array('id_perfil'=>'4','nombre_perfil'=>'Vendedor')
);

// lottomax.sorteos
$sorteos = array(
  array('id_sorteo'=>'1','nombre_sorteo'=>'CHANCE A 1PM','hora_sorteo'=>'18:38:40','zodiacal'=>'0','estatus'=>'1'),
  array('id_sorteo'=>'2','nombre_sorteo'=>'CHANCE B 1PM','hora_sorteo'=>'18:38:34','zodiacal'=>'0','estatus'=>'1'),
  array('id_sorteo'=>'3','nombre_sorteo'=>'CHANCE ASTRAL 1PM','hora_sorteo'=>'18:38:28','zodiacal'=>'1','estatus'=>'1')
);

// lottomax.status
$status = array(
  array('id_status'=>'0','status_nombre'=>'Inactivo'),
  array('id_status'=>'1','status_nombre'=>'Activo'),
  array('id_status'=>'2','status_nombre'=>'Modificado')
);

// lottomax.ticket
$ticket = array(
  array('id_ticket'=>'124606','serial'=>'58985561','fecha_hora'=>'14:01:00','taquilla'=>'1','total_ticket'=>'0.00','id_usuario'=>'0','premiado'=>'0','pagado'=>'0'),
  array('id_ticket'=>'124607','serial'=>'58874442','fecha_hora'=>'16:49:32','taquilla'=>'1','total_ticket'=>'0.00','id_usuario'=>'0','premiado'=>'0','pagado'=>'0')
);

// lottomax.ticket_transaccional
$ticket_transaccional = array(
  array('id_ticket_transaccional'=>'18','numero'=>'569','id_sorteo'=>'1','id_zodiacal'=>'0','monto'=>'2.00'),
  array('id_ticket_transaccional'=>'19','numero'=>'365','id_sorteo'=>'2','id_zodiacal'=>'0','monto'=>'25.00'),
  array('id_ticket_transaccional'=>'20','numero'=>'321','id_sorteo'=>'2','id_zodiacal'=>'0','monto'=>'25.00'),
  array('id_ticket_transaccional'=>'21','numero'=>'256','id_sorteo'=>'2','id_zodiacal'=>'0','monto'=>'25.00'),
  array('id_ticket_transaccional'=>'22','numero'=>'45','id_sorteo'=>'2','id_zodiacal'=>'0','monto'=>'10.00'),
  array('id_ticket_transaccional'=>'23','numero'=>'25','id_sorteo'=>'1','id_zodiacal'=>'0','monto'=>'5.00'),
  array('id_ticket_transaccional'=>'24','numero'=>'25','id_sorteo'=>'2','id_zodiacal'=>'0','monto'=>'5.00'),
  array('id_ticket_transaccional'=>'25','numero'=>'458','id_sorteo'=>'1','id_zodiacal'=>'0','monto'=>'2.00'),
  array('id_ticket_transaccional'=>'26','numero'=>'458','id_sorteo'=>'2','id_zodiacal'=>'0','monto'=>'2.00'),
  array('id_ticket_transaccional'=>'27','numero'=>'25','id_sorteo'=>'1','id_zodiacal'=>'0','monto'=>'10.00'),
  array('id_ticket_transaccional'=>'28','numero'=>'25','id_sorteo'=>'2','id_zodiacal'=>'0','monto'=>'10.00'),
  array('id_ticket_transaccional'=>'29','numero'=>'256','id_sorteo'=>'1','id_zodiacal'=>'0','monto'=>'40.00'),
  array('id_ticket_transaccional'=>'30','numero'=>'256','id_sorteo'=>'2','id_zodiacal'=>'0','monto'=>'40.00'),
  array('id_ticket_transaccional'=>'31','numero'=>'256','id_sorteo'=>'1','id_zodiacal'=>'0','monto'=>'23.00'),
  array('id_ticket_transaccional'=>'32','numero'=>'256','id_sorteo'=>'2','id_zodiacal'=>'0','monto'=>'23.00'),
  array('id_ticket_transaccional'=>'33','numero'=>'741','id_sorteo'=>'2','id_zodiacal'=>'0','monto'=>'10.00'),
  array('id_ticket_transaccional'=>'34','numero'=>'125','id_sorteo'=>'1','id_zodiacal'=>'0','monto'=>'20.00'),
  array('id_ticket_transaccional'=>'35','numero'=>'425','id_sorteo'=>'1','id_zodiacal'=>'0','monto'=>'10.00'),
  array('id_ticket_transaccional'=>'36','numero'=>'45','id_sorteo'=>'1','id_zodiacal'=>'0','monto'=>'10.00'),
  array('id_ticket_transaccional'=>'37','numero'=>'25','id_sorteo'=>'1','id_zodiacal'=>'0','monto'=>'10.00'),
  array('id_ticket_transaccional'=>'38','numero'=>'125','id_sorteo'=>'1','id_zodiacal'=>'0','monto'=>'20.00')
);

// lottomax.usuario
$usuario = array(
  array('id_usuario'=>'1','id_perfil'=>'1','nombre_usuario'=>'Gerzahim Salas','email_usuario'=>'rasce88@gmail.com','login_usuario'=>'admin','clave_usuario'=>'admin1','id_status_usuario'=>'1'),
  array('id_usuario'=>'3','id_perfil'=>'3','nombre_usuario'=>'Rasce Salas','email_usuario'=>'rasce88@gmail.com','login_usuario'=>'rasce88','clave_usuario'=>'123','id_status_usuario'=>'1')
);

// lottomax.zodiacal
$zodiacal = array(
  array('Id_zodiacal'=>'0','nombre_zodiacal'=>'No Zodiacal','pre_zodiacal'=>''),
  array('Id_zodiacal'=>'1','nombre_zodiacal'=>'Acuario','pre_zodiacal'=>'Acu'),
  array('Id_zodiacal'=>'2','nombre_zodiacal'=>'Aries','pre_zodiacal'=>'Ari'),
  array('Id_zodiacal'=>'3','nombre_zodiacal'=>'Cancer','pre_zodiacal'=>'Can'),
  array('Id_zodiacal'=>'4','nombre_zodiacal'=>'Capricornio','pre_zodiacal'=>'Cap'),
  array('Id_zodiacal'=>'5','nombre_zodiacal'=>'Escorpio','pre_zodiacal'=>'Esc'),
  array('Id_zodiacal'=>'6','nombre_zodiacal'=>'Geminis','pre_zodiacal'=>'Gem'),
  array('Id_zodiacal'=>'7','nombre_zodiacal'=>'Leo','pre_zodiacal'=>'Leo'),
  array('Id_zodiacal'=>'8','nombre_zodiacal'=>'Libra','pre_zodiacal'=>'Lib'),
  array('Id_zodiacal'=>'9','nombre_zodiacal'=>'Piscis','pre_zodiacal'=>'Pis'),
  array('Id_zodiacal'=>'10','nombre_zodiacal'=>'Sagitario','pre_zodiacal'=>'Sag'),
  array('Id_zodiacal'=>'11','nombre_zodiacal'=>'Tauro','pre_zodiacal'=>'Tau'),
  array('Id_zodiacal'=>'12','nombre_zodiacal'=>'Virgo','pre_zodiacal'=>'Vir')
);
