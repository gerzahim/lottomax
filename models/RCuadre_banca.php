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

	// CABLEADO DE AGENCIA 1
	/**
	 * Devuelve el porcentaje de comisión de la agencia
	 *
	 * @return integer, comision
	 */
	public function GetComision(){
	
		//Preparacion del query
		 
		$sql = "SELECT comision_agencia FROM parametros WHERE id_agencia=1";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww['comision_agencia'];
	}    
	
	
         /**
	 * Devuelve el listado de balance por dia entre dos fechas
	 *
         * @param string $fecha_desde
         * @param string $fecha_hasta
         * @param integer $comision
	 * @return boolean, array
	 */
	public function GetBalance($fecha_desde, $fecha_hasta,$comision){

		//Preparacion del query
               
               

		$sql = "SELECT LEFT(fecha_hora,10) AS fecha, SUM(total_ticket) AS total_ventas, SUM(total_ticket)* ".$comision." /100 AS comision, SUM(total_premiado) AS total_premiado, SUM(total_ticket)- ((SUM(total_ticket)* ".$comision." /100) + SUM(total_premiado)) AS balance
                        FROM ticket
                        WHERE status='1' AND fecha_hora BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."  23:59:59'
                        GROUP BY LEFT(fecha_hora,10)";
		//echo $sql;
		
		$result= $this->vConexion->ExecuteQuery($sql);
                return $result;
		
		
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
		 //PRNDIENTE ESTE NO TOMA EN CUENTA LOS ANULADOS		 
	
		$sql = "SELECT LEFT(fecha_hora,10) AS fecha, SUM(total_ticket) AS total_ventas, SUM(total_ticket)* 15 /100 AS comision, SUM(total_premiado) AS total_premiado, SUM(total_ticket)- ((SUM(total_ticket)* 15 /100) + SUM(total_premiado)) AS balance
                        FROM ticket
                        WHERE `taquilla`='".$num_taquilla."' AND fecha_hora BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."  23:59:59' AND status=1				
						GROUP BY LEFT(fecha_hora,10)";
		//echo $sql;
		
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