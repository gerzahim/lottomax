<?php

/**
 * Archivo del modelo para modulo de VistaLogin
 * @package VistaLogin.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Abril - 2013
 */

class VistaLogin{

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
	 * Obtiene los datos de taquillas
	 *
	 * @access public
	 * @param integer $id_referencia
	 * @return boolean or array
	 */
	public function GetDatosTaquillas(){
        
		//Preparacion del query
		$sql = "SELECT * FROM taquillas WHERE status=1";
                return $this->vConexion->ExecuteQuery($sql);
	}

	
}		
?>