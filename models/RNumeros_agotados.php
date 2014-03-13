<?php

/**
 * Archivo del modelo para modulo reporte de Numeros Agotados
 * @package RNumeros_agotados.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Mayo - 2013
 */

class RNumeros_agotados{

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
	 * Devuelve el listado de numeros agotados
	 *
         * @param string $fecha
	 * @return boolean, array
	 */
	public function GetNumeros($fecha){

		//Preparacion del query
		$sql = "SELECT IA.numero, S.nombre_sorteo, S.hora_sorteo, Z.nombre_zodiacal
                    FROM  detalle_ticket IA
                    INNER JOIN Sorteos S ON IA.id_sorteo=S.id_sorteo
                    INNER JOIN Zodiacal  Z ON IA.id_zodiacal=Z.Id_zodiacal
                    WHERE monto_restante=0 AND IA.fecha_sorteo LIKE '%".$fecha."%'";
                
		$result= $this->vConexion->ExecuteQuery($sql);
		//echo $sql;
                if ($this->vConexion->GetNumberRows($result) >0){
                    return $result;
                }else{
                    return "";
                }
	}
}		
?>