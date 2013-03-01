<?php
/**
 * Archivo del controlador para modulo Parametros
 * @package cParametros.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Marzo - 2013
 */

// Vista asignada
$obj_xtpl->assign_file('contenido', $obj_config->GetVar('ruta_vista').'parametros'.$obj_config->GetVar('ext_vista'));

// Modelo asignado
require($obj_config->GetVar('ruta_modelo').'Parametros.php');

$obj_modelo= new Parametros($obj_conexion);


switch (ACCION){
		
	case 'mod':
		
		// Ruta actual
		$_SESSION['Ruta_Form']= $obj_generico->RutaRegreso();
		
		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Lista']);
		
		// Accion a realizar
		$obj_xtpl->assign('tipo_accion', 'upd');
		$obj_xtpl->assign('tag_boton', 'Modificar');

				
		
		//Obteniendo Datos de Parametros
		if( is_numeric($_GET['id']) ){
			
			// Asignaciones
			$row_datos= $obj_modelo->GetDatosParametros($_GET['id']);
			$obj_xtpl->assign($obj_generico->CleanTextDb($row_datos));
		


                        $obj_xtpl->assign('id_parametros', $obj_generico->CleanTextDb($row_datos["id_parametros"]));
                        $obj_xtpl->assign('nombre_agencia', $obj_generico->CleanTextDb($row_datos["nombre_agencia"]));
                        $obj_xtpl->assign('taquilla', $obj_generico->CleanTextDb($row_datos["taquilla"]));
                        $obj_xtpl->assign('tiempo_cierre_sorteos', $obj_generico->CleanTextDb($row_datos["tiempo_cierre_sorteos"]));
                        $obj_xtpl->assign('tiempo_anulacion_ticket', $obj_generico->CleanTextDb($row_datos["tiempo_anulacion_ticket"]));
                        $obj_xtpl->assign('tiempo_vigencia_ticket', $obj_generico->CleanTextDb($row_datos["tiempo_vigencia_ticket"]));

      				
			// Asigno el id de relacion de pagos
			$obj_xtpl->assign('id_parametros', $_GET['id']);
			// ID en el hidden
			$obj_xtpl->parse('main.contenido.formulario.identificador');				

		}
		else{
			header('location:'.$_SESSION['Ruta_Lista']);
		}		

		
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.formulario');			
		break;

	case 'upd':

                if (!empty ($_POST['txt_agencia'])){
                    $nombre_agencia= $obj_generico->CleanText($_POST['txt_agencia']);
                }

                if ($obj_generico->IsNumerico($_POST['txt_taquilla'])){
                    $taquilla= $obj_generico->CleanText($_POST['txt_taquilla']);
                }

                if ($obj_generico->IsNumerico($_POST['txt_tiempo_cierre_sorteos'])){
                    $tiempo_cierre_sorteos= $obj_generico->CleanText($_POST['txt_tiempo_cierre_sorteos']);
                }
                
                if ($obj_generico->IsNumerico($_POST['txt_tiempo_anulacion_ticket'])){
                    $tiempo_anulacion_ticket= $obj_generico->CleanText($_POST['txt_tiempo_anulacion_ticket']);
                }

                if ($obj_generico->IsNumerico($_POST['txt_tiempo_vigencia_ticket'])){
                    $tiempo_vigencia_ticket= $obj_generico->CleanText($_POST['txt_tiempo_vigencia_ticket']);
                }

		$id_parametros= $_REQUEST['idreferencia'];

		// Verifica que los datos requeridos no este vacios
		if(!$obj_generico->IsEmpty($nombre_agencia) && !$obj_generico->IsEmpty($taquilla) && !$obj_generico->IsEmpty($id_parametros)){
				

			// Modifica datos
			if( $obj_modelo->ActualizaDatosParametros($id_parametros, $nombre_agencia, $taquilla, $tiempo_cierre_sorteos, $tiempo_anulacion_ticket, $tiempo_vigencia_ticket) ){
				
				$_SESSION['mensaje']= $mensajes['info_modificada'];
				header('location:'.$_SESSION['Ruta_Lista']);					
			}
			else{
				$_SESSION['mensaje']= $mensajes['fallo_modificar'];
				header('location:'.$_SESSION['Ruta_Form']);
			}
				
			
				
		}
		else{
			$_SESSION['mensaje']= $mensajes['info_requerida'];
			header('location:'.$_SESSION['Ruta_Form']);
		}			
		break;		
		
	// Muestra el listado		
	default:
		
		// Ruta actual
		$_SESSION['Ruta_Lista']= $obj_generico->RutaRegreso();
				
		// Para la paginacion
		if(empty($_GET['pg'])){
			$pag= 1;
		}
		else{
			$pag= $_GET['pg'];
		}
		
		// Busca el listado de la informacion.
		$lista= $obj_modelo->GetListado($obj_config->GetVar('num_registros'),$pag);
		if( $lista['total_registros']>0 ){
			$i=1;
			while($row= $obj_conexion->GetArrayInfo($lista['result'])){
				if( ($i % 2) >0){
					$obj_xtpl->assign('estilo_fila', 'even');
				}
				else{
					$obj_xtpl->assign('estilo_fila', 'odd');
				}
				
				// Asignacion de los datos
				$obj_xtpl->assign('id_parametros', $obj_generico->CleanTextDb($row["id_parametros"]));
				$obj_xtpl->assign('nombre_agencia', $obj_generico->CleanTextDb($row["nombre_agencia"]));
                                $obj_xtpl->assign('taquilla', $obj_generico->CleanTextDb($row["taquilla"]));
                                $obj_xtpl->assign('tiempo_cierre_sorteos', $obj_generico->CleanTextDb($row["tiempo_cierre_sorteos"]));
                                $obj_xtpl->assign('tiempo_anulacion_ticket', $obj_generico->CleanTextDb($row["tiempo_anulacion_ticket"]));
				$obj_xtpl->assign('tiempo_vigencia_ticket', $obj_generico->CleanTextDb($row["tiempo_vigencia_ticket"]));

							
				
				// Parseo del bloque de la fila  
				$obj_xtpl->parse('main.contenido.lista_parametros.lista');
				
				$i++;
			}
		}
		else{
			// Mensaje
			$obj_xtpl->assign('sin_listado',$mensajes['sin_lista']);
			
			// Parseo del bloque de la fila
			$obj_xtpl->parse('main.contenido.lista_parametros.no_lista');
		}
	
		// Datos para la paginacion
		$paginacion= $obj_generico->paginacion($lista['pagina'],$lista['total_paginas'],$lista['total_registros'],$obj_generico->urlPaginacion());
		$obj_xtpl->assign('paginacion',$paginacion);
		
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.lista_parametros');
		
		break;
	
}

$obj_xtpl->parse('main.contenido');

?>