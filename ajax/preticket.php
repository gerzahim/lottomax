<?php

// Archivo de variables de configuracion
require_once('../config/config.php');
$obj_config= new ConfigVars();

// Archivo de mensajes
require_once('.'.$obj_config->GetVar('ruta_config').'mensajes.php');

// Clase Generica
require('.'.$obj_config->GetVar('ruta_libreria').'Generica.php');
$obj_generico= new Generica();

// Conexion a la bases de datos
require('.'.$obj_config->GetVar('ruta_libreria').'Bd.php');
$obj_conexion= new Bd();
if( !$obj_conexion->ConnectDataBase($obj_config->GetVar('host'), $obj_config->GetVar('data_base'), $obj_config->GetVar('usuario_db'), $obj_config->GetVar('clave_db')) ){
	echo "sin_conexion_bd";
}


//Funciones
/*    $matriz2=CalculaIncompletoYnuevoMonto(); 
    echo $matriz2[0]; // nuevomontodisponible o faltante
    echo $matriz2[1]; // incompleto
    
    */
function CalculaIncompletoYnuevoMonto($txt_monto, $num_jug){
	
	//calculando el faltante entre el numero ya jugado y el nuevo por jugar
	$montodiferencia= ($num_jug-$txt_monto);	
	
	//si no completa el monto solicitado
	if ($montodiferencia < 0){
		//Mensaje de ERROR -- NUMERO INCOMPLETO PARA ESTE SORTEO
		
		//el nuevo disponible es el faltante del incompleto
		$num_jug_nuevodisponible = (-1)*$montodiferencia;
		$incompleto=1;
			
	}else{
		//el nuevo disponible es el restante para numeros_jugados
		$num_jug_nuevodisponible = $montodiferencia;
		$incompleto=0;
	}
	
	 $matriz=array();
	  
     $matriz[0]=$num_jug_nuevodisponible; 
     $matriz[1]=$incompleto; 	 
     
	return $matriz;
	
}


// Modelo asignado
require('.'.$obj_config->GetVar('ruta_modelo').'Ventas.php');
$obj_modelo= new Ventas($obj_conexion);

//Verificando que escriban el numero y el monto 
if(isset($_POST['txt_numero'])){
	$txt_numero=$_POST['txt_numero'];
	$tamano_numero = strlen($txt_numero);
	if ($tamano_numero < 2){
		$txt_numero=1;		
	}
	
}else{
	$txt_numero=0;
}

if(isset($_POST['txt_monto'])){
	$txt_monto=$_POST['txt_monto'];
}else{
	$txt_monto=0;
}

// Verificando que marquen algun sorteo o Zodiacal
$flagSorteo = 1;
$flagZodiacal = 1;

// Validando que si marco al menos un sorteo
if(!empty($_POST['ss'])) {
    // Si selecciono algun sorteo	
	$flagSorteo = 1;
	
	// asignado todos los sorteos a una variable en array()
	$sorteos =$_POST['ss'];
	
	//recorriendo el array de los sorteos
	foreach ( $sorteos as $sorteo) {
		// Verifica si el sorteo es Zodiacal
		if($obj_modelo->GetTrueZodiacal($sorteo)){
			//Verificando si marco al menos un signo ari, sag, etc...			
			if(!empty($_POST['zz'])) {
				// Si Marco al menos un signo 
				$flagZodiacal = 1;
				// asignado todos los zodiacales a una variable en array()
				$zodiacales =$_POST['zz'];
			}else{
				// No Marco al menos un signo 
				$flagZodiacal = 0;				
			}			
		}		
	}   

}else{
	// No selecciono ningun sorteo	
	$flagSorteo = 0;
}




// Clase XTemplate
//require('.'.$obj_config->GetVar('ruta_libreria').'XTemplate.php');
//$obj_xtpl = new XTemplate('.'.$obj_config->GetVar('ruta_vista')."ticket".$obj_config->GetVar('ext_vista'));

