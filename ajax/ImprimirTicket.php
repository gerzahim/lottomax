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
//setLocale(LC_ALL, 'America/Caracas');

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
$id_ticket1= substr($id_ticket, 0 , 3);
$id_ticket2= substr($id_ticket, 3 , 3);
$id_ticket3= substr($id_ticket, 6);
$formato_id_ticket=$id_ticket1."-".$id_ticket2."-".$id_ticket3;

$serial=$info_ticket["serial"]; 
$serial1= substr($serial, 0 , 3);
$serial2= substr($serial, 3 , 3);
$serial3= substr($serial, 6);
$formato_serial=$serial1."-".$serial2."-".$serial3;

$string=$info_ticket["fecha_hora"]; 
$year = substr($string,0,4);
$month = substr($string,5,2);
$day = substr($string,8,2);
$hour = substr($string,11,2);
$minute = substr($string,14,2);
$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");
//$fecha_hora=$day."-".$month."-".$year." ".$hour.":".$minute;
$fecha_hora=$day."-".$month."-".$year;

$result=$obj_modelo->GetPreZodiacal();

while($row=$obj_conexion->GetArrayInfo($result))
{
	$pre_zod[]=$row['pre_zodiacal'];
	
}
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
$data=" <table width='100%' cellpadding='0' cellspacing='0' border='0' >";
$data.="<tr><td colspan='2' align='center'><font face='Tahoma' size='3' >";
$data.="SISTEMA LOTTOMAX";
$data.=" </font></td> </tr>";
$data.="<tr> <td colspan='2' align='center'><font face='Tahoma' size='3' >";
$data.="Agencia: ".$nombre_agencia;
$data.="</font></td> </tr>";
$data.="<tr><td colspan='2' align='center'><font face='Tahoma' size='3' >";
$data.="Ticket: ".$formato_id_ticket;
$data.="</font></td> </tr>";
$data.="<tr><td colspan='2' align='center'><font face='Tahoma' size='3' >";
$data.="Serial: ".$formato_serial;
$data.="</font></td> </tr>";
$data.="<tr><td colspan='2' align='center'><font face='Tahoma' size='2' >";
$data.=$dias[date('w')]." ".$fecha_hora."&nbsp;&nbsp;".$hora;
$data.="</font></td> </tr>";
$data.="<tr><td colspan='2' align='center'><font face='Tahoma' size='3' >";
$data.="Taquilla: ".$id_taquilla;
$data.="</font></td> </tr>";
$data.="<tr><td colspan='2' align='center'><font face='Tahoma' size='3' >";
$data.="Vendedor: ".$nombre;
$data.="</font></td> </tr>";

$data.="<tr height='10'><td colspan='2' align='center'></td></tr>";

$data1="";

//setea impreso=1 en ticket para saber que ya esta impreso
$obj_modelo->SeteaImpresionenTicket($id_ticket);

//Determinando Numeros No Zodiacales Primero
$sorteosenticket=array();
$combinacionunica=array();
$ticket_completo=array();

