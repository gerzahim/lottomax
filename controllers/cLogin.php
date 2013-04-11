<?php

if( !empty($_POST['txtuser']) && !empty($_POST['txtpass']) && !empty($_POST['op_taquilla']) ){
	
	// Conexion a la base de datos
	require($obj_config->GetVar('ruta_libreria').'Bd.php');
	$obj_conexion= new Bd();

	//Comprobando si hay conexion bd
	if( !$obj_conexion->ConnectDataBase($obj_config->GetVar('host'), $obj_config->GetVar('data_base'), $obj_config->GetVar('usuario_db'), $obj_config->GetVar('clave_db')) ){	
		$nueva_url= "login.php?msj=469";
	}	
	else{
		// Modelo asignado
		require($obj_config->GetVar('ruta_modelo').'LoginAcceso.php');

		$obj_modelo= new LoginAcceso($obj_conexion);
		
		if( $info= $obj_modelo->VerificarUsuario($_POST['txtuser'],$_POST['txtpass'])){

                        // Verificamos que la taquilla no esta siendo usada por otro usuario...
                        If (!$obj_modelo->VerificarUsuarioTaquilla($_POST['op_taquilla'])){
                            
                            // Destruccion de las variables de sesion
                            session_unset();
                            session_destroy();

                            // Ruta en caso de no entrar en ningun condicional
                            $nueva_url= "login.php";

                            //Detecta la IP
                            if(!empty($_SERVER['HTTP_X_FORWARDER_FOR'])){
                                    $ip = $_SERVER['HTTP_X_FORWARDER_FOR'];
                            }
                            elseif(!empty($_SERVER['HTTP_VIA'])){
                                    $ip = $_SERVER['HTTP_VIA'];
                            }
                            elseif(!empty($_SERVER['REMOTE_ADDR'])){
                                    $ip = $_SERVER['REMOTE_ADDR'];
                            }
                            else{
                                    $ip = 'Desconocida';
                            }

                            //Lo nuevo agregado
                            session_set_cookie_params(0, "/");
                            //session_set_cookie_params(0, "/", $HTTP_SERVER_VARS["HTTP_HOST"], 0);
                            // Se define la sesion del usuario
                            //include( $obj_config->GetVar('ruta_libreria').'InfoLogin.php');


                            session_start();
                            //Lo nuevo agregado

                            $_SESSION['InfoLogin']= new InfoLogin($info['nombre_usuario'], $info['id_perfil'], $ip, $_POST['op_taquilla']);

                            // Guadarmos en la base de datos la asosiacion del usuario y la taquilla
                            $obj_modelo->GuardarUsuarioTaquilla($info['id_usuario'], $_POST['op_taquilla']);

                            $_SESSION['taquilla']=$_POST['op_taquilla'];
                            $_SESSION['id_usuario']=$info['id_usuario'];
                            $nueva_url= $obj_config->GetVar('index_page');

                        }
                        else{

                            //Taquilla en uso
                            $nueva_url= "login.php?msj=328";
                        }
		}
		else{
			//Acceso Invalido
			$nueva_url= "login.php?msj=207";
		}
	}
}
else{
	$nueva_url= "login.php?msj=185";
}

header('location:'.$nueva_url);

?>