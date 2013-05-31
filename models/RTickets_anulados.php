<?php

/**
 * Archivo del modelo para modulo reporte de Tickets Anulados
 * @package RTickets_anulados.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Mayo - 2013
 */

class RTickets_anulados{

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
	 * Devuelve el listado de Tickets Anulados
	 *
         * @param string $fecha
	 * @return boolean, array
	 */
	public function GetTicketsAnulados($fecha){

		//Preparacion del query
		$sql = "SELECT * FROM  ticket WHERE status='0' AND fecha_hora LIKE '%".$fecha."%'";
                
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
                        WHERE id_ticket='".$id_ticket."'";
                        
		$result= $this->vConexion->ExecuteQuery($sql);
                return $result;


	}
    	
}		
?>