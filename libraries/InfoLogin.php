<?php
// Inicio de la sesion
session_start();

class InfoLogin{
	/**
	 * Nombre del usuario logeado.
	 *
	 * @var string $vUsuario
	 */
	private $vUsuario="";
	
	/**
	 * Perfil del usuario logeado.
	 *
	 * @var string $vPerfilUsuario
	 */
	private $vPerfilUsuario="";
	
	/**
	 *IP donde se conecto al sistema
	 *
	 * @var string
	 */
	private  $vIP;
	
	/**
	 * Constructor de la clase. Instancia.
	 *
	 * @param string $usuario
	 * @param integer $perfil
	 * @param string $ip
	 */
	public function __construct($usuario,$perfil, $ip){
		$this->vUsuario= $usuario;
		$this->vPerfilUsuario= $perfil;
		$this->vIP= $ip;
	}
	
	
	/**
	 * Retorna el ip del usuario.
	 *
	 * @return string $vIP
	 */
	public function GetIP(){
		return $this->vIP;
	}
	
	/**
	 * Retorna el nombre del usuario.
	 *
	 * @return string $vUsuario
	 */
	public function GetUsuario(){
		return $this->vUsuario;
	}
	
	/**
	 * Retorna el rol del usuario.
	 *
	 * @return integer $vRolUsuario
	 */
	public function GetPerfil(){
		return $this->vPerfilUsuario;
	}
		
}
?>