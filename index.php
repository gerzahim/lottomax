<?php

// Sesion e informacion del usuario conectado
include('./libraries/InfoLogin.php');

// Archivo de variables de configuracion
require_once('./config/config.php');
$obj_config= new  ConfigVars();

//Verificando si tiene Sesion Activa
if(!isset($_SESSION['InfoLogin'])){
	// Redireccionamiento
	header('location:login.php');
}

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

// Archivo de mensajes
require_once($obj_config->GetVar('ruta_config').'mensajes.php');

// Objetos de clases
$obj_xtpl = new XTemplate($obj_config->GetVar('ruta_vista')."main".$obj_config->GetVar('ext_vista'));
$obj_date= new Fecha();
$obj_generico= new Generica();

// Conexion a la bases de datos
$obj_conexion= new Bd();
if( !$obj_conexion->ConnectDataBase($obj_config->GetVar('host'), $obj_config->GetVar('data_base'), $obj_config->GetVar('usuario_db'), $obj_config->GetVar('clave_db')) ){	
	$obj_xtpl->assign('mensaje_conexion',$mensajes['sin_conexion_bd']);
	$obj_xtpl->parse('main.conexion_fallida');
}	
	
// Asignaciones
$obj_xtpl->assign('titulo_web',$obj_config->GetVar('titulo_web'));
$obj_xtpl->assign('fecha_hoy',$obj_date->FechaActual());
$obj_xtpl->assign('titulo_sistema',$obj_config->GetVar('titulo_sistema'));
$obj_xtpl->assign('pagina_principal',$obj_config->GetVar('index_page'));
$obj_xtpl->assign('nombre_usuario',$obj_generico->CleanTextDb($_SESSION['InfoLogin']->GetUsuario()));


//Verificando si las variables estan seteadas
if(!isset($_SESSION['mensaje'])){
	// seteando variable
	$_SESSION['mensaje']='';
}

$obj_xtpl->assign('mensaje',$_SESSION['mensaje']);
unset($_SESSION['mensaje']);

//Verificando si las variables estan seteadas
if(!isset($_REQUEST['op'])){
	// seteando variable
	$_REQUEST['op']='';
}

// Opcion del sistema
define('OPCION',$_REQUEST['op']);
$obj_xtpl->assign('opcion_sistema',OPCION);

//Verificando si las variables estan seteadas
if(!isset($_REQUEST['accion'])){
	// seteando variable
	$_REQUEST['accion']='';
}
// Accion del sistema
define('ACCION',$_REQUEST['accion']);

// Header de la Pagina
include($obj_config->GetVar('ruta_controlador').'cHeader.php');

//  Menu del sistema
include($obj_config->GetVar('ruta_controlador').'cMenu.php');


// Contenido del sistema
switch(OPCION){
	case 'cliente':
		include($obj_config->GetVar('ruta_controlador').'cCliente.php');		
		/*if(isset($_SESSION['cliente'])){
			include($obj_config->GetVar('ruta_controlador').'cCliente.php');
		}
		else{
			header('location:'.$obj_config->GetVar('index_page'));
		}*/
		break;
		
	case 'loterias':
		
		include($obj_config->GetVar('ruta_controlador').'cLoterias.php');
		break;
		
	case 'sorteos':
		
		include($obj_config->GetVar('ruta_controlador').'cSorteos.php');
		break;

        case 'relacion_pagos':

		include($obj_config->GetVar('ruta_controlador').'cRelacion_Pagos.php');
		break;

        case 'cupos_generales':

		include($obj_config->GetVar('ruta_controlador').'cCupos_Generales.php');
		break;

        case 'cupos_especiales':

		include($obj_config->GetVar('ruta_controlador').'cCupos_Especiales.php');
		break;

	case 'ventas':
		
		include($obj_config->GetVar('ruta_controlador').'cVentas.php');
		break;		
	
	case 'usuario':
		
		include($obj_config->GetVar('ruta_controlador').'cUsuario.php');
		break;

        case 'parametros':

                include($obj_config->GetVar('ruta_controlador').'cParametros.php');
		break;
            
	case 'close':
		include($obj_config->GetVar('ruta_controlador').'cLogoff.php');
		break;
		
	case 'inicio':
		include($obj_config->GetVar('ruta_controlador').'cHome.php');
		break;		
	
	default:
		include($obj_config->GetVar('ruta_controlador').'cHome.php');
		break;
}

// Pie de Pagina
include($obj_config->GetVar('ruta_controlador').'cFooter.php');

// Parseo  final del  documento
$obj_xtpl->parse('main');
$obj_xtpl->out('main');

?>
