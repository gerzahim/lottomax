<?php
class Sorteo{

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
		$sql = "SELECT * FROM sorteos ORDER BY hora_sorteo, id_loteria, zodiacal, nombre_sorteo";
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
	 * Listar posibles Horas
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetHoras(){
		
		$i=0;
		$hora= array();
		
		while ($i < 25){
			if ($i < 10){
				$hora[]= '0'.$i;				
			}else{
				$hora[]= $i;
			}
			$i++;
		}
		return $hora;
	}	
	
	/**
	 * Listar posibles Minutos
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetMinutos(){
		
		$i=0;
		$minutos= array();
		
		while ($i < 60){
			if ($i < 10){
				$minutos[]= '0'.$i;				
			}else{
				$minutos[]= $i;
			}
			$i++;
		}
		return $minutos;
	}

	/**
	 * Guardar Datos de Sorteo
	 *
	 * @param string $usuario
	 * @param string $clave
	 * @return boolean, array
	 */
	public function GuardarDatosSorteo($id_loteria,$nombre,$time, $turno, $zodiacal,$tradicional,$tipoc){
		
		// id_sorteo  id_loteria nombre_sorteo	hora_sorteo	zodiacal status
		//Preparacion del query
		$sql = "INSERT INTO `sorteos` (`id_loteria` , `nombre_sorteo` , `hora_sorteo`, `id_turno` , `zodiacal`, `tradicional` , `status`, `tipoc`) VALUES ('".$id_loteria."', '".$nombre."', '".$time."', '".$turno."', '".$zodiacal."', '".$tradicional."', 1 , '".$tipoc."')";
		return $this->vConexion->ExecuteQuery($sql);
		
	}	

	
	/**
	 * Busca los datos del Usuario
	 *
	 * @access public
	 * @param integer $id_referencia
	 * @return boolean or array
	 */
	public function GetDatosSorteo($id_referencia){
		
		//Preparacion del query
		$sql = "SELECT * FROM sorteos WHERE id_sorteo = '".$id_referencia."'";
		
		if( $result= $this->vConexion->ExecuteQuery($sql) ){
			return $this->vConexion->GetArrayInfo($result);
		}
		else{
			return false;
		}		

	}

	/**
	 * Actualiza Datos de Sorteo
	 * @param string $usuario
	 * @param string $clave
	 * @return boolean, array
	 */
	public function ActualizaDatosSorteo($id_sorteo,$id_loteria,$nombre,$hora,$minutos,$turno,$zodiacal,$tradicional,$status,$tipoc){
		
		$time= $hora.":".$minutos.":00";
		//Preparacion del query
		$sql = "UPDATE `sorteos` SET `id_loteria`='".$id_loteria."',  `nombre_sorteo`='".$nombre."', `hora_sorteo`='".$time."', `id_turno`='".$turno."', `zodiacal`='".$zodiacal."', `tradicional`='".$tradicional."', `status`='".$status."', `tipoc`='".$tipoc."' WHERE id_sorteo='".$id_sorteo."'";
		return $this->vConexion->ExecuteQuery($sql);
		
	}

	/**
	 * Desactivar Sorteo
	 *
	 * @param string $usuario
	 * @param string $clave
	 * @return boolean, array
	 */
	public function EliminarDatosSorteo($id_sorteo){		
		//Preparacion del query
		$sql = "UPDATE `sorteos` SET `status`= 0 WHERE id_sorteo='".$id_sorteo."'";
		return $this->vConexion->ExecuteQuery($sql);
		
	}


	/**
	 * Busqueda de todos los Perfiles
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetLoterias(){
		
		//Preparacion del query
		$sql = "SELECT * FROM loterias";
		return $this->vConexion->ExecuteQuery($sql);
	}	

	
	/**
	 * Obtiene el nombre del Perfil Segun ID
	 *
	 * @param string $usuario
	 * @param string $clave
	 * @return boolean, array
	 */
	public function GetNombreLoteria($id){
		
		//Preparacion del query
		$sql = "SELECT nombre_loteria FROM loterias WHERE id_loteria = ".$id."";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["nombre_loteria"];
		
	}	
	
}		
?>