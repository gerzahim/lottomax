<?php
class Fecha{
	
	/**
	 * Constructor de la clase. Instancia.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct(){	
	}
	
	/**
	 * Devuelve la fecha actual.
	 * 
	 * @access public
	 * @return string
	 */
	public function FechaActual(){ 
		$mesArray= array( 
			1 => "Enero",  2 => "Febrero",  3 => "Marzo",  4 => "Abril", 5 => "Mayo",  6 => "Junio",  7 => "Julio",  
			8 => "Agosto",  9 => "Septiembre",  10 => "Octubre",  11 => "Noviembre",   12=> "Diciembre" 
		); 
		
		$semanaArray= array( 
			"Mon" => "Lunes", "Tue" => "Martes", "Wed" => "Miercoles",  "Thu" => "Jueves", "Fri" => "Viernes", "Sat" => "Sabado",  "Sun" => "Domingo", 
		); 
		
		return $semanaArray[date("D")]." ".date("d")." de ".$mesArray[date("n")]." de ".date ("Y"); 
	}
	
	/**
	 * Devuelve la fecha hoy formato 0000-00-00 ano mes dia.
	 * 
	 * @access public
	 * @return string
	 */
	public function FechaHoy(){ 

		$fecha_hoy = date('Y-m-d');		
		return $fecha_hoy; 
	}
	
	
	/**
	 * Devuelve la fecha y hora de hoy formato 0000-00-00 ano mes dia.
	 * 
	 * @access public
	 * @return string
	 */
	public function FechaAno(){ 

		$fecha_hoy = date('Y');		
		return $fecha_hoy; 
	}	

	/**
	 * Devuelve la fecha y hora de hoy formato 0000-00-00 ano mes dia.
	 *
	 * @access public
	 * @return string
	 */
	public function FechaHoraHoy(){
	
		$fecha_hoy = date('Y-m-d H:i');
		return $fecha_hoy;
	}	
	
	/**
	 * Devuelve la fecha hoy formato 00/00/0000 ano mes dia.
	 * 
	 * @access public
	 * @return string
	 */
	public function FechaHoy2(){ 

		$fecha_hoy = date('d/m/Y');		
		return $fecha_hoy; 
	}	
	
	/**
	 * Cambia el formato de la fecha
	 * 
	 * @access public
	 * @example $tipo=1 lo convierte a 00/00/0000
	 * @example $tipo=2 lo convierte a 0000-00-00
	 * @param date $fecha
	 * @param integer $formato
	 * @return date $nueva_fecha
	 */
	public function changeFormatDate($fecha, $formato){
		if( ($fecha=="") || ($fecha=="0000-00-00") || ($fecha=="00/00/0000") ){
			$nueva_fecha= "";
		}
		else{
			switch ($formato){
				case 1:
					$nueva_fecha= substr($fecha, 8, 2)."/".substr($fecha, 5, 2)."/".substr($fecha, 0, 4);
					break;
				case 2:
					$nueva_fecha= substr($fecha, 6, 4)."-".substr($fecha, 3, 2)."-".substr($fecha, 0, 2);
					break;
				default:
					$nueva_fecha= "";
					break;
			}
		}
		return $nueva_fecha;
	}

	/**
	 * Cambia el formato de la fecha
	 * 
	 * @access public
	 * @recieve 0000-00-00
	 * @example de 0000-00-00 lo convierte a  00-00-0000
	 * @param date $fecha
	 * @return date $nueva_fecha
	 */
	public function changeFormatDateI($fecha){
		if( $fecha=="" || $fecha=="0000-00-00"){
			$nueva_fecha= "";
		}
		else{
			$nueva_fecha= substr($fecha, 8, 2)."-".substr($fecha, 5, 2)."-".substr($fecha, 0, 4);
		}
		return $nueva_fecha;
	}	
	
	/**
	 * Cambia el formato de la fecha
	 * 
	 * @access public
	 * @recieve 00-00-0000
	 * @example de 00-00-0000 lo convierte a  0000-00-00
	 * @param date $fecha
	 * @return date $nueva_fecha
	 */
	public function changeFormatDateII($fecha){
		if( $fecha==""){
			$nueva_fecha= "0000-00-00";
		}
		else{
			$nueva_fecha= substr($fecha, 6, 4)."-".substr($fecha, 3, 2)."-".substr($fecha, 0, 2);
		}
		return $nueva_fecha;
	}	
	/**
	 * Resta dos fechas dadas y devuelve la cantidad de dias transcurridos.
	 *
	 * @access public
	 * @param date $fecha_mayor
	 * @param date $fecha_menor
	 * @return date $nueva_fecha
	 */
	public function RestaFechas($fecha_mayor,$fecha_menor){
		
		if(empty($fecha_menor)){
			$fecha_menor= $fecha_mayor;
		}
		
		// Separa la fecha
		list($anio_mayor,$mes_mayor,$dia_mayor)= split('[/.-]',$fecha_mayor);
		list($anio_menor,$mes_menor,$dia_menor)= split('[/.-]',$fecha_menor);
		
		// Calculo timestamp de las dos fechas
		$timestamp1= mktime(0,0,0,$mes_mayor,$dia_mayor,$anio_mayor);
		$timestamp2= mktime(0,0,0,$mes_menor,$dia_menor,$anio_menor);
		
		// Se obtienen los segundos y se convierten en dias
		$nueva_fecha= ($timestamp1-$timestamp2) / (60*60*24);
		
		// Devuelve el tiempo transcurrido
		return $nueva_fecha;
	}
	
	/**
	 * Verifica si la ultima solicitud fue en el mismo mes actual
	 *
	 * @access public
	 * @param date $fecha_ultima_sol
	 * @param date $fechahoy
	 * @return integer
	 */
	public function CompareDateLastSolicitudes($fecha_ultima_sol,$fechahoy){
		// Separando el Mes de la ultima fecha de la solicitud
		$compare=explode("-",$fecha_ultima_sol);
		
		// Separando el Mes de la ultima fecha de la solicitud
		$compare1=explode("-",$fechahoy);
		
		if($compare[1]==$compare1[1]) {
			return 1;
		}
		else {
			return 0;
		}
	}
	
	/**
	 * Devuelve el nombre del mes segun el numero del mismo.
	 *
	 * @access public
	 * @param integer $mes
	 * @return string
	 */
	public function GetMes($mes){
		$mesArray= array( 
			1 => "Enero",  2 => "Febrero",  3 => "Marzo",  4 => "Abril", 5 => "Mayo",  6 => "Junio",  7 => "Julio",  
			8 => "Agosto",  9 => "Septiembre",  10 => "Octubre",  11 => "Noviembre",   12=> "Diciembre" 
		);
		
		return $mesArray[date("n")];
	}
}
?>