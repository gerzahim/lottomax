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
require('.'.$obj_config->GetVar('ruta_modelo').'Cargar_resultados.php');

$obj_modelo= new Cargar_resultados($obj_conexion);

$sw=0; // PARA PREMIAR TICKETS LUEGO
$fecha_hora = $obj_date->changeFormatDateII ( $_POST['fecha'] );

$result=$obj_modelo->GetResultadosRepetidos($fecha_hora);
$sorteosexistentes=array();
while($row=$obj_conexion->GetArrayInfo($result))
	$sorteosexistentes[$row['id_sorteo']]=1;
$sorteos=preg_split('/-/',$_POST ['sorteosnocargados']);
$sql="INSERT INTO `resultados` (`id_sorteo` , `zodiacal`, `numero`, `fecha_hora`) VALUES ";
$resultados=array();
$zodiacales=array();
foreach($sorteos as $st)
{
	$numero = $_POST['txt_numero-' .$st];
	if (isset( $_POST ['zodiacal-' . $st] )){
		$zodiacal = $_POST ['zodiacal-' . $st];
		$zodiacales[$st]=$zodiacal;
	}
	else{
		$zodiacales[$st]=0;
		$zodiacal =0;
	}
	if(!empty($numero)){
		if(!isset($sorteosexistentes[$st])){
			if (strlen ( $numero ) == 3){
				$resultados[$st]=$numero;
				$sql.="('".$st."', '".$zodiacal."', '".$numero."', '".$fecha_hora."'),";
				$sw=1;
			}
		}
	}
	else {
		$_SESSION ['mensaje'] = 'Los numeros ingresados deben ser de tres digitos! ';
		header('Location: CargarResultados.php?fecha='.$fecha_hora);
	}
}
if($sw==1){
	$sql=trim($sql,',');
	$sql.=";";
	if($obj_modelo->GuardarDatosResultadosMasivo($sql)){
		$mensaje=$mensajes['info_agregada'];
		PremiarGanadores ($obj_conexion, $obj_modelo,$resultados,$zodiacales,$fecha_hora); // Premiamos los tickets ganadores
	}
	else
		$mensaje= "No se ingresaron nuevos resultados";
}	
else
$mensaje= "No se ingresaron nuevos resultados";
$_SESSION ['mensaje'] = $mensaje;
header('Location: CargarResultados.php?fecha='.$fecha_hora);

function PremiarGanadores($obj_conexion,$obj_modelo,$resultados,$zodiacales,$fecha_hora){
	$id_detalle_ticket[]="";
	$id_tickets[]="";
	$totales[]="";
	//print_r($resultados);
	$aprox= $obj_modelo->GetAprox();
	$relacion_pago=array();
	$result=$obj_modelo->GetRelacionPagos($fecha_hora);
	while($row=$obj_conexion->GetArrayInfo($result)){
		$relacion_pago[$row['id_tipo_jugada']]=$row['monto'];
	}
	$result= $obj_modelo->GetListadosegunVariable($fecha_hora);
	If($obj_conexion->GetNumberRows($result)>0){
		$i=0; $j=0;
		$ticket_premiado=0;
		while ($roww= $obj_conexion->GetArrayInfo($result)){
			$id_ticket=$roww["id_ticket"];
			$fecha_ticket= substr($roww["fecha_hora"],0 , -9);
			$resultDT = $obj_modelo->GetAllDetalleTciket($id_ticket);
			//revisamos la tabla de detalle ticket y comparamos con los resultados
			$monto_total=$roww['total_premiado'];
			$sw=0;
			// SE RECORRE CADA TICKET VENDIDO EL DIA DE LA CARGA DE RESULTADO
			while($rowDT= $obj_conexion->GetArrayInfo($resultDT)){
				// Verificamos si hay alguna apuesta ganadora...
				$terminal_abajo=0;
				$terminal_arriba=0;
				if($rowDT['id_tipo_jugada']==2){
					switch ($aprox){
						case 0:
							$terminal_abajo=$rowDT['numero']-1;
							break;
						case 1:
							$terminal_arriba=$rowDT['numero']+1;
							$terminal_abajo=$rowDT['numero']-1;
							break;
						case 2:
							$terminal_arriba=$rowDT['numero']+1;
							break;
					}
					if(isset($resultados[$rowDT['id_sorteo']]))
						if(($terminal_abajo==substr($resultados[$rowDT['id_sorteo']], 1, 3) OR $terminal_arriba==substr($resultados[$rowDT['id_sorteo']], 1, 3)) ){
						$monto_pago=$relacion_pago[5]*$rowDT['monto'];
						$monto_total+=$monto_pago;
						$obj_modelo->PremiarDetalleTicket($rowDT['id_detalle_ticket'], $monto_pago);
						$sw=1;
					}
				}
				if(isset($resultados[$rowDT['id_sorteo']]))
					if(($rowDT['numero']==$resultados[$rowDT['id_sorteo']] AND ($rowDT['id_zodiacal']==$zodiacales[$rowDT['id_sorteo']])) OR ( ($rowDT['numero']== substr($resultados[$rowDT['id_sorteo']], 1, 3)  AND $rowDT['id_zodiacal']==$zodiacales[$rowDT['id_sorteo']]) AND ($rowDT['id_tipo_jugada']==2 OR $rowDT['id_tipo_jugada']==4)) ){
					$monto_pago=$relacion_pago[$rowDT['id_tipo_jugada']]*$rowDT['monto'];
					$monto_total+=$monto_pago;
					$sw=1;
					$obj_modelo->PremiarDetalleTicket($rowDT['id_detalle_ticket'], $monto_pago);
				}
			}
			if($sw==1)
				$obj_modelo->PremiarTicket($id_ticket,$monto_total);
		}
	}
}



?>
