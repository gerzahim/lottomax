<?php
class Usuario{

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
	 * Busqueda de Todos los Usuarios.
	 *
	 * @param string $usuario
	 * @param string $clave
	 * @return boolean, array
	 */
	public function GetListado($cantidad, $pagina){
		
		// Datos para la paginacion
		$inicial= ($pagina-1) * $cantidad;
		
		//Preparacion del query
		$sql = "SELECT * FROM usuario WHERE id_status_usuario <> 0";
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
	 * Busqueda Usuarios Segun parametro.
	 *
	 * @param string $usuario
	 * @param string $clave
	 * @return boolean, array
	 */
	public function GetListadosegunVariable($nombreCampo,$parametro){
		
		//Preparacion del query
		$sql = "SELECT * FROM usuario WHERE ".$nombreCampo." LIKE '%".$parametro."%'";
		$result= $this->vConexion->ExecuteQuery($sql);		
		return  $result;		
		
	}

	
	/**
	 * Obtiene el nombre del Perfil Segun ID
	 *
	 * @param string $usuario
	 * @param string $clave
	 * @return boolean, array
	 */
	public function GetNombrePerfil($id){
		
		//Preparacion del query
		$sql = "SELECT nombre_perfil FROM perfil WHERE id_perfil = ".$id."";
		$result= $this->vConexion->ExecuteQuery($sql);
		$roww= $this->vConexion->GetArrayInfo($result);
		return $roww["nombre_perfil"];
		
	}

	/**
	 * Busqueda de todos los Perfiles
	 *
	 * @access public
	 * @return boolean
	 */
	public function GetPerfiles(){
		
		//Preparacion del query
		$sql = "SELECT * FROM perfil";
		return $this->vConexion->ExecuteQuery($sql);
	}	
	

	/**
	 * Verifica si selecciono Perfil
	 *
	 * @access public
	 * @return boolean
	 */
	public function VerificaSeleccion_Perfil($perfil){
		
		if($perfil == 0){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Guardar Datos de Usuario
	 *
	 * @param string $usuario
	 * @param string $clave
	 * @return boolean, array
	 */
	public function GuardarDatosUsuario($nombre,$email,$perfil,$login,$pass){
		
		//Preparacion del query
		$sql = "INSERT INTO `usuario` (`id_perfil` , `nombre_usuario` , `email_usuario` , `login_usuario` , `clave_usuario` , `id_status_usuario` ) VALUES ('".$perfil."', '".$nombre."', '".$email."', '".$login."', '".$pass."', 1)";
		return $this->vConexion->ExecuteQuery($sql);
		
	}
	
	/**
	 * Eliminar Datos de Usuario
	 *
	 * @param string $usuario
	 * @param string $clave
	 * @return boolean, array
	 */
	public function EliminarDatosUsuario($id_usuario){		
		//Preparacion del query
		$sql = "UPDATE `usuario` SET `id_status_usuario`= 0 WHERE id_usuario='".$id_usuario."'";
		return $this->vConexion->ExecuteQuery($sql);
		
	}	
	
	/**
	 * Actualiza Datos de Usuario
	 *
	 * @param string $usuario
	 * @param string $clave
	 * @return boolean, array
	 */
	public function ActualizaDatosUsuario($id_usuario,$nombre,$email,$perfil,$login,$pass){
		
		//Preparacion del query
		$sql = "UPDATE `usuario` SET `id_perfil`='".$perfil."', `nombre_usuario`='".$nombre."', `email_usuario`='".$email."' , `login_usuario` ='".$login."', `clave_usuario` ='".$pass."' WHERE id_usuario='".$id_usuario."'";
		return $this->vConexion->ExecuteQuery($sql);
		
	}

	/**
	 * Busca los datos del Usuario
	 *
	 * @access public
	 * @param integer $id_referencia
	 * @return boolean or array
	 */
	public function GetDatosUsuario($id_referencia){
		
		//Preparacion del query
		$sql = "SELECT * FROM usuario WHERE id_usuario = '".$id_referencia."'";
		
		if( $result= $this->vConexion->ExecuteQuery($sql) ){
			return $this->vConexion->GetArrayInfo($result);
		}
		else{
			return false;
		}		

	}	


	
}		
?>