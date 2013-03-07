<?php
class Ventas{

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
	 * Busqueda de todos los Sorteos
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetSorteos(){
		
		//Preparacion del query
		$sql = "SELECT * FROM sorteos WHERE status = 1 ORDER BY zodiacal, hora_sorteo, nombre_sorteo ASC ";
		return $this->vConexion->ExecuteQuery($sql);
	}	
	
	/**
	 * Busqueda de Id de Taquilla.
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetIdTaquilla(){
		
		//Preparacion del query
		$sql = "SELECT * FROM parametros";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["taquilla"];
	}

	/**
	 * Obtiene el nombre del Perfil Segun ID
	 *
	 * @param string $usuario
	 * @param string $clave
	 * @return boolean, array
	 */
	public function GetTrueZodiacal($id){
		
		//Preparacion del query
		$sql = "SELECT zodiacal FROM sorteos WHERE status = 1 AND id_sorteo = ".$id."";
		//echo $sql;
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		$respu = $roww["zodiacal"];
		
		//echo $respu, '<br>';
		
		if($respu == 1){
			return true;
		}else{
			return false;
		}		
	}
	
	
	/**
	 * Guardar Datos de Ticket Transaccional
	 *
	 * @param string $numero
	 * @param string $id_sorteo
	 * @param string $id_zodiacal
	 * @param string $monto 
	 * @return boolean, array
	 */
	public function GuardarTicketTransaccional($numero,$id_sorteo,$id_zodiacal,$id_tipo_jugada,$montofaltante,$incompleto,$monto){
		
		//Preparacion del query
		$sql = "INSERT INTO `ticket_transaccional` (`numero` , `id_sorteo` , `id_zodiacal`, `id_tipo_jugada` , `monto_faltante` , `incompleto`, `monto`) VALUES ('".$numero."', '".$id_sorteo."', '".$id_zodiacal."', '".$id_tipo_jugada."', '".$montofaltante."', '".$incompleto."', '".$monto."')";
               
		return $this->vConexion->ExecuteQuery($sql);
		
		
	}	

	/**
	 * Busqueda de datos ticket transaccional
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetDatosTicketTransaccional(){
		
		//Preparacion del query
		$sql = "SELECT * FROM ticket_transaccional WHERE incompleto<> 2 ORDER BY id_ticket_transaccional DESC";
		return $this->vConexion->ExecuteQuery($sql);
	}

	/**
	 * Obtiene el nombre del Sorteo Segun ID
	 *
	 * @param string $id
	 * @return boolean, array
	 */
	public function GetNombreSorteo($id){
		
		//Preparacion del query
		$sql = "SELECT nombre_sorteo FROM sorteos WHERE status = 1 AND id_sorteo  = ".$id."";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["nombre_sorteo"];
		
	}
	
	/**
	 * Obtiene el hora del Sorteo Segun ID
	 *
	 * @param string $id
	 * @return boolean, array
	 */
	public function GetHoraSorteo($id){
		
		//Preparacion del query
		$sql = "SELECT hora_sorteo FROM sorteos WHERE status = 1 AND id_sorteo  = ".$id."";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["hora_sorteo"];
		
	}	
	
	/**
	 * Obtiene el pre del Zodiacal Segun ID
	 *
	 * @param string $id
	 * @return boolean, array
	 */
	public function GetPreZodiacal($id){
		
		//Preparacion del query
		$sql = "SELECT pre_zodiacal FROM zodiacal WHERE Id_zodiacal  = ".$id."";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["pre_zodiacal"];
		
	}	

	/**
	 * Listar Preticket
	 *
	 * @param string $id
	 * @return boolean, array
	 */	
	public function ListarPreTicket(){	
			// Listado de Sorteos
			if( $result= $obj_modelo->GetDatosTicketTransaccional() ){
				$tabla= "<br><table class='table_ticket' align='center' border='1' width='90%'>";
				while($row= $obj_conexion->GetArrayInfo($result)){
					//print_r($row);
					$tabla.="<tr class='eveno'><td align='center'>SORTEO: ".$obj_modelo->GetNombreSorteo($row['id_sorteo'])."</td></tr>";
					$tabla.="<tr class='eveni'><td align='left'>".$row['numero']." x ".$row['monto']."</td></tr>";	
				}		
				$tabla.="</table>";	
			}
		return $tabla;
	}
	
	
	/**
	 * Busqueda en tabla de Numeros_Jugados
	 *
	 * @param string $numero
	 * @param string $sorteo
	 * @return boolean, array
	 */
	public function GetTicketTransaccional($numero, $sorteo, $id_zodiacal){
			
		//Preparacion del query
		$sql = "SELECT * FROM ticket_transaccional WHERE numero = ".$numero." AND id_sorteo  = ".$sorteo." AND id_zodiacal = ".$id_zodiacal."";

		$result= $this->vConexion->ExecuteQuery($sql);

		// Datos para la paginacion
		$total_registros= $this->vConexion->GetNumberRows($result);
		
		$roww= $this->vConexion->GetArrayInfo($result);
		
		return array('total_registros'=>$total_registros,'monto_faltante'=>$roww["monto_faltante"], 'monto'=>$roww["monto"], 'incompleto'=>$roww["incompleto"]);
		
	}
	
	
	/**
	 * Busqueda en tabla de Numeros_Jugados
	 *
	 * @param string $numero
	 * @param string $sorteo
	 * @return boolean, array
	 */
	public function GetNumerosJugados($numero, $sorteo, $id_zodiacal){
			
		//Preparacion del query
		$sql = "SELECT monto_restante FROM numeros_jugados WHERE numero = ".$numero." AND id_sorteo  = ".$sorteo." AND id_zodiacal = ".$id_zodiacal."";
		$result= $this->vConexion->ExecuteQuery($sql);
		
		//numeros_jugados
		//id_numero_jugados	fecha	numero	id_sorteo	id_tipo_jugada	id_zodiacal	monto_restante

		// Datos para la paginacion
		$total_registros= $this->vConexion->GetNumberRows($result);
		
		$roww= $this->vConexion->GetArrayInfo($result);
		
		return array('total_registros'=>$total_registros,'monto_restante'=>$roww["monto_restante"]);
		
	}	
	
	
	
