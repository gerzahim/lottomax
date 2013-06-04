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
require('.'.$obj_config->GetVar('ruta_modelo').'Pagar_Ganador.php');
$obj_modelo= new Pagar_Ganador($obj_conexion);

$id_detalle_ticket[]="";
$id_tickets[]="";
$totales[]="";

$where = " fecha_hora LIKE '%".date('Y-m-d')."%'";
$result= $obj_modelo->GetListadosegunVariable($where);
If ($obj_conexion->GetNumberRows($result)>0){
   $i=0; $j=0; 
    while ($roww= $obj_conexion->GetArrayInfo($result)){
        $monto_total=0;
        $id_ticket=$roww["id_ticket"];
        $fecha_ticket= $obj_modelo->GetFechaTicket($id_ticket);
        $resultDT = $obj_modelo->GetAllDetalleTciket($id_ticket);
        
        while($rowDT= $obj_conexion->GetArrayInfo($resultDT)){
            
            // Verificamos si hay alguna apuesta ganadora...
            if ($obj_modelo->GetGanador($rowDT['id_sorteo'], $rowDT['id_zodiacal'], $rowDT['numero'], substr($fecha_ticket,0,10), $rowDT['id_tipo_jugada'])){
               $id_detalle_ticket[$j]=$rowDT['id_detalle_ticket']; $id_tickets[$j]=$id_ticket;
                $monto_pago = $obj_modelo->GetRelacionPagos($rowDT['id_tipo_jugada']);
                $monto_total = $monto_total + ($monto_pago*$rowDT['monto']);
                 $j++;
            }

            // Verificamos las aproximaciones por arriba y por abajo...
            if ($obj_modelo->GetAprox_abajo()){ // Si esta activa la aproximacion por abajo...
                if ($rowDT['id_tipo_jugada']==2){ // Si el tipo de jugada es Terminal
                    // Verificamos si hay aproximaciones por abajo
                     if ($obj_modelo->GetAproximacion($rowDT['id_sorteo'], $rowDT['id_zodiacal'], $rowDT['numero'], substr($fecha_ticket,0,10), 'abajo')){
                         $id_detalle_ticket[$j]=$rowDT['id_detalle_ticket'];$id_tickets[$j]=$id_ticket;
                         $monto_pago = $obj_modelo->GetRelacionPagos('5'); // Tipo Jugada Aproximacion
                         $monto_total = $monto_total + ($monto_pago*$rowDT['monto']);
                         $j++;
                     }
                }
            }

            if ($obj_modelo->GetAprox_arriba()){ // Si esta activa la aproximacion por arriba...
                if ($rowDT['id_tipo_jugada']==2){ // Si el tipo de jugada es Terminal
                    // Verificamos si hay aproximaciones por abajo
                     if ($obj_modelo->GetAproximacion($rowDT['id_sorteo'], $rowDT['id_zodiacal'], $rowDT['numero'], substr($fecha_ticket,0,10), 'arriba')){
                         $id_detalle_ticket[$j]=$rowDT['id_detalle_ticket'];$id_tickets[$j]=$id_ticket;
                         $monto_pago = $obj_modelo->GetRelacionPagos('5'); // Tipo Jugada Aproximacion
                         $monto_total = $monto_total + ($monto_pago*$rowDT['monto']);
                         $j++; 
                     }
                }
            }

       }
         $totales[$i]=$monto_total; $i++;
    }
    
    
//    print_r($id_tickets);
//    print_r($id_detalle_ticket);
//    print_r($totales);

    // Premiamos los tickets
    for ($i = 0; $i < count($id_tickets); $i++){
         if( $obj_modelo->PremiarTicket($id_tickets[$i],$totales[$i])){

         }
    }
    for ($i = 0; $i < count($id_detalle_ticket); $i++){
         if( $obj_modelo->PagarDetalleTicket($id_detalle_ticket[$i])){}
    }
}
?>