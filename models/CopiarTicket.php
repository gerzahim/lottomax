<?php

/**
 * Archivo del modelo para modulo de Copiar Ticket
 * @package CopiarTicket.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Abril - 2013
 */

class CopiarTicket{

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
	 * Obtiene todos los datos de tickets
	 *
	 * @param string $cantidad
	 * @param string $pagina
	 * @return boolean, array
	 */
	public function GetListado($cantidad, $pagina){
		
		// Datos para la paginacion
		$inicial= ($pagina-1) * $cantidad;
		
		//Preparacion del query
		$sql = "SELECT * FROM ticket WHERE status='1' ORDER BY fecha_hora DESC";
		$result= $this->vConexion->ExecuteQuery($sql);
		
		
		// Datos para la paginacion
		$total_registros= $this->vConexion->GetNumberRows($result);
		if($cantidad!=0){
			$total_paginas= ceil($total_registros / $cantidad);
		}
		else{
			$total_paginas= 0;
		}
		
		// Nuevo SQL
		$sql.=" LIMIT ".$cantidad." OFFSET ".$inicial."";
		$result=  $this->vConexion->ExecuteQuery($sql);
		
		return array('pagina'=>$pagina,'total_paginas'=>$total_paginas,'total_registros'=>$total_registros,'result'=>$result);
		
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
		return $this->vConexion->ExecuteQuery($sql);
	
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
	 * Busqueda de Tickets Segun parametro.
	 *
	 * @param string $id_ticket
         * @param string $serial
	 */
	public function GetListadosegunVariable($parametro_where){

		//Preparacion del query
                 $sql = "SELECT * FROM ticket WHERE status='1' AND ".$parametro_where;
                 
		$result= $this->vConexion->ExecuteQuery($sql);
		return  $result;

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
	 * Busqueda de detalle de Tickets Segun parametro.
	 *
	 * @param string $id_ticket
         * @param string $serial
	 */
	public function GetDetalleTicket($id_ticket){

		//Preparacion del query
        $sql = "SELECT * FROM detalle_ticket WHERE id_ticket='".$id_ticket."'";
        
		$result= $this->vConexion->ExecuteQuery($sql);
		return  $result;

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
	 * Busqueda en tabla de Numeros_Jugados
	 *
	 * @param string $numero
	 * @param string $sorteo
	 * @return boolean, array
	 */
	public function GetTicketTransaccional($numero, $sorteo, $id_zodiacal, $id_tipo_jugada){
			
		//Preparacion del query
		$sql = "SELECT * FROM ticket_transaccional WHERE id_taquilla='".$_SESSION["taquilla"]."' AND numero = ".$numero." AND id_sorteo  = ".$sorteo." AND id_sorteo  = ".$sorteo." AND id_zodiacal = ".$id_zodiacal." AND id_tipo_jugada = ".$id_tipo_jugada."";

		//echo $sql;
		
		$result= $this->vConexion->ExecuteQuery($sql);  

		// Datos para la paginacion
		$total_registros= $this->vConexion->GetNumberRows($result);
		
		$roww= $this->vConexion->GetArrayInfo($result);
		
		return array('total_registros'=>$total_registros,'monto_faltante'=>$roww["monto_faltante"], 'monto'=>$roww["monto"], 'incompleto'=>$roww["incompleto"]);
		
	}
	
	/**
	 * Obtiene el pre del Zodiacal Segun ID
	 *
	 * @param string $id
	 * @return boolean, array
	 */
	public function GetPreNombreSigno($id){
	
		//Preparacion del query
		$sql = "SELECT pre_zodiacal FROM zodiacal WHERE Id_zodiacal  = ".$id."";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["pre_zodiacal"];
	
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
		$sql = "SELECT id_numeros_jugados, monto_restante FROM numeros_jugados 
							
				WHERE numero = ".$numero." AND id_sorteo  = ".$sorteo." AND id_zodiacal = ".$id_zodiacal." AND fecha LIKE '%".$fecha_hoy."%' ";
	//	exit;
		$result= $this->vConexion->ExecuteQuery($sql);
		
		
		
		//numeros_jugados
		//id_numero_jugados	fecha	numero	id_sorteo	id_tipo_jugada	id_zodiacal	monto_restante

		// Datos para la paginacion
		$total_registros= $this->vConexion->GetNumberRows($result);
		
		$roww= $this->vConexion->GetArrayInfo($result);
		
		return array('total_registros'=>$total_registros,'monto_restante'=>$roww["monto_restante"],'id_numeros_jugados'=>$roww["id_numeros_jugados"]);
		
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
public function GuardarTicketTransaccional($numero,$id_sorteo,$id_zodiacal,$id_tipo_jugada,$montofaltante,$incompleto,$monto,$monto_restante,$id_taquilla,$id_insert_taquilla){
		
		//Preparacion del query
		$sql = "INSERT INTO `ticket_transaccional` (`numero` , `id_sorteo` , `id_zodiacal`, `id_tipo_jugada` , `monto_faltante` , `incompleto`, `monto`,`monto_restante`, `id_taquilla`,`id_insert_jugada`)
                    VALUES ('".$numero."', '".$id_sorteo."', '".$id_zodiacal."', '".$id_tipo_jugada."', '".$montofaltante."', '".$incompleto."', '".$monto."', '".$monto_restante."', '".$id_taquilla."',".$id_insert_taquilla.")";
       // echo $sql;       
		return $this->vConexion->ExecuteQuery($sql);
		
		
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
	 * Retorna el Sorteo Segun la opcion de horario que desea Cambiar
	 *
	 * @param integer $op_horario
	 * @param integer $id_sorteo
	 * @return integer $id_sorteo_new
	 */
	
	function GetSorteobyHorarioCambiar($op_horario, $id_sorteo){
		/* $op_duplicar;
		* 4 = Duplicar Igual
		* 1 = Duplicar y Cambiar Horario a Mañana
		* 2 = Duplicar y Cambiar Horario a Tarde
		* 3 = Duplicar y Cambiar Horario a Noche
		*/
		
		if($op_horario == 4){
			return $id_sorteo;
		}else{
			$id_turno= $op_horario;
			$sql = "SELECT id_loteria, id_tipo_sorteo FROM sorteos WHERE id_sorteo = ".$id_sorteo."";
			$result= $this->vConexion->ExecuteQuery($sql);
			$row= $this->vConexion->GetArrayInfo($result);
			$id_loteria= $row["id_loteria"];
			$id_tipo_sorteo= $row["id_tipo_sorteo"];
			
			//id_turno 
			$sql = "SELECT id_sorteo FROM sorteos WHERE id_turno = ".$id_turno." AND id_loteria = ".$id_loteria." AND id_tipo_sorteo = ".$id_tipo_sorteo."";
			$resulta= $this->vConexion->ExecuteQuery($sql);
			$roww= $this->vConexion->GetArrayInfo($resulta);
			$id_sorteo_new= $roww["id_sorteo"];
			return $id_sorteo_new;
		}
		
	
	}	
	
	
}		
?>