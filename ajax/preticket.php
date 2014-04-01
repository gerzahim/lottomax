

<?php
date_default_timezone_set("America/Caracas");
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

session_start();

// Obtenemos los datos de la taquilla
$taquilla= $obj_modelo->GetIdTaquilla();

//Verificando que escriban el numero y el monto 
if(isset($_POST['txt_numero'])){
	$txt_numero=$_POST['txt_numero'];
	$txt_numero= $obj_generico->ConvertirSoloNumero($txt_numero);
	$tamano_numero = strlen($txt_numero);
	if ($tamano_numero == 1){
		$txt_numero='0'.$_POST['txt_numero'];		
	}
	
}

//verifica que el numero no sea un campo vacio 
if(empty($_POST['txt_numero']) == true){
	if ($_POST['txt_numero'] == ''){
		$txt_numero =  'a';
	}else{
		$txt_numero='00';
	}
	//
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

		//Verificando que todos los sorteos esten entre las horas disponibles
		if ($sorteo){
			$row= $obj_modelo->GetHoraSorteo($sorteo);
			//print_r($row);
			$hora_sorteo=$row;
			$hora_sorteo=strtotime($hora_sorteo);
			//Valor que debe venir de la base de datos tiempo_cierre_sorteos
			$minutos_bloqueo= $obj_modelo->MinutosBloqueo();
			//Valor que debe venir de la base de datos
			//$hora_actualMas= date('H:i:s', strtotime("+$minutos_bloqueo minutes"));
			$hora_actualMas= strtotime(date('H:i:s', strtotime("+$minutos_bloqueo minutes")));
			/*
			echo "<br>";
			echo $hora_actualMas;
			echo " - ";
			echo $hora_sorteo;			
			*/
			if ($hora_actualMas > $hora_sorteo){
				
				//redirect 
				//echo "MENSAJE DE ERROR SELECCION DE SORTEO FUERA DE HORARIO";
				
				/************* CABLEADO **********************/
				$_SESSION['mensaje']= $mensajes['no_sorteo_hora'];
				echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";	
				exit();
			}			
			
		}
		
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

if($txt_numero ==  'a' ){
	$_SESSION['mensaje']= $mensajes['no_numero'];
	echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";	
}elseif ($txt_numero == 'b'){	
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
	$id_insert_taquilla=$obj_modelo->GetUltimoIdInsert($taquilla)+1;
	// Contenido del sistema
	switch ($op_juego){
		case 1:  
			// Juega solo triple
        	//    $result= $obj_modelo->GetIdTaquilla();
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
						$result = ProcesoCupos($txt_numero, $txt_monto, $sorteo, $zodiacal, $eszodiacal,$id_insert_taquilla);						
					}
					}else{
						
	
					//El sorteo no es zodiacal !
					$eszodiacal=0; 
					$zodiacal=0;
					
					//Proceso_Cupo() funcion para determinar los cupos					
					//echo $txt_numero, $txt_monto, $sorteo, $zodiacal, $eszodiacal;
					
					$result = ProcesoCupos($txt_numero, $txt_monto, $sorteo, $zodiacal, $eszodiacal,$id_insert_taquilla);
										
				}		
			}
	    break;
		case 2:  
			// Juega Permuta
          //  $result= $obj_modelo->GetIdTaquilla();
			//recorriendo el array de los sorteos seleccionados
			foreach ( $sorteos as $sorteo) {
				// Verifica si el sorteo es Zodiacal
				if($obj_modelo->GetTrueZodiacal($sorteo)){
					//El sorteo si es zodiacal !
					//recorrer el sorteo
					$eszodiacal=1;
					$zodiacalestotal='';
					foreach ($zodiacales as $zodiacal){
						// Realizamos la permuta
						$zodiacalestotal.=$zodiacal.",";
						if (strlen($txt_numero)<3){
                        	echo "<div id='mensaje' class='mensaje' >Debe ingresar un numero de minimo tres cifras para generar la permuta !!!</div>";
						}else{
                        	$numeros_permuta = Permutar($txt_numero);
                            $numero_inicio=$numeros_permuta[0];
                            $numero_final=$numeros_permuta[count($numeros_permuta)-1];
                            $altero=0;
                            foreach ( $numeros_permuta as $numero_permuta) {
                            	//Proceso_Cupo() funcion para determinar los cupos
                                $result = ProcesoCupos($numero_permuta, $txt_monto, $sorteo, $zodiacal, $eszodiacal,$id_insert_taquilla);
                                if($result!=$txt_monto)
                                	$altero=1;
							}
							
						}						
					}
					$zodiacalestotal==trim($zodiacalestotal,",");
	//$tipo_jugada_especial,$numero_inicio,$id_sorteos,$numero_final,$numero_solicitado,$monto,$id_zodiacales,$id_taquilla,$id_insert_taquilla
					if($altero==0)
						$obj_modelo->GuardarJugadaEspecial(1,$numero_inicio,$zodiacal,$id_tipo_jugada,$matriz2[0],$matriz2[1],$matriz2[2],$matriz2[3],$taquilla,$id_insert_taquilla) )
							
				}else{
					//El sorteo no es zodiacal !
                    $eszodiacal=0;
                    $zodiacal=0;
					// Realizamos la permuta
                    if (strlen($txt_numero)<3){
                    	echo "<div id='mensaje' class='mensaje' >Debe ingresar un numero de minimo tres cifras para generar la permuta !!!</div>";
					}else{
                    	$numeros_permuta = Permutar($txt_numero);
                        $numero_inicio=$numeros_permuta[0];
                        $numero_final=$numeros_permuta[count($numeros_permuta)-1];
						$altero=0;
                        foreach ( $numeros_permuta as $numero_permuta) {
                        	//Proceso_Cupo() funcion para determinar los cupos
                        	$result = ProcesoCupos($numero_permuta, $txt_monto, $sorteo, $zodiacal, $eszodiacal,$id_insert_taquilla);
							if($result!=$txt_monto)
							$altero=1;
                        }
                        if($altero==0)
                        $obj_modelo->GuardarJugadaEspecial($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$matriz2[0],$matriz2[1],$matriz2[2],$matriz2[3],$taquilla,$id_insert_taquilla) )
					}
				}
			}
		break;
		case 3:  
			// Juega Series
			$result= $obj_modelo->GetIdTaquilla();
			//recorriendo el array de los sorteos seleccionados
			foreach ( $sorteos as $sorteo) {
				// Verifica si el sorteo es Zodiacal
				if($obj_modelo->GetTrueZodiacal($sorteo)){
					//El sorteo si es zodiacal !
					//recorrer el sorteo
					$eszodiacal=1;
					foreach ($zodiacales as $zodiacal){
						// Realizamos la serie
            			if (strlen($txt_numero)>2){
                        	echo "<div id='mensaje' class='mensaje' >Debe ingresar un numero de dos cifras para generar la serie !!!</div>";
						}else{
                        	$numeros_serie = Serializar($txt_numero);
							foreach ( $numeros_serie as $numero_serie) {
                            	//Proceso_Cupo() funcion para determinar los cupos
                                $result = ProcesoCupos($numero_serie, $txt_monto, $sorteo, $zodiacal, $eszodiacal,$id_insert_taquilla);
							}
						}
					}
				}else{
					//El sorteo no es zodiacal !
                    $eszodiacal=0;
                    $zodiacal=0;
					// Realizamos la serie
                    if (strlen($txt_numero)>2){
                    	echo "<div id='mensaje' class='mensaje' >Debe ingresar un numero de dos cifras para generar la serie !!!</div>";
					}else{
                    	$numeros_serie = Serializar($txt_numero);
						foreach ( $numeros_serie as $numero_serie) {
                        	//Proceso_Cupo() funcion para determinar los cupos
                        	$result = ProcesoCupos($numero_serie, $txt_monto, $sorteo, $zodiacal, $eszodiacal,$id_insert_taquilla);
						}
					}
				}
			}
		break;
		case 4:  
			// Juega Corridas
			$result= $obj_modelo->GetIdTaquilla();
			//recorriendo el array de los sorteos seleccionados
			foreach ( $sorteos as $sorteo) {
				// Verifica si el sorteo es Zodiacal
				if($obj_modelo->GetTrueZodiacal($sorteo)){
					//El sorteo si es zodiacal !
					//recorrer el sorteo
					$eszodiacal=1;
					foreach ($zodiacales as $zodiacal){
						// Realizamos la permuta
            			$numeros_corrida = Corrida($txt_numero);
                        if ($numeros_corrida == $txt_numero){
                        	echo "<div id='mensaje' class='mensaje' >Debe ingresar dos numeros de la forma: 'numero1-numero2' para generar la corrida !!!</div>";
						}else{
                        	foreach ( $numeros_corrida as $numero_corrida) {
	                        	//Proceso_Cupo() funcion para determinar los cupos
	                            $result = ProcesoCupos($numero_corrida, $txt_monto, $sorteo, $zodiacal, $eszodiacal,$id_insert_taquilla);
							}
						}
					}	
				}else{
					//El sorteo no es zodiacal !
                    $eszodiacal=0;
                    $zodiacal=0;
                    // Realizamos la permuta
                    $numeros_corrida = Corrida($txt_numero);
                    if ($numeros_corrida == $txt_numero){
                    	echo "<div id='mensaje' class='mensaje' >Debe ingresar dos numeros de la forma: 'numero1-numero2' para generar la corrida !!!</div>";
					}else{
                    	foreach ( $numeros_corrida as $numero_corrida) {
                        	//Proceso_Cupo() funcion para determinar los cupos
                        	$result = ProcesoCupos($numero_corrida, $txt_monto, $sorteo, $zodiacal, $eszodiacal,$id_insert_taquilla);
						}
					}
				}
			}
		break;
	}	
