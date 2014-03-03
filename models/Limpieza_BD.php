<?php
class Limpieza_BD{

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
	 * Borra los numeros_jugados que no sean del dia de hoy
	 */
	public function EliminarNumerosJugados($fecha_hoy){
				
		//Preparacion del query
		$sql = "DELETE FROM `numeros_jugados` WHERE fecha NOT LIKE '%".$fecha_hoy."%' ";
		//echo $sql;		
		return $this->vConexion->ExecuteQuery($sql);
		
	}	
	
	/**
	 * Borra los IncompletosAgotados que no sean del dia de hoy
	 */
	public function EliminarIncompletosAgotados($fecha_hoy){
	
		//Preparacion del query
		$sql = "DELETE FROM `incompletos_agotados` WHERE fecha NOT LIKE '%".$fecha_hoy."%' ";
		//echo $sql;
		return $this->vConexion->ExecuteQuery($sql);
	
	}	
		
	
}		
?>