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
		$sql = "INSERT INTO `usuarios_taquillas` (`id_usuario` , `id_taquilla`) VALUES ('".$id_usuario."', '".$id_taquilla."')";
		return $this->vConexion->ExecuteQuery($sql);

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
		$sql = "SELECT * FROM usuarios_taquillas WHERE id_taquilla= '".$id_referencia."'";

		if( $result= $this->vConexion->ExecuteQuery($sql) ){
			return $this->vConexion->GetArrayInfo($result);
		}
		else{
			return false;
		}

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

}		
?>