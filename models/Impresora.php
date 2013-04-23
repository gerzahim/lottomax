<?php

/**
 * Archivo del modelo para modulo Impresora
 * @package Impresora.php
 * @author Gerzahim Salas. - <rasce88@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Abril - 2013
 */

class Impresora{

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
	 * Busqueda de Impresoras
	 *
	 * @param string $cantidad
	 * @param string $pagina
	 * @return boolean, array
	 */
	public function GetListado($cantidad, $pagina){
		
		// Datos para la paginacion
		$inicial= ($pagina-1) * $cantidad;
		
		//Preparacion del query
		$sql = "SELECT * FROM impresora_taquillas";
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
	 * Obtiene el numero de la Taquilla
	 *
	 * @param string $usuario
	 * @param string $clave
	 * @return boolean, array
	 */
	public function GetNumeroTaquilla($id){
		
		//Preparacion del query
		$sql = "SELECT numero_taquilla FROM taquillas WHERE id_taquilla = ".$id."";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["numero_taquilla"];
		
	}	
	
	
	/**
	 * Busqueda de todos los Perfiles
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetTaquillas(){
		
		//Preparacion del query
		$sql = "SELECT * FROM taquillas";
		return $this->vConexion->ExecuteQuery($sql);
	}		
	
	
	/**
	 * Guarda Datos de Impresora
	 * @param string $nombre_vendedor_ticket
     * @param string $cortar_ticket
     * @param string $op_taquilla
     * @param string $lineas_saltar_antes
     * @param string $lineas_saltar_despues
     * @param string $ver_numeros_incompletos
     * @param string $ver_numeros_agotados  
	 * @return boolean, array
	 */
	public function GuardarDatosImpresora($nombre_vendedor_ticket,$cortar_ticket,$op_taquilla,$lineas_saltar_antes,$lineas_saltar_despues,$ver_numeros_incompletos,$ver_numeros_agotados){
		
		//Preparacion del query
		$sql = "INSERT INTO `impresora_taquillas` (`nombre_vendedor_ticket` , `cortar_ticket` , `id_taquilla` , `lineas_saltar_antes` , `lineas_saltar_despues` , `ver_numeros_incompletos` , `ver_numeros_agotados` ) VALUES ('".$nombre_vendedor_ticket."', '".$cortar_ticket."', '".$op_taquilla."', '".$lineas_saltar_antes."', '".$lineas_saltar_despues."', '".$ver_numeros_incompletos."', '".$ver_numeros_agotados."')";
		return $this->vConexion->ExecuteQuery($sql);
				
	}	

	
	/**
	 * Busca los datos de impresora
	 *
	 * @access public
	 * @param integer $id_referencia
	 * @return boolean or array
	 */
	public function GetDatosImpresora($id_referencia){
		
		//Preparacion del query
		$sql = "SELECT * FROM impresora_taquillas WHERE id_impresora_taquillas= '".$id_referencia."'";
		
		if( $result= $this->vConexion->ExecuteQuery($sql) ){
			return $this->vConexion->GetArrayInfo($result);
		}
		else{
			return false;
		}		

	}

       
	/**
	 * Actualiza Datos de Parametros
	 * @param string $nombre_vendedor_ticket
     * @param string $cortar_ticket
     * @param string $op_taquilla
     * @param string $lineas_saltar_antes
     * @param string $lineas_saltar_despues
     * @param string $ver_numeros_incompletos
     * @param string $ver_numeros_agotados	 
     * @return boolean, array
	 */
	
	public function ActualizaDatosImpresora($id_impresora,$nombre_vendedor_ticket,$cortar_ticket,$op_taquilla,$lineas_saltar_antes,$lineas_saltar_despues,$ver_numeros_incompletos,$ver_numeros_agotados){
		
		//Preparacion del query
		$sql = "UPDATE `impresora_taquillas` SET `nombre_vendedor_ticket`='".$nombre_vendedor_ticket."', `cortar_ticket`='".$cortar_ticket."',`id_taquilla`='".$op_taquilla."',`lineas_saltar_antes`='".$lineas_saltar_antes."',`lineas_saltar_despues`='".$lineas_saltar_despues."',`ver_numeros_incompletos`='".$ver_numeros_incompletos."',`ver_numeros_agotados`='".$ver_numeros_agotados."' WHERE id_impresora_taquillas='".$id_impresora."'";
		//echo $sql;
		//exit();
		return $this->vConexion->ExecuteQuery($sql);
		
	}
	
     /**
	 * Eliminar Cupos Especialees
	 *
	 * @param string $id_cupo_especial
	 * @return boolean, array
	 */
	public function EliminarDatosImpresora($id){
		//Preparacion del query
		$sql = "DELETE FROM `impresora_taquillas` WHERE id_impresora_taquillas='".$id."'";
		return $this->vConexion->ExecuteQuery($sql);

	}	
	
}		
?>