<?php



// Funcion que genera la corrida entre dos numeros
function Corrida($txt_numero){
         $corrida= array();

         if (strlen(strchr($txt_numero, '-'))>0){
                 $numeros=explode('-',$txt_numero);
                 $numero1= $numeros[0];
                 $numero2= $numeros[1];
                 $diferencia=$numero1-$numero2;
                 if ($numero1 >0 && $numero2>0){
                        $cadena= '';
                        $flag= false;

                        if ($diferencia<0){
                            $diferencia = $diferencia * (-1);
                            $flag=true;
                        }

                        for($i = 0; $i <= $diferencia; $i++){
                                if ($flag){
                                    $cadena = $numero1 + $i;
                                    $corrida[] = $cadena;
                                }else{
                                    $cadena = $numero2 + $i;
                                    $corrida[] = $cadena;
                                }


                        }
                        return $corrida;
                  }else{
                        return $txt_numero;
                    }
             }else{
                 return $txt_numero;
             }
        

      
	
}




$txt_numeros = '120-135';

echo 'Corrida de '. $txt_numeros;
$datos = Corrida($txt_numeros);
echo "<pre>";
print_r($datos);
echo "</pre>";
