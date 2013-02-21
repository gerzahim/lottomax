<?php

/**
 * Archivo del modelo para modulo de Cupos Generales de Numeros
 * @package Cupos_Generales.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Febrero - 2013
 */

class Cupo_Especial{

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
	 * Obtiene todos los datos de cupo general.
	 *
	 * @param string $cantidad
	 * @param string $pagina
	 * @return boolean, array
	 */
	public function GetListado($cantidad, $pagina){
		
		// Datos para la paginacion
		$inicial= ($pagina-1) * $cantidad;
		
		//Preparacion del query
		$sql = "SELECT CE.*, TJ.nombre_jugada, S.nombre_sorteo, Z.nombre_zodiacal
                        FROM cupo_especial CE
                            INNER JOIN tipo_jugadas TJ ON CE.id_tipo_jugada=TJ.id_tipo_jugada
                            INNER JOIN sorteos S ON CE.id_sorteo=S.id_sorteo
                            INNER JOIN zodiacal Z ON CE.id_zodiacal=Z.id_zodiacal";
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
	 * Busca los datos de cupos generales segun un Id
	 *
	 * @access public
	 * @param integer $id_referencia
	 * @return boolean or array
	 */
	public function GetDatosCupoGeneral($id_referencia){
		
		//Preparacion del query
		$sql = "SELECT * FROM cupo_general WHERE id_cupo_general= '".$id_referencia."'";
		
		if( $result= $this->vConexion->ExecuteQuery($sql) ){
			return $this->vConexion->GetArrayInfo($result);
		}
		else{
			return false;
		}		

	}

        /**
	 * Obtiene los datos de tipo jugadas
	 *
	 * @access public
	 * @param integer $id_referencia
	 * @return boolean or array
	 */
	public function GetDatosTipoJugadas(){

		//Preparacion del query
		$sql = "SELECT * FROM tipo_jugadas";
                return $this->vConexion->ExecuteQuery($sql);
	}

	/**
	 * Actualiza Datos de Cupos Generales
	 * @param string $Id_cupo_general
         * @param string $monto
	 * @return boolean, array
	 */
	public function ActualizaDatosCupoGeneral($id_cupo_general,$monto){
		
		//Preparacion del query
		$sql = "UPDATE `cupo_general` SET `monto_cupo`='".$monto."' WHERE id_cupo_general='".$id_cupo_general."'";
		return $this->vConexion->ExecuteQuery($sql);
		
	}
	
}		
?>