<html>
   <head><title>Impresion de Ticket</title>
<!--
   <script language="javascript" type="text/javascript" src="../jscripts/jquery-latest.js"></script>
   <script language="javascript" type="text/javascript" src="../jscripts/PluginPrint.js"></script>        
   <script language="javascript" type="text/javascript" src="../jscripts/DefaultPrinter.js"></script>    
   </head>
   <body id="content" bgcolor="#FFF380">
   
   <applet name="jzebra" code="jzebra.PrintApplet.class" archive="../jscripts/jzebra.jar" width="50px" height="50px">
	  <param name="printer" value="zebra">
   </applet>
  -->
  
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

//$id_taquilla=2;
// Obtenemos los datos de la taquilla
$id_taquilla= $obj_modelo->GetIdTaquilla();

/************* CABLEADO **********************/
//id_taquilla debe venir de una variable de sesion

$info_ticket= $obj_modelo->GetLastTicket($id_taquilla);
//echo "<pre>".print_r($info_ticket)."</pre>";
$id_ticket=$info_ticket["id_ticket"];
$serial=$info_ticket["serial"]; 
$string=$info_ticket["fecha_hora"]; 
$year = substr($string,0,4);
$month = substr($string,5,2);
$day = substr($string,8,2);
$hour = substr($string,11,2);
$minute = substr($string,14,2);
//$fecha_hora=$day."-".$month."-".$year." ".$hour.":".$minute;
$fecha_hora=$day."-".$month."-".$year;

if($hour > 11){
	$formato="PM";	
}else{
	$formato="AM";
}
$formato_militar= array("13","14","15","16","17","18","19","20","21","22","23","24");
$formato_civil= array("01","02","03","04","05","06","07","08","09","10","11","12");

$hour= str_replace($formato_militar,$formato_civil,$hour);
$hora=$hour.":".$minute." ".$formato;

$total_ticket=$info_ticket["total_ticket"]; 
$id_usuario=$info_ticket["id_usuario"];
$nombre_usuario=$obj_modelo->GetNombreUsuarioById($id_usuario);
$nombre = explode(" ", $nombre_usuario);
$nombre = $nombre[0];

$info_agencia= $obj_modelo->GetDatosParametros();
$nombre_agencia=$info_agencia["nombre_agencia"];
$tiempo_vigencia_ticket=$info_agencia["tiempo_vigencia_ticket"];



// ENCABEZADO DEL TICKET
$data="SISTEMA LOTTOMAX";
$data.="<br>";
$data.="AGENCIA: ".$nombre_agencia;
$data.="<br>";
$data.="TICKET: ".$id_ticket;
$data.="<br>";
$data.="SERIAL: ".$serial;
$data.="<br>";
$data.="FECHA: ".$fecha_hora;
$data.="<br>";
$data.="HORA: ".$hora;
$data.="<br>";
$data.="TAQUILLA: ".$id_taquilla;
$data.="<br>";
$data.="VENDEDOR: ".$nombre;
$data.="<br>";
$data.="------------------------------------";

$data1="";


//setea impreso=1 en ticket para saber que ya esta impreso
$obj_modelo->SeteaImpresionenTicket($id_ticket);

//Determinando Numeros No Zodiacales Primero
$sorteosenticket=array();
$combinacionunica=array();
$ticket_completo=array();

