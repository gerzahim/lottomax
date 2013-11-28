<?php

$address = $_POST['ip'];

error_reporting(E_ALL);

echo "<h2>TCP/IP Connection</h2>\n";

/* Obtener el puerto para el servicio WWW. */
$service_port = '9999';

/* Obtener la dirección IP para el host objetivo.  */
//$address = '192.168.1.104'; 


/* Crear un socket TCP/IP. */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "socket_create() falló: razón: " . socket_strerror(socket_last_error()) . "\n";
} else {
    echo "OK.\n";
}

echo "Intentando conectar a '$address' en el puerto '$service_port'...";
echo "<br>";
$result = socket_connect($socket, $address, $service_port);
if ($result === false) {
    echo "socket_connect() falló.\nRazón: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    echo "OK.\n";
}
/*
$in = "HEAD / HTTP/1.1\r\n";
$in .= "Host: www.example.com\r\n";
$in .= "Connection: Close\r\n\r\n";
*/

$in = "Llamado deste taquilla \r\n";
$in = "*01*\r\n";
$out = '';

echo "Enviando peticion HTTP HEAD ...";
socket_write($socket, $in, strlen($in));

/*
echo "OK.\n";

echo "Leyendo respuesta:\n\n";
while ($out = socket_read($socket, 2048)) {
    echo $out;
}
*/

echo "Cerrando socket...";
socket_close($socket);
echo "OK.\n\n";
?>