<?php

/**
 * Archivo del modelo para modulo reporte de ver Resultados
 * @package RVer_resultados.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Mayo - 2013
 */

class RVer_Resultados{

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
	 * Devuelve el listado de resultados
	 *
         * @param string $fecha
	 * @return boolean, array
	 */
	public function GetResultados($fecha){

		//Preparacion del query
		$sql = "SELECT * FROM  ver_resultados WHERE fecha_hora LIKE '%".$fecha."%'";
                
		$result= $this->vConexion->ExecuteQuery($sql);
                if ($this->vConexion->GetNumberRows($result) >0){
                    return $result;
                }else{
                    return "";
                }
		
		
	}


        /**
	 * Devuelve la cantidad de sorteos existentes
	 *
	 * @return boolean, array
	 */
	public function GetNumSoteos(){

		//Preparacion del query
		$sql = "SELECT COUNT(id_sorteo) as  N FROM  sorteos";
                
		$result= $this->vConexion->ExecuteQuery($sql);
                if ($this->vConexion->GetNumberRows($result) >0){
                    $row = $this->vConexion->GetArrayInfo($result);

                    return $row ['N'];
                }else{
                    return 0;
                }


	}


	
}		
?>