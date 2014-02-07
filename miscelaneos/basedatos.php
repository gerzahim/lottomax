<?php
$conex=mysql_connect("192.168.0.125","root","secreta");
mysql_select_db("lottomax",$conex);

$date=date("Y-m-d H:i:s");
$sql="INSERT INTO prueba (date)VALUES ('".$date."')";
mysql_query($sql);


?>