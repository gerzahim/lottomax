<?php
/*
op=ventas&accion=add&
txt_numero=&
txt_monto=&
op_juego=1&
cant_sorteos=3&
s1=on&s2=on&s3=on&
z00=on&z01=on&z02=on&z03=on&z04=on&z05=on&z06=on&z07=on&z08=on&z09=on&z10=on&z11=on&z12=on&
efectivo=150&cambio=30&total=120.00
*/
$codigo=$_REQUEST['results'];
echo $codigo;

parse_str($_POST[arraydata], $searcharray);
print_r($searcharray);

// Archivo de variables de configuracion
require_once('../config/config.php');
$obj_config= new  ConfigVars();



// Objetos de clases

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
require('.'.$obj_config->GetVar('ruta_modelo').'Test.php');
$obj_modelo= new Test($obj_conexion);

if(!$obj_generico->IsEmpty($_REQUEST['txt_numero']) && !$obj_generico->IsEmpty($_REQUEST['txt_monto'])){
	echo "que bolas";
}

/*
$codigo=$_REQUEST['sorteo1'];
echo $codigo;

if(isset($_GET['cantm'])){
	$cantidad_motivos=$_GET['cantm'];
}else{
	$cantidad_motivos=0;
}
*/

echo "
    <table width='180' border='0'>
      <tr class='eveni'>
        <td>CHANCE A 1PM 587 x 20</td>
      </tr>
      <tr class='eveni'>
        <td>CHANCE A 1PM</td>
      </tr>
      <tr class='eveni'>
        <td> 587 x 20&nbsp;&nbsp;&nbsp;87 x 20</td>
      </tr>
      <tr class='eveni'>
        <td>CHANCE ZODIACAL 7PM</td>
      </tr>
      <tr class='eveni'>
        <td>Acu 587 x 20&nbsp;&nbsp;&nbsp;Acu 87 x 20</td>
      </tr>
      <tr class='eveni'>
        <td>CHANCE ASTRAL 7PM</td>
      </tr>
      <tr class='eveni'>
        <td>Acu 587 x 20&nbsp;&nbsp;&nbsp;</td>
      </tr>
      <tr class='eveni'>
        <td>&nbsp;</td>
      </tr>
      <tr class='eveni'>
        <td>&nbsp;</td>
      </tr>
      <tr class='eveni'>
        <td>&nbsp;</td>
      </tr>
      <tr class='eveni'>
        <td>&nbsp;</td>
      </tr>
      <tr class='eveni'>
        <td>&nbsp;</td>
      </tr>
      <tr class='eveni'>
        <td>&nbsp;</td>
      </tr>
      <tr class='eveni'>
        <td>&nbsp;</td>
      </tr>
      <tr class='eveni'>
        <td>&nbsp;</td>
      </tr>
      <tr class='eveni'>
        <td>&nbsp;</td>
      </tr>
      <tr class='eveni'>
        <td>&nbsp;</td>
      </tr>
      <tr class='eveni'>
        <td>&nbsp;</td>
      </tr>
      <tr class='eveni'>
        <td>&nbsp;</td>
      </tr>
      <tr class='eveni'>
        <td>&nbsp;</td>
      </tr>
      <tr class='eveni'>
        <td>&nbsp;</td>
      </tr>
      <tr class='eveni'>
        <td>&nbsp;</td>
      </tr>
      <tr class='eveni'>
        <td>&nbsp;</td>
      </tr>
      <tr class='eveni'>
        <td>&nbsp;</td>
      </tr>
      <tr class='eveni'>
        <td>&nbsp;</td>
      </tr>
      <tr class='eveni'>
        <td>&nbsp;</td>
      </tr>
    </table>
";
?>