<?php

// Vista asignada
$obj_xtpl->assign_file('contenido', $obj_config->GetVar('ruta_vista').'ticket_transaccional'.$obj_config->GetVar('ext_vista'));

// Modelo asignado
require($obj_config->GetVar('ruta_modelo').'Ticket_Transaccional.php');

$obj_modelo= new Ticket_Transaccional($obj_conexion);


switch (ACCION){
	
	case 'upd':
 		/*
		 * [txt_numero-5] => 146 [sorteo-5] => 23 [txt_monto-5] => 10.00 [zodiacal-5] => 0
		 * */
		// Busca el listado de la informacion.
		$id_taquilla = $obj_modelo->GetIdTaquilla();
				 		
		if( $result= $obj_modelo->GetListadoTicketTransaccional($id_taquilla) ){
        	while($row= $obj_conexion->GetArrayInfo($result)){
        		//echo $row['id_ticket_transaccional'];
        		$id_ticket_transaccional = $row['id_ticket_transaccional']; 
         		$numero= $obj_generico->CleanText($_POST['txt_numero-'.$row['id_ticket_transaccional']]);  
            	$sorteo= $obj_generico->CleanText($_POST['sorteo-'.$row['id_ticket_transaccional']]);  
            	$monto= $obj_generico->CleanText($_POST['txt_monto-'.$row['id_ticket_transaccional']]);  
            	$zodiacal= $obj_generico->CleanText($_POST['zodiacal-'.$row['id_ticket_transaccional']]);
            	//echo $id_ticket_transaccional, " | ",$numero, " | ",$sorteo, " | ",$monto, " | ",$zodiacal;  
				//echo "<br>";
				
        		// Verifica que los datos requeridos no este vacios
				if(!$obj_generico->IsEmpty($numero) && !$obj_generico->IsEmpty($monto)){
						
					// Modifica la cuenta
					if( $obj_modelo->ActualizaDatosJugada($id_ticket_transaccional,$numero,$sorteo,$monto,$zodiacal) ){
						
						//$_SESSION['mensaje']= $mensajes['info_modificada'];
						//header('location:'.$_SESSION['Ruta_Lista']);					
					}
					else{
						$_SESSION['mensaje']= $mensajes['fallo_modificar'];
						header('location:'.$_SESSION['Ruta_Lista']);
					}

				}
				else{
					$_SESSION['mensaje']= $mensajes['info_requerida'];
					header('location:'.$_SESSION['Ruta_Lista']);
				}				
            }
		}
 		header('location:'.$_SESSION['Ruta_Lista']);
		break;

 
		
		
	case 'del':
		
		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Lista']);

		$id = $_GET['id'];
		// Actualiza el estatus como eliminado
		if( $obj_modelo->EliminarDatosJugada($id)){
			$_SESSION['mensaje']= $mensajes['info_eliminada'];
		}
		else{
			$_SESSION['mensaje']= $mensajes['fallo_eliminar'];
		}
		header('location:'.$_SESSION['Ruta_Lista']);	
				
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
		$id_taquilla = $obj_modelo->GetIdTaquilla();
		
		//echo $id_taquilla;
		
		// Busca el listado de la informacion.
		$lista= $obj_modelo->GetListado(300 ,$pag ,$id_taquilla);
		if( $lista['total_registros']>0 ){
			$i=1;
			while($row= $obj_conexion->GetArrayInfo($lista['result'])){
				if( ($i % 2) >0){
					$obj_xtpl->assign('estilo_fila', 'even');
				}
				else{
					$obj_xtpl->assign('estilo_fila', 'odd');
				}
				
				//print_r($row);
				
				// Asignacion de los datos
				$obj_xtpl->assign('id_ticket_transaccional', $obj_generico->CleanTextDb($row["id_ticket_transaccional"]));
				//$obj_xtpl->assign('numero', $obj_generico->CleanTextDb($row["numero"]));
				$obj_xtpl->assign('numero', '<input id="txt_numero" name="txt_numero-'.$row['id_ticket_transaccional'].'" size="7" value="'.$row['numero'].'"" type="text" maxlength="3" />');
				$obj_xtpl->assign('monto', '<input id="txt_monto" name="txt_monto-'.$row['id_ticket_transaccional'].'" size="10" value="'.$row['monto'].'"" type="text" maxlength="10" />');

                // Listado de Sorteos
                if($row['id_zodiacal'] == '0'){
                	// si es un sorteo no zodiacal
					if( $result_s= $obj_modelo->GetSorteos() ){
	                while($row_s= $obj_conexion->GetArrayInfo($result_s)){
						$obj_xtpl->assign($obj_generico->CleanTextDb($row_s));
						
						//print_r($row_s);
						$obj_xtpl->assign('id_sorteo', $row_s['id_sorteo']);
						$obj_xtpl->assign('nombre_sorteo', $row_s['nombre_sorteo']);
						
						if ($row_s['id_sorteo']==$row['id_sorteo']){
								$obj_xtpl->assign('selected', 'selected');
						}else{
						     $obj_xtpl->assign('selected', '');
						 }
	                	
						//Saca la hora del sorteo
						$hora_sorteo=$row_s['hora_sorteo'];
						$hora_sorteo= strtotime($hora_sorteo);
						
						//Valor que viene de la base de datos
						// Obtiene el parametros de los minutos para no listar el sorteo
						$minutos_bloqueo= $obj_modelo->MinutosBloqueo();
							
						//Valor que debe venir de la base de datos
						$hora_actualMas= strtotime("+$minutos_bloqueo minutes");
						
						if ($hora_actualMas < $hora_sorteo){
							//$obj_xtpl->parse('main.contenido.lista_sorteos_manana');
						 	$obj_xtpl->parse('main.contenido.lista_usuario.lista.lista_sorteos.op_sorteos');
 						}
		
						 //$obj_xtpl->parse('main.contenido.lista_usuario.lista.lista_sorteos.op_sorteos');
						}
						$obj_xtpl->parse('main.contenido.lista_usuario.lista.lista_sorteos');
						}	 
                }else{
                     // si es un sorteo zodiacal
					if( $result_s= $obj_modelo->GetSorteosZod() ){
	                while($row_s= $obj_conexion->GetArrayInfo($result_s)){
						$obj_xtpl->assign($obj_generico->CleanTextDb($row_s));
						
						$obj_xtpl->assign('id_sorteo', $row_s['id_sorteo']);
						$obj_xtpl->assign('nombre_sorteo', $row_s['nombre_sorteo']);
						
						if ($row_s['id_sorteo']==$row['id_sorteo']){
								$obj_xtpl->assign('selected', 'selected');
						}else{
						     $obj_xtpl->assign('selected', '');
						 }
	                						//Saca la hora del sorteo
						$hora_sorteo=$row_s['hora_sorteo'];
						$hora_sorteo= strtotime($hora_sorteo);
						
						//Valor que viene de la base de datos
						// Obtiene el parametros de los minutos para no listar el sorteo
						$minutos_bloqueo= $obj_modelo->MinutosBloqueo();
							
						//Valor que debe venir de la base de datos
						$hora_actualMas= strtotime("+$minutos_bloqueo minutes");
						
						if ($hora_actualMas < $hora_sorteo){
							//$obj_xtpl->parse('main.contenido.lista_sorteos_manana');
						 	$obj_xtpl->parse('main.contenido.lista_usuario.lista.lista_sorteos.op_sorteos');
 						}						
						 
						//$obj_xtpl->parse('main.contenido.lista_usuario.lista.lista_sorteos.op_sorteos');
						}
						$obj_xtpl->parse('main.contenido.lista_usuario.lista.lista_sorteos');
						}                	
                }
                
                // Listado de Zodiacal
                if($row['id_zodiacal'] == '0'){
                	//EN caso de que sea no zodiacal
                	$obj_xtpl->assign('Id_zodiacal', '0');
					$obj_xtpl->assign('nombre_zodiacal', 'No Zodiacal');
                	$obj_xtpl->parse('main.contenido.lista_usuario.lista.lista_zodiacal.op_nozodiacal');
                	$obj_xtpl->parse('main.contenido.lista_usuario.lista.lista_zodiacal');
                }else{
                	//en caso de que sea zodiacal
                	if( $result_z= $obj_modelo->GetZodiacales() ){
                		while($row_z= $obj_conexion->GetArrayInfo($result_z)){
							//$obj_xtpl->assign($obj_generico->CleanTextDb($row_z));
							$obj_xtpl->assign('Id_zodiacal', $row_z['Id_zodiacal']);
							$obj_xtpl->assign('nombre_zodiacal', $row_z['nombre_zodiacal']);
						
							if ($row_z['Id_zodiacal']==$row['id_zodiacal']){
								$obj_xtpl->assign('selected', 'selected');
	 						    
							}else{
							     $obj_xtpl->assign('selected', '');
							 }
							$obj_xtpl->parse('main.contenido.lista_usuario.lista.lista_zodiacal.op_zodiacal');
						}
						$obj_xtpl->parse('main.contenido.lista_usuario.lista.lista_zodiacal');
					}               	
                }

				
				// Parseo del bloque de la fila  
				$obj_xtpl->parse('main.contenido.lista_usuario.lista');
				
				$i++;
			}
		}
		else{
			// Mensaje
			$obj_xtpl->assign('sin_listado',$mensajes['sin_lista']);
			
			// Parseo del bloque de la fila
			$obj_xtpl->parse('main.contenido.lista_usuario.no_lista');
		}
	
		// Datos para la paginacion
		$paginacion= $obj_generico->paginacion($lista['pagina'],$lista['total_paginas'],$lista['total_registros'],$obj_generico->urlPaginacion());
		$obj_xtpl->assign('paginacion',$paginacion);
		
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.lista_usuario');		
		
		break;
	
}

$obj_xtpl->parse('main.contenido');

?>