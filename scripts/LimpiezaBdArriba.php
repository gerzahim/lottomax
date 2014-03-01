<?php
/**
 * Archivo , utilizado como script para limpiar la BD Online
 * @package LimpiezaBdArriba.php
 * @author Gerzahim Salas. - <rasce88@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Febrero - 2014
 */


//Creamos la conexión a las distintas base de datos, la de arriba y las de abajo.

$conexion_abajo = mysql_connect("localhost" , "root" , "secreta");
mysql_select_db("lottomax",$conexion_abajo);


$conexion_arriba = mysql_connect("sql3.freesqldatabase.com:3306" , "sql331522" , "gI4%hG8%",true);
mysql_select_db("sql331522",$conexion_arriba);

$ano=date('Y');
$mes=date('m');
$dia_hoy=date('d');
$dia_ayer=$dia_hoy-1;
//completando con Cero a la Izquierda
$dia_ayer=str_pad($dia_ayer, 2, "0", STR_PAD_LEFT);
$dia_antesayr=$dia_hoy-2;
//completando con Cero a la Izquierda
$dia_antesayr=str_pad($dia_antesayr, 2, "0", STR_PAD_LEFT);

if($dia_hoy == '01'){
	if($mes == '01'){
		$mes1='12';
	}else{
		$mes1=$mes-1;
		//completando con Cero a la Izquierda
		$mes1=str_pad($mes1, 2, "0", STR_PAD_LEFT);
	}
	
	if($mes1=='01' || $mes1=='03' || $mes1=='05' || $mes1=='07' || $mes1=='08' || $mes1=='10' || $mes1=='12'){
		$dia_ayer='31';
	}else if($mes1=='04' || $mes1=='06' || $mes1=='09' || $mes1=='11'){
		$dia_ayer='30';
	}else{
		//Es Febrero
		$dia_ayer='28';
	}
	
	
}else{
	$mes1=$mes;
}

$fecha_hoy=$ano."-".$mes."-".$dia_hoy;
$fecha_ayer=$ano."-".$mes1."-".$dia_ayer;
$fecha_antesayr=$ano."-".$mes1."-".$dia_antesayr;


//Sql para borrar la tabla de ticket y por estar relacionados borra los detalles_ticket asociados
// Borra Todo los  tickets que no sean de hoy, ayer ni antes de ayer.
$sql = "DELETE FROM `ticket` WHERE fecha_hora NOT LIKE '%".$fecha_hoy."%' AND fecha_hora NOT LIKE '%".$fecha_ayer."%' ";
mysql_query($sql,$conexion_arriba);

//Sql para borrar la tabla de resultados 
// Borra Todo los resultados Cargados que no sean de hoy, ayer ni antes de ayer.
$sql = "DELETE FROM `resultados` WHERE fecha_hora NOT LIKE '%".$fecha_hoy."%' AND fecha_hora NOT LIKE '%".$fecha_ayer."%' AND fecha_hora NOT LIKE '%".$fecha_antesayr."%' ";
mysql_query($sql,$conexion_arriba);


?>