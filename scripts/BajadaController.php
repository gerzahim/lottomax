<?php 

class BajadaController{

	
	public function __construct() {
		//$this->vConexion= $conexion;
	}
        /**
	 * Obtencion de valor configurado de aproximacion por abajo
 	 * @return integer, -1 Cuando no hay aproximaciones ni arriba ni abajo, 0 cuando hay abajo nada mas, 1 cuando hay tanto arriba como abajo, 2 cuando hay arriba nada mas
	 *
	 */
	public function GetAprox($conexion_abajo){

		//Preparacion del query
        $sql = "SELECT aprox_abajo, aprox_arriba FROM parametros";
		$result= mysql_query($sql,$conexion_abajo);
		
		$roww = mysql_fetch_array($result,$conexion_abajo);
		if ($roww["aprox_abajo"]==0 AND $roww["aprox_arriba"]==0)
		return -1;
		else		
		if ($roww["aprox_abajo"]==1 AND $roww["aprox_arriba"]==0)
		return 0;
		else
		if ($roww["aprox_abajo"]==1 AND $roww["aprox_arriba"]==1)
		return 1;
        else
        if ($roww["aprox_abajo"]==0 AND $roww["aprox_arriba"]==1)
        return 2;

	}
	
	
	

	/**
	 * Obtiene los resultados de acuerdo a una fecha
	 *
	 * @param string $fecha_hora
	 * @return boolean, array
	 */
	public function GetResultados($fecha_hora,$conexion_abajo){
	
		//Preparacion del query
		$sql = "SELECT id_sorteo, zodiacal, numero FROM resultados WHERE fecha_hora LIKE '%".$fecha_hora."%'";
		//echo $sql;
		$result= mysql_query($sql,$conexion_abajo);
	
		return $result;
	}
	
	/**
	 * Busqueda de relacion de pagos
	 *
	 * @return boolean, array
	 */
	public function GetRelacionPagos($id_tipo_jugada,$conexion_abajo){
	
		//Preparacion del query
		$sql = "SELECT monto,id_tipo_jugada FROM relacion_pagos ";
		$result= mysql_query($sql,$conexion_abajo);
		return $result;
	
	}
	
	/**
	 * Busqueda de Tickets Segun fecha.
	 *
	 * @param string $id_ticket
	 * @param string $serial
	 */
	//public function GetListadosegunVariable($parametro_where){
	public function GetListadosegunVariable($fecha_resultado,$conexion_abajo){
		//echo $fecha_resultado;
		//Preparacion del query
		//$sql = "SELECT * FROM ticket WHERE status='1' AND pagado=0 AND ".$parametro_where;
		// deberiamos colocar un parametro premiado=0, verificado=0
		// premiado cambia cuando se premia un ticket
		// verificado cambia cuando ya se reviso y no esta premiado verificado=1
		$sql = "SELECT * FROM ticket WHERE status=1 AND fecha_hora LIKE '%".$fecha_resultado."%'";
		$result= mysql_query($sql,$conexion_abajo);
		return  $result;
	
	}
	
	/**
	 * Busqueda de detalle de Tickets Segun id_ticket
	 *
	 * @param string $id_ticket
	 */
	public function GetAllDetalleTciket($id_ticket,$conexion_abajo){
	
		//Preparacion del query
		$sql = "SELECT *
                        FROM detalle_ticket DT
                        WHERE id_ticket='".$id_ticket."'";
	
		$result= mysql_query($sql,$conexion_abajo);
	
		return $result;
	
	}
	
	/**
	 * Actualiza Datos del ticket en detalle ticket  a premiadoo 1.
	 * @param string $id_detalle_ticket
	 */
	public function PremiarDetalleTicket($id_detalle_ticket, $total_premiado,$conexion_abajo){
	
		//Preparacion del query
	
		$sql = "UPDATE `detalle_ticket` SET `premiado`='1', `total_premiado`='".$total_premiado."' WHERE id_detalle_ticket='".$id_detalle_ticket."'";
		//echo $sql;
		$result= mysql_query($sql,$conexion_abajo);
		return $result;
	}
	
	/**
	 * Quitar Premios a Ticket
	 * @param string $fecha_hora
	 * @return boolean, array
	 */
	public function DespremiarTicket($fecha_hora,$conexion_abajo){
	
		//Preparacion del query
		$sql = "UPDATE `ticket` SET `premiado`=0 WHERE `fecha_hora` LIKE '%".$fecha_hora."%'";
		$result= mysql_query($sql,$conexion_abajo);
		$sql = "UPDATE `detalle_ticket` SET `premiado`='0', WHERE `fecha_sorteo` LIKE '%".$fecha_hora."%'";
		$result= mysql_query($sql,$conexion_abajo);
		return $result;
	}
	
	/**
	 * Actualiza Datos del ticket en premiadoo 1 y el monto total del premio
	 * @param string $id_ticket
	 * @param string $total_premiado
	 */
	public function PremiarTicket($id_ticket, $total_premiado,$conexion_abajo){
	
		//Preparacion del query
		$sql = "UPDATE `ticket` SET `premiado`='1', `total_premiado`='".$total_premiado."' WHERE id_ticket='".$id_ticket."'";
	
		$result= mysql_query($sql,$conexion_abajo);
		return $result;
		
	}
	
}
?>