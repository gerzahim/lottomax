<?php

/**
 * Archivo del modelo para modulo Relacion de Pagos
 * @package Relacion_Pagos.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Febrero - 2013
 */

class Relacion_Pagos{

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
	 * Busqueda de Todos las Relaciones de Pago.
	 *
	 * @param string $cantidad
	 * @param string $pagina
	 * @return boolean, array
	 */
	public function GetListado($cantidad, $pagina){
		
		// Datos para la paginacion
		$inicial= ($pagina-1) * $cantidad;
		
		//Preparacion del query
		$sql = "SELECT RP.*, TJ.nombre_jugada FROM relacion_pagos RP INNER JOIN tipo_jugadas TJ ON RP.id_tipo_jugada=TJ.id_tipo_jugada";
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
	 * Busca los datos de relacion Pagos
	 *
	 * @access public
	 * @param integer $id_referencia
	 * @return boolean or array
	 */
	public function GetDatosRelacionPagos($id_referencia){
		
		//Preparacion del query
		$sql = "SELECT * FROM relacion_pagos WHERE id_relacion_pagos= '".$id_referencia."'";
		
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
	 * Actualiza Datos de Relacion de Pagos
	 * @param string $Id_relacion_pagos
         * @param string $monto
         * @param string $status
	 * @return boolean, array
	 */
	public function ActualizaDatosRelacionPagos($id_relacion_pagos,$monto,$status){
		
		//Preparacion del query
		$sql = "UPDATE `relacion_pagos` SET `monto`='".$monto."',`status`='".$status."' WHERE id_relacion_pagos='".$id_relacion_pagos."'";
		return $this->vConexion->ExecuteQuery($sql);
		
	}
	
}		
?>