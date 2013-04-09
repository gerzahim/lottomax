<?php

/**
 * Archivo modelo para modulo de Taquillas
 * @package Taquilla.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Abril - 2013
 */

class Taquilla{

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
	 * Busqueda de Taquillas.
	 *
	 * @return boolean, array
	 */
	public function GetListado($cantidad, $pagina){
		
		// Datos para la paginacion
		$inicial= ($pagina-1) * $cantidad;
		
		//Preparacion del query
		$sql = "SELECT * FROM taquillas";
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
	 * Guardar Datos de Taquilla
	 *
	 * @param string $numero
	 * @return boolean, array
	 */
	public function GuardarDatosTaquilla($numero){
		
		// id_loteria 	nombre_loteria	status
		//Preparacion del query
		$sql = "INSERT INTO `taquillas` (`numero_taquilla` , `status`) VALUES ('".$numero."', 1)";
		return $this->vConexion->ExecuteQuery($sql);
		
	}	

	
	/**
	 * Busca los datos de la Taquilla
	 *
	 * @access public
	 * @param integer $id_referencia
	 * @return boolean or array
	 */
	public function GetDatosTaquilla($id_referencia){
		
		//Preparacion del query
		$sql = "SELECT * FROM taquillas WHERE id_taquilla = '".$id_referencia."'";
		
		if( $result= $this->vConexion->ExecuteQuery($sql) ){
			return $this->vConexion->GetArrayInfo($result);
		}
		else{
			return false;
		}		

	}

	/**
	 * Actualiza Datos de Taquilla
	 * @param string $numero
	 * @return boolean, array
	 */
	public function ActualizaDatosTaquilla($id_taquilla,$numero,$status){
		
		//Preparacion del query
		$sql = "UPDATE `taquillas` SET `numero_taquilla`='".$numero."', `status`='".$status."' WHERE id_taquilla='".$id_taquilla."'";
		return $this->vConexion->ExecuteQuery($sql);
		
	}

	/**
	 * Desactivar Taquilla
	 *
	 * @param string $id_taquilla
	 * @return boolean, array
	 */
	public function EliminarDatosTaquilla($id_taquilla){
		//Preparacion del query
		$sql = "UPDATE `taquillas` SET `status`= 0 WHERE id_taquilla='".$id_taquilla."'";
		return $this->vConexion->ExecuteQuery($sql);
		
	}		

	
}		
?>