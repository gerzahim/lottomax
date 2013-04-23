<?php
/**
 * Archivo del controlador para modulo Impresora
 * @package cImpresora.php
 * @author Gerzahim Salas. - <rasce88@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Abril - 2013
 */

// Vista asignada
$obj_xtpl->assign_file('contenido', $obj_config->GetVar('ruta_vista').'impresora'.$obj_config->GetVar('ext_vista'));

// Modelo asignado
require($obj_config->GetVar('ruta_modelo').'Impresora.php');

$obj_modelo= new Impresora($obj_conexion);


switch (ACCION){
	
	case 'add':
		
		// Ruta actual
		$_SESSION['Ruta_Form']= $obj_generico->RutaRegreso();
		
		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Lista']);
		
		// Accion a realizar
		$obj_xtpl->assign('tipo_accion', 'save');
		$obj_xtpl->assign('tag_boton', 'Guardar');
						

		// Listado de Taquillas
		if( $result= $obj_modelo->GetTaquillas() ){
			while($row= $obj_conexion->GetArrayInfo($result)){
				$obj_xtpl->assign($obj_generico->CleanTextDb($row));
				$obj_xtpl->parse('main.contenido.formulario.lista_taquillas');
			}
		}
				
		// Asignacion de los datos
		$obj_xtpl->assign($obj_generico->CleanTextDb($row));
						

		$obj_xtpl->assign('check_box','checked="checked"');
		$obj_xtpl->assign('check_box2','checked="checked"');
		$obj_xtpl->assign('check_box3','checked="checked"');
		$obj_xtpl->assign('lineas_saltar_antes','0');
		$obj_xtpl->assign('lineas_saltar_despues','0');
		
		$obj_xtpl->assign('nombre_vendedor_ticket','1');
		$obj_xtpl->assign('cortar_ticket','1');
		$obj_xtpl->assign('ver_numeros_incompletos','1');
		$obj_xtpl->assign('ver_numeros_agotados','1');		
		
		
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.formulario');			
		break;	
		
	case 'mod':
		
		// Ruta actual
		$_SESSION['Ruta_Form']= $obj_generico->RutaRegreso();
		
		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Lista']);
		
		// Accion a realizar
		$obj_xtpl->assign('tipo_accion', 'upd');
		$obj_xtpl->assign('tag_boton', 'Modificar');

		
		//Obteniendo Datos de Parametros
		if( is_numeric($_GET['id']) ){
			
			// Asignaciones
			$row_datos= $obj_modelo->GetDatosImpresora($_GET['id']);
			$obj_xtpl->assign($obj_generico->CleanTextDb($row_datos));

			
			if ($row_datos['nombre_vendedor_ticket'] == '1'){
				$obj_xtpl->assign('check_box','checked="checked"');
				//$obj_xtpl->assign('nombre_vendedor_ticket','1');				
			}
			
			if ($row_datos['cortar_ticket'] == '1'){
				$obj_xtpl->assign('check_box1','checked="checked"');
				//$obj_xtpl->assign('cortar_ticket','1');				
			}

			if ($row_datos['ver_numeros_incompletos'] == '1'){
				$obj_xtpl->assign('check_box2','checked="checked"');
				//$obj_xtpl->assign('ver_numeros_incompletos','1');				
			}
			
			if ($row_datos['ver_numeros_agotados'] == '1'){
				$obj_xtpl->assign('check_box3','checked="checked"');
				//$obj_xtpl->assign('ver_numeros_agotados','1');				
			}	

		
			$obj_xtpl->assign('nombre_vendedor_ticket','1');
			$obj_xtpl->assign('cortar_ticket','1');
			$obj_xtpl->assign('ver_numeros_incompletos','1');
			$obj_xtpl->assign('ver_numeros_agotados','1');			
					
			// Listado de Taquillas
			if( $result= $obj_modelo->GetTaquillas() ){
				while($row= $obj_conexion->GetArrayInfo($result)){
					if( $row['id_taquilla'] == $row_datos['id_taquilla']){
						$obj_xtpl->assign('seleccion','selected="selected"');
					}
					else{
						$obj_xtpl->assign('seleccion','');
					}
					$obj_xtpl->assign($obj_generico->CleanTextDb($row));
					$obj_xtpl->parse('main.contenido.formulario.lista_taquillas');
				}
			}				
			

		}
		else{
			header('location:'.$_SESSION['Ruta_Lista']);
		}		

		
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.formulario.identificador');		
		
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.formulario');			
		break;

	case 'upd':
		/*
		echo "<pre>";
		print_r($_POST);
		echo "</pre>";
		*/
		if(isset($_POST) && array_key_exists('nombre_vendedor_ticket',$_POST)){
			$nombre_vendedor_ticket= $obj_generico->CleanText($_POST['nombre_vendedor_ticket']);
		}else{
			$nombre_vendedor_ticket= 0;
		}
		if(isset($_POST) && array_key_exists('cortar_ticket',$_POST)){
			$cortar_ticket= $obj_generico->CleanText($_POST['cortar_ticket']);
		}else{
			$cortar_ticket= 0;
		}
		if(isset($_POST) && array_key_exists('ver_numeros_incompletos',$_POST)){
			$ver_numeros_incompletos= $obj_generico->CleanText($_POST['ver_numeros_incompletos']);
		}else{
			$ver_numeros_incompletos= 0;
		}
		if(isset($_POST) && array_key_exists('ver_numeros_agotados',$_POST)){
			$ver_numeros_agotados= $obj_generico->CleanText($_POST['ver_numeros_agotados']);
		}else{
			$ver_numeros_agotados= 0;
		}		
		/*
		$nombre_vendedor_ticket= $obj_generico->CleanText($_POST['nombre_vendedor_ticket']);
		$cortar_ticket= $obj_generico->ToLower($obj_generico->CleanText($_POST['cortar_ticket']));
		$ver_numeros_incompletos= $obj_generico->CleanText($_POST['ver_numeros_incompletos']);
		$ver_numeros_agotado= $obj_generico->CleanText($_POST['ver_numeros_agotado']);		
		*/
		
		$op_taquilla= $obj_generico->CleanText($_POST['op_taquilla']);		
		$lineas_saltar_antes= $obj_generico->CleanText($_POST['lineas_saltar_antes']);
		$lineas_saltar_despues= $obj_generico->CleanText($_POST['lineas_saltar_despues']);

		$id_impresora= $_REQUEST['idreferencia'];
		
		// Verifica que los datos requeridos no este vacios
		if(!$obj_generico->IsEmpty($op_taquilla) && !$obj_generico->IsEmpty($lineas_saltar_antes) && !$obj_generico->IsEmpty($lineas_saltar_despues)  ){
				
				// Crea la cuenta de acceso
				if( $obj_modelo->ActualizaDatosImpresora($id_impresora,$nombre_vendedor_ticket,$cortar_ticket,$op_taquilla,$lineas_saltar_antes,$lineas_saltar_despues,$ver_numeros_incompletos,$ver_numeros_agotados) ){
					
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
		
		
	case 'save':
		

		if(isset($_POST) && array_key_exists('nombre_vendedor_ticket',$_POST)){
			$nombre_vendedor_ticket= $obj_generico->CleanText($_POST['nombre_vendedor_ticket']);
		}else{
			$nombre_vendedor_ticket= 0;
		}
		if(isset($_POST) && array_key_exists('cortar_ticket',$_POST)){
			$cortar_ticket= $obj_generico->CleanText($_POST['cortar_ticket']);
		}else{
			$cortar_ticket= 0;
		}
		if(isset($_POST) && array_key_exists('ver_numeros_incompletos',$_POST)){
			$ver_numeros_incompletos= $obj_generico->CleanText($_POST['ver_numeros_incompletos']);
		}else{
			$ver_numeros_incompletos= 0;
		}
		if(isset($_POST) && array_key_exists('ver_numeros_agotados',$_POST)){
			$ver_numeros_agotados= $obj_generico->CleanText($_POST['ver_numeros_agotados']);
		}else{
			$ver_numeros_agotados= 0;
		}		
		/*
		$nombre_vendedor_ticket= $obj_generico->CleanText($_POST['nombre_vendedor_ticket']);
		$cortar_ticket= $obj_generico->ToLower($obj_generico->CleanText($_POST['cortar_ticket']));
		$ver_numeros_incompletos= $obj_generico->CleanText($_POST['ver_numeros_incompletos']);
		$ver_numeros_agotado= $obj_generico->CleanText($_POST['ver_numeros_agotado']);		
		*/
		
		$op_taquilla= $obj_generico->CleanText($_POST['op_taquilla']);		
		$lineas_saltar_antes= $obj_generico->CleanText($_POST['lineas_saltar_antes']);
		$lineas_saltar_despues= $obj_generico->CleanText($_POST['lineas_saltar_despues']);
		
		// Verifica que los datos requeridos no este vacios
		if(!$obj_generico->IsEmpty($op_taquilla) && !$obj_generico->IsEmpty($lineas_saltar_antes) && !$obj_generico->IsEmpty($lineas_saltar_despues)  ){
				
				// Crea la cuenta de acceso
				if( $obj_modelo->GuardarDatosImpresora($nombre_vendedor_ticket,$cortar_ticket,$op_taquilla,$lineas_saltar_antes,$lineas_saltar_despues,$ver_numeros_incompletos,$ver_numeros_agotados) ){
					
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

	case 'del':
		
		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Lista']);

		$id = $_GET['id'];
		// Actualiza el estatus como eliminado
		if( $obj_modelo->EliminarDatosImpresora($id)){
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
				$obj_xtpl->assign($obj_generico->CleanTextDb($row));
				
				// Asignacion de los datos
				$obj_xtpl->assign('id_taquilla', $obj_modelo->GetNumeroTaquilla($obj_generico->CleanTextDb($row["id_taquilla"])));
				
				if($row["nombre_vendedor_ticket"] == '1'){
					$obj_xtpl->assign('nombre_vendedor_ticket', 'Si');
				}else{
					$obj_xtpl->assign('nombre_vendedor_ticket', 'No');
				}
									
				// Parseo del bloque de la fila  
				$obj_xtpl->parse('main.contenido.lista_impresora.lista');
				
				$i++;
			}
		}
		else{
			// Mensaje
			$obj_xtpl->assign('sin_listado',$mensajes['sin_lista']);
			
			// Parseo del bloque de la fila
			$obj_xtpl->parse('main.contenido.lista_impresora.no_lista');
		}
	
		// Datos para la paginacion
		$paginacion= $obj_generico->paginacion($lista['pagina'],$lista['total_paginas'],$lista['total_registros'],$obj_generico->urlPaginacion());
		$obj_xtpl->assign('paginacion',$paginacion);
		
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.lista_impresora');
		
		break;
	
}

$obj_xtpl->parse('main.contenido');

?>