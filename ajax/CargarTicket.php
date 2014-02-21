<?php
date_default_timezone_set("America/Caracas");
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

// Listado de Jugadas Agregadas
if( $result= $obj_modelo->GetDatosTicketTransaccional() ){
    If ($obj_conexion->GetNumberRows($result)>0){
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
			$zodiacal = $obj_modelo->GetPreNombreSigno($row['id_zodiacal']);
		}

                //print_r($row);
                echo "<tr class='eveno'><td align='center'>".$span1."SORTEO: ".$obj_modelo->GetNombreSorteo($row['id_sorteo']).$span2."</td></tr>";
                echo "<tr class='eveni'><td align='left'>".$span1.$row['numero']." x ".$row['monto']." ".$zodiacal." ".$inc.$span2."</td></tr>";
        }
        echo "</table>";

    }
}
?>