	/**
	 * Busqueda en tabla de Cupos_Especiales
	 *
	 * @param string $numero
	 * @param string $sorteo
	 * @return boolean, array
	 */
	
	
	public function GetCuposEspeciales($numero, $sorteo, $id_zodiacal){
		
		
			
		//Preparacion del query
		$sql = "SELECT * FROM cupo_especial WHERE numero = ".$numero." AND id_sorteo  = ".$sorteo." AND id_zodiacal = ".$id_zodiacal."";
		$result= $this->vConexion->ExecuteQuery($sql);

		// Datos para la paginacion
		$total_registros= $this->vConexion->GetNumberRows($result);
		
		return array('total_registros'=>$total_registros,'result'=>$result);
		
	}
	
	/**
	 * Busqueda en id de tipos de jugadas segun parametros
	 *
	 * @param string $eszodiacal
	 * @param string $estriple
	 * @return boolean, array
	 */
	
	
	public function GetTipoJugada($eszodiacal,$txt_numero){

		$tamano_numero = strlen($txt_numero);
		
		if ($tamano_numero == 3){
			$estriple=1;	
		}
		
		if ($tamano_numero == 2){
			$estriple=0;	
		}		
			
		//Preparacion del query
		$sql = "SELECT id_tipo_jugada FROM tipo_jugadas WHERE zodiacal = ".$eszodiacal." AND triple  = ".$estriple."";

		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["id_tipo_jugada"];
		
	}	
	
	/**
	 * Busqueda en tabla de Cupos_Especiales
	 *
	 * @param string $numero
	 * @param string $sorteo
	 * @return boolean, array
	 */
	
	
	public function GetCuposGenerales($id_tipo_jugada){
			
		//Preparacion del query
		$sql = "SELECT monto_cupo FROM cupo_general WHERE id_tipo_jugada  = ".$id_tipo_jugada."";
		$result= $this->vConexion->ExecuteQuery($sql);
		
		//  cupo_especial
		// id_cupo_especial	numero	id_sorteo  monto_cupo  id_tipo_jugada  id_zodiacal  fecha_desde  fecha_hasta

		// Datos para la paginacion
		$total_registros= $this->vConexion->GetNumberRows($result);
		
		$roww= $this->vConexion->GetArrayInfo($result);
		
		return $roww["monto_cupo"];
		
	}	

	/**
	 * Comparar si hoy esta entre 2 fechas.
	 *
	 * @param string $primera //la fecha es mas baja
	 * @param string $segunda //la fecha es mas alta
	 * @return boolean 0 no esta entre las fechas; 1 si esta entre las fechas
	 * 
	 * $primera = "2013-02-23"; fecha_desde NO -> 2013-02-26 00:00:00	
	 * $segunda = "2013-02-23"; fecha_hasta	NO -> 2013-02-26 00:00:00	
	 */
	
	public function entreFechasYhoy($primera, $segunda){
		
	  $valoresPrimera = explode ("-", $primera);   
	  $valoresSegunda = explode ("-", $segunda); 
	 
	  $diaPrimera    = $valoresPrimera[2];  
	  $mesPrimera  = $valoresPrimera[1];  
	  $anyoPrimera   = $valoresPrimera[0]; 
	 
	  $diaSegunda   = $valoresSegunda[2];  
	  $mesSegunda = $valoresSegunda[1];  
	  $anyoSegunda  = $valoresSegunda[0];
	 
	  $diasPrimeraJuliano = gregoriantojd($mesPrimera, $diaPrimera, $anyoPrimera);  
	  $diasSegundaJuliano = gregoriantojd($mesSegunda, $diaSegunda, $anyoSegunda);
	
	  //echo $diasPrimeraJuliano, " ",$diasSegundaJuliano, "<br>";
	 
	  if(!checkdate($mesPrimera, $diaPrimera, $anyoPrimera)){
	    echo "La fecha ".$primera." no es v&aacute;lida";
	    return 0;
	  }elseif(!checkdate($mesSegunda, $diaSegunda, $anyoSegunda)){
	    echo "La fecha ".$segunda." no es v&aacute;lida";
	    return 0;
	  }else{
		
	  	$hoyJuliano = gregoriantojd(date("m"), date("d"), date("Y"));
	
	  	$diferenciadias = $diasSegundaJuliano - $diasPrimeraJuliano;
	  	
	  	if ( ($hoyJuliano >= $diasPrimeraJuliano) && ($diasSegundaJuliano >= $hoyJuliano) ){
			//estoy entre las fechas
	  		$respuestas = 1;	
	  	}else {
	  		// No se encuentra entre las fechas
	  		$respuestas = 0;
	  	}
	    return $respuestas;
	  } 
	 
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
	
	
}		
?>