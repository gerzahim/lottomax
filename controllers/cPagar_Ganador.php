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


switch (ACCION){

        // Para la busqueda
	case 'buscar_ticket':

		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Lista']);

                // Para la paginacion
		if(empty($_GET['pg'])){
			$pag= 1;
		}
		else{
			$pag= $_GET['pg'];
		}

		$id_ticket= $obj_generico->CleanText($_GET['id_ticket']);
                
                $where = "";
		if(!$obj_generico->IsEmpty($id_ticket)){
                    $where = $where. " id_ticket='".$id_ticket."' " ;
                }

                
		// Busca el listado de la informacion.
		$lista= $obj_modelo->GetListadosegunVariable($where);
		$total_registros= $obj_conexion->GetNumberRows($lista);
		if( $total_registros >0 ){
			
			while($row= $obj_conexion->GetArrayInfo($lista)){
				$fecha_ticket= $obj_modelo->GetFechaTicket($row['id_ticket']);
                                $tiempo_vigencia = $obj_modelo->TiempoVigencia();
                                $fecha_actual =strtotime(date('Y-m-d'));
                                $fecha_vencido_ticket = strtotime("+$tiempo_vigencia days", strtotime($fecha_ticket));

                                // Verificamos que el ticket no este vencido...
                                if ($fecha_vencido_ticket >= $fecha_actual) {
                                    $resultDT = $obj_modelo->GetDetalleTciket($row['id_ticket'],$obj_config->GetVar('num_registros'),$pag);
                                    $resultR = $obj_modelo->GetResultados($fecha_ticket);

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
                                        $obj_xtpl->assign('hora_sorteo', $obj_generico->CleanTextDb($rowDT["hora_sorteo"]));
                                        $obj_xtpl->assign('numero', $obj_generico->CleanTextDb($rowDT["numero"]));
                                        $obj_xtpl->assign('tipo_jugada', $obj_generico->CleanTextDb($rowDT["nombre_jugada"]));
                                        $obj_xtpl->assign('zodiacal', $obj_generico->CleanTextDb($rowDT["nombre_zodiacal"]));
                                        $obj_xtpl->assign('monto', $obj_generico->CleanTextDb($rowDT["monto"]));

                                        // Verificamos si hay alguna apuesta ganadora...
                                        //while($rowR = $obj_conexion->GetArrayInfo($resultR)){
                                            if ($obj_modelo->GetGanador($rowDT['id_sorteo'], $rowDT['id_zodiacal'], $rowDT['numero'], substr($fecha_ticket,0,10))){
                                                $j++;
                                                $monto_pago = $obj_modelo->GetRelacionPagos($rowDT['id_tipo_jugada']);
                                                $monto_total = $monto_total + ($monto_pago*$rowDT['monto']);
                                                $obj_xtpl->assign('monto_ganado', $monto_pago*$rowDT['monto']);
                                            }else{
                                                $obj_xtpl->assign('monto_ganado', "NO Ganador");
                                            }

                                        //}

                                        // Parseo del bloque de la fila
                                        $obj_xtpl->parse('main.contenido.detalle_ticket.lista');
                                        $i++;
                                    }

                                    if ($j>0){
                                        $_SESSION['mensaje']= "Total a pagar: ".$monto_total." de ".$j." apuestas.";
                                    }else{
                                        $_SESSION['mensaje']= "Este ticket no tiene apuestas ganadoras!";
                                    }
                                    // Datos para la paginacion
                                    $paginacion= $obj_generico->paginacion($resultDT['pagina'],$resultDT['total_paginas'],$resultDT['total_registros'],$obj_generico->urlPaginacion());
                                    $obj_xtpl->assign('paginacion',$paginacion);
                                    
                                    $obj_xtpl->parse('main.contenido.detalle_ticket');

                                }else{ // Tciket vencido
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

		
	default:
		
		// Ruta actual
		$_SESSION['Ruta_Lista']= $obj_generico->RutaRegreso();
				
		
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.busqueda_ticket');
		
		break;
	
}

$obj_xtpl->parse('main.contenido');

?>