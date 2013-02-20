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
	public function GuardarTicketTransaccional($numero,$id_sorteo,$id_zodiacal,$monto){
		
		//Preparacion del query
		$sql = "INSERT INTO `ticket_transaccional` (`numero` , `id_sorteo` , `id_zodiacal` , `monto`) VALUES ('".$numero."', '".$id_sorteo."', '".$id_zodiacal."', '".$monto."')";
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
		$sql = "SELECT * FROM ticket_transaccional ORDER BY id_ticket_transaccional DESC";
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
	
}		
?>