//Determinando Numeros No Zodiacales Primero
if( $result= $obj_modelo->GetDetalleTicketNoZodiacalByIdticket($id_ticket) ){
	//Numeros jugados NoZodiacal
	$numero_jugadasNoZodiacal=$obj_conexion->GetNumberRows($result);
	while($row= $obj_conexion->GetArrayInfo($result)){

		$combinacion=$row['numero']." x ".$row['monto'];	
			
		//Guardar todos los sorteos pero una sola vez
		if(!in_array($row['id_sorteo'], $sorteosenticket) ) {
			$sorteosenticket[]=$row['id_sorteo'];
		}		

		//Guardar todos las combinaciones Unicas de Numero y Monto 14-1.00
		if(!in_array($combinacion, $combinacionunica) ) {
			$combinacionunica[]=$combinacion;
		}
		$ticket_completo[]=$row['id_sorteo']."x".$row['numero']." x ".$row['monto'];
	}// fin de while
	

	// Creamos un array General $combinacion_ticket
	// donde el key es la combinacion de ['numero x monto']
	// y donde los valores es una cadena con los id_sorteo  [123 x 1.00] => 26-27-41-42
	$combinacion_ticket=array();
	$i=0;
	for($i;$i<count($combinacionunica);$i++){
		$j=0;
		for($j;$j<count($sorteosenticket);$j++){
				
			//verificar si esta combinacion existe
			foreach ($ticket_completo as $tc){	
				$combinacion_creada= $sorteosenticket[$j]."x".$combinacionunica[$i];
				if ($tc == $combinacion_creada){
										
					if (isset($combinacion_ticket[$combinacionunica[$i]])) {
						$combinacion_ticket[$combinacionunica[$i]].= "-".$sorteosenticket[$j];
					}else{
						$combinacion_ticket[$combinacionunica[$i]]= $sorteosenticket[$j];
					}										
					
				}	
			}
		}
	}

	//Creando el Ticket Final 
	
	// Invirtiendo el Arreglo 
	// Ahora los keys sera la cadena de los Id_sorteos [4-5-6-11-12-13-15-16-79-80] => Array
	// y los valores sera un subarray con todos las combinaciones de ['numero x monto'] 
	$ticket_final=array();
	foreach ($combinacion_ticket as $comb_numMonto => $todoslossorteos){
		$ticket_final[$todoslossorteos][]=$comb_numMonto;
	}
	

	$nombre_sorteo=array();
	$i=0;
	foreach ($ticket_final as $todoslossorteos => $comb_numMonto){

		//Convertir en array todos los sorteos 
		$todoslossorteos2 = preg_split('/-/', $todoslossorteos);
		
		foreach ($todoslossorteos2 as $ts){
			if (!isset($nombre_sorteo[$ts])) {
				$nombre_sorteo[$ts]=$obj_modelo->GetNombreSorteo($ts);
			}
			$data.="<br>"; //para cada nombre de sorteo aparte
			$data.="-".$nombre_sorteo[$ts];							
		}
		
		foreach($comb_numMonto as $cn){
			$data.="<br>"; //para cada nombre de sorteo aparte
			$data.=$cn;							
		}
		$data.="<br>";// para separar bloques

	}
	


}

//Determinando Ahora Numeros Zodiacales
$sorteosenticket=array();
$combinacionunica=array();
$ticket_completo=array();

