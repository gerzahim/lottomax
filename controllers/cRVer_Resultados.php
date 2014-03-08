<?php
/**
 * Archivo del controlador para modulo Reoprte Ver Resultados
 * @package cRVer_Resultados.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Mayo - 2013
 */

// Vista asignada
$obj_xtpl->assign_file('contenido', $obj_config->GetVar('ruta_vista').'Rver_resultados'.$obj_config->GetVar('ext_vista'));

// Modelo asignado
require($obj_config->GetVar('ruta_modelo').'RVer_resultados.php');

$obj_modelo= new RVer_Resultados($obj_conexion);
$obj_date= new Fecha();

require('./fpdf/fpdf.php');

switch (ACCION){

    case 'listar_resultados':
        $fecha = $obj_date->changeFormatDateII($_GET['txt_fecha']);
        $obj_xtpl->assign('fecha', $obj_date->changeFormatDateI($fecha, 0));
        // Ruta actual
        $_SESSION['Ruta_Lista']= $obj_generico->RutaRegreso();

        // Ruta regreso
        $obj_xtpl->assign('ruta_regreso', $_SESSION['Ruta_Form']);
        
        $i=0;
        if( $result= $obj_modelo->GetResultados($fecha) ){
            if ($obj_conexion->GetNumberRows($result)>0 ){
                
                while($row= $obj_conexion->GetArrayInfo($result)){
                    if( ($i % 2) >0){
                            $obj_xtpl->assign('estilo_fila', 'even');
                    }
                    else{
                            $obj_xtpl->assign('estilo_fila', 'odd');
                    }

                    $obj_xtpl->assign('nombre_sorteo', $row['nombre_sorteo']);
                    $obj_xtpl->assign('hora_sorteo', $row['hora_sorteo']);
                    $obj_xtpl->assign('nombre_zodiacal', $row['nombre_zodiacal']);
                    $obj_xtpl->assign('numero', $row['numero']);

                    // Parseo del bloque de la fila
                    $obj_xtpl->parse('main.contenido.lista_resultados.lista');
                    $i++;
                }

            }else{
                        // Mensaje
                        $obj_xtpl->assign('sin_listado',$mensajes['sin_lista']);

                        // Parseo del bloque de la fila
                        $obj_xtpl->parse('main.contenido.lista_resultados.no_lista');
                }

            
        }
        $num_s = $obj_modelo->GetNumSoteos();
        $obj_xtpl->assign('faltantes', '<span class="requerido">Faltan <b>'.($num_s-$i).'</b> de <b>'.$num_s.' Sorteos</b> por ingresar resultados...</span>');

        // Parseo del bloque de la fila
       $obj_xtpl->parse('main.contenido.lista_resultados');
        break;

    case 'ver_resultados':

        // Creamos el PDF

        //Creacion del objeto de la clase heredada
        $pdf=new FPDF();
        
        $pdf->AliasNbPages();
        
        //Primera pagina
        $pdf->AddPage();

         $fecha = $obj_date->changeFormatDateII($_GET['fecha']);


        // Imagen  de encabezado
        //$pdf->Image("./images/banner4.jpg" , 0 ,0, 200 ,40  , "JPG" ,"");
        $pdf->Image("./images/banner4.jpg" , 10 ,0, 180 ,40  , "JPG" ,"");
        
        // Titulo del Reporte
            $pdf->SetFont('Arial','B',20);
            $pdf->SetY(45);
            $pdf->Cell(50,10,'Resultados a la fecha '.$obj_date->changeFormatDateI($fecha,0));


            
        // Configuracion de colores
            $pdf->SetY(60);
            $pdf->SetFillColor(224,235,255);
            $pdf->SetTextColor(0);
            $pdf->SetDrawColor(128,0,0);
            $pdf->SetLineWidth(.3);
            $pdf->SetFont('','B');

        
         if( $result= $obj_modelo->GetResultados($fecha) ){
            if ($obj_conexion->GetNumberRows($result)>0 ){
                // Establecemos la cabecera de la tabla
                $pdf->SetFont('Arial','B',16);
                $pdf->SetTextColor(128,0,0);
                $pdf->Cell(60,7,'Sorteo',1,0,'C',true);
                //$pdf->Cell(40,7,'Hora sorteo',1,0,'C',true);
                $pdf->Cell(40,7,'Signo',1,0,'C',true);
                $pdf->Cell(40,7,'Numero',1,0,'C',true);

                $pdf->SetFont('Arial','',12);
                while($row= $obj_conexion->GetArrayInfo($result)){
                    $pdf->Ln();
                    $pdf->SetTextColor(0);
                    $pdf->Cell(60,7,$row['nombre_sorteo'],1);
                    //$pdf->Cell(40,7,$row['hora_sorteo'],1,0,'C');
                    $pdf->Cell(40,7,$row['nombre_zodiacal'],1,0,'C');
                    $pdf->Cell(40,7,$row['numero'],1,0,'C');
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
    
    default:

            // Ruta actual
            $_SESSION['Ruta_Form']= $obj_generico->RutaRegreso();
		
            $obj_xtpl->assign('fecha', $obj_date->FechaHoy2());
            
            
            
            $ayer= date('d/m/Y', strtotime('-1 day')) ;
            if( date ( 'l' , strtotime($ayer ))=='Sunday')
            $ayer= date('d/m/Y', strtotime('-1 day')) ;
            $obj_xtpl->assign('ruta_ayer', $obj_generico->RutaRegreso()."&btnentrar=Ver&accion=listar_resultados&txt_fecha=".$ayer);
             
            // Parseo del bloque
            $obj_xtpl->parse('main.contenido.buscar_resultados');

            break;

}
$obj_xtpl->parse('main.contenido');


?>