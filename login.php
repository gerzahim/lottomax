<?php

// Sesion e informacion del usuario conectado
include('./libraries/InfoLogin.php');

// Comprueba si es un usuario conectado o no.
if(isset($_SESSION['InfoLogin'])){
	// Redireccionamiento
	header('location:index.php');
}
	//exit();
// Archivo de variables de configuracion
require_once('./config/config.php');
$obj_config= new  ConfigVars();

// Archivo de mensajes
require_once($obj_config->GetVar('ruta_config').'mensajes.php');
// Muestra el formulario
if( !isset($_POST['btnentrar']) ){

	// Directorio de las librerias para la funcion "__autoload()"
	define('DIR_LIBRERIA',$obj_config->GetVar('ruta_libreria'));
	
	/**
	 * LLamada automatica de los archivos de las clases a utilizar.
	 *
	 * @param string $className
	 */
	function __autoload($class_name) {
		require_once (DIR_LIBRERIA.$class_name.".php");
	}
	
	// Objetos de clases
	$obj_xtpl = new XTemplate($obj_config->GetVar('ruta_vista')."main".$obj_config->GetVar('ext_vista'));	
	$obj_date= new Fecha();
	
	// Asignaciones
	$obj_xtpl->assign('titulo_web',$obj_config->GetVar('titulo_web'));
	$obj_xtpl->assign('fecha_hoy',$obj_date->FechaActual());
	$obj_xtpl->assign('titulo_sistema',$obj_config->GetVar('titulo_sistema'));
	//echo "pasamos";
	//Definiendo $_GET['msj']
	if( !isset($_GET['msj']) ){
	$_GET['msj']= 0;
	}
		
	// Mensajes Formulario Login	
	switch ($_GET['msj']){
		case 185:
			$obj_xtpl->assign('msj_uno','block');
			$obj_xtpl->assign('mensaje', $mensajes['campos_vacios']);
			break;
			
		case 207:
			$obj_xtpl->assign('msj_uno','block');
			$obj_xtpl->assign('mensaje', $mensajes['acceso_invalido']);
			break;
			
		case 327:
			$obj_xtpl->assign('msj_uno','block');
			$obj_xtpl->assign('mensaje', $mensajes['acceso_conectado']);
			break;

                case 328:
			$obj_xtpl->assign('msj_uno','block');
			$obj_xtpl->assign('mensaje', $mensajes['taquilla_en_uso']);
			break;
			
		case 404:
			$obj_xtpl->assign('msj_uno','block');
			$obj_xtpl->assign('mensaje', $mensajes['acceso_bloqueado']);
			break;
			
		case 469:
			$obj_xtpl->assign('msj_uno','block');
			$obj_xtpl->assign('mensaje', $mensajes['sin_conexion_bd']);
			break;
			
		default:
			$obj_xtpl->assign('msj_uno','none');
			break;
	}
	
	// Header de la Pagina
	include($obj_config->GetVar('ruta_controlador').'cHeader.php');
	// Form del Login
	include($obj_config->GetVar('ruta_controlador').'cVistaLogin.php');		
	// Pie de Pagina
	include($obj_config->GetVar('ruta_controlador').'cFooter.php');
		
	// Parseo  final del  documento
	$obj_xtpl->parse('main');
	$obj_xtpl->out('main');
}
else{

	//  Verificacion de acceso al sistema
	include($obj_config->GetVar('ruta_controlador').'cLogin.php');
}
?>