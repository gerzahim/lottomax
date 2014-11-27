<?php

/**
 * Archivo del modelo para modulo de Cargar Resultados
 * @package Cargar_Resultados.php
 * @author Brenda Batista B. - <Brebatista@gmail.com>
 * @copyright Grupo de empresas Voila
 * @license BSD License
 * @version v 1.0 Abril - 2013
 */

class Cargar_Resultados{

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
	 * Busqueda de todos los resultados de los Sorteos en una fecha y periodo determinado (mañana, tarde, noche, todos)
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetSorteos($fecha, $periodo){

		//Preparacion del query
                switch ($periodo){
                    case '1': // Manana
                            $sql_periodo=" hora_sorteo BETWEEN '00:00' AND '14:00'" ;
                        break;
                    case '2': // Tarde
                        $sql_periodo=" hora_sorteo BETWEEN '14:01' AND '17:00'";
                        break;
                    case '3': // Noche
                        $sql_periodo=" hora_sorteo BETWEEN '17:01' AND '23:59'";
                        break;
                    default : // Todos
                        $sql_periodo="hora_sorteo <> '1'";
                        break;

                }
                
		$sql = "SELECT S.id_sorteo, S.hora_sorteo, S.id_loteria, S.id_tipo_sorteo, S.nombre_sorteo, S.zodiacal, 'signo','numero', 'id_resultado', 'bajado'
                    FROM sorteos S
                    WHERE ".$sql_periodo. " AND S.id_dias_semana LIKE '%".date('w',strtotime($fecha))."%' AND S.status = 1 AND S.id_sorteo NOT IN (SELECT id_sorteo FROM resultados WHERE fecha_hora LIKE '%".$fecha."%')
                    UNION ALL
                    SELECT S.id_sorteo, S.hora_sorteo, S.id_loteria, S.id_tipo_sorteo, S.nombre_sorteo, S.zodiacal, Z.nombre_zodiacal,R.numero, R.id_resultados, R.bajado
                    FROM resultados R
                    INNER JOIN zodiacal Z ON R.zodiacal=Z.Id_zodiacal
                    INNER JOIN  sorteos S ON S.id_sorteo=R.id_sorteo
                    WHERE ".$sql_periodo. " AND S.id_dias_semana LIKE '%".date('w',strtotime($fecha))."%' AND S.status = 1 AND R.fecha_hora LIKE '%".$fecha."%'  ORDER BY  `id_loteria` ASC, hora_sorteo ASC, id_tipo_sorteo ASC
                    ";
		/*echo $sql;
		exit;*/
		return $this->vConexion->ExecuteQuery($sql);
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
		
		/*$fecha_resultado= strtotime(substr ($fecha_resultado,0,10));
		$fecha_actual =strtotime(date('Y-m-d'));*/
	//	if($fecha_resultado<$fecha_actual)
		$sql = "SELECT id_ticket as id_ticket, fecha_hora as fecha_hora, total_premiado as total_premiado, id_agencia as id_agencia FROM ticket WHERE status=1 AND fecha_hora LIKE '%".$fecha_resultado."%'
				UNION ALL
				SELECT id_ticket_diario as id_ticket, fecha_hora as fecha_hora, total_premiado as total_premiado, id_agencia as id_agencia  FROM ticket_diario WHERE status=1 AND fecha_hora LIKE '%".$fecha_resultado."%'";
			
/*		
		$sql = "SELECT * FROM ticket_diario WHERE status=1 AND fecha_hora LIKE '%".$fecha_resultado."%'";
	*/			
		$result= $this->vConexion->ExecuteQuery($sql);
		return  $result;
	
	}
	
	/**
	 * Busqueda de Tickets Segun fecha.
	 *
	 * @param string $id_ticket
	 * @param string $serial
	 */
	//public function GetListadosegunVariable($parametro_where){
	public function GetListadosegunVariableDiario($fecha_resultado){
		//echo $fecha_resultado;
		//Preparacion del query
		//$sql = "SELECT * FROM ticket WHERE status='1' AND pagado=0 AND ".$parametro_where;
		// deberiamos colocar un parametro premiado=0, verificado=0
		// premiado cambia cuando se premia un ticket
		// verificado cambia cuando ya se reviso y no esta premiado verificado=1
	
		/*$fecha_resultado= strtotime(substr ($fecha_resultado,0,10));
			$fecha_actual =strtotime(date('Y-m-d'));*/
		//	if($fecha_resultado<$fecha_actual)
		$sql = "SELECT * FROM ticket_diario WHERE status=1 AND fecha_hora LIKE '%".$fecha_resultado."%'";
					/*		else
			 $sql = "SELECT * FROM ticket_diario WHERE status=1 AND fecha_hora LIKE '%".$fecha_resultado."%'";
			*/
		$result= $this->vConexion->ExecuteQuery($sql);
		return  $result;
	
	}
	