if( $result= $obj_modelo->GetDetalleTicketZodiacalByIdticket($id_ticket) ){
	//Numeros jugados NoZodiacal
	$numero_jugadasZodiacal=$obj_conexion->GetNumberRows($result);
	
	$numero_jugadas=$numero_jugadasZodiacal+$numero_jugadasNoZodiacal;
		
	while($row= $obj_conexion->GetArrayInfo($result)){
		
		$combinacion=$row['numero']." x ".$row['monto']."-".$row['id_zodiacal'];
			
		//Guardar todos los sorteos pero una sola vez
		if(!in_array($row['id_sorteo'], $sorteosenticket) ) {
			$sorteosenticket[]=$row['id_sorteo'];
		}

		//Guardar todos las combinaciones Unicas de Numero,Monto y IdZodiacal 14x1.00x9
		if(!in_array($combinacion, $combinacionunica) ) {
			$combinacionunica[]=$combinacion;
		}
		$ticket_completo[]=$row['id_sorteo']."x".$row['numero']." x ".$row['monto']."-".$row['id_zodiacal'];
	}// fin de while

	// Creamos un array General $combinacion_ticket
	// donde el key es la combinacion de ['numero x monto']
	// y donde los valores es una cadena con los id_sorteo  [123 x 1.00] => 26-27-41-42
	$combinacion_ticket=array();
	$i=0;
	for($i;$i<count($combinacionunica);$i++){
		$j=0;
		for($j;$j<count($sorteosenticket);$j++){

			//verificar si esta combinacion existe
			foreach ($ticket_completo as $tc){
				$combinacion_creada= $sorteosenticket[$j]."x".$combinacionunica[$i];
				if ($tc == $combinacion_creada){
					//$combinacion_ticket[$combinacionunica[$i]][]= $sorteosenticket[$j];
						
					if (isset($combinacion_ticket[$combinacionunica[$i]])) {
						$combinacion_ticket[$combinacionunica[$i]].= "-".$sorteosenticket[$j];
					}else{
						$combinacion_ticket[$combinacionunica[$i]]= $sorteosenticket[$j];
					}
						
				}
			}
		}
	} 
	//print_r($combinacion_ticket);
	//Creando el Ticket Final 
	
	// Invirtiendo el Arreglo 
	// Ahora los keys sera la cadena de los Id_sorteos [4-5-6-11-12-13-15-16-79-80] => Array
	// y los valores sera un subarray con todos las combinaciones de ['numero x monto'] 
	$ticket_final=array();
	foreach ($combinacion_ticket as $comb_numMonto => $todoslossorteos){
		$ticket_final[$todoslossorteos][]=$comb_numMonto;
	}

	$nombre_sorteo=array();
	$nombre_prezodiacal=array();
	$i=0;
	foreach ($ticket_final as $todoslossorteos => $comb_numMonto){

		//Convertir en array todos los sorteos
		$todoslossorteos2 = preg_split('/-/', $todoslossorteos);

		foreach ($todoslossorteos2 as $ts){
			if (!isset($nombre_sorteo[$ts])) {
				$nombre_sorteo[$ts]=$obj_modelo->GetNombreSorteo($ts);
			}
			$data.="<br>"; //para cada nombre de sorteo aparte
			$data.="-".$nombre_sorteo[$ts];
		}
					

		foreach($comb_numMonto as $cn){
			
			// para separar combNumxMonto-Idzodi por "-"
			$resulto= explode('-', $cn);
			//print_r($resulto);
			$numxMonto=$resulto[0];
			$id_zod=$resulto[1];
			if (!isset($nombre_prezodiacal[$id_zod])) {
				$prenombre_signo[$id_zod]=$obj_modelo->GetPreNombreSigno($id_zod);
			}			
			
			$data.="<br>"; //para cada nombre de sorteo aparte
			$data.=$numxMonto." ".$prenombre_signo[$id_zod];
		}
		$data.="<br>";// para separar bloques  

	}



}



// FOOTER
$data.="<br>";
$data.="------------------------------------";
$data.="<br>";
$data.="BUENA SUERTE...";
$data.="<br>";
$data.="NUMEROS JUGADOS: ".$numero_jugadas;
$data.="<br>";
$data.="TOTAL: ".$total_ticket;
$data.="<br>";
$data.="Caduca en ".$tiempo_vigencia_ticket." dias el Premio";


// Obtenemos los datos de la taquilla
$ida_taquilla= $obj_modelo->GetIdTaquillabyNumero($id_taquilla);

//$lineas_saltar_despues= $obj_modelo->lineas_saltar_despues($ida_taquilla);


//Determinar si va a imprimir incompletos y Agotados
$info_impresora= $obj_modelo->GetDatosImpresora($ida_taquilla);

$lineas_saltar_despues=$info_impresora["lineas_saltar_despues"];
$ver_numeros_incompletos=$info_impresora["ver_numeros_incompletos"];
$ver_numeros_agotados=$info_impresora["ver_numeros_agotados"];




$id_bandera=0;

//INCOMPLETOS Y AGOTADOS

$sorteosenticket=array();
$combinacionunica=array();
$ticket_completo=array();


$id_bandera=0;

