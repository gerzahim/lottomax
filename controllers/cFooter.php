<?php

// Vista asignada
$obj_xtpl->assign_file('footer', $obj_config->GetVar('ruta_vista').'footer'.$obj_config->GetVar('ext_vista'));
$obj_xtpl->parse('main.footer');


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