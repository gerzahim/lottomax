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
require($obj_config->GetVar('ruta_modelo').'Pagar_Ganador.php');

$obj_modelo= new Cargar_Resultados($obj_conexion);

$id_detalle_ticket[]="";
$id_tickets[]="";
$totales[]="";

switch (ACCION){

        case 'cargar_resultados':

                // Ruta actual
                $_SESSION['Ruta_Form']= $obj_generico->RutaRegreso();

                // Ruta regreso
                $obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Lista']);

                // Accion a realizar
                $obj_xtpl->assign('tipo_accion', 'save');
                $obj_xtpl->assign('tag_boton', 'Guardar');

                // Para la paginacion
		if(empty($_GET['pg'])){
			$pag= 1;
		}
		else{
			$pag= $_GET['pg'];
		}

                $fecha = $_GET['txt_fecha'];
                 $obj_xtpl->assign('fecha', $fecha);
                $periodo = $_GET['radio_periodo'];
                // Listado de Sorteos
                    if( $result= $obj_modelo->GetSorteos($fecha, $periodo) ){
                        if ($obj_conexion->GetNumberRows($result)>0 ){
                            $i=1; $j=0;
                            while($row= $obj_conexion->GetArrayInfo($result)){
                                if( ($i % 2) >0){
					$obj_xtpl->assign('estilo_fila', 'even');
				}
				else{
					$obj_xtpl->assign('estilo_fila', 'odd');
				}

                                    $obj_xtpl->assign($obj_generico->CleanTextDb($row));
                                    $obj_xtpl->assign('sorteo', $row['nombre_sorteo']);
                                    if  ($row['numero']=='numero'){
                                        $j++;
                                        $obj_xtpl->assign('estilo_fila', 'evenred');
                                        $obj_xtpl->assign('id_resultado', '');
                                        $obj_xtpl->assign('id_sorteo', $row['id_sorteo']);
                                        $obj_xtpl->assign('numero', '<input id="txt_numero" name="txt_numero-'.$row['id_sorteo'].'" size="12" type="text" maxlength="3" />');
                                        $obj_xtpl->assign('terminal', '');

                                        if  ($row['zodiacal']==1){
                                            // Listado de Zodiacal
                                            if( $result_z= $obj_modelo->GetZodiacales() ){
                                                    while($row_z= $obj_conexion->GetArrayInfo($result_z)){
                                                            $obj_xtpl->assign($obj_generico->CleanTextDb($row_z));
                                                            $obj_xtpl->assign('Id_zodiacal', $row_z['Id_zodiacal']);
                                                            $obj_xtpl->assign('nombre_zodiacal', $row_z['nombre_zodiacal']);
                                                            $obj_xtpl->assign('selected', '');
                                                            $obj_xtpl->parse('main.contenido.lista_cargar_resultados.lista.lista_zodiacal.op_zodiacal');
                                                    }
                                                    $obj_xtpl->parse('main.contenido.lista_cargar_resultados.lista.lista_zodiacal');
                                            }
                                         }else if  ($row['zodiacal']==0){

                                         }

                                        $obj_xtpl->assign('aprox_arriba', '');
                                        $obj_xtpl->assign('aprox_abajo', '');

                                    }else{
                                        
                                        $obj_xtpl->assign('id_resultado', $row['id_resultado']);
                                        $obj_xtpl->assign('id_sorteo', $row['id_sorteo']);
                                        $obj_xtpl->assign('numero', '<input id="txt_numero" name="txt_numero-'.$row['id_sorteo'].'" size="12" value="'.$row['numero'].'"" type="text" maxlength="3" />');
                                        $obj_xtpl->assign('terminal', substr($row['numero'], 1,2));
                                        $obj_xtpl->assign('signo', substr($row['numero'], 1,2));

                                        if  ($row['zodiacal']==1){
                                            // Listado de Zodiacal
                                            if( $result_z= $obj_modelo->GetZodiacales() ){
                                                        while($row_z= $obj_conexion->GetArrayInfo($result_z)){
                                                                $obj_xtpl->assign($obj_generico->CleanTextDb($row_z));
                                                                $obj_xtpl->assign('Id_zodiacal', $row_z['Id_zodiacal']);
                                                                $obj_xtpl->assign('nombre_zodiacal', $row_z['nombre_zodiacal']);
                                                                if ($row_z['nombre_zodiacal']==$row['signo']){
                                                                    $obj_xtpl->assign('selected', 'selected');
                                                                }else{
                                                                    $obj_xtpl->assign('selected', '');
                                                                }

                                                                $obj_xtpl->parse('main.contenido.lista_cargar_resultados.lista.lista_zodiacal.op_zodiacal');
                                                        }
                                                        $obj_xtpl->parse('main.contenido.lista_cargar_resultados.lista.lista_zodiacal');
                                                }
                                        }


                                            $obj_xtpl->assign('aprox_arriba', (substr($row['numero'], 1,2)) +1);
                                            $obj_xtpl->assign('aprox_abajo', (substr($row['numero'], 1,2)) -1);
                                        }



                                    // Parseo del bloque de la fila
                                    $obj_xtpl->parse('main.contenido.lista_cargar_resultados.lista');
                                    $i++;

                            }
                        }else{
                                // Mensaje
                                $obj_xtpl->assign('sin_listado',$mensajes['sin_lista']);

                                // Parseo del bloque de la fila
                                $obj_xtpl->parse('main.contenido.lista_cargar_resultados.no_lista');
                        }
                        $obj_xtpl->assign('faltantes', '<span class="requerido">Faltan <b>'.$j.'</b> de <b>'.($i-1).' Sorteos</b> por ingresar resultados...</span>');
                    }



		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.lista_cargar_resultados');
               break;

        case 'save':
            $fecha_hora = $_GET['fecha'];
             if( $result= $obj_modelo->GetAllSorteos() ){
                if ($obj_conexion->GetNumberRows($result)>0 ){
                     while($row= $obj_conexion->GetArrayInfo($result)){

                             $numero = $_GET['txt_numero-'.$row['id_sorteo']];
                             $id_sorteo = $row['id_sorteo'];
                             
                             if (!$obj_generico->IsEmpty($numero)){
                                 if (strlen($numero)==3){
                                     if ($obj_modelo->GetTrueZodiacal($id_sorteo)){
                                        $zodiacal= $_GET['zodiacal-'.$row['id_sorteo']];
                                        if (!$obj_generico->IsEmpty($zodiacal)){
                                            if ($obj_modelo->GetResultadoSorteo($id_sorteo, $fecha_hora)== ""){ // Es un resultado nuevo a ingresar
                                               if ($obj_modelo->GuardarDatosResultados($id_sorteo, $zodiacal, $numero, $fecha_hora)){
                                                   PremiarGanadores(); // Premiamos los tickets ganadores
                                                   $_SESSION['mensaje']= $mensajes['info_agregada'];
                                                     header('location:'.$_SESSION['Ruta_Form']);
                                               }
                                            }else{ // Es una actualizacion de un resultado ingresado previamente
                                                $id_resultados= $obj_modelo->GetResultadoSorteo($id_sorteo, $fecha_hora);
                                                if ($obj_modelo->ActualizaDatosResultados($id_resultados, $id_sorteo, $zodiacal, $numero, $fecha_hora)){
                                                    PremiarGanadores(); // Premiamos los tickets ganadores
                                                   $_SESSION['mensaje']= $mensajes['info_agregada'];
                                                     header('location:'.$_SESSION['Ruta_Form']);
                                               }
                                            }
                                        }else{
                                            $_SESSION['mensaje']= 'Debe seleccionar un signo para los sorteos zodiacales! ';
                                            header('location:'.$_SESSION['Ruta_Form']);
                                        }

                                     }else{
                                        $zodiacal = 0;
                                        if ($obj_modelo->GetResultadoSorteo($id_sorteo, $fecha_hora)== ""){ // Es un resultado nuevo a ingresar
                                            if ($obj_modelo->GuardarDatosResultados($id_sorteo, $zodiacal, $numero, $fecha_hora)){
                                                PremiarGanadores(); // Premiamos los tickets ganadores
                                                $_SESSION['mensaje']= $mensajes['info_agregada'];
                                                header('location:'.$_SESSION['Ruta_Form']);
                                            }
                                        }else{ // Es una actualizacion de un resultado ingresado previamente
                                                $id_resultados= $obj_modelo->GetResultadoSorteo($id_sorteo, $fecha_hora);
                                                if ($obj_modelo->ActualizaDatosResultados($id_resultados, $id_sorteo, $zodiacal, $numero, $fecha_hora)){
                                                    PremiarGanadores(); // Premiamos los tickets ganadores
                                                   $_SESSION['mensaje']= $mensajes['info_agregada'];
                                                     header('location:'.$_SESSION['Ruta_Form']);
                                               }
                                            }
                                     }



                                 }else{
                                      $_SESSION['mensaje']= 'Los números ingresados deben ser de tres digitos! ';
                                      header('location:'.$_SESSION['Ruta_Form']);
                                 }
                             }
                     }
                }
             }
           
		break;
			
	default:
		
		// Ruta actual
		$_SESSION['Ruta_Lista']= $obj_generico->RutaRegreso();

                $obj_xtpl->assign('fecha', date('Y-m-d'));
                
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.buscar_resultados');
		
		break;
	
}


