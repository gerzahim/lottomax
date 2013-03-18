<?php



// Funcion que genera la serie de un numero

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




$txt_numeros = '82';

echo 'Serie de '.$txt_numeros;
$datos = Serializar($txt_numeros);
echo "<pre>";
print_r($datos);
echo "</pre>";
