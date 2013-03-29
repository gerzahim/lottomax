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

session_start();

// Modelo asignado
require('.'.$obj_config->GetVar('ruta_modelo').'Ventas.php');
$obj_modelo= new Ventas($obj_conexion);

$resultTT= $obj_modelo->GetDatosTicketTransaccional();
If ($obj_conexion->GetNumberRows($resultTT)>0){

    // Generacion del Id del Ticket
    $id_ticket= $obj_modelo->GeneraIDTicket();


    // Generacion del serial del ticket
    $serial="";
    $serial = $obj_modelo->GeneraSerialTicket();
    while ($obj_modelo->GetExisteSerialTicket($serial)){
        $serial = $obj_modelo->GeneraSerialTicket();
    }

    // Obtenemos los datos de la taquilla
    $taquilla= $obj_modelo->GetIdTaquilla();

    // Obtenemos el total del ticket
    if( $result= $obj_modelo->GetDatosTicketTransaccional() ){
            $total_ticket=0;
            while($row= $obj_conexion->GetArrayInfo($result)){
                    // acumula y suma el total a la variable
                    $total_ticket+=$row['monto'];
            }
    }

    $fecha_hora= date('Y-m-d h:m');
    $id_usuario= $_SESSION['id_usuario'];

    // Guardamos el Ticket
    if ($obj_modelo->GuardarTicket($id_ticket,$serial, $fecha_hora, $taquilla, $total_ticket, $id_usuario)){
        if( $result2= $obj_modelo->GetDatosAllTicketTransaccional() ){
            while($row= $obj_conexion->GetArrayInfo($result2)){
                // Guarda los datos en detalle Ticket
                if ($row['incompleto']<>'2'){
                    $hora_sorteo= $obj_modelo->GetHoraSorteo($row['id_sorteo']);
                     if ($obj_modelo->GuardarDetalleTicket($id_ticket, $row['numero'], $row['id_sorteo'], $hora_sorteo, $row['id_zodiacal'], $row['id_tipo_jugada'], $row['monto'])){

                        //Verificamos los numeros incompletos  y guardamos en tabla
                        if ($row['incompleto']=='1'){
                            $incompleto=1;
                            $obj_modelo->GuardarIncompletosAgotados($id_ticket, $fecha_hora, $row['numero'], $row['id_sorteo'], $row['id_tipo_jugada'], $row['id_zodiacal'], $row['monto_faltante'], $incompleto);
                        }

                        //Actualizamos en tabla numeros jugados

                        // Buscamos el numero en la tabla a ver si ya esta registrado
                        $result = $obj_modelo->GetExisteNumeroJugados($row['numero'],$row['id_sorteo'], $row['id_tipo_jugada'], $row['id_zodiacal']);
                        if ($obj_conexion->GetNumberRows($result)>0){
                            // Ya el numero esta registrado...
                            $row_nj = $obj_conexion->GetArrayInfo($result);

                            if ($row_nj['monto_restante']>0){
                                $nuevo_monto=$row_nj['monto_restante']-$row['monto'];
                                // Actualizamos el nuevo monto disponible para jugar del numero
                                $obj_modelo->ActualizaNumeroJugados($row_nj['id_numero_jugados'], $nuevo_monto);
                            }else{
                                // Si el numero ya está registrado y el monto restante es igual a cero, entonces el numero ya estaba agotado en el monto disponible...
                            }

                        }else{ // Si el numero no se encuentra registrado en numeros jugados debe guardarse en la tabla

                            //Calculamos el monto_restante
                            if ($row['incompleto']=='1'){
                                // Si incompleto es 1, quiere decir que se hizo la jugada por el valor maximo disponible del cupo
                                $monto_restante=0;
                            }elseif ($row['incompleto']=='0'){
                                // Si incompleto es 0, quiere decir que se hizo la jugada por el valor inferior al disponible del cupo y
                                // el disponible para jugar quedo registrado en monto_faltante
                                $monto_restante=$row['monto_faltante'];
                            }

                            //Guardamos el numero en la tabla
                            $obj_modelo->GuardarNumerosJugados($fecha_hora, $row['numero'], $row['id_sorteo'], $row['id_tipo_jugada'], $row['id_zodiacal'], $monto_restante);
                        }
                    }
                }else{//Verificamos los numeros agotados y guardamos en tabla
                     $incompleto=2;
                     $obj_modelo->GuardarIncompletosAgotados($id_ticket, $fecha_hora, $row['numero'], $row['id_sorteo'], $row['id_tipo_jugada'], $row['id_zodiacal'], $row['monto_faltante'], $incompleto);
                }

                // Despues de guardado en detalle_ticket, borramos el registro de ticket transaccional...
                $obj_modelo->EliminarTicketTransaccional($row['id_ticket_transaccional']);
            }
        }

        echo "Ok";
    }else{
        echo "NotOk";
    }

}else{
    echo "CeroTicketTransaccional";
}



?>