<html>
   <head><title>Detalle Tickets Ganadores Hoy</title>
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
require('.'.$obj_config->GetVar('ruta_modelo').'RCuadre_banca.php');

$obj_modelo= new RCuadre_banca($obj_conexion);


$ano=date('Y');
$mes=date('m');
$dia_hoy=date('d');

if(isset($_GET ['fecha']))
	$fecha_hoy=	$_GET ['fecha'];
else
	$fecha_hoy=$ano."-".$mes."-".$dia_hoy;

$data="";
$data.="URL ?fecha=".$fecha_hoy;

         
         
         $data.="<br><br>";
         if( $result= $obj_modelo->GetTicketsGanadores($fecha_hoy)){
         	if ($obj_conexion->GetNumberRows($result)>0 ){
         		 
         		// ENCABEZADO DEL TICKET
         
         		 
         		$data.=" <table width='200' border='1' ><tr><td colspan='3' align='center'><font face='arial' size='2' >";
         		$data.=" SISTEMA LOTTOMAX";
         		$data.=" </font></td> </tr>";
         		$data.="<tr> <td colspan='3' align='center'><font face='arial' size='2' >";
         		$data.="Números Premiados HOY";
         		$data.="</font></td> </tr>";
         		$data.="<tr><td colspan='3' align='center'><font face='arial' size='2' >";
         		$data.="Fecha: ".$fecha_hoy;
         		$data.="</font></td> </tr>";
         
         		$total_premios=0;
         		$total_pagado=0;
         		
         		
         
         			
         		while($row= $obj_conexion->GetArrayInfo($result)){
         			//	$data.="SISTEMA LOTTOMAX";
         			$data.="<tr><td width='40' align='center'><font face='arial' size='2' >ID</font> </td> <td align='center' width='73' ><font face='arial' size='2' >Monto Premiado</font></td></tr>";
         			$data.="<tr><td width='40' align='left'> <font face='times' size='2' >".$row['id_ticket']."</font></td> ";
         			$total_premio=$row['total_premiado'];
         			if($total_premio < 400){
         				$data.="<td width='73' align='center'><font face='times' size='2' ><strong>".$row['total_premiado']."</strong></font></td> ";
         			}else{
         				$data.="<td width='73' align='center'><font face='times' size='4' color='red' ><strong>".$row['total_premiado']."</strong></font></td> ";
         			}
         			
         			
         			$data.="<tr><td colspan='2' width='40' align='left'><font face='arial' size='2' >";
         			$data.="Detalle Apuesta Ganadora";
         			$data.="<br>Ticket: ".$row['id_ticket'];
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
         			$data.="<tr><td colspan='2' width='40' align='center'><font face='arial' size='2' ></font>.</td></tr>";
         			$data.="<tr><td colspan='2' width='40' align='center'><font face='arial' size='2' ></font>.</td></tr>";
         			$total_premios=$total_premios+$row['total_premiado'];
         		}
         		$data.="<tr><td colspan='2' width='40' align='center'><font face='arial' size='2' > Total Premios: ".round($total_premios, 2)."</font> </td></tr>";
         		
         		//echo $data;
         	}
         
         }       

echo $data;


?>

   </body>
</html>