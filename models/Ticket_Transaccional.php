<?php
class Ticket_Transaccional{

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
	 * Busqueda de Todos los Usuarios.
	 *
	 * @param string $usuario
	 * @param string $clave
	 * @return boolean, array
	 */
	public function GetListado($cantidad, $pagina, $id_taquilla){
		
		// Datos para la paginacion
		$inicial= ($pagina-1) * $cantidad;
		
		//Preparacion del query
		//$sql = "SELECT * FROM sorteos WHERE status <> 0";
		$sql = "SELECT * FROM ticket_transaccional WHERE id_taquilla=".$id_taquilla." ORDER BY numero, id_sorteo, id_zodiacal";
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


        /**
	 * Busqueda de todos los zodiacales
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetZodiacales(){

		//Preparacion del query
		$sql = "SELECT * FROM zodiacal WHERE nombre_zodiacal <>'No Zodiacal' ORDER BY Id_zodiacal ASC ";
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
		$sql = "SELECT * FROM sorteos WHERE status = 1 AND zodiacal = 0 ORDER BY hora_sorteo, id_loteria, nombre_sorteo ASC ";
		return $this->vConexion->ExecuteQuery($sql);
	}	
	
         /**
	 * Busqueda de todos los Sorteos Zodiacales
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetSorteosZod(){

		//Preparacion del query
		$sql = "SELECT * FROM sorteos WHERE status = 1 AND zodiacal = 1 ORDER BY hora_sorteo, id_loteria, nombre_sorteo ASC ";
		return $this->vConexion->ExecuteQuery($sql);
	}	
	

	/**
	 * Eliminar por id_ticket_transaccional
	 *
	 * @param string $id_jugada
	 * @return boolean, array
	 */
	public function EliminarDatosJugada($id_jugada){		
		//Preparacion del query
		$sql = "DELETE FROM ticket_transaccional WHERE id_ticket_transaccional='".$id_jugada."'";
		return $this->vConexion->ExecuteQuery($sql);
		
	}	
	
	/**
	 * Eliminar por id_sorteo
	 *
	 * @param string $id_sorteo
	 * @return boolean, array
	 */
	public function EliminarporSorteo($id_sorteo){
		//Preparacion del query
		$sql = "DELETE FROM ticket_transaccional WHERE id_sorteo='".$id_sorteo."'";
		return $this->vConexion->ExecuteQuery($sql);
	
	}	
	
	
         /**
	 * Busqueda de todos los Sorteos Zodiacales
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetListadoTicketTransaccional($id_taquilla){

		//Preparacion del query
		$sql = "SELECT * FROM ticket_transaccional WHERE id_taquilla=".$id_taquilla." ORDER BY numero, id_sorteo, id_zodiacal";
		return $this->vConexion->ExecuteQuery($sql);
	}	
	

	/**
	 * Obtiene el nombre del Sorteo Segun ID
	 *
	 * @param string $id
	 * @return boolean, array
	 */
	public function GetNombreSorteo($id){
	
		//Preparacion del query
		$sql = "SELECT nombre_sorteo FROM sorteos WHERE status = 1 AND id_sorteo  = ".$id."";
		//echo $sql;
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["nombre_sorteo"];
	
	}	
	
	
  /**
	 * Actualiza Datos de la jugada
	 * @param string $id_resultados
     * @param string $id_sorteo
     * @param string $zodiacal
	 * @param string $numero
     * @param string $fecha_hora
	 * @return boolean, array
	 */
	public function ActualizaDatosJugada($id_ticket_transaccional,$numero,$sorteo,$monto,$zodiacal){
		
		//Preparacion del query
		$sql = "UPDATE `ticket_transaccional` SET `numero`='".$numero."', `id_sorteo`='".$sorteo."', `monto`='".$monto."', `id_zodiacal`='".$zodiacal."' WHERE id_ticket_transaccional='".$id_ticket_transaccional."'";
		return $this->vConexion->ExecuteQuery($sql);
		
	}	
	
	/**
	 * Busqueda de minutos antes de bloquear un sorteo
	 *
	 */
	
	
	public function MinutosBloqueo(){

		//Preparacion del query
		$sql = "SELECT tiempo_cierre_sorteos FROM parametros";

		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["tiempo_cierre_sorteos"];
		
	}		
	
}		
?>