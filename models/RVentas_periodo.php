<?php

/**
 * Archivo del modelo para modulo reporte de Ventas por periodo
 * @package RVentas_periodo.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Junio - 2013
 */

class RVentas_periodo{

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
	 * Obtiene los datos de taquillas
	 *
	 * @access public
	 * @param integer $id_referencia
	 * @return boolean or array
	 */
	public function GetDatosTaquillas(){

		//Preparacion del query
		$sql = "SELECT * FROM taquillas WHERE status=1";
                return $this->vConexion->ExecuteQuery($sql);
	}

         /**
	 * Busqueda de todos los Sorteos
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetSorteos(){

		//Preparacion del query
		$sql = "SELECT * FROM sorteos WHERE status = 1 ORDER BY zodiacal, hora_sorteo, nombre_sorteo ASC ";
		return $this->vConexion->ExecuteQuery($sql);
	}
        
         /**
	 * Devuelve el listado de Tickets en determinado rango de  fechas
	 *
         * @param string $fecha_desde
         * @param string $fecha_hasta
         * @param string $taquilla
         * @param string $sorteo
	 * @return boolean, array
	 */
	public function GetTickets($fecha_desde, $fecha_hasta, $taquilla, $sorteo){

		//Preparacion del query
                $where = "";
                if(!Empty($taquilla)){
                    $where = $where. " taquilla='".$taquilla."' AND " ;
                }

                if(!Empty($sorteo)){
                     $where = $where. "id_ticket IN (SELECT id_ticket FROM detalle_ticket WHERE id_sorteo='".$sorteo."') AND ";
                }

                if(!Empty($fecha_desde) && !Empty($fecha_hasta)){
                    $where = $where." fecha_hora BETWEEN '".$fecha_desde."' AND '".$fecha_hasta." 23:59:59' AND ";
                }

                $where = substr($where, 0,strlen($where) - 5);

		$sql = "SELECT * FROM  ticket WHERE ".$where. " ORDER BY fecha_hora DESC";
                
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
	
	/**
	 * Devuelve el listado de balance por dia entre dos fechas
	 *
	 * @param string $fecha_desde
	 * @param string $fecha_hasta
	 * @return boolean, array
	 */
	
	public function GetBalancebyTaquilla($fecha_desde, $fecha_hasta, $num_taquilla){
	
		//Preparacion del query
		$where = "";
		if(!Empty($num_taquilla)){
			$where = "taquilla='".$num_taquilla."' AND" ;
		}	
			
	
		$sql = "SELECT LEFT(fecha_hora,10) AS fecha, SUM(total_ticket) AS total_ventas, SUM(total_ticket)* 15 /100 AS comision, SUM(total_premiado) AS total_premiado, SUM(total_ticket)- ((SUM(total_ticket)* 15 /100) + SUM(total_premiado)) AS balance
                        FROM ticket
                        WHERE ".$where." fecha_hora BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."  23:59:59' AND status=1
						GROUP BY LEFT(fecha_hora,10)";
		echo $sql;
	
		$result= $this->vConexion->ExecuteQuery($sql);
		return $result;
	
	
	}

	/**
	 * Busqueda de Id de Taquilla.
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetIdTaquilla(){
	
		//Preparacion del query
		//		$sql = "SELECT * FROM parametros";
		//		$result= $this->vConexion->ExecuteQuery($sql);
		//		$roww= $this->vConexion->GetArrayInfo($result);
		//		return $roww["taquilla"];
		 
		return $_SESSION['taquilla'];
	}
	
	public function GetIdTaquillabyNumero($num_taquilla){
	
		//Preparacion del query
		$sql = "SELECT id_taquilla FROM taquillas WHERE numero_taquilla = ".$num_taquilla."";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["id_taquilla"];
	}
	
	public function lineas_saltar_despues($id_taquilla){
	
		//Preparacion del query
		$sql = "SELECT lineas_saltar_despues FROM impresora_taquillas WHERE id_taquilla = ".$id_taquilla."";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["lineas_saltar_despues"];
	
	}
	
	public function GetDatosImpresora($id_taquilla){
	
		//Preparacion del query
		$sql = "SELECT lineas_saltar_despues, ver_numeros_incompletos, ver_numeros_agotados FROM impresora_taquillas WHERE id_taquilla = ".$id_taquilla."";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww;
	}	
    	
}		
?>