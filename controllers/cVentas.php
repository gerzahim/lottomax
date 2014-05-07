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
$turno_array=array();

if( $result= $obj_modelo->GetTurnos() ){
	//$i=1;
	while($row= $obj_conexion->GetArrayInfo($result)){
	$turno_array[$row['id_turno']]=strtolower($row['nom_turno']);
	}
}
// Listado de Sorteos 
if( $result= $obj_modelo->GetSorteosxTurno() ){
	$i=1;
	while($row= $obj_conexion->GetArrayInfo($result)){
		if($i==1)
		$id_turno_anterior=$row['id_turno'];
		$id_turno=$row['id_turno'];
		$obj_xtpl->assign($obj_generico->CleanTextDb($row));
	    //Colocar una clase a los Tradicionales de la manana
		$estradicional=$row['tradicional'];
		$obj_xtpl->assign('claset', '');
		if ($estradicional == '1') {
			$obj_xtpl->assign('claset', 'class="t_'.$turno_array[$row['id_turno']].'"');
		}
		//Colocar una clase a los Zodiacales de la Manana
		/*$eszodiacal=$row['zodiacal'];
		$obj_xtpl->assign('clasez', '');
	/*	if ($eszodiacal == '1') {
			$obj_xtpl->assign('clasez', 'class="z_'.$turno_array[$row['id_turno']].'"');
		}*/		
		//Saca la hora del sorteo
		$hora_sorteo=$row['hora_sorteo'];
		$hora_sorteo= strtotime($hora_sorteo);
		//Valor que viene de la base de datos
		// Obtiene el parametros de los minutos para no listar el sorteo
		$minutos_bloqueo= $obj_modelo->MinutosBloqueo();
		//Valor que debe venir de la base de datos
		$hora_actualMas= strtotime("+$minutos_bloqueo minutes");
		// Validando Cuando Cambian de Turno para Parsear los Sorteos
		if ($id_turno!=$id_turno_anterior) {
			$obj_xtpl->parse('main.contenido.lista_turnos');
		}
		if ($hora_actualMas < $hora_sorteo)   {
			$obj_xtpl->parse('main.contenido.lista_turnos.lista_sorteos');
		}		
		$obj_xtpl->assign('text_turno', 'text-label'.$id_turno);
		$id_turno_anterior=$id_turno;
		/************* CABLEADO **********************/
		//$obj_xtpl->parse('main.contenido.lista_sorteos_manana');
		//$obj_xtpl->assign('cant_sorteos', $i);
		$i++;
	}
	$obj_xtpl->parse('main.contenido.lista_turnos');
}	



// Objeto de no entiendo porque me cambia el font
//$obj_modelo->GetL();

// Busca el listado de la informacion.
$obj_xtpl->assign('taquilla', $obj_modelo->GetIdTaquilla());

$obj_xtpl->parse('main.contenido');


?>