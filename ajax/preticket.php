<?php

// Archivo de variables de configuracion
require_once('../config/config.php');
$obj_config= new ConfigVars();

// Archivo de mensajes
require_once('.'.$obj_config->GetVar('ruta_config').'mensajes.php');

// Clase Generica
require('.'.$obj_config->GetVar('ruta_libreria').'Generica.php');
$obj_generico= new Generica();

// Conexion a la bases de datos
require('.'.$obj_config->GetVar('ruta_libreria').'Bd.php');
$obj_conexion= new Bd();
if( !$obj_conexion->ConnectDataBase($obj_config->GetVar('host'), $obj_config->GetVar('data_base'), $obj_config->GetVar('usuario_db'), $obj_config->GetVar('clave_db')) ){
	echo "sin_conexion_bd";
}	

// Modelo asignado
require('.'.$obj_config->GetVar('ruta_modelo').'Ventas.php');
$obj_modelo= new Ventas($obj_conexion);

//Verificando que escriban el numero y el monto 
if(isset($_POST['txt_numero'])){
	$txt_numero=$_POST['txt_numero'];
}else{
	$txt_numero=0;
}

if(isset($_POST['txt_monto'])){
	$txt_monto=$_POST['txt_monto'];
}else{
	$txt_monto=0;
}

// Verificando que marquen algun sorteo o Zodiacal
$flagSorteo = 1;
$flagZodiacal = 1;

// Validando que si marco al menos un sorteo
if(!empty($_POST['ss'])) {
    // Si selecciono algun sorteo	
	$flagSorteo = 1;
	
	// asignado todos los sorteos a una variable en array()
	$sorteos =$_POST['ss'];
	
	//recorriendo el array de los sorteos
	foreach ( $sorteos as $sorteo) {
		// Verifica si el sorteo es Zodiacal
		if($obj_modelo->GetTrueZodiacal($sorteo)){
			//Verificando si marco al menos un signo ari, sag, etc...			
			if(!empty($_POST['zz'])) {
				// Si Marco al menos un signo 
				$flagZodiacal = 1;
				// asignado todos los zodiacales a una variable en array()
				$zodiacales =$_POST['zz'];
			}else{
				// No Marco al menos un signo 
				$flagZodiacal = 0;				
			}			
		}		
	}   

}else{
	// No selecciono ningun sorteo	
	$flagSorteo = 0;
}




// Clase XTemplate
//require('.'.$obj_config->GetVar('ruta_libreria').'XTemplate.php');
//$obj_xtpl = new XTemplate('.'.$obj_config->GetVar('ruta_vista')."ticket".$obj_config->GetVar('ext_vista'));

if($txt_numero == 0 ){
	$_SESSION['mensaje']= $mensajes['no_numero'];
	echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";	
}elseif ($txt_monto == 0 ){	
	$_SESSION['mensaje']= $mensajes['no_monto'];
	echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
	
}elseif ($flagSorteo == 0 ){	
	$_SESSION['mensaje']= $mensajes['no_sorteo'];
	echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
	//exit();
}elseif ($flagZodiacal == 0 ){	
	$_SESSION['mensaje']= $mensajes['no_zodiacal'];
	echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
	//exit();
}else {	
	
	// Asignando El Modo de juego 
	$op_juego=$_POST['op_juego']; 
	
	// Contenido del sistema
	switch ($op_juego){
	
		case 1:  
			// Juega solo triple
			$result= $obj_modelo->GetIdTaquilla();
			
			//recorriendo el array de los sorteos seleccionados	
			foreach ( $sorteos as $sorteo) {
				// Verifica si el sorteo es Zodiacal
				if($obj_modelo->GetTrueZodiacal($sorteo)){
					foreach ($zodiacales as $zodiacal){
						
						//revisar tabla de numeros jugados
						//revisar tabla de cupo_especial
						//revisar tabla de cupo_general
						
						// guardar registro en tabla de numeros jugados
						
						
						// Agregar ticket a tabla transaccional
						if( $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,$zodiacal,$txt_monto) ){
																		
						}
						else{
							$_SESSION['mensaje']= $mensajes['fallo_agregar'];
							echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
						}			
					}
				// El sorteo no es Zodiacal
				}else{
					// Agregar ticket a tabla transaccional
					if( $obj_modelo->GuardarTicketTransaccional($txt_numero,$sorteo,0,$txt_monto) ){
																	
					}
					else{
						$_SESSION['mensaje']= $mensajes['fallo_agregar'];
						echo "<div id='mensaje' class='mensaje' >".$_SESSION['mensaje']."</div>";
					}	
				}		
			}
			
			// Listado de Sorteos
			if( $result= $obj_modelo->GetDatosTicketTransaccional() ){
				echo "<br><table class='table_ticket' align='center' border='1' width='90%'>";
				while($row= $obj_conexion->GetArrayInfo($result)){
					//print_r($row);
					echo "<tr class='eveno'><td align='center'>SORTEO: ".$obj_modelo->GetNombreSorteo($row['id_sorteo'])."</td></tr>";
					echo "<tr class='eveni'><td align='left'>".$row['numero']." x ".$row['monto']."</td></tr>";	
				}		
				echo "</table>";	
			}			
								
	     break;
		 
		case 2:  
			// Juepa Permuta
	     break;
		 
		case 3:  
			// Juega Series
	     break;
	     
		case 4:  
			// Juega Corridas
	     break;	 
        
	}	
	
}



/*
 * 
 * 			<option value="2">Permuta</option>
			<option value="3">Series</option>
			<option value="4">Corridas</option>
			<option value="5">Astral</option>
op=ventas&accion=add&
txt_numero=&
txt_monto=&
op_juego=1&
cant_sorteos=3&
s1=on&s2=on&s3=on&
z00=on&z01=on&z02=on&z03=on&z04=on&z05=on&z06=on&z07=on&z08=on&z09=on&z10=on&z11=on&z12=on&
efectivo=150&cambio=30&total=120.00

$codigo=$_REQUEST['txt_numero'];
echo $codigo;
*/


/*
foreach ( $_POST as $key => $value) {

  echo "<p>".$key."</p>";
  echo "<p>".$value."</p>";
  echo "<hr />";

}

echo "
<table class='table_ticket' align='center' border='0' width='90%'>
	<tr>
		<td align='center'><strong>Sorteo<strong></td>
		<td align='center'><strong>Numero<strong></td>
		<td align='center'><strong>Monto<strong></td>		
	</tr>
	<tr>
		<td align='center'>Chance A 8 PM</td>
		<td align='center'>124</td>
		<td align='center'>20</td>
	</tr>																																			
</table>    
";


*/


/*
 * lo primero es validar que txt_numero=&
txt_monto=& no esten vacios, de lo contrario enviar mensaje de error

luego validamos que op_juego para llevar la variable a un switch
crear variable de sesion
dos for uno para sorteos 
 y (if es zodiacal) dentro de este los zodiacales

 * 
 * */



// Parseo  final del  documento
//$obj_xtpl->parse('contenido1');
//$obj_xtpl->out('contenido1');
?>