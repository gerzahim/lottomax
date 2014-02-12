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
$tiempo_anulacion_ticket=$info_agencia["tiempo_anulacion_ticket"];



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



//Cambio de tamano fuenta a 10 cpi
$data1="\\x1B\\x50";

$data1.="SISTEMA LOTTOMAX";
//salto de linea
//$data1.="\\x1B\\x0A";
$data1.="\\nAGENCIA: ".$nombre_agencia;
//$data1.="\\x1B\\x0A";
$data1.="\\nTICKET: ".$id_ticket;
//$data1.="\\x1B\\x0A";
$data1.="\\nSERIAL: ".$serial;
//$data1.="\\x1B\\x0A";
$data1.="\\nFECHA: ".$fecha_hora;
//$data1.="\\x1B\\x0A";
$data1.="\\nTAQUILLA: ".$id_taquilla;
//$data1.="\\x1B\\x0A";
$data1.="\\nVENDEDOR: ".$nombre;
//$data1.="\\x1B\\x0A";
$data1.="\\n";
$data1.="------------------------------------";
//Cambio de tamano fuenta a 12 cpi
$data1.="\\x1B\\x4D";

//setea impreso=1 en ticket para saber que ya esta impreso
$obj_modelo->SeteaImpresionenTicket($id_ticket);

if( $result= $obj_modelo->GetDetalleTicketByIdticket($id_ticket) ){
		
	$numero_jugadas=$obj_conexion->GetNumberRows($result);
	
	$id_sorteo_actual=0;
	$contador=0;
	while($row= $obj_conexion->GetArrayInfo($result)){
		
		$id_sorteo=$row['id_sorteo'];
		if( ($id_sorteo != $id_sorteo_actual) && ($row['id_zodiacal'] == 0) ){
			$contador=0;
			$id_sorteo_actual=$row['id_sorteo'];
			$nombre_sorteo=$obj_modelo->GetNombreSorteo($row['id_sorteo']);
			$data.="<br>"; //para cada nombre de sorteo aparte			
			$data.="---".$nombre_sorteo;
			
			//$data1.="\\x1B\\x0A";
			$data1.="\\n";
			$data1.=$nombre_sorteo;
		}
		

			
		//comprobando si es zodiacal o no
		if($row['id_zodiacal'] == 0){
			if($contador % 2){
				$data.=$row['numero']." x ".$row['monto']."&nbsp;&nbsp;&nbsp;";
				
				$data1.=$row['numero']." x ".$row['monto']."  ";
			}else{
				$data.="<br>";	
				$data.=$row['numero']." x ".$row['monto']."&nbsp;&nbsp;&nbsp;";
				
				//$data1.="\\x1B\\x0A";
				$data1.="\\n";
				$data1.=$row['numero']." x ".$row['monto']."  ";
			}
		}
		$contador++;	
		

	}

	
}

