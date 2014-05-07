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
require('.'.$obj_config->GetVar('ruta_modelo').'Loteria.php');
$obj_modelo= new Loteria($obj_conexion);
//Script Para Activar y Desactivar Programación de Loterias  
$string_busqueda=" fecha_desde <= '".date('Y-m-d')."'  AND status_especial <> 2 AND fecha_desde <> '0000-00-00'";
$result=$obj_modelo->BuscarFechaEspecial($string_busqueda);
if($obj_conexion->GetNumberRows($result)>0)
	while($row= $obj_conexion->GetArrayInfo($result)){
		$obj_modelo->ActualizarStatusLoteria($row['id_loteria'],$row['status_especial']);
		$obj_modelo->ActualizarStatusEspecialLoteria($row['id_loteria'],2);
	}
// Buscar las loterias que ya cumplieron con el estatus programado y volveras a su estado anterior.
$string_busqueda=" fecha_hasta < '".date('Y-m-d')."' AND fecha_hasta <> '0000-00-00' ";
$result=$obj_modelo->BuscarFechaEspecial($string_busqueda);
if($obj_conexion->GetNumberRows($result)>0)
	while($row= $obj_conexion->GetArrayInfo($result)){
		if($row['status'])
		$status=0;
		else
		$status=1;
		$obj_modelo->ActualizaStatusSorteo($row['id_loteria'],$status,$row['id_dias_semana']);
		$obj_modelo->ActualizaDatosLoteria($row['id_loteria'],$row['nombre_loteria'],$status,$row['id_dias_semana'],'0000-00-00','0000-00-00',0);
	}
?>

