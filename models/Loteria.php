<?php
class Loteria{

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
	public function GetListado($cantidad, $pagina){
		
		// Datos para la paginacion
		$inicial= ($pagina-1) * $cantidad;
		
		//Preparacion del query
		//$sql = "SELECT * FROM sorteos WHERE status <> 0";
		$sql = "SELECT * FROM loterias";
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
	 * Guardar Datos de Loteria
	 *
	 * @param string $usuario
	 * @param string $clave
	 * @return boolean, array
	 */
	public function GuardarDatosLoteria($nombre){
		
		// id_loteria 	nombre_loteria	status
		//Preparacion del query
		$sql = "INSERT INTO `loterias` (`nombre_loteria` , `status`) VALUES ('".$nombre."', 1)";
		return $this->vConexion->ExecuteQuery($sql);
		
	}	

	
	/**
	 * Busca los datos del Usuario
	 *
	 * @access public
	 * @param integer $id_referencia
	 * @return boolean or array
	 */
	public function GetDatosLoteria($id_referencia){
		
		//Preparacion del query
		$sql = "SELECT * FROM loterias WHERE id_loteria = '".$id_referencia."'";
		
		if( $result= $this->vConexion->ExecuteQuery($sql) ){
			return $this->vConexion->GetArrayInfo($result);
		}
		else{
			return false;
		}		

	}

	/**
	 * Actualiza Datos de Loteria
	 * @param string $usuario
	 * @param string $clave
	 * @return boolean, array
	 */
	public function ActualizaDatosLoteria($id_loteria,$nombre,$status){
		
		$time= $hora.":".$minutos.":00";
		//Preparacion del query
		$sql = "UPDATE `loterias` SET `nombre_loteria`='".$nombre."', `status`='".$status."' WHERE id_loteria='".$id_loteria."'";
		return $this->vConexion->ExecuteQuery($sql);
		
	}

	/**
	 * Desactivar Loteria
	 *
	 * @param string $usuario
	 * @param string $clave
	 * @return boolean, array
	 */
	public function EliminarDatosLoteria($id_loteria){		
		//Preparacion del query
		$sql = "UPDATE `loterias` SET `status`= 0 WHERE id_loteria='".$id_loteria."'";
		return $this->vConexion->ExecuteQuery($sql);
		
	}		

	
}		
?>