	/**
	 * Actualiza Datos del ticket en detalle ticket  a premiadoo 1.
	 * @param string $id_detalle_ticket
	 */
	public function PremiarDetalleTicket($id_detalle_ticket, $total_premiado){
	
		$sql = "UPDATE `detalle_ticket` SET `premiado`='1', `total_premiado`='".$total_premiado."' WHERE id_detalle_ticket='".$id_detalle_ticket."'";
		$result=$this->vConexion->ExecuteQuery($sql);
		if(mysql_affected_rows()==0){
			$sql = "UPDATE `detalle_ticket_diario` SET `premiado`='1', `total_premiado`='".$total_premiado."' WHERE id_detalle_ticket_diario='".$id_detalle_ticket."'";
			$result=$this->vConexion->ExecuteQuery($sql);
		}
		return $result;
	}
	/**
	 * Busqueda de detalle de Tickets Segun id_ticket
	 *
	 * @param string $id_ticket
	 */
	public function GetAllDetalleTciket($id_ticket){
	
		//Preparacion del query
		$sql = "SELECT id_detalle_ticket as id_detalle_ticket, id_tipo_jugada as id_tipo_jugada, numero as numero, id_sorteo as id_sorteo, monto as monto, id_zodiacal as id_zodiacal
                        FROM detalle_ticket DT
                        WHERE id_ticket='".$id_ticket."'";
	
		$result= $this->vConexion->ExecuteQuery($sql);
		$total_registros= $this->vConexion->GetNumberRows($result);
		if($total_registros==0)
		{
			$sql = "SELECT id_detalle_ticket_diario as id_detalle_ticket, id_tipo_jugada as id_tipo_jugada, numero as numero, id_sorteo as id_sorteo, monto as monto, id_zodiacal as id_zodiacal
					FROM detalle_ticket_diario WHERE id_ticket_diario=".$id_ticket ;
			$result= $this->vConexion->ExecuteQuery($sql);
		}		
		return $result;
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
	 * Busqueda de relacion de pagos
	 *
	 * @return boolean, array
	 */
	public function GetRelacionPagos(){
	
		//Preparacion del query
		$sql = "SELECT monto, id_tipo_jugada, id_agencia FROM relacion_pagos ";
		$result= $this->vConexion->ExecuteQuery($sql);
		return $result;
	
	}
	


         /**
	 * Busqueda de todos los Sorteos
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetAllSorteos(){

		//Preparacion del query
		$sql = "SELECT * FROM sorteos WHERE status = 1 ORDER BY zodiacal, hora_sorteo, nombre_sorteo ASC ";
		return $this->vConexion->ExecuteQuery($sql);
	}

        /**
	 * Busqueda de todos los zodiacales
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetZodiacales(){

		//Preparacion del query
		$sql = "SELECT * FROM zodiacal WHERE nombre_zodiacal <>'No Zodiacal' ORDER BY nombre_zodiacal ASC ";
		return $this->vConexion->ExecuteQuery($sql);
	}

       /**
	 * Verifica si un sorteo es zodiacal
	 *
	 * @param string $id_sorteo
	 * @return boolean, array
	 */
	public function GetTrueZodiacal($id_sorteo){

		//Preparacion del query
		$sql = "SELECT zodiacal FROM sorteos WHERE status = 1 AND id_sorteo = ".$id_sorteo."";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		$respu = $roww["zodiacal"];

		if($respu == 1){
			return true;
		}else{
			return false;
		}
	}
	/**
	 * Actualiza Datos del ticket en premiadoo 1 y el monto total del premio
	 * @param string $id_ticket
	 * @param string $total_premiado
	 */
	public function PremiarTicket($id_ticket, $total_premiado){
	
		//Preparacion del query
		$sql = "UPDATE `ticket` SET `premiado`='1', `total_premiado`='".$total_premiado."' WHERE id_ticket='".$id_ticket."'";
		$result=$this->vConexion->ExecuteQuery($sql);
		if(mysql_affected_rows()==0){
			$sql = "UPDATE `ticket_diario` SET `premiado`='1', `total_premiado`='".$total_premiado."' WHERE id_ticket_diario='".$id_ticket."'";
			$result=$this->vConexion->ExecuteQuery($sql);
		}
		return $result;
	}

         /**
	 * Verifica si esta registrado el resultado de un sorteo
	 *
	 * @param string $id_sorteo
         * @param string $fecha
	 * @return boolean, array
	 */
	public function GetResultadoSorteo($id_sorteo, $fecha){

		//Preparacion del query
		$sql = "SELECT id_resultados FROM resultados WHERE id_sorteo = ".$id_sorteo." AND fecha_hora  LIKE '%".$fecha."%'";
                
		$result= $this->vConexion->ExecuteQuery($sql);
                if ($this->vConexion->GetNumberRows($result) >0){
                    $roww= $this->vConexion->GetArrayInfo($result);
                    $respu = $roww["id_resultados"];
                    return $respu;
                }else{
                    return "";
                }
		
		
	}
	
	/**
	 * Obtiene los datos de un sorteo
	 *
	 * @param string $id_sorteo
	 * @param string $fecha
	 * @return boolean, array
	 */
	public function GetDatosSorteo($id_sorteo){
	
		//Preparacion del query
		$sql = "SELECT * FROM resultados WHERE id_sorteo = ".$id_sorteo;
	
		$result= $this->vConexion->ExecuteQuery($sql);
		if ($this->vConexion->GetNumberRows($result) >0){
			$roww= $this->vConexion->GetArrayInfo($result);
			//$respu = $roww["id_resultados"];
			return $roww;
		}else{
			return "";
		}
	
	
	}
	


	/**
	 * Guardar Datos de los Resultados
	 *
	 * @param string $id_sorteo
         * @param string $zodiacal
	 * @param string $numero
         * @param string $fecha_hora
	 * @return boolean, array
	 */
	public function GuardarDatosResultados($id_sorteo, $zodiacal, $numero, $fecha_hora){

		//Preparacion del query
		$sw=1;
		$sql = "INSERT INTO `resultados` (`id_sorteo` , `zodiacal`, `numero`, `fecha_hora`) VALUES ('".$id_sorteo."', '".$zodiacal."', '".$numero."', '".$fecha_hora."')";
		$this->vConexion->ExecuteQuery($sql);
		$id_resultado=mysql_insert_id();
		$result=self::buscarIdAgencias();
	//	echo "PASA";
		while ($row = mysql_fetch_array($result)){
			$sql = "INSERT INTO ``resultado_bajado_agencia` (`id_resultado` , `id_agencia`, `tipo`) VALUES ('".$id_resultado."', ".$row['id_agencia'].", 1)";
			if(!$this->vConexion->ExecuteQuery($sql))
			$sw=0;
			else 
			echo "FINISIMO";
		}
		return $sw;
		
	}
	/**
	 * Obtener los id agencias activos
	 *
	 * 
	 * 
	 * 
	 * 
	 * @return result
	 */
	public function buscarIdAgencias(){
		//Preparacion del query
		$sql = "SELECT id_agencia FROM agencias WHERE status=1";
		return $this->vConexion->ExecuteQuery($sql);
	
	}
	
	/**
	 * Setea el autoincremental en el ultimo ID posible esto por los valores eliminados de la tabla
	 *
	 * @return result
	 */
	public function reseteaID($id){
		//Preparacion del query
		$sql = "ALTER TABLE resultados AUTO_INCREMENT = ".$id;
		return $this->vConexion->ExecuteQuery($sql);
	
	}
		
	
	/**
	 * Obtiene los resultados de acuerdo a una fecha
	 *
	 * @param string $fecha_hora
	 * @return boolean, array
	 */
	public function GetResultadosRepetidos($fecha_hora){
	
		//Preparacion del query
		$sql = "SELECT id_sorteo FROM resultados WHERE fecha_hora LIKE '%".$fecha_hora."%' ";
		//echo $sql;
		$result= mysql_query($sql);
		
		return $result;
	}
	
	/**
	 * Guardar Datos de los Resultados Masivos
	 *
	 * @param string $sql
	 * @return boolean, array
	 */
	public function GuardarDatosResultadosMasivo($sql,$id_resultado,$primer_id){
	
		//Preparacion del query
		//echo "pasa";
		$result=self::buscarIdAgencias();
		$sql2 = "INSERT INTO `resultado_bajado_agencia` (`id_resultado` , `id_agencia`, `tipo`) VALUES ";
		while ($row = mysql_fetch_array($result)){
			for($i=$primer_id+1;$i<=$id_resultado;$i++)
			{
				$sql2.="(".$i.", ".$row['id_agencia'].", 1),";
			}
		}
		//echo "PASA";
		//exit;
		//echo $sql2;
		$sql2=trim($sql2,',');
		$sql2.=";";
	
		$this->vConexion->ExecuteQuery($sql2);
		return $this->vConexion->ExecuteQuery($sql);
	
	}
	
	
	/**
	 * Retorna El ultimo Id_resultado
	 *
	 * 
	 * @return int
	 */
	public function getUltimoIdResultado(){
	
		//Preparacion del query
		//echo "pasa";
		$sql="SELECT id_resultados FROM resultados ORDER by id_resultados DESC";
		$result=$this->vConexion->ExecuteQuery($sql);
		$row=$this->vConexion->GetArrayInfo($result);
		return $row['id_resultados'];
		
	
	}

       	/**
	 * Actualiza Datos de Resultados
	 * @param string $id_resultados
         * @param string $id_sorteo
         * @param string $zodiacal
	 * @param string $numero
         * @param string $fecha_hora
	 * @return boolean, array
	 */
	public function ActualizaDatosResultados($id_resultados, $id_sorteo, $zodiacal, $numero, $fecha_hora,$bajado){
		
		//Preparacion del query
		$sql = "UPDATE `resultados` SET `id_sorteo`='".$id_sorteo."', `zodiacal`='".$zodiacal."', `numero`='".$numero."', `fecha_hora`='".$fecha_hora."', `bajado`='".$bajado."' WHERE id_resultados='".$id_resultados."'";
		return $this->vConexion->ExecuteQuery($sql);
	
		
	}
	
	/**
	 * Quitar Premios a Ticket
	 * @param string $fecha_hora, $id_sorteo
	 * @return boolean, array
	 */
	public function DespremiarTicket($fecha_hora,$id_sorteo){
	
		//Sw que valilda si busca en Ticket o Ticket Diario
		$sw=0;
		//Preparacion del query
		$sql = "SELECT * FROM `detalle_ticket` WHERE `premiado`=1 AND `fecha_sorteo` LIKE '%".$fecha_hora."%' AND id_sorteo=".$id_sorteo;
		$result=$this->vConexion->ExecuteQuery($sql);
		$total_registros= $this->vConexion->GetNumberRows($result);
		if($total_registros==0)
		{
			$sql = "SELECT * FROM `detalle_ticket_diario` WHERE `premiado`=1 AND `fecha_sorteo` LIKE '%".$fecha_hora."%' AND id_sorteo=".$id_sorteo;
			$result=$this->vConexion->ExecuteQuery($sql);
			$sw=1;
		}
		
		while($row=$this->vConexion->GetArrayInfo($result)){
			if($sw==1)
			$sql = "SELECT * FROM `ticket_diario` WHERE `id_ticket_diario` = ".$row['id_ticket_diario'];
			else	
			$sql = "SELECT * FROM `ticket` WHERE `id_ticket` = ".$row['id_ticket'];
				$result2=$this->vConexion->ExecuteQuery($sql);
				while($row2=$this->vConexion->GetArrayInfo($result2)){
					$total_premiado=$row2['total_premiado']-$row['total_premiado'];
					if($total_premiado==0)
					$premiado=0;
					else
					$premiado=1;
					if($sw==1)
					$sql = "UPDATE `ticket_diario` SET `premiado`=".$premiado.", `total_premiado`=".$total_premiado." WHERE `id_ticket_diario`=".$row['id_ticket_diario'];
					else	
					$sql = "UPDATE `ticket` SET `premiado`=".$premiado.", `total_premiado`=".$total_premiado." WHERE `id_ticket`=".$row['id_ticket'];
					$this->vConexion->ExecuteQuery($sql);
					if($sw==1)
					$sql = "UPDATE `detalle_ticket_diario` SET `premiado`=0, `total_premiado`=0 WHERE `id_detalle_ticket_diario`=".$row['id_detalle_ticket_diario'];
					else
					$sql = "UPDATE `detalle_ticket` SET `premiado`=0, `total_premiado`=0 WHERE `id_detalle_ticket`=".$row['id_detalle_ticket'];
					$this->vConexion->ExecuteQuery($sql);
				}		
		}
		return true;
	}
		
	/**
	 * Busqueda de todos los Sorteos
	 *
	 * @access public
	 * @return boolean
	 */
	public function VerificarResultadoSorteo($id_sorteo, $fecha_hora){
	
		//Preparacion del query
		$sql = "SELECT id_resultados FROM resultados WHERE id_sorteo='".$id_sorteo."' AND fecha_hora='".$fecha_hora."' ";
		
		$result = $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		$respu = $roww["id_resultados"];
		return $respu;
	}	
	
         /**
	 * Busqueda de todos los Sorteos
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetHoraSorteo($id_sorteo){

		//Preparacion del query
		$sql = "SELECT hora_sorteo FROM sorteos WHERE status = 1 AND id_sorteo = ".$id_sorteo."";
		$result = $this->vConexion->ExecuteQuery($sql);
        $roww= $this->vConexion->GetArrayInfo($result);
        $respu = $roww["hora_sorteo"];
        return $respu; 		
	}	
	
	

}		
?>