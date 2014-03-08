<?php
//Creamos la conexión a las distintas base de datos, la de arriba y las de abajo.
// Archivo de variables de configuracion
//require('../models/Pagar_Ganador.php');
set_time_limit(360);
error_reporting(E_ALL);
//// BAJADO = 0 Significa que el resultado no ha sido copiado en la BD local, BAJADO=1 significa que el resultado fue bajado a la BD, cuando BAJADO=2 significa que se hizo un cambio el resultado del servidor de arriba y este tiene que ser actualizado en la BD local
require_once('BajadaController.php');

$conexion_abajo = mysql_connect("localhost" , "root" , "secreta");
mysql_select_db("lottomax",$conexion_abajo);

$conexion_arriba = mysql_connect("www.db4free.net" , "lottomaxuser" , "secreta7",true);
mysql_select_db("lottomaxdb",$conexion_arriba);
$obj_modelo= new BajadaController();


/*$conexion_arriba = mysql_connect("www.db4free.net:3306" , "lottomaxuser" , "secreta7");
mysql_select_db("lottomaxdb",$conexion_arriba);


//Buscamos los resultados que no han sido bajados.
*/
//$sql1 = "SELECT * FROM resultados";

$sql = "SELECT * FROM resultados WHERE bajado = 0";

if($result= mysql_query($sql,$conexion_arriba))
{
	$numero_registros = mysql_num_rows($result);
//echo $numero_registros ;
//Creamos la cadena para insertar los resultados que no han sido bajados.
$consulta_abajo="INSERT INTO resultados ( id_sorteo, zodiacal, numero, fecha_hora, bajado) VALUES  ";

$h=0;
$sw=0;
$id_ultimo=0;
$jj=0;
$resultados=array();
$zodiacales=array();
$fecha_hora=array();
while ($row = mysql_fetch_array($result)) 
{
	//echo "pasa";
	if(!in_array($row['fecha_hora'], $fecha_hora)){
		$fecha_hora[]=$row['fecha_hora'];
	}
	// Si no existe el resultado Creamos la consulta para insertar los datos de los premios
	if(ExisteResultado($row['id_sorteo'],$row['zodiacal'],$row['numero'],$row['fecha_hora'],$conexion_abajo)){
		$h=1;
		$consulta_abajo.=" ( ".$row['id_sorteo'].", ".$row['zodiacal'].", ".$row['numero'].",'".$row['fecha_hora']."', 1 ),";
		$resultados[$row['id_sorteo']."/".$row['fecha_hora']]=$row['numero'];
		$zodiacales[$row['id_sorteo']."/".$row['fecha_hora']]=$row['zodiacal'];
	}
	$arreglo[]=$row['id_resultados'];	
}
$consulta_abajo=trim($consulta_abajo,",");
$consulta_abajo.=";";
$error=0;
if (mysql_query("SET AUTOCOMMIT=0;",$conexion_abajo))//desactivar el modo de autoguardado
	if (mysql_query("BEGIN;",$conexion_abajo)) //dar inicio a la transacción
		if (mysql_query($consulta_abajo,$conexion_abajo) AND $h==1)
			$error=0;//mysql_query("SET AUTOCOMMIT=1;",$conexion_arriba);	
		else
		$error=1;
	else
	$error=1;
else
$error=1;

if($error==0)
	// Busco los resultados arriba que acabo de bajar.
	if (mysql_query("SET AUTOCOMMIT=0;",$conexion_arriba))//desactivar el modo de autoguardado
		if (mysql_query("BEGIN;",$conexion_arriba)) //dar inicio a la transacción
			foreach ($arreglo as $id)
			{
				$sql="UPDATE resultados SET bajado=1 WHERE bajado=0 AND id_resultados=".$id;
				if (mysql_query($sql,$conexion_arriba)){
					
				}
				else
				$error=1;
			}
		else
		$error=1;
	else
	$error=1;
//	echo "error".$error;
if($error==1)
{
	//echo "pasa";
	mysql_query("ROLLBACK;",$conexion_arriba);
	mysql_query("ROLLBACK;",$conexion_abajo); //garantizo que se haga el retroceso de las operaciones	
}
else 
{
	mysql_query("SET AUTOCOMMIT=1;",$conexion_abajo);
	mysql_query("SET AUTOCOMMIT=1;",$conexion_arriba);
	//echo "PASA ANTES";
	/*print_r($fecha_hora);
	exit;*/
	PremiarGanadores($conexion_abajo,$obj_modelo,$resultados,$zodiacales,$fecha_hora);
	
	//shell_exec("curl http://localhost/scripts/BuscarTicketsGanadores.php?fecha_hora=".$fecha_hora);
	//header ("Location: );
}
}

