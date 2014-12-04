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
		
		$roww = mysql_fetch_array($result);
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
	 * Obtiene los resultados de acuerdo a una fecha
	 *
	 * @param string $fecha_hora
	 * @return boolean, array
	 */
	public function GetResultadosRepetidos($id_sorteo, $zodiacal, $numero, $fecha_hora,$conexion_abajo){
	
		//Preparacion del query
		$sql = "SELECT id_sorteo, zodiacal, numero FROM resultados WHERE fecha_hora LIKE '%".$fecha_hora."%' AND id_sorteo=".$id_sorteo;
		//echo $sql;
		$result= mysql_query($sql,$conexion_abajo);
	
		return $result;
	}
	
	/**
	 * Busqueda de relacion de pagos
	 *
	 * @return boolean, array
	 */
	public function GetRelacionPagos($obj_conexion){
	
		//Preparacion del query
		$sql = "SELECT monto, id_tipo_jugada, id_agencia FROM relacion_pagos ";
		$result= mysql_query($sql,$obj_conexion);
		return $result;
	
	}
	
	/**
	 * Busqueda de Tickets Segun fecha.
	 *
	 * @param string $id_ticket
	 * @param string $serial
	 */
	//public function GetListadosegunVariable($parametro_where){
	public function GetListadosegunVariable($fecha_resultado,$conexion_abajo,$fecha_actual){
		//echo $fecha_resultado;
		//Preparacion del query
		//$sql = "SELECT * FROM ticket WHERE status='1' AND pagado=0 AND ".$parametro_where;
		// deberiamos colocar un parametro premiado=0, verificado=0
		// premiado cambia cuando se premia un ticket
		// verificado cambia cuando ya se reviso y no esta premiado verificado=1
		echo "Fecha resulta".$fecha_resultado;
		echo "Fecha actual".$fecha_actual;
		
		if($fecha_resultado==$fecha_actual)
			$sql="	SELECT * FROM ticket_diario WHERE status=1 AND fecha_hora LIKE '%".$fecha_resultado."%'";
		else
			$sql = "SELECT * FROM ticket WHERE status=1 AND fecha_hora LIKE '%".$fecha_resultado."%'";
		
		
		
		echo "<br>".$sql;
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
		$total_registros= mysql_num_rows($result);
		if($total_registros==0){
			$sql = "SELECT *
                        FROM detalle_ticket_diario DT
                        WHERE id_ticket_diario='".$id_ticket."'";
			$result= mysql_query($sql,$conexion_abajo);
		}	
		return $result;
	}
	/**
	 * Actualiza Datos del ticket en detalle ticket  a premiadoo 1.
	 * @param string $id_detalle_ticket
	 */
	public function PremiarDetalleTicket($id_detalle_ticket, $total_premiado,$conexion_abajo){
		//Preparacion del query
		$sql = "UPDATE `detalle_ticket` SET `premiado`='1', `total_premiado`='".$total_premiado."' WHERE id_detalle_ticket='".$id_detalle_ticket."'";
		echo "<br>".$sql;
		$result= mysql_query($sql,$conexion_abajo);
		
		print_r($result);
	//	echo "<br>result".$result;
		if(mysql_affected_rows()==0){
			$sql = "UPDATE `detalle_ticket_diario` SET `premiado`='1', `total_premiado`='".$total_premiado."' WHERE id_detalle_ticket_diario='".$id_detalle_ticket."'";
			echo "<br> ".$sql;
			$result= mysql_query($sql,$conexion_abajo);
		}
		return $result;
	}
	
	/**
	 * Quitar Premios a Ticket
	 * @param array $resultados, $conexion_abajo
	 * @return boolean, array
	 */
	public function DespremiarTicket($resultados,$conexion_abajo){
		$fecha_ante='';
		$fecha_ante2='';
		$fecha_actual=date('Y-m-d');
		foreach ($resultados as $key => $rs){
			$aux=preg_split("/\//",$key);
			
			if($fecha_ante2=='' AND $fecha_actual==$aux[1])
			$sql2 = "SELECT * FROM `detalle_ticket_diario` WHERE `premiado`=1 AND (`fecha_sorteo` LIKE '%".$aux[1]."%' AND (`id_sorteo` =".$aux[0];
			else
			if($aux[1]==$fecha_ante2 AND $fecha_actual==$aux[1])
			$sql2.=" OR `id_sorteo` = ".$aux[0];
			elseif($fecha_actual==$aux[1])
			$sql2.=") OR (`fecha_sorteo` LIKE '%".$aux[1]."%' AND `id_sorteo` =".$aux[0];
			if($fecha_ante=='' AND $fecha_actual!=$aux[1])
			$sql = "SELECT * FROM `detalle_ticket` WHERE `premiado`=1 AND (`fecha_sorteo` LIKE '%".$aux[1]."%' AND (`id_sorteo` =".$aux[0];
			else
			if($aux[1]==$fecha_ante AND $fecha_actual!=$aux[1])
			$sql.=" OR `id_sorteo` = ".$aux[0];
			elseif($fecha_actual!=$aux[1])
			$sql.=") OR (`fecha_sorteo` LIKE '%".$aux[1]."%' AND `id_sorteo` =".$aux[0];
			if($fecha_actual!=$aux[1])
			$fecha_ante=$aux[1];
			if($fecha_actual==$aux[1])
			$fecha_ante2=$aux[1];
		}
		if(!empty($sql))
		$sql.="))";
		if(!empty($sql2))
		$sql2.="))";
		
	/*	echo "<br> SQL TICKET".$sql;
		echo "<br> SQL TICKET_DIARIO".$sql2;
		*/
		if(!empty($sql))
		{
		$result=mysql_query($sql,$conexion_abajo);
		while($row=mysql_fetch_array($result)){
			$sql = "SELECT * FROM `ticket` WHERE `id_ticket` = ".$row['id_ticket'];
			$result2=mysql_query($sql,$conexion_abajo);
			while($row2=mysql_fetch_array($result2)){
				$total_premiado=$row2['total_premiado']-$row['total_premiado'];
				if($total_premiado==0)
					$premiado=0;
				else
					$premiado=1;
				$sql = "UPDATE `ticket` SET `premiado`=".$premiado.", `total_premiado`=".$total_premiado." WHERE `id_ticket`=".$row['id_ticket'];
				mysql_query($sql,$conexion_abajo);
				$sql = "UPDATE `detalle_ticket` SET `premiado`=0, `total_premiado`=0 WHERE `id_detalle_ticket`=".$row['id_detalle_ticket'];
				mysql_query($sql,$conexion_abajo);
			}
		}
		}
		
		
		if(!empty($sql2))
		{
		$result=mysql_query($sql2,$conexion_abajo);
		while($row=mysql_fetch_array($result)){
			$sql = "SELECT * FROM `ticket_diario` WHERE `id_ticket_diario` = ".$row['id_ticket_diario'];
			$result2=mysql_query($sql,$conexion_abajo);
			while($row2=mysql_fetch_array($result2)){
				$total_premiado=$row2['total_premiado']-$row['total_premiado'];
				if($total_premiado==0)
					$premiado=0;
				else
					$premiado=1;
				$sql = "UPDATE `ticket_diario` SET `premiado`=".$premiado.", `total_premiado`=".$total_premiado." WHERE `id_ticket_diario`=".$row['id_ticket_diario'];
				mysql_query($sql,$conexion_abajo);
				$sql = "UPDATE `detalle_ticket_diario` SET `premiado`=0, `total_premiado`=0 WHERE `id_detalle_ticket_diario`=".$row['id_detalle_ticket_diario'];
				mysql_query($sql,$conexion_abajo);
			}
		}
		}
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
		echo "<br>Sql ".$sql;
		if(mysql_affected_rows()==0){
			$sql = "UPDATE `ticket_diario` SET `premiado`='1', `total_premiado`='".$total_premiado."' WHERE id_ticket_diario='".$id_ticket."'";
			$result= mysql_query($sql,$conexion_abajo);
			echo "<br>Sql ".$sql;
		}
		return $result;
		
	}
	
}
?>