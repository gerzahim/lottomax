<?php
/**
 * Archivo del controlador para modulo Anular Tickets
 * @package cAnularTicket.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Abril - 2013
 */

// Vista asignada
$obj_xtpl->assign_file('contenido', $obj_config->GetVar('ruta_vista').'anular_ticket'.$obj_config->GetVar('ext_vista'));

// Modelo asignado
require($obj_config->GetVar('ruta_modelo').'AnularTicket.php');

$obj_modelo= new AnularTicket($obj_conexion);
$obj_date= new Fecha();


switch (ACCION){

        // Para la busqueda
	case 'search':

		// Ruta actual
		$_SESSION['Ruta_Search']= $obj_generico->RutaRegreso();

		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Lista']);

                
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.busqueda');
		break;

        // Para la busqueda
	case 'looking':

		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Search']);

		$id_ticket= $obj_generico->CleanText($_GET['id_ticket']);
		$serial= $obj_generico->CleanText($_GET['serial']);
                
                $where = "";
		if(!$obj_generico->IsEmpty($id_ticket)){
                    $where = $where. " id_ticket='".$id_ticket."' AND " ;
                }

                if(!$obj_generico->IsEmpty($serial)){
                     $where = $where. "serial='".$serial."' AND ";
                }

                $where = substr($where, 0,strlen($where) - 5);
                
		// Busca el listado de la informacion.
		$lista= $obj_modelo->GetListadosegunVariable($where);
		$total_registros= $obj_conexion->GetNumberRows($lista);
		if( $total_registros >0 ){
			$i=1;
			while($row= $obj_conexion->GetArrayInfo($lista)){
				if( ($i % 2) >0){
					$obj_xtpl->assign('estilo_fila', 'even');
				}
				else{
					$obj_xtpl->assign('estilo_fila', 'odd');
				}

				// Asignacion de los datos
                                $obj_xtpl->assign('id_ticket', $obj_generico->CleanTextDb($row["id_ticket_diario"]));
                                $obj_xtpl->assign('fecha_hora', $obj_date->changeFormatDateI($obj_generico->CleanTextDb($row["fecha_hora"]),1) );
                                $obj_xtpl->assign('total_ticket', $obj_generico->CleanTextDb($row["total_ticket"]));

				// Parseo del bloque de la fila
				$obj_xtpl->parse('main.contenido.lista_anular_ticket.lista');
                               
				$i++;
			}
		}
		else{
			// Mensaje
			$obj_xtpl->assign('sin_listado',$mensajes['sin_lista']);

			// Parseo del bloque de la fila
			$obj_xtpl->parse('main.contenido.lista_anular_ticket.no_lista');
		}

		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.lista_anular_ticket');
		break;

	case 'del':

		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Lista']);
                
		$id_ticket = $_GET['id'];
                
                $fecha_ticket= strtotime($obj_modelo->GetFechaTicket($id_ticket));

                $minutos_anulacion=$obj_modelo->MinutosAnulacion();
                $fecha_hora_actual= strtotime(date('Y-m-d H:i:s'));
                $fecha_actual=date('Y-m-d');


                // Validamos que no haya transcurrido, desde la generacion del ticket, un tiempo superior al configurado como limite para anulacion de tickets
                if (($fecha_hora_actual-$fecha_ticket)<=($minutos_anulacion*60) ){

                    // Verificamos que los sorteos en el ticket no esten cerrados y que el ticket a eliminar sea de hoy...
                    if (!$obj_modelo->ValidaSorteosTicket($id_ticket) && $fecha_actual==substr($obj_modelo->GetFechaTicket($id_ticket), 0,10)){

                        // Eliminamos el ticket
                        if( $obj_modelo->EliminarTicket($id_ticket)){
                        	//Reestablecer Incompletos y Agotados
                        	$obj_modelo->ReestablecerImcompletosyJugados($id_ticket);
                                $_SESSION['mensaje']= $mensajes['ticket_anulado'];
                        }
                        else{
                                $_SESSION['mensaje']= $mensajes['fallo_eliminar'];
                        }
                    }else{
                         $_SESSION['mensaje']= $mensajes['sorteo_cerrado'];
                    }


                    header('location:'.$_SESSION['Ruta_Lista']);
                }else{
                    $obj_xtpl->assign('id_ticket',$id_ticket );
                    $obj_xtpl->parse('main.contenido.busqueda_serial');
                }		
		break;
		
          case 'looking_serial':

		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Search']);

		$id_ticket= $obj_generico->CleanText($_GET['id_ticket']);
		$serial= $obj_generico->CleanText($_GET['serial']);
                $fecha_actual=date('Y-m-d');

                $where = "";
		if(!$obj_generico->IsEmpty($id_ticket)){
                    $where = $where. " id_ticket_diario='".$id_ticket."' AND " ;
                }

                if(!$obj_generico->IsEmpty($serial)){
                     $where = $where. "serial='".$serial."' AND ";
                }

                $where = substr($where, 0,strlen($where) - 5);

		// Busca el listado de la informacion.
		$lista= $obj_modelo->GetListadosegunVariable($where);
		$total_registros= $obj_conexion->GetNumberRows($lista);
		if( $total_registros >0 ){
                    $row= $obj_conexion->GetArrayInfo($lista);

                    // Verificamos que los sorteos en el ticket no esten cerrados y que el ticket a eliminar sea de hoy...
                    if (!$obj_modelo->ValidaSorteosTicket($id_ticket)  && $fecha_actual==substr($obj_modelo->GetFechaTicket($id_ticket), 0,10)){

                         // Eliminamos el ticket
                        if( $obj_modelo->EliminarTicket($row['id_ticket_diario'])){
                        	//Reestablecer Incompletos y Agotados
                        	$obj_modelo->ReestablecerImcompletosyJugados($id_ticket,$row['fecha_hora']);
                            $_SESSION['mensaje']= $mensajes['info_eliminada'];
                        }
                        else{
                                $_SESSION['mensaje']= $mensajes['fallo_eliminar'];
                        }
                    }else{
                         $_SESSION['mensaje']= $mensajes['sorteo_cerrado'];
                    }
                    header('location:'.$_SESSION['Ruta_Lista']);

		}
		else{
                    // Mensaje
                     $_SESSION['mensaje']= $mensajes['serial_no_coincide'];
                     header('location:'.$_SESSION['Ruta_Lista']);
			
		}
               
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.lista_anular_ticket');
		break;
		
		
          case 'anular_clave':

		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Lista']);
                
		$id_ticket = $_GET['id'];
              
		$obj_xtpl->assign('id_ticket',$id_ticket );
        $obj_xtpl->parse('main.contenido.anular_clave');
                
		break;
		
         case 'looking_clave':

		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Search']);

		$id_ticket= $obj_generico->CleanText($_GET['id_ticket']);
		
		$clave= $obj_generico->CleanText($_GET['clave']);
        $fecha_actual=date('Y-m-d');
		
        $fecha_clave=date('YmdH');
        $clave_sistema = substr(md5($fecha_clave),0,7); 
  		//echo $fecha_actual," - ",$clave_sistema;
  			
		if($clave == $clave_sistema){

			// Verificamos que los sorteos en el ticket no esten cerrados y que el ticket a eliminar sea de hoy...
                    if (!$obj_modelo->ValidaSorteosTicket($id_ticket)  && $fecha_actual==substr($obj_modelo->GetFechaTicket($id_ticket), 0,10)){

                         // Eliminamos el ticket
                        if( $obj_modelo->EliminarTicket($id_ticket)){
                            $_SESSION['mensaje']= $mensajes['info_eliminada'];
                        }
                        else{
                                $_SESSION['mensaje']= $mensajes['fallo_eliminar'];
                        }
                    }else{
                         $_SESSION['mensaje']= $mensajes['sorteo_cerrado'];
                    }
                    header('location:'.$_SESSION['Ruta_Lista']);

		}
		else{
                    // Mensaje
                     $_SESSION['mensaje']= $mensajes['clave_no_coincide'];
                     header('location:'.$_SESSION['Ruta_Lista']);
			
		}
               
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.lista_anular_ticket');
		break;		
				
	// Muestra el listado		
	default:
		
		// Ruta actual
		$_SESSION['Ruta_Lista']= $obj_generico->RutaRegreso();
				
		// Para la paginacion
		if(empty($_GET['pg'])){
			$pag= 1;
		}
		else{
			$pag= $_GET['pg'];
		}
		
		// Busca el listado de la informacion.
		$lista= $obj_modelo->GetListado($obj_config->GetVar('num_registros'),$pag);
		if( $lista['total_registros']>0 ){
			$i=1;
			while($row= $obj_conexion->GetArrayInfo($lista['result'])){
				if( ($i % 2) >0){
					$obj_xtpl->assign('estilo_fila', 'even');
				}
				else{
					$obj_xtpl->assign('estilo_fila', 'odd');
				}
				
				// Asignacion de los datos
				$obj_xtpl->assign('id_ticket', $obj_generico->CleanTextDb($row["id_ticket_diario"]));
                               $obj_xtpl->assign('fecha_hora', $obj_date->changeFormatDateI($obj_generico->CleanTextDb($row["fecha_hora"]),1));
                                   $obj_xtpl->assign('total_ticket', $obj_generico->CleanTextDb($row["total_ticket"]));
                               
						
				// Parseo del bloque de la fila  
				$obj_xtpl->parse('main.contenido.lista_anular_ticket.lista');
				
				$i++;
			}
		}
		else{
			// Mensaje
			$obj_xtpl->assign('sin_listado',$mensajes['sin_lista']);
			
			// Parseo del bloque de la fila
			$obj_xtpl->parse('main.contenido.lista_anular_ticket.no_lista');
		}
	
		// Datos para la paginacion
		$paginacion= $obj_generico->paginacion($lista['pagina'],$lista['total_paginas'],$lista['total_registros'],$obj_generico->urlPaginacion());
		$obj_xtpl->assign('paginacion',$paginacion);
		
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.lista_anular_ticket');
		
		break;
	
}

$obj_xtpl->parse('main.contenido');

?>