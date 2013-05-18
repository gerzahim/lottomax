<?php
/**
 * Archivo del controlador para modulo Reoprte Numeros Agotados
 * @package cRNumeros_agotados.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Mayo - 2013
 */

// Vista asignada
$obj_xtpl->assign_file('contenido', $obj_config->GetVar('ruta_vista').'Rnumeros_agotados'.$obj_config->GetVar('ext_vista'));

// Modelo asignado
require($obj_config->GetVar('ruta_modelo').'RNumeros_agotados.php');

$obj_modelo= new RNumeros_agotados($obj_conexion);

require('/fpdf/fpdf.php');

switch (ACCION){

    case 'ver_numeros':

        // Creamos el PDF

   

        //Creación del objeto de la clase heredada
        $pdf=new FPDF();
        
        $pdf->AliasNbPages();
        
        //Primera página
        $pdf->AddPage();

        $fecha = $_GET['txt_fecha'];


        // Imagen  de encabezado
        $pdf->Image("./images/banner4.jpg" , 0 ,0, 200 ,40  , "JPG" ,"");
        
        // Titulo del Reporte
            $pdf->SetFont('Arial','B',20);
            $pdf->SetY(45);
            $pdf->Cell(50,10,'Numeros Agotados a la fecha '.$fecha);


            
        // Configuracion de colores
            $pdf->SetY(60);
            $pdf->SetFillColor(224,235,255);
            $pdf->SetTextColor(0);
            $pdf->SetDrawColor(128,0,0);
            $pdf->SetLineWidth(.3);
            $pdf->SetFont('','B');

        
         if( $result= $obj_modelo->GetNumeros($fecha) ){
            if ($obj_conexion->GetNumberRows($result)>0 ){
                // Establecemos la cabecera de la tabla
                $pdf->SetFont('Arial','B',16);
                $pdf->SetTextColor(128,0,0);
                $pdf->Cell(60,7,'Sorteo',1,0,'C',true);
                $pdf->Cell(40,7,'Hora sorteo',1,0,'C',true);
                $pdf->Cell(40,7,'Signo',1,0,'C',true);
                $pdf->Cell(40,7,'Numero',1,0,'C',true);

                $pdf->SetFont('Arial','',12);
                while($row= $obj_conexion->GetArrayInfo($result)){
                    $pdf->Ln();
                    $pdf->SetTextColor(0);
                    $pdf->Cell(60,7,$row['nombre_sorteo'],1);
                    $pdf->Cell(40,7,$row['hora_sorteo'],1,0,'C');
                    $pdf->Cell(40,7,$row['nombre_zodiacal'],1,0,'C');
                    $pdf->Cell(40,7,$row['numero'],1,0,'C');
                }
            }else{  
                $pdf->SetFont('Arial','B',14);
                $pdf->SetTextColor(0);
                $pdf->SetY(80);
                $pdf->Cell(10,10,'No hay numeros agotados a la fecha...');
            }

         }
  
        $pdf->Output();

        break;
    
    default:

            // Ruta actual
            $_SESSION['Ruta_Lista']= $obj_generico->RutaRegreso();

            $obj_xtpl->assign('fecha', date('Y-m-d'));
            // Parseo del bloque
            $obj_xtpl->parse('main.contenido.buscar_numeros');

            break;

}
$obj_xtpl->parse('main.contenido');


?>