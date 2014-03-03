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
$fecha_hora = $obj_date->changeFormatDateII ( $_GET ['fecha'] );
if ($result = $obj_modelo->GetAllSorteos ()) {
	while ( $row = $obj_conexion->GetArrayInfo ( $result ) ) {
		//$numero = $_GET ['txt_numero-' . $row ['id_sorteo']];

		if (empty($_GET['txt_numero-'.$row['id_sorteo']])) { $numero="";} else { $numero=$_GET['txt_numero-'.$row['id_sorteo']];}
				
		$id_sorteo = $row ['id_sorteo'];
		if (!$obj_generico->IsEmpty ( $numero )) {
			if (strlen ( $numero ) == 3) {
				if($obj_modelo->GetResultadoSorteo($row ['id_sorteo'],$fecha_hora)==""){
					
					if (isset( $_GET ['zodiacal-' . $row ['id_sorteo']] )) {
						$zodiacal = $_GET ['zodiacal-' . $row ['id_sorteo']];
						if ($obj_modelo->GuardarDatosResultados ( $id_sorteo, $zodiacal, $numero, $fecha_hora )) {
							$sw=1;
							$_SESSION ['mensaje'] = $mensajes ['info_agregada'];
						}
					}
					else { // NO ES ZODIACAL
						$zodiacal = 0;
						if ($obj_modelo->GuardarDatosResultados ( $id_sorteo, $zodiacal, $numero, $fecha_hora )) {
							$sw=1;
							$_SESSION ['mensaje'] = $mensajes ['info_agregada'];
						}
					}
					
				}
			} else {
				$_SESSION ['mensaje'] = 'Los numeros ingresados deben ser de tres digitos! ';
				header ( 'location:' . $_SESSION ['Ruta_Lista'] );
			}
		}
	}
	if($sw==1){
		PremiarGanadores ($obj_conexion, $obj_modelo,$fecha_hora ); // Premiamos los tickets ganadores
	}else{
		$_SESSION ['mensaje'] = "No se ingresaron nuevos resultados";
	}	
	header('Location: CargarResultados.php');
}

// Funcion para premiar los tickets ganadores
function PremiarGanadores($obj_conexion,$obj_modelo,$fecha_hora){
	$id_detalle_ticket[]="";
	$id_tickets[]="";
	$totales[]="";
	$aprox= $obj_modelo->GetAprox();
	//$where = " fecha_hora LIKE '%".date('Y-m-d')."%'";
	//$result= $obj_modelo->GetListadosegunVariable($where);
	$resultados=array();
	$id_sorteo=array();
	$id_zodiacal=array();
	$result=$obj_modelo->GetResultados($fecha_hora);
	while($row=$obj_conexion->GetArrayInfo($result)){
		$resultados[]=$row['numero'];
		$id_sorteo[]=$row['id_sorteo'];
		$id_zodiacal[]=$row['zodiacal'];
	}
	$relacion_pago=array();
	//$id_tipo_jugada[]=array();
	$result=$obj_modelo->GetRelacionPagos($fecha_hora);
	while($row=$obj_conexion->GetArrayInfo($result)){
		$relacion_pago[$row['id_tipo_jugada']]=$row['monto'];
		//	$id_tipo_jugada[]=$row['id_tipo_jugada'];
	}
	//print_r($relacion_pago);
	$result= $obj_modelo->GetListadosegunVariable($fecha_hora);
	If ($obj_conexion->GetNumberRows($result)>0){
		$i=0; $j=0;
		$ticket_premiado=0;
		$monto_total_ticket=0;
		while ($roww= $obj_conexion->GetArrayInfo($result)){
			$id_ticket=$roww["id_ticket"];
			$fecha_ticket= substr($roww["fecha_hora"],0 , -9);
			$resultDT = $obj_modelo->GetAllDetalleTciket($id_ticket);
			//revisamos la tabla de detalle ticket y comparamos con los resultados
			$monto_total=0;
			$sw=0;
			while($rowDT= $obj_conexion->GetArrayInfo($resultDT)){
				// Verificamos si hay alguna apuesta ganadora...
				for ($i=0;$i<count($resultados);$i++){
					$swz=0;
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
						if(($terminal_abajo==substr($resultados[$i], 1, 3) OR $terminal_arriba==substr($resultados[$i], 1, 3)) AND $rowDT['id_sorteo']==$id_sorteo[$i] ){
							$monto_pago=$relacion_pago[5]*$rowDT['monto'];
							$monto_total+=$monto_pago;
							$obj_modelo->PremiarDetalleTicket($rowDT['id_detalle_ticket'], $monto_pago);
							$sw=1;
						}
					}
					if((($rowDT['numero']==$resultados[$i] AND ($rowDT['id_tipo_jugada']==1 OR $rowDT['id_tipo_jugada']==3))OR ($rowDT['numero']== substr($resultados[$i], 1, 3) AND ($rowDT['id_tipo_jugada']==2 OR $rowDT['id_tipo_jugada']==4))    )      AND $rowDT['id_sorteo']==$id_sorteo[$i] ){


						if($id_zodiacal[$i]!=0 AND $id_zodiacal[$i]==$rowDT['id_zodiacal'])
						{
							$swz=1;
							$monto_pago=$relacion_pago[$rowDT['id_tipo_jugada']]*$rowDT['monto'];
						}
						else
							$monto_pago=$relacion_pago[$rowDT['id_tipo_jugada']]*$rowDT['monto'];

						if($id_zodiacal[$i]!=0 )
						{
							if($swz==1 )
							{
								$monto_total+=$monto_pago;
								$sw=1;
								$obj_modelo->PremiarDetalleTicket($rowDT['id_detalle_ticket'], $monto_pago);
							}
						}
						else
						{
							$monto_total+=$monto_pago;
							$sw=1;
							$obj_modelo->PremiarDetalleTicket($rowDT['id_detalle_ticket'], $monto_pago);
						}
					}
				}
			}
			if($sw==1)
				$obj_modelo->PremiarTicket($id_ticket,$monto_total);
				
		}
	}


}
?>
