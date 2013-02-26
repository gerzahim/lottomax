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
		
		$hora_sorteo=$row['hora_sorteo'];
		$hora_sorteo= strtotime($hora_sorteo);
		
		//Valor que debe venir de la base de datos
		//$minutos_bloqueo= $obj_modelo->MinutosBloqueo();
		/************* CABLEADO **********************/
		$minutos_bloqueo= 5;
		
		//Valor que debe venir de la base de datos
		$hora_actualMas= strtotime("+$minutos_bloqueo minutes");
		
		if ($hora_actualMas < $hora_sorteo){
			$obj_xtpl->parse('main.contenido.lista_sorteos');
		}
		$obj_xtpl->assign('cant_sorteos', $i);
		$i++;
	}
}	


// Objeto de no entiendo porque me cambia el font
//$obj_modelo->GetL();


// Busca el listado de la informacion.
$obj_xtpl->assign('taquilla', $obj_modelo->GetIdTaquilla());


$obj_xtpl->parse('main.contenido');

?>