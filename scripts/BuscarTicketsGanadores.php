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
$fecha_hora=$_GET['fecha_hora'];
$aprox_abajo= $obj_modelo->GetAprox_abajo();
$aprox_arriba= $obj_modelo->GetAprox_arriba();



//$where = " fecha_hora LIKE '%".date('Y-m-d')."%'";
//$result= $obj_modelo->GetListadosegunVariable($where);

$result= $obj_modelo->GetListadosegunVariable($fecha_hora);


If ($obj_conexion->GetNumberRows($result)>0){
   $i=0; $j=0; 
   $ticket_premiado=0;
   $monto_total_ticket=0;
    while ($roww= $obj_conexion->GetArrayInfo($result)){

       
        $id_ticket=$roww["id_ticket"];
        $fecha_ticket= $obj_modelo->GetFechaTicket($id_ticket);
        $resultDT = $obj_modelo->GetAllDetalleTciket($id_ticket);
        
        //revisamos la tabla de detalle ticket y comparamos con los resultados
        while($rowDT= $obj_conexion->GetArrayInfo($resultDT)){
        	$monto_total=0;
            // Verificamos si hay alguna apuesta ganadora...
            if ($obj_modelo->GetGanador($rowDT['id_sorteo'], $rowDT['id_zodiacal'], $rowDT['numero'], substr($fecha_ticket,0,10), $rowDT['id_tipo_jugada'])){
               
            	$id_detalle_ticket[$j]=$rowDT['id_detalle_ticket'];
              
                $monto_pago = $obj_modelo->GetRelacionPagos($rowDT['id_tipo_jugada']);
                $monto_total = $monto_total + ($monto_pago*$rowDT['monto']);
                
                //destacamos la jugada ganadora en detalle ticket premiado 1 y monto ganado por la jugada
                $obj_modelo->PremiarDetalleTicket($id_detalle_ticket[$j], $monto_total);
                $ticket_premiado=1;
                $monto_total_ticket = $monto_total_ticket + $monto_total;
            }

            // Verificamos las aproximaciones por arriba y por abajo...
            if ($aprox_abajo){ // Si esta activa la aproximacion por abajo...
                if ($rowDT['id_tipo_jugada']==2){ // Si el tipo de jugada es Terminal
                    // Verificamos si hay aproximaciones por abajo
                     if ($obj_modelo->GetAproximacion($rowDT['id_sorteo'], $rowDT['id_zodiacal'], $rowDT['numero'], substr($fecha_ticket,0,10), 'abajo')){
                         $id_detalle_ticket[$j]=$rowDT['id_detalle_ticket'];
                                                 
                         $monto_pago = $obj_modelo->GetRelacionPagos('5'); // Tipo Jugada Aproximacion
                         $monto_total = $monto_total + ($monto_pago*$rowDT['monto']);
                         
                         //destacamos la jugada ganadora en detalle ticket premiado 1 y monto ganado por la jugada
                         $obj_modelo->PremiarDetalleTicket($id_detalle_ticket[$j], $monto_total);
                         $ticket_premiado=1;
                         $monto_total_ticket = $monto_total_ticket + $monto_total;
                     }
                }
            }

            if ($aprox_arriba){ // Si esta activa la aproximacion por arriba...
                if ($rowDT['id_tipo_jugada']==2){ // Si el tipo de jugada es Terminal
                    // Verificamos si hay aproximaciones por abajo
                     if ($obj_modelo->GetAproximacion($rowDT['id_sorteo'], $rowDT['id_zodiacal'], $rowDT['numero'], substr($fecha_ticket,0,10), 'arriba')){
                         $id_detalle_ticket[$j]=$rowDT['id_detalle_ticket'];
                                                 
                         $monto_pago = $obj_modelo->GetRelacionPagos('5'); // Tipo Jugada Aproximacion
                         $monto_total = $monto_total + ($monto_pago*$rowDT['monto']);
                         
                         
                         //destacamos la jugada ganadora en detalle ticket premiado 1 y monto ganado por la jugada
                         $obj_modelo->PremiarDetalleTicket($id_detalle_ticket[$j], $monto_total);
                         $ticket_premiado=1;
                         $monto_total_ticket = $monto_total_ticket + $monto_total;
                     }
                }
            }
            

       }// fin del subwhile
       
       //Cambiar a Verficado=1 en tabla ticket independientemente si gano o no gano
       //$id_ticket=$roww["id_ticket"];
       $obj_modelo->MarcarVerificadoByIdTicket($id_ticket);
       
       //contadores
         $id_tickets[$j]=$id_ticket;$j++;
         $totales[$i]=$monto_total; $i++; //total en Bs premiados, 
          
         //verificando que estemos pasando por un ticket premiado
         // vamos a destacar el ticket premiado 1 y monto total ganado 
         if($ticket_premiado==1){
 
         	$obj_modelo->PremiarTicket($id_ticket,$monto_total_ticket);
         	$ticket_premiado=0;
         	$monto_total_ticket=0;
         }
    
    }// fin del while mayor
    
    /*
	    // Premiamos los tickets
	    for ($i = 0; $i < count($id_tickets); $i++){
	         if( $obj_modelo->PremiarTicket($id_tickets[$i],$totales[$i])){
	
	         }
	    }

	    if (count($id_detalle_ticket)>0){
	        for ($i = 0; $i < count($id_detalle_ticket); $i++){
	             if( $obj_modelo->PagarDetalleTicket($id_detalle_ticket[$i])){}
	        }
	    } 
	    */
}
?>