if( $result2= $obj_modelo->GetNumerosIncompletosTransaccionalNoZodiacal($id_taquilla) ){
			
	while($row= $obj_conexion->GetArrayInfo($result2)){
		//print_r($row);
		//exit();

			if ($row['incompleto'] != '0' && $id_bandera == '0'){
				// validacion para mostrar o no incompletos
				if ($ver_numeros_incompletos == '1') {
					$data.="<br><br><br>";
					$data.="INCOMPLETOS";
				}
				$id_bandera=$row['incompleto'];
			}

		
		$combinacion=$row['numero']." x ".$row['monto_faltante']*(-1);	
			
		//Guardar todos los sorteos pero una sola vez
		if(!in_array($row['id_sorteo'], $sorteosenticket) ) {
			$sorteosenticket[]=$row['id_sorteo'];
		}		

		//Guardar todos las combinaciones Unicas de Numero y Monto 14-1.00
		if(!in_array($combinacion, $combinacionunica) ) {
			$combinacionunica[]=$combinacion;
		}
		$ticket_completo[]=$row['id_sorteo']."x".$row['numero']." x ".$row['monto_faltante']*(-1);
	
	}

	// Creamos un array General $combinacion_ticket
	// donde el key es la combinacion de ['numero x monto']
	// y donde los valores es una cadena con los id_sorteo  [123 x 1.00] => 26-27-41-42
	$combinacion_ticket=array();
	$i=0;
	for($i;$i<count($combinacionunica);$i++){
		$j=0;
		for($j;$j<count($sorteosenticket);$j++){
	
			//verificar si esta combinacion existe
			foreach ($ticket_completo as $tc){
				$combinacion_creada= $sorteosenticket[$j]."x".$combinacionunica[$i];
				if ($tc == $combinacion_creada){
	
					if (isset($combinacion_ticket[$combinacionunica[$i]])) {
						$combinacion_ticket[$combinacionunica[$i]].= "-".$sorteosenticket[$j];
					}else{
						$combinacion_ticket[$combinacionunica[$i]]= $sorteosenticket[$j];
					}
						
				}
			}
		}
	}
	
	//Creando el Ticket Final
	
	// Invirtiendo el Arreglo
	// Ahora los keys sera la cadena de los Id_sorteos [4-5-6-11-12-13-15-16-79-80] => Array
	// y los valores sera un subarray con todos las combinaciones de ['numero x monto']
	$ticket_final=array();
	foreach ($combinacion_ticket as $comb_numMonto => $todoslossorteos){
		$ticket_final[$todoslossorteos][]=$comb_numMonto;
	}
	
	$nombre_sorteo=array();
	$i=0;
	foreach ($ticket_final as $todoslossorteos => $comb_numMonto){
	
		//Convertir en array todos los sorteos
		$todoslossorteos2 = preg_split('/-/', $todoslossorteos);
	
		foreach ($todoslossorteos2 as $ts){
			if (!isset($nombre_sorteo[$ts])) {
				$nombre_sorteo[$ts]=$obj_modelo->GetNombreSorteo($ts);
			}
			$data.="<br>"; //para cada nombre de sorteo aparte
			$data.="-".$nombre_sorteo[$ts];
		}
	
		foreach($comb_numMonto as $cn){
			$data.="<br>"; //para cada nombre de sorteo aparte
			$data.=$cn;
		}
		$data.="<br>";// para separar bloques
	
	}

}

$sorteosenticket=array();
$combinacionunica=array();
$ticket_completo=array();
//INCOMPLETOS Y AGOTADOS

