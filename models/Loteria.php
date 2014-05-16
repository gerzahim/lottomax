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
	 * Busqueda de dias de la semana
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetDias(){
	
		//Preparacion del query
		$sql = "SELECT * FROM dias_semana";
		return $this->vConexion->ExecuteQuery($sql);
	}
	

	/**
	 * Guardar Datos de Loteria
	 *
	 * @param string $usuario
	 * @param string $clave
	 * @return boolean, array
	 */
	public function GuardarDatosLoteria($nombre,$status,$id_dias_semana,$fecha_desde,$fecha_hasta,$status_especial){
		
		// id_loteria 	nombre_loteria	status
		//Preparacion del query
		$sql = "INSERT INTO `loterias` (`nombre_loteria` , `status`, `id_dias_semana` , `fecha_desde` , `fecha_hasta`,`status_especial`,`bajado`) VALUES ('".$nombre."', ".$status.",'".$id_dias_semana."','".$fecha_desde."','".$fecha_hasta."', ".$status_especial.", 0)";
		
		echo $sql; 
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
	public function ActualizaDatosLoteria($id_loteria,$nombre,$status,$id_dias_semana,$fecha_desde,$fecha_hasta,$status_especial){
		
	//	$time= $hora.":".$minutos.":00";
		//Preparacion del query
		$sql = "UPDATE `loterias` SET `nombre_loteria`='".$nombre."', `status`='".$status."', `id_dias_semana`='".$id_dias_semana ."' , `fecha_desde`='".$fecha_desde."' , `fecha_hasta`='".$fecha_hasta."', `status_especial`='".$status_especial."', `bajado`=2  WHERE id_loteria='".$id_loteria."'";
		/*echo $sql;
		exit;*/
		echo "<br>".$sql;
		return $this->vConexion->ExecuteQuery($sql);
		
	}

	/**
	 * Actualiza los Sorteos asociados a la Loteria
	 * @param string $id_loteria
	 * @param string $status
	 * @return boolean, array
	 */
	public function ActualizaStatusSorteo($id_loteria,$status,$id_dias_semana){
	
		//Preparacion del query
		$sql = "UPDATE `sorteos` SET `status`='".$status."', `id_dias_semana`='".$id_dias_semana."' WHERE id_loteria='".$id_loteria."'";
		echo "<br>".$sql;
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
		$this->vConexion->ExecuteQuery($sql);
		$sql = "UPDATE `sorteos` SET `status`= 0 WHERE id_loteria='".$id_loteria."'";
		return $this->vConexion->ExecuteQuery($sql);
	}		
	/**
	 * Actualizar Estatus de Loteria
	 *
	 * @param string $usuario
	 * @param string $clave
	 * @return boolean, array
	 */
	public function ActualizarStatusLoteria($id_loteria,$status){
		//Preparacion del query
		$sql = "UPDATE `loterias` SET `status`= ".$status.", bajado=2 WHERE id_loteria='".$id_loteria."'";
		echo "<br>".$sql;
		$this->vConexion->ExecuteQuery($sql);
		$sql = "UPDATE `sorteos` SET `status`= ".$status." WHERE id_loteria='".$id_loteria."'";
		echo "<br>".$sql;
		return $this->vConexion->ExecuteQuery($sql);
	}
	
	
	
	
	/**
	 * Busca las loterias que fueron agregadas o cambiadas en el servidor
	 *
	 * @access public
	 * @return array
	 */
	public function BuscarLoteriasActualizadas($conexion_arriba){
	
		//Preparacion del query
		$sql = "SELECT * FROM loterias WHERE bajado = 0 OR bajado= 2 ";
	
		if( $result= $this->vConexion->ExecuteQuery($sql) ){
			return $this->vConexion->GetArrayInfo($result);
		}
		else{
			return false;
		}
	
	}
	/**
	 * Actualizar Estatus Especial
	 *
	 * @param string $usuario
	 * @param string $clave
	 * @return boolean, array
	 */
	public function ActualizarStatusEspecialLoteria($id_loteria,$status_especial){
		//Preparacion del query
		$sql = "UPDATE `loterias` SET `status_especial`= ".$status_especial." WHERE id_loteria='".$id_loteria."'";
		$this->vConexion->ExecuteQuery($sql);
		echo "<br>".$sql;
	}
	/**
	 * Buscar Fecha de Estatus Especial
	 *
	 * @param string $usuario
	 * @param string $clave
	 * @return boolean, array
	 */
	public function BuscarFechaEspecial($string_busqueda){
		//Preparacion del query
		$sql = "SELECT * FROM `loterias` WHERE ".$string_busqueda;
		echo "<br>".$sql;
		return $this->vConexion->ExecuteQuery($sql);
	}
	/**
	 * Buscar Sorteos de una Loteria en Particular
	 *
	 * @param string $usuario
	 * @param string $clave
	 * @return boolean, array
	 */
	public function BuscarLoterias($id_loteria){
		//Preparacion del query
		$sql = "SELECT * FROM `sorteos` WHERE id_loteria = ".$id_loteria;
		echo "<br>".$sql;
		return $this->vConexion->ExecuteQuery($sql);
	}
}		
?>