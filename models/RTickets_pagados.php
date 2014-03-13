<?php

/**
 * Archivo del modelo para modulo reporte de Tickets Pagados
 * @package RTickets_pagados.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Mayo - 2013
 */

class RTickets_pagados{

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
	 * Devuelve el listado de Tickets Ganadores
	 *
         * @param string $fecha
	 * @return boolean, array
	 */
	public function GetTicketsGanadores($fecha){

		//Preparacion del query
		$sql = "SELECT * FROM  ticket WHERE premiado='1' AND pagado='1' AND fecha_hora LIKE '%".$fecha."%'";        
		$result= $this->vConexion->ExecuteQuery($sql);
                return $result;
		
		
	}

        /**
	 * Devuelve el detalle de jugadas de algun ticket
	 *
         * @param string $id_ticket
	 * @return boolean, array
	 */
	public function GetDetalleTicket($id_ticket){

		//Preparacion del query
		$sql = "SELECT DT.*, S.nombre_sorteo, Z.nombre_zodiacal
                        FROM  detalle_ticket DT
                        INNER JOIN sorteos S ON S.id_sorteo=DT.id_sorteo
                        INNER JOIN zodiacal Z ON Z.Id_zodiacal=DT.id_zodiacal
                        WHERE id_ticket='".$id_ticket."' AND monto <> 0";
				                        
		$result= $this->vConexion->ExecuteQuery($sql);
                return $result;


	}
	/**
	 * Obtiene el hora del Sorteo Segun ID
	 *
	 * @param string $id
	 * @return boolean, array
	 */
	public function GetHoraSorteo($id){
	
		//Preparacion del query
		$sql = "SELECT hora_sorteo FROM sorteos WHERE status = 1 AND id_sorteo  = ".$id."";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["hora_sorteo"];
	
	}
    	
}		
?>