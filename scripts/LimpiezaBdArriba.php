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

$conexion_arriba = mysql_connect("www.db4free.net" , "lottomaxuser" , "secreta7",true);
mysql_select_db("lottomaxdb",$conexion_arriba);

$fecha_pasado= date('Y-m-d', strtotime('-45 day')) ;
$fecha_pasado.=" 00:00:00";
$sql = "DELETE FROM `ticket` WHERE fecha_hora < '".$fecha_pasado."'";
mysql_query($sql,$conexion_abajo);
//Sql para borrar la tabla de resultados 
// Borra Todo los resultados Cargados que no sean de hoy, ayer ni antes de ayer.
$sql = "DELETE FROM `resultados` WHERE fecha_hora < '".$fecha_pasado."'";
mysql_query($sql,$conexion_abajo);


?>