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
	
}		
?>