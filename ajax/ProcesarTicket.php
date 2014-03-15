<?php
date_default_timezone_set("America/Caracas");
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

session_start();

// Modelo asignado
require('.'.$obj_config->GetVar('ruta_modelo').'Ventas.php');
$obj_modelo= new Ventas($obj_conexion);
$taquilla= $obj_modelo->GetIdTaquilla();
$impreso=$obj_modelo->ExisteTicketNoImpreso($taquilla);
if($impreso == 1 || $impreso == 2){
	$resultTT= $obj_modelo->GetDatosTicketTransaccional();
	If ($obj_conexion->GetNumberRows($resultTT)>0){
	
	    // Generacion del Id del Ticket
	    $id_ticket= $obj_modelo->GeneraIDTicket();
	
	
	    // Generacion del serial del ticket
	    $serial="";
	    $serial = $obj_modelo->GeneraSerialTicket();
	    while ($obj_modelo->GetExisteSerialTicket($serial)){
	        $serial = $obj_modelo->GeneraSerialTicket();
	    }
	
	    // Obtenemos los datos de la taquilla
	
	    // Obtenemos el total del ticket
	    if( $result= $obj_modelo->GetDatosTicketTransaccional() ){
	            $total_ticket=0;
	            while($row= $obj_conexion->GetArrayInfo($result)){
	                    // acumula y suma el total a la variable
	                    $total_ticket+=$row['monto'];
	            }
	    }
	
	    $fecha_hora= date('Y-m-d H:i:s');
	    $id_usuario= $_SESSION['id_usuario'];
	
	 //	$sw=0;
	    if ($obj_modelo->GuardarTicket($id_ticket,$serial, $fecha_hora, $taquilla, $total_ticket, $id_usuario)){

	    	if( $result2= $obj_modelo->GetDatosAllTicketTransaccional() ){
	            while($row= $obj_conexion->GetArrayInfo($result2)){
	                // Guarda los datos en detalle Ticket
	                	$fecha_sorteo= date('Y-m-d');
	                     if ($obj_modelo->GuardarDetalleTicket($id_ticket, $row['numero'], $row['id_sorteo'], $fecha_sorteo, $row['id_zodiacal'], $row['id_tipo_jugada'], $row['monto'],$row['monto_restante'],$row['monto_faltante'])){
	                  	}	
	           }
	        }
	
	        echo "Ok";
	    }else{
	    	
	        echo "NotOk";
	    }
	
	}else{
	    echo "CeroTicketTransaccional";
	}
}
else {
	//echo "pasaaa";
	header ("Location: ImprimirTicket.php");
	
}




?>