// COMIENZA LAS INSTRUCCIONES PARA CUANDO UN RESULTADO FUE MODIFICADO Y REQUIERE SER ACTUALIZADO EN LA BD LOCAL
$fecha_hora2=array();
$id_sorteo=array();
$sql = "SELECT * FROM resultados WHERE bajado = 2";  
if($result= mysql_query($sql,$conexion_arriba))
{	
	//echo "pasas";
	
	$numero_registros = mysql_num_rows($result);
	//Creamos la cadena para insertar los resultados que no han sido bajados.
	while ($row = mysql_fetch_array($result))
	{
		if(!in_array($row['fecha_hora'], $fecha_hora2)){
			$fecha_hora2[]=$row['fecha_hora'];
		}
		$id_sorteo[]=$row['id_sorteo'];
		$consulta_abajo="UPDATE resultados SET numero='".$row['numero']."', zodiacal='".$row['zodiacal']."' WHERE id_sorteo=".$row['id_sorteo']." AND fecha_hora LIKE '%".$row['fecha_hora']."%'";
		$consulta_arriba="UPDATE resultados SET bajado=1 WHERE id_resultados=".$row['id_resultados']; // volvemos a setear bajado=1 para que el sistema sepa que este resultado ya fue actualizado.
		if (mysql_query("SET AUTOCOMMIT=0;",$conexion_abajo) AND mysql_query("SET AUTOCOMMIT=0;",$conexion_arriba))//desactivar el modo de autoguardado
		if (mysql_query("BEGIN;",$conexion_abajo) AND mysql_query("BEGIN;",$conexion_arriba)){ //dar inicio a la transacción
			if(mysql_query($consulta_abajo,$conexion_abajo))//EJECUTA EL QUERY
			{
				if (mysql_query($consulta_arriba,$conexion_arriba)) //EJECUTA EL QUERY
				{
					mysql_query("SET AUTOCOMMIT=1;",$conexion_abajo);
					mysql_query("SET AUTOCOMMIT=1;",$conexion_arriba);
					$obj_modelo->DespremiarTicket($fecha_hora2,$id_sorteo, $conexion_abajo);
					$resultados[$row['id_sorteo']."/".$row['fecha_hora']]=$row['numero'];
					$zodiacales[$row['id_sorteo']."/".$row['fecha_hora']]=$row['zodiacal'];
					
					PremiarGanadores($conexion_abajo,$obj_modelo,$resultados,$zodiacales,$fecha_hora2);
					//shell_exec("curl http://localhost/scripts/BuscarTicketsGanadores.php?fecha_hora=".$fecha_hora);
					//header ("Location: BuscarTicketsGanadores.php?fecha_hora=".$fecha_hora);
				}
				else 
				mysql_query("ROLLBACK;",$conexion_abajo);
			}
		}
	}
}