// Listado de Jugadas Agregadas
if( $result= $obj_modelo->GetDatosTicketTransaccional() ){
	echo "<br><table class='table_ticket' align='center' border='1' width='90%'>";
	while($row= $obj_conexion->GetArrayInfo($result)){
		$inc = "";
                $span1="";
                $span2="";
		//determinando si es un numero inc.. 
		if($row['incompleto'] == 1){
			$inc = "Inc ...";
                        $span1="<span class='requerido'>";
                        $span2="</span>";
		}else
			{
			//	$inc = "Inc ...";
				$span1="";
				$span2="";
			}	
			
			

                $zodiacal="";
                // determinando el signo zodiacal... si lo hay...
                if($row['id_zodiacal'] <> 0){
			$zodiacal = $obj_modelo->GetPreNombreSigno($row['id_zodiacal']);	
		}
		//print_r($row);
		//echo "<tr class='eveno'><td align='center'>".$span1."SORTEO: ".$obj_modelo->GetNombreSorteo($row['id_sorteo']).$span2."</td></tr>";
		echo "<tr class='eveno'><td align='center'>".$span1."".$obj_modelo->GetNombreSorteo($row['id_sorteo']).$span2."</td></tr>";
		echo "<tr class='eveni'><td align='left'>".$span1.$row['numero']." x ".$row['monto']." ".$zodiacal." ".$inc.$span2."</td></tr>";
	}		
	echo "</table>";	
}		
	
}



