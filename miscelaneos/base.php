<?php

if($_GET['var'])
{
$conex=mysql_connect("localhost","root","secreta");
mysql_select_db("lottomax",$conex);

$date=date("Y-m-d H:i:s");
mysql_query("INSERT INTO prueba (date) VALUES ('".$date."')");
echo "INSERT INTO prueba (date) VALUES ('".$date."')";
echo "si pasa";

}
else
{
echo "no pasa";
}

?>