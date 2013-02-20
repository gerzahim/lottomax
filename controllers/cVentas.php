<?php

// Ruta actual
$_SESSION['Ruta_Lista']= $obj_generico->RutaRegreso();

// Vista asignada
$obj_xtpl->assign_file('contenido', $obj_config->GetVar('ruta_vista').'ventas'.$obj_config->GetVar('ext_vista'));

// Modelo asignado
require($obj_config->GetVar('ruta_modelo').'Ventas.php');

$obj_modelo= new Ventas($obj_conexion);

// Accion a realizar
$obj_xtpl->assign('tipo_accion', 'add');
	
// Listado de Sorteos
if( $result= $obj_modelo->GetSorteos() ){
	$i=1;
	while($row= $obj_conexion->GetArrayInfo($result)){
		$obj_xtpl->assign($obj_generico->CleanTextDb($row));
		$obj_xtpl->assign('cant_sorteos', $i);
		$obj_xtpl->parse('main.contenido.lista_sorteos');
		$i++;
	}
}	


// Objeto de no entiendo porque me cambia el font
//$obj_modelo->GetL();


// Busca el listado de la informacion.
$obj_xtpl->assign('taquilla', $obj_modelo->GetIdTaquilla());


$obj_xtpl->parse('main.contenido');

?>