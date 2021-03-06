<?php

/**
 * Archivo del modelo para modulo de Anular Ticket
 * @package AnularTicket.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Abril - 2013
 */

class AnularTicket{

	/**
	 * Objeto de la conexion.
	 *
	 * @var object $vConexion
	 */
	private $vConexion="";
	
	
	/**
	 * Constructor de la clase. Instancia.
	 *
	 * @param object $conexion
	 */
	public function __construct($conexion="") {
		$this->vConexion= $conexion;
	}

         
	/**
	 * Obtiene todos los datos de tickets
	 *
	 * @param string $cantidad
	 * @param string $pagina
	 * @return boolean, array
	 */
	public function GetListado($cantidad, $pagina,$tipo_servidor){
		if($tipo_servidor==1 OR $tipo_servidor==2)
			$ticket= "ticket";
		else
			$ticket= "ticket_diario";
		// Datos para la paginacion
		$inicial= ($pagina-1) * $cantidad;
		
		//Preparacion del query
		$sql = "SELECT * FROM ".$ticket." WHERE status='1' AND taquilla='".$_SESSION['InfoLogin']->GetTaquilla()."' AND premiado=0 AND pagado=0 AND fecha_hora LIKE '%".Date('Y-m-d')."%' ORDER BY fecha_hora DESC";
		//echo $sql;
		$result= $this->vConexion->ExecuteQuery($sql);
		
		
		// Datos para la paginacion
		$total_registros= $this->vConexion->GetNumberRows($result);
		if($cantidad!=0){
			$total_paginas= ceil($total_registros / $cantidad);
		}
		else{
			$total_paginas= 0;
		}
		
		// Nuevo SQL
		$sql.=" LIMIT ".$cantidad." OFFSET ".$inicial."";
		$result=  $this->vConexion->ExecuteQuery($sql);
		
		return array('pagina'=>$pagina,'total_paginas'=>$total_paginas,'total_registros'=>$total_registros,'result'=>$result);
		
	}

       
        /**
	 * Eliminar Ticket
	 *
	 * @param string $id_ticket
	 * @return boolean, array
	 */
	public function EliminarTicket($id_ticket,$tipo_servidor){
		if($tipo_servidor==1 OR $tipo_servidor==2)
			$ticket= "ticket";
		else
			$ticket= "ticket_diario";
			
		//Preparacion del query
		//$sql = "DELETE FROM `ticket` WHERE id_ticket='".$id_ticket."'";
		
		$sql = "SELECT subido FROM ".$ticket." WHERE id_".$ticket."='".$id_ticket."'";
		$result=$this->vConexion->ExecuteQuery($sql);
		$row= $this->vConexion->GetArrayInfo($result);
		if($row['subido']==0)
		$adicional="";
		else
		$adicional=", subido = 2";
        $sql = "UPDATE ".$ticket." SET status='0', fecha_hora_anulacion='".Date('Y-m-d H:i:s')."', taquilla_anulacion='".$_SESSION['taquilla']."' ".$adicional." WHERE id_".$ticket."='".$id_ticket."'";
        $this->vConexion->ExecuteQuery($sql);
        $sql = "UPDATE detalle_".$ticket." SET status=0 WHERE id_".$ticket."=".$id_ticket;
        return $this->vConexion->ExecuteQuery($sql);
		//return 1;
	}

	
	/**
	 * Reestablecer Imcompletos y Numeros Jugados
	 *
	 * @param string $id_ticket
	 * @return boolean, array
	 */
	public function ReestablecerImcompletosyJugados($id_ticket,$fecha_hora,$tipo_servidor){
		if($tipo_servidor==1 OR $tipo_servidor==2)
			$ticket= "ticket";
		else
			$ticket= "ticket_diario";
		//Preparacion del query
        $sql = "SELECT * FROM detalle_".$ticket." WHERE id_".$ticket."='".$id_ticket."'";
		$result= $this->vConexion->ExecuteQuery($sql);
		$total_registros= $this->vConexion->GetNumberRows($result);
		while($row= $this->vConexion->GetArrayInfo($result)){
			$numero=$row['numero'];
			$id_sorteo=$row['id_sorteo'];
			$id_zodiacal=$row['id_zodiacal'];
			$monto=$row['monto'];
			$id_tipo_jugada=$row['id_tipo_jugada'];
			
			$sql = "SELECT DT.id_detalle_".$ticket.", DT.monto_restante, TD.fecha_hora FROM detalle_".$ticket." DT 
					INNER JOIN ".$ticket." TD ON DT.id_".$ticket." = TD.id_".$ticket."
					WHERE DT.numero='".$numero."' 
					AND DT.id_sorteo='".$row['id_sorteo']."' AND  DT.id_zodiacal='".$row['id_zodiacal']."' 
					AND DT.id_tipo_jugada ='".$row['id_tipo_jugada']."' AND DT.id_".$ticket." <> '".$id_ticket."'";
			//echo "<br>".$sql;
			//exit;
			$result2= $this->vConexion->ExecuteQuery($sql);
			$total_registros= $this->vConexion->GetNumberRows($result2);
			if($total_registros>0){
				while($roww= $this->vConexion->GetArrayInfo($result2)){
					if($roww['fecha_hora']>$fecha_hora)
					{
						$monto_restante=$roww['monto_restante']+$monto;
						$sql="UPDATE detalle_".$ticket." SET monto_restante ='".$monto_restante."' WHERE id_detalle_".$ticket."='".$roww["id_detalle_".$ticket]."'";
						$result3= $this->vConexion->ExecuteQuery($sql);					
					}
				}
				$sql="UPDATE detalle_".$ticket." SET status =0 WHERE id_detalle_".$ticket."='".$row["id_detalle_".$ticket]."'";
				$result4= $this->vConexion->ExecuteQuery($sql);
			}
			
					
			
		}
		
		//return $this->vConexion->ExecuteQuery($sql);
	
	}
	/**
	 * Obtiene el tipo de servidor donde esta alojado el sistema
	 *
	 *
	 * @return integer
	 */
	public function GetTipoServidor(){
		//Preparacion del query
		$sql = "SELECT tipo_servidor FROM parametros";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["tipo_servidor"];
	}

