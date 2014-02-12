<?php

/**
 * Archivo del modelo para modulo de Cargar Resultados
 * @package Cargar_Resultados.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Abril - 2013
 */

class Cargar_Resultados{

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
	 * Busqueda de todos los resultados de los Sorteos en una fecha y periodo determinado (mañana, tarde, noche, todos)
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetSorteos($fecha, $periodo){

		//Preparacion del query
                switch ($periodo){
                    case '1': // Manana
                            $sql_periodo=" hora_sorteo BETWEEN '00:00' AND '14:00'" ;
                        break;
                    case '2': // Tarde
                        $sql_periodo=" hora_sorteo BETWEEN '14:01' AND '17:00'";
                        break;
                    case '3': // Noche
                        $sql_periodo=" hora_sorteo BETWEEN '17:01' AND '23:59'";
                        break;
                    default : // Todos
                        $sql_periodo="hora_sorteo <> '1'";
                        break;

                }
                
		$sql = "SELECT S.id_sorteo, S.hora_sorteo, S.id_loteria, S.nombre_sorteo, S.zodiacal, 'signo','numero', 'id_resultado'
                    FROM sorteos S
                    WHERE ".$sql_periodo. " AND S.status = 1 AND S.id_sorteo NOT IN (SELECT id_sorteo FROM resultados WHERE fecha_hora LIKE '%".$fecha."%')
                   
                    UNION ALL
                    SELECT S.id_sorteo, S.hora_sorteo, S.id_loteria, S.nombre_sorteo, S.zodiacal, Z.nombre_zodiacal,R.numero, R.id_resultados
                    FROM resultados R
                    INNER JOIN zodiacal Z ON R.zodiacal=Z.Id_zodiacal
                    INNER JOIN  sorteos S ON S.id_sorteo=R.id_sorteo
                    WHERE ".$sql_periodo. " AND S.status = 1 AND R.fecha_hora LIKE '%".$fecha."%'  ORDER BY  `id_loteria` ASC , id_sorteo ASC  
                    ";
		
		return $this->vConexion->ExecuteQuery($sql);
	}
	
	/**
	 * Busqueda de minutos antes de bloquear un sorteo
	 *
	 */
	
	
	public function MinutosBloqueo(){
	
		//Preparacion del query
		$sql = "SELECT tiempo_cierre_sorteos FROM parametros";
	
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["tiempo_cierre_sorteos"];
	
	}
	


         /**
	 * Busqueda de todos los Sorteos
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetAllSorteos(){

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
	 * Verifica si esta registrado el resultado de un sorteo
	 *
	 * @param string $id_sorteo
         * @param string $fecha
	 * @return boolean, array
	 */
	public function GetResultadoSorteo($id_sorteo, $fecha){

		//Preparacion del query
		$sql = "SELECT id_resultados FROM resultados WHERE id_sorteo = ".$id_sorteo." AND fecha_hora  LIKE '%".$fecha."%'";
                
		$result= $this->vConexion->ExecuteQuery($sql);
                if ($this->vConexion->GetNumberRows($result) >0){
                    $roww= $this->vConexion->GetArrayInfo($result);
                    $respu = $roww["id_resultados"];
                    return $respu;
                }else{
                    return "";
                }
		
		
	}


	/**
	 * Guardar Datos de los Resultados
	 *
	 * @param string $id_sorteo
         * @param string $zodiacal
	 * @param string $numero
         * @param string $fecha_hora
	 * @return boolean, array
	 */
	public function GuardarDatosResultados($id_sorteo, $zodiacal, $numero, $fecha_hora){

		//Preparacion del query
		$sql = "INSERT INTO `resultados` (`id_sorteo` , `zodiacal`, `numero`, `fecha_hora`) VALUES ('".$id_sorteo."', '".$zodiacal."', '".$numero."', '".$fecha_hora."')";
		return $this->vConexion->ExecuteQuery($sql);
                
	}

       	/**
	 * Actualiza Datos de Resultados
	 * @param string $id_resultados
         * @param string $id_sorteo
         * @param string $zodiacal
	 * @param string $numero
         * @param string $fecha_hora
	 * @return boolean, array
	 */
	public function ActualizaDatosResultados($id_resultados, $id_sorteo, $zodiacal, $numero, $fecha_hora){
		
		//Preparacion del query
		$sql = "UPDATE `resultados` SET `id_sorteo`='".$id_sorteo."', `zodiacal`='".$zodiacal."', `numero`='".$numero."', `fecha_hora`='".$fecha_hora."', `bajado`=2 WHERE id_resultados='".$id_resultados."'";
		return $this->vConexion->ExecuteQuery($sql);
	
		
	}
	
         /**
	 * Busqueda de todos los Sorteos
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetHoraSorteo($id_sorteo){

		//Preparacion del query
		$sql = "SELECT hora_sorteo FROM sorteos WHERE status = 1 AND id_sorteo = ".$id_sorteo."";
		$result = $this->vConexion->ExecuteQuery($sql);
        $roww= $this->vConexion->GetArrayInfo($result);
        $respu = $roww["hora_sorteo"];
        return $respu; 		
	}	
	
	

}		
?>