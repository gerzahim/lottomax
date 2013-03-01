<?php

/**
 * Archivo del modelo para modulo de Cupos Especiales de Numeros
 * @package Cupos_Especiales.php
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
	 * Busqueda de todos los Sorteos
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetSorteos(){

		//Preparacion del query
		$sql = "SELECT * FROM sorteos WHERE status = 1 ORDER BY zodiacal, hora_sorteo, nombre_sorteo ASC ";
		return $this->vConexion->ExecuteQuery($sql);
	}

        /**
	 * Busqueda de todos los zodiacales
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetZodiacales(){

		//Preparacion del query
		$sql = "SELECT * FROM zodiacal WHERE nombre_zodiacal <>'No Zodiacal' ORDER BY nombre_zodiacal ASC ";
		return $this->vConexion->ExecuteQuery($sql);
	}

        /**
	 * Busqueda de todos los tipos de jugadas
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetTipoJugadas(){

		//Preparacion del query
		$sql = "SELECT * FROM tipo_jugadas WHERE status = 1 AND nombre_jugada <> 'Aproximacion' ORDER BY id_tipo_jugada ASC ";
		return $this->vConexion->ExecuteQuery($sql);
	}

        /**
	 * Busqueda en id de tipos de jugadas segun parametros
	 *
	 * @param string $eszodiacal
	 * @param string $estriple
	 * @return boolean, array
	 */


	public function GetTipoJugada_2($eszodiacal,$txt_numero){

		$tamano_numero = strlen($txt_numero);

		if ($tamano_numero == 3){
			$estriple=1;
		}

		if ($tamano_numero == 2){
			$estriple=0;
		}

		//Preparacion del query
		$sql = "SELECT id_tipo_jugada FROM tipo_jugadas WHERE zodiacal = ".$eszodiacal." AND triple  = ".$estriple."";

		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["id_tipo_jugada"];

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
	 * Verifica si un sorteo es zodiacal
	 *
	 * @param string $id_sorteo
	 * @return boolean, array
	 */
	public function GetTrueZodiacal($id_sorteo){

		//Preparacion del query
		$sql = "SELECT zodiacal FROM sorteos WHERE status = 1 AND id_sorteo = ".$id_sorteo."";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		$respu = $roww["zodiacal"];

		if($respu == 1){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Guardar Datos de Cupo Especial
	 *
	 * @param string $numero
         * @param string $monto_cupo
	 * @param string $id_sorteo
         * @param string $id_tipo_jugada
         * @param string $id_zodiacal
         * @param string $fecha_desde
         * @param string $fecha_hasta
	 * @return boolean, array
	 */
	public function GuardarDatosCupoEspecial($numero, $monto_cupo, $id_sorteo, $id_tipo_jugada, $id_zodiacal, $fecha_desde, $fecha_hasta ){

		//Preparacion del query
		$sql = "INSERT INTO `cupo_especial` (`numero` , `monto_cupo`, `id_sorteo`, `id_tipo_jugada`, `id_zodiacal`, `fecha_desde`, `fecha_hasta`) 
                        VALUES ('".$numero."', '".$monto_cupo."', '".$id_sorteo."', '".$id_tipo_jugada."', '".$id_zodiacal."', '".$fecha_desde."', '".$fecha_hasta."')";
		return $this->vConexion->ExecuteQuery($sql);
                
	}


        /**
	 * Eliminar Cupos Especialees
	 *
	 * @param string $id_cupo_especial
	 * @return boolean, array
	 */
	public function EliminarDatosCupoEspecial($id_cupo_especial){
		//Preparacion del query
		$sql = "DELETE FROM `cupo_especial` WHERE id_cupo_especial='".$id_cupo_especial."'";
		return $this->vConexion->ExecuteQuery($sql);

	}

	/**
	 * Busca los datos de cupos Especiales segun un Id
	 *
	 * @access public
	 * @param integer $id_referencia
	 * @return boolean or array
	 */
	public function GetDatosCupoEspecial($id_referencia){
		
		//Preparacion del query
		$sql = "SELECT * FROM cupo_especial WHERE id_cupo_especial= '".$id_referencia."'";
		
		if( $result= $this->vConexion->ExecuteQuery($sql) ){
			return $this->vConexion->GetArrayInfo($result);
		}
		else{
			return false;
		}		

	}

       	/**
	 * Actualiza Datos de Cupos Especiales
	 * @param string $Id_cupo_especial
         * @param string $monto_cupo
         * @param string $numero
         * @param string $fecha_desde
         * @param string $fecha_hasta
	 * @return boolean, array
	 */
	public function ActualizaDatosCupoEspecial($id_cupo_especial,$numero,$monto_cupo,$fecha_desde,$fecha_hasta){
		
		//Preparacion del query
		$sql = "UPDATE `cupo_especial` SET `monto_cupo`='".$monto_cupo."', `numero`='".$numero."', `fecha_desde`='".$fecha_desde."', `fecha_hasta`='".$fecha_hasta."' WHERE id_cupo_especial='".$id_cupo_especial."'";
		return $this->vConexion->ExecuteQuery($sql);
		
	}


        /**
	 * Busqueda Cupos Especiales Segun parametro.
	 *
	 * @param string $numero o $monto
         * @param string $sorteo
         * @param string $tipo_jugada
         * @param string $zodiacal
	 * @param string $clave
	 * @return boolean, array
	 */
	public function GetListadosegunVariable($parametro_where){

		//Preparacion del query
                 $sql = "SELECT CE.*, TJ.nombre_jugada, S.nombre_sorteo, Z.nombre_zodiacal
                        FROM cupo_especial CE
                            INNER JOIN tipo_jugadas TJ ON CE.id_tipo_jugada=TJ.id_tipo_jugada
                            INNER JOIN sorteos S ON CE.id_sorteo=S.id_sorteo
                            INNER JOIN zodiacal Z ON CE.id_zodiacal=Z.id_zodiacal
                        WHERE ".$parametro_where;
                 
		$result= $this->vConexion->ExecuteQuery($sql);
		return  $result;

	}
	
}		
?>