        /**
	 * Busqueda de Tickets Segun parametro.
	 *
	 * @param string $id_ticket
         * @param string $serial
	 */
	public function GetListadosegunVariable($parametro_where,$tipo_servidor){
		if($tipo_servidor==1 OR $tipo_servidor==2)
			$ticket= "ticket";
		else
			$ticket= "ticket_diario";
		//Preparacion del query
		$sql = "SELECT * FROM ".$ticket." WHERE status='1' AND taquilla='".$_SESSION['taquilla']."' AND premiado=0 AND pagado=0 AND ".$parametro_where;
		$result= $this->vConexion->ExecuteQuery($sql);
		return  $result;
	}

        /**
	 * Busqueda de minutos antes de anular un ticket
	 *
	 */
	public function MinutosAnulacion(){

		//Preparacion del query
		$sql = "SELECT tiempo_anulacion_ticket FROM parametros";

		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["tiempo_anulacion_ticket"];

	}

        /**
	 * Busqueda de la fecha de un Ticket
	 *
	 * @param string $id_ticket
         * @param string $serial
	 */
	public function GetFechaTicket($id_ticket,$tipo_servidor){
			if($tipo_servidor==1 OR $tipo_servidor==2)
				$ticket= "ticket";
			else
				$ticket= "ticket_diario";
        $sql = "SELECT fecha_hora FROM ".$ticket." WHERE status='1' AND id_".$ticket."='".$id_ticket."'";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["fecha_hora"];
	}
        /**
	 * Valida que los sorteos de un Ticket no se han cerrado
	 *
	 * @param string $id_ticket
         * @param string $serial
	 */
	public function ValidaSorteosTicket($id_ticket,$tipo_servidor){
		if($tipo_servidor==1 OR $tipo_servidor==2)
			$ticket= "ticket";
		else
			$ticket= "ticket_diario";
		//Preparacion del query
                $sql = "SELECT id_sorteo FROM detalle_".$ticket." WHERE id_".$ticket."='".$id_ticket."'";
                 
				$result= $this->vConexion->ExecuteQuery($sql);
		        $total_registros= $this->vConexion->GetNumberRows($result);
                $flag=false;
                             
		if( $total_registros >0 ){
                    $hora_actual= strtotime(date('H:i:s'));
                    while ($roww= $this->vConexion->GetArrayInfo($result)){
                    	
                    	$sql = "SELECT hora_sorteo FROM sorteos WHERE status = 1 AND id_sorteo  = ".$roww["id_sorteo"]."";
                    	$result= $this->vConexion->ExecuteQuery($sql);
                    	$row= $this->vConexion->GetArrayInfo($result);
                    	
                        if ($hora_actual>strtotime($row["hora_sorteo"])){
                            $flag=true;
                        }
                    }
                }
		return $flag;

	}
        
}		
?>