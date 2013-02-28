<?php

// Archivo de variables de configuracion
require_once('../config/config.php');
$obj_config= new ConfigVars();

// Archivo de mensajes
require('.'.$obj_config->GetVar('ruta_config').'mensajes.php');


// Clase Generica
require('.'.$obj_config->GetVar('ruta_libreria').'Generica.php');
$obj_generico= new Generica();

// Conexion a la bases de datos
require('.'.$obj_config->GetVar('ruta_libreria').'Bd.php');
$obj_conexion= new Bd();
if( !$obj_conexion->ConnectDataBase($obj_config->GetVar('host'), $obj_config->GetVar('data_base'), $obj_config->GetVar('usuario_db'), $obj_config->GetVar('clave_db')) ){
	echo "sin_conexion_bd";
}

// Modelo asignado
require('.'.$obj_config->GetVar('ruta_modelo').'Ventas.php');
$obj_modelo= new Ventas($obj_conexion);


 
//Funciones

function CalculaIncompletoYnuevoMonto($txt_monto, $num_jug){

	$matriz=array();
	
	//calculando el faltante entre el numero ya jugado y el nuevo por jugar
	$montodiferencia= ($num_jug-$txt_monto);	
	
	//si no completa el monto solicitado
	if ($montodiferencia < 0){
		//Mensaje de ERROR -- NUMERO INCOMPLETO PARA ESTE SORTEO
		
		//el nuevo disponible es el faltante del incompleto
		$num_jug_nuevodisponible = (-1)*$montodiferencia;
		$incompleto=1;
		$matriz[2] = $txt_monto-$num_jug_nuevodisponible; // si falta txt_monto es la diferencia
			
	}else{
		//el nuevo disponible es el restante para numeros_jugados
		$num_jug_nuevodisponible = $montodiferencia;
		$incompleto=0;
		$matriz[2] = $txt_monto; // si no falta lo juega por lo que pidio
	}
	  
     $matriz[0]=$num_jug_nuevodisponible; 
     $matriz[1]=$incompleto; 	 

	// $matriz2=CalculaIncompletoYnuevoMonto(); 
	// echo $matriz2[0]; // nuevomontodisponible o faltante
	// echo $matriz2[1]; // incompleto
	// echo $matriz2[2]; // monto real disponible para el ticket o monto solicitado
	     
	return $matriz;
	
}

 
function ProcesoCupos($txt_numero,$txt_monto, $sorteo, $zodiacal, $esZodiacal){
	
	global $obj_modelo;
	
	//revisar tabla de ticket_transaccional
	
	$numero_jugadoticket= $obj_modelo->GetTicketTransaccional($txt_numero,$sorteo,$zodiacal);
	
	if ( $numero_jugadoticket['total_registros']>0 ){
		
		//adicionar y dar mensaje de confirm (adicionar al ticket u obviar)
		
		//significa que ya existe y debemos ver el monto que queda
		$num_ticket_restante = $numero_jugadoticket['monto_restante'];
		$num_ticket_monto = $numero_jugadoticket['monto'];		
		$num_ticket_inc = $numero_jugadoticket['incompleto'];
		
		//Verificando que si esta incompleto
		if ($num_ticket_inc == '1'){
			
			// ya no se puede anadir, mas bien le falto por jugar
			echo "El numero ya esta jugado y tiene su cupo completo";
			
		}else{
			
		//no esta incompleto
		//todavia le queda cupo
		
		// confirm
			
		}
		
	}else{
	

	//revisar tabla de numeros_jugados
	$numero_jugado= $obj_modelo->GetNumerosJugados($txt_numero,$sorteo,$zodiacal);
							
	if( $numero_jugado['total_registros']>0 ){
		
		//print_r($numero_jugado);
		
		//significa que ya existe y debemos ver el monto que queda
		$num_jug = $numero_jugado['monto_restante'];
		
		//si queda por un monto mayor que 0
		if ($num_jug >0){
			
			$matriz2= CalculaIncompletoYnuevoMonto($txt_monto, $num_jug);

			//registrar $num_jug_nuevodisponible, $incompleto 						 
 			$num_jug_nuevodisponible = $matriz2[0];
 			$incompleto= $matriz2[1];
 			$txt_monto= $matriz2[2];
						
			$id_tipo_jugada= $obj_modelo->GetTipoJugada($esZodiacal,$txt_numero); 
			
			// Guardar ticket a tabla transaccional
			if( $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$num_jug_nuevodisponible,$incompleto,$txt_monto) ){
																		
			}else{
				
				$_SESSION['mensaje']= $mensajes['fallo_agregar_ticket'];
				echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
			}									
						
		}else{
			
			//Mensaje de ERROR -- NUMERO AGOTADO PARA ESTE SORTEO
			//echo $mensajes['numero_agotado'];
			$_SESSION['mensaje']= $txt_numero." AGOTADO para sorteo ".$obj_modelo->GetNombreSorteo($sorteo)."  ".$obj_modelo->GetPreZodiacal($zodiacal);
			echo "<script language='JavaScript'> alert('Hola'); </script>";
						
			//echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";			
						
		}
					
	}else{
		//No existe aun
		//revisar tabla de cupo_especial
		$cupo_especial= $obj_modelo->GetCuposEspeciales($txt_numero,$sorteo,$zodiacal);
		
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
						if( $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$num_jug_nuevodisponible,$incompleto,$txt_monto) ){
																		
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

					$id_tipo_jugada= $obj_modelo->GetTipoJugada($esZodiacal,$txt_numero); 					
														
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
					if( $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$num_jug_nuevodisponible,$incompleto,$txt_monto) ){
																	
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
		
					//No posee cupo especial, ni esta en tabla numeros_jugados
					//revisar tabla de cupo_general							
					//procesa A

					$id_tipo_jugada= $obj_modelo->GetTipoJugada($esZodiacal,$txt_numero); 					
														
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
					if( $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$num_jug_nuevodisponible,$incompleto,$txt_monto) ){
																	
					}
					else{
						$_SESSION['mensaje']= $mensajes['fallo_agregar_ticket'];
						echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
					}				
			
		}
		
		
		
	}
					

	
	return 1;
	
	}
}


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
		/************* CABLEADO *********************
		//Verificando que todos los sorteos esten entre las horas disponibles
		if ($sorteo){
			$row= $obj_modelo->GetHoraSorteo($sorteo);
			$hora_sorteo=$row['hora_sorteo'];
			
			//Valor que debe venir de la base de datos tiempo_cierre_sorteos
			//$minutos_bloqueo= $obj_modelo->MinutosBloqueo();
			/************* CABLEADO **********************
			$minutos_bloqueo= 5;
						
			//Valor que debe venir de la base de datos
			$hora_actualMas= strtotime("+$minutos_bloqueo minutes");
			
			if ($hora_actualMas > $hora_sorteo){
				
				//redirect 
				// MENSAJE DE ERROR SELECCION DE SORTEO FUERA DE HORARIO 
				 
			}			
			
		}
		*/
		
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
					//El sorteo si es zodiacal !
					//recorrer el sorteo
					$eszodiacal=1;					
					
					foreach ($zodiacales as $zodiacal){
						
						//Proceso_Cupo() funcion para determinar los cupos					
						//echo $txt_numero, $txt_monto, $sorteo, $zodiacal, $eszodiacal;
						
						$result = ProcesoCupos($txt_numero, $txt_monto, $sorteo, $zodiacal, $eszodiacal);						
						
					}
					
					
					
					}else{
						
	
					//El sorteo no es zodiacal !
					$eszodiacal=0; 
					$zodiacal=0;
					
					//Proceso_Cupo() funcion para determinar los cupos					
					//echo $txt_numero, $txt_monto, $sorteo, $zodiacal, $eszodiacal;
					
					$result = ProcesoCupos($txt_numero, $txt_monto, $sorteo, $zodiacal, $eszodiacal);
										
				}		
			}
			


			
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


?>