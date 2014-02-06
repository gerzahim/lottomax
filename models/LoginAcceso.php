<?php
class LoginAcceso{

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
	 * Verifica si los datos estan en la BD.  Si lo encuentra devuelve un arreglo con los datos principales.
	 *
	 * @param string $usuario
	 * @param string $clave
	 * @return boolean, array
	 */
	public function VerificarUsuario($usuario,$clave){
		$sql = "SELECT * FROM usuario WHERE login_usuario='".$usuario."' AND clave_usuario='".$clave."'";
		$result= $this->vConexion->ExecuteQuery($sql);
		$row=  $this->vConexion->GetArrayInfo($result);
		return $row;		
	}

        /**
	 * Guardar Datos de usuarios_taquillas
	 *
	 * @param string $id_usuario
	 * @param string $id_taquilla
	 * @return boolean, array
	 */
	public function GuardarUsuarioTaquilla($id_usuario, $id_taquilla){

		//Preparacion del query
		/************* CABLEADO **********************/
		$sql = "INSERT INTO `usuarios_taquillas` (`id_usuario` , `id_taquilla`, `time_ping`) VALUES ('".$id_usuario."', '".$id_taquilla."','".date('H:i:s')."')";
	//	echo $sql;
		return $this->vConexion->ExecuteQuery($sql);
		return true;

	}

        /**
	 * Busca una relacion de usuario y taquilla
	 *
	 * @access public
	 * @param integer $id_referencia
	 * @return boolean or array
	 */
	public function VerificarUsuarioTaquilla($id_referencia){

		//Preparacion del query
		//$date=strtotime(date('H:i:s'));
		$sql = "SELECT * FROM usuarios_taquillas WHERE id_taquilla= '".$id_referencia."' ";
		//echo $sql;	
		$result= $this->vConexion->ExecuteQuery($sql);
		if( $row=$this->vConexion->GetArrayInfo($result)){
			if((strtotime(date("H:i:s"))-strtotime($row['time_ping'])) <30)
			return false;
			else
			{
				self::EliminarUsuarioTaquilla($id_referencia);
				return true;
			}			
		}
		else
		return true;			
	}

        /**
	 * Eliminar asosiacion
	 *
	 * @param string $id_taquilla
	 * @param string $clave
	 * @return boolean, array
	 */
	public function EliminarUsuarioTaquilla($id_taquilla){
		//Preparacion del query
		$sql = "DELETE FROM `usuarios_taquillas` WHERE id_taquilla='".$id_taquilla."'";
		return $this->vConexion->ExecuteQuery($sql);

	}


        /**
	 * Actualiza el time ping del usuario
	 *
	 * @access public
	 * @param integer $id_usuario
	 * @return boolean or array
	 */
	public function UpdateTimePing($id_usuario){

		//Preparacion del query
		$sql = "UPDATE `usuarios_taquillas` SET `time_ping`='".date('H:i:s')."' WHERE id_usuario='".$id_usuario."'";
                return $this->vConexion->ExecuteQuery($sql);
	}

        /**
	 * Verifica que el time ping de los usuarios no sea superior a 2 minutos
	 *
	 * @access public
	 
	 */
	public function CheckTimePing(){

		//Preparacion del query
		$sql = "SELECT `time_ping`,`id_usuario`  FROM `usuarios_taquillas`";
                $result =  $this->vConexion->ExecuteQuery($sql);
		return $result;
	}

        /**
	 * Eliminar asosiacion de usuario en taquilla
	 *
	 * @param string $id_usuario
	 * @param string $clave
	 * @return boolean, array
	 */
	public function EliminarUsuarioTimePing($id_usuario){
		//Preparacion del query
		$sql = "DELETE FROM `usuarios_taquillas` WHERE id_usuario='".$id_usuario."'";
		return $this->vConexion->ExecuteQuery($sql);

	}
}		
?>