if($txt_numero == 0 ){
	$_SESSION['mensaje']= $mensajes['no_numero'];
	echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";	
}elseif ($txt_numero == 1 ){	
	$_SESSION['mensaje']= $mensajes['no_numero_completo'];
	echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
	
}elseif ($txt_monto == 0 ){	
	$_SESSION['mensaje']= $mensajes['no_monto'];
	echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
	
}elseif ($flagSorteo == 0 ){	
	$_SESSION['mensaje']= $mensajes['no_sorteo'];
	echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
	//exit();
}elseif ($flagZodiacal == 0 ){	
	$_SESSION['mensaje']= $mensajes['no_zodiacal'];
	echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
	//exit();
}else {	
	
	// Asignando El Modo de juego 
	$op_juego=$_POST['op_juego']; 
	
	// Contenido del sistema
	switch ($op_juego){
	
		case 1:  
			// Juega solo triple
			$result= $obj_modelo->GetIdTaquilla();
			
			//recorriendo el array de los sorteos seleccionados	
			foreach ( $sorteos as $sorteo) {
				
				// Verifica si el sorteo es Zodiacal
				if($obj_modelo->GetTrueZodiacal($sorteo)){
					
					foreach ($zodiacales as $zodiacal){
						
						$eszodiacal=1; 
						
						//revisar tabla de numeros jugados
						
						//revisar tabla de cupo_especial
						//revisar tabla de cupo_general
						
						// guardar registro en tabla de numeros jugados

					
						// Agregar ticket a tabla transaccional
						if( $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$txt_monto) ){
																		
						}
						else{
							$_SESSION['mensaje']= $mensajes['fallo_agregar'];
							echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
						}			
					}
				// El sorteo no es Zodiacal
				}else{
					
					//CREAR FUNCION Proceso_Cupo()
					
					$eszodiacal=0; 
					$zodiacal=0;
					
					//revisar tabla de numeros_jugados
					$numero_jugado= $obj_modelo->GetNumerosJugados($txt_numero,$sorteo);
					
					if( $numero_jugado['total_registros']>0 ){
						//significa que ya existe y debemos ver el monto que queda
						$num_jug = $numero_jugado['monto_restante'];
						//si queda por un monto mayor que 0
						if ($num_jug >0){
														
	 						$matriz2= CalculaIncompletoYnuevoMonto($txt_monto, $num_jug);
	 						 
	 						$num_jug_nuevodisponible = $matriz2[0];
	 						$incompleto= $matriz2[1];

							
							//registrar $num_jug_nuevodisponible, $incompleto 
							
							// Guardar ticket a tabla transaccional
							if( $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$txt_monto,$num_jug_nuevodisponible,$incompleto) ){
																			
							}
							else{
								$_SESSION['mensaje']= $mensajes['fallo_agregar_ticket'];
								echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
							}							
							
							
							
						}else{
							//Mensaje de ERROR -- NUMERO AGOTADO PARA ESTE SORTEO
							
						}
						
					}else{
						//No existe aun
						//revisar tabla de cupo_especial
						$cupo_especial= $obj_modelo->GetCuposEspeciales($txt_numero,$sorteo);
						
						if( $cupo_especial['total_registros']>0 ){
							while($row= $obj_conexion->GetArrayInfo($cupo_especial['result'])){
								
								//recortando el formato 2013-02-26 00:00:00 a 2013-02-26	
								$fecha_desde = substr($row["fecha_desde"], 0, 10);
								$fecha_hasta = substr($row["fecha_hasta"], 0, 10);
								
								//funcion para saber si hoy esta entre dos fechas dadas
								// regresa 1 si esta entre las fechas; 0 de lo contrario
								$fecha_efectiva= $obj_modelo->entreFechasYhoy($fecha_desde,$fecha_hasta);
								
								//si esta entre las fechas del bloqueo
								if ($fecha_efectiva == 1){
									
									//significa que ya existe y debemos ver el monto que queda									
									$monto_cupoespecial= $row["monto_cupo"];

									//si queda por un monto mayor que 0
									if ($monto_cupoespecial >0){
										
										//calculando el faltante entre el numero ya jugado y el nuevo por jugar
										$montodiferencia= ($monto_cupoespecial-$txt_monto);
										
										//si no completa el monto solicitado
										if ($montodiferencia < 0){
											//Mensaje de ERROR -- NUMERO INCOMPLETO PARA ESTE SORTEO
											
											//el nuevo disponible es el faltante del incompleto
											$num_jug_nuevodisponible = (-1)*$montodiferencia;
											$incompleto=1;
												
										}else{
											//el nuevo disponible es el restante para numeros_jugados
											$num_jug_nuevodisponible = $montodiferencia;
											$incompleto=0;
										}
										
										//registrar $num_jug_nuevodisponible, $incompleto 
										
										// Guardar ticket a tabla transaccional
										if( $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,0,$txt_monto,$num_jug_nuevodisponible,$incompleto) ){
																						
										}
										else{
											$_SESSION['mensaje']= $mensajes['fallo_agregar_ticket'];
											echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
										}							
										
										
										
									}else{
										//Mensaje de ERROR -- NUMERO BLOQUEADO PARA ESTE SORTEO
									}									
										
								}else{
									//No posee cupo especial, ni esta en tabla numeros_jugados
									//revisar tabla de cupo_general							
									//procesa A
															
									$tamano_numero = strlen($txt_numero);
									if ($tamano_numero == 3){
										$estriple=1;	
									}
									
									if ($tamano_numero == 2){
										$estriple=0;	
									}
																		
									$id_tipo_jugada= $obj_modelo->GetTipoJugada($eszodiacal,$estriple);

																		
									//determinando monto_cupo segun id tipo de jugada									
									$cupo_general= $obj_modelo->GetCuposGenerales($id_tipo_jugada);
								
									//calculando el faltante entre el numero ya jugado y el nuevo por jugar
									$montodiferencia= ($cupo_general-$txt_monto);
									
									//si no completa el monto solicitado
									if ($montodiferencia < 0){
										//Mensaje de ERROR -- NUMERO INCOMPLETO PARA ESTE SORTEO
										
										//el nuevo disponible es el faltante del incompleto
										$num_jug_nuevodisponible = (-1)*$montodiferencia;
										$incompleto=1;
											
									}else{
										//el nuevo disponible es el restante para numeros_jugados
										$num_jug_nuevodisponible = $montodiferencia;
										$incompleto=0;
									}									
									
									// Calculando $num_jug_nuevodisponible,$incompleto
									
									// Guardar ticket a tabla transaccional
									if( $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,0,$txt_monto,$num_jug_nuevodisponible,$incompleto) ){
																					
									}
									else{
										$_SESSION['mensaje']= $mensajes['fallo_agregar_ticket'];
										echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
									}									
									
									
								}
								
								
							}							
							
						}else{
							//No posee cupo especial, ni esta en tabla numeros_jugados
							//revisar tabla de cupo_general							
							//procesa A
						
						}
						
						
						
					} 
					
					//revisar tabla de cupo_especial
					//revisar tabla de cupo_general

					


					
					
					
					
					
					
					
				}		
			}
			/*
			// Listado de Sorteos
			if( $result= $obj_modelo->GetDatosTicketTransaccional() ){
				echo "<br><table class='table_ticket' align='center' border='1' width='90%'>";
				while($row= $obj_conexion->GetArrayInfo($result)){
					//print_r($row);
					echo "<tr class='eveno'><td align='center'>SORTEO: ".$obj_modelo->GetNombreSorteo($row['id_sorteo'])."</td></tr>";
					echo "<tr class='eveni'><td align='left'>".$row['numero']." x ".$row['monto']."</td></tr>";	
				}		
				echo "</table>";	
			}			
			*/

			
	     break;
		 
		case 2:  
			// Juepa Permuta
	     break;
		 
		case 3:  
			// Juega Series
	     break;
	     
		case 4:  
			// Juega Corridas
	     break;	 
        
	}	
	
}



