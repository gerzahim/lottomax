<?php

date_default_timezone_set("America/Caracas");
if(isset($_GET['cantm'])){
	$cantidad_motivos=$_GET['cantm'];
}else{
	$cantidad_motivos=0;
}

// Archivo de variables de configuracion
require_once('../config/config.php');
$obj_config= new  ConfigVars();


// Objetos de clases

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
require('.'.$obj_config->GetVar('ruta_modelo').'Test.php');
$obj_modelo= new Test($obj_conexion);



?>