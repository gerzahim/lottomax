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
	
	/// PARA TABLA VISTA
	
	/*
	public function GetResultados($fecha){

		//Preparacion del query
		$sql = "SELECT * FROM  ver_resultados WHERE fecha_hora LIKE '%".$fecha."%'";
                
		$result= $this->vConexion->ExecuteQuery($sql);
                if ($this->vConexion->GetNumberRows($result) >0){
                    return $result;
                }else{
                    return "";
                }
		
		
	}*/
	
	/// SIN TABLA VISTA
	
	public function GetResultados($fecha){
	
		//Preparacion del query
		$sql = "SELECT * FROM resultados R
				INNER JOIN zodiacal Z ON R.zodiacal=Z.Id_zodiacal
                INNER JOIN  sorteos S ON S.id_sorteo=R.id_sorteo
				WHERE fecha_hora LIKE '%".$fecha."%'  AND S.status = 1  ORDER BY  `id_loteria` ASC , R.id_sorteo ASC ";
		
		//echo $sql;
		
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
		$sql = "SELECT COUNT(id_sorteo) as N FROM sorteos WHERE status = 1";
                
		$result= $this->vConexion->ExecuteQuery($sql);
                if ($this->vConexion->GetNumberRows($result) >0){
                    $row = $this->vConexion->GetArrayInfo($result);

                    return $row ['N'];
                }else{
                    return 0;
                }


	}
	
	/**
	 * Busqueda de Id de Taquilla.
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetIdTaquilla(){
	
		//Preparacion del query
		//		$sql = "SELECT * FROM parametros";
		//		$result= $this->vConexion->ExecuteQuery($sql);
		//		$roww= $this->vConexion->GetArrayInfo($result);
		//		return $roww["taquilla"];
		 
		return $_SESSION['taquilla'];
	}

	public function GetIdTaquillabyNumero($num_taquilla){
	
		//Preparacion del query
		$sql = "SELECT id_taquilla FROM taquillas WHERE numero_taquilla = ".$num_taquilla."";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["id_taquilla"];
	}

	public function lineas_saltar_despues($id_taquilla){
	
		//Preparacion del query
		$sql = "SELECT lineas_saltar_despues FROM impresora_taquillas WHERE id_taquilla = ".$id_taquilla."";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["lineas_saltar_despues"];
	
	}

	public function GetDatosImpresora($id_taquilla){
	
		//Preparacion del query
		$sql = "SELECT lineas_saltar_despues, ver_numeros_incompletos, ver_numeros_agotados FROM impresora_taquillas WHERE id_taquilla = ".$id_taquilla."";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww;
	}	


	
}		
?>