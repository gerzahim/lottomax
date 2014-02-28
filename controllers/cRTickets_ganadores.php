<?php
/**
 * Archivo del controlador para modulo de Reporte de Tcikets Ganadores
 * @package cRTickets_ganadores.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Mayo - 2013
 */

// Vista asignada
$obj_xtpl->assign_file('contenido', $obj_config->GetVar('ruta_vista').'Rtickets_ganadores'.$obj_config->GetVar('ext_vista'));

// Modelo asignado
require($obj_config->GetVar('ruta_modelo').'RTickets_ganadores.php');
$obj_date= new Fecha();

$obj_modelo= new RTickets_ganadores($obj_conexion);

require('./fpdf/fpdf.php');

switch (ACCION){

    case 'listar_resultados':
        $fecha = $obj_date->changeFormatDateII($_GET['txt_fecha']);
        
        $obj_xtpl->assign('fecha', $obj_date->changeFormatDateI($fecha,0));
        
        // Ruta actual
        $_SESSION['Ruta_Lista']= $obj_generico->RutaRegreso();

        // Ruta regreso
        $obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Form']);
        
        $i=0;$total_ganadores=0;
        if( $result= $obj_modelo->GetTicketsGanadores($fecha) ){
            if ($obj_conexion->GetNumberRows($result)>0 ){
                
                while($row= $obj_conexion->GetArrayInfo($result)){
                    if( ($i % 2) >0){
                            $obj_xtpl->assign('estilo_fila', 'even');
                    }
                    else{
                            $obj_xtpl->assign('estilo_fila', 'odd');
                    }

                    $obj_xtpl->assign('fecha_hora', $obj_date->changeFormatDateI($row['fecha_hora'],1));
                    $obj_xtpl->assign('id_ticket', $row['id_ticket']);
                    $obj_xtpl->assign('total', $row['total_ticket']);
                    $obj_xtpl->assign('total_premiado', $row['total_premiado']);
                    if ($row['pagado'] == '1'){
                        $obj_xtpl->assign('pagado','Si' );
                    }else{
                        $obj_xtpl->assign('pagado','No' );
                    }
                    
                    $link_detalle= $_SESSION['Ruta_Form']."&accion=ver_detalle&id_ticket=".$row['id_ticket'];
                    $obj_xtpl->assign('link_detalle', $link_detalle);
                   
                    // Parseo del bloque de la fila
                    $obj_xtpl->parse('main.contenido.lista_resultados.lista');
                    $total_ganadores= $total_ganadores + $row['total_premiado'];
                    $i++;
                }
                
                $obj_xtpl->assign('fecha', $fecha);
                $obj_xtpl->assign('total_ganadores', ' El Total de Ganadores fue: Bs. '.$total_ganadores);
                //echo $total_ganadores;
                
                 // Parseo del bloque de la fila
                $obj_xtpl->parse('main.contenido.lista_resultados');
            }else{
                // Mensaje
                $obj_xtpl->assign('no_info',$mensajes['sin_lista']);
//                        // Mensaje
//                        $obj_xtpl->assign('sin_listado',$mensajes['sin_lista']);
//
//                        // Parseo del bloque de la fila
//                        $obj_xtpl->parse('main.contenido.lista_resultados.no_lista');
                }

           
        }
        
        break;

    case 'ver_resultados':

        // Creamos el PDF

   

        //Creación del objeto de la clase heredada
        $pdf=new FPDF();
        
        $pdf->AliasNbPages();
        
        //Primera pagina
        $pdf->AddPage();

        //echo $_GET['fecha'];
        
        $fecha = $_GET['fecha'];


        // Imagen  de encabezado
        $pdf->Image("./images/banner4.jpg" , 10 ,0, 180 ,40  , "JPG" ,"");
        
        // Titulo del Reporte
            $pdf->SetFont('Arial','B',20);
            $pdf->SetY(45);
            //$pdf->Cell(50,10,'Tickets Ganadores a la fecha '.$_GET['fecha']);
            $pdf->Cell(50,10,'Tickets Ganadores a la fecha '.$obj_date->changeFormatDateI($fecha,0));


            
        // Configuracion de colores
            $pdf->SetY(60);
            $pdf->SetFillColor(224,235,255);
            $pdf->SetTextColor(0);
            $pdf->SetDrawColor(128,0,0);
            $pdf->SetLineWidth(.3);
            $pdf->SetFont('','B');

        
         if( $result= $obj_modelo->GetTicketsGanadores($fecha) ){
            if ($obj_conexion->GetNumberRows($result)>0 ){
                // Establecemos la cabecera de la tabla
                $pdf->SetFont('Arial','B',10);
                $pdf->SetTextColor(128,0,0);
                $pdf->Cell(40,7,'Fecha Hora',1,0,'C',true);
                $pdf->Cell(30,7,'Numero Ticket',1,0,'C',true);
                $pdf->Cell(30,7,'Total',1,0,'C',true);
                $pdf->Cell(30,7,'Total Premio',1,0,'C',true);
                $pdf->Cell(30,7,'Pagado',1,0,'C',true);

                $pdf->SetFont('Arial','',8);
                while($row= $obj_conexion->GetArrayInfo($result)){
                    $pdf->Ln();
                    $pdf->SetTextColor(0);
                    $pdf->Cell(40,7,$obj_date->changeFormatDateI($row['fecha_hora'],0),1);
                    $pdf->Cell(30,7,$row['id_ticket'],1,0,'C');
                    $pdf->Cell(30,7,$row['total_ticket'],1,0,'C');
                    $pdf->Cell(30,7,$row['total_premiado'],1,0,'C');
                    if ($row['pagado'] == '1'){
                        $pagado='Si';
                    }else{
                        $pagado='No';
                    }
                    $pdf->Cell(30,7,$pagado,1,0,'C');
                }
            }else{  
                $pdf->SetFont('Arial','B',14);
                $pdf->SetTextColor(0);
                $pdf->SetY(80);
                $pdf->Cell(10,10,'No se han ingresado resultados a la fecha...');
            }

         }
  
        $pdf->Output();

        break;
        case 'ver_detalle':

        // Creamos el PDF

        
        //Creación del objeto de la clase heredada
        $pdf=new FPDF();

        $pdf->AliasNbPages();

        //Primera página
        $pdf->AddPage();

        $id_ticket = $_GET['id_ticket'];


        // Imagen  de encabezado
        $pdf->Image("./images/banner4.jpg" , 10 ,0, 180 ,40  , "JPG" ,"");

        // Titulo del Reporte
            $pdf->SetFont('Arial','B',20);
            $pdf->SetY(45);
            $pdf->Cell(50,10,'Detalle del Ticket No. '.$id_ticket);



        // Configuracion de colores
            $pdf->SetY(60);
            $pdf->SetFillColor(224,235,255);
            $pdf->SetTextColor(0);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetLineWidth(.1);
            $pdf->SetFont('','B');


         if( $result= $obj_modelo->GetDetalleTicket($id_ticket) ){
            if ($obj_conexion->GetNumberRows($result)>0 ){
                // Establecemos la cabecera de la tabla
                $pdf->SetFont('Arial','B',10);
                $pdf->SetTextColor(128,0,0);
                $pdf->Cell(20,7,'Numero',1,0,'C',true);
                $pdf->Cell(40,7,'Sorteo',1,0,'C',true);
                $pdf->Cell(30,7,'Signo',1,0,'C',true);
                $pdf->Cell(30,7,'Monto Jugado',1,0,'C',true);
                $pdf->Cell(30,7,'Monto Ganado',1,0,'C',true);
                $pdf->Cell(35,7,'Apuesta Ganadora',1,0,'C',true);

                $pdf->SetFont('Arial','',8);
                while($row= $obj_conexion->GetArrayInfo($result)){
                	$hora_sorteo= $obj_modelo->GetHoraSorteo($row['id_sorteo']);
                    $pdf->Ln();
                    if ($row['premiado'] == '1'){
                    	$pdf->SetFont('Arial','B',10);
                    	$pdf->SetTextColor(128,0,0);
                    	$premiado='Si';
                    	$monto=$row['total_premiado'];
                    }else{
              			$pdf->SetFont('Arial','',8);
                    	$pdf->SetTextColor(0,0,0);
                    	$premiado='No';
                    	$monto=0;
                    }
                    $pdf->Cell(20,7,$row['numero'],1,0,'C');
                    $pdf->Cell(40,7,$row['nombre_sorteo'],1,0,'C');
                    $pdf->Cell(30,7,$row['nombre_zodiacal'],1,0,'C');
                    $pdf->Cell(30,7,$row['monto'],1,0,'C');
                     $pdf->Cell(30,7,$monto,1,0,'C');
                    
                    $pdf->Cell(35,7,$premiado,1,0,'C');
                }
            }else{
                $pdf->SetFont('Arial','B',14);
                $pdf->SetTextColor(0);
                $pdf->SetY(80);
                $pdf->Cell(10,10,'No existe informacion...');
            }

         }

        $pdf->Output();

        break;
    default:

            // Ruta actual
            $_SESSION['Ruta_Form']= $obj_generico->RutaRegreso();

            $obj_xtpl->assign('fecha', $obj_date->FechaHoy2());
            // Parseo del bloque
            $obj_xtpl->parse('main.contenido.buscar_tickets');

            break;

}
$obj_xtpl->parse('main.contenido');


?>