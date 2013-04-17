<?php
/**
 * Archivo del controlador para modulo Copiar Tickets
 * @package cCopiarTicket.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Abril - 2013
 */

// Vista asignada
$obj_xtpl->assign_file('contenido', $obj_config->GetVar('ruta_vista').'copiar_ticket'.$obj_config->GetVar('ext_vista'));

// Modelo asignado
require($obj_config->GetVar('ruta_modelo').'CopiarTicket.php');

$obj_modelo= new CopiarTicket($obj_conexion);


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
                
                $where = "";
		if(!$obj_generico->IsEmpty($id_ticket)){
                    $where = $where. " id_ticket='".$id_ticket."' AND " ;
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
                                $obj_xtpl->assign('id_ticket', $obj_generico->CleanTextDb($row["id_ticket"]));
                                $obj_xtpl->assign('fecha_hora', $obj_generico->CleanTextDb($row["fecha_hora"]));
                                $obj_xtpl->assign('total_ticket', $obj_generico->CleanTextDb($row["total_ticket"]));

				// Parseo del bloque de la fila
				$obj_xtpl->parse('main.contenido.lista_copiar_ticket.lista');
                               
				$i++;
			}
		}
		else{
			// Mensaje
			$obj_xtpl->assign('sin_listado',$mensajes['sin_lista']);

			// Parseo del bloque de la fila
			$obj_xtpl->parse('main.contenido.lista_copiar_ticket.no_lista');
		}

		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.lista_copiar_ticket');
		break;

	case 'copy':

		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Lista']);
                
		$id_ticket = $_GET['id'];

                $datos= $obj_modelo->GetDetalleTicket($id_ticket);
		$total_registros= $obj_conexion->GetNumberRows($datos);
		if( $total_registros >0 ){
			while($row= $obj_conexion->GetArrayInfo($datos)){

                            //Proceso de copiar el ticket

                            // Verificamos que el sorteo este activo
                                $hora_sorteo=$row['hora_sorteo'];
                                $hora_sorteo= strtotime($hora_sorteo);

                                //Valor que debe venir de la base de datos
                                $minutos_bloqueo= $obj_modelo->MinutosBloqueo();

                                //Valor que debe venir de la base de datos
                                $hora_actualMas= strtotime("+$minutos_bloqueo minutes");

                                if ($hora_actualMas < $hora_sorteo){
                                    // Verifica si el sorteo es Zodiacal
                                    if($obj_modelo->GetTrueZodiacal($row['id_sorteo'])){

                                            //El sorteo si es zodiacal !
                                            $eszodiacal=1;

                                            //Proceso_Cupo() funcion para determinar los cupos
                                            //echo $row['numero'], $row['monto'], $row['id_sorteo'], $row['id_zodiacal'];

                                            $result = ProcesoCupos($row['numero'], $row['monto'], $row['id_sorteo'], $row['id_zodiacal'], $eszodiacal);

                                     }else{

                                            //El sorteo no es zodiacal !
                                            $eszodiacal=0;
                                            $zodiacal=0;

                                            //Proceso_Cupo() funcion para determinar los cupos

                                            $result = ProcesoCupos($row['numero'], $row['monto'], $row['id_sorteo'], $row['id_zodiacal'], $eszodiacal);

                                    }

                                }
                            
                        }
                        header('location:index.php?op=ventas');
                }
		//echo $id_ticket;exit();
		break;
          case 'looking_serial':

		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Search']);

		$id_ticket= $obj_generico->CleanText($_GET['id_ticket']);
		$serial= $obj_generico->CleanText($_GET['serial']);
                $fecha_actual=date('Y-m-d');

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
                    $row= $obj_conexion->GetArrayInfo($lista);

                    // Verificamos que los sorteos en el ticket no esten cerrados y que el ticket a eliminar sea de hoy...
                    if (!$obj_modelo->ValidaSorteosTicket($id_ticket)  && $fecha_actual==substr($obj_modelo->GetFechaTicket($id_ticket), 0,10)){

                         // Eliminamos el ticket
                        if( $obj_modelo->EliminarTicket($row['id_ticket'])){
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
		$obj_xtpl->parse('main.contenido.lista_copiar_ticket');
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
				$obj_xtpl->assign('id_ticket', $obj_generico->CleanTextDb($row["id_ticket"]));
                                $obj_xtpl->assign('fecha_hora', $obj_generico->CleanTextDb($row["fecha_hora"]));
                                $obj_xtpl->assign('total_ticket', $obj_generico->CleanTextDb($row["total_ticket"]));
                               
						
				// Parseo del bloque de la fila  
				$obj_xtpl->parse('main.contenido.lista_copiar_ticket.lista');
				
				$i++;
			}
		}
		else{
			// Mensaje
			$obj_xtpl->assign('sin_listado',$mensajes['sin_lista']);
			
			// Parseo del bloque de la fila
			$obj_xtpl->parse('main.contenido.lista_copiar_ticket.no_lista');
		}
	
		// Datos para la paginacion
		$paginacion= $obj_generico->paginacion($lista['pagina'],$lista['total_paginas'],$lista['total_registros'],$obj_generico->urlPaginacion());
		$obj_xtpl->assign('paginacion',$paginacion);
		
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.lista_copiar_ticket');
		
		break;
	
}

