<?php

// Vista asignada
$obj_xtpl->assign_file('contenido', $obj_config->GetVar('ruta_vista').'usuario'.$obj_config->GetVar('ext_vista'));

// Modelo asignado
require($obj_config->GetVar('ruta_modelo').'Usuario.php');

$obj_modelo= new Usuario($obj_conexion);

$id_perfil = $_SESSION['id_perfil'];

switch (ACCION){
	case 'add':
		
		// Ruta actual
		$_SESSION['Ruta_Form']= $obj_generico->RutaRegreso();
		
		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Lista']);
		
		// Accion a realizar
		$obj_xtpl->assign('tipo_accion', 'save');
		$obj_xtpl->assign('tag_boton', 'Guardar');
						

		// Listado de Perfiles
		if( $result= $obj_modelo->GetPerfiles() ){
			while($row= $obj_conexion->GetArrayInfo($result)){
				
				if($id_perfil<= $row['id_perfil'])
				{
					
				$obj_xtpl->assign($obj_generico->CleanTextDb($row));
				$obj_xtpl->parse('main.contenido.formulario.lista_perfiles');
				}
			}
		}		
		
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

				
		
		//Obteniendo Datos del Usuario
		if( is_numeric($_GET['id']) ){
			
			// Asignaciones
			$row_datos= $obj_modelo->GetDatosUsuario($_GET['id']);
			$obj_xtpl->assign($obj_generico->CleanTextDb($row_datos));
			
			// Listado de Perfiles
			if( $result= $obj_modelo->GetPerfiles() ){
				while($row= $obj_conexion->GetArrayInfo($result)){
					
					
					
					if( $row['id_perfil'] == $row_datos['id_perfil']){
						$obj_xtpl->assign('seleccion','selected="selected"');
					}
					else{
						$obj_xtpl->assign('seleccion','');
					}
					
					
					if($id_perfil<= $row['id_perfil'])
				{
					
				$obj_xtpl->assign($obj_generico->CleanTextDb($row));
				$obj_xtpl->parse('main.contenido.formulario.lista_perfiles');
				}
				}
			}
			
			// Lista los datos del usuario obtenidos de la BD
			$obj_xtpl->assign('txt_name', $row_datos['nombre_usuario']);
			$obj_xtpl->assign('txt_mail', $row_datos['email_usuario']);
			$obj_xtpl->assign('txt_login', $row_datos['login_usuario']);
			$obj_xtpl->assign('txt_pass', $row_datos['clave_usuario']);
			$obj_xtpl->assign('txt_repeatpass', $row_datos['clave_usuario']);
			$obj_xtpl->assign('id_Usuario', $_GET['id']);
			// ID en el hidden
			$obj_xtpl->parse('main.contenido.formulario.identificador');				

		}
		else{
			header('location:'.$_SESSION['Ruta_Lista']);
		}		

		
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.formulario');			
		break;		
		
	case 'save':
		
		$nombre= $obj_generico->CleanText($_POST['txt_name']);
		$email= $obj_generico->ToLower($obj_generico->CleanText($_POST['txt_mail']));
		$perfil= $obj_generico->CleanText($_POST['op_perfil']);		
		$login= $obj_generico->ToLower($obj_generico->CleanText($_POST['txt_login']));
		$pass= $obj_generico->CleanText($_POST['txt_pass']);
		$repeatpass= $obj_generico->CleanText($_POST['txt_repeatpass']);

		// Verifica que los datos requeridos no este vacios
		if(!$obj_generico->IsEmpty($nombre) && !$obj_generico->IsEmpty($email) && !$obj_generico->IsEmpty($perfil) && !$obj_generico->IsEmpty($login) && !$obj_generico->IsEmpty($pass) && !$obj_generico->IsEmpty($repeatpass) ){
				// Verifica email correcto
				if(!$obj_generico->is_email($email)){
					$_SESSION['mensaje']= $mensajes['email_incorrecto'];
					header('location:'.$_SESSION['Ruta_Form']);					
				}
				
				//Verifica que haya seleccionado perfil
				if($obj_modelo->VerificaSeleccion_Perfil($perfil)){
					$_SESSION['mensaje']= $mensajes['no_perfil'];
					header('location:'.$_SESSION['Ruta_Form']);					
				}
				
				// Verifica Login Valido
				if(!$obj_generico->VerificarLogin($login)){
					$_SESSION['mensaje']= $mensajes['login_invalido'];
					header('location:'.$_SESSION['Ruta_Form']);					
				}

				//Verifica Claves Iguales
				if($obj_generico->VerificaClaveIguales($pass,$repeatpass) ){
					$_SESSION['mensaje']= $mensajes['clave_desiguales'];
					header('location:'.$_SESSION['Ruta_Form']);
				}
				
				// Crea la cuenta de acceso
				if( $obj_modelo->GuardarDatosUsuario($nombre,$email,$perfil,$login,$pass) ){
					
					// Correo al usuario
					//$obj_correo->sendMailUsuario($obj_generico->CleanTextDb($nombre),$email,$login,$clave);
					
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
		
	case 'upd':
		
		$nombre= $obj_generico->CleanText($_POST['txt_name']);
		$email= $obj_generico->ToLower($obj_generico->CleanText($_POST['txt_mail']));
		$perfil= $obj_generico->CleanText($_POST['op_perfil']);		
		$login= $obj_generico->ToLower($obj_generico->CleanText($_POST['txt_login']));
		$pass= $obj_generico->CleanText($_POST['txt_pass']);
		$repeatpass= $obj_generico->CleanText($_POST['txt_repeatpass']);		
		$id_usuario= $_REQUEST['idreferencia'];

		// Verifica que los datos requeridos no este vacios
		if(!$obj_generico->IsEmpty($nombre) && !$obj_generico->IsEmpty($email) && !$obj_generico->IsEmpty($perfil) && !$obj_generico->IsEmpty($login) && !$obj_generico->IsEmpty($pass) && !$obj_generico->IsEmpty($repeatpass) ){
				
				//Para saber que no entro en ningun validacion
				$validacion = 0;
				// Verifica email correcto
				if(!$obj_generico->is_email($email)){
					$validacion = 1;
					$_SESSION['mensaje']= $mensajes['email_incorrecto'];
					header('location:'.$_SESSION['Ruta_Form']);					
				}
				
				//Verifica que haya seleccionado perfil
				if($obj_modelo->VerificaSeleccion_Perfil($perfil)){
					$validacion = 1;
					$_SESSION['mensaje']= $mensajes['no_perfil'];
					header('location:'.$_SESSION['Ruta_Form']);					
				}
			
				// Verifica Login Valido
				if(!$obj_generico->VerificarLogin($login)){
					$validacion = 1;
					$_SESSION['mensaje']= $mensajes['login_invalido'];
					header('location:'.$_SESSION['Ruta_Form']);					
				}

				//Verifica Claves Iguales
				if($obj_generico->VerificaClaveIguales($pass,$repeatpass) ){
					$validacion = 1;
					$_SESSION['mensaje']= $mensajes['clave_desiguales'];
					header('location:'.$_SESSION['Ruta_Form']);
				}
				
				//Para restringir la validacion
				if ($validacion == 0){
					// Crea la cuenta de acceso
					if( $obj_modelo->ActualizaDatosUsuario($id_usuario,$nombre,$email,$perfil,$login,$pass) ){
						
						// Correo al usuario
						//$obj_correo->sendMailUsuario($obj_generico->CleanTextDb($nombre),$email,$login,$clave);
						
						$_SESSION['mensaje']= $mensajes['info_modificada'];
						header('location:'.$_SESSION['Ruta_Lista']);					
					}
					else{
						$_SESSION['mensaje']= $mensajes['fallo_modificar'];
						header('location:'.$_SESSION['Ruta_Form']);
					}
				}
			
				
		}
		else{
			$_SESSION['mensaje']= $mensajes['info_requerida'];
			header('location:'.$_SESSION['Ruta_Form']);
		}			
		break;	

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
		
		$nombre= $obj_generico->CleanText($_GET['nombre']);
		$email= $obj_generico->ToLower($obj_generico->CleanText($_GET['mail']));

		if(!$obj_generico->IsEmpty($nombre) && !$obj_generico->IsEmpty($email)){
			$_SESSION['mensaje']= $mensajes['demasiados_argumentos'];
			header('location:'.$_SESSION['Ruta_Search']);		
		}elseif ($obj_generico->IsEmpty($nombre) && $obj_generico->IsEmpty($email)){
			$_SESSION['mensaje']= $mensajes['info_requerida'];
			header('location:'.$_SESSION['Ruta_Search']);				
		}else{
			if(!$obj_generico->IsEmpty($nombre)){
				$nombreCampo = 'nombre_usuario';
				$parametro = $nombre;
			}else{
				$nombreCampo = 'email_usuario';
				$parametro = $email;
			}
			
		}
		
		// Busca el listado de la informacion.
		$lista= $obj_modelo->GetListadosegunVariable($nombreCampo,$parametro);
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
				$obj_xtpl->assign('id_Usuario', $obj_generico->CleanTextDb($row["id_usuario"]));								
				$obj_xtpl->assign('Nombre_Cliente', $obj_generico->CleanTextDb($row["nombre_usuario"]));
				$obj_xtpl->assign('Perfil', $obj_modelo->GetNombrePerfil($obj_generico->CleanTextDb($row["id_perfil"])));
				$obj_xtpl->assign('Email', $obj_generico->CleanTextDb($row["email_usuario"]));
								
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
		
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.lista_usuario');		
		break;			

	case 'del':
		
		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Lista']);

		$id = $_GET['id'];
		// Actualiza el estatus como eliminado
		if( $obj_modelo->EliminarDatosUsuario($id)){
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
				
				if($id_perfil<= $row['id_perfil'])
				{
						
				
				if( ($i % 2) >0){
					$obj_xtpl->assign('estilo_fila', 'even');
				}
				else{
					$obj_xtpl->assign('estilo_fila', 'odd');
				}
				
				// Asignacion de los datos
				$obj_xtpl->assign('id_Usuario', $obj_generico->CleanTextDb($row["id_usuario"]));								
				$obj_xtpl->assign('Nombre_Cliente', $obj_generico->CleanTextDb($row["nombre_usuario"]));
				$obj_xtpl->assign('Perfil', $obj_modelo->GetNombrePerfil($obj_generico->CleanTextDb($row["id_perfil"])));
				$obj_xtpl->assign('Email', $obj_generico->CleanTextDb($row["email_usuario"]));
								
				// Parseo del bloque de la fila
				$obj_xtpl->parse('main.contenido.lista_usuario.lista');
				
				$i++;
				}
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