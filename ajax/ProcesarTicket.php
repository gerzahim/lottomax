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
$total_ticket=$_GET['monto_total'];
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
	    $fecha_hora= date('Y-m-d H:i:s');
	    $id_usuario= $_SESSION['id_usuario'];
	 	$sql="INSERT INTO `detalle_ticket` (`id_ticket`, `numero` , `id_sorteo` , `fecha_sorteo`, `id_zodiacal` , `id_tipo_jugada` , `monto`,`monto_restante`,`monto_faltante`) VALUES  ";
	 	$sql_numeros_jugados="INSERT INTO `numeros_jugados` (`fecha`, `numero` , `id_sorteo` , `id_tipo_jugada` , `id_zodiacal`, `monto_restante` ) VALUES  ";
	 	$sw=0;
	 	$sw1=0;
	    if ($obj_modelo->GuardarTicket($id_ticket,$serial, $fecha_hora, $taquilla, $total_ticket, $id_usuario)){
	    	if( $result2= $obj_modelo->GetDatosAllTicketTransaccional() ){
	            while($row= $obj_conexion->GetArrayInfo($result2)){
	                // Guarda los datos en detalle Ticket
	                	$fecha_sorteo= date('Y-m-d');
	                	$sql.="('".$id_ticket."', '".$row['numero']."', '".$row['id_sorteo']."', '".$fecha_sorteo."', '".$row['id_zodiacal']."', '".$row['id_tipo_jugada']."', '".$row['monto']."', '".$row['monto_restante']."', '".$row['monto_faltante']."'),";
	                	$sw1=1;
	                	$registros=$obj_modelo->GetNumerosJugados($row['numero'], $row['id_sorteo'], $row['id_zodiacal'],$fecha_sorteo);
	                  	if($registros['total_registros']>0){
	                  		$obj_modelo->ActualizaNumeroJugados($registros['id_numeros_jugados'],$row['monto_restante']);
	                  	}
	                  	else{
	                  		$sql_numeros_jugados.="('".$fecha_sorteo."', '".$row['numero']."', '".$row['id_sorteo']."', '".$row['id_tipo_jugada']."', '".$row['id_zodiacal']."', '".$row['monto_restante']."'),";
	                  		$sw=1;
	                  	}
	           }
	           $sql = trim($sql, ',');
	           $sql_numeros_jugados = trim($sql_numeros_jugados, ',');
	           $sql.=";";
	           $sql_numeros_jugados.=";";
	      	   if($sw1==1){
	           		if($result=$obj_modelo->GuardarSql($sql))
	           		$sw1=2;
	           }
	           if($sw==1){
	           	if($result=$obj_modelo->GuardarSql($sql_numeros_jugados))
	           		$sw=2;
	           }
	           if($sw1==2)
	           echo "Ok";
	           else
	           echo "NotOk";
	        }
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