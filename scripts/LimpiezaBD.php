<?php

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
require('.'.$obj_config->GetVar('ruta_modelo').'Limpieza_BD.php');
$obj_modelo= new Limpieza_BD($obj_conexion);



//Script Para Mantenimiento Numeros_Jugados
//Borra los numeros que no sean del dia de hoy

$where = "'".date('Y-m-d')."'";
$obj_modelo->EliminarNumerosJugados($where);


//Script Para Mantenimiento IncompletosAgotados
//Borra los numeros que no sean del dia de hoy
$obj_modelo->EliminarIncompletosAgotados($where);


//Script Para Mantenimiento Resultados
//Elaborar script para que borre y deje los resultados de al menos un mes

?>

