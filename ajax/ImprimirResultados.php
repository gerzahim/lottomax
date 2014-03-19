<html>
   <head><title>Impresion de Resultados</title>
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
require('.'.$obj_config->GetVar('ruta_modelo').'RVer_resultados.php');
$obj_modelo= new RVer_Resultados($obj_conexion);




session_start();

//$id_taquilla=2;
// Obtenemos los datos de la taquilla
$id_taquilla= $obj_modelo->GetIdTaquilla();


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


$fecha= $obj_generico->CleanText($_GET['txt_fecha']);

$fecha=$obj_date->changeFormatDateII($fecha);

$i = strtotime($fecha); 
$numero_semana = jddayofweek(cal_to_jd(CAL_GREGORIAN, date("m",$i),date("d",$i), date("Y",$i)) , 0 ); 

$data="";


$i=0;
if( $result= $obj_modelo->GetResultados($fecha) ){
	if ($obj_conexion->GetNumberRows($result)>0 ){
		
		// ENCABEZADO DEL TICKET
		$data.=" <table width='100%' cellpadding='0' cellspacing='0' border='0' >";
		$data.="<tr><td colspan='3' align='center'><font face='Tahoma' size='1' >";
		$data.=$dias[date('w')]." ".$fecha_hora."&nbsp;&nbsp;".$hora;
		$data.=" </font>";
		$data.="<font face='Tahoma' size='2' >";
		$data.="<br>";
		$data.="<br>";
		$data.="SISTEMA LOTTOMAX";
		$data.="<br>";
		$data.=" Resultados Del ".$dias[$numero_semana]." ".$_GET['txt_fecha'];
		$data.="<br><br>";
		$data.=" </font></td> </tr>";		
		$data.="<tr><td align='left'><font face='Tahoma' size='2' >Sorteos</font></td><td align='left'><font face='Tahoma' size='2' >Num</font></td><td align='left'>&nbsp;<font face='Tahoma' size='2' >Signo</font></td>";
		$data.="</tr>";
		while($row= $obj_conexion->GetArrayInfo($result)){
			$data.="<tr>";
			$data.="<td align='left'><font face='Tahoma' size='2' >".$row['nombre_sorteo']."</font></td>";
			$data.="<td align='center'><font face='Tahoma' size='2' >".$row['numero']."</font></td>";
			$pre_zodiacal=$row['pre_zodiacal'];
			if ($pre_zodiacal == "**"){
				$pre_zodiacal = "";
			}
			$data.="<td align='right'><font face='Tahoma' size='2' >".$pre_zodiacal."</font></td>";
			$data.="</tr>";			
			$i++;
		}
		$data.=" </table>";
	}

}

// Obtenemos los datos de la taquilla
$ida_taquilla= $obj_modelo->GetIdTaquillabyNumero($id_taquilla);

//$lineas_saltar_despues= $obj_modelo->lineas_saltar_despues($ida_taquilla);


//Determinar si va a imprimir incompletos y Agotados
$info_impresora= $obj_modelo->GetDatosImpresora($ida_taquilla);

$lineas_saltar_despues=$info_impresora["lineas_saltar_despues"];

//Saltos de linea para hacer FEED
for($i=1;$i<=$lineas_saltar_despues;$i++){
	//$data1.="\\x1B\\x0A";
	//$data1.="\\n";
	$data.=".<br>";
}

echo $data;
?>

<script type="text/javascript"> 
window.print();
</script>
<script language='javascript'>setTimeout('self.close();',5000)</script>
</body>
</html>