function ProcesoCupos($txt_numero,$txt_monto, $sorteo, $zodiacal, $esZodiacal){

	global $obj_modelo;
	global $obj_conexion;

	//determinando el tipo de jugada
	$id_tipo_jugada= $obj_modelo->GetTipoJugada($esZodiacal,$txt_numero);

	//revisar tabla de ticket_transaccional
	$numero_jugadoticket= $obj_modelo->GetTicketTransaccional($txt_numero,$sorteo,$zodiacal);

	if ( $numero_jugadoticket['total_registros']>0 ){

		//adicionar y dar mensaje de confirm (adicionar al ticket u obviar)

		//significa que ya existe y debemos ver el monto que queda
		$num_ticket_faltante = $numero_jugadoticket['monto_faltante'];
		$num_ticket_monto = $numero_jugadoticket['monto'];
		$num_ticket_inc = $numero_jugadoticket['incompleto'];

		//Verificando que si esta incompleto
		if ($num_ticket_inc == '1'){

			// ya no se puede anadir, mas bien le falto por jugar
			//echo "El numero ya esta jugado y tiene su cupo completo";

			//$_SESSION['mensaje']= $mensajes['numero_repetido_eincompleto'];
			echo "<div id='mensaje' class='mensaje' >El numero ya esta jugado y tiene su cupo completo !!!</div>";

		}else{
                        if ($num_ticket_inc == '0'){
                            /************* CABLEADO **********************/
                            //no esta incompleto y todavia le queda cupo disponible

                            // hacer javascript confirm,  para que modifique el monto y simplemente cancele porque fue un error de tipeo
                            // en caso de que el confirm se positivo, tomar el valor y crear metodo para modificar el nuevo valor en ticket_transaccional

                            echo "CONFIRMAPUESTA-".$txt_numero; //" falta crear javascript CONFIRM-".$num_ticket_monto;
                            //echo "RRR-Numero ya registrado, falta crear javascript*numero*monto*";
                            //$texto = "<script language='javascript' type='text/javascript'>alert('Hola');</script>";
                            //echo $texto;
                        }
		}

	}else{


	//revisar tabla de numeros_jugados
	$numero_jugado= $obj_modelo->GetNumerosJugados($txt_numero,$sorteo,$zodiacal);

        //significa que ya existe y debemos ver el monto que queda
	$num_jug = $numero_jugado['monto_restante'];

        if( $numero_jugado['total_registros']>0 ){

		//print_r($numero_jugado);

		//si queda por un monto mayor que 0
		if ($num_jug >0){

			$matriz2= CalculaIncompletoYnuevoMonto($txt_monto, $num_jug);

			//registrar $num_jug_nuevodisponible, $incompleto
 			$num_jug_nuevodisponible = $matriz2[0];
 			$incompleto= $matriz2[1];
 			$txt_monto= $matriz2[2];

			// Guardar ticket a tabla transaccional
			if( $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$num_jug_nuevodisponible,$incompleto,$txt_monto) ){

			}else{

				$_SESSION['mensaje']= $mensajes['fallo_agregar_ticket'];
				echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
			}

		}else{

			//Mensaje de ERROR -- NUMERO AGOTADO PARA ESTE SORTEO

                        //Se registra el numero como agotado
                        $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$txt_monto,2,0);
			$_SESSION['mensaje']= $txt_numero." AGOTADO para sorteo ".$obj_modelo->GetNombreSorteo($sorteo)."  ".$obj_modelo->GetPreZodiacal($zodiacal);
			echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";

		}

	}else{

		//No existe aun
		//revisar tabla de cupo_especial
		$cupo_especial= $obj_modelo->GetCuposEspeciales($txt_numero,$sorteo,$zodiacal);

		if( $cupo_especial['total_registros']>0 ){

			while($row= $obj_conexion->GetArrayInfo($cupo_especial['result'])){

				//recortando el formato 2013-02-26 00:00:00 a 2013-02-26
				$fecha_desde = substr($row["fecha_desde"], 0, 10);
				$fecha_hasta = substr($row["fecha_hasta"], 0, 10);

				//funcion para saber si hoy esta entre dos fechas dadas
				// regresa 1 si esta entre las fechas; 0 de lo contrario
				$fecha_efectiva= $obj_modelo->entreFechasYhoy($fecha_desde,$fecha_hasta);

				//si esta entre las fechas del bloqueo
				if ($fecha_efectiva == 1){

					//significa que ya existe y debemos ver el monto que queda
					$monto_cupoespecial= $row["monto_cupo"];

					//si queda por un monto mayor que 0
					if ($monto_cupoespecial >0){

                                                $num_jug= $txt_monto;

                                                // Calculamos el valor de incompleto
                                                if ($txt_monto < $monto_cupoespecial) {
                                                    $incompleto= 0;
                                                }else{
                                                    $incompleto= 1;
                                                }

						//registrar $num_jug_nuevodisponible, $incompleto
						$matriz2= CalculaIncompletoYnuevoMonto($monto_cupoespecial, $num_jug);

						//registrar $num_jug_nuevodisponible, $incompleto
			 			$num_jug_nuevodisponible = $matriz2[0];
			 			//$incompleto= $matriz2[1];
			 			$txt_monto= $matriz2[2];

						// Guardar ticket a tabla transaccional
						if( $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$num_jug_nuevodisponible,$incompleto,$txt_monto) ){

						}
						else{
							$_SESSION['mensaje']= $mensajes['fallo_agregar_ticket'];
							echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
						}

					}else{
						//Mensaje de ERROR -- NUMERO BLOQUEADO PARA ESTE SORTEO

                                                //Se registra el numero como agotado
                                                $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$txt_monto,2,0);

						$_SESSION['mensaje']= $txt_numero." AGOTADO para sorteo ".$obj_modelo->GetNombreSorteo($sorteo)."  ".$obj_modelo->GetPreZodiacal($zodiacal);
						echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
					}

				}else{
					//No posee cupo especial, ni esta en tabla numeros_jugados
					//revisar tabla de cupo_general
					//procesa A

					//determinando monto_cupo segun id tipo de jugada
					$cupo_general= $obj_modelo->GetCuposGenerales($id_tipo_jugada);

					// Asignamos al monto restante el monto de apuesta para efectos de la funcion CalculaIncompletoYnuevoMonto
                                        $num_jug= $txt_monto;

                                       // Calculamos el valor de incompleto
                                        if ($txt_monto < $cupo_general) {
                                            $incompleto= 0;
                                        }else{
                                            $incompleto= 1;
                                        }
					// Calculando $num_jug_nuevodisponible,$incompleto
					$matriz2= CalculaIncompletoYnuevoMonto($cupo_general, $num_jug);

					//registrar $num_jug_nuevodisponible, $incompleto
		 			$num_jug_nuevodisponible = $matriz2[0];
		 			//$incompleto= $matriz2[1];
		 			$txt_monto= $matriz2[2];

					// Guardar ticket a tabla transaccional
					if( $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$num_jug_nuevodisponible,$incompleto,$txt_monto) ){

					}
					else{
						$_SESSION['mensaje']= $mensajes['fallo_agregar_ticket'];
						echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
					}


				}


			}

		}else{
			//No posee cupo especial, ni esta en tabla numeros_jugados
			//revisar tabla de cupo_general
			//procesa A


                        //determinando monto_cupo segun id tipo de jugada
                        $cupo_general= $obj_modelo->GetCuposGenerales($id_tipo_jugada);

                        // Asignamos al monto restante el monto de apuesta para efectos de la funcion CalculaIncompletoYnuevoMonto
                        $num_jug= $txt_monto;

                         // Calculamos el valor de incompleto
                        if ($txt_monto < $cupo_general) {
                            $incompleto= 0;
                        }else{
                            $incompleto= 1;
                        }

                        // Calculando $num_jug_nuevodisponible,$incompleto
                        $matriz2= CalculaIncompletoYnuevoMonto($cupo_general, $num_jug);

                        //registrar $num_jug_nuevodisponible, $incompleto
                        $num_jug_nuevodisponible = $matriz2[0];
                        //$incompleto= $matriz2[1];
                        $txt_monto= $matriz2[2];



                        // Guardar ticket a tabla transaccional
                        if( $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$num_jug_nuevodisponible,$incompleto,$txt_monto) ){

                        }
                        else{
                                $_SESSION['mensaje']= $mensajes['fallo_agregar_ticket'];
                                echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
                        }

		}



	}



	return 1;

	}
}

