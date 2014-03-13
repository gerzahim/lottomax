<?php
/**
 * Archivo del controlador para modulo Cargar Resultados
 * @package cCargar_Resultados.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Abril - 2013
 */

// Vista asignada
$obj_xtpl->assign_file('contenido', $obj_config->GetVar('ruta_vista').'cargar_resultados'.$obj_config->GetVar('ext_vista'));

// Modelo asignado
require($obj_config->GetVar('ruta_modelo').'Cargar_resultados.php');

// Modelo asignado para premiar tickets
//require($obj_config->GetVar('ruta_modelo').'Pagar_Ganador.php');

$obj_modelo= new Cargar_Resultados($obj_conexion);
$obj_date= new Fecha();
$id_detalle_ticket[]="";
$id_tickets[]="";
$totales[]="";

switch (ACCION){
	
	case 'mod':
	
		// Ruta actual
		$_SESSION['Ruta_Form']= $obj_generico->RutaRegreso();
	
		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso',$_SESSION['Ruta_Form']);
	
		// Accion a realizar
		$obj_xtpl->assign('tipo_accion', 'upd');
		$obj_xtpl->assign('tag_boton', 'Modificar');
		

	

		//Obteniendo Datos del Usuario
		if( is_numeric($_GET['id'])){
			
			$fecha= $_GET['fecha'];
			$fecha_hora = $obj_date->changeFormatDateII($fecha);
			$id_sorteo= $_GET['id'];
			$bajado= $_GET['bajado'];
			$id_resultado = $obj_modelo->VerificarResultadoSorteo($id_sorteo, $fecha_hora);
			
			if($obj_generico->IsEmpty($id_resultado)){
							
				// no tiene id_resultado, es decir no lo han cargado aun
				$_SESSION['mensaje']= $mensajes['no_idresultado'];
				header('location:'.$_SESSION['Ruta_Lista']);
			}			
			
			// Asignaciones
			$row = $obj_modelo->GetDatosSorteo($_GET['id']);
			
			//print_r($row);
			// Array ( [0] => 1 [id_resultados] => 1 [1] => 4 [id_sorteo] => 4 [2] => 0 [zodiacal] => 0 [3] => 833 [numero] => 833 [4] => 2014-02-08 [fecha_hora] => 2014-02-08 [5] => 1 [bajado] => 1 ) 
			
			if ($row ['zodiacal'] != 0) {
							// Listado de Zodiacal
							if ($result_z = $obj_modelo->GetZodiacales ()) {
								while ( $row_z = $obj_conexion->GetArrayInfo ( $result_z ) ) {
									$obj_xtpl->assign ( $obj_generico->CleanTextDb ( $row_z ) );
									$obj_xtpl->assign ( 'Id_zodiacal', $row_z ['Id_zodiacal'] );
									$obj_xtpl->assign ( 'nombre_zodiacal', $row_z ['nombre_zodiacal'] );
									$obj_xtpl->assign ( 'selected', '' );
									$obj_xtpl->parse ( 'main.contenido.formulario.lista_zodiacal.op_zodiacal' );
								}
								//$obj_xtpl->parse ( 'main.contenido.formulario.lista_zodiacal' );
							}
			}else{
				$obj_xtpl->assign ( 'Id_zodiacal', '0' );
				$obj_xtpl->assign ( 'nombre_zodiacal', 'NO ZODIACAL' );
				$obj_xtpl->parse ( 'main.contenido.formulario.lista_zodiacal.op_zodiacal' );
			}
			$obj_xtpl->parse ( 'main.contenido.formulario.lista_zodiacal' );
				
			// Lista los datos del usuario obtenidos de la BD
			$obj_xtpl->assign('fecha', $_GET['fecha']);
			$obj_xtpl->assign('zodiacal', $row ['zodiacal']);
			$obj_xtpl->assign('id_Sorteo', $_GET['id']);
			$obj_xtpl->assign('id_resultado', $id_resultado);
			$obj_xtpl->assign('bajado', $bajado);
				
			// ID en el hidden
			$obj_xtpl->parse('main.contenido.formulario.identificador');
	
		}
	
	
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.formulario');
		break;

		case 'upd':
			$id_resultados;
			$id_sorteo = $_POST['idreferencia'];
			$numero = $_POST['txt_numero'];
			$zodiacal = $_POST['zodiacal'];
			$id_resultado = $_POST['id_resultado'];
			$resultados=array();
			$zodiacales=array();
			$resultados[$id_sorteo]=$numero;
			$zodiacales[$id_sorteo]=$zodiacal;
			$bajado= $_POST['bajado'];
			if($bajado==0)
			$bajado=0;
			else
			$bajado=2;
			$fecha_hora = $obj_date->changeFormatDateII($_POST['fecha']);
			if ($obj_modelo->ActualizaDatosResultados($id_resultado, $id_sorteo, $zodiacal, $numero, $fecha_hora,$bajado)){
				
				$obj_modelo->DespremiarTicket($fecha_hora,$id_sorteo);
				PremiarGanadores($obj_conexion,$obj_modelo,$resultados,$zodiacales,$fecha_hora); // Premiamos los tickets ganadores
				$_SESSION['mensaje']= $mensajes['info_agregada'];
				header('location:'.$_SESSION['Ruta_Lista']);
			}
			
	
			break;		

        case 'cargar_resultados':
        $zodiacales=array();
        if ($result_z = $obj_modelo->GetZodiacales ())
        while ( $row_z = $obj_conexion->GetArrayInfo ( $result_z ) )
        $zodiacales[$row_z['Id_zodiacal']]=$row_z ['nombre_zodiacal'];
		// Ruta actual
		$_SESSION ['Ruta_Lista'] = $obj_generico->RutaRegreso();
		// Ruta regreso
		$obj_xtpl->assign ( 'ruta_regreso', $_SESSION ['Ruta_Fecha'] );
		// Accion a realizar
		$obj_xtpl->assign ( 'tipo_accion', 'save' );
		$obj_xtpl->assign ( 'tag_boton', 'Guardar' );
		// Para la paginacion
		/*if (empty ( $_GET ['pg'] )) {
			$pag = 1;
		} else {
			$pag = $_GET ['pg'];
		}*/
		$fecha = $obj_date->changeFormatDateII ( $_GET ['txt_fecha'] );
		$obj_xtpl->assign ( 'fecha', $obj_date->changeFormatDateI ( $fecha, 0 ) );
		$periodo = $_GET ['radio_periodo'];
		// Listado de Sorteos
		$sorteosassign='';
		$sorteosnocargados='';
		if ($result = $obj_modelo->GetSorteos ( $fecha, $periodo )) {
			
			if ($obj_conexion->GetNumberRows ( $result ) > 0) {
				$i = 1;
				$j = 0;
				while ( $row = $obj_conexion->GetArrayInfo ( $result ) ) {
					
					//print_r($row);
					//Saca la hora del sorteo
					$hora_sorteo=$fecha." ".$row['hora_sorteo'];
					//Valor que viene de la base de datos
					// Obtiene el parametros de los minutos para no listar el sorteo
					$minutos_bloqueo=$obj_modelo->MinutosBloqueo();
					//echo "Hora".$hora_sorteo."<br>";
					$fecha_hora_actual=date("Y-m-d H:i:s");
					// Restando la fecha actual con la fecha y hora del sorteo.
					$resta=strtotime($fecha_hora_actual)-strtotime($hora_sorteo);
					// Si la resta es negativo quiere decir que todavia los sorteos no han cerrado
					if ($resta>0){
						if (($i % 2) > 0)
							$obj_xtpl->assign ( 'estilo_fila', 'even' );
						else
							$obj_xtpl->assign ( 'estilo_fila', 'odd' );
						$obj_xtpl->assign ( 'sorteo', $row ['nombre_sorteo'] );
						if ($row ['numero'] == 'numero') {
							$j ++;
							$sorteosnocargados.=$row ['id_sorteo'].'-';
							$obj_xtpl->assign ( 'estilo_fila', 'evenred' );
							$obj_xtpl->assign ( 'id_Sorteo', $row ['id_sorteo']);
							$obj_xtpl->assign ( 'id_resultado', '' );
							$obj_xtpl->assign ( 'id_sorteo', $row ['id_sorteo'] );
							$obj_xtpl->assign ( 'numero', '<input id="txt_numero" name="txt_numero-' . $row ['id_sorteo'] . '" size="12" type="text" maxlength="3" />' );
							$obj_xtpl->assign ( 'terminal', '' );
							$obj_xtpl->assign('bajado', $row['bajado']);
							if ($row ['zodiacal'] == 1) {
								foreach ($zodiacales as $key => $zd){
									$obj_xtpl->assign ( 'Id_zodiacal', $key);
									$obj_xtpl->assign ( 'nombre_zodiacal', $zd);
									$obj_xtpl->parse ( 'main.contenido.lista_cargar_resultados.lista.lista_zodiacal.op_zodiacal' );
								}
								$obj_xtpl->parse ( 'main.contenido.lista_cargar_resultados.lista.lista_zodiacal' );
							} 
							$obj_xtpl->assign ( 'aprox_arriba', '' );
							$obj_xtpl->assign ( 'aprox_abajo', '' );
							
						}
						else {
							$obj_xtpl->assign ( 'id_Sorteo', $row ['id_sorteo']);
							$obj_xtpl->assign ( 'id_resultado', $row ['id_resultado'] );
							$obj_xtpl->assign ( 'id_sorteo', $row ['id_sorteo'] );
							$obj_xtpl->assign ( 'numero', '<input id="txt_numero" readonly name="txt_numero-' . $row ['id_sorteo'] . '" size="12" value="' . $row ['numero'] . '"" type="text" maxlength="3" />' );
							$obj_xtpl->assign ( 'terminal', substr ( $row ['numero'], 1, 2 ) );
							$obj_xtpl->assign ( 'signo', substr ( $row ['numero'], 1, 2 ) );
							$obj_xtpl->assign('bajado', $row['bajado']);
							$obj_xtpl->parse ( 'main.contenido.lista_cargar_resultados.lista.modificar' );
							if ($row ['zodiacal'] == 1) {
								// Listado de Zodiacal
									$obj_xtpl->assign ( 'text_zodiacal', $row ['signo'] );
									$obj_xtpl->parse ( 'main.contenido.lista_cargar_resultados.lista.text_zodiacal' );
							
								}
								$term = (substr ( $row ['numero'], 1, 2 ));
								
								if ($term < 9 && $term > 0) {
									$preceroa = "0" . ($term + 1);
									$preceroo = "0" . ($term - 1);
								} else if ($term == 9) {
									$preceroa = "" . ($term + 1);
									$preceroo = "0" . ($term - 1);
								} else if ($term == 10) {
									$preceroa = "" . ($term + 1);
									$preceroo = "0" . ($term - 1);
								} else if ($term == '00') {
									$preceroa = "0" . ($term + 1);
									$preceroo = "99";
								} else if ($term == 99) {
									$preceroa = "00";
									$preceroo = "" . ($term - 1);
									;
								} else {
									$preceroa = "" . ($term + 1);
									$preceroo = "" . ($term - 1);
								}
								$obj_xtpl->assign ( 'aprox_arriba', $preceroa );
								$obj_xtpl->assign ( 'aprox_abajo', $preceroo );
						}
						// Parseo del bloque de la fila
						$obj_xtpl->parse ( 'main.contenido.lista_cargar_resultados.lista' );
						$i ++;
					}
				} 
			}else {
				// Mensaje
				$obj_xtpl->assign ( 'sin_listado', $mensajes ['sin_lista'] );
				// Parseo del bloque de la fila
				$obj_xtpl->parse ( 'main.contenido.lista_cargar_resultados.no_lista' );
			}
			$obj_xtpl->assign ( 'faltantes', '<span class="requerido">Faltan <b>' . $j . '</b> de <b>' . ($i - 1) . ' Sorteos</b> por ingresar resultados...</span>' );
		}
		$sorteosnocargados= trim($sorteosnocargados, '-');
		$obj_xtpl->assign ('sorteosnocargados', $sorteosnocargados);
		// Parseo del bloque
		$obj_xtpl->parse ( 'main.contenido.lista_cargar_resultados' );
		break;
        case 'save':
		//exit;
        $sw=0; // PARA PREMIAR TICKETS LUEGO
  		$mensaje='';
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
				header ( 'location:' . $_SESSION ['Ruta_Lista'] );
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
		header ( 'location:' . $_SESSION ['Ruta_Lista'] );
		break;
	default:
		// Ruta actual
		$_SESSION['Ruta_Fecha']=$obj_generico->RutaRegreso();
	    $obj_xtpl->assign('fecha', $obj_date->FechaHoy2());
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.buscar_resultados');
		break;
}
$obj_xtpl->parse('main.contenido');

// Funcion para premiar los tickets ganadores
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