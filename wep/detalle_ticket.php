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

// Clase Date
require('.'.$obj_config->GetVar('ruta_libreria').'Fecha.php');
$obj_date= new Fecha();

// Conexion a la bases de datos
require('.'.$obj_config->GetVar('ruta_libreria').'Bd.php');
$obj_conexion= new Bd();
if( !$obj_conexion->ConnectDataBase($obj_config->GetVar('host'), $obj_config->GetVar('data_base'), $obj_config->GetVar('usuario_db'), $obj_config->GetVar('clave_db')) ){
	echo "sin_conexion_bd";
}

// Modelo asignado
require('.'.$obj_config->GetVar('ruta_modelo').'RVentas_periodo.php');

$obj_modelo= new RVentas_periodo($obj_conexion);


$id_ticket = $_GET['id_ticket'];

$data="<table border='1'>";
$data.="<tr>";
$data.="<td colspan='5'>Detalle del Ticket No. ".$id_ticket."</td>";
$data.="</tr>";

if( $result= $obj_modelo->GetDetalleTicket($id_ticket) ){
	if ($obj_conexion->GetNumberRows($result)>0 ){
		// Establecemos la cabecera de la tabla
		$data.="<tr>";
			$data.="<td>Numero</td>";
			$data.="<td>Sorteo</td>";
			$data.="<td>Signo</td>";
			$data.="<td>Monto</td>";
			$data.="<td>Apuesta Ganadora</td>";
		$data.="</tr>";

		while($row= $obj_conexion->GetArrayInfo($result)){
			if($row['monto']!=0){
				$data.="<tr>";
				$hora_sorteo= $obj_modelo->GetHoraSorteo($row['id_sorteo']);
				$data.="<td>".$row['numero']."</td>";
				$data.="<td>".$row['nombre_sorteo']."</td>";
				$data.="<td>".$row['nombre_zodiacal']."</td>";
				$data.="<td>".$row['monto']."</td>";

				if ($row['premiado'] == '1'){
					$premiado='SI';
					$data.="<td><strong>".$premiado."</strong></td>";
				}else{
					$premiado='No';
					$data.="<td>".$premiado."</td>";
				}
				$data.="</tr>";
			}
		}
	}else{
		$data.="<tr>";
		$data.="<td>No existe informacion...</td>";
		$data.="</tr>";
	}

}

$data.="</table>";


echo $data;

?>
