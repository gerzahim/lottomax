<?php

/**
 * Archivo del controlador para modulo de Taquillas
 * @package cTaquillas.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Abril - 2013
 */

// Vista asignada
$obj_xtpl->assign_file('contenido', $obj_config->GetVar('ruta_vista').'taquilla'.$obj_config->GetVar('ext_vista'));

// Modelo asignado
require($obj_config->GetVar('ruta_modelo').'Taquilla.php');

$obj_modelo= new Taquilla($obj_conexion);


switch (ACCION){
	
	case 'add':
		
		// Ruta actual
		$_SESSION['Ruta_Form']= $obj_generico->RutaRegreso();
		
		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Lista']);
		
		// Accion a realizar
		$obj_xtpl->assign('tipo_accion', 'save');
		$obj_xtpl->assign('tag_boton', 'Guardar');
								
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.formulario');			
		break;
		
	case 'save':
		
		$numero= $obj_generico->CleanText($_POST['txt_numero']);
		$status= $obj_generico->CleanText($_POST['op_status']);	

		// Verifica que los datos requeridos no este vacios
		if(!$obj_generico->IsEmpty($numero)){
				
				// Crea la cuenta de acceso
				if( $obj_modelo->GuardarDatosTaquilla($numero) ){
					
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

				
		
		//Obteniendo Datos 
		if( is_numeric($_GET['id']) ){
			
			// Asignaciones
			$row_datos= $obj_modelo->GetDatosTaquilla($_GET['id']);
			$obj_xtpl->assign($obj_generico->CleanTextDb($row_datos));
			
			//Selecciona el tipo de status que tiene
			if ($row_datos['status'] == '1'){
				$obj_xtpl->assign('selecciono','selected="selected"');
			}
			else{
				$obj_xtpl->assign('seleccionu','selected="selected"');
			}			
			
			
			// Lista los datos del usuario obtenidos de la BD
			$obj_xtpl->assign('txt_numero', $row_datos['numero_taquilla']);
			$obj_xtpl->assign('id_taquilla', $_GET['id']);
			
			
			
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
		
		$numero= $obj_generico->CleanText($_POST['txt_numero']);
		$status= $obj_generico->CleanText($_POST['op_status']);		
		$id_taquilla= $_REQUEST['idreferencia'];

		// Verifica que los datos requeridos no este vacios
		if(!$obj_generico->IsEmpty($numero) && !$obj_generico->IsEmpty($id_taquilla)){
				

			// Modifica la cuenta
			if( $obj_modelo->ActualizaDatosTaquilla($id_taquilla,$numero,$status) ){
				
				
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
		if( $obj_modelo->EliminarDatosTaquilla($id)){
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
				$obj_xtpl->assign('id_taquilla', $obj_generico->CleanTextDb($row["id_taquilla"]));
				$obj_xtpl->assign('numero_taquilla', $obj_generico->CleanTextDb($row["numero_taquilla"]));
				
				if ($obj_generico->CleanTextDb($row["status"]) == '1'){
					$obj_xtpl->assign('status', 'Activo');
				}else{
					$obj_xtpl->assign('status', 'Inactivo');				
				}				
				
				// Parseo del bloque de la fila  
				$obj_xtpl->parse('main.contenido.lista_taquilla.lista');
				
				$i++;
			}
		}
		else{
			// Mensaje
			$obj_xtpl->assign('sin_listado',$mensajes['sin_lista']);
			
			// Parseo del bloque de la fila
			$obj_xtpl->parse('main.contenido.lista_taquilla.no_lista');
		}
	
		// Datos para la paginacion
		$paginacion= $obj_generico->paginacion($lista['pagina'],$lista['total_paginas'],$lista['total_registros'],$obj_generico->urlPaginacion());
		$obj_xtpl->assign('paginacion',$paginacion);
		
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.lista_taquilla');
		
		break;
	
}

$obj_xtpl->parse('main.contenido');

?>