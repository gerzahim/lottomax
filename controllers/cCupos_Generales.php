<?php
/**
 * Archivo del controlador para modulo Cupos Generales de Numeros
 * @package cCupos_Generales.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Febrero - 2013
 */

// Vista asignada
$obj_xtpl->assign_file('contenido', $obj_config->GetVar('ruta_vista').'cupos_generales'.$obj_config->GetVar('ext_vista'));

// Modelo asignado
require($obj_config->GetVar('ruta_modelo').'Cupos_Generales.php');

$obj_modelo= new Cupo_General($obj_conexion);


switch (ACCION){
		
	case 'mod':
		
		// Ruta actual
		$_SESSION['Ruta_Form']= $obj_generico->RutaRegreso();
		
		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Lista']);
		
		// Accion a realizar
		$obj_xtpl->assign('tipo_accion', 'upd');
		$obj_xtpl->assign('tag_boton', 'Modificar');

				
		
		//Obteniendo Datos de Relacion de Pagos
		if( is_numeric($_GET['id']) ){
			
			// Asignaciones
			$row_datos= $obj_modelo->GetDatosCupoGeneral($_GET['id']);
			$obj_xtpl->assign($obj_generico->CleanTextDb($row_datos));
			
			//Selecciona el tipo de jugada
                        if( $result = $obj_modelo->GetDatosTipoJugadas() ){
                            while($row= $obj_conexion->GetArrayInfo($result)){
                                $obj_xtpl->assign('id_tipo_jugada',$row['id_tipo_jugada']);
                                $obj_xtpl->assign('nombre_tipo_jugada',$row['nombre_jugada']);
				if ($row['id_tipo_jugada'] == $row_datos['id_tipo_jugada']){
                                    $obj_xtpl->assign('selecciono','selected="selected"');
                                }
                                else
                                {
                                    $obj_xtpl->assign('selecciono','');
                                }
                                // Parseo del bloque de lista_tipo_jugadas
                                $obj_xtpl->parse('main.contenido.formulario.lista_tipo_jugadas');
                            }
                        }

                        // Asigna el monto
                        $obj_xtpl->assign('txt_monto',$row_datos['monto_cupo']);

                       			
			// Asigno el id de relacion de pagos
			$obj_xtpl->assign('id_cupo_general', $_GET['id']);
			// Paseo de ID en el hidden
			$obj_xtpl->parse('main.contenido.formulario.identificador');				

		}
		else{
			header('location:'.$_SESSION['Ruta_Lista']);
		}		

		
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.formulario');			
		break;

	case 'upd':
		if ($obj_generico->IsNumerico($_POST['txt_monto'])){
                    $monto_cupo= $obj_generico->CleanText($_POST['txt_monto']);
                }	
		$id_cupo_general= $_REQUEST['idreferencia'];

		// Verifica que los datos requeridos no este vacios
		if(!$obj_generico->IsEmpty($monto_cupo) && !$obj_generico->IsEmpty($id_cupo_general)){
				

			// Modifica la relacion de pagos
			if( $obj_modelo->ActualizaDatosCupoGeneral($id_cupo_general,$monto_cupo) ){
				
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
				$obj_xtpl->assign('id_cupo_general', $obj_generico->CleanTextDb($row["id_cupo_general"]));
				$obj_xtpl->assign('Nombre_tipo_jugada', $obj_generico->CleanTextDb($row["nombre_jugada"]));
                                $obj_xtpl->assign('monto', $obj_generico->CleanTextDb($row["monto_cupo"]));
						
				// Parseo del bloque de la fila  
				$obj_xtpl->parse('main.contenido.lista_cupos_generales.lista');
				
				$i++;
			}
		}
		else{
			// Mensaje
			$obj_xtpl->assign('sin_listado',$mensajes['sin_lista']);
			
			// Parseo del bloque de la fila
			$obj_xtpl->parse('main.contenido.lista_cupos_generales.no_lista');
		}
	
		// Datos para la paginacion
		$paginacion= $obj_generico->paginacion($lista['pagina'],$lista['total_paginas'],$lista['total_registros'],$obj_generico->urlPaginacion());
		$obj_xtpl->assign('paginacion',$paginacion);
		
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.lista_cupos_generales');
		
		break;
	
}

$obj_xtpl->parse('main.contenido');

?>