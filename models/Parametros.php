<?php

/**
 * Archivo del modelo para modulo Parametros
 * @package Parametros.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Marzo - 2013
 */

class Parametros{

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
	 * Busqueda de Parametros
	 *
	 * @param string $cantidad
	 * @param string $pagina
	 * @return boolean, array
	 */
	public function GetListado($cantidad, $pagina){
		
		// Datos para la paginacion
		$inicial= ($pagina-1) * $cantidad;
		
		//Preparacion del query
		$sql = "SELECT * FROM parametros";
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
	 * Busca los datos de parametros
	 *
	 * @access public
	 * @param integer $id_referencia
	 * @return boolean or array
	 */
	public function GetDatosParametros($id_referencia){
		
		//Preparacion del query
		$sql = "SELECT * FROM parametros WHERE id_parametros= '".$id_referencia."'";
		
		if( $result= $this->vConexion->ExecuteQuery($sql) ){
			return $this->vConexion->GetArrayInfo($result);
		}
		else{
			return false;
		}		

	}

       
	/**
	 * Actualiza Datos de Parametros
	 * @param string $Id_parametros
         * @param string $nombre_agencia
         * @param string $tiempo_cierre_sorteos
         * @param string $tiempo_anulacion_ticket
         * @param string $tiempo_vigencia_ticket
         * @param string $aprox_arriba
         * @param string $aprox_abajo
         * @param string $comision_agencia
	 * @return boolean, array
	 */
	public function ActualizaDatosParametros($id_parametros,$id_agencia,$nombre_agencia,$tiempo_cierre_sorteos,$tiempo_anulacion_ticket,$tiempo_vigencia_ticket, $aprox_arriba, $aprox_abajo, $comision_agencia,$tipo_comision){
		
		//Preparacion del query
		$sql = "UPDATE `parametros` SET `id_agencia`='".$id_agencia."', `nombre_agencia`='".$nombre_agencia."',`tiempo_cierre_sorteos`='".$tiempo_cierre_sorteos."',`tiempo_anulacion_ticket`='".$tiempo_anulacion_ticket."',`tiempo_vigencia_ticket`='".$tiempo_vigencia_ticket."', `aprox_arriba`='".$aprox_arriba."', `aprox_abajo`='".$aprox_abajo."', `comision_agencia`='".$comision_agencia."' ,`tipo_comision`='".$tipo_comision."'
                        WHERE id_parametros='".$id_parametros."'";
                
		return $this->vConexion->ExecuteQuery($sql);
		
	}
	
}		
?>