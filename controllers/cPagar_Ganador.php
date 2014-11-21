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
$tiempo_vigencia = $obj_modelo->TiempoVigencia();
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
		$lista= $obj_modelo->GetPremiado($id_ticket);
		$total_registros= $obj_conexion->GetNumberRows($lista);
		if( $total_registros >0 ){
				$row= $obj_conexion->GetArrayInfo($lista);
				$fecha_ticket= strtotime(substr ($row['fecha_hora'],0,10));
				$fecha_actual =strtotime(date('Y-m-d'));
/*                //Detectando el Proximo Domingo
                $proximo_domingo=strtotime($fecha_ticket);
                $proximo_domingo=strtotime("next Sunday",$proximo_domingo);
              	if($proximo_domingo < $fecha_actual){
                	//quiere decir que hay un domingo de por medio
                    $tiempo_vigencia=$tiempo_vigencia+1;
                    $fecha_vencido_ticket = strtotime("+$tiempo_vigencia days", strtotime($fecha_ticket));
                   }else{*/
                		$fecha_vencido_ticket = strtotime("+$tiempo_vigencia days", strtotime($row['fecha_hora']));
//                   }                                
					// Verificamos que el ticket no este vencido...
                    if ($fecha_vencido_ticket >= $fecha_actual) {
                    	$monto_total=0;
                    	$i=0;
                    	// Si el ticket ganador es de hoy el parametro es 0 eso indica que tiene que buscar en tabla de ticket diario
                    	// Si el ticket ganador es de dias atrás el parametro es 1 indica que tiene que buscar en tabla de ticket
                    	if($fecha_ticket<$fecha_actual){
                    		$id_ticket=$row['id_ticket'];
                    		$param=1;
                    	}
                    	else{
                    		$id_ticket=$row['id_ticket_diario'];
                    		$param=0;
                    	}
						$resultDT = $obj_modelo->GetDetalleTciket($id_ticket,$param);
                        while($rowDT= $obj_conexion->GetArrayInfo($resultDT)){
	                    if( ($i % 2) >0)
	                    	$obj_xtpl->assign('estilo_fila', 'even');
	                    else
	                    $obj_xtpl->assign('estilo_fila', 'odd');
                        // Asignacion de los datos
                        $obj_xtpl->assign('sorteo', $obj_generico->CleanTextDb($rowDT["nombre_sorteo"]));
                        $hora_sorteo= $obj_modelo->GetHoraSorteo($rowDT['id_sorteo']);
                        $obj_xtpl->assign('numero', $obj_generico->CleanTextDb($rowDT["numero"]));
                        $obj_xtpl->assign('tipo_jugada', $obj_generico->CleanTextDb($rowDT["nombre_jugada"]));
                        $obj_xtpl->assign('zodiacal', $obj_generico->CleanTextDb($rowDT["nombre_zodiacal"]));
                        $obj_xtpl->assign('monto', $obj_generico->CleanTextDb($rowDT["monto"]));
						$monto_total = $monto_total + $rowDT['total_premiado'];
						$obj_xtpl->assign('monto_ganado', $rowDT['total_premiado']);
                        // Parseo del bloque de la fila
                        $obj_xtpl->parse('main.contenido.detalle_ticket.lista');
                        $i++;
					}
					$_SESSION['mensaje']= "Total a pagar :  Bs. ".$monto_total;
                  //  $obj_xtpl->assign('total_premiado', $monto_total);
                    $obj_xtpl->assign('mensaje',$_SESSION['mensaje']);
                    $obj_xtpl->parse('main.contenido.detalle_ticket.boton_pagar');
                    // Datos para la paginacion
                    $obj_xtpl->parse('main.contenido.detalle_ticket');
				}else{ // Ticket vencido
                	// Mensaje
                	$_SESSION['mensaje']= $mensajes['ticket_vencido'];
                    header('location:'.$_SESSION['Ruta_Lista']);
				}
			
		}
		else{
			$_SESSION['mensaje']= $mensajes['no_ticket'];
			header('location:'.$_SESSION['Ruta_Lista']);
		}

		break;

        case 'looking_serial':
                 // Ruta actual
        		$obj_xtpl->assign('id_ticket', $_GET['id_ticket']);
        	
				$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_ticket']);
                $_SESSION['Ruta_serial']= $obj_generico->RutaRegreso();
                $obj_xtpl->assign('total_premiado', $_GET['total_premiado']);
		
		// Parseo del bloque
			$obj_xtpl->parse('main.contenido.busqueda_serial');
                break;
        case 'pagar_ticket':
	// Ruta regreso
			$id_ticket= $obj_generico->CleanText($_GET['id_ticket']);
        //	$fecha_ticket= $obj_modelo->GetFechaTicket($id_ticket);
			$serial= $obj_generico->CleanText($_GET['serial']);
           // $total_premiado = $obj_generico->CleanText($_GET['total_premiado']);
                
                if(!$obj_generico->IsEmpty($serial)){
                    // $where = "serial='".$serial."' ";
                    
                    $taquilla= $obj_modelo->GetIdTaquilla();
                    $id_usuario= $obj_modelo->GetIdUsuario();
                    
                     // Busca el listado de la informacion.
                        $lista= $obj_modelo->GetPremiado($id_ticket);
                        /*$row= $obj_conexion->GetArrayInfo($lista);
                        print_r($row);*/
                        if($row= $obj_conexion->GetArrayInfo($lista))
                        	if($row['serial']==$serial)
                        	{
                        		$fecha_ticket= strtotime(substr ($row['fecha_hora'],0,10));
                        		$fecha_actual =strtotime(date('Y-m-d'));
                        		// Si el ticket ganador es de hoy el parametro es 0 eso indica que tiene que buscar en tabla de ticket diario
                        		// Si el ticket ganador es de dias atrás el parametro es 1 indica que tiene que buscar en tabla de ticket
                        		if($fecha_ticket<$fecha_actual)
                        		{
                        			$param="ticket";
                        		}
                        		else
                        		{
                        			$param="ticket_diario";
                        		}
                        		
                        		
                 				if( $obj_modelo->PagarTicket($row['id_'.$param], $taquilla, $id_usuario,$param)){
                 					$_SESSION['mensaje']= $mensajes['serial_coincide'];
                                    //header('location:'.$_SESSION['Ruta_Lista']);
                                }
                            	else{
                            		$_SESSION['mensaje']= $mensajes['fallo_modificar'];
                            		//header('location:'.$_SESSION['Ruta_ticket']);
                            	}
                        	}
                          	else{
                           		$_SESSION['mensaje']= $mensajes['serial_no_coincide'];
                            	//header('location:'.$_SESSION['Ruta_serial']);
                            }
                        
                      
                }
                else{
                    // Mensaje
                     $_SESSION['mensaje']= $mensajes['serial_no_coincide'];
                      //header('location:'.$_SESSION['Ruta_serial']);

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