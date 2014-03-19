<html>
   <head><title>Impresion de Reporte Taquilla</title>
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
require('.'.$obj_config->GetVar('ruta_modelo').'RVentas_periodo.php');

$obj_modelo= new RVentas_periodo($obj_conexion);

session_start();

//$id_taquilla=2;
// Obtenemos los datos de la taquilla
$id_taquilla= $_GET['taquilla'];


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





$fecha_desde= $obj_generico->CleanText($_GET['fechadesde']);
$fecha_hasta= $obj_generico->CleanText($_GET['fechahasta']);

$fecha_desde=$obj_date->changeFormatDateII($fecha_desde);
$fecha_hasta=$obj_date->changeFormatDateII($fecha_hasta);

$resulta= $obj_modelo->GetBalancebyTaquillaAnulados($fecha_desde, $fecha_hasta, $id_taquilla);
$total_anulados= array();
$totales_anulados= 0;
$tickets_anulados= array();
$i=0;
while($ruw= $obj_conexion->GetArrayInfo($resulta)){
	
	$fecha_anul= substr($ruw['fecha'],0, 10);
	
	if (!isset($mismo_fecha)) {
		$mismo_fecha = $fecha_anul;
	}
	
	if($mismo_fecha != $fecha_anul){
		$mismo_fecha = $fecha_anul;
	
		$totales_anulados = $ruw['total_ticket'];
		$total_anulados[$ruw['fecha']]= $totales_anulados;
		
	}else{
		$totales_anulados = $totales_anulados +$ruw['total_ticket'];
		$total_anulados[$ruw['fecha']]= $totales_anulados;
	}	
		
	$tickets_anulados[$i]=$ruw;
	$i++;
}

//echo $fecha_desde, $fecha_hasta;
$data="";
         if( $result= $obj_modelo->GetBalancebyTaquilla($fecha_desde, $fecha_hasta, $id_taquilla)){
            if ($obj_conexion->GetNumberRows($result)>0 ){
            	
				// ENCABEZADO DEL TICKET
            	$data.=" <table width='100%' cellpadding='0' cellspacing='0' border='0' >";
            	$data.="<tr><td colspan='2' align='center'><font face='Tahoma' size='2' >";
            	$data.=$dias[date('w')]." ".$fecha_hora."&nbsp;&nbsp;".$hora;
				$data.="<br>";
				$data.="SISTEMA LOTTOMAX";
				$data.="<br>";
                if(!empty ($id_taquilla)){
                	$data.="CUADRE TAQUILLA: $id_taquilla";
                }else{
                	$data.="VENTAS DE TAQUILLAS";
                } 				
                $data.="<br>";
                $data.=" </font></td> </tr>";
                $data.="<tr><td colspan='2' align='center'><font face='Tahoma' size='1' >";

				if($_GET['fechadesde'] != $_GET['fechahasta']){
					$data.="Del ".$_GET['fechadesde']." al ".$_GET['fechahasta'];
				}else{
					$data.="Del ".$_GET['fechadesde'];
				}
				$data.="<br>";
				$data.=" </font></td> </tr>";
				
				$data.="<tr><td colspan='2' align='center'><font face='Tahoma' size='2' >";
				$total_ventas=0;
                while($row= $obj_conexion->GetArrayInfo($result)){

                	$fecha_desde1=$obj_date->changeFormatDateI($row['fecha'], 0);
					$data.="<br>FECHA: ".$fecha_desde1;
					
					$data.="<br>Ventas Bs: ".$row['total_ventas'];
					$total_anul = isset($total_anulados[$row['fecha']]) ? $total_anulados[$row['fecha']] : 'No Anulados' ;
					$data.="<br>Anulados Bs: ".$total_anul;
					//echo $row['fecha'];
					$premiados_pagados= $obj_modelo->GetTicketsPagadosbyFechaPagados($row['fecha'], $id_taquilla);
					if($premiados_pagados == 0){
						$premiados_pagados="No Pagados ";
					}
					$data.="<br>Premios Pagados Bs: ".$premiados_pagados;
					$total_ventas= $total_ventas + $row['total_ventas'];
					$data.="<br>";
					$data.="-----------------------------";
					$data.="<br>";  
                    
                }
                $data.=" </font></td> </tr>";
                $data.="<tr><td colspan='2' align='center'><font face='Tahoma' size='2' >";
                $data.="<br>Total Ventas: ".$total_ventas;
                $data.=" </font></td> </tr>";
                //echo $data;               
            } 

         }

$data.="<br>";        
$data.="<br>"; 
$data.="<tr><td colspan='2' align='center'><font face='Tahoma' size='1' >";
$data.="<br>LISTADO DE TICKETS ANULADOS: ";
$data.=" </font></td> </tr>";
$data.="<tr><td colspan='2' align='center'><font face='Tahoma' size='1' >";
$data.="&nbsp;&nbsp;";
$data.=" </font></td> </tr>";


for($j=0;$j<$i;$j++){
	
	$fecha_anu= substr($tickets_anulados[$j]['fecha_hora_anulacion'],0, 10);
	
	if (!isset($misma_fecha)) {
		$misma_fecha = $fecha_anu;
	}
	
	if($misma_fecha != $fecha_anu){
		$misma_fecha = $fecha_anu;
	
		$data.="<tr><td colspan='2' align='center'>";
		$data.= "-----------------------------";
		$data.=" </font></td> </tr>";
	}else{
		$data.="<tr><td colspan='2' align='center'><font face='Tahoma' size='1' >";
		$data.= ".";
		$data.=" </font></td> </tr>";
	}	
	
	
	$data.="<tr><td colspan='1' align='right'><font face='Tahoma' size='1' >";
	$data.= "Taquilla :";
	$data.= $tickets_anulados[$j]['taquilla'];
	$data.=" </font></td>";
	$data.="<td colspan='1' align='left'><font face='Tahoma' size='1' >";
	$data.= "&nbsp;&nbsp;Monto Bs ";
	$data.= $tickets_anulados[$j]['total_ticket'];	
	$data.=" </font></td></tr>";
	$data.="<tr><td colspan='2' align='center'><font face='Tahoma' size='1' >";
	$data.= "Fecha Hora: ".$tickets_anulados[$j]['fecha_hora_anulacion'];
	$data.=" </font></td> </tr>";
	$data.="<tr><td colspan='2' align='center'><font face='Tahoma' size='1' >";
	$data.= "Ticket: ".$tickets_anulados[$j]['id_ticket'];
	$data.=" </font></td> </tr>";
	

}

$data.="</table>";

// Obtenemos los datos de la taquilla
$ida_taquilla= $obj_modelo->GetIdTaquilla($id_taquilla);
//Determinar si va a imprimir incompletos y Agotados
$info_impresora= $obj_modelo->GetDatosImpresora($ida_taquilla);

$lineas_saltar_despues=$info_impresora["lineas_saltar_despues"];

//Saltos de linea para hacer FEED
for($i=1;$i<=$lineas_saltar_despues;$i++){
	//$data1.="\\x1B\\x0A";
	//$data1.="\\n";
	$data.=".<br>";
}

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