<?php

// Vista asignada
$obj_xtpl->assign_file('contenido', $obj_config->GetVar('ruta_vista').'home'.$obj_config->GetVar('ext_vista'));
$obj_xtpl->parse('main.home');

?>