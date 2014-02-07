<?php
$conex=mysql_connect("localhost","root","");
mysql_select_db("prueba",$conex);

$date=date("Y-m-d H:i:s");
mysql_query("INSERT INTO prueba (date)VALUES ('".$date."')");




?>