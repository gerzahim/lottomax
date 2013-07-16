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
	 * Busqueda de Tickets Segun parametro.
	 *
	 * @param string $id_ticket
         * @param string $serial
	 */
	public function GetListadosegunVariable($parametro_where){

		//Preparacion del query
                 $sql = "SELECT * FROM ticket WHERE status='1' AND pagado=0 AND ".$parametro_where;
                 //echo $sql;
		$result= $this->vConexion->ExecuteQuery($sql);
		return  $result;

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
	public function GetDetalleTciket($id_ticket, $cantidad, $pagina){

                // Datos para la paginacion
		$inicial= ($pagina-1) * $cantidad;
		
		//Preparacion del query
                 $sql = "SELECT S.id_sorteo, S.nombre_sorteo, DT.id_detalle_ticket, DT.hora_sorteo, DT.numero, DT.id_tipo_jugada, TJ.nombre_jugada, DT.id_zodiacal, Z.nombre_zodiacal, DT.monto
                        FROM detalle_ticket DT
                            INNER JOIN sorteos S ON DT.id_sorteo=S.id_sorteo
                            INNER JOIN zodiacal Z ON DT.id_zodiacal=Z.Id_zodiacal
                            INNER JOIN tipo_jugadas TJ ON DT.id_tipo_jugada=TJ.id_tipo_jugada
                        WHERE id_ticket='".$id_ticket."'";
                        
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
                $result= $this->vConexion->ExecuteQuery($sql);

                return array('pagina'=>$pagina,'total_paginas'=>$total_paginas,'total_registros'=>$total_registros,'result'=>$result);

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
	 * @param string $id_tipo_jugada
	 */
	public function GetRelacionPagos($id_tipo_jugada){

		//Preparacion del query
                 $sql = "SELECT monto FROM relacion_pagos WHERE id_tipo_jugada='".$id_tipo_jugada."'";

		$result= $this->vConexion->ExecuteQuery($sql);
                $roww= $this->vConexion->GetArrayInfo($result);
                return $roww['monto'];

	}

        /**
	 * Actualiza Datos del ticket en premiadoo 1, pagado 1 y el monto total del premio
	 * @param string $id_ticket
         * @param string $total_premiado
	 */
	public function PagarTicket($id_ticket, $total_premiado){

		//Preparacion del query
		$sql = "UPDATE `ticket` SET `premiado`='1', `pagado`='1', `total_premiado`='".$total_premiado."' WHERE id_ticket='".$id_ticket."'";
                
		return $this->vConexion->ExecuteQuery($sql);

	}

        /**
	 * Actualiza Datos del ticket en detalle ticket  a premiadoo 1.
	 * @param string $id_detalle_ticket
	 */
	public function PagarDetalleTicket($id_detalle_ticket){

		//Preparacion del query

		$sql = "UPDATE `detalle_ticket` SET `premiado`='1' WHERE id_detalle_ticket='".$id_detalle_ticket."'";
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
	 *
	 *
	 */
	public function GetAprox_abajo(){

		//Preparacion del query
                $sql = "SELECT aprox_abajo FROM parametros";

		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		if ($roww["aprox_abajo"]==1){
                    return true;
                }else{
                    return false;
                }


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