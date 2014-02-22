<?php

class Bd{
	/**
	 * Nombre de la base de datos a conectar
	 *
	 * @var string
	 */
	protected  $vBaseDato;
	
	/**
	 * Valor del servidor a conectar
	 *
	 * @var string
	 */
	protected $vServidor;
	
	/**
	 * Usuario autorizado a conestarse
	 *
	 * @var string
	 */
	protected $vUsuario;
	
	/**
	 * Clave del usuario autorizado
	 *
	 * @var string
	 */
	protected $vClave;
	
	/**
	 * Identificador de Conexion
	 *
	 * @var integer
	 */
	protected $vIdConexion = 0;
	
	/**
	 * Identificador de Consulta
	 *
	 * @var integer
	 */
	protected $vIdConsulta = 0;
	
	/**
	 * Texto Error 
	 *
	 * @var string
	 */
	public $vTextError = "";

	/**
	 * Texto Error 
	 *
	 * @var string
	 */
	public $vErrno = 0;
	/**
	 * Texto Error 
	 *
	 * @var string
	 */
	public $vError = "";

	/**
	 * Constructor. Instancia del objeto Bd.
	 * 
	 * @access public
	 * 
	 */
	public function __construct(){ 
		$this->vBaseDato= ""; 
		$this->vServidor= ""; 
		$this->vUsuario= ""; 
		$this->vClave= "";
		$this->vIdConexion= "";
		$this->vTextError= "";
		$this->vErrno= 0;
		$this->vError= "";		
	}
	
	/**
	 * Destruye la conexion.
	 * 
	 * @access public
	 *
	 */
	public function __destruct(){
		$this->CloseConexion();
	}


   /**
    * Conecta a la base de datos.
    * @param string $server
    * @param string $db
    * @param string $user
    * @param string $pass
    * @return resource $id_conexion
    * 
    */	
	
	public function ConnectDataBase($server, $db, $user, $pass){
		
		// Variables de conexion
		$this->vServidor= $server;
		$this->vBaseDato= $db;
		$this->vUsuario= $user;
		$this->vClave= $pass;

		
		// Conecta al servidor
		if(!$this->vIdConexion= mysql_connect($this->vServidor, $this->vUsuario, $this->vClave)){
			echo "Servidor".$this->vServidor;
			echo "<br>Usuario".$this->vUsuario;
			echo "<br>Clave".$this->vClave;
				
			$this->vTextError= "Imposible conexi&oacute;n a la base de datos. Por favor reintente m&aacute;s tarde...";
			return false;
		}
		
		//SELECCIONAMOS LA BASE DE DATOS
		if (!@mysql_select_db($this->vBaseDato, $this->vIdConexion)) {
			$this->vTextError = "Imposible abrir ".$this->vBaseDato ;
			return false;
		}		
		
		// SI HEMOS TENIDO EXITO CONECTANDO DEVUELVE EL IDENTIFICADOR DE LA CONEXION, SINO DEVUELVE 0
		return $this->vIdConexion; 
	}
	
	/**
	 * Cierre de la conexion.
	 * 
	 * @access public
	 * 
	 */
	public function CloseConexion(){
		@mysql_close($this->vIdConexion);
	}
	
	/**
	 * Ejecuta la consulta.
	 *
	 * @access public
	 * @param string $sql
	 * @return resource $id_consulta
	 * 
	 */
	public function ExecuteQuery($sql=""){  
		
		// Si el SQL esta vacio no ejecuta nada
		if($sql==""){
			$this->vTextError= "No ha especificado una consulta SQL";
			return false;
		}
		
		// Limpia los parametros
		//$sql= $this->CleanParameters($sql,$params);
		//echo $sql."<br>";
		//exit();
		
		// Ejecuta la consulta
		if( !$this->vIdConsulta= @mysql_query($sql, $this->vIdConexion) ){
			$this->vTextError= "No se ha podido ejecutar una consulta SQL - ".@mysql_errno()." : ".@mysql_error();
			return false;
		}
		
		// SI HEMOS TENIDO EXITO CONECTANDO DEVUELVE EL IDENTIFICADOR DE LA CONSULTA, SINO DEVUELVE 0
		return $this->vIdConsulta;
	}	
	

	
	/**
	 * Ejecuta la consulta Limpia.
	 *
	 * @access public
	 * @param string $sql
	 * @return resource $id_consulta
	 * 
	 */
	public function ExecuteQueryClean($sql="", $params=""){  
		
		// Si el SQL esta vacio no ejecuta nada
		if($sql==""){
			$this->vTextError= "No ha especificado una consulta SQL";
			return false;
		}
		
		// Limpia los parametros
		//$sql= $this->CleanParameters($sql,$params);
		//echo $sql."<br>";
		//exit();
		
		// Ejecuta la consulta
		if( !$this->vIdConsulta= @mysql_query($sql, $this->vIdConexion) ){
			$this->vTextError= "No se ha podido ejecutar una consulta SQL - ".@mysql_errno()." : ".@mysql_error();
			return false;
		}
		
		// SI HEMOS TENIDO EXITO CONECTANDO DEVUELVE EL IDENTIFICADOR DE LA CONSULTA, SINO DEVUELVE 0
		return $this->vIdConsulta;
	}	
	
	/**
	 * Devuelve un array con los campos del resultado.
	 *
	 * @param resource $result
	 * @return array $array
	 * 
	 */
	public function GetArrayInfo($result){
		if(is_resource($result)){
			$array= @mysql_fetch_array($result);
		}
		else{
			$array="";
		}
		return $array;
	}
	
	/**
	 * Devuelve el numero de registros de una consulta.
	 * 
	 * @access public
	 * @param resource $result
	 * @return integer $total
	 * 
	 */
	public function GetNumberRows($result){
		if(is_resource($result)){
			$total= @mysql_num_rows($result);
		}
		else{
			$total=0;
		}
		return $total;
	}
		
	/**
	 * Devuelve el ID del ultimo elemento insertado
	 *
	 * @return integer
	 * 
	 */
	public function GetLastInsert($result){
		if(is_resource($result)){
			return @mysql_insert_id($result);
		}
		else{
			return false;
		}
	}

	/**
	 * Limpia los parametro dados y devuelve el SQL final
	 *
	 * @param string $query
	 * @param array $values
	 * @return string $sql
	 * 
	 */
	private function CleanParameters($query, $values){
		
		// Si no hay arreglo devuelve el query 
		if(!is_array($values) || !count($values)){
			return $query;
		}
		
		// Divide el query en trozos
		$trozos= explode("%?", $query);
		$sql= $trozos[0];
		
		// Completa el query con los parametros dados
		for($i=0; $i < count($values); $i++){
			
			// Limpia el parametro
			if($values[$i] || $values[$i]==0){
				
				// Retira las barras
				$arg= stripslashes($values[$i]);
				
				// Escapa caracteres especiales
				if(is_numeric($arg)){
					$parametro= $arg;
				}
				else{
					$parametro= "'".pg_escape_string($arg)."'";
				}
			}
			else{
				$parametro= 'NULL';
			}
			
			$sql.= $parametro.$trozos[$i+1];
		}
		
		return $sql;
	}	
	
}

?>