function CalculaIncompletoYnuevoMonto($txt_monto, $num_jug){

	$matriz=array();

	//calculando el faltante entre el numero ya jugado y el nuevo por jugar
	$montodiferencia= ($num_jug-$txt_monto);

	//si no completa el monto solicitado
	if ($montodiferencia < 0){
		//Mensaje de ERROR -- NUMERO INCOMPLETO PARA ESTE SORTEO

		//el nuevo disponible es el faltante del incompleto
		$num_jug_nuevodisponible = (-1)*$montodiferencia;
		$incompleto=1;
		$matriz[2] = $txt_monto-$num_jug_nuevodisponible; // si falta txt_monto es la diferencia

	}else{
		//el nuevo disponible es el restante para numeros_jugados
		$num_jug_nuevodisponible = $montodiferencia;
		$incompleto=0;
		$matriz[2] = $txt_monto; // si no falta lo juega por lo que pidio
	}

     $matriz[0]=$num_jug_nuevodisponible;
     $matriz[1]=$incompleto;

	// $matriz2=CalculaIncompletoYnuevoMonto();
	// echo $matriz2[0]; // nuevomontodisponible o faltante
	// echo $matriz2[1]; // incompleto
	// echo $matriz2[2]; // monto real disponible para el ticket o monto solicitado

	return $matriz;

}


$obj_xtpl->parse('main.contenido');

?>