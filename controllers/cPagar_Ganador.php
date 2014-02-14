<?php
/**
 * Archivo del controlador para modulo Pagar Ganador
 * @package cPagar_Ganador.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Abril - 2013
 */

// Vista asignada
$obj_xtpl->assign_file('contenido', $obj_config->GetVar('ruta_vista').'pagar_ganador'.$obj_config->GetVar('ext_vista'));

// Modelo asignado
require($obj_config->GetVar('ruta_modelo').'Pagar_Ganador.php');

$obj_modelo= new Pagar_Ganador($obj_conexion);

$id_detalle_ticket[]="";

switch (ACCION){

        // Para la busqueda
	case 'buscar_ticket':

		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Lista']);
                $_SESSION['Ruta_ticket']= $obj_generico->RutaRegreso();
                
                // Para la paginacion
		if(empty($_GET['pg'])){
			$pag= 1;
		}
		else{
			$pag= $_GET['pg'];
		}

		$id_ticket= $obj_generico->CleanText($_GET['id_ticket']);
                $obj_xtpl->assign('id_ticket', $id_ticket);

                $where = "";
		if(!$obj_generico->IsEmpty($id_ticket)){
                    $where = $where. " id_ticket='".$id_ticket."' " ;
                }

                
		// Busca el listado de la informacion.
		$lista= $obj_modelo->GetListadosegunVariable2($where);
		$total_registros= $obj_conexion->GetNumberRows($lista);
		if( $total_registros >0 ){
			while($row= $obj_conexion->GetArrayInfo($lista)){
				$fecha_ticket= $obj_modelo->GetFechaTicket($row['id_ticket']);
                                $tiempo_vigencia = $obj_modelo->TiempoVigencia();
                                $fecha_actual =strtotime(date('Y-m-d'));
                                $fecha_vencido_ticket = strtotime("+$tiempo_vigencia days", strtotime($fecha_ticket));

                                // Verificamos que el ticket no este vencido...
                                if ($fecha_vencido_ticket >= $fecha_actual) {
                                    $resultDT = $obj_modelo->GetDetalleTciket($row['id_ticket'], 300,$pag);

                                    $i=1; $j=0; $monto_total=0;
                                    while($rowDT= $obj_conexion->GetArrayInfo($resultDT['result'])){
                                        if( ($i % 2) >0){
                                            $obj_xtpl->assign('estilo_fila', 'even');
                                        }
                                        else{
                                            $obj_xtpl->assign('estilo_fila', 'odd');
                                        }

                                        // Asignacion de los datos
                                        $obj_xtpl->assign('sorteo', $obj_generico->CleanTextDb($rowDT["nombre_sorteo"]));
                                        
                                        $hora_sorteo= $obj_modelo->GetHoraSorteo($rowDT['id_sorteo']);
                                        
                                        
                                        $obj_xtpl->assign('hora_sorteo', $obj_generico->CleanTextDb($hora_sorteo));
                                        $obj_xtpl->assign('numero', $obj_generico->CleanTextDb($rowDT["numero"]));
                                        $obj_xtpl->assign('tipo_jugada', $obj_generico->CleanTextDb($rowDT["nombre_jugada"]));
                                        $obj_xtpl->assign('zodiacal', $obj_generico->CleanTextDb($rowDT["nombre_zodiacal"]));
                                        $obj_xtpl->assign('monto', $obj_generico->CleanTextDb($rowDT["monto"]));

                                        // Verificamos si hay alguna apuesta ganadora...
                                        if ($obj_modelo->GetGanador($rowDT['id_sorteo'], $rowDT['id_zodiacal'], $rowDT['numero'], substr($fecha_ticket,0,10), $rowDT['id_tipo_jugada'])){
                                            $j++; $id_detalle_ticket[$j]=$rowDT['id_detalle_ticket'];
                                            $monto_pago = $obj_modelo->GetRelacionPagos($rowDT['id_tipo_jugada']);
                                            $monto_total = $monto_total + ($monto_pago*$rowDT['monto']);
                                            $obj_xtpl->assign('monto_ganado', $monto_pago*$rowDT['monto']);
                                        }else{
                                            //$obj_xtpl->assign('monto_ganado', "NO Ganador");
                                            $obj_xtpl->assign('monto_ganado', "");
                                        }

                                        // Verificamos las aproximaciones por arriba y por abajo...
                                        if ($obj_modelo->GetAprox_abajo()){ // Si esta activa la aproximacion por abajo...
                                            if ($rowDT['id_tipo_jugada']==2){ // Si el tipo de jugada es Terminal
                                                // Verificamos si hay aproximaciones por abajo
                                                 if ($obj_modelo->GetAproximacion($rowDT['id_sorteo'], $rowDT['id_zodiacal'], $rowDT['numero'], substr($fecha_ticket,0,10), 'abajo')){
                                                     $j++; $id_detalle_ticket[$j]=$rowDT['id_detalle_ticket'];
                                                     $monto_pago = $obj_modelo->GetRelacionPagos('5'); // Tipo Jugada Aproximacion
                                                     $monto_total = $monto_total + ($monto_pago*$rowDT['monto']);
                                                     $obj_xtpl->assign('aprox_abajo', $monto_pago*$rowDT['monto']);
                                                 }
                                            }
                                        }

                                        if ($obj_modelo->GetAprox_arriba()){ // Si esta activa la aproximacion por arriba...
                                            if ($rowDT['id_tipo_jugada']==2){ // Si el tipo de jugada es Terminal
                                                // Verificamos si hay aproximaciones por abajo
                                                 if ($obj_modelo->GetAproximacion($rowDT['id_sorteo'], $rowDT['id_zodiacal'], $rowDT['numero'], substr($fecha_ticket,0,10), 'arriba')){
                                                     $j++; $id_detalle_ticket[$j]=$rowDT['id_detalle_ticket'];
                                                     $monto_pago = $obj_modelo->GetRelacionPagos('5'); // Tipo Jugada Aproximacion
                                                     $monto_total = $monto_total + ($monto_pago*$rowDT['monto']);
                                                     $obj_xtpl->assign('aprox_arriba', $monto_pago*$rowDT['monto']);
                                                 }
                                            }
                                        }

                                        // Parseo del bloque de la fila
                                        $obj_xtpl->parse('main.contenido.detalle_ticket.lista');
                                        $i++;
                                    }

                                    if ($j>0){
                                        $_SESSION['mensaje']= "Total a pagar :  Bs. ".$monto_total." de ".$j." apuestas.";
                                        $obj_xtpl->assign('total_premiado', $monto_total);
                                        $_SESSION['id_detalle_ticket']=$id_detalle_ticket;
                                        $obj_xtpl->assign('mensaje',$_SESSION['mensaje']);
                                        $obj_xtpl->parse('main.contenido.detalle_ticket.boton_pagar');
                                    }else{
                                        $_SESSION['mensaje']= "Este ticket no tiene apuestas ganadoras!";	
										$obj_xtpl->assign('mensaje',$_SESSION['mensaje']);
                                    }
                                    // Datos para la paginacion
                                    $paginacion= $obj_generico->paginacion($resultDT['pagina'],$resultDT['total_paginas'],$resultDT['total_registros'],$obj_generico->urlPaginacion());
                                    $obj_xtpl->assign('paginacion',$paginacion);
                                    
                                    $obj_xtpl->parse('main.contenido.detalle_ticket');

                                }else{ // Ticket vencido
                                    // Mensaje
                                     $_SESSION['mensaje']= $mensajes['ticket_vencido'];
                                     header('location:'.$_SESSION['Ruta_Lista']);
                                }
			}
		}
		else{
			$_SESSION['mensaje']= $mensajes['no_ticket'];
                       header('location:'.$_SESSION['Ruta_Lista']);
		}

		break;

        case 'looking_serial':
                 // Ruta actual
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_ticket']);
                $_SESSION['Ruta_serial']= $obj_generico->RutaRegreso();
                $obj_xtpl->assign('total_premiado', $_GET['total_premiado']);
		
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.busqueda_serial');
                break;
        case 'pagar_ticket':
	// Ruta regreso

		$id_ticket= $obj_generico->CleanText($_GET['id_ticket']);
                $fecha_ticket= $obj_modelo->GetFechaTicket($id_ticket);
		$serial= $obj_generico->CleanText($_GET['serial']);
                $total_premiado = $obj_generico->CleanText($_GET['total_premiado']);
                $id_detalle_ticket_2[] = $_SESSION['id_detalle_ticket'];
                $id_detalle= $id_detalle_ticket_2[0];
                
                if(!$obj_generico->IsEmpty($serial)){
                     $where = "serial='".$serial."' ";
                     // Busca el listado de la informacion.
                        $lista= $obj_modelo->GetListadosegunVariable2($where);
                        $total_registros= $obj_conexion->GetNumberRows($lista);
                        if( $total_registros >0 ){
                            $row= $obj_conexion->GetArrayInfo($lista);
                                 // Actualizamos el ticket a premiado y pagado
                                if( $obj_modelo->PagarTicket($row['id_ticket'], $total_premiado)){
                                    // Actualizamos el estado premiado en cada una de las apuestas del ticket
                                   /* for ($i = 0; $i < count($id_detalle); $i++){
                                           //   if( $obj_modelo->PagarDetalleTicket($id_detalle[$i])){}
                                    }*/                                      
                                    $_SESSION['mensaje']= $mensajes['serial_coincide'];
                                     header('location:'.$_SESSION['Ruta_Lista']);
                                }
                                else{
                                        $_SESSION['mensaje']= $mensajes['fallo_modificar'];
                                        header('location:'.$_SESSION['Ruta_ticket']);
                                }
                            }else{
                                 $_SESSION['mensaje']= $mensajes['serial_no_coincide'];
                                 header('location:'.$_SESSION['Ruta_serial']);
                            }
                }
                else{
                    // Mensaje
                     $_SESSION['mensaje']= $mensajes['serial_no_coincide'];
                      header('location:'.$_SESSION['Ruta_serial']);

                }
		break;
                
	default:
		
		// Ruta actual
		$_SESSION['Ruta_Lista']= $obj_generico->RutaRegreso();
				
		
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.busqueda_ticket');
		
		break;
	
}

$obj_xtpl->parse('main.contenido');

?>