if( $result2= $obj_modelo->GetNumerosIncompletosTransaccionalZodiacal($id_taquilla) ){

	while($row= $obj_conexion->GetArrayInfo($result2)){

		if ($row['incompleto'] != '0' && $id_bandera == '0'){
			// validacion para mostrar o no incompletos
			if ($ver_numeros_incompletos == '1') {
				$data.="<br><br><br>";
				$data.="INCOMPLETOS";
			}
			$id_bandera=$row['incompleto'];
		}



		$combinacion=$row['numero']." x ".$row['monto_faltante']*(-1)."-".$row['id_zodiacal'];
			
		//Guardar todos los sorteos pero una sola vez
		if(!in_array($row['id_sorteo'], $sorteosenticket) ) {
			$sorteosenticket[]=$row['id_sorteo'];
		}

		//Guardar todos las combinaciones Unicas de Numero,Monto y IdZodiacal 14x1.00x9
		if(!in_array($combinacion, $combinacionunica) ) {
			$combinacionunica[]=$combinacion;
		}
		$ticket_completo[]=$row['id_sorteo']."x".$row['numero']." x ".$row['monto_faltante']*(-1)."-".$row['id_zodiacal'];

	}
		
	// Creamos un array General $combinacion_ticket
	// donde el key es la combinacion de ['numero x monto']
	// y donde los valores es una cadena con los id_sorteo  [123 x 1.00] => 26-27-41-42
	$combinacion_ticket=array();
	$i=0;
	for($i;$i<count($combinacionunica);$i++){
		$j=0;
		for($j;$j<count($sorteosenticket);$j++){
	
			//verificar si esta combinacion existe
			foreach ($ticket_completo as $tc){
				$combinacion_creada= $sorteosenticket[$j]."x".$combinacionunica[$i];
				if ($tc == $combinacion_creada){
					//$combinacion_ticket[$combinacionunica[$i]][]= $sorteosenticket[$j];
	
					if (isset($combinacion_ticket[$combinacionunica[$i]])) {
						$combinacion_ticket[$combinacionunica[$i]].= "-".$sorteosenticket[$j];
					}else{
						$combinacion_ticket[$combinacionunica[$i]]= $sorteosenticket[$j];
					}
	
				}
			}
		}
	}
	//print_r($combinacion_ticket);
	//Creando el Ticket Final
	
	// Invirtiendo el Arreglo
	// Ahora los keys sera la cadena de los Id_sorteos [4-5-6-11-12-13-15-16-79-80] => Array
	// y los valores sera un subarray con todos las combinaciones de ['numero x monto']
	$ticket_final=array();
	foreach ($combinacion_ticket as $comb_numMonto => $todoslossorteos){
		$ticket_final[$todoslossorteos][]=$comb_numMonto;
	}
	
	$nombre_sorteo=array();
	$nombre_prezodiacal=array();
	$i=0;
	foreach ($ticket_final as $todoslossorteos => $comb_numMonto){
	
		//Convertir en array todos los sorteos
		$todoslossorteos2 = preg_split('/-/', $todoslossorteos);
	
		foreach ($todoslossorteos2 as $ts){
			if (!isset($nombre_sorteo[$ts])) {
				$nombre_sorteo[$ts]=$obj_modelo->GetNombreSorteo($ts);
			}
			$data.="<br>"; //para cada nombre de sorteo aparte
			$data.="-".$nombre_sorteo[$ts];
		}
			
	
		foreach($comb_numMonto as $cn){
				
			// para separar combNumxMonto-Idzodi por "-"
			$resulto= explode('-', $cn);
			//print_r($resulto);
			$numxMonto=$resulto[0];
			$id_zod=$resulto[1];
			if (!isset($nombre_prezodiacal[$id_zod])) {
				$prenombre_signo[$id_zod]=$obj_modelo->GetPreNombreSigno($id_zod);
			}
				
			$data.="<br>"; //para cada nombre de sorteo aparte
			$data.=$numxMonto." ".$prenombre_signo[$id_zod];
		}
		$data.="<br>";// para separar bloques
	
	}

}






//Saltos de linea para hacer FEED
for($i=1;$i<=$lineas_saltar_despues;$i++){
	//$data1.="\\x1B\\x0A";
	//$data1.="\\n";
	$data.=".<br>";
}



// Despues de guardado en detalle_ticket, borramos el registro de ticket transaccional...
$obj_modelo->EliminarTicketTransaccionalByTaquilla($id_taquilla);
echo $data;


?>

<script type="text/javascript"> 
window.print();
</script>
<script language='javascript'>setTimeout('self.close();',5000)</script>
</body>
</html>