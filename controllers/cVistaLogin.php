<?php

// Vista asignada
$obj_xtpl->assign_file('contenido', $obj_config->GetVar('ruta_vista').'login'.$obj_config->GetVar('ext_vista'));

// Conexion a la bases de datos
$obj_conexion= new Bd();
if( !$obj_conexion->ConnectDataBase($obj_config->GetVar('host'), $obj_config->GetVar('data_base'), $obj_config->GetVar('usuario_db'), $obj_config->GetVar('clave_db')) ){
	$obj_xtpl->assign('mensaje_conexion',$mensajes['sin_conexion_bd']);
	$obj_xtpl->parse('main.conexion_fallida');
}	

// Modelo asignado
require($obj_config->GetVar('ruta_modelo').'VistaLogin.php');
$obj_modelo= new VistaLogin($obj_conexion);

 

//Cargar taquilla
if( $result = $obj_modelo->GetDatosTaquillas() ){
    while($row= $obj_conexion->GetArrayInfo($result)){
        $obj_xtpl->assign('id_taquilla',$row['id_taquilla']);
        $obj_xtpl->assign('numero_taquilla',$row['numero_taquilla']);

        // Parseo del bloque de lista_tipo_jugadas
        $obj_xtpl->parse('main.login.lista_taquillas');
    }
}
$obj_xtpl->parse('main.login');
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