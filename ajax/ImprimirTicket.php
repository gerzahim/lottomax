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
require('.'.$obj_config->GetVar('ruta_modelo').'Ventas.php');
$obj_modelo= new Ventas($obj_conexion);

session_start();

$id_taquilla=2;
/************* CABLEADO **********************/
//id_ticket debe venir de una variable de sesion

$info_ticket= $obj_modelo->GetLastTicket($id_taquilla);
$id_ticket=$info_ticket["id_ticket"];
$serial=$info_ticket["serial"]; 
$string=$info_ticket["fecha_hora"]; 
$year = substr($string,0,4);
$month = substr($string,5,2);
$day = substr($string,8,2);
$hour = substr($string,11,2);
$minute = substr($string,14,2);
$fecha_hora=$day."-".$month."-".$year." ".$hour.":".$minute;

$total_ticket=$info_ticket["total_ticket"]; 
$id_usuario=$info_ticket["id_usuario"];
$nombre_usuario=$obj_modelo->GetNombreUsuarioById($id_usuario);
$nombre = explode(" ", $nombre_usuario);
$nombre = $nombre[0];

$nombre_agencia= $obj_modelo->GetNombreAgencia();


// ENCABEZADO DEL TICKET
echo "SISTEMA LOTTOMAX";
echo "<br>";
echo "AGENCIA: ",$nombre_agencia;
echo "<br>";
echo "TICKET: ",$id_ticket;
echo "<br>";
echo "SERIAL: ",$serial;
echo "<br>";
echo "FECHA: ",$fecha_hora;
echo "<br>";
echo "TAQUILLA: ",$id_taquilla;
echo "<br>";
echo "VENDEDOR: ",$nombre;
echo "<br>";
echo "<br>";
$data="SISTEMA LOTTOMAX";
$data.="\n";
$data.=$nombre_agencia;
$data.="\n";


if( $result= $obj_modelo->GetDetalleTicketByIdticket($id_ticket) ){
	$result1=$result;
	$id_sorteo_actual=0;
	$contador=0;
	while($row= $obj_conexion->GetArrayInfo($result)){
		$id_sorteo=$row['id_sorteo'];
		if( ($id_sorteo != $id_sorteo_actual) && ($row['id_zodiacal'] == 0) ){
			$contador=0;
			$id_sorteo_actual=$row['id_sorteo'];
			$nombre_sorteo=$obj_modelo->GetNombreSorteo($row['id_sorteo']);
			echo "<br>"; //para cada nombre de sorteo aparte			
			echo $nombre_sorteo;
			//echo "<br>2 "; //para iniciar las jugadas en la proxima linea
		}
		

			
		//comprobando si es zodiacal o no
		if($row['id_zodiacal'] == 0){
			if($contador % 2){
				echo $row['numero']." x ".$row['monto']."&nbsp;&nbsp;&nbsp;";
			}else{
				echo "<br>";	
				echo $row['numero']." x ".$row['monto']."&nbsp;&nbsp;&nbsp;";
			}
			$contador++;	
			
		}
		
	/*
			$nombre_signo=$obj_modelo->GetPreNombreSigno($row['id_zodiacal']);
			echo $row['numero']." ".$nombre_signo." x ".$row['monto']."&nbsp;&nbsp;&nbsp;";
	*/

	}	
	
	
	
		
}


/*
    [id_detalle_ticket] => 39
    [id_ticket] => 20130314120010
    [numero] => 123
    [id_sorteo] => 5
    [hora_sorteo] => 12:00:00
    [id_zodiacal] => 0
    [id_tipo_jugada] => 1
    [monto] => 20.00

If ($obj_conexion->GetNumberRows($resultTT)>0){  
    if ($obj_modelo->EliminarAllTicketTransaccional()){
      echo "Ok";
    }
}else{
    echo "NotOk";
}
*/

?>