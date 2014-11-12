<?php

/**
 * Archivo del modelo para modulo de Pagar Ganador
 * @package Pagar_Ganador.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Abril - 2013
 */

class Pagar_Ganador{

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
	 * Busqueda de Tickets Segun fecha.
	 *
	 * @param string $id_ticket
         * @param string $serial
	 */
	//public function GetListadosegunVariable($parametro_where){
	public function GetListadosegunVariable($fecha_resultado){		
		//echo $fecha_resultado;
		//Preparacion del query
        //$sql = "SELECT * FROM ticket WHERE status='1' AND pagado=0 AND ".$parametro_where;
        // deberiamos colocar un parametro premiado=0, verificado=0
        // premiado cambia cuando se premia un ticket
        // verificado cambia cuando ya se reviso y no esta premiado verificado=1
        $sql = "SELECT * FROM ticket WHERE status=1 AND fecha_hora LIKE '%".$fecha_resultado."%'";
		$result= $this->vConexion->ExecuteQuery($sql);
		return  $result;

	}
	
	/**
	 * Busqueda de Tickets Segun parametro.
	 *
	 * @param string $where
	
	 */
	//public function GetListadosegunVariable($parametro_where){
	public function GetPremiado($id_ticket){
		//echo $fecha_resultado;
		//Preparacion del query
		//$sql = "SELECT * FROM ticket WHERE status='1' AND pagado=0 AND ".$parametro_where;
		// deberiamos colocar un parametro premiado=0, verificado=0
		// premiado cambia cuando se premia un ticket
		// verificado cambia cuando ya se reviso y no esta premiado verificado=1
		$sql = "SELECT * FROM ticket_diario WHERE status='1' AND pagado=0 AND id_ticket_diario=".$id_ticket." AND premiado=1" ;
		// echo $sql;
		$result= $this->vConexion->ExecuteQuery($sql);
		$total_registros= $this->vConexion->GetNumberRows($result);
		if($total_registros==0)
		{
			$sql = "SELECT * FROM ticket WHERE status='1' AND pagado=0 AND id_ticket=".$id_ticket." AND premiado=1" ;
			$result= $this->vConexion->ExecuteQuery($sql);
		}
		return  $result;
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
	 * Obtiene los resultados de acuerdo a una fecha
	 *
	 * @param string $fecha_hora
	 * @return boolean, array
	 */
	public function GetResultados($fecha_hora){
	
		//Preparacion del query
		$sql = "SELECT id_sorteo, zodiacal, numero FROM resultados WHERE fecha_hora LIKE '%".$fecha_hora."%'";
	//echo $sql;
		$result= $this->vConexion->ExecuteQuery($sql);
		
		return $result;
	}

        /**
	 * Busqueda del tiempo de Vigencia de un ticket
	 *
	 */
	public function TiempoVigencia(){

		//Preparacion del query
		$sql = "SELECT tiempo_vigencia_ticket FROM parametros";

		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["tiempo_vigencia_ticket"];

	}

        /**
	 * Busqueda de la fecha de un Ticket
	 *
	 * @param string $id_ticket
         * @param string $serial
	 */
	public function GetFechaTicket($id_ticket){

		//Preparacion del query
                 $sql = "SELECT fecha_hora FROM ticket WHERE status='1' AND id_ticket='".$id_ticket."'";

		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["fecha_hora"];

	}

       /**
	 * Busqueda de detalle de Tickets Segun id_ticket
	 *
	 * @param string $id_ticket
	 */
	public function GetDetalleTciket($id_ticket,$param){

                // Datos para la paginacion
		//$inicial= ($pagina-1) * $cantidad;
		
		//Preparacion del query
		
		if($param)
		$tabla="ticket";
		else
		$tabla="ticket_diario";
		
			
                 $sql = "SELECT S.id_sorteo, S.nombre_sorteo, DT.*,  TJ.nombre_jugada, Z.nombre_zodiacal
                        	FROM detalle_".$tabla." DT
                            INNER JOIN sorteos S ON DT.id_sorteo=S.id_sorteo
                            INNER JOIN zodiacal Z ON DT.id_zodiacal=Z.Id_zodiacal
                            INNER JOIN tipo_jugadas TJ ON DT.id_tipo_jugada=TJ.id_tipo_jugada
                        WHERE id_".$tabla."='".$id_ticket."' AND total_premiado > 0";

                 
                 /*$sql = "SELECT S.id_sorteo, S.nombre_sorteo, DT.id_total_premiado,DT.id_detalle_ticket, S.hora_sorteo, DT.numero, DT.id_tipo_jugada, TJ.nombre_jugada, DT.id_zodiacal, Z.nombre_zodiacal, DT.monto
                        FROM detalle_ticket DT
                            INNER JOIN sorteos S ON DT.id_sorteo=S.id_sorteo
                            INNER JOIN zodiacal Z ON DT.id_zodiacal=Z.Id_zodiacal
                            INNER JOIN tipo_jugadas TJ ON DT.id_tipo_jugada=TJ.id_tipo_jugada
                        WHERE id_ticket='".$id_ticket."'";
                 */
                 
		$result= $this->vConexion->ExecuteQuery($sql);

         
                return $result;

	}

//        /**
//	 * Busqueda de Resultados en una fecha
//	 *
//	 * @param string $fecha
//	 */
//	public function GetResultados($fecha){
//
//		//Preparacion del query
//                 $sql = "SELECT * FROM resultados WHERE fecha LIKE '%".$fecha."%'";
//
//		$result= $this->vConexion->ExecuteQuery($sql);
//		return  $result;
//
//	}

        /**
	 * Busqueda de una apuesta ganadora
	 *
	 * @param string $id_sorteo
         * @param string $zodiacal
         * @param string $numero
         * @param string $fecha_hora
	 */
	public function GetGanador($id_sorteo, $zodiacal, $numero, $fecha_hora, $id_tipo_jugada){

		//Preparacion del query
                if ($id_tipo_jugada == 2 || $id_tipo_jugada == 4){ // Si las jugadas son terminales
                    $sql = "SELECT id_resultados FROM resultados WHERE id_sorteo='".$id_sorteo."' AND zodiacal='".$zodiacal."' AND SUBSTR(numero,2,3)='".$numero."' AND fecha_hora LIKE '%".$fecha_hora."%'";
                }else{
                    $sql = "SELECT id_resultados FROM resultados WHERE id_sorteo='".$id_sorteo."' AND zodiacal='".$zodiacal."' AND numero='".$numero."' AND fecha_hora LIKE '%".$fecha_hora."%'";
                }
                 //echo $sql;
		$result= $this->vConexion->ExecuteQuery($sql);

                if ($this->vConexion->GetNumberRows($result)>0){
                    $roww= $this->vConexion->GetArrayInfo($result);
                    return true;
                }else{
                    return false;
                }


	}

        /**
	 * Busqueda de una aproximacion por abajo o por arriba ganadora
	 *
	 * @param string $id_sorteo
         * @param string $zodiacal
         * @param string $numero
         * @param string $fecha_hora
         * @param string $tipo_aproximacion
	 */
	public function GetAproximacion($id_sorteo, $zodiacal, $numero, $fecha_hora, $tipo_aproximacion){

		//Preparacion del query
                if ($tipo_aproximacion == 'abajo'){
                    $sql = "SELECT id_resultados FROM resultados WHERE id_sorteo='".$id_sorteo."' AND zodiacal='".$zodiacal."' AND SUBSTR(numero,2,3)-1='".$numero."' AND fecha_hora LIKE '%".$fecha_hora."%'";
                }else if ($tipo_aproximacion == 'arriba'){
                    $sql = "SELECT id_resultados FROM resultados WHERE id_sorteo='".$id_sorteo."' AND zodiacal='".$zodiacal."' AND SUBSTR(numero,2,3)+1='".$numero."' AND fecha_hora LIKE '%".$fecha_hora."%'";
                }

		$result= $this->vConexion->ExecuteQuery($sql);

                if ($this->vConexion->GetNumberRows($result)>0){
                    $roww= $this->vConexion->GetArrayInfo($result);
                    return true;
                }else{
                    return false;
                }
	}

        /**
	 * Busqueda de relacion de pagos
	 *
	 * @return boolean, array 
	 */
	public function GetRelacionPagos(){

		//Preparacion del query
		$sql = "SELECT monto,id_tipo_jugada FROM relacion_pagos ";
		$result= $this->vConexion->ExecuteQuery($sql);
        return $result;

	}

        /**
	 * Actualiza Datos del ticket en premiadoo 1, pagado 1 y el monto total del premio
	 * @param string $id_ticket
         * @param string $total_premiado
	 */
	public function PagarTicket($id_ticket, $taquilla, $id_usuario,$param){

		//Preparacion del query
		$fecha= date('Y-m-d H:i:s');
		if($param)
		$tabla="ticket";
		else
		$tabla="ticket_diario";
		
		$sql = "UPDATE `".$tabla."` SET  `pagado`='1', `fecha_hora_pagado`='".$fecha."', `taquilla_pagado`='".$taquilla."', `usuario_pagado`='".$id_usuario."'  WHERE id_".$tabla."='".$id_ticket."'";
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
	 * Busqueda de Id de Usuario.
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetIdUsuario(){
	
		//Preparacion del query
		//		$sql = "SELECT * FROM parametros";
		//		$result= $this->vConexion->ExecuteQuery($sql);
		//		$roww= $this->vConexion->GetArrayInfo($result);
		//		return $roww["taquilla"];
			
		return $_SESSION['id_usuario'];
	}	

        /**
	 * Actualiza Datos del ticket en detalle ticket  a premiadoo 1.
	 * @param string $id_detalle_ticket
	 */
	public function PremiarDetalleTicket($id_detalle_ticket, $total_premiado){

		//Preparacion del query

		$sql = "UPDATE `detalle_ticket` SET `premiado`='1', `total_premiado`='".$total_premiado."' WHERE id_detalle_ticket='".$id_detalle_ticket."'";
		//echo $sql;
		return $this->vConexion->ExecuteQuery($sql);

	}
	
	/**
	 * Quitar Premios a Ticket
	 * @param string $fecha_hora
	 * @return boolean
	 */
	public function DespremiarTicket($fecha_hora){
	
		//Preparacion del query
		$sql = "UPDATE `ticket` SET `premiado`=0,`total_premiado`='0' WHERE `fecha_hora` LIKE '%".$fecha_hora."%'";
		if($this->vConexion->ExecuteQuery($sql)  or die ('Hubo un error con el registro de los datos:' .mysql_error())){
		$sql = "UPDATE `detalle_ticket` SET `premiado`='0',`total_premiado`='0' WHERE `fecha_sorteo` LIKE '%".$fecha_hora."%'";
			return $this->vConexion->ExecuteQuery($sql) or die ('Hubo un error con el registro de los datos:' .mysql_error()); 
		}
		else
		return 0;
	}
	
	/**
	 * Actualiza Datos del ticket en verificado 1
	 * @param string $id_ticket
	 */
	public function MarcarVerificadoByIdTicket($id_ticket){
	
		//Preparacion del query
		$sql = "UPDATE `ticket` SET `verificado`='1' WHERE id_ticket='".$id_ticket."'";
	
		return $this->vConexion->ExecuteQuery($sql);
	
	}

        /**
	 * Actualiza Datos del ticket en premiadoo 1 y el monto total del premio
	 * @param string $id_ticket
         * @param string $total_premiado
	 */
	public function PremiarTicket($id_ticket, $total_premiado){

		//Preparacion del query
		$sql = "UPDATE `ticket` SET `premiado`='1', `total_premiado`='".$total_premiado."' WHERE id_ticket='".$id_ticket."'";
                
		return $this->vConexion->ExecuteQuery($sql);

	}

        /**
	 * Obtencion de valor configurado de aproximacion por abajo
 	 * @return integer, -1 Cuando no hay aproximaciones ni arriba ni abajo, 0 cuando hay abajo nada mas, 1 cuando hay tanto arriba como abajo, 2 cuando hay arriba nada mas
	 *
	 */
	public function GetAprox(){

		//Preparacion del query
        $sql = "SELECT aprox_abajo, aprox_arriba FROM parametros";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww = $this->vConexion->GetArrayInfo($result);
		if ($roww["aprox_abajo"]==0 AND $roww["aprox_arriba"]==0)
		return -1;
		else		
		if ($roww["aprox_abajo"]==1 AND $roww["aprox_arriba"]==0)
		return 0;
		else
		if ($roww["aprox_abajo"]==1 AND $roww["aprox_arriba"]==1)
		return 1;
        else
        if ($roww["aprox_abajo"]==0 AND $roww["aprox_arriba"]==1)
        return 2;

	}

         /**
	 * Obtencion de valor configurado de aproximacion por arriba
	 *
	 *
	 */
	public function GetAprox_arriba(){

		//Preparacion del query
                $sql = "SELECT aprox_arriba FROM parametros";

		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		if ($roww["aprox_arriba"]==1){
                    return true;
                }else{
                    return false;
                }


	}
        
        /**
	 * Busqueda de detalle de Tickets Segun id_ticket
	 *
	 * @param string $id_ticket
	 */
	public function GetAllDetalleTciket($id_ticket){

		//Preparacion del query
                 $sql = "SELECT *
                        FROM detalle_ticket DT
                        WHERE id_ticket='".$id_ticket."'";
                
		$result= $this->vConexion->ExecuteQuery($sql);

                return $result;

	}
}		
?>