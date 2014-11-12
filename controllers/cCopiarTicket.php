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
$obj_date= new Fecha();

$obj_modelo= new CopiarTicket($obj_conexion);

//session_start();
$id_taquilla= $_SESSION['taquilla'];

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
		//echo "pasa";
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
                                $obj_xtpl->assign('fecha_hora', $obj_date->changeFormatDateI($obj_generico->CleanTextDb($row["fecha_hora"]),1));
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
		$_SESSION['mensaje_errorcopia']="";
		
		// Validando que id ticket no este Vacio
		if(!empty($_GET['id_ticket'])){
			$id_ticket=$_GET['id_ticket'];
		}else{
			echo "<script type='text/javascript'>";
            echo "alert('ID ticket esta Vacio !!!')";
            echo "</script>";
			echo "<script type='text/javascript'>";
            echo "window.location.href = '".$_SESSION['Ruta_Search']."'";
            echo "</script>";
		}		


		// Validando que el array de op_duplicar no este vacio
		if(!empty($_GET['duplicar'])){
			$op_duplicar=$_GET['duplicar'];
		}else{
			echo "<script type='text/javascript'>";
			echo "alert('No selecciono Opcion de Duplicar !!!')";
			echo "</script>";
			echo "<script type='text/javascript'>";
			echo "window.location.href = '".$_SESSION['Ruta_Search']."'";
			echo "</script>";
		}		

		//Guardando $txt_monto_triple y $txt_monto_terminal si existe sino le asigna 0
		if (empty($_GET['txt_monto_triple'])) { $txt_monto_triple=0;} else { $txt_monto_triple=$_GET['txt_monto_triple'];}
		if (empty($_GET['txt_monto_terminal'])) { $txt_monto_terminal=0;} else { $txt_monto_terminal=$_GET['txt_monto_terminal'];}
	
		// Obtenemos los datos de la taquilla
		$taquilla= $obj_modelo->GetIdTaquilla();

		
		//Valor que debe venir de la base de datos
		$minutos_bloqueo= $obj_modelo->MinutosBloqueo();
		//hora actual mas los minutos de bloqueo
		$hora_actualMas= strtotime("+$minutos_bloqueo minutes");		
		
		//recorrer entre todas las opciones de duplicar
		foreach ( $op_duplicar as $op_dp) {
			//echo "<br>op_duplicar", $op_dp;
			$datos= $obj_modelo->GetDetalleTicket($id_ticket);
			$total_registros= $obj_conexion->GetNumberRows($datos);
			if( $total_registros >0 ){
				while($row= $obj_conexion->GetArrayInfo($datos)){
					$id_insert_taquilla=$obj_modelo->GetUltimoIdInsert($taquilla)+1;
					//Proceso de copiar el ticket
					//Determina $id_sorteo a cambiar segun seleccion
					$id_sorteo_new= $obj_modelo->GetSorteobyHorarioCambiar($op_dp, $row['id_sorteo']);
					//Determinando los nuevo montos de triple y terminales si lo cambiaron
					$tamano_numero = strlen($row['numero']);
					if ($tamano_numero == 2){
						// Es terminal
						if($txt_monto_terminal != 0)
							$txt_monto = $txt_monto_terminal;
						else
						$txt_monto = $row['monto'];
						}
						else
						if ($tamano_numero == 3){
						// Es Triple
						if($txt_monto_triple != 0)
							$txt_monto = $txt_monto_triple;
						else
						$txt_monto = $row['monto'];
					}
					// Verificamos que el sorteo este activo
					$hora_sorteo= $obj_modelo->GetHoraSorteo($id_sorteo_new);
					$hora_sorteo= strtotime($hora_sorteo);					
					//Verificando si el sorteo ya esta cerrado
					if ($hora_actualMas < $hora_sorteo){
						// Verifica si el sorteo es Zodiacal
						if($obj_modelo->GetTrueZodiacal($id_sorteo_new)){
							//echo "PASA";
							//El sorteo si es zodiacal !
							$eszodiacal=1;
							//Proceso_Cupo() funcion para determinar los cupos
							$result = ProcesoCupos($row['numero'], $txt_monto, $id_sorteo_new, $row['id_zodiacal'], $eszodiacal,  $id_insert_taquilla);
			
						}else{
							//El sorteo no es zodiacal !
							$eszodiacal=0;
							$zodiacal=0;
			
							//Proceso_Cupo() funcion para determinar los cupos
							$result = ProcesoCupos($row['numero'], $txt_monto, $id_sorteo_new, $row['id_zodiacal'], $eszodiacal, $id_insert_taquilla);
			
						}
			
					}
					else{
						//Quiere decir que al menos un sorteo ya estaba cerrado
						$bandera=1;
					}
			
				}// fin de while
				
				if($bandera==1){
					echo "<script type='text/javascript'>";
					echo "alert('Algunos de los sorteos han sido cerrados')";
					echo "</script>";
					echo "<script type='text/javascript'>";
					echo "window.location.href = 'index.php?op=ventas#final'";
					echo "</script>";
				}else if ($_SESSION['mensaje_errorcopia']!=""){
					//$bodytag ="Existen Numeros Agotados...\\n";
					//$bodytag.= str_replace("<br>", "\\n", $_SESSION['mensaje_errorcopia']);
					echo "<script type='text/javascript'>";
					//echo "alert('$bodytag')";
					echo "alert('Existen Numeros Agotados...')";
					echo "</script>";
					echo "<script type='text/javascript'>";
					echo "window.location.href = 'index.php?op=ventas#final'";
					echo "</script>";
				}else{
					//sleep(3);
					header('location:index.php?op=ventas#final');					
				}
				

			}// fin if total_registro > 0			
		}// fin foreach		

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
                                $obj_xtpl->assign('fecha_hora', $obj_date->changeFormatDateI($obj_generico->CleanTextDb($row["fecha_hora"]),1));
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

