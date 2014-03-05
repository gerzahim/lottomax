<?php
///scripts/CupoEspecial.php?num=05&monto=5&fechadesde=2014-05-03&fechahasta=2014-05-03
// Archivo de variables de configuracion
require_once('../config/config.php');
$obj_config= new ConfigVars();

// Archivo de mensajes
require_once('.'.$obj_config->GetVar('ruta_config').'mensajes.php');

// Clase Generica
require('.'.$obj_config->GetVar('ruta_libreria').'Generica.php');
$obj_generico= new Generica();

// Conexion a la bases de datos
require('.'.$obj_config->GetVar('ruta_libreria').'Bd.php');
$obj_conexion= new Bd();
if( !$obj_conexion->ConnectDataBase($obj_config->GetVar('host'), $obj_config->GetVar('data_base'), $obj_config->GetVar('usuario_db'), $obj_config->GetVar('clave_db')) ){
	echo "sin_conexion_bd";
}

// Modelo asignado
require('.'.$obj_config->GetVar('ruta_modelo').'CupoEspecial.php');
$obj_modelo= new CupoEspecial($obj_conexion);



//Script Para Mantenimiento Numeros_Jugados
//Borra los numeros que no sean del dia de hoy

$terminal= $_GET['num'];
$monto= $_GET['monto'];
$fechadesde= $_GET['fechadesde'];
$fechahasta= $_GET['fechahasta'];
$sql="INSERT INTO `cupo_especial`( `numero`, `id_sorteo`, `monto_cupo`, `id_tipo_jugada`, `id_zodiacal`, `fecha_desde`, `fecha_hasta`) VALUES "; 

$serie= $obj_modelo->Serializar($terminal);
$result=$obj_modelo->getSorteos();
while($row= $obj_conexion->GetArrayInfo($result))
{
	for($i=0;$i<count($serie);$i++){
	$numero_insertar=$obj_modelo->Permutar($serie[$i]);
	for($h=0;$h<count($numero_insertar);$h++)
		$sql.="('".$numero_insertar[$h]."' , ".$row['id_sorteo']." , ".$monto." , 1 , 0 , '".$fechadesde." 00:00:00 ', '".$fechahasta." 00:00:00 ' ),";
	}
}
$sql = trim($sql, ',');
$sql.=";";
//echo $sql;
if($obj_modelo->insertarCupoEspecial($sql))
	echo "OK";
else
	echo "NO OK";



?>

