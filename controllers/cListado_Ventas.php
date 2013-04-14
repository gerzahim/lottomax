<?php
/**
 * Archivo del controlador para reporte de listado de ventas
 * @package cListado_Ventas.php
 * @author Gerzahim Salas. - <rasce88@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Abril - 2013
 */

// Vista asignada
$obj_xtpl->assign_file('contenido', $obj_config->GetVar('ruta_vista').'listado_ventas'.$obj_config->GetVar('ext_vista'));

// Modelo asignado
require($obj_config->GetVar('ruta_modelo').'Listado_Ventas.php');

$obj_modelo= new Listado_Ventas($obj_conexion);


switch (ACCION){

        case 'add':

                    // Ruta actual
                    $_SESSION['Ruta_Form']= $obj_generico->RutaRegreso();

                    // Ruta regreso
                    $obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Lista']);

                    // Accion a realizar
                    $obj_xtpl->assign('tipo_accion', 'save');
                    $obj_xtpl->assign('tag_boton', 'Guardar');

                    $obj_xtpl->assign('fecha_marcar', $obj_date->FechaHoy());

                    // Listado de Sorteos
                    if( $result= $obj_modelo->GetSorteos() ){
                            $i=1;
                            while($row= $obj_conexion->GetArrayInfo($result)){
                                    $obj_xtpl->assign($obj_generico->CleanTextDb($row));
                                    $obj_xtpl->assign('cant_sorteos', $i);
                                    $obj_xtpl->parse('main.contenido.formulario.lista_sorteos');
                                    $i++;
                            }
                    }

                    // Listado de Zodiacal
                    if( $result= $obj_modelo->GetZodiacales() ){
                            $i=1;
                            while($row= $obj_conexion->GetArrayInfo($result)){
                                    $obj_xtpl->assign($obj_generico->CleanTextDb($row));
                                    $obj_xtpl->assign('cant_zodiacal', $i);
                                    $obj_xtpl->parse('main.contenido.formulario.lista_zodiacal');
                                    $i++;
                            }
                    }

                    // Listado de Tipo de Jugada
//                    if( $result= $obj_modelo->GetTipoJugadas() ){
//                            $i=1;
//                            while($row= $obj_conexion->GetArrayInfo($result)){
//                                    $obj_xtpl->assign($obj_generico->CleanTextDb($row));
//                                    $obj_xtpl->assign('cant_tipo_jugada', $i);
//                                    $obj_xtpl->parse('main.contenido.formulario.lista_tipo_jugada');
//                                    $i++;
//                            }
//                    }
                    // Parseo del bloque
                    $obj_xtpl->parse('main.contenido.formulario');
                    break;

        case 'save':
                if ($obj_generico->IsNumerico($_POST['txt_numero'])){
                    $numero= $obj_generico->CleanText($_POST['txt_numero']);
                }
                
		if ($obj_generico->IsNumerico($_POST['txt_monto'])){
                    $monto_cupo= $obj_generico->CleanText($_POST['txt_monto']);
                }
                
                $fecha_desde= $obj_generico->CleanText($_POST['txt_fechadesde']);
                $fecha_hasta= $obj_generico->CleanText($_POST['txt_fechahasta']);
                // Verifica que los datos requeridos no este vacios
     		if(!$obj_generico->IsEmpty($numero) && !$obj_generico->IsEmpty($monto_cupo) && !$obj_generico->IsEmpty($fecha_desde) &&
                    !$obj_generico->IsEmpty($fecha_hasta) && !empty($_POST['ss'])){
//                        if(!empty($_POST['tj'])) {
//
//                                // Asigno todos los tipos de jugadas a una variable en array()
//                                $tipos_jugadas =$_POST['tj'];
//
//                                //recorriendo el array de tipo de jugadas
//                                foreach ( $tipos_jugadas as $id_tipo_jugada) {
                                    
                                    // Asigno todos los sorteos una variable en array()
                                    $sorteos =$_POST['ss'];
                                    
                                    //recorriendo el array de sorteos
                                    foreach ( $sorteos as $id_sorteo) {

                                            // Verifica si el sorteo es Zodiacal
                                            if($obj_modelo->GetTrueZodiacal($id_sorteo)){

                                                    // Obtengo el tipo de jugada
                                                    $id_tipo_jugada = $obj_modelo->GetTipoJugada_2('1',$numero);

                                                    //Verificando si marco al menos un signo ari, sag, etc...
                                                    if(!empty($_POST['zz'])) {
                                                           // Si Marco al menos un signo...

                                                           // Asigno todos los sorteos una variable en array()
                                                            $zodiacales =$_POST['zz'];

                                                            //recorriendo el array de sorteos
                                                            foreach ( $zodiacales as $id_zodiacal) {
                                                                // Se guardan los datos del cupo especial
                                                                if( $obj_modelo->GuardarDatosCupoEspecial($numero, $monto_cupo, $id_sorteo, $id_tipo_jugada, $id_zodiacal, $fecha_desde, $fecha_hasta)){
                                                                            $_SESSION['mensaje']= $mensajes['info_agregada'];
                                                                            header('location:'.$_SESSION['Ruta_Lista']);
                                                                    }//
                                                                    else{
                                                                            $_SESSION['mensaje']= $mensajes['fallo_agregar'];
                                                                            header('location:'.$_SESSION['Ruta_Form']);
                                                                    }
                                                            }
                                                    }else{
                                                            // No Marco al menos un signo
                                                             $_SESSION['mensaje']= "Debe seleccionar al menos un signo si ha seleccionado un sorteo zodiacal";
                                                             header('location:'.$_SESSION['Ruta_Form']);
                                                        }
                                            }else{

                                                // Obtengo el tipo de jugada
                                                $id_tipo_jugada = $obj_modelo->GetTipoJugada_2('0',$numero);
                                                
                                                // Se guardan los datos del cupo especial
                                                if( $obj_modelo->GuardarDatosCupoEspecial($numero, $monto_cupo, $id_sorteo, $id_tipo_jugada, '0' , $fecha_desde, $fecha_hasta)){
                                                            $_SESSION['mensaje']= $mensajes['info_agregada'];
                                                            header('location:'.$_SESSION['Ruta_Lista']);
                                                    }//
                                                    else{
                                                            $_SESSION['mensaje']= $mensajes['fallo_agregar'];
                                                            header('location:'.$_SESSION['Ruta_Form']);
                                                    }
                                            }
                                    }	
//                                }
//
//                        }else{
//                                $_SESSION['mensaje']= $mensajes['info_requerida'];
//                                header('location:'.$_SESSION['Ruta_Form']);
//                        }
                }else{
                            $_SESSION['mensaje']= $mensajes['info_requerida'];
                            header('location:'.$_SESSION['Ruta_Form']);
                    }
                
             
				
		break;
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
			$row_datos= $obj_modelo->GetDatosCupoEspecial($_GET['id']);
			$obj_xtpl->assign($obj_generico->CleanTextDb($row_datos));

                        // Asigna el numero
                        $obj_xtpl->assign('txt_numero',$row_datos['numero']);

                        // Asigna el monto
                        $obj_xtpl->assign('txt_monto',$row_datos['monto_cupo']);

                        // Asigna fecha desde
                        $obj_xtpl->assign('txt_fechadesde',substr($row_datos['fecha_desde'],0,10));

                        // Asigna fecha hasta
                        $obj_xtpl->assign('txt_fechahasta',substr($row_datos['fecha_hasta'],0,10));

                         // Listado de Sorteos
                        if( $result= $obj_modelo->GetSorteos() ){
                                $i=1;
                                while($row= $obj_conexion->GetArrayInfo($result)){
                                        $obj_xtpl->assign($obj_generico->CleanTextDb($row));
                                        $obj_xtpl->assign('cant_sorteos', $i);
                                        $obj_xtpl->assign('disabled','disabled');
                                        if ($row['id_sorteo'] == $row_datos['id_sorteo']){
                                            $obj_xtpl->assign('checked','checked');
                                        }
                                        else
                                        {
                                            $obj_xtpl->assign('checked','disabled');
                                        }
                                        $obj_xtpl->parse('main.contenido.formulario.lista_sorteos');
                                        $i++;
                                }
                        }

                        // Listado de Zodiacal
                        if( $result= $obj_modelo->GetZodiacales() ){
                                $i=1;
                                while($row= $obj_conexion->GetArrayInfo($result)){
                                        $obj_xtpl->assign($obj_generico->CleanTextDb($row));
                                        $obj_xtpl->assign('cant_zodiacal', $i);
                                        $obj_xtpl->assign('disabled','disabled');
                                        if ($row['Id_zodiacal'] == $row_datos['id_zodiacal']){
                                            $obj_xtpl->assign('checked','checked');
                                        }
                                        else
                                        {
                                            $obj_xtpl->assign('checked','disabled');
                                        }
                                        $obj_xtpl->parse('main.contenido.formulario.lista_zodiacal');
                                        $i++;
                                }
                        }

                        // Listado de tipos de jugadas
                        if( $result= $obj_modelo->GetTipoJugadas() ){
                            $i=1;
                            while($row= $obj_conexion->GetArrayInfo($result)){
                                    $obj_xtpl->assign($obj_generico->CleanTextDb($row));
                                    $obj_xtpl->assign('cant_tipo_jugada', $i);
                                     $obj_xtpl->assign('disabled','disabled');
                                    if ($row['id_tipo_jugada'] == $row_datos['id_tipo_jugada']){
                                        $obj_xtpl->assign('checked','checked');
                                    }
                                    else
                                    {
                                        $obj_xtpl->assign('checked','disabled');
                                    }
                                    $obj_xtpl->parse('main.contenido.formulario.lista_tipo_jugada');
                                    $i++;
                            }
                        }
			
                        

                       			
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

                if ($obj_generico->IsNumerico($_POST['txt_numero'])){
                    $numero= $obj_generico->CleanText($_POST['txt_numero']);
                }

                if (!empty ($_POST['txt_fechadesde'])){
                    $fecha_desde=$_POST['txt_fechadesde'];
                }

                if (!empty ($_POST['txt_fechahasta'])){
                    $fecha_hasta=$_POST['txt_fechahasta'];
                }
                
		$id_cupo_especial= $_REQUEST['idreferencia'];  

		// Verifica que los datos requeridos no este vacios
		if(!$obj_generico->IsEmpty($monto_cupo) && !$obj_generico->IsEmpty($numero) && !$obj_generico->IsEmpty($fecha_desde) && !$obj_generico->IsEmpty($fecha_hasta)){
				
                      
			// Modifica la relacion de pagos
			if( $obj_modelo->ActualizaDatosCupoEspecial($id_cupo_especial,$numero,$monto_cupo,$fecha_desde,$fecha_hasta) ){
				
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
                
        // Para la busqueda
	case 'search':

		// Ruta actual
		$_SESSION['Ruta_Search']= $obj_generico->RutaRegreso();

		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Lista']);

                //Selecciona el tipo de jugada
                if( $result = $obj_modelo->GetTipoJugadas() ){
                    while($row= $obj_conexion->GetArrayInfo($result)){
                        $obj_xtpl->assign('id_tipo_jugada',$row['id_tipo_jugada']);
                        $obj_xtpl->assign('nombre_tipo_jugada',$row['nombre_jugada']);
                       
                        // Parseo del bloque de lista_tipo_jugadas
                        $obj_xtpl->parse('main.contenido.busqueda.lista_tipo_jugadas');
                    }
                }

                if( $result = $obj_modelo->GetSorteos() ){
                    while($row= $obj_conexion->GetArrayInfo($result)){
                        $obj_xtpl->assign('id_sorteo',$row['id_sorteo']);
                        $obj_xtpl->assign('nombre_sorteo',$row['nombre_sorteo']);

                       
                        $obj_xtpl->parse('main.contenido.busqueda.lista_sorteo');
                    }
                }

                if( $result = $obj_modelo->GetZodiacales() ){
                    while($row= $obj_conexion->GetArrayInfo($result)){
                        $obj_xtpl->assign('Id_zodiacal',$row['Id_zodiacal']);
                        $obj_xtpl->assign('nombre_zodiacal',$row['nombre_zodiacal']);

                        // Parseo del bloque de lista_tipo_jugadas
                        $obj_xtpl->parse('main.contenido.busqueda.lista_zodiacal');
                    }
                }
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.busqueda');
		break;

        // Para la busqueda
	case 'looking':

		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Search']);

		$numero= $obj_generico->CleanText($_GET['numero']);
		$monto= $obj_generico->CleanText($_GET['monto']);
                $jugada= $obj_generico->CleanText($_GET['tipo_jugada']);
                $sorteo= $obj_generico->CleanText($_GET['sorteo']);
                $zodiacal= $obj_generico->CleanText($_GET['zodiacal']);


                $where = "";
		if(!$obj_generico->IsEmpty($numero)){
                    $where = $where. " numero='".$numero."' AND " ;
                }

                if(!$obj_generico->IsEmpty($monto)){
                     $where = $where. "monto_cupo='".$monto."' AND ";
                }

                if(!$sorteo == "0"){
                     $where = $where.  "CE.id_sorteo='".$sorteo."' AND ";
                }

                if(!$jugada == "0"){
                     $where = $where.  "CE.id_tipo_jugada='".$jugada."' AND ";
                }

                if(!$zodiacal == "0"){
                    $where = $where.  "CE.id_zodiacal='".$zodiacal."' AND ";
                }


                $where = substr($where, 0,strlen($where) - 5);
                
		// Busca el listado de la informacion.
		$lista= $obj_modelo->GetListadosegunVariable($where);
		$total_registros= $obj_conexion->GetNumberRows($lista);
		if( $total_registros >0 ){
			$i=1;
			while($row= $obj_conexion->GetArrayInfo($lista)){
				if( ($i % 2) >0){
					$obj_xtpl->assign('estilo_fila', 'even');
				}
				else{
					$obj_xtpl->assign('estilo_fila', 'odd');
				}

				// Asignacion de los datos
                                $obj_xtpl->assign('id_cupo_especial', $obj_generico->CleanTextDb($row["id_cupo_especial"]));
                                $obj_xtpl->assign('numero', $obj_generico->CleanTextDb($row["numero"]));
                                $obj_xtpl->assign('monto', $obj_generico->CleanTextDb($row["monto_cupo"]));
                                $obj_xtpl->assign('Nombre_tipo_jugada', $obj_generico->CleanTextDb($row["nombre_jugada"]));
                                $obj_xtpl->assign('Nombre_sorteo', $obj_generico->CleanTextDb($row["nombre_sorteo"]));
                                $obj_xtpl->assign('Nombre_zodiacal', $obj_generico->CleanTextDb($row["nombre_zodiacal"]));
                                $obj_xtpl->assign('fecha_desde', substr($obj_generico->CleanTextDb($row["fecha_desde"]), 0, 10));
                                $obj_xtpl->assign('fecha_hasta', substr($obj_generico->CleanTextDb($row["fecha_hasta"]), 0, 10));

				// Parseo del bloque de la fila
				$obj_xtpl->parse('main.contenido.lista_cupos_especiales.lista');
                               
				$i++;
			}
		}
		else{
			// Mensaje
			$obj_xtpl->assign('sin_listado',$mensajes['sin_lista']);

			// Parseo del bloque de la fila
			$obj_xtpl->parse('main.contenido.lista_cupos_especiales.no_lista');
		}

		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.lista_cupos_especiales');
		break;

	case 'del':

		// Ruta regreso
		$obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Lista']);

		$id = $_GET['id'];
		// Actualiza el estatus como eliminado
		if( $obj_modelo->EliminarDatosCupoEspecial($id)){
			$_SESSION['mensaje']= $mensajes['info_eliminada'];
		}
		else{
			$_SESSION['mensaje']= $mensajes['fallo_eliminar'];
		}
		header('location:'.$_SESSION['Ruta_Lista']);

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
				$obj_xtpl->assign('id_cupo_especial', $obj_generico->CleanTextDb($row["id_cupo_especial"]));
                                $obj_xtpl->assign('numero', $obj_generico->CleanTextDb($row["numero"]));
                                $obj_xtpl->assign('monto', $obj_generico->CleanTextDb($row["monto_cupo"]));
                                $obj_xtpl->assign('Nombre_tipo_jugada', $obj_generico->CleanTextDb($row["nombre_jugada"]));
                                $obj_xtpl->assign('Nombre_sorteo', $obj_generico->CleanTextDb($row["nombre_sorteo"]));
                                $obj_xtpl->assign('Nombre_zodiacal', $obj_generico->CleanTextDb($row["nombre_zodiacal"]));
                                $obj_xtpl->assign('fecha_desde', substr($obj_generico->CleanTextDb($row["fecha_desde"]), 0, 10));
                                $obj_xtpl->assign('fecha_hasta', substr($obj_generico->CleanTextDb($row["fecha_hasta"]), 0, 10));
						
				// Parseo del bloque de la fila  
				$obj_xtpl->parse('main.contenido.lista_cupos_especiales.lista');
				
				$i++;
			}
		}
		else{
			// Mensaje
			$obj_xtpl->assign('sin_listado',$mensajes['sin_lista']);
			
			// Parseo del bloque de la fila
			$obj_xtpl->parse('main.contenido.lista_cupos_especiales.no_lista');
		}
	
		// Datos para la paginacion
		$paginacion= $obj_generico->paginacion($lista['pagina'],$lista['total_paginas'],$lista['total_registros'],$obj_generico->urlPaginacion());
		$obj_xtpl->assign('paginacion',$paginacion);
		
		// Parseo del bloque
		$obj_xtpl->parse('main.contenido.lista_cupos_especiales');
		
		break;
	
}

$obj_xtpl->parse('main.contenido');

?>