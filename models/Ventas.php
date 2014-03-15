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
		$sql = "SELECT * FROM sorteos WHERE status = 1 ORDER BY hora_sorteo , zodiacal, nombre_sorteo ASC ";
		$sql = "SELECT * FROM sorteos WHERE id_turno =1 AND STATUS =1 ORDER BY id_loteria, zodiacal, nombre_sorteo ASC "; 
		return $this->vConexion->ExecuteQuery($sql);
	}	
	
	/**
	 * Busqueda de todos los Sorteos Manana
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetSorteosManana(){
		
		//Preparacion del query
		$sql = "SELECT * FROM sorteos WHERE id_turno =1 AND STATUS =1 ORDER BY hora_sorteo, id_loteria, zodiacal, nombre_sorteo ASC "; 
		return $this->vConexion->ExecuteQuery($sql);
	}		
	
	
	/**
	 * Busqueda de todos los Sorteos Manana
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetSorteosTarde(){
		
		//Preparacion del query
		$sql = "SELECT * FROM sorteos WHERE id_turno =2 AND STATUS =1 ORDER BY hora_sorteo, id_loteria, zodiacal, nombre_sorteo ASC "; 
		return $this->vConexion->ExecuteQuery($sql);
	}	

	
	/**
	 * Busqueda de todos los Sorteos Manana
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetSorteosNoche(){
		
		//Preparacion del query
		$sql = "SELECT * FROM sorteos WHERE id_turno =3 AND STATUS =1 ORDER BY hora_sorteo, id_loteria, zodiacal, nombre_sorteo ASC "; 
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
//		$sql = "SELECT * FROM parametros";
//		$result= $this->vConexion->ExecuteQuery($sql);
//		$roww= $this->vConexion->GetArrayInfo($result);
//		return $roww["taquilla"];
       
            return $_SESSION['taquilla'];
	}
	
	/**
	 * Busqueda de Id de Taquilla.
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetDatosParametros(){
		
		//Preparacion del query
		$sql = "SELECT * FROM parametros";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww;
	}	
	
	/**
	 * Busqueda de Id de Taquilla.
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetPreNombreSigno($id){
		
		//Preparacion del query
		$sql = "SELECT pre_zodiacal FROM zodiacal WHERE Id_zodiacal = ".$id."";
		//echo $sql;
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["pre_zodiacal"];
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
         * @param string $id_taquilla
	 * @return boolean, array
	 */
	public function GuardarTicketTransaccional($numero,$id_sorteo,$id_zodiacal,$id_tipo_jugada,$montofaltante,$incompleto,$monto,$monto_restante,$id_taquilla,$id_insert_taquilla){
		
		//Preparacion del query
		$sql = "INSERT INTO `ticket_transaccional` (`numero` , `id_sorteo` , `id_zodiacal`, `id_tipo_jugada` , `monto_faltante` , `incompleto`, `monto`,`monto_restante`, `id_taquilla`,`id_insert_jugada`)
                    VALUES ('".$numero."', '".$id_sorteo."', '".$id_zodiacal."', '".$id_tipo_jugada."', '".$montofaltante."', '".$incompleto."', '".$monto."', '".$monto_restante."', '".$id_taquilla."',".$id_insert_taquilla.")";
       //echo $sql;       
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
		$sql = "SELECT * FROM ticket_transaccional WHERE incompleto<> 2 AND id_taquilla='".$_SESSION["taquilla"]."' ORDER BY id_ticket_transaccional DESC";
		return $this->vConexion->ExecuteQuery($sql);
	}
	
	/**
	 * Busqueda de datos detalle ticket
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetDetalleTicketNoZodiacalByIdticket($id_ticket){
	
		//Preparacion del query
		$sql = "SELECT * FROM detalle_ticket WHERE id_ticket = ".$id_ticket." AND id_zodiacal = '0' ";
		$sql.= "ORDER BY id_sorteo, id_zodiacal, numero ASC";
		//echo $sql;
	
		return $this->vConexion->ExecuteQuery($sql);
	}

	/**
	 * Busqueda de datos detalle ticket
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetDetalleTicketZodiacalByIdticket($id_ticket){
	
		//Preparacion del query
		$sql = "SELECT * FROM detalle_ticket WHERE id_ticket = ".$id_ticket." AND id_zodiacal != '0' ";
		//$sql = "SELECT * FROM detalle_ticket WHERE id_ticket = ".$id_ticket." ";
		$sql.= "ORDER BY numero, id_sorteo, id_zodiacal  ASC";
		//echo $sql;
	
		return $this->vConexion->ExecuteQuery($sql);
	}	
	
	
	/**
	 * Busqueda de datos detalle ticket 
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetDetalleTicketByIdticket($id_ticket){
		
		//Preparacion del query
		$sql = "SELECT * FROM detalle_ticket WHERE id_ticket = ".$id_ticket." ";
		$sql.= "ORDER BY id_sorteo, id_zodiacal, numero ASC";
		//echo $sql;
		
		return $this->vConexion->ExecuteQuery($sql);
	}	
	

	/**
	 * Busqueda de datos detalle ticket 
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetDetalleTicketByIdticket2($id_ticket){
		
		//Preparacion del query
		$sql = "SELECT * FROM detalle_ticket WHERE id_ticket = ".$id_ticket." ";
		$sql.= "ORDER BY numero, id_sorteo, id_zodiacal ASC";
		//echo $sql;
		
		return $this->vConexion->ExecuteQuery($sql);
	}	

	
	/**
	 * Busqueda de datos Numeros Incompletos 
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetNumerosIncompletobyIdticket($id_ticket){
		
		//Preparacion del query
		$sql = "SELECT * FROM incompletos_agotados WHERE id_ticket = ".$id_ticket." ";
		$sql.= "ORDER BY incompleto, numero, id_sorteo ASC";
		//echo $sql;
		
		return $this->vConexion->ExecuteQuery($sql);
	}	
	
	
	

	/**
	 * Busqueda de datos Numeros Incompletos en Ticket Transaccional
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetNumerosIncompletosTransaccional($id_taquilla){
		//echo "PASa";
		//Preparacion del query
		$sql = "SELECT * FROM ticket_transaccional WHERE id_taquilla = '".$id_taquilla."' AND (incompleto ='1' OR incompleto ='2')  ORDER BY id_ticket_transaccional DESC ";
		//$sql.= "ORDER BY incompleto, numero, id_sorteo ASC";	
		//echo $sql;

		return $this->vConexion->ExecuteQuery($sql);
	}
	

	
	/**
	 * Busqueda de datos Numeros Incompletos en Ticket Transaccional
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetNumerosIncompletosTransaccionalZodiacal($id_taquilla){
		//echo "PASa";
		//Preparacion del query
		$sql = "SELECT * FROM ticket_transaccional WHERE id_taquilla = '".$id_taquilla."' AND id_zodiacal !='0' AND incompleto !='0' ORDER BY incompleto ASC ";
		//echo $sql;
		return $this->vConexion->ExecuteQuery($sql);
	}	
		
     /**
	 * Busqueda de datos ticket transaccional, incluyendo los registrados como agotados(2)
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetDatosAllTicketTransaccional(){

		//Preparacion del query
		$sql = "SELECT * FROM ticket_transaccional WHERE id_taquilla='".$_SESSION["taquilla"]."' ORDER BY id_ticket_transaccional DESC";
		return $this->vConexion->ExecuteQuery($sql);
	}

        /**
	 * Busqueda de triples en ticket transaccional
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetTriplesTicketTransaccional(){

		//Preparacion del query
		$sql = "SELECT * FROM ticket_transaccional WHERE id_tipo_jugada='1' AND id_taquilla='".$_SESSION["taquilla"]."' ORDER BY id_ticket_transaccional DESC";
		return $this->vConexion->ExecuteQuery($sql);
	}
	
	/**
	 * Busqueda de triples en ticket transaccional
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetAstralesTicketTransaccional(){
	
		//Preparacion del query
		$sql = "SELECT * FROM ticket_transaccional WHERE id_tipo_jugada='3' AND id_taquilla='".$_SESSION["taquilla"]."' ORDER BY id_ticket_transaccional DESC";
		return $this->vConexion->ExecuteQuery($sql);
	}	

        /**
	 * Busqueda de la ultima jugada en ticket
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetLastTicket($id_taquilla){

		//Preparacion del query
		$sql = "SELECT MAX(id_ticket) as id_ticket FROM ticket WHERE status='1' AND taquilla  = ".$id_taquilla."";
		//echo $sql;
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		$id_ticket=$roww["id_ticket"];
		//echo "<pre>".print_r($roww)."</pre>";
		$sql = "SELECT id_ticket, serial, fecha_hora, total_ticket, id_usuario FROM ticket WHERE status='1' AND id_ticket  = ".$id_ticket."";
		//echo $sql;
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);		
		return $roww;		
	}
	
        /**
	 * Busqueda de la ultima jugada en ticket transaccional
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetLastTicketTransaccional(){

		//Preparacion del query
		$sql = "SELECT MAX(id_ticket_transaccional) as id_ticket_transaccional FROM ticket_transaccional WHERE id_taquilla='".$_SESSION["taquilla"]."'";
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
		//echo $sql;
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["nombre_sorteo"];
		
	}
	
	/**
	 * Obtiene el nombre del Usuario Segun ID
	 *
	 * @param string $usuario
	 * @param string $clave
	 * @return boolean, array
	 */
	public function GetNombreUsuarioById($id){
		
		//Preparacion del query
		$sql = "SELECT nombre_usuario FROM usuario WHERE id_usuario = ".$id."";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["nombre_usuario"];
		
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
	 * Obtiene el pre del Zodiacal 
	 *
	 * @param string $id
	 * @return boolean, array
	 */
	public function GetPreZodiacal(){
		
		//Preparacion del query
		$sql = "SELECT id_zodiacal, pre_zodiacal FROM zodiacal";
		$result= $this->vConexion->ExecuteQuery($sql);
		return $result;
		//print_r(mysql_fetch_array($result));
		
		/*$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["pre_zodiacal"];*/
		
	}	
	
	
	/**
	 * Devuelve si el sorteo es Zodiacal o No
	 *
	 * @param string $id
	 * @return boolean
	 */
	public function EsZodiacal(){
	
		//Preparacion del query
		//$sql = "SELECT id_sorteo FROM sorteos WHERE zodiacal = 1 OR tipoc=1";
		$sql = "SELECT id_sorteo FROM sorteos WHERE zodiacal = 1 OR id_tipo_sorteo=3";
		
		$result= $this->vConexion->ExecuteQuery($sql);
		//$row= $this->vConexion->GetArrayInfo($result);
		return $result;	
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
	public function GetTicketTransaccional($numero, $sorteo, $id_zodiacal, $id_tipo_jugada){
			
		//Preparacion del query
		$sql = "SELECT * FROM ticket_transaccional WHERE numero = ".$numero." AND id_sorteo  = ".$sorteo." AND id_sorteo  = ".$sorteo." AND id_zodiacal = ".$id_zodiacal." AND id_tipo_jugada = ".$id_tipo_jugada."";

		$result= $this->vConexion->ExecuteQuery($sql);  

		// Datos para la paginacion
		$total_registros= $this->vConexion->GetNumberRows($result);
		
		$roww= $this->vConexion->GetArrayInfo($result);
		
		return array('total_registros'=>$total_registros,'monto_faltante'=>$roww["monto_faltante"], 'monto'=>$roww["monto"], 'incompleto'=>$roww["incompleto"], 'id_ticket_transaccional'=>$roww["id_ticket_transaccional"],'id_taquilla'=>$roww["id_taquilla"]);
		
	}
	
	/**
	 * Obtiene el ID de un ticket transaccional
	 *
	 * @param string $numero
	 * @param string $sorteo
	 * @return boolean, string
	 */
	public function GetIDTicketTransaccional($numero, $sorteo, $id_zodiacal){

		//Preparacion del query
		$sql = "SELECT id_ticket_transaccional FROM ticket_transaccional WHERE id_taquilla='".$_SESSION["taquilla"]."' AND numero = ".$numero." AND id_sorteo  = ".$sorteo." AND id_zodiacal = ".$id_zodiacal."";

		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww['id_ticket_transaccional'];

	}

	/**
	 * Busqueda en tabla de Numeros_Jugados
	 *
	 * @param string $numero
	 * @param string $sorteo
	 * @return boolean, array
	 */
	public function GetNumerosJugados($numero, $sorteo, $id_zodiacal,$fecha_hoy){
		
		
			
		//Preparacion del query
		$sql = "SELECT id_numero_jugados, monto_restante FROM numeros_jugados 
							
				WHERE numero = ".$numero." AND id_sorteo  = ".$sorteo." AND id_zodiacal = ".$id_zodiacal." AND fecha LIKE '%".$fecha_hoy."%' ";
	//	exit;
		$result= $this->vConexion->ExecuteQuery($sql);
		
		
		
		//numeros_jugados
		//id_numero_jugados	fecha	numero	id_sorteo	id_tipo_jugada	id_zodiacal	monto_restante

		// Datos para la paginacion
		$total_registros= $this->vConexion->GetNumberRows($result);
		
		$roww= $this->vConexion->GetArrayInfo($result);
		
		if($total_registros>0)
		{
			$id_numeros_jugados=$roww["id_numero_jugados"];
			$monto_restante=$roww["monto_restante"];
		}
		else
		{
			$id_numeros_jugados=0;
			$monto_restante=0;
		}
		
	/*	echo $sql;		
		echo $id_numeros_jugados;
		echo $monto_restante;
		exit;
		*/
		return array('total_registros'=>$total_registros,'monto_restante'=>$monto_restante,'id_numeros_jugados'=>$id_numeros_jugados);
		
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

		//echo $eszodiacal, "-", $txt_numero, "<br>";
		
		$tamano_numero = strlen($txt_numero);
		
		if ($tamano_numero == 3){
			$estriple=1;	
		}
		
		if ($tamano_numero == 2){
			$estriple=0;	
		}		
		//echo $eszodiacal, "-", $txt_numero, "-", $estriple,  "<br>";	
		
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
	 * Busqueda el ultimo id de insercción para despues borrar la última jugada
	 *
	 * @param string $id_taquilla
	 * @return integer, id_insert_jugada
	 */
	
	
	public function GetUltimoIdInsert($id_taquilla){
			
		//Preparacion del query
		$sql = "SELECT MAX( id_insert_jugada) as maximo FROM ticket_transaccional WHERE id_taquilla = ".$id_taquilla." ";
	
		$result= $this->vConexion->ExecuteQuery($sql);
	
		//  cupo_especial
		// id_cupo_especial	numero	id_sorteo  monto_cupo  id_tipo_jugada  id_zodiacal  fecha_desde  fecha_hasta
	
		// Datos para la paginacion
	
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww['maximo'];
	
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

	public function Ver_Incompletos($id_taquilla){

		//Preparacion del query  	
		$sql = "SELECT ver_numeros_incompletos FROM impresora_taquillas WHERE id_taquilla = ".$id_taquilla."";
		$result= $this->vConexion->ExecuteQuery($sql);		
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["ver_numeros_incompletos"];
		
	}
		
	public function Ver_Agotados($id_taquilla){

		//Preparacion del query  	ver_numeros_agotados
		$sql = "SELECT ver_numeros_agotados FROM impresora_taquillas WHERE id_taquilla = ".$id_taquilla."";
		$result= $this->vConexion->ExecuteQuery($sql);		
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["ver_numeros_agotados"];
		
	}

	
	public function GetDatosImpresora($id_taquilla){
		
		//Preparacion del query  	
		$sql = "SELECT lineas_saltar_despues, ver_numeros_incompletos, ver_numeros_agotados FROM impresora_taquillas WHERE id_taquilla = ".$id_taquilla."";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww;
	}	
	/**
	 * Genera un ID para los tickets
	 *
	 */
	/*public function GeneraIDTicket(){
                        
		// Obtenemos el ultimo id del ticket correlativo
                $sql1 = "SELECT MAX(id_ticket) as id FROM ticket WHERE status='1' AND fecha_hora LIKE '%".date('Y-m-d')."%' ";
		$result= $this->vConexion->ExecuteQuery($sql1);
                if ($this->vConexion->GetNumberRows($result)>0){
                    $roww= $this->vConexion->GetArrayInfo($result);
                    $id=$roww["id"];
                    $id_ticket= substr($id,10,4)+1;
                    $len =0;
                    $len =strlen($id_ticket);


                    switch($len){
                        case 1:
                            $id_tic = "000".$id_ticket;
                            break;
                        case 2:
                            $id_tic = "00".$id_ticket;
                            break;
                        case 3:
                            $id_tic = "0".$id_ticket;
                            break;
                    }
                    
                }else{
                    $id_tic="0001";
                }
		

                //Generamos el prefijo aÃ±o+mes+dia+id_agencia+id_taquilla
                $fecha= date('Ymd');

                // Obtenemos el id de la agencia y taquilla
                $sql = "SELECT id_agencia FROM parametros";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		$id_agencia=$roww["id_agencia"];
                $taquilla=$_SESSION["taquilla"];


                $prefijo=$fecha.$id_agencia.$taquilla;
                return $prefijo.$id_tic;
                
;
                
	}*/

        public function GeneraIDTicket(){
        	/*
            //Generamos el prefijo ano+mes+dia+hora+minutos+segudos+id_agencia+id_taquilla
            $fecha= date('ymdHis');
            
            /// CABLEADA EL ID DE LA AGENCIA /// 
            // Obtenemos el id de la agencia y taquilla
            $sql = "SELECT id_agencia FROM parametros";
            $result= $this->vConexion->ExecuteQuery($sql);
            $roww= $this->vConexion->GetArrayInfo($result);
            $id_agencia=$roww["id_agencia"];
            $taquilla=$_SESSION["taquilla"];


            $prefijo=$fecha.$id_agencia.$taquilla;
            return $prefijo;

        	/**/
        	
        	//Generamos el prefijo ano+agencia+taquilla+correlativo
            $fecha= date('y');
            
            /// CABLEADA EL ID DE LA AGENCIA ///
            // Obtenemos el id de la agencia y taquilla
            $sql = "SELECT id_agencia FROM parametros";
            $result= $this->vConexion->ExecuteQuery($sql);
            $roww= $this->vConexion->GetArrayInfo($result);
            $id_agencia=$roww["id_agencia"];
            $taquilla=$_SESSION["taquilla"];
            
            $sql = "SELECT id_ticket FROM ticket WHERE taquilla  = ".$taquilla." ORDER BY fecha_hora DESC limit 1";
           /* echo $sql;
            exit;*/
            $result= $this->vConexion->ExecuteQuery($sql);
            
            // Existe algun ticket en esta bd ?
            if ($this->vConexion->GetNumberRows($result)>0){
            	
				$roww= $this->vConexion->GetArrayInfo($result);
				$maximo=$roww["id_ticket"];
            	
				//preguntar si es de los ticket viejos o no
	            $tamano = strlen($maximo);
	            if($tamano < 12){
	            	//Preguntar si el ano es diferente 
	            	$ano = substr($maximo, 0 , 2);
	            	if ($ano != $fecha){
	            		$correlativo=1;
	            	}else{
	            		$maximo = substr($maximo, 4);
	            		$correlativo= $maximo+1;	            		
	            	}

	            }else{
	            	$correlativo=1;
	            }
            }else{
            	$correlativo=1;
            }            

                        
            $prefijo=$fecha.$id_agencia.$taquilla.$correlativo;
            return $prefijo;            
			

        }

        /**
	 * Genera un ID para los tickets
	 *
	 */
	public function GeneraSerialTicket(){
            $longitud = 9;

		// Caracteres a utilizar para generar el identificador numerico
		$caracteres = str_shuffle('0123456789');

		// Concatena los caracteres al azar
		$cadena= '';
		for($i = 0; $i < $longitud; $i++){
			$key = rand(0,strlen($caracteres)-1);
			$cadena .= substr($caracteres,$key,1);
		}

		return $cadena;

	}

        /**
	 * Busca el serial de un ticket
	 *
	 * @param string $id_ticket
	 * @param string $serial
	 * @return boolean, array
	 */
	public function GetExisteSerialTicket($serial){

		//Preparacion del query
		$sql = "SELECT id_ticket FROM ticket WHERE status='1' AND serial = ".$serial."";
		$result= $this->vConexion->ExecuteQuery($sql);
		
		if ($this->vConexion->GetNumberRows($result)>0){
                    return true;
        }else{
			return false;
		}
	}

        /**
	 * Guardar Datos de Ticket
	 *
         * @param string $id_ticket
         * @param string $serial
         * @param string $fecha_hora
         * @param string $taquilla
         * @param string $total_ticket
         * @param string $id_usuario
	 * @return boolean, array
	 */
	public function GuardarTicket($id_ticket, $serial,$fecha_hora,$taquilla,$total_ticket,$id_usuario){

		//Preparacion del query
		$sql = "INSERT INTO `ticket` (`id_ticket`, `serial` , `fecha_hora` , `taquilla`, `total_ticket` , `id_usuario` , `premiado`, `pagado`)
                    VALUES ('".$id_ticket."', '".$serial."', '".$fecha_hora."', '".$taquilla."', '".$total_ticket."', '".$id_usuario."', '0', '0')";
			/*echo $sql;
			exit;*/
    return $this->vConexion->ExecuteQuery($sql);
         }
         
         
         /**
          * Existe Ticket no impreso
          *
          * @param string $fecha_hora
          * @param string $taquilla
          * @param string $total_ticket
          * @param string $id_usuario
          * @return boolean, array
          */
         public function ExisteTicketNoImpreso($taquilla){
         
         	//Preparacion del query
         	$sql = "SELECT * FROM ticket WHERE status='1' AND taquilla  = ".$taquilla." ORDER BY `id_ticket` DESC LIMIT 1 ";
         	$result= $this->vConexion->ExecuteQuery($sql);
         	$roww= $this->vConexion->GetArrayInfo($result);
         	if($this->vConexion->GetNumberRows($result)== 0){
         		return 1;
         	}else{
         		return $roww['impreso'];
         	}
         	
         	/*$sql = "SELECT FROM `ticket` WHERE `fecha_hora` LIKE '%".$fecha_hora."%' , `taquilla`, `total_ticket` , `id_usuario` , `premiado`, `pagado`)
                    VALUES ('".$id_ticket."', '".$serial."', '".."', '".$taquilla."', '".$total_ticket."', '".$id_usuario."', '0', '0')";
         
         	return $this->vConexion->ExecuteQuery($sql);*/
         }

        /**
	 * Guardar Datos de Detalle Ticket
	 *
         * @param string $id_ticket
         * @param string $numero
         * @param string $id_sorteo
         * @param string $fecha_sorteo
         * @param string $id_zodiacal
         * @param string $id_tipo_jugada
         * @param string $monto
	 * @return boolean, array
	 */
	public function GuardarDetalleTicket($id_ticket,$numero,$id_sorteo,$fecha_sorteo,$id_zodiacal,$id_tipo_jugada,$monto,$monto_restante,$monto_faltante){

		//Preparacion del query
		$sql = "INSERT INTO `detalle_ticket` (`id_ticket`, `numero` , `id_sorteo` , `fecha_sorteo`, `id_zodiacal` , `id_tipo_jugada` , `monto`,`monto_restante`,`monto_faltante`)
                    VALUES ('".$id_ticket."', '".$numero."', '".$id_sorteo."', '".$fecha_sorteo."', '".$id_zodiacal."', '".$id_tipo_jugada."', '".$monto."','".$monto_restante."','".$monto_faltante."')";

                return $this->vConexion->ExecuteQuery($sql);
	}

        /**
	 * Guardar Datos de numeros incompletos y agotaados del Ticket
	 *
         * @param string $fecha
         * @param string $numero
         * @param string $id_sorteo
         * @param string $id_tipo_jugada
         * @param string $id_zodiacal
         * @param string $monto_restante
         * @param string $incompleto
	 * @return boolean, array
	 */
	/*public function GuardarIncompletosAgotados($id_ticket,$fecha,$numero,$id_sorteo,$id_tipo_jugada,$id_zodiacal,$monto_restante,$incompleto){

		//Preparacion del query
		$sql = "INSERT INTO `incompletos_agotados` (`id_ticket`, `fecha`, `numero` , `id_sorteo` , `id_tipo_jugada` , `id_zodiacal`, `monto_restante`, `incompleto` )
                    VALUES ('".$id_ticket."', '".$fecha."', '".$numero."', '".$id_sorteo."', '".$id_tipo_jugada."', '".$id_zodiacal."', '".$monto_restante."', '".$incompleto."')";

                return $this->vConexion->ExecuteQuery($sql);
		
	}*/

         /**
	 * Verificar si un numero existe dentro de la tabla de numeros jugados
	 *
	 * @param string $numero
	 * @return boolean, array
	 */
	public function GetExisteNumeroJugados($numero, $id_sorteo, $id_tipo_jugada, $id_zodiacal){

		//Preparacion del query
		$sql = "SELECT id_numero_jugados, monto_restante FROM numeros_jugados WHERE numero = ".$numero." AND id_sorteo  = ".$id_sorteo." AND id_tipo_jugada  = ".$id_tipo_jugada." AND id_zodiacal = ".$id_zodiacal."";
		$result= $this->vConexion->ExecuteQuery($sql);

                return $result;
	}

        /**
	 * Actualiza Datos de Numeros Jugados
	 * @param string $id_numero_jugados
         * @param string $monto_restante
	 * @return boolean, array
	 */
	public function ActualizaNumeroJugados($id_numero_jugados,$monto_restante){

		//Preparacion del query
		$sql = "UPDATE `numeros_jugados` SET `monto_restante`='".$monto_restante."' WHERE id_numero_jugados='".$id_numero_jugados."'";
		
	/*	echo $sql;
		exit;*/
		return $this->vConexion->ExecuteQuery($sql);

	}

	/**
	 * Actualiza impreso=1 en Impresion
	 * @param string $id_ticket
	 * @return boolean, array
	 */
	public function SeteaImpresionenTicket($id_ticket){
	
		//Preparacion del query `id_ticket`, `serial`
		$sql = "UPDATE `ticket` SET `impreso`= 1 WHERE id_ticket='".$id_ticket."'";
		return $this->vConexion->ExecuteQuery($sql);
	
	}	
	
	/**
	 * Actualiza Serial para reimpresion
	 * @param string $id_ticket
	 * @param string $serial
	 * @return boolean, array
	 */
	public function ActualizaSerialReimpresion($id_ticket, $serial){
	
		//Preparacion del query `id_ticket`, `serial`
		$sql = "UPDATE `ticket` SET `serial`='".$serial."', `impreso`= 2 WHERE id_ticket='".$id_ticket."'";
		return $this->vConexion->ExecuteQuery($sql);
	
	}	

        /**
	 * Guardar Datos de numeros jugados
	 *
         * @param string $fecha
         * @param string $numero
         * @param string $id_sorteo
         * @param string $id_tipo_jugada
         * @param string $id_zodiacal
         * @param string $monto_restante
	 * @return boolean, array
	 */
	public function GuardarNumerosJugados($fecha,$numero,$id_sorteo,$id_tipo_jugada,$id_zodiacal,$monto_restante){

		//Preparacion del query
		$sql = "INSERT INTO `numeros_jugados` (`fecha`, `numero` , `id_sorteo` , `id_tipo_jugada` , `id_zodiacal`, `monto_restante` )
                    VALUES ('".$fecha."', '".$numero."', '".$id_sorteo."', '".$id_tipo_jugada."', '".$id_zodiacal."', '".$monto_restante."')";

                return $this->vConexion->ExecuteQuery($sql);

	}
	
	/**
	 * Eliminar terminales de Ticket transaccional segun un Id
	 *
	 * @param string $id_ticket_transaccional
	 * @return boolean, array
	 */
	public function EliminarTerminalTransaccional($id_taquilla){
		//Preparacion del query
		$sql = "DELETE FROM `ticket_transaccional` WHERE id_taquilla='".$id_taquilla."' AND id_tipo_jugada=2 OR id_tipo_jugada=4";
		return $this->vConexion->ExecuteQuery($sql);
	
	}

        /**
	 * Eliminar registros de Ticket transaccional segun un Id
	 *
	 * @param string $id_ticket_transaccional
	 * @return boolean, array
	 */
	public function EliminarTicketTransaccionalByTicket($id_ticket_transaccional){
		//Preparacion del query
		$sql = "DELETE FROM `ticket_transaccional` WHERE id_ticket_transaccional='".$id_ticket_transaccional."'";
		//echo $sql;
		return $this->vConexion->ExecuteQuery($sql);

	}
	
	/**
	 * Eliminar registros de Ticket transaccional segun Id de Jugada
	 *
	 * @param string $id_insert_taquilla
	 * @return boolean, array
	 */
	public function EliminarTicketTransaccionalByInsert($id_insert_taquilla){
		//Preparacion del query
		$sql = "DELETE FROM `ticket_transaccional` WHERE id_insert_jugada='".$id_insert_taquilla."'";
		return $this->vConexion->ExecuteQuery($sql);
	
	}
	
	/**
	 * Eliminar registros de Ticket transaccional segun un Id
	 *
	 * @param string $id_ticket_transaccional
	 * @return boolean, array
	 */
	public function EliminarTicketTransaccionalByTaquilla($id_taquilla){
		//Preparacion del query
		$sql = "DELETE FROM `ticket_transaccional` WHERE id_taquilla='".$id_taquilla."'";
		return $this->vConexion->ExecuteQuery($sql);
	
	}

        /**
	 * Eliminar registros de Ticket transaccional
	 *
	 * @return boolean, array
	 */
	public function EliminarAllTicketTransaccional(){
		//Preparacion del query
		$sql = "DELETE FROM `ticket_transaccional` WHERE id_taquilla='".$_SESSION["taquilla"]."'";
		return $this->vConexion->ExecuteQuery($sql);

	}
}	
?>