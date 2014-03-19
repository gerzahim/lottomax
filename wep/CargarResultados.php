<html>
   <head><title>Cargar Resultados Hoy</title>
  
<?php
date_default_timezone_set("America/Caracas");

// Archivo de variables de configuracion
require_once('../config/config.php');
$obj_config= new ConfigVars();

// Archivo de mensajes
require_once('.'.$obj_config->GetVar('ruta_config').'mensajes.php');

// Clase Generica
require('.'.$obj_config->GetVar('ruta_libreria').'Generica.php');
$obj_generico= new Generica();


// Clase XTemplate
require('.'.$obj_config->GetVar('ruta_libreria').'XTemplate.php');
$obj_xtpl = new XTemplate('.'.$obj_config->GetVar('ruta_vista').'cargar_resultados'.$obj_config->GetVar('ext_vista'));

// Clase Date
require('.'.$obj_config->GetVar('ruta_libreria').'Fecha.php');
$obj_date= new Fecha();

// Conexion a la bases de datos
require('.'.$obj_config->GetVar('ruta_libreria').'Bd.php');
$obj_conexion= new Bd();
if( !$obj_conexion->ConnectDataBase($obj_config->GetVar('host'), $obj_config->GetVar('data_base'), $obj_config->GetVar('usuario_db'), $obj_config->GetVar('clave_db')) ){
	echo "sin_conexion_bd";
}

// Modelo asignado
require('.'.$obj_config->GetVar('ruta_modelo').'Cargar_resultados.php');

$obj_modelo= new Cargar_resultados($obj_conexion);


$ano=date('Y');
$mes=date('m');
$dia_hoy=date('d');

if(isset($_GET ['fecha']))
$fecha=	$_GET ['fecha'];
else
$fecha=$ano."-".$mes."-".$dia_hoy;

echo "URL ?fecha=".$fecha."<br>";

// Accion a realizar
$obj_xtpl->assign ( 'tipo_accion', 'save' );
$obj_xtpl->assign ( 'tag_boton', 'Guardar' );

$obj_xtpl->assign ( 'pagina_principal', 'save_result.php' );
$obj_xtpl->assign ( 'opcion_sistema', 'cargar_resultados' );
$obj_xtpl->assign ( 'fecha', $obj_date->changeFormatDateI ($fecha,0));


