<html>
   <head><title>Impresion de Reporte Tickets Ganadores</title>
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
require('.'.$obj_config->GetVar('ruta_modelo').'RTickets_ganadores.php');

$obj_modelo= new RTickets_ganadores($obj_conexion);

session_start();

$string=date('Y-m-d H:i:s');
$year = substr($string,0,4);
$month = substr($string,5,2);
$day = substr($string,8,2);
$hour = substr($string,11,2);
$minute = substr($string,14,2);
$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");
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


$id_taquilla= $obj_modelo->GetIdTaquilla();


$fecha= $obj_generico->CleanText($_GET['fecha']);

         $data="<br><br>";
         if( $result= $obj_modelo->GetTicketsGanadores($fecha)){
         	if ($obj_conexion->GetNumberRows($result)>0 ){
         
         		// ENCABEZADO DEL TICKET
         		 
         
         		$data.=" <table width='200' border='1' ><tr><td colspan='4' align='center'><font face='arial' size='2' >";
         		$data.=$dias[date('w')]." ".$fecha_hora."&nbsp;&nbsp;".$hora;
         		$data.="<br>";         		
         		$data.=" SISTEMA LOTTOMAX";
         		$data.=" </font></td> </tr>";
         		$data.="<tr> <td colspan='4' align='center'><font face='arial' size='2' >";
         		$data.="</font></td> </tr>";
         		$data.="<tr><td colspan='4' align='center'><font face='arial' size='2' >";
         		$data.="Tickets Premiados Detallado<br>";
         		$data.="Fecha: ".$fecha;
         		$data.="</font></td> </tr>";
         		 
         		$total_premios=0;
         		$total_pagado=0;
         		 
         		 
         		 
         
         		while($row= $obj_conexion->GetArrayInfo($result)){
         			//	$data.="SISTEMA LOTTOMAX";
         			//$total_premio=$row['total_premiado'];
         
         			$data.="<tr><td colspan='4' width='40' align='left'><font face='arial' size='2' >";
         			//$data.="Detalle Apuesta Ganadora";
         			$data.="Ticket: ".$row['id_ticket'];
         			$data.="<br>Fecha: ".$row['fecha_hora'];
         			$data.="<br>Monto del Ticket Bs:".$row['total_ticket'];
         			$data.="<br>Taquilla: ".$row['taquilla'];
         			if($row['pagado']=='0'){$pagado='NO';}else{$pagado='SI';}
         			$data.="<br>Pagado: ".$pagado;
         			$data.="<br>Total Premio Ticket Bs: ".$row['total_premiado'];
         
         
         			$resulta= $obj_modelo->GetDetalleTicketPremiados($row['id_ticket']);
         
         			while($rowa= $obj_conexion->GetArrayInfo($resulta)){
         				$data.="<br><br>Numero: ".$rowa['numero'];
         				$data.="<br>Monto Jugado Bs : ".$rowa['monto'];
         				$data.="<br>Sorteo: ".$obj_modelo->GetNombreSorteo($rowa['id_sorteo']);
         				if($rowa['id_zodiacal'] != '0'){
         					$data.="<br>Zodiacal: ".$obj_modelo->GetPreNombreSigno($rowa['id_zodiacal']);
         				}
         				$total_premioa=$rowa['total_premiado'];
         				if($total_premioa < 400){
         					$data.="<br><font face='times' size='3' ><strong>Premio Bs : ".$total_premioa."</strong></font>";
         				}else{
         					$data.="<br><font face='times' size='3' color='blue' ><strong>Premio Bs : ".$total_premioa."</strong></font>";
         				}
         
         			}
         			$data."</font></td></tr>";
         			//$data.="<tr><td colspan='4' width='40' align='center'><font face='arial' size='2' ></font>.</td></tr>";
         			$data.="<tr height='10'><td colspan='4' align='center'>=====================</td></tr>";
         			$total_premios=$total_premios+$row['total_premiado'];
         		}
         		//$data.="<tr><td colspan='4' width='40' align='center'><font face='arial' size='2' > Total Premios: ".round($total_premios, 2)."</font> </td></tr>";
         		 
         		//echo $data;
         	}
         	 
         }         
         
         
         
         
         
// Obtenemos los datos de la taquilla
$ida_taquilla= $obj_modelo->GetIdTaquillabyNumero($id_taquilla);
//Determinar si va a imprimir incompletos y Agotados
$info_impresora= $obj_modelo->GetDatosImpresora($ida_taquilla);

$lineas_saltar_despues=$info_impresora["lineas_saltar_despues"];

//Saltos de linea para hacer FEED
for($i=1;$i<=$lineas_saltar_despues;$i++){
	//$data1.="\\x1B\\x0A";
	//$data1.="\\n";
	$data.=".<br>";
}

$data.="<tr><td colspan='2' align='left'><font face='arial' size='2' ></font> </td> <td align='center' width='73' ><font face='arial' size='2' > </font></td><td align='center' width='49' ><font face='arial' size='2' ></font></td></tr>";

$data.="<tr><td colspan='2' align='left'> <font face='times' size='2' > Total Premios: </font></td> ";
$data.="<td colspan='2' align='left'> <font face='times' size='2' > ".$total_premios." Bs.</font></td></tr> ";

$data.="<tr><td colspan='2' align='left'> <font face='times' size='2' > Total Pagados: </font></td> ";
$data.="<td colspan='2' align='left'> <font face='times' size='2' > ".$total_pagado." Bs.</font></td></tr> ";

$data.="<tr><td colspan='2' align='left'> <font face='times' size='2' > Total No Pagados: </font></td> ";
$data.="<td colspan='2' align='left'> <font face='times' size='2' > ".($total_premios-$total_pagado)." Bs. </font></td></tr> ";
$data.="</table>";


//$data.="</FONT>";
//echo $data1;
/*
echo "<script type='text/javascript'>";
echo "print('".$data1."')";
echo "</script>";

echo "<script language='javascript'>setTimeout('self.close();',5000)</script>"
*/

echo $data;
?>


 <script type="text/javascript"> 
window.print();
</script>
<script language='javascript'>setTimeout('self.close();',5000)</script>

   </body>
   
</html>  