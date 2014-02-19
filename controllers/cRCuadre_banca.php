<?php
/**
 * Archivo del controlador para modulo de Reporte de Cuadre con Banca
 * @package cRCuadre_banca.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Junio - 2013
 */

// Vista asignada
$obj_xtpl->assign_file('contenido', $obj_config->GetVar('ruta_vista').'RCuadre_banca'.$obj_config->GetVar('ext_vista'));

// Modelo asignado
require($obj_config->GetVar('ruta_modelo').'RCuadre_banca.php');

$obj_modelo= new RCuadre_banca($obj_conexion);
$obj_date= new Fecha();
$comision=$obj_modelo->GetComision();

require('./fpdf/fpdf.php');

switch (ACCION){

    case 'listar_resultados':

        
        
        // Ruta actual
        $_SESSION['Ruta_Lista']= $obj_generico->RutaRegreso();

        // Ruta regreso
        $obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Form']);
        
        $fecha_desde= $obj_generico->CleanText($_GET['fechadesde']);
        $fecha_hasta= $obj_generico->CleanText($_GET['fechahasta']);

        $obj_xtpl->assign('fechadesde', $fecha_desde);
        $obj_xtpl->assign('fechahasta', $fecha_hasta);
		
       	$fecha_desde=$obj_date->changeFormatDateII($fecha_desde);
       	$fecha_hasta=$obj_date->changeFormatDateII($fecha_hasta);
       	
        $i=0; 
        if( $result= $obj_modelo->GetBalance($fecha_desde, $fecha_hasta,$comision) ){
            if ($obj_conexion->GetNumberRows($result)>0 ){
                
                while($row= $obj_conexion->GetArrayInfo($result)){
 
                    if( ($i % 2) >0){
                        if ($row['balance']<0){
                            $obj_xtpl->assign('estilo_fila', 'evenred');
                        }else{
                            $obj_xtpl->assign('estilo_fila', 'even');
                        }
                    }
                    else{
                         if ($row['balance']<0){
                            $obj_xtpl->assign('estilo_fila', 'oddred');
                         }else{
                            $obj_xtpl->assign('estilo_fila', 'odd');
                         }
                    }

                    $obj_xtpl->assign('fecha', $obj_date->changeFormatDateI($row['fecha'], 0));
                    $obj_xtpl->assign('total_ventas', number_format($row['total_ventas'], 2) );
                    $obj_xtpl->assign('comision', number_format($row['comision'],2));
                    $obj_xtpl->assign('total_premiado', number_format($row['total_premiado'],2));
                    $obj_xtpl->assign('balance', number_format($row['balance'],2));

                    // Parseo del bloque de la fila
                    $obj_xtpl->parse('main.contenido.lista_resultados.lista');
                    $i++;
                   
                }

                
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
        
        //Primera página
        $pdf->AddPage();

        $fecha_desde= $obj_generico->CleanText($_GET['fechadesde']);
        $fecha_hasta= $obj_generico->CleanText($_GET['fechahasta']);
        
        $fecha_desde=$obj_date->changeFormatDateII($fecha_desde);
        $fecha_hasta=$obj_date->changeFormatDateII($fecha_hasta);
        
        
        
        // Imagen  de encabezado
        $pdf->Image("./images/banner4.jpg" , 10 ,0, 180 ,40  , "JPG" ,"");
        
        // Titulo del Reporte
            $pdf->SetFont('Arial','B',20);
            $pdf->SetY(45);
            $pdf->Cell(50,10,'Cuadre con Banca desde '.$obj_date->changeFormatDateI($fecha_desde, 0).' hasta '.$obj_date->changeFormatDateI($fecha_hasta, 0));


            
        // Configuracion de colores
            $pdf->SetY(60);
            $pdf->SetFillColor(224,235,255);
            $pdf->SetTextColor(0);
            $pdf->SetDrawColor(128,0,0);
            $pdf->SetLineWidth(.3);
            $pdf->SetFont('','B');

        
         if( $result= $obj_modelo->GetBalance($fecha_desde, $fecha_hasta,$comision)){
            if ($obj_conexion->GetNumberRows($result)>0 ){
                // Establecemos la cabecera de la tabla
                $pdf->SetFont('Arial','B',10);
                $pdf->SetTextColor(128,0,0);
                $pdf->Cell(40,7,'Fecha',1,0,'C',true);
                $pdf->Cell(30,7,'Total Ventas',1,0,'C',true);
                $pdf->Cell(30,7,'Comision',1,0,'C',true);
                $pdf->Cell(30,7,'Total Premiados',1,0,'C',true);
                $pdf->Cell(30,7,'Balance',1,0,'C',true);

                $pdf->SetFont('Arial','',8);
               
              
                while($row= $obj_conexion->GetArrayInfo($result)){
                        
                        $pdf->Ln();
                        if ($row['balance']<0){
                          $pdf->SetTextColor(255,0,0);
                           $pdf->SetFont('Arial','B',8);
                        }else{
                            $pdf->SetTextColor(0);
                             $pdf->SetFont('Arial','',8);
                        }
                        
                        
                        $pdf->Cell(40,7,$obj_date->changeFormatDateI($row['fecha'], 0),1,0,'C');
                        $pdf->Cell(30,7,number_format($row['total_ventas'],2), 1,0,'C');
                        $pdf->Cell(30,7,number_format($row['comision'],2), 1,0,'C');
                        $pdf->Cell(30,7,number_format($row['total_premiado'],2), 1,0,'C');
                        $pdf->Cell(30,7,number_format($row['balance'],2), 1,0,'C');
                    
                }
                
            }else{  
                $pdf->SetFont('Arial','B',14);
                $pdf->SetTextColor(0);
                $pdf->SetY(80);
                $pdf->Cell(10,10,'No hay informacion');
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