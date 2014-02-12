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
	
// Listado de Sorteos Manana
if( $result= $obj_modelo->GetSorteosManana() ){
	$i=1;
	while($row= $obj_conexion->GetArrayInfo($result)){
		$obj_xtpl->assign($obj_generico->CleanTextDb($row));
		
	    //Colocar una clase a los Tradicionales de la manana
		$estradicional=$row['tradicional'];
		
		$obj_xtpl->assign('claset', '');
		if ($estradicional == '1') {
			$obj_xtpl->assign('claset', 'class="t_manana"');
		}		
		
		//Saca la hora del sorteo
		$hora_sorteo=$row['hora_sorteo'];
		$hora_sorteo= strtotime($hora_sorteo);
		
		//Valor que viene de la base de datos
		// Obtiene el parametros de los minutos para no listar el sorteo
		$minutos_bloqueo= $obj_modelo->MinutosBloqueo();
			
		//Valor que debe venir de la base de datos
		$hora_actualMas= strtotime("+$minutos_bloqueo minutes");
		
		if ($hora_actualMas < $hora_sorteo){
			$obj_xtpl->parse('main.contenido.lista_sorteos_manana');
		}		
		
		/************* CABLEADO **********************/
		//$obj_xtpl->parse('main.contenido.lista_sorteos_manana');
		
		//$obj_xtpl->assign('cant_sorteos', $i);
		$i++;
	}
}	

// Listado de Sorteos Tarde
if( $result= $obj_modelo->GetSorteosTarde() ){
	$i=1;
	while($row= $obj_conexion->GetArrayInfo($result)){
		$obj_xtpl->assign($obj_generico->CleanTextDb($row));
		
		//Colocar una clase a los Tradicionales de la tarde
		$estradicional=$row['tradicional'];
		
		$obj_xtpl->assign('claset', '');
		if ($estradicional == '1') {
			$obj_xtpl->assign('claset', 'class="t_tarde"');
		}
				
		$hora_sorteo=$row['hora_sorteo'];
		$hora_sorteo= strtotime($hora_sorteo);
		
		//Valor que debe venir de la base de datos
		$minutos_bloqueo= $obj_modelo->MinutosBloqueo();
			
		//Valor que debe venir de la base de datos
		$hora_actualMas= strtotime("+$minutos_bloqueo minutes");
		
		if ($hora_actualMas < $hora_sorteo){
			$obj_xtpl->parse('main.contenido.lista_sorteos_tarde');
		}		
		/************* CABLEADO **********************/
		//$obj_xtpl->parse('main.contenido.lista_sorteos_tarde');
		
		//$obj_xtpl->assign('cant_sorteos', $i);
		$i++;
	}
}

// Listado de Sorteos Noche
if( $result= $obj_modelo->GetSorteosNoche() ){
	$i=1;
	while($row= $obj_conexion->GetArrayInfo($result)){
		$obj_xtpl->assign($obj_generico->CleanTextDb($row));
		
		//Colocar una clase a los Tradicionales de la noche
		$estradicional=$row['tradicional'];
		
		$obj_xtpl->assign('claset', '');
		if ($estradicional == '1') {
			$obj_xtpl->assign('claset', 'class="t_noche"');
		}
				
		$hora_sorteo=$row['hora_sorteo'];
		$hora_sorteo= strtotime($hora_sorteo);
		
		//Valor que debe venir de la base de datos
		$minutos_bloqueo= $obj_modelo->MinutosBloqueo();
			
		//Valor que debe venir de la base de datos
		$hora_actualMas= strtotime("+$minutos_bloqueo minutes");
		
		if ($hora_actualMas < $hora_sorteo){
			$obj_xtpl->parse('main.contenido.lista_sorteos_noche');
		}		
		/************* CABLEADO **********************/
		//$obj_xtpl->parse('main.contenido.lista_sorteos_noche');
		
		//$obj_xtpl->assign('cant_sorteos', $i);
		$i++;
	}
}

// Objeto de no entiendo porque me cambia el font
//$obj_modelo->GetL();

// Busca el listado de la informacion.
$obj_xtpl->assign('taquilla', $obj_modelo->GetIdTaquilla());

$obj_xtpl->parse('main.contenido');


?>