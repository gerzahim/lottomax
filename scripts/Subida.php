<?php
//Creamos la conexión a las distintas base de datos, la de arriba y las de abajo.


$conexion_abajo = mysql_connect("localhost" , "root" , "secreta");
mysql_select_db("lottomax",$conexion_abajo);

$conexion_arriba = mysql_connect("www.db4free.net" , "lottomaxuser" , "secreta7",true);
mysql_select_db("lottomaxdb",$conexion_arriba);


/*$conexion_arriba = mysql_connect("www.db4free.net:3306" , "lottomaxuser" , "secreta7");
mysql_select_db("lottomaxdb",$conexion_arriba);


//Buscamos los tickets que no han sido subido abajo.
*/

$sql = "SELECT * FROM ticket WHERE subido=0";

if($result= mysql_query($sql,$conexion_abajo))
{
//	echo "PASA";
	$numero_registros = mysql_num_rows($result);
	
	//Creamos la cadena para insertar los ticket y detalle_ticket que no han sido subidos.
	$consulta_arriba_ticket="INSERT INTO ticket (id_ticket, serial, fecha_hora, taquilla, total_ticket, id_usuario, premiado, pagado, total_premiado, status, fecha_hora_anulacion, taquilla_anulacion, subido, verificado, impreso) VALUES ( ";
	$consulta_arriba_detalle="INSERT INTO detalle_ticket (id_detalle_ticket,id_ticket, numero, id_sorteo, fecha_sorteo, id_zodiacal, id_tipo_jugada, monto, premiado, total_premiado, monto_restante, monto_faltante) VALUES ";
	
	$h=0;
	$jj=0;
	
	while ($row = mysql_fetch_array($result)) 
	{
		
		// Creamos la consulta para extraer los datos de detalle_ticket de cada ticket extraído de la tabla ticket que no ha sido subido
		$consulta_arriba_ticket.="(".$row['id_ticket'].",".$row['serial'].",'".$row['fecha_hora']."',".$row['taquilla'].",'".$row['total_ticket']."',".$row['id_usuario'].",".$row['premiado'].",".$row['pagado'].",".$row['total_premiado'].",".$row['status'].",'".$row['fecha_hora_anulacion']."',".$row['taquilla_anulacion'].",1,".$row['verificado'].",".$row['impreso']."),";
		$arreglo[]=$row['id_ticket'];
		
		$sql1 = "SELECT * FROM detalle_ticket WHERE id_ticket=".$row[0];
		$result1= mysql_query($sql1,$conexion_abajo);
		$numero_registros1 = mysql_num_rows($result1);
		//	echo $numero_registros1."<br>";
		while ($row1 = mysql_fetch_array($result1))
		$consulta_arriba_detalle.="(".$row1['id_detalle_ticket'].",".$row1['id_ticket'].",'".$row1['numero']."',".$row1['id_sorteo'].",'".$row1['fecha_sorteo']."',".$row1['id_zodiacal'].",".$row1['id_tipo_jugada'].",".$row1['monto'].",".$row1['premiado'].",".$row1['total_premiado'].",".$row1['monto_restante'].",".$row1['monto_faltante']."),";
	}
	$consulta_arriba_detalle = trim($consulta_arriba_detalle, ',');
	$consulta_arriba_ticket = trim($consulta_arriba_ticket, ',');
	$consulta_arriba_ticket.=";";
	$consulta_arriba_detalle.=";";
	echo $consulta_arriba_detalle;
	echo $consulta_arriba_ticket;
	exit;
		$error=0;
	if (mysql_query("SET AUTOCOMMIT=0;",$conexion_arriba))//desactivar el modo de autoguardado
		if (mysql_query("BEGIN;",$conexion_arriba)) //dar inicio a la transacción
		{
			//echo $consulta_arriba_detalle;
			if (mysql_query($consulta_arriba_ticket,$conexion_arriba))
			{
				//echo $consulta_arriba_detalle;
				if (mysql_query($consulta_arriba_detalle,$conexion_arriba))
					$error=0;//mysql_query("SET AUTOCOMMIT=1;",$conexion_arriba);
				else
				$error=1;
			}
			else
			$error=1;
		}
		else
		$error=1;
	else
	$error=1;
	if($error==0)
	{	
		// Busco los ticket abajo que acabo de subir.
		if (mysql_query("SET AUTOCOMMIT=0;",$conexion_abajo))//desactivar el modo de autoguardado	
			if (mysql_query("BEGIN;",$conexion_abajo)) //dar inicio a la transacción
				foreach ($arreglo as $id)
				{
					$sql="UPDATE ticket SET subido=1 WHERE subido=0 AND id_ticket=".$id;
					if (mysql_query($sql,$conexion_abajo)){}
					else
					$error=1;						
				}
			else
			$error=1;
			else
			$error=1;
	}
	if($error==1)
	{
		mysql_query("ROLLBACK;",$conexion_abajo); //garantizo que se haga el retroceso de las operaciones
		mysql_query("ROLLBACK;",$conexion_arriba); //garantizo que se haga el retroceso de las operaciones	
	}
	else
	{
		mysql_query("SET AUTOCOMMIT=1;",$conexion_abajo);
		mysql_query("SET AUTOCOMMIT=1;",$conexion_arriba);
	}	
}


?>