//Funciones

/**
 * Calcula el monto real en que podra jugar el numero
 *
 * @param integer $monto_disponible
 * @param integer $monto_jugado
 * @return array
 */

function CalculaIncompletoYnuevoMonto($monto_disponible, $monto_jugado){

	$matriz=array();

	// Matriz[0]= El monto  para cubrir el faltante
	// Matriz[1]= El switch de ser incompleto o no 1 incompleto ya de por si / 2 agotado / 3 imcompleto en esta jugada
	// Matriz[2]= El monto por el que realmente se va a jugar
	// Matriz[3]= El monto por el que quedaria disponible


	//calculando el faltante entre el numero ya jugado y el nuevo por jugar
	$montodiferencia= ($monto_disponible-$monto_jugado);

	//si no completa el monto solicitado
	if ($montodiferencia < 0){
		//Mensaje de ERROR -- NUMERO INCOMPLETO PARA ESTE SORTEO
		//el nuevo disponible es el faltante del incompleto
		$matriz[0] = $montodiferencia*(-1);
		$matriz[1] = 1;
		$matriz[2] = $monto_disponible;
		$matriz[3] = 0;
		//echo "<br> nuevo ".$num_jug_nuevodisponible;
	}else
		if ($montodiferencia ==0)
		{
			//	echo "pasa";
			$matriz[0] = 0;
			$matriz[1] = 3;
			$matriz[2] = $monto_jugado;
			$matriz[3] = 0;
		}
		else
		{
			//el nuevo disponible es el restante para numeros_jugados
			$matriz[0] = 0;
			$matriz[1] = 0;
			$matriz[2] = $monto_jugado;
			$matriz[3] = $montodiferencia;
				
		}
		 
		// $matriz2=CalculaIncompletoYnuevoMonto();
		// echo $matriz2[0]; // nuevomontodisponible o faltante
		// echo $matriz2[1]; // incompleto
		// echo $matriz2[2]; // monto real disponible para el ticket o monto solicitado
		//  print_r($matriz);
		return $matriz;

}

