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


$id_taquilla= $obj_modelo->GetIdTaquilla();


$fecha= $obj_generico->CleanText($_GET['fecha']);


//echo $fecha_desde, $fecha_hasta;
$data="";
         if( $result= $obj_modelo->GetTicketsGanadores($fecha)){
            if ($obj_conexion->GetNumberRows($result)>0 ){
            	
				// ENCABEZADO DEL TICKET
				//$data.="SISTEMA LOTTOMAX";
				//$data.="<br>";
				//$data.="TICKET GANADOR";
				//$data.="<br>";
 	
            	//Cambio de tamano fuenta a 10 cpi
				$data1="\\x1B\\x50";
				$data1.="SISTEMA LOTTOMAX";
				$data1.="\\n";
				$data1.="TICKET GANADOR";
				$data1.="\\n";										            
              
                while($row= $obj_conexion->GetArrayInfo($result)){
                	$data.="SISTEMA LOTTOMAX";
					$data.="<br>FECHA: ".$obj_date->changeFormatDateI($row['fecha_hora'],1);
					$data.="<br>Id Ticket: ".$row['id_ticket'];
					//$data.="<br>Taquilla: ".$obj_modelo->GetNumeroTaquillabyId($row['taquilla']);

					$data.="<br><br>Detalle Jugada Ganadora: ";
					$resulta= $obj_modelo->GetDetalleTicketPremiados($row['id_ticket']);
					while($rowa= $obj_conexion->GetArrayInfo($resulta)){
						
						$data.="<br><br>Numero: ".$rowa['numero'];
						$data.="<br>Sorteo: ".$obj_generico->ToTitle($rowa['nombre_sorteo']);
						if($rowa['id_zodiacal'] != '0'){
							$data.="<br>Zodiacal: ".$rowa['nombre_zodiacal'];
						}
						$data.="<br>Premiado Bs : ".$rowa['total_premiado'];
					
					}
					$data.="<br><br>TOTAL Bs : ".$row['total_premiado'];
					if($row['pagado'] == '0'){
						$pagado="NO";
					}else{
						$pagado="SI";
					}
					$data.="<br>PAGADO : ".$pagado;
					$data.="<br>";
					$data.="<br>";
					$data.="-----------------------------";
					$data.="<br>";

					
					//$data1.="\\nFECHA: ".$row['fecha'];
					//$data1.="\\nTotal Ventas: ".$row['total_ventas'];					                									
					//$data1.="\\nComision: ".$row['comision'];
					//$data1.="\\nTotal Premios: ".$row['total_premiado'];					                									
					//$data1.="\\nBalance: ".$row['balance'];
					$data1.="\\n";
					$data1.="-----------------------------";
					$data1.="\\n";					
				                        
					
                }
 				
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