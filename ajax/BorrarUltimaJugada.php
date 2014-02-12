<?php

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
require('.'.$obj_config->GetVar('ruta_modelo').'Ventas.php');
$obj_modelo= new Ventas($obj_conexion);

session_start();

$resultTT= $obj_modelo->GetLastTicketTransaccional();
If ($obj_conexion->GetNumberRows($resultTT)>0){
    $roww= $obj_conexion->GetArrayInfo($resultTT);
    $id_ticket_transaccional=$roww["id_ticket_transaccional"];
    
    $obj_modelo->EliminarTicketTransaccionalByTicket($id_ticket_transaccional);
      
}

// Listado de Jugadas Agregadas
if( $result= $obj_modelo->GetDatosTicketTransaccional() ){
    echo "<br><table class='table_ticket' align='center' border='1' width='90%'>";
    while($row= $obj_conexion->GetArrayInfo($result)){
            $inc = "";
            $span1="";
            $span2="";
            //determinando si es un numero inc..
            if($row['incompleto'] == 1){
                    $inc = "Inc ...";
                     $span1="<span class='requerido'>";
                     $span2="</span>";
            }

            $zodiacal="";
            // determinando el signo zodiacal... si lo hay...
            if($row['id_zodiacal'] <> 0){
                    $zodiacal = $obj_modelo->GetPreZodiacal($row['id_zodiacal']);
            }
            
            //print_r($row);
            echo "<tr class='eveno'><td align='center'>".$span1."SORTEO: ".$obj_modelo->GetNombreSorteo($row['id_sorteo']).$span2."</td></tr>";
            echo "<tr class='eveni'><td align='left'>".$span1.$row['numero']." x ".$row['monto']." ".$zodiacal." ".$inc.$span2."</td></tr>";
    }
    echo "</table>";
}
?>