$periodo='Todos';
// Listado de Sorteos
$sorteosassign='';
$sorteosnocargados='';
if ($result = $obj_modelo->GetSorteos( $fecha, $periodo )) {
	if ($obj_conexion->GetNumberRows( $result ) > 0) {
		$i = 1;
		$j = 0;
		while ( $row = $obj_conexion->GetArrayInfo ( $result ) ) {
				
			//print_r($row);
			//Saca la hora del sorteo
			$hora_sorteo= $fecha." ".$row['hora_sorteo'];
			$sorteosassign.=$row['id_sorteo']."-";
			//Valor que viene de la base de datos
			// Obtiene el parametros de los minutos para no listar el sorteo
			$minutos_bloqueo= $obj_modelo->MinutosBloqueo();
			//echo "Hora".$hora_sorteo."<br>";
				
			$fecha_hora_actual=date("Y-m-d H:i:s");
				
				
			// Restando la fecha actual con la fecha y hora del sorteo.
			$resta=strtotime($fecha_hora_actual)-strtotime($hora_sorteo);

			// Si la resta es negativo quiere decir que todavia los sorteos no han cerrado
			if ($resta>0)
			{
				//echo "pasaasa";
				/*	
				echo "<pre>";
				print_r($row);
				echo "</pre>";				
				*/	
				if (($i % 2) > 0) {
					$obj_xtpl->assign ( 'estilo_fila', 'even' );
				} else {
					$obj_xtpl->assign ( 'estilo_fila', 'odd' );
				}
				$obj_xtpl->assign ( $obj_generico->CleanTextDb ( $row ) );
				$obj_xtpl->assign ( 'sorteo', $row ['nombre_sorteo'] );
				if ($row ['numero'] == 'numero') {
					$j ++;
					$sorteosnocargados.=$row ['id_sorteo'].'-';
					$obj_xtpl->assign ( 'estilo_fila', 'evenred' );
					$obj_xtpl->assign ( 'id_Sorteo', $row ['id_sorteo']);
					$obj_xtpl->assign ( 'id_resultado', '' );
					$obj_xtpl->assign ( 'id_sorteo', $row ['id_sorteo'] );
					$obj_xtpl->assign ( 'numero', '<input id="txt_numero" name="txt_numero-' . $row ['id_sorteo'] . '" size="12" type="text" maxlength="3" />' );
					$obj_xtpl->assign ( 'terminal', '' );
					$obj_xtpl->assign('bajado', $row['bajado']);

					if ($row ['zodiacal'] == 1) {
						// Listado de Zodiacal
						if ($result_z = $obj_modelo->GetZodiacales ()) {
							while ( $row_z = $obj_conexion->GetArrayInfo ( $result_z ) ) {
								$obj_xtpl->assign ( $obj_generico->CleanTextDb ( $row_z ) );
								$obj_xtpl->assign ( 'Id_zodiacal', $row_z ['Id_zodiacal'] );
								$obj_xtpl->assign ( 'nombre_zodiacal', $row_z ['nombre_zodiacal'] );
								$obj_xtpl->assign ( 'selected', '' );
								$obj_xtpl->parse ( 'contenido.lista_cargar_resultados.lista.lista_zodiacal.op_zodiacal' );
							}
							$obj_xtpl->parse ( 'contenido.lista_cargar_resultados.lista.lista_zodiacal' );
						}
					} else if ($row ['zodiacal'] == 0) {
					}

					$obj_xtpl->assign ( 'aprox_arriba', '' );
					$obj_xtpl->assign ( 'aprox_abajo', '' );
				} else {
					$obj_xtpl->assign ( 'id_Sorteo', $row ['id_sorteo']);
					$obj_xtpl->assign ( 'id_resultado', $row ['id_resultado'] );
					$obj_xtpl->assign ( 'id_sorteo', $row ['id_sorteo'] );
					$obj_xtpl->assign ( 'numero', $row ['numero']);
					$obj_xtpl->assign ( 'terminal', substr ( $row ['numero'], 1, 2 ) );
					$obj_xtpl->assign ( 'signo', substr ( $row ['numero'], 1, 2 ) );
					$obj_xtpl->assign('bajado', $row['bajado']);

					if ($row ['zodiacal'] == 1) {
						// Listado de Zodiacal
						if ($result_z = $obj_modelo->GetZodiacales ()) {
							while ( $row_z = $obj_conexion->GetArrayInfo ( $result_z ) ) {
									
								$obj_xtpl->assign ( 'text_zodiacal', $row ['signo'] );

							}
							$obj_xtpl->parse ( 'contenido.lista_cargar_resultados.lista.text_zodiacal' );
						}
					}

					$term = (substr ( $row ['numero'], 1, 2 ));

					if ($term < 9 && $term > 0) {
						$preceroa = "0" . ($term + 1);
						$preceroo = "0" . ($term - 1);
					} else if ($term == 9) {
						$preceroa = "" . ($term + 1);
						$preceroo = "0" . ($term - 1);
					} else if ($term == 10) {
						$preceroa = "" . ($term + 1);
						$preceroo = "0" . ($term - 1);
					} else if ($term == '00') {
						$preceroa = "0" . ($term + 1);
						$preceroo = "99";
					} else if ($term == 99) {
						$preceroa = "00";
						$preceroo = "" . ($term - 1);
						;
					} else {
						$preceroa = "" . ($term + 1);
						$preceroo = "" . ($term - 1);
					}

					$obj_xtpl->assign ( 'aprox_arriba', $preceroa );
					$obj_xtpl->assign ( 'aprox_abajo', $preceroo );
				}
				
				// Parseo del bloque de la fila
				$obj_xtpl->parse ( 'contenido.lista_cargar_resultados.lista' );
				$i ++;
			}
		}
	}else {
		// Mensaje
		$obj_xtpl->assign ( 'sin_listado', $mensajes ['sin_lista'] );

		// Parseo del bloque de la fila
		$obj_xtpl->parse ( 'contenido.lista_cargar_resultados.no_lista' );
	}
	$obj_xtpl->assign ( 'faltantes', '<span class="requerido">Faltan <b>' . $j . '</b> de <b>' . ($i - 1) . ' Sorteos</b> por ingresar resultados...</span>' );
}
$sorteosassign = trim($sorteosassign, '-');
$sorteosnocargados= trim($sorteosnocargados, '-');
$obj_xtpl->assign ('sorteos', $sorteosassign);
$obj_xtpl->assign ('sorteosnocargados', $sorteosnocargados);
// Parseo del bloque
$obj_xtpl->parse('contenido.lista_cargar_resultados');

// Parseo  final del  documento
$obj_xtpl->parse('contenido');
$obj_xtpl->out('contenido');
?>

   </body>
</html>