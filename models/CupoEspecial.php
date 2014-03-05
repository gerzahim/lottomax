<?php
class CupoEspecial{

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
	 * Devuelve los id_sorteos activos
	 *
	 * 
	 * @return result
	 */
	public function getSorteos(){
	
		//Preparacion del query
		$sql = "SELECT id_sorteo FROM `sorteos` WHERE status=1 AND zodiacal = 0";
	//	echo $sql;
		return $this->vConexion->ExecuteQuery($sql);
	
	}	
	
	
	/**
	 * Devuelve si se ejecuto la consulta del SQL para ingresar los cupos especiales
	 * @param string $sql
	 *
	 * @return boolean
	 */
	public function insertarCupoEspecial($sql){
	
		//Preparacion del query
		//	echo $sql;
		return $this->vConexion->ExecuteQuery($sql);
	
	}
	/**
	 * Devuelve el arreglo de la serie del terminal enviado
	 *
	 * @param string $txt_numero
	 * @return array
	 */
	function Serializar($txt_numero){
		$serie= array();
		$longitud = 10;
	
		// Concatena los caracteres de la serie
		$cadena= '';
		for($i = 0; $i < $longitud; $i++){
			$cadena = $i.$txt_numero;
			$serie[] = $cadena;
		}
	
		return $serie;
	
	}
	
	/**
	 * Devuelve las permutas de acuerdo al numero enviado
	 *
	 * @param string $txt_numero
	 * @return array
	 */	
	 function Permutar($txt_numero){
	
		// Esta funcion de permuta, funciona con y sin numeros repetidos
		// Los Numero a combinar se deben guardar en un array
		// Ejemplo
		//  $letras = array('1', '2', '3'); numero 123
		// En el caso de tener numeros repetidos se debe agregar al array numeros duplicados, (el orden es indiferente!)
		// Ejemplo
		//  $letras = array('1', '2', '3', '33');  numero 1233
		//  $letras = array('1', '2', '3', '4', '44'); numero 12443
		//  $letras = array('2', '1', '3', '11', '33'); numero 33211
		// Verificar si tiene repeticiones y crea un nuevo array
		// esto para anexar a la final los repetidos con con 22, 55
		$addarr = array();
		foreach (count_chars($txt_numero, 1) as $i => $val) {
			if ($val >= 2){
				$addarr[] = chr($i).chr($i);
			}
		}
		// Creando un arreglo principal con el numero dado
		$arr1 = str_split($txt_numero);
		// Eliminar elementos duplicados de un array en PHP
		$arr1 = array_values(array_unique($arr1));
		// Uniendo el array principal y el array con los repetidos en caso de que alla repetidos
		$letras = array_merge($arr1, $addarr);
		/*
		 echo "<pre>";
		print_r($letras);
		echo "</pre>";
		exit();
		*/
		$arr = array(); //Array de combinaciones
		// Creando array bidimensional para las combinaciones
		// Foreach Ciclico para hacer todas las combinaciones posibles en grupo de 3
		// resultado de combinaciones en un arreglo bidimensional
		foreach ($letras as $l) {
			$letra1 = $l;
			foreach ($letras as $l2) {
				if ( strcmp($l2,$letra1)!= 0){
					$letra2 = $l2;
					foreach ($letras as $l3) {
						if (strcmp($l3,$letra2)!= 0 && strcmp($l3,$letra1)!= 0){
							$letra3 = $l3;
							$arr[] = array($l,$l2,$l3);
	
						}
	
					}
	
				}
	
			}
		}
		// resultado bidimensional convertirlo a array unidimensional
		foreach ($arr as $l) {
			$duro =$l[0].$l[1].$l[2];
			$letra11[] = $duro;
		}
		/******** Validacion para repetidos 'c', 'cc' *******/
	
		// Eliminar elementos duplicados de un array en PHP
		$letra11 = array_values(array_unique($letra11));
	
	
		// Elimando ultimo valor del string si es mayor a 3
		foreach ($letra11 as $letra12){
			if (strlen($letra12) > 3){
				$letra13[] = substr($letra12, 0, 3);
			}else{
				$letra13[] = $letra12;
			}
		}
	
		// Eliminar elementos duplicados de un array en PHP
		//Eliminando los valores repetidos en caso de valores truncados
		$letra13 = array_values(array_unique($letra13));
	
	
		// Eliminando la combinacion extra 333, 444, 555...
		foreach ($letra13 as $letra14) {
			$bandera=0;
			foreach (count_chars($letra14, 1) as $i => $val) {
				if ($val >= 3){
					$bandera=1;
				}
			}
			if ($bandera != 1){
				$letra15[] = $letra14;
			}
		}
	
	
		return $letra15;
	}	
	
}		
?>