/*
 * 
 * 			<option value="2">Permuta</option>
			<option value="3">Series</option>
			<option value="4">Corridas</option>
			<option value="5">Astral</option>
op=ventas&accion=add&
txt_numero=&
txt_monto=&
op_juego=1&
cant_sorteos=3&
s1=on&s2=on&s3=on&
z00=on&z01=on&z02=on&z03=on&z04=on&z05=on&z06=on&z07=on&z08=on&z09=on&z10=on&z11=on&z12=on&
efectivo=150&cambio=30&total=120.00

$codigo=$_REQUEST['txt_numero'];
echo $codigo;
*/


/*
foreach ( $_POST as $key => $value) {

  echo "<p>".$key."</p>";
  echo "<p>".$value."</p>";
  echo "<hr />";

}

echo "
<table class='table_ticket' align='center' border='0' width='90%'>
	<tr>
		<td align='center'><strong>Sorteo<strong></td>
		<td align='center'><strong>Numero<strong></td>
		<td align='center'><strong>Monto<strong></td>		
	</tr>
	<tr>
		<td align='center'>Chance A 8 PM</td>
		<td align='center'>124</td>
		<td align='center'>20</td>
	</tr>																																			
</table>    
";


*/


/*
 * lo primero es validar que txt_numero=&
txt_monto=& no esten vacios, de lo contrario enviar mensaje de error

luego validamos que op_juego para llevar la variable a un switch
crear variable de sesion
dos for uno para sorteos 
 y (if es zodiacal) dentro de este los zodiacales

 * 
 * */



// Parseo  final del  documento
//$obj_xtpl->parse('contenido1');
//$obj_xtpl->out('contenido1');
?>