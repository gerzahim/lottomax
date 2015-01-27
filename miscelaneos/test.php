<?php

echo date_default_timezone_get();
//date_default_timezone_set("America/Caracas");
/*
$conexion_abajo = mysql_connect("localhost" , "root" , "secreta");
mysql_select_db("lottomax",$conexion_abajo);
/*
$date_time= date('Y-m-d H:i:s');
$sql = "INSERT INTO ingreso (`fecha_hora`) values ('".$date_time."')";
*/
function GetRelacionPagos(){

	//Preparacion del query
	$sql = "SELECT monto, id_tipo_jugada, id_agencia FROM relacion_pagos ";
	$result= mysql_query($sql);
	return $result;

}
/*
$relacion_pago=array();
$result=GetRelacionPagos();
//	echo "aqui ando";
while($row= mysql_fetch_array($result)){
	$relacion_pago[$row['id_tipo_jugada']][$row['id_agencia']]=$row['monto'];
}


print_r($relacion_pago);

//echo $sql;
//mysql_query($sql,$conexion_abajo);
mysql_close($conexion_abajo);
*/
?>