function ExisteResultado ($id_sorteo, $zodiacal, $numero, $fecha_hora,$conexion_abajo){
	$obj_modelo= new BajadaController();
	$result1=$obj_modelo->GetResultadosRepetidos($id_sorteo, $zodiacal, $numero, $fecha_hora,$conexion_abajo);
	if(mysql_num_rows($result1)>0)
	return false;
	else
	return true;
	
}
// Funcion para premiar los tickets ganadores
function PremiarGanadores($obj_conexion,$obj_modelo,$resultados,$zodiacales,$fecha_hora){
	$id_detalle_ticket[]="";
	$id_tickets[]="";
	$totales[]="";
	//print_r($resultados);
	$aprox= $obj_modelo->GetAprox($obj_conexion);
	$relacion_pago=array();
	$result=$obj_modelo->GetRelacionPagos($obj_conexion);
	while($row=mysql_fetch_array($result)){
		$relacion_pago[$row['id_tipo_jugada']]=$row['monto'];
	}
	//echo "pasa";
	//print_r($fecha_hora);
	foreach ($fecha_hora as $fh){
		$result= $obj_modelo->GetListadosegunVariable($fh,$obj_conexion);
		If(mysql_num_rows($result)>0){
			$i=0; $j=0;
			$ticket_premiado=0;
			while ($roww= mysql_fetch_array($result)){
				$id_ticket=$roww["id_ticket"];
		//		$fecha_ticket= substr($roww["fecha_hora"],0 , -9);
				$resultDT = $obj_modelo->GetAllDetalleTciket($id_ticket,$obj_conexion);
				//revisamos la tabla de detalle ticket y comparamos con los resultados
				$monto_total=$roww['total_premiado'];
				$sw=0;
				// SE RECORRE CADA TICKET VENDIDO EL DIA DE LA CARGA DE RESULTADO
				while($rowDT= mysql_fetch_array($resultDT)){
					// Verificamos si hay alguna apuesta ganadora...
					$terminal_abajo=0;
					$terminal_arriba=0;
					if($rowDT['id_tipo_jugada']==2){
						switch ($aprox){
							case 0:
								$terminal_abajo=$rowDT['numero']-1;
								break;
							case 1:
								$terminal_arriba=$rowDT['numero']+1;
								$terminal_abajo=$rowDT['numero']-1;
								break;
							case 2:
								$terminal_arriba=$rowDT['numero']+1;
								break;
						}
						if(isset($resultados[$rowDT['id_sorteo']."/".$rowDT['fecha_sorteo']]))
						if(($terminal_abajo==substr($resultados[$rowDT['id_sorteo']."/".$rowDT['fecha_sorteo']], 1, 3) OR $terminal_arriba==substr($resultados[$rowDT['id_sorteo']."/".$rowDT['fecha_sorteo']], 1, 3)) ){	
							$monto_pago=$relacion_pago[5]*$rowDT['monto'];
							$monto_total+=$monto_pago;
							$obj_modelo->PremiarDetalleTicket($rowDT['id_detalle_ticket'], $monto_pago,$obj_conexion);
							$sw=1;
						}
					}
					if(isset($resultados[$rowDT['id_sorteo']."/".$rowDT['fecha_sorteo']]))
					if(($rowDT['numero']==$resultados[$rowDT['id_sorteo']."/".$rowDT['fecha_sorteo']] AND ($rowDT['id_zodiacal']==$zodiacales[$rowDT['id_sorteo']."/".$rowDT['fecha_sorteo']])) OR ( ($rowDT['numero']== substr($resultados[$rowDT['id_sorteo']."/".$rowDT['fecha_sorteo']], 1, 3)  AND $rowDT['id_zodiacal']==$zodiacales[$rowDT['id_sorteo']."/".$rowDT['fecha_sorteo']]) AND ($rowDT['id_tipo_jugada']==2 OR $rowDT['id_tipo_jugada']==4)) ){
						$monto_pago=$relacion_pago[$rowDT['id_tipo_jugada']]*$rowDT['monto'];
						$monto_total+=$monto_pago;
						$sw=1;
						$obj_modelo->PremiarDetalleTicket($rowDT['id_detalle_ticket'], $monto_pago,$obj_conexion);
					}
				}
				if($sw==1)
					$obj_modelo->PremiarTicket($id_ticket,$monto_total,$obj_conexion);
			}
		}
	}
}

?>