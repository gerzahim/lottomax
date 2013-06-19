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
require('.'.$obj_config->GetVar('ruta_modelo').'LoginAcceso.php');
$obj_modelo= new LoginAcceso($obj_conexion);

$hora_actual= strtotime(date('H:i:s'));

$result= $obj_modelo->CheckTimePing();
 while ($row =  $obj_conexion->GetArrayInfo($result)){
    if ($hora_actual-strtotime($row['time_ping'])>120){  // Si la hora actual menos el time_ping es mayor a 120(2 minutos), quiere decir que el usuario está desconectado...
        $obj_modelo->EliminarUsuarioTimePing($row['id_usuario']);
    }
 }

?>