function ProcesoCupos($txt_numero,$txt_monto, $sorteo, $zodiacal, $esZodiacal,$id_insert_taquilla){

	global $taquilla;
	global $obj_modelo;
	global $obj_conexion;
	$fecha_hoy=date('Y-m-d');
	//determinando el tipo de jugada
	$id_tipo_jugada= $obj_modelo->GetTipoJugada($esZodiacal,$txt_numero);
	//$monto_inicial=$txt_monto;
	//revisar tabla de ticket_transaccional
	$monto_inicial=$txt_monto;
	$numero_jugadoticket= $obj_modelo->GetTicketTransaccional($txt_numero,$sorteo,$zodiacal, $id_tipo_jugada);

	if ($numero_jugadoticket['total_registros']>0 )
	{
		if($numero_jugadoticket['id_taquilla']!=$_SESSION["taquilla"]){
			$monto_restante = $numero_jugadoticket['monto_restante'];
			if ($monto_restante >0){
				$matriz2= CalculaIncompletoYnuevoMonto($monto_restante,$txt_monto);
				$txt_monto=$matriz2[2];
				// Guardar ticket a tabla transaccional
				if( $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$matriz2[0],$matriz2[1],$matriz2[2],$matriz2[3],$taquilla,$id_insert_taquilla) ){
				}
				else
				{
					$_SESSION['mensaje']= $mensajes['fallo_agregar_ticket'];
					echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
				}
			}
			else{
				//Mensaje de ERROR -- NUMERO AGOTADO PARA ESTE SORTEO
				//Se registra el numero como agotado
				$obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$txt_monto,2,0,0,$taquilla,$id_insert_taquilla);
				$_SESSION['mensaje']= $txt_numero." AGOTADO para sorteo ".$obj_modelo->GetNombreSorteo($sorteo)."  ".$obj_modelo->GetPreNombreSigno($zodiacal);
				echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
			}
		}
		else
		{
			$txt_monto+=$numero_jugadoticket['monto'];
			//adicionar y dar mensaje de confirm (adicionar al ticket u obviar)
			//significa que ya existe y debemos ver el monto que queda
			$num_ticket_inc = $numero_jugadoticket['incompleto'];
			//Verificando que si esta incompleto
			
			//$matriz2= CalculaIncompletoYnuevoMonto($monto_restante,$txt_monto);
			
			
			
			if ($txt_monto < 0){
				echo "<div id='mensaje' class='mensaje' >El monto debe ser mayor a 0 Bs !!!</div>";
				exit();
			}else if($txt_monto == 0){
				$obj_modelo->EliminarTicketTransaccionalByTicket($numero_jugadoticket['id_ticket_transaccional']);
				echo "<div id='mensaje' class='mensaje' >La jugada fue Eliminada !!!</div>";
				exit();
			}else
			if ($num_ticket_inc == '0')
				echo "--";
			elseif ($num_ticket_inc == '1'|| $num_ticket_inc == '3')
				echo "<div id='mensaje' class='mensaje' >El numero ya esta jugado y tiene su cupo completo !!!</div>";
			//Proceso CONFIRM: Elimina la apuesta existente en ticket transaccional, y para que no este repetida, la registra
			// con el nuevo monto ingresado.
			$id_ticket_transaccional= $obj_modelo->GetIDTicketTransaccional($txt_numero,$sorteo,$zodiacal);
			//echo "<input id='txt_id_ticket_transaccional' name='txt_id_ticket_transaccional' type='text' value='".$id_ticket_transaccional."'/>";
			$obj_modelo->EliminarTicketTransaccionalByTicket($id_ticket_transaccional);
			$result = ProcesoCupos($txt_numero, $txt_monto, $sorteo, $zodiacal, $esZodiacal,$id_insert_taquilla);
		}
	}else{
		
		//revisar tabla de numeros_jugados
		$numero_jugado= $obj_modelo->GetNumerosJugados($txt_numero,$sorteo,$zodiacal,$fecha_hoy);
		//significa que ya existe y debemos ver el monto que queda
		$monto_restante = $numero_jugado['monto_restante'];
		if( $numero_jugado['total_registros']>0 ){
			//echo $num_jug;
			//print_r($numero_jugado);
			//si queda por un monto mayor que 0
			if ($monto_restante >0){
				$matriz2= CalculaIncompletoYnuevoMonto($monto_restante,$txt_monto);
				$txt_monto=$matriz2[2];
				
				// Guardar ticket a tabla transaccional
				if( $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$matriz2[0],$matriz2[1],$matriz2[2],$matriz2[3],$taquilla,$id_insert_taquilla) ){
				}else{
					$_SESSION['mensaje']= $mensajes['fallo_agregar_ticket'];
					echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
				}
			}else{
				//Mensaje de ERROR -- NUMERO AGOTADO PARA ESTE SORTEO
				//Se registra el numero como agotado
				$matriz2= CalculaIncompletoYnuevoMonto($monto_restante,$txt_monto);
				$txt_monto=$matriz2[2];
				
				$obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$matriz2[0],$matriz2[1],$matriz2[2],$matriz2[3],$taquilla,$id_insert_taquilla);
				$_SESSION['mensaje']= $txt_numero." AGOTADO para sorteo ".$obj_modelo->GetNombreSorteo($sorteo)."  ".$obj_modelo->GetPreNombreSigno($zodiacal);
				echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
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
							//  $monto_restante= $txt_monto;
							//registrar $num_jug_nuevodisponible, $incompleto
							$matriz2= CalculaIncompletoYnuevoMonto($monto_cupoespecial, $txt_monto);
							$txt_monto=$matriz2[2];
							if( $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$matriz2[0],$matriz2[1],$matriz2[2],$matriz2[3],$taquilla,$id_insert_taquilla) ){

							}
							else{
								$_SESSION['mensaje']= $mensajes['fallo_agregar_ticket'];
								echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
							}

						}else{
							//Mensaje de ERROR -- NUMERO BLOQUEADO PARA ESTE SORTEO

							//Se registra el numero como agotado
							$obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$txt_monto,2,0,0,$taquilla,$id_insert_taquilla);

							$_SESSION['mensaje']= $txt_numero." AGOTADO para sorteo ".$obj_modelo->GetNombreSorteo($sorteo)."  ".$obj_modelo->GetPreNombreSigno($zodiacal);
							echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
						}

					}else{
						//No posee cupo especial, ni esta en tabla numeros_jugados
						//revisar tabla de cupo_general
						//procesa A

						//determinando monto_cupo segun id tipo de jugada
						$cupo_general= $obj_modelo->GetCuposGenerales($id_tipo_jugada);
						// Asignamos al monto restante el monto de apuesta para efectos de la funcion CalculaIncompletoYnuevoMonto
						// $monto_restante= $txt_monto;

						// Calculamos el valor de incompleto


						// Calculando $num_jug_nuevodisponible,$incompleto
						$matriz2= CalculaIncompletoYnuevoMonto($cupo_general, $txt_monto);
						$txt_monto=$matriz2[2];
						if( $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$matriz2[0],$matriz2[1],$matriz2[2],$matriz2[3],$taquilla,$id_insert_taquilla) ){

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
				//echo "PASA";
				//determinando monto_cupo segun id tipo de jugada
				$cupo_general= $obj_modelo->GetCuposGenerales($id_tipo_jugada);
				
				// Asignamos al monto restante el monto de apuesta para efectos de la funcion CalculaIncompletoYnuevoMonto
				$monto_restante= $txt_monto;

				// Calculamos el valor de incompleto
				 

				// Calculando $num_jug_nuevodisponible,$incompleto
				$matriz2= CalculaIncompletoYnuevoMonto($cupo_general, $monto_restante);

				 
			/*	echo "este".$cupo_general;
				echo "sisi";
				echo "monto".$matriz2[2];
				exit;*/
				// Guardar ticket a tabla transaccional
				if( $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$id_tipo_jugada,$matriz2[0],$matriz2[1],$matriz2[2],$matriz2[3],$taquilla,$id_insert_taquilla) ){

				}
				else{
					//$_SESSION['mensaje']= $mensajes['fallo_agregar_ticket'];
					$_SESSION['mensaje']= "Error No se logro ingresar la jugada al ticket!!!";
					echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
				}
					
			}
		}
		return $txt_monto;
	}
}

