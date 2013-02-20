<?php
// Ruta de redireccionamiento
$ruta_inicio= $obj_config->GetVar('index_page');

// Destruye la sesion
unset($_SESSION['InfoLogin']);
session_unset();
session_destroy();

// Redireccionamiento
header('Location:'.$ruta_inicio);
?>