<?php
set_time_limit( 3600);
//Creamos la conexión a las distintas base de datos, la de arriba y las de abajo.
$conexion = mysql_connect("localhost" , "root" , "secreta");
mysql_select_db("lottomax",$conexion);
/*$conexion_arriba = mysql_connect("www.db4free.net:3306" , "lottomaxuser" , "secreta7");
mysql_select_db("lottomaxdb",$conexion_arriba);
//Buscamos los tickets que no han sido subido abajo.
*/
$sql = "SELECT * FROM ticket_diario WHERE subido=1 ORDER by fecha_hora ASC ";
if($result= mysql_query($sql,$conexion)){
	/*echo "PASA";
	exit;*/
	$numero_registros = mysql_num_rows($result);
	//Creamos la cadena para insertar los ticket y detalle_ticket que no han sido subidos.
	$consulta_arriba_ticket="INSERT INTO ticket (id_ticket, serial, fecha_hora, taquilla, total_ticket, id_usuario, premiado, pagado, total_premiado, status, fecha_hora_anulacion, taquilla_anulacion, subido, verificado, impreso, fecha_hora_pagado, usuario_pagado, taquilla_pagado) VALUES  ";
	$consulta_arriba_detalle="INSERT INTO detalle_ticket (id_detalle_ticket,id_ticket, numero, id_sorteo, fecha_sorteo, id_zodiacal, id_tipo_jugada, monto, premiado, total_premiado, monto_restante, monto_faltante) VALUES ";
	while ($row = mysql_fetch_array($result)){
		// Creamos la consulta para extraer los datos de detalle_ticket de cada ticket extraído de la tabla ticket que no ha sido subido
		if($row['usuario_pagado']==null)
		{
			$usuario_pagado=0;
			$taquilla_pagado=0;
			$fecha_hora_pagado='0000-00-00 00:00:00';
		}
		else
		{
			$usuario_pagado=$row['usuario_pagado'];
			$taquilla_pagado=$row['taquilla_pagado'];
			$fecha_hora_pagado=$row['fecha_hora_pagado'];
				
		}
		$consulta_arriba_ticket.="('".$row['id_ticket_diario']."','".$row['serial']."','".$row['fecha_hora']."',".$row['taquilla'].",'".$row['total_ticket']."',".$row['id_usuario'].",".$row['premiado'].",".$row['pagado'].",".$row['total_premiado'].",".$row['status'].",'".$row['fecha_hora_anulacion']."',".$row['taquilla_anulacion'].",1,".$row['verificado'].",".$row['impreso'].",'".$fecha_hora_pagado."',".$usuario_pagado.",".$taquilla_pagado."),";   
		$arreglo[]=$row['id_ticket_diario'];
		$sql1 = "SELECT * FROM detalle_ticket_diario WHERE id_ticket_diario=".$row['id_ticket_diario'];
		$result1= mysql_query($sql1,$conexion);
		$numero_registros1 = mysql_num_rows($result1);
		//	echo $numero_registros1."<br>";
		while ($row1 = mysql_fetch_array($result1))
		$consulta_arriba_detalle.="(".$row1['id_detalle_ticket_diario'].",".$row1['id_ticket_diario'].",'".$row1['numero']."',".$row1['id_sorteo'].",'".$row1['fecha_sorteo']."',".$row1['id_zodiacal'].",".$row1['id_tipo_jugada'].",".$row1['monto'].",".$row1['premiado'].",".$row1['total_premiado'].",".$row1['monto_restante'].",".$row1['monto_faltante']."),";
	}
	$consulta_arriba_detalle = trim($consulta_arriba_detalle, ',');
	$consulta_arriba_ticket = trim($consulta_arriba_ticket, ',');
	$consulta_arriba_ticket.=";";
	$consulta_arriba_detalle.=";";
	echo $consulta_arriba_ticket;
	//exit;
	echo "<br>".$consulta_arriba_detalle;
	//*/
	//exit;*/
	$error=0;
	if (mysql_query("SET AUTOCOMMIT=0;",$conexion))//desactivar el modo de autoguardado
	{
		echo "<br>Desactivo el modo de autoguardado";
		if (mysql_query("BEGIN;",$conexion)) //dar inicio a la transacción
		{
			echo "<br>Inicia la conexion";
			if (mysql_query($consulta_arriba_ticket,$conexion))
			{
				echo "<br>Inserto Ticket";
				if (mysql_query($consulta_arriba_detalle,$conexion))
				{
					echo "<br>Inserto Detalle Ticket";
					$error=0;//mysql_query("SET AUTOCOMMIT=1;",$conexion_arriba);
				}
				else
				$error=1;
			}
			else
			$error=1;
		}
		else
		$error=1;
	}
	else
	$error=1;
	if($error==0)
	{	
		// Busco los ticket abajo que acabo de subir.
		foreach ($arreglo as $id){
			$sql="DELETE FROM ticket_diario WHERE id_ticket_diario=".$id;
			//echo "<br> Borrar: ".$sql;
			if (mysql_query($sql,$conexion)){}
			else
			$error=1;
		}
	}
	if($error==1)
	{
		echo "<br>Error";
		mysql_query("ROLLBACK;",$conexion); //garantizo que se haga el retroceso de las operaciones
		mysql_query("SET AUTOCOMMIT=1;",$conexion);
		mysql_close($conexion);
	}
	else
	{
		echo "<br>Perfecto";
		mysql_query("COMMIT;",$conexion); //garantizo que se haga el retroceso de las operaciones
		mysql_query("SET AUTOCOMMIT=1;",$conexion);
		mysql_close($conexion);
	}		
}


?>