// Funcion que permuta los numeros
function Permutar($txt_numero){

	// Esta funcion de permuta, funciona con y sin numeros repetidos
	// Los Numero a combinar se deben guardar en un array
	// Ejemplo
	//  $letras = array('1', '2', '3'); numero 123
	// En el caso de tener numeros repetidos se debe agregar al array numeros duplicados, (el orden es indiferente!)
	// Ejemplo
	//  $letras = array('1', '2', '3', '33');  numero 1233
	//  $letras = array('1', '2', '3', '4', '44'); numero 12443
	//  $letras = array('2', '1', '3', '11', '33'); numero 33211



	// Verificar si tiene repeticiones y crea un nuevo array
	// esto para anexar a la final los repetidos con con 22, 55
	$addarr = array();
	foreach (count_chars($txt_numero, 1) as $i => $val) {
		if ($val >= 2){
			$addarr[] = chr($i).chr($i);
		}
	}


	// Creando un arreglo principal con el numero dado
	$arr1 = str_split($txt_numero);

	// Eliminar elementos duplicados de un array en PHP
	$arr1 = array_values(array_unique($arr1));

	// Uniendo el array principal y el array con los repetidos en caso de que alla repetidos
	$letras = array_merge($arr1, $addarr);

	/*
	echo "<pre>";
	print_r($letras);
	echo "</pre>";
	exit();
	*/
	
	$arr = array(); //Array de combinaciones

	// Creando array bidimensional para las combinaciones
	// Foreach Ciclico para hacer todas las combinaciones posibles en grupo de 3
	// resultado de combinaciones en un arreglo bidimensional

	foreach ($letras as $l) {
		$letra1 = $l;
		foreach ($letras as $l2) {
			if ( strcmp($l2,$letra1)!= 0){
				$letra2 = $l2;
				foreach ($letras as $l3) {
					if (strcmp($l3,$letra2)!= 0 && strcmp($l3,$letra1)!= 0){
						$letra3 = $l3;
						$arr[] = array($l,$l2,$l3);
	
					}
	
				}
	
			}
	
		}
	}	
	
	// resultado bidimensional convertirlo a array unidimensional
	foreach ($arr as $l) {
		$duro =$l[0].$l[1].$l[2];
		$letra11[] = $duro;
	}



	/******** Validacion para repetidos 'c', 'cc' *******/

	// Eliminar elementos duplicados de un array en PHP
	$letra11 = array_values(array_unique($letra11));


	// Elimando ultimo valor del string si es mayor a 3
	foreach ($letra11 as $letra12){
		if (strlen($letra12) > 3){
			$letra13[] = substr($letra12, 0, 3);
		}else{
			$letra13[] = $letra12;
		}
	}

	// Eliminar elementos duplicados de un array en PHP
	//Eliminando los valores repetidos en caso de valores truncados
	$letra13 = array_values(array_unique($letra13));


	// Eliminando la combinacion extra 333, 444, 555...
	foreach ($letra13 as $letra14) {
		$bandera=0;
		foreach (count_chars($letra14, 1) as $i => $val) {
			if ($val >= 3){
				$bandera=1;
			}
		}
		if ($bandera != 1){
			$letra15[] = $letra14;
		}
	}


	return $letra15;
}

// Funcion que genera la serie de un numero
function Serializar($txt_numero){
	$serie= array();
	$longitud = 10;

	// Concatena los caracteres de la serie
	$cadena= '';
	for($i = 0; $i < $longitud; $i++){
		$cadena = $i.$txt_numero;
		$serie[] = $cadena;
	}

	return $serie;

}

// Funcion que genera la corrida entre dos numeros
function Corrida($txt_numero){
	$corrida= array();

	if (strlen(strchr($txt_numero, '-'))>0){
		$numeros=explode('-',$txt_numero);
		$numero1= $numeros[0];
		$numero2= $numeros[1];
		$diferencia=$numero1-$numero2;
		if ($numero1 >0 && $numero2>0){
			$cadena= '';
			$flag= false;

			if ($diferencia<0){
				$diferencia = $diferencia * (-1);
				$flag=true;
			}

			for($i = 0; $i <= $diferencia; $i++){
				if ($flag){
					$cadena = $numero1 + $i;
					$corrida[] = $cadena;
				}else{
					$cadena = $numero2 + $i;
					$corrida[] = $cadena;
				}


			}
			return $corrida;
		}else{
			return $txt_numero;
		}
	}else{
		return $txt_numero;
	}




}


?>