//Determinando  Primero
if( $result= $obj_modelo->GetDetalleTicketByIdticket($id_ticket) ){
	//Numeros jugados NoZodiacal
	$numero_jugadas=$obj_conexion->GetNumberRows($result);
	while($row= $obj_conexion->GetArrayInfo($result)){
		if($row['monto']!=0){
			if($row['id_zodiacal']==0){
				$combinacion=$row['numero']." x ".$row['monto'];
				$ticket_completo[]=$row['id_sorteo']."x".$row['numero']." x ".$row['monto'];
			}
			else{
				$combinacion=$row['numero']." x ".$row['monto']."-".$row['id_zodiacal'];
				$ticket_completo[]=$row['id_sorteo']."x".$row['numero']." x ".$row['monto']."-".$row['id_zodiacal'];
			}
			//Guardar todos los sorteos pero una sola vez
			//$sorteoszodiac[]=$row['id_zodiacal'];
			if(!in_array($row['id_sorteo'], $sorteosenticket)){
				$sorteosenticket[]=$row['id_sorteo'];
			}
				
			//Guardar todos las combinaciones Unicas de Numero y Monto 14-1.00
			if(!in_array($combinacion, $combinacionunica) )
				$combinacionunica[]=$combinacion;
		}
	}// fin de while
	// Creamos un array General $combinacion_ticket
	// donde el key es la combinacion de ['numero x monto']
	// y donde los valores es una cadena con los id_sorteo  [123 x 1.00] => 26-27-41-42
	$combinacion_ticket=array();
	for($i=0;$i<count($combinacionunica);$i++){
		for($j=0;$j<count($sorteosenticket);$j++){
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
		$g=0;
		foreach ($todoslossorteos2 as $ts){
			$sw=0;
			if (!isset($nombre_sorteo[$ts])){
				$nombre_sorteo[$ts]=$obj_modelo->GetNombreSorteo($ts);
			}
			if($g==2 OR $g==0){
				if($obj_generico->SizeText($obj_generico->ToTitle($nombre_sorteo[$ts])) <= 18){
					$data.="<tr><td align='left' ><font face='Times' size='2'  >".$obj_generico->ToTitle($nombre_sorteo[$ts])."</td>";
					$sw=1;
				}					
				else
					$data.="<tr><td colspan='2' align='left'><font face='Times' size='2'>".$obj_generico->ToTitle($nombre_sorteo[$ts])."</td></tr>";
				$g=0;
			}					
			else{
				if($obj_generico->SizeText($obj_generico->ToTitle($nombre_sorteo[$ts])) <= 18)
					$data.="<td align='left' ><font face='Times' size='2' >".$obj_generico->ToTitle($nombre_sorteo[$ts])."</td></tr>";
				else
					$data.="<td></td><tr><td colspan='2' align='left' ><font face='Times' size='2' >".$obj_generico->ToTitle($nombre_sorteo[$ts])."</td></tr>";
			}					
			$g++;
		}
		if($sw==1)
		$data.="<td align='left'></td></tr>";
		$data.="<tr  height='10'><td colspan='2'><table width='100%' cellpadding='1' cellspacing='4' border='0' >";
		$g=0;
		foreach($comb_numMonto as $cn){
			$array_sorteo=preg_split('/-/',$cn);
			if(count($array_sorteo)>1)
				$cn=$array_sorteo[0]." - ".$pre_zod[$array_sorteo[1]];
			if($g==0){
				$data.="<tr><td align='left'><font face='Tahoma' size='2' > ".$cn."</td>";
				$g++;
			}
			elseif($g==1){
				$data.="<td align='center'><font face='Tahoma' size='2' >".$cn."</td>";
				$g++;
			}
			elseif($g==2){
				$data.="<td align='left'><font face='Tahoma' size='2' >".$cn."</td></tr>";
				$g=0;
			}	
		}
		if($g==1)
		$data.="<td colspan='2'align='left'></td></tr>";
		elseif($g==2)
		$data.="<td align='left'></td></tr>";
		$data.="</td></tr></table>";
	}
}
// FOOTER
$data.="<tr  height='10'><td colspan='2'></td></tr>";
$data.="<tr><td colspan='2' align='center'><font face='Tahoma' size='3' >";
$data.="BUENA SUERTE";
$data.=" </font></td> </tr>";
$data.="<tr> <td colspan='2' align='center'><font face='Tahoma' size='3' >";
$data.="Numeros Jugados: ".$numero_jugadas;
$data.=" </font></td> </tr>";
$data.="<tr> <td colspan='2' align='center'><font face='Tahoma' size='3' >";
$data.="TOTAL: ".$total_ticket;
$data.=" </font></td> </tr>";
$data.="<tr> <td colspan='2' align='center'><font face='Tahoma' size='3' >";
$data.="Caduca en: ".$tiempo_vigencia_ticket." dias el Premio";
$data.=" </font></td> </tr>";
$data.="</table>";

// Obtenemos los datos de la taquilla
$ida_taquilla= $obj_modelo->GetIdTaquillabyNumero($id_taquilla);

//$lineas_saltar_despues= $obj_modelo->lineas_saltar_despues($ida_taquilla);


//Determinar si va a imprimir incompletos y Agotados
$info_impresora= $obj_modelo->GetDatosImpresora($ida_taquilla);

$lineas_saltar_despues=$info_impresora["lineas_saltar_despues"];
$ver_numeros_incompletos=$info_impresora["ver_numeros_incompletos"];
$ver_numeros_agotados=$info_impresora["ver_numeros_agotados"];

//INCOMPLETOS Y AGOTADOS

$sorteosenticket=array();
$combinacionunica=array();
$ticket_completo=array();
$result2= $obj_modelo->GetNumerosIncompletosTransaccional($id_taquilla);
$total_incompletos=$obj_conexion->GetNumberRows($result2);
if($total_incompletos>0){
	$data.=" <table width='260' cellpadding='0' cellspacing='0' border='0' >";
	$data.="<tr height='10'><td colspan='2' align='center'></td> </tr>";
	$data.="<tr><td colspan='2' align='center'><font face='Tahoma' size='3' >";
	$data.="Incompletos";
	$data.=" </font></td> </tr>";
	while($row= $obj_conexion->GetArrayInfo($result2)){
		//if($row['incompleto']==1){
		//if($row['incompleto']==1)
	//	$monto=($row['monto_faltante']*(-1));
		//else
		$monto=($row['monto_faltante']);

		if($row['id_zodiacal']==0){
			$combinacion=$row['numero']." x ".$monto;	
			$ticket_completo[]=$row['id_sorteo']."x".$row['numero']." x ".$monto;	
		}
		else{
			$combinacion=$row['numero']." x ".$monto."-".$row['id_zodiacal'];
			$ticket_completo[]=$row['id_sorteo']."x".$row['numero']." x ".$monto."-".$row['id_zodiacal'];
		}
		//Guardar todos los sorteos pero una sola vez
		//$sorteoszodiac[]=$row['id_zodiacal'];
		if(!in_array($row['id_sorteo'], $sorteosenticket)){
			$sorteosenticket[]=$row['id_sorteo'];
		}
		//Guardar todos las combinaciones Unicas de Numero y Monto 14-1.00
		if(!in_array($combinacion, $combinacionunica) )
			$combinacionunica[]=$combinacion;
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
		$g=0;
		foreach ($todoslossorteos2 as $ts){
			$sw=0;
			if (!isset($nombre_sorteo[$ts])){
				$nombre_sorteo[$ts]=$obj_modelo->GetNombreSorteo($ts);
			}
			if($g==2 OR $g==0){
				if($obj_generico->SizeText($obj_generico->ToTitle($nombre_sorteo[$ts])) <= 18){
					$data.="<tr><td align='left' ><font face='Tahoma' size='3'  >".$obj_generico->ToTitle($nombre_sorteo[$ts])."</td>";
					$sw=1;
				}					
				else
					$data.="<tr><td colspan='2' align='left'><font face='Tahoma' size='3'>".$obj_generico->ToTitle($nombre_sorteo[$ts])."</td></tr>";
				$g=0;
			}					
			else{
				if($obj_generico->SizeText($obj_generico->ToTitle($nombre_sorteo[$ts])) <= 18)
					$data.="<td align='left' ><font face='Tahoma' size='3' >".$obj_generico->ToTitle($nombre_sorteo[$ts])."</td></tr>";
				else
					$data.="<td></td><tr><td colspan='2' align='left' ><font face='Tahoma' size='3' >".$obj_generico->ToTitle($nombre_sorteo[$ts])."</td></tr>";
			}					
			$g++;
		}
		if($sw==1)
		$data.="<td align='left'></td></tr>";
		$data.="<tr  height='10'><td colspan='2'><table width='247' cellpadding='1' cellspacing='4' border='0' >";
		$g=0;
		foreach($comb_numMonto as $cn){
			$array_sorteo=preg_split('/-/',$cn);
			if(count($array_sorteo)>=2)
				$cn=$array_sorteo[0]." - ".$pre_zod[$array_sorteo[1]];
			if($g==0){
				$data.="<tr><td align='left'><font face='Tahoma' size='2' > ".$cn."</td>";
				$g++;
			}
			elseif($g==1){
				$data.="<td align='center'><font face='Tahoma' size='2' >".$cn."</td>";
				$g++;
			}
			elseif($g==2){
				$data.="<td align='left'><font face='Tahoma' size='2' >".$cn."</td></tr>";
				$g=0;
			}	
		}
		if($g==1)
		$data.="<td colspan='2'align='left'></td></tr>";
		elseif($g==2)
		$data.="<td align='left'></td></tr>";
		$data.="</td></tr></table>";
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