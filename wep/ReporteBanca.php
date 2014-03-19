<html>
   <head><title>Cuadre Banca Hoy</title>
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
$dia_ayer=$dia_hoy-1;

//completando con Cero a la Izquierda
$dia_ayer=str_pad($dia_ayer, 2, "0", STR_PAD_LEFT);

if($dia_hoy == '01'){
	if($mes == '01'){
		$mes1='12';
	}else{
		$mes1=$mes-1;
		//completando con Cero a la Izquierda
		$mes1=str_pad($mes1, 2, "0", STR_PAD_LEFT);
	}
	
	if($mes1=='01' || $mes1=='03' || $mes1=='05' || $mes1=='07' || $mes1=='08' || $mes1=='10' || $mes1=='12'){
		$dia_ayer='31';
	}else if($mes1=='04' || $mes1=='06' || $mes1=='09' || $mes1=='11'){
		$dia_ayer='30';
	}else{
		//Es Febrero
		$dia_ayer='28';
	}
	
	
}else{
	$mes1=$mes;
}


$fecha_hoy=$ano."-".$mes."-".$dia_hoy;
$fecha_ayer=$ano."-".$mes1."-".$dia_ayer;

if(isset($_GET ['fechadesde']))
	$fecha_ayer=	$_GET ['fechadesde'];
else
	$fecha_ayer=$ano."-".$mes1."-".$dia_ayer;

if(isset($_GET ['fechahasta']))
	$fecha_hoy=	$_GET ['fechahasta'];
else
	$fecha_hoy=$ano."-".$mes."-".$dia_hoy;

$fecha_desde= $obj_generico->CleanText($fecha_ayer);
$fecha_hasta= $obj_generico->CleanText($fecha_hoy);

/*
$fecha_desde=$obj_date->changeFormatDateII($fecha_desde);
$fecha_hasta=$obj_date->changeFormatDateII($fecha_hasta);

//echo $fecha_desde, $fecha_hasta;
*/

$comision=$obj_modelo->GetComision();
$data="";
$data.="URL ?fechadesde=".$fecha_ayer."&fechahasta=".$fecha_hoy;
$data.="<br><br>";

         if( $result= $obj_modelo->GetBalance($fecha_desde, $fecha_hasta,$comision)){
            if ($obj_conexion->GetNumberRows($result)>0 ){
            	
				// ENCABEZADO DEL TICKET
				$data.="SISTEMA LOTTOMAX";
				$data.="<br>";
				$data.="CUADRE CON BANCA";
				$data.="<br>";
				$data.="Ayer y Hoy";
				$data.="<br>";				
 							            
              
                while($row= $obj_conexion->GetArrayInfo($result)){
                        
					$data.="<br>FECHA: ".$row['fecha'];
					$data.="<br>Total Ventas: ".round($row['total_ventas'], 2);					                									
					$data.="<br>Comision: ".round($row['comision'], 2);
					$data.="<br>Total Premios: ".round($row['total_premiado'], 2);					                									
					$data.="<br>Balance: ".round($row['balance'], 2);
					$data.="<br>";
					$data.="-----------------------------";
					$data.="<br>";

                }
 				
                //echo $data;               
            } 

         }
         
         
         $data.="<br><br>";
         if( $result= $obj_modelo->GetTicketsGanadores($fecha_hoy)){
         	if ($obj_conexion->GetNumberRows($result)>0 ){
         		 
         		// ENCABEZADO DEL TICKET
         
         		 
         		$data.=" <table width='200' border='0' ><tr><td colspan='3' align='center'><font face='arial' size='2' >";
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
         		//$data.="<tr><td width='40' align='center'><font face='arial' size='2' ></font> </td> <td align='center' width='73' ><font face='arial' size='2' > </font></td><td align='center' width='49' ><font face='arial' size='2' ></font></td></tr>";
         
         		$data.="<tr><td width='40' align='center'><font face='arial' size='2' >ID</font> </td> <td align='center' width='73' ><font face='arial' size='2' >Monto Premiado</font></td></tr>";
         
         			
         		while($row= $obj_conexion->GetArrayInfo($result)){
         			//	$data.="SISTEMA LOTTOMAX";
         			$data.="<tr><td width='40' align='left'> <font face='times' size='2' >".$row['id_ticket']."</font></td> ";
         			$data.="<td width='73' align='center'><font face='times' size='2' >".$row['total_premiado']."</font></td> ";
         			         			         			
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