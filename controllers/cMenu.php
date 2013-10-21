<?php
	
$id_perfil = $_SESSION['id_perfil'];

if ($id_perfil == '1') {
	//Menu para Administrador
	$obj_xtpl->assign_file('menu', $obj_config->GetVar('ruta_vista').'menu'.$obj_config->GetVar('ext_vista'));
}elseif ($id_perfil == '4'){
	//Menu para Vendedor
	$obj_xtpl->assign_file('menu', $obj_config->GetVar('ruta_vista').'menu_vendedor'.$obj_config->GetVar('ext_vista'));
}

// Vista asignada
//$obj_xtpl->assign_file('menu', $obj_config->GetVar('ruta_vista').'menu'.$obj_config->GetVar('ext_vista'));

$obj_xtpl->parse('main.menu');


/*
if( isset($_SESSION['InfoLogin']) && ($_SESSION['InfoLogin']->GetTipo() == 'c' || $_SESSION['InfoLogin']->GetTipo() == 'd' )){
	
	// Parseo del header de pdvsa para clientes
	$obj_xtpl->parse('main.top_header_cliente');
}else{
// Parseo final del header para personal
$obj_xtpl->parse('main.top_header');

}
*/

?>