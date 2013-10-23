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
require('.'.$obj_config->GetVar('ruta_modelo').'RCuadre_banca.php');

$obj_modelo= new RCuadre_banca($obj_conexion);

session_start();

//$id_taquilla=2;
// Obtenemos los datos de la taquilla
$id_taquilla= $obj_modelo->GetIdTaquilla();


$fecha_desde= $obj_generico->CleanText($_GET['fechadesde']);
$fecha_hasta= $obj_generico->CleanText($_GET['fechahasta']);

echo $fecha_desde, $fecha_hasta;

         if( $result= $obj_modelo->GetBalance($fecha_desde, $fecha_hasta)){
            if ($obj_conexion->GetNumberRows($result)>0 ){
				// ENCABEZADO DEL TICKET
				$data="SISTEMA LOTTOMAX";
				$data.="<br>";

 	
            	//Cambio de tamano fuenta a 10 cpi
				$data1="\\x1B\\x50";
				$data1.="SISTEMA LOTTOMAX";
				$data1.="\\n";				            
              
                while($row= $obj_conexion->GetArrayInfo($result)){
                        
					$data.="<br>FECHA: ".$row['fecha'];
					$data.="<br>Total Ventas: ".$row['total_ventas'];					                									
					$data.="<br>Comision: ".$row['comision'];
					$data.="<br>Total Premios: ".$row['total_premiado'];					                									
					$data.="<br>Balance: ".$row['balance'];
					$data.="<br>";
					$data.="-----------------------------";
					$data.="<br>";

					
					$data1.="\\nFECHA: ".$row['fecha'];
					$data1.="\\nTotal Ventas: ".$row['total_ventas'];					                									
					$data1.="\\nComision: ".$row['comision'];
					$data1.="\\nTotal Premios: ".$row['total_premiado'];					                									
					$data1.="\\nBalance: ".$row['balance'];
					$data1.="\\n";
					$data1.="-----------------------------";
					$data1.="\\n";					
				                        
                    
                }
 				
                echo $data;               
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
	$data1.="\\n";
}

//echo $data1;

echo "<script type='text/javascript'>";
echo "print('".$data1."')";
echo "</script>";

echo "<script language='javascript'>setTimeout('self.close();',5000)</script>"

?>

   </body>
</html>