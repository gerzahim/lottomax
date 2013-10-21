<?php

// Vista asignada
$obj_xtpl->assign_file('contenido', $obj_config->GetVar('ruta_vista').'sorteo'.$obj_config->GetVar('ext_vista'));

// Modelo asignado
require($obj_config->GetVar('ruta_modelo').'Sorteo.php');

$obj_modelo= new Sorteo($obj_conexion);


switch (ACCION){
	
	case 'add':
		
		// Ruta actual
		$_SESSION['Ruta_Form']= $obj_generico->RutaRegreso();
		
		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Lista']);
		
		// Accion a realizar
		$obj_xtpl->assign('tipo_accion', 'save');
		$obj_xtpl->assign('tag_boton', 'Guardar');
						
		// Listado de Horas
		if( $result= $obj_modelo->GetHoras() ){
			foreach ($result as $hora){
				$obj_xtpl->assign('id_hora',$hora);
				$obj_xtpl->parse('main.contenido.formulario.lista_horas');
			}
		}

		// Listado de Minutos
		if( $result= $obj_modelo->GetMinutos() ){
			foreach ($result as $minutos){
				$obj_xtpl->assign('id_minutos',$minutos);
				$obj_xtpl->parse('main.contenido.formulario.lista_minutos');
			}
		}

		// Listado de Loterias
		if( $result= $obj_modelo->GetLoterias() ){
			while($row= $obj_conexion->GetArrayInfo($result)){
				$obj_xtpl->assign($obj_generico->CleanTextDb($row));
				$obj_xtpl->parse('main.contenido.formulario.lista_loterias');
			}
		}

		// Listado de Turnos
		/*
		if( $result= $obj_modelo->GetTurnos() ){
			while($row= $obj_conexion->GetArrayInfo($result)){
				$obj_xtpl->assign($obj_generico->CleanTextDb($row));
				$obj_xtpl->parse('main.contenido.formulario.lista_loterias');
			}
		}		
			*/	
		
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.formulario');			
		break;
		
	case 'save':
		
		$nombre= $obj_generico->CleanText($_POST['txt_name']);
		$id_loteria= $obj_generico->CleanText($_POST['op_loteria']);
		$hora= $obj_generico->CleanText($_POST['op_hora']);	
		$minutos= $obj_generico->CleanText($_POST['op_minute']);
		$turno= $obj_generico->CleanText($_POST['op_turno']);	
		$zodiacal= $obj_generico->CleanText($_POST['op_zodiacal']);
		$tradicional= $obj_generico->CleanText($_POST['op_tradicional']);
		$status= $obj_generico->CleanText($_POST['op_status']);	

		// Verifica que los datos requeridos no este vacios
		if(!$obj_generico->IsEmpty($nombre) && !$obj_generico->IsEmpty($id_loteria) && !$obj_generico->IsEmpty($hora) && !$obj_generico->IsEmpty($minutos) && !$obj_generico->IsEmpty($zodiacal)){
				
				$time= $hora.":".$minutos.":00";

				// Crea la cuenta de acceso
				if( $obj_modelo->GuardarDatosSorteo($id_loteria,$nombre,$time,$turno,$zodiacal,$tradicional) ){
					
					$_SESSION['mensaje']= $mensajes['info_agregada'];
					header('location:'.$_SESSION['Ruta_Lista']);					
				}
				else{
					$_SESSION['mensaje']= $mensajes['fallo_agregar'];
					header('location:'.$_SESSION['Ruta_Form']);
				}				
				
		}
		else{
			$_SESSION['mensaje']= $mensajes['info_requerida'];
			header('location:'.$_SESSION['Ruta_Form']);
		}			
		break;	

	case 'mod':
		
		// Ruta actual
		$_SESSION['Ruta_Form']= $obj_generico->RutaRegreso();
		
		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Lista']);
		
		// Accion a realizar
		$obj_xtpl->assign('tipo_accion', 'upd');
		$obj_xtpl->assign('tag_boton', 'Modificar');

				
		
		//Obteniendo Datos del Usuario
		if( is_numeric($_GET['id']) ){
			
			// Asignaciones
			$row_datos= $obj_modelo->GetDatosSorteo($_GET['id']);
			$obj_xtpl->assign($obj_generico->CleanTextDb($row_datos));
		
			// Listado de Loterias
			if( $result= $obj_modelo->GetLoterias() ){
				while($row= $obj_conexion->GetArrayInfo($result)){
					if( $row['id_loteria'] == $row_datos['id_loteria']){
						$obj_xtpl->assign('seleccion','selected="selected"');
					}
					else{
						$obj_xtpl->assign('seleccion','');
					}
					$obj_xtpl->assign($obj_generico->CleanTextDb($row));
					$obj_xtpl->parse('main.contenido.formulario.lista_loterias');
				}
			}			
			
			$partes=explode(":",$row_datos['hora_sorteo']); 
			$hora_sorteo=$partes[0];
			$minuto_sorteo=$partes[1];  
		
			// Listado de Horas, selecciona hora
			if( $result= $obj_modelo->GetHoras() ){
				foreach ($result as $hora){
					if( $hora == $hora_sorteo){
						$obj_xtpl->assign('seleccion','selected="selected"');
					}
					else{
						$obj_xtpl->assign('seleccion','');
					}
					$obj_xtpl->assign('id_hora',$hora);
					$obj_xtpl->parse('main.contenido.formulario.lista_horas');					
				}

			}
			
			// Listado de Minutos, selecciona minutos
			if( $result= $obj_modelo->GetMinutos() ){
				foreach ($result as $minutos){
					if( $minutos == $minuto_sorteo){
						$obj_xtpl->assign('seleccion','selected="selected"');
					}
					else{
						$obj_xtpl->assign('seleccion','');
					}
					$obj_xtpl->assign('id_minutos',$minutos);
					$obj_xtpl->parse('main.contenido.formulario.lista_minutos');					
				}

			}
			
			//Selecciona el turno
			if ($row_datos['id_turno'] == '1'){
				$obj_xtpl->assign('selecciona_tm','selected="selected"');
			}
			else if ($row_datos['id_turno'] == '2'){
				$obj_xtpl->assign('selecciona_tt','selected="selected"');
			}else{
				$obj_xtpl->assign('selecciona_tn','selected="selected"');
			}
			
			//Selecciona si es zodiacal o no
			if ($row_datos['zodiacal'] == '1'){
				$obj_xtpl->assign('selecciona','selected="selected"');
			}
			else{
				$obj_xtpl->assign('seleccione','selected="selected"');
			}
			
			//Selecciona si es tradicional o no
			if ($row_datos['tradicional'] == '1'){
				$obj_xtpl->assign('seleccionot','selected="selected"');
			}
			else{
				$obj_xtpl->assign('seleccionut','selected="selected"');
			}			
			
			//Selecciona el tipo de status que tiene
			if ($row_datos['status'] == '1'){
				$obj_xtpl->assign('selecciono','selected="selected"');
			}
			else{
				$obj_xtpl->assign('seleccionu','selected="selected"');
			}			
			
			
			// Lista los datos del usuario obtenidos de la BD
			$obj_xtpl->assign('txt_name', $row_datos['nombre_sorteo']);
			$obj_xtpl->assign('id_Sorteo', $_GET['id']);
			
			
			
			// ID en el hidden
			$obj_xtpl->parse('main.contenido.formulario.identificador');				

		}
		else{
			header('location:'.$_SESSION['Ruta_Lista']);
		}		

		
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.formulario');			
		break;

	case 'upd':
		
		$nombre= $obj_generico->CleanText($_POST['txt_name']);
		$id_loteria= $obj_generico->CleanText($_POST['op_loteria']);
		$hora= $obj_generico->CleanText($_POST['op_hora']);	
		$minutos= $obj_generico->CleanText($_POST['op_minute']);
		$turno= $obj_generico->CleanText($_POST['op_turno']);	
		$zodiacal= $obj_generico->CleanText($_POST['op_zodiacal']);
		$tradicional= $obj_generico->CleanText($_POST['op_tradicional']);
		$status= $obj_generico->CleanText($_POST['op_status']);		
		$id_sorteo= $_REQUEST['idreferencia'];

		// Verifica que los datos requeridos no este vacios
		if(!$obj_generico->IsEmpty($nombre) && !$obj_generico->IsEmpty($hora) && !$obj_generico->IsEmpty($minutos) && !$obj_generico->IsEmpty($zodiacal) && !$obj_generico->IsEmpty($id_sorteo)){
				

			// Modifica la cuenta
			if( $obj_modelo->ActualizaDatosSorteo($id_sorteo,$id_loteria,$nombre,$hora,$minutos,$turno,$zodiacal,$tradicional,$status) ){
				
				$_SESSION['mensaje']= $mensajes['info_modificada'];
				header('location:'.$_SESSION['Ruta_Lista']);					
			}
			else{
				$_SESSION['mensaje']= $mensajes['fallo_modificar'];
				header('location:'.$_SESSION['Ruta_Form']);
			}
				
			
				
		}
		else{
			$_SESSION['mensaje']= $mensajes['info_requerida'];
			header('location:'.$_SESSION['Ruta_Form']);
		}			
		break;

	case 'del':
		
		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Lista']);

		$id = $_GET['id'];
		// Actualiza el estatus como eliminado
		if( $obj_modelo->EliminarDatosSorteo($id)){
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
				$obj_xtpl->assign('id_Sorteo', $obj_generico->CleanTextDb($row["id_sorteo"]));	
				$obj_xtpl->assign('Nombre_Loteria', $obj_modelo->GetNombreLoteria($obj_generico->CleanTextDb($row["id_loteria"])));							
				$obj_xtpl->assign('Nombre_Sorteo', $obj_generico->CleanTextDb($row["nombre_sorteo"]));
				$obj_xtpl->assign('Hora', $obj_generico->CleanTextDb($row["hora_sorteo"]));
				if ($obj_generico->CleanTextDb($row["zodiacal"]) == '1'){
					$obj_xtpl->assign('Zodiacal', 'Si');
				}else{
					$obj_xtpl->assign('Zodiacal', 'No');				
				}
				if ($obj_generico->CleanTextDb($row["status"]) == '1'){
					$obj_xtpl->assign('status', 'Activo');
				}else{
					$obj_xtpl->assign('status', 'Inactivo');				
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