/**
 * Calcula el monto real en que podra jugar el numero
 *
 * @param integer $monto_disponible
 * @param integer $monto_jugado
 * @return array
 */

function CalculaIncompletoYnuevoMonto($monto_disponible, $monto_jugado){

	$matriz=array();

	// Matriz[0]= El monto  para cubrir el faltante
	// Matriz[1]= El switch de ser incompleto o no 1 incompleto ya de por si / 2 agotado / 3 imcompleto en esta jugada
	// Matriz[2]= El monto por el que realmente se va a jugar
	// Matriz[3]= El monto por el que quedaria disponible


	//calculando el faltante entre el numero ya jugado y el nuevo por jugar
	$montodiferencia= ($monto_disponible-$monto_jugado);

	//si no completa el monto solicitado
	if ($montodiferencia < 0){
		//Mensaje de ERROR -- NUMERO INCOMPLETO PARA ESTE SORTEO
		//el nuevo disponible es el faltante del incompleto
		$matriz[0] = $montodiferencia*(-1);
		$matriz[1] = 1;
		$matriz[2] = $monto_disponible;
		$matriz[3] = 0;
		//echo "<br> nuevo ".$num_jug_nuevodisponible;
	}else
		if ($montodiferencia ==0)
		{
			//	echo "pasa";
			$matriz[0] = 0;
			$matriz[1] = 3;
			$matriz[2] = $monto_jugado;
			$matriz[3] = 0;
		}
		else
		{
			//el nuevo disponible es el restante para numeros_jugados
			$matriz[0] = 0;
			$matriz[1] = 0;
			$matriz[2] = $monto_jugado;
			$matriz[3] = $montodiferencia;

		}
		// $matriz2=CalculaIncompletoYnuevoMonto();
		// echo $matriz2[0]; // nuevomontodisponible o faltante
		// echo $matriz2[1]; // incompleto
		// echo $matriz2[2]; // monto real disponible para el ticket o monto solicitado
		//  print_r($matriz);
		return $matriz;

}
function ProcesoCupos($txt_numero,$txt_monto, $sorteo, $zodiacal, $esZodiacal,$id_insert_taquilla){
	global $taquilla;
	global $obj_modelo;
	global $obj_conexion;
	$fecha_hoy=date('Y-m-d');
	//echo "pasa";
	//determinando el tipo de jugada
	$id_tipo_jugada= $obj_modelo->GetTipoJugada($esZodiacal,$txt_numero);
	//$monto_inicial=$txt_monto;
	//revisar tabla de ticket_transaccional
	$monto_inicial=$txt_monto;
	$numero_jugadoticket= $obj_modelo->GetTicketTransaccional($txt_numero,$sorteo,$zodiacal, $id_tipo_jugada);
	// Existe en la Tabla Transaccional
	if ($numero_jugadoticket['total_registros']>0 )
	{
		// Solo si es de diferente taquilla a la que se esta jugando en ese instante
		if($numero_jugadoticket['id_taquilla']!=$_SESSION["taquilla"]){
			$monto_restante = $numero_jugadoticket['monto_restante'];
			if ($monto_restante >0){
				$matriz2= CalculaIncompletoYnuevoMonto($monto_restante,$txt_monto);
				$txt_monto=$matriz2[2];
				// Guardar ticket a tabla transaccional
				if( $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$matriz2[0],$matriz2[1],$matriz2[2],$matriz2[3],$taquilla,$id_insert_taquilla) ){
				}
				else
				{
					$_SESSION['mensaje']= $mensajes['fallo_agregar_ticket'];
					echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
				}
			}
			else{
				//Mensaje de ERROR -- NUMERO AGOTADO PARA ESTE SORTEO
				//Se registra el numero como agotado
				$obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$txt_monto,2,0,0,$taquilla,$id_insert_taquilla);
				$_SESSION['mensaje']= $txt_numero." AGOTADO para sorteo ".$obj_modelo->GetNombreSorteo($sorteo)."  ".$obj_modelo->GetPreNombreSigno($zodiacal);
				echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
			}
		}
		else
			// Es la misma taquilla por lo tanto es el mismo ticket transaccional se hace una suma algebraica con los montos
		{
			$txt_monto+=$numero_jugadoticket['monto'];
			//adicionar y dar mensaje de confirm (adicionar al ticket u obviar)
			//significa que ya existe y debemos ver el monto que queda
			$num_ticket_inc = $numero_jugadoticket['incompleto'];
			//Verificando que si esta incompleto
			if ($txt_monto < 0){
				echo "<div id='mensaje' class='mensaje' >El monto debe ser mayor a 0 Bs !!!</div>";
				exit();
			}else if($txt_monto == 0){
				//$obj_modelo->EliminarTicketTransaccionalByTicket($numero_jugadoticket['id_ticket_transaccional']);
				echo "<div id='mensaje' class='mensaje' >La jugada fue Eliminada !!!</div>";
			}else
				if ($num_ticket_inc == '0')
				echo "--";
			elseif ($num_ticket_inc == '1'|| $num_ticket_inc == '3')
			echo "<div id='mensaje' class='mensaje' >El numero ya esta jugado y tiene su cupo completo !!!</div>";
			//Proceso CONFIRM: Elimina la apuesta existente en ticket transaccional, y para que no este repetida, la registra
			// con el nuevo monto ingresado.
			$id_ticket_transaccional= $obj_modelo->GetIDTicketTransaccional($txt_numero,$sorteo,$zodiacal);
			//echo "<input id='txt_id_ticket_transaccional' name='txt_id_ticket_transaccional' type='text' value='".$id_ticket_transaccional."'/>";
			$obj_modelo->EliminarTicketTransaccionalByTicket($id_ticket_transaccional);
			/*	echo "TXT Numero".$txt_numero."<br>";
				echo "TXT Monto".$txt_monto."<br>";
			exit();*/
			$result = ProcesoCupos($txt_numero, $txt_monto, $sorteo, $zodiacal, $esZodiacal,$id_insert_taquilla);
		}
	}else{
	
		//revisar tabla de numeros_jugados
		$numero_jugado= $obj_modelo->GetNumerosJugados($txt_numero,$sorteo,$zodiacal);
		//significa que ya existe y debemos ver el monto que queda
		$monto_restante = $numero_jugado['monto_restante'];
		if( $numero_jugado['total_registros']>0 ){
			//echo $num_jug;
			//print_r($numero_jugado);
			//si queda por un monto mayor que 0
			if ($monto_restante >0){
				$matriz2= CalculaIncompletoYnuevoMonto($monto_restante,$txt_monto);
				$txt_monto=$matriz2[2];
	
				// Guardar ticket a tabla transaccional
				if( $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$matriz2[0],$matriz2[1],$matriz2[2],$matriz2[3],$taquilla,$id_insert_taquilla) ){
				}else{
					$_SESSION['mensaje']= $mensajes['fallo_agregar_ticket'];
					echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
				}
			}else{
				//Mensaje de ERROR -- NUMERO AGOTADO PARA ESTE SORTEO
				//Se registra el numero como agotado
				$matriz2= CalculaIncompletoYnuevoMonto($monto_restante,$txt_monto);
				$txt_monto=$matriz2[2];
	
				$obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$matriz2[0],$matriz2[1],$matriz2[2],$matriz2[3],$taquilla,$id_insert_taquilla);
				$_SESSION['mensaje']= $txt_numero." AGOTADO para sorteo ".$obj_modelo->GetNombreSorteo($sorteo)."  ".$obj_modelo->GetPreNombreSigno($zodiacal);
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
							//  $monto_restante= $txt_monto;
							//registrar $num_jug_nuevodisponible, $incompleto
							$matriz2= CalculaIncompletoYnuevoMonto($monto_cupoespecial, $txt_monto);
							$txt_monto=$matriz2[2];
							if( $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$matriz2[0],$matriz2[1],$matriz2[2],$matriz2[3],$taquilla,$id_insert_taquilla) ){
	
							}
							else{
								$_SESSION['mensaje']= $mensajes['fallo_agregar_ticket'];
								echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
							}
	
						}else{
							//Mensaje de ERROR -- NUMERO BLOQUEADO PARA ESTE SORTEO
	
							//Se registra el numero como agotado
							$obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$txt_monto,2,0,0,$taquilla,$id_insert_taquilla);
	
							$_SESSION['mensaje']= $txt_numero." AGOTADO para sorteo ".$obj_modelo->GetNombreSorteo($sorteo)."  ".$obj_modelo->GetPreNombreSigno($zodiacal);
							echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
						}
	
					}else{
						//No posee cupo especial, ni esta en tabla numeros_jugados
						//revisar tabla de cupo_general
						//procesa A
	
						//determinando monto_cupo segun id tipo de jugada
						$cupo_general= $obj_modelo->GetCuposGenerales($id_tipo_jugada);
						// Asignamos al monto restante el monto de apuesta para efectos de la funcion CalculaIncompletoYnuevoMonto
						// $monto_restante= $txt_monto;
	
						// Calculamos el valor de incompleto
	
	
						// Calculando $num_jug_nuevodisponible,$incompleto
						$matriz2= CalculaIncompletoYnuevoMonto($cupo_general, $txt_monto);
						$txt_monto=$matriz2[2];
						if( $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$matriz2[0],$matriz2[1],$matriz2[2],$matriz2[3],$taquilla,$id_insert_taquilla) ){
	
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
				//echo "PASA";
				//determinando monto_cupo segun id tipo de jugada
				$cupo_general= $obj_modelo->GetCuposGenerales($id_tipo_jugada);
	
				// Asignamos al monto restante el monto de apuesta para efectos de la funcion CalculaIncompletoYnuevoMonto
				$monto_restante= $txt_monto;
				if($txt_monto>0)
				{
	
					// Calculamos el valor de incompleto
						
	
					// Calculando $num_jug_nuevodisponible,$incompleto
					$matriz2= CalculaIncompletoYnuevoMonto($cupo_general, $monto_restante);
	
						
					/*	echo "este".$cupo_general;
					 echo "sisi";
					echo "monto".$matriz2[2];
					exit;*/
					// Guardar ticket a tabla transaccional
					if( $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$matriz2[0],$matriz2[1],$matriz2[2],$matriz2[3],$taquilla,$id_insert_taquilla) ){
	
					}
					else{
						//$_SESSION['mensaje']= $mensajes['fallo_agregar_ticket'];
						$_SESSION['mensaje']= "Error No se logro ingresar la jugada al ticket!!!";
						echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
					}
				}
					
			}
		}
		return $txt_monto;
	}
}


$obj_xtpl->parse('main.contenido');

?>