// Funcion para premiar los tickets ganadores
function PremiarGanadores(){
    global $obj_conexion;
   $obj_modelo= new Pagar_Ganador($obj_conexion);

    $where = " fecha_hora LIKE '%".date('Y-m-d')."%'";
    $result= $obj_modelo->GetListadosegunVariable($where);
    If ($obj_conexion->GetNumberRows($result)>0){
       $i=0; $j=0;
        while ($roww= $obj_conexion->GetArrayInfo($result)){
            $monto_total=0;
            $id_ticket=$roww["id_ticket"];
            $fecha_ticket= $obj_modelo->GetFechaTicket($id_ticket);
            $resultDT = $obj_modelo->GetAllDetalleTciket($id_ticket);

            while($rowDT= $obj_conexion->GetArrayInfo($resultDT)){

                // Verificamos si hay alguna apuesta ganadora...
                if ($obj_modelo->GetGanador($rowDT['id_sorteo'], $rowDT['id_zodiacal'], $rowDT['numero'], substr($fecha_ticket,0,10), $rowDT['id_tipo_jugada'])){
                   $id_detalle_ticket[$j]=$rowDT['id_detalle_ticket'];
                   
                    $monto_pago = $obj_modelo->GetRelacionPagos($rowDT['id_tipo_jugada']);
                    $monto_total = $monto_total + ($monto_pago*$rowDT['monto']);
                    
                }
                
                // Verificamos las aproximaciones por arriba y por abajo...
                if ($obj_modelo->GetAprox_abajo()){ // Si esta activa la aproximacion por abajo...
                    if ($rowDT['id_tipo_jugada']==2){ // Si el tipo de jugada es Terminal
                        // Verificamos si hay aproximaciones por abajo
                         if ($obj_modelo->GetAproximacion($rowDT['id_sorteo'], $rowDT['id_zodiacal'], $rowDT['numero'], substr($fecha_ticket,0,10), 'abajo')){
                             $id_detalle_ticket[$j]=$rowDT['id_detalle_ticket'];
                            
                             $monto_pago = $obj_modelo->GetRelacionPagos('5'); // Tipo Jugada Aproximacion
                             $monto_total = $monto_total + ($monto_pago*$rowDT['monto']);
                           
                         }
                    }
                }

                if ($obj_modelo->GetAprox_arriba()){ // Si esta activa la aproximacion por arriba...
                    if ($rowDT['id_tipo_jugada']==2){ // Si el tipo de jugada es Terminal
                        // Verificamos si hay aproximaciones por abajo
                         if ($obj_modelo->GetAproximacion($rowDT['id_sorteo'], $rowDT['id_zodiacal'], $rowDT['numero'], substr($fecha_ticket,0,10), 'arriba')){
                             $id_detalle_ticket[$j]=$rowDT['id_detalle_ticket'];
                             
                             $monto_pago = $obj_modelo->GetRelacionPagos('5'); // Tipo Jugada Aproximacion
                             $monto_total = $monto_total + ($monto_pago*$rowDT['monto']);
                            
                         }
                    }
                }

           }
             $id_tickets[$j]=$id_ticket;$j++;
             $totales[$i]=$monto_total; $i++;
        }
        
        // Premiamos los tickets
        for ($i = 0; $i < count($id_tickets); $i++){
             if( $obj_modelo->PremiarTicket($id_tickets[$i],$totales[$i])){

             }
        }
        for ($i = 0; $i < count($id_detalle_ticket); $i++){
             if( $obj_modelo->PagarDetalleTicket($id_detalle_ticket[$i])){}
        }
    }
}

$obj_xtpl->parse('main.contenido');

?>