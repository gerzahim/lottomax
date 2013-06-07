<?php

/**
 * Archivo del modelo para modulo reporte de Cuadre con Banca
 * @package RCuadre_banca.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Junio - 2013
 */

class RCuadre_banca{

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
	 * Devuelve el listado de balance por dia entre dos fechas
	 *
         * @param string $fecha_desde
         * @param string $fecha_hasta
	 * @return boolean, array
	 */
	public function GetBalance($fecha_desde, $fecha_hasta){

		//Preparacion del query
               
               

		$sql = "SELECT LEFT(fecha_hora,10) AS fecha, SUM(total_ticket) AS total_ventas, SUM(total_ticket)* 15 /100 AS comision, SUM(total_premiado) AS total_premiado, SUM(total_ticket)- ((SUM(total_ticket)* 15 /100) + SUM(total_premiado)) AS balance
                        FROM ticket
                        WHERE fecha_hora BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."' 
                        GROUP BY LEFT(fecha_hora,10)";
                
		$result= $this->vConexion->ExecuteQuery($sql);
                return $result;
		
		
	}      
}		
?>