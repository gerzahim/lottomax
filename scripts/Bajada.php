<?php
//Creamos la conexión a las distintas base de datos, la de arriba y las de abajo.
// Archivo de variables de configuracion
//require('../models/Pagar_Ganador.php');

//// BAJADO = 0 Significa que el resultado no ha sido copiado en la BD local, BAJADO=1 significa que el resultado fue bajado a la BD, cuando BAJADO=2 significa que se hizo un cambio el resultado del servidor de arriba y este tiene que ser actualizado en la BD local

$conexion_abajo = mysql_connect("localhost" , "root" , "secreta");
mysql_select_db("lottomax",$conexion_abajo);

$conexion_arriba = mysql_connect("sql3.freesqldatabase.com:3306" , "sql329054" , "gA5!tM4*",true);
mysql_select_db("sql329054",$conexion_arriba);


/*$conexion_arriba = mysql_connect("www.db4free.net:3306" , "lottomaxuser" , "secreta7");
mysql_select_db("lottomaxdb",$conexion_arriba);


//Buscamos los resultados que no han sido bajados.
*/

$sql = "SELECT * FROM resultados WHERE bajado = 0";

if($result= mysql_query($sql,$conexion_arriba))
{

	$numero_registros = mysql_num_rows($result);
//echo $numero_registros ;
//Creamos la cadena para insertar los resultados que no han sido bajados.
$consulta_abajo="INSERT INTO resultados (id_resultados, id_sorteo, zodiacal, numero, fecha_hora, bajado) VALUES ( ";

$h=0;
$sw=0;
$id_ultimo=0;
$jj=0;

while ($row = mysql_fetch_row($result)) 
{
	//print_r($row);
	$fecha_hora=$row[4];
	// Creamos la consulta para insertar los datos de los premios
	for ($i = 0; $i < mysql_num_fields($result); $i++)
	{
		if ($i!=mysql_num_fields($result)-1)
		{
			if($i==4 OR $i==3)
				$consulta_abajo.=" '".$row[$i]."', ";
			else
			$consulta_abajo.=" ".$row[$i].", ";
		}
		else
		$consulta_abajo.=" 1 )";
	}
	$h++;
	if($h<$numero_registros)
	$consulta_abajo.=",(";
	$arreglo[]=$row[0];	
}
$consulta_abajo.=";";
$error=0;
//echo $consulta_abajo;
if (mysql_query("SET AUTOCOMMIT=0;",$conexion_abajo))//desactivar el modo de autoguardado
	if (mysql_query("BEGIN;",$conexion_abajo)) //dar inicio a la transacción
		if (mysql_query($consulta_abajo,$conexion_abajo))
			$error=0;//mysql_query("SET AUTOCOMMIT=1;",$conexion_arriba);	
		else
		$error=1;
	else
	$error=1;
else
$error=1;

//echo "Error".$error;
if($error==0)
	// Busco los resultados arriba que acabo de bajar.
	if (mysql_query("SET AUTOCOMMIT=0;",$conexion_arriba))//desactivar el modo de autoguardado
		if (mysql_query("BEGIN;",$conexion_arriba)) //dar inicio a la transacción
			foreach ($arreglo as $id)
			{
				$sql="UPDATE resultados SET bajado=1 WHERE bajado=0 AND id_resultados=".$id;
				if (mysql_query($sql,$conexion_arriba)){}
				else
				$error=1;
			}
		else
		$error=1;
	else
	$error=1;
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
//	echo "PASA";
	shell_exec("curl http://localhost/scripts/BuscarTicketsGanadores.php?fecha_hora=".$fecha_hora);
	//header ("Location: );
}
}

// COMIENZA LAS INSTRUCCIONES PARA CUANDO UN RESULTADO FUE MODIFICADO Y REQUIERE SER ACTUALIZADO EN LA BD LOCAL

$sql = "SELECT * FROM resultados WHERE bajado = 2";  
if($result= mysql_query($sql,$conexion_arriba))
{
	$numero_registros = mysql_num_rows($result);
	//Creamos la cadena para insertar los resultados que no han sido bajados.
	while ($row = mysql_fetch_array($result))
	{
		$fecha_hora=$row['fecha_hora'];
		$consulta_abajo="UPDATE resultados SET numero='".$row['numero']."' WHERE id_resultados=".$row['id_resultados'];
		$consulta_arriba="UPDATE resultados SET bajado=1 WHERE id_resultados=".$row['id_resultados']; // volvemos a setear bajado=1 para que el sistema sepa que este resultado ya fue actualizado.
		if (mysql_query("SET AUTOCOMMIT=0;",$conexion_abajo) AND mysql_query("SET AUTOCOMMIT=0;",$conexion_arriba))//desactivar el modo de autoguardado
		if (mysql_query("BEGIN;",$conexion_abajo) AND mysql_query("BEGIN;",$conexion_arriba)) //dar inicio a la transacción
			if (mysql_query($consulta_abajo,$conexion_abajo)) //EJECUTA EL QUERY
				if (mysql_query($consulta_arriba,$conexion_arriba)) //EJECUTA EL QUERY
				{	
					mysql_query("SET AUTOCOMMIT=1;",$conexion_abajo);
					mysql_query("SET AUTOCOMMIT=1;",$conexion_arriba);
					shell_exec("curl http://localhost/scripts/BuscarTicketsGanadores.php?fecha_hora=".$fecha_hora);
					//header ("Location: BuscarTicketsGanadores.php?fecha_hora=".$fecha_hora);
				}
				else 
				mysql_query("ROLLBACK;",$conexion_abajo);
	}

}
//$fecha_hora=date('Y-m-d');
?>