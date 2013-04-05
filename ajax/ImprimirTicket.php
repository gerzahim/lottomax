<html>
   <head><title>jZebra Demo</title>
   <script language="javascript" type="text/javascript" src="../jscripts/jquery-latest.js"></script>
   <script language="javascript" type="text/javascript" src="../jscripts/PluginPrint.js"></script>        
   <script language="javascript" type="text/javascript" src="../jscripts/DefaultPrinter.js"></script>    
   </head>
   <body id="content" bgcolor="#FFF380">
   
   <applet name="jzebra" code="jzebra.PrintApplet.class" archive="../jscripts/jzebra.jar" width="50px" height="50px">
	  <param name="printer" value="zebra">
   </applet>
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

// Modelo asignado
require('.'.$obj_config->GetVar('ruta_modelo').'Ventas.php');
$obj_modelo= new Ventas($obj_conexion);

session_start();

$id_taquilla=2;


/************* CABLEADO **********************/
//id_ticket debe venir de una variable de sesion

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
$fecha_hora=$day."-".$month."-".$year." ".$hour.":".$minute;

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
$data.="TAQUILLA: ".$id_taquilla;
$data.="<br>";
$data.="VENDEDOR: ".$nombre;
$data.="<br>";
$data.="________________________________";




$data1="SISTEMA LOTTOMAX ";
$data1.="\\n";
//$data1="\x0A";

$data1.="AGENCIA: ".$nombre_agencia;
$data1.="\\n";
$data1.="TICKET: ".$id_ticket;
$data1.="\\n";
$data1.="SERIAL: ".$serial;
$data1.="\\n";
$data1.="FECHA: ".$fecha_hora;
$data1.="\\n";
$data1.="TAQUILLA: ".$id_taquilla;
$data1.="\\n";
$data1.="VENDEDOR: ".$nombre;
$data1.="\\n";
$data1.="________________________________";



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
			$data.=$nombre_sorteo;
			
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
			$data.=$nombre_sorteo;
			
			$data1.="\\n";
			$data1.=$nombre_sorteo;						
		}
				
		//comprobando si es zodiacal o no
		if($row['id_zodiacal'] != 0){
			if($contador % 2){
				$nombre_signo=$obj_modelo->GetPreNombreSigno($row['id_zodiacal']);
				$data.=$row['numero']." ".$nombre_signo." x ".$row['monto']."&nbsp;&nbsp;&nbsp;";
				
				$data1.=$row['numero']." ".$nombre_signo." x ".$row['monto']."  ";				
			}else{
				$data.="<br>";	
				$nombre_signo=$obj_modelo->GetPreNombreSigno($row['id_zodiacal']);
				$data.=$row['numero']." ".$nombre_signo." x ".$row['monto']."&nbsp;&nbsp;&nbsp;";
				
				$data1.="\\n";
				$data1.=$row['numero']." ".$nombre_signo." x ".$row['monto']."  ";					
			}
			
		}
		$contador++;
		

	}	
	
}



// FOOTER
$data.="<br>";
$data.="________________________________";
$data.="<br>";
$data.="NUMEROS JUGADOS: ".$numero_jugadas;
$data.="<br>";
$data.="TOTAL: ".$total_ticket;
$data.="<br>";
$data.="Caduca en ".$tiempo_anulacion_ticket." dias";

$data1.="\\n";
$data1.="________________________________";
$data1.="\\n";
$data1.="NUMEROS JUGADOS: ".$numero_jugadas;
$data1.="\\n";
$data1.="TOTAL: ".$total_ticket;
$data1.="\\n";
$data1.="Caduca en ".$tiempo_anulacion_ticket." dias";



//INCOMPLETOS Y AGOTADOS
if( $result2= $obj_modelo->GetNumerosIncompletobyIdticket($id_ticket) ){
	$id_bandera_actual=0;	
	while($row= $obj_conexion->GetArrayInfo($result2)){
		$id_bandera=$row['incompleto'];

		if($id_bandera != $id_bandera_actual){
			$id_bandera_actual=$id_bandera;
			if ($id_bandera_actual == '1'){
				$data.="<br><br><br>";
				$data.="INCOMPLETOS";

				$data1.="\n\n\n";
				$data1.="INCOMPLETOS";
						
			}
			if ($id_bandera_actual == '2'){
				$data.="<br><br>";
				$data.="AGOTADOS";

				$data1.="\n\n";
				$data1.="AGOTADOS";				
			}				
		}

		$nombre_sorteo=$obj_modelo->GetNombreSorteo($row['id_sorteo']);
		$data.="<br>"; //para cada nombre de sorteo aparte			
		$data.=$nombre_sorteo;

		$data1.="\\n";
		$data1.=$nombre_sorteo;		
		
		//comprobando si es zodiacal o no
		if($row['id_zodiacal'] == 0){
			$data.="<br>";	
			$data.=$row['numero']." FALTA ".$row['monto_restante']."&nbsp;&nbsp;&nbsp;";
			
			$data1.="\\n";
			$data1.=$row['numero']." FALTA ".$row['monto_restante']."\t";
		}else{
			$nombre_signo=$obj_modelo->GetPreNombreSigno($row['id_zodiacal']);
			$data.="<br>";
			$data.=$row['numero']." ".$nombre_signo." FALTA ".$row['monto_restante']."&nbsp;&nbsp;&nbsp;";

			$data1.="\\n";
			$data1.=$row['numero']." FALTA ".$row['monto_restante']."\t";
		}		
			
	
		

	}	
		
}

echo $data1;

echo "<script type='text/javascript'>";
//echo "alert('".$data1."')";
echo "print('".$data1."')";
echo "</script>";


?>

   </body>

   
</html>