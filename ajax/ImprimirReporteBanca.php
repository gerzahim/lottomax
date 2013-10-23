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

$fecha_desde= $obj_generico->CleanText($_GET['fechadesde']);
$fecha_hasta= $obj_generico->CleanText($_GET['fechahasta']);

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
$obj_modelo= new Ventas($obj_conexion);

session_start();


         if( $result= $obj_modelo->GetBalance($fecha_desde, $fecha_hasta)){
            if ($obj_conexion->GetNumberRows($result)>0 ){
				// ENCABEZADO DEL TICKET
				$data="SISTEMA LOTTOMAX";
				$data.="<br>";

 	
            	//Cambio de tamano fuenta a 10 cpi
				$data1="\\x1B\\x50";
				$data1.="SISTEMA LOTTOMAX";
				$data1.="\\n";				
            	
                // Establecemos la cabecera de la tabla
                $pdf->SetFont('Arial','B',10);
                $pdf->SetTextColor(128,0,0);
                $pdf->Cell(40,7,'Fecha',1,0,'C',true);
                $pdf->Cell(30,7,'Total Ventas',1,0,'C',true);
                $pdf->Cell(30,7,'Comision',1,0,'C',true);
                $pdf->Cell(30,7,'Total Premiados',1,0,'C',true);
                $pdf->Cell(30,7,'Balance',1,0,'C',true);

                $pdf->SetFont('Arial','',8);
               
              
                while($row= $obj_conexion->GetArrayInfo($result)){
                        
					$data.="<br>FECHA: ".$row['fecha'];
					$data.="<br>Total Ventas: ".$row['total_ventas'];					                									
					$data.="<br>Comision: ".$row['comision'];
					$data.="<br>Total Premios: ".$row['total_premiado'];					                									
					$data.="<br>Balance: ".$row['balance'];
					$data.="-----------------------------";
					$data.="<br><br>";

					
					$data1.="\\nFECHA: ".$row['fecha'];
					$data1.="\\nTotal Ventas: ".$row['total_ventas'];					                									
					$data1.="\\nComision: ".$row['comision'];
					$data1.="\\nTotal Premios: ".$row['total_premiado'];					                									
					$data1.="\\nBalance: ".$row['balance'];
					$data1.="-----------------------------";
					$data1.="\\n\\n";					
				                        
                    
                }
                
            } 

         }


echo $data;
//echo $data1;

echo "<script type='text/javascript'>";
echo "print('".$data1."')";
echo "</script>";

echo "<script language='javascript'>setTimeout('self.close();',5000)</script>"

?>

   </body>
</html>