if( $result1= $obj_modelo->GetDetalleTicketByIdticket2($id_ticket) ){
	
	$id_sorteo_actual=0;
	$contador=0;
	while($row= $obj_conexion->GetArrayInfo($result1)){
		$id_sorteo=$row['id_sorteo'];
		if( ($id_sorteo != $id_sorteo_actual) && ($row['id_zodiacal'] != 0) ){
			$contador=0;
			$id_sorteo_actual=$row['id_sorteo'];
			$nombre_sorteo=$obj_modelo->GetNombreSorteo($row['id_sorteo']);
			$data.="<br>"; //para cada nombre de sorteo aparte			
			$data.="---".$nombre_sorteo;
			
			//$data1.="\\x1B\\x0A";
			$data1.="\\n";
			$data1.=$nombre_sorteo;						
		}
				
		//comprobando si es zodiacal o no
		if($row['id_zodiacal'] != 0){
			
			if($contador % 2){
				$nombre_signo=$obj_modelo->GetPreNombreSigno($row['id_zodiacal']);
				$data.=$row['numero']." ".$nombre_signo." x".$row['monto']."&nbsp;&nbsp;&nbsp;";
				
				$data1.=$row['numero']." ".$nombre_signo." x".$row['monto']." ";				
			}else{
				$data.="<br>";	
				$nombre_signo=$obj_modelo->GetPreNombreSigno($row['id_zodiacal']);
				$data.=$row['numero']." ".$nombre_signo." x".$row['monto']."&nbsp;&nbsp;&nbsp;";
				
				//$data1.="\\x1B\\x0A";
				$data1.="\\n";
				$data1.=$row['numero']." ".$nombre_signo." x".$row['monto']." ";					
			}
			
			/*
			$data.="<br>";	
			$nombre_signo=$obj_modelo->GetPreNombreSigno($row['id_zodiacal']);
			$data.=$row['numero']." ".$nombre_signo." x ".$row['monto']."&nbsp;&nbsp;&nbsp;";
			
			//$data1.="\\x1B\\x0A";
			$data1.=$row['numero']." ".$nombre_signo." x ".$row['monto']."  ";	
			*/
			
		}
		$contador++;
		

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
$data.="Caduca en ".$tiempo_anulacion_ticket." dias el Premio";

//$data1.="\\x1B\\x0A";
$data1.="\\n";
$data1.="-----------------------------";
//$data1.="\\x1B\\x0A";
$data1.="\\n";
$data1.="NUMEROS JUGADOS: ".$numero_jugadas;
//$data1.="\\x1B\\x0A";
$data1.="\\n";
$data1.="TOTAL: ".$total_ticket;
//$data1.="\\x1B\\x0A";
$data1.="\\n";
$data1.="Caduca en ".$tiempo_anulacion_ticket." dias";

// Obtenemos los datos de la taquilla
$ida_taquilla= $obj_modelo->GetIdTaquillabyNumero($id_taquilla);

//$lineas_saltar_despues= $obj_modelo->lineas_saltar_despues($ida_taquilla);


//Determinar si va a imprimir incompletos y Agotados
$info_impresora= $obj_modelo->GetDatosImpresora($ida_taquilla);

$lineas_saltar_despues=$info_impresora["lineas_saltar_despues"];
$ver_numeros_incompletos=$info_impresora["ver_numeros_incompletos"];
$ver_numeros_agotados=$info_impresora["ver_numeros_agotados"];

//INCOMPLETOS Y AGOTADOS
if( $result2= $obj_modelo->GetNumerosIncompletosTransaccional($id_taquilla) ){
		
	$id_bandera_actual=0;	
	while($row= $obj_conexion->GetArrayInfo($result2)){
		$id_bandera=$row['incompleto'];

		if($id_bandera != $id_bandera_actual){
			$id_bandera_actual=$id_bandera;
			if ($id_bandera_actual == '1'){
				
				// validacion para mostrar o no incompletos
				if ($ver_numeros_incompletos == '1') {
					$data.="<br><br><br>";
					$data.="INCOMPLETOS";
	
					//$data1.="\\x1B\\x0A";
					//$data1.="\\x1B\\x0A";
					//$data1.="\\x1B\\x0A";
					$data1.="\\n";
					$data1.="\\n";
					$data1.="\\n";				
					$data1.="INCOMPLETOS";
				}

						
			}
			if ($id_bandera_actual == '2'){
				// validacion para mostrar o no incompletos
				if ($ver_numeros_agotados == '1') {				
					$data.="<br><br>";
					$data.="AGOTADOS";
	
					//$data1.="\\x1B\\x0A";
					//$data1.="\\x1B\\x0A";
					$data1.="\\n";
					$data1.="\\n";
					$data1.="AGOTADOS";		
				}		
			}				
		}

		$nombre_sorteo=$obj_modelo->GetNombreSorteo($row['id_sorteo']);
		$data.="<br>"; //para cada nombre de sorteo aparte			
		$data.="---".$nombre_sorteo."<br>";
		//$data1.="\\x1B\\x0A";
		$data1.="\\n";
		$data1.=$nombre_sorteo;		
		
		//comprobando si es zodiacal o no
		if($row['id_zodiacal'] == 0){
			//$data.="<br>";	
			$data.=$row['numero']."  FALTA  Bs ".($row['monto_faltante']*(-1))."&nbsp;&nbsp;&nbsp;";
			
			//$data1.="\\x1B\\x0A";
			$data1.="\\n";
			$data1.=$row['numero']."  FALTA  Bs ".($row['monto_faltante']*(-1))."  ";
		}else{
			$nombre_signo=$obj_modelo->GetPreNombreSigno($row['id_zodiacal']);
			//$data.="<br>";
			$data.=$row['numero']." ".$nombre_signo."  FALTA  Bs ".($row['monto_faltante']*(-1))."&nbsp;&nbsp;&nbsp;";

			//$data1.="\\x1B\\x0A";
			$data1.="\\n";
			$data1.=$row['numero']." ".$nombre_signo."  FALTA  Bs ".($row['monto_faltante']*(-1))."  ";
		}		
			
	
		

	}	
		
}
//Valor que debe venir de la base de datos
//Saltos de linea para hacer FEED
/*
$data1.="\\x1B\\x0A";
$data1.="\\x1B\\x0A";
$data1.="\\x1B\\x0A";
$data1.="\\x1B\\x0A";
$data1.="\\x1B\\x0A";
$data1.="\\x1B\\x0A";
$data1.="\\x1B\\x0A";*/





//Saltos de linea para hacer FEED
for($i=1;$i<=$lineas_saltar_despues;$i++){
	//$data1.="\\x1B\\x0A";
	//$data1.="\\n";
	$data.=".<br>";
}


/************* CABLEADO **********************/
//los feed deben venir de la base de datos una variable de parametros
// Despues de guardado en detalle_ticket, borramos el registro de ticket transaccional...
  $obj_modelo->EliminarTicketTransaccionalByTaquilla($id_taquilla);
echo $data;
//echo $data1;
/*

echo "<script type='text/javascript'>";
echo "print('".$data1."')";
echo "</script>";
*/


?>

<script type="text/javascript"> 
window.print();
</script>
<script language='javascript'>setTimeout('self.close();',5000)</script>
   </body>
</html>