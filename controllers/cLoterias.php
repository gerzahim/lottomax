<?php

// Vista asignada
$obj_xtpl->assign_file('contenido', $obj_config->GetVar('ruta_vista').'loteria'.$obj_config->GetVar('ext_vista'));

// Modelo asignado
require($obj_config->GetVar('ruta_modelo').'Loteria.php');

$obj_modelo= new Loteria($obj_conexion);
$obj_date= new Fecha();

switch (ACCION){
	
	case 'add':
		
		// Ruta actual
		$_SESSION['Ruta_Form']= $obj_generico->RutaRegreso();
		
		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Lista']);
		$obj_xtpl->assign('muestra_fechasi', 'style="display:none;"');
		$obj_xtpl->assign('check_fechassi_no', 'checked');
		
		// Accion a realizar
		$obj_xtpl->assign('tipo_accion', 'save');
		$obj_xtpl->assign('tag_boton', 'Guardar');
								
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.formulario');			
		break;
	case 'save':
		$nombre= $obj_generico->CleanText($_POST['txt_name']);
		$status= $obj_generico->CleanText($_POST['op_status']);	
		$status_espe= $obj_generico->CleanText($_POST['op_status_espe']);
		
		$id_dias_semana='';
		foreach ( $dia_sem as $dia) {
			$id_dias_semana.=$dia.',';
		}
		$id_dias_semana=trim($id_dias_semana,",");
		// Verifica que los datos requeridos no este vacios
		if(!$obj_generico->IsEmpty($nombre)){
			if($opt_fecha_si)
				{
					if(isset($_POST['fechadesde'])&&isset($_POST['fechahasta']))
					{
						$fecha_desde= $obj_date->changeFormatDateII($_POST['fechadesde']);
						$fecha_hasta= $obj_date->changeFormatDateII($_POST['fechahasta']);
						if(strtotime($fecha_desde) >= strtotime(date('y-m-d')) || strtotime(date('y-m-d'))<= strtotime($fecha_hasta))
						{
							if($status_espe)
							$status=1;
							else
							$status=0;
						}
					}
					else
					{
						//$status=1;
						$fecha_desde= "0000-00-00";
						$fecha_hasta= "0000-00-00";
					}
				}
				else
				{
					//$status=1;
					$fecha_desde= "0000-00-00";
					$fecha_hasta= "0000-00-00";
				}
				// Crea la cuenta de acceso
				if($obj_modelo->GuardarDatosLoteria($nombre,$status,$id_dias_semana,$fecha_desde,$fecha_hasta,$status_espe)){
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
			$row_datos= $obj_modelo->GetDatosLoteria($_GET['id']);
			$obj_xtpl->assign($obj_generico->CleanTextDb($row_datos));
			
			//Selecciona el tipo de status que tiene
			if ($row_datos['status'] == '1'){
				$obj_xtpl->assign('selecciono','selected="selected"');
			}
			else{
				$obj_xtpl->assign('seleccionu','selected="selected"');
			}			
			
			if($row_datos['fecha_desde']=='0000-00-00')
			{
				$obj_xtpl->assign('check_fechassi_no', 'checked');
				$obj_xtpl->assign('check_fechassi_si', '');
				$obj_xtpl->assign('muestra_fechasi', 'style="display:none;"');
			}
			else
			{
				$obj_xtpl->assign('check_fechassi_si', 'checked');
				$obj_xtpl->assign('check_fechassi_no', '');
				$obj_xtpl->assign('muestra_fechasi', 'style=""');
				$obj_xtpl->assign('fechadesde', $obj_date->changeFormatDateI($row_datos['fecha_desde'], 0));
				$obj_xtpl->assign('fechahasta', $obj_date->changeFormatDateI($row_datos['fecha_hasta'], 0));
				if($row_datos['status_especial']==1)
				$obj_xtpl->assign('seleccionoe','selected="selected"');
				else
				if($row_datos['status_especial']==0)
				$obj_xtpl->assign('seleccionue','selected="selected"');
				else
				if($row_datos['status_especial']==2)
				{
					if ($row_datos['status'] == 1){
						$obj_xtpl->assign('seleccionoe','selected="selected"');
					}
					else{
						$obj_xtpl->assign('seleccionue','selected="selected"');
					}
						
				}
			}
			// Lista los datos del usuario obtenidos de la BD
			$obj_xtpl->assign('txt_name', $row_datos['nombre_loteria']);
			$obj_xtpl->assign('id_Loteria', $_GET['id']);
			
			if($row_datos['status'])
			$obj_xtpl->assign('esta_activo', "Desactivar ");
			else
			$obj_xtpl->assign('esta_activo', "Activar ");
				if( $result= $obj_modelo->GetDias() ){
				while($row= $obj_conexion->GetArrayInfo($result)){
					$obj_xtpl->assign('id_dias_semana', $row['id_dias_semana']);
					$obj_xtpl->assign('abv_dia_semana', $row['abv_dia_semana']);
					if( strpos( $row_datos['id_dias_semana'], $row['id_dias_semana']) !== false )
						$obj_xtpl->assign('chek', 'checked');
					else
						$obj_xtpl->assign('chek', '');
					$obj_xtpl->parse('main.contenido.formulario.dias_sem');
				}
			}
			
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
		$status= $obj_generico->CleanText($_POST['op_status']);		
		$status_espe= $obj_generico->CleanText($_POST['op_status_espe']);
		$id_loteria= $_REQUEST['idreferencia'];
		$opt_fecha_si= $_POST['opt_fecha_si'];
		$dia_sem =$_POST['dia_sem'];
		$id_dias_semana='';
		foreach ( $dia_sem as $dia) {
			$id_dias_semana.=$dia.',';
		}
		$id_dias_semana=trim($id_dias_semana,",");
		// Verifica que los datos requeridos no este vacios
		if(!$obj_generico->IsEmpty($nombre) && !$obj_generico->IsEmpty($id_loteria)&& !$obj_generico->IsEmpty($id_dias_semana) ){
		if($opt_fecha_si)
		{
			if(isset($_POST['fechadesde'])&&isset($_POST['fechahasta']))
			{
				$fecha_desde= $obj_date->changeFormatDateII($_POST['fechadesde']);
				$fecha_hasta= $obj_date->changeFormatDateII($_POST['fechahasta']);
				if(strtotime($fecha_desde) <= strtotime(date('y-m-d')) && strtotime(date('y-m-d')) <= strtotime($fecha_hasta))
				{
					if($status_espe)
					$status=1;
					else
					$status=0;
				}
			}
			else
			{
				$fecha_desde= "0000-00-00";
				$fecha_hasta= "0000-00-00";
			}
		}
		else
		{
			$status_espe=0;
			$fecha_desde= "0000-00-00";
			$fecha_hasta= "0000-00-00";
		}
		// Modifica la cuenta
		if( $obj_modelo->ActualizaDatosLoteria($id_loteria,$nombre,$status,$id_dias_semana,$fecha_desde,$fecha_hasta,$status_espe)){
			// Actualiza el status de los sorteos Asociados
			$obj_modelo->ActualizaStatusSorteo($id_loteria,$status,$id_dias_semana);
				$_SESSION['mensaje']= $mensajes['info_modificada'];
				header('location:'.$_SESSION['Ruta_Lista']);					
			}
			else{
				echo "naiboa";
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
		if( $obj_modelo->ActualizarStatusLoteria($id,0)){
			$_SESSION['mensaje']= $mensajes['loteria_desactivada'];
		}
		else{
			$_SESSION['mensaje']= $mensajes['fallo_loteria_desactivada'];
		}
		header('location:'.$_SESSION['Ruta_Lista']);	
				
		break;			

		// Activar loteria
		case 'ok':
		
			// Ruta regreso
			$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Lista']);
		
			$id = $_GET['id'];
			// Actualiza el estatus como activo
			if( $obj_modelo->ActualizarStatusLoteria($id,1)){
				$_SESSION['mensaje']= $mensajes['loteria_activada'];
			}
			else{
				$_SESSION['mensaje']= $mensajes['fallo_loteria_activada'];
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
				$obj_xtpl->assign('id_Loteria', $obj_generico->CleanTextDb($row["id_loteria"]));								
				$obj_xtpl->assign('Nombre_Loteria', $obj_generico->CleanTextDb($row["nombre_loteria"]));
				if($row["status"])
				$obj_xtpl->assign('accion_modificar', 'del');
				else
				$obj_xtpl->assign('accion_modificar', 'ok');
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