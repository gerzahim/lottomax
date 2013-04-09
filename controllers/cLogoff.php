<?php
// Ruta de redireccionamiento
$ruta_inicio= $obj_config->GetVar('index_page');

// Modelo asignado
require($obj_config->GetVar('ruta_modelo').'LoginAcceso.php');

$obj_modelo= new LoginAcceso($obj_conexion);


// Destruye la sesion
If ($obj_modelo->EliminarUsuarioTaquilla($_SESSION['InfoLogin']->GetTaquilla())){}
unset($_SESSION['InfoLogin']);
session_unset();
session_destroy();



// Redireccionamiento
header('Location:'.$ruta_inicio);
?>