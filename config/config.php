<?php
class ConfigVars{
	
	/**
	 * Titulo del sistema.
	 *
	 * @var string $vTituloSistema
	 */
	private $vTituloSistema= "Sistema Web de Loteria - Lotto 
	/Max";
		/**
	 * Titulo del banner.
	 *
	 * @var string $vTituloBanner
	 */
	private $vTituloWeb= "Lotto Max";
	
	/**
	 * Cantidad de registros a mostrar para la paginacion.
	 *
	 * @var string $vCantidadRegistros
	 */
	private $vCantidadRegistros= 10;
	
	/**
	 * Directorio de los modelos.
	 *
	 * @var string $vRutaModelo
	 */
	private $vRutaModelo= "./models/";
	
	/**
	 * Directorio de las vistas.
	 *
	 * @var string $vRutaVista
	 */
	private $vRutaVista="./views/";
	
	/**
	 * Directorio de los controladores.
	 *
	 * @var string $vRutaControlador
	 */
	private $vRutaControlador= "./controllers/";
	
	/**
	 * Directorio de las librerias. Al cambiar esta ruta se debe modificar tambien la
	 * que aparece en los siguientes archivos:
	 * index.php linea #4.
	 * login.php linea #4.
	 * export/image.php.
	 *
	 * @var string $vRutaLibrerias
	 */
	private $vRutaLibreria= "./libraries/";
	
	/**
	 * Directorio de archivos de configuracion. Al cambiar esta ruta se debe modificar
	 * tambien la que aparece en los siguientes archivos:
	 * index.php linea  #7,
	 * login.php linea #18,
	 * ajax/Ajax.php linea #3.
	 * export/Exportar.php linea #4
	 * export/Planilla.php linea #4
	 * export/graph_page.php linea #8
	 * export/reportes.php linea #8
	 * export/verificacion.php linea #4
	 *
	 * @var string $vRutaConfig
	 */
	private $vRutaConfig="./config/";
	
	/**
	 * Extension de los archivos de la vista.
	 *
	 * @var string $vExtensionVista
	 */
	private $vExtensionVista= ".html";
	
	/**
	 * Pagina inicial. Al cambiar el nombre del archivo debe cambiar el siguiente archivo:
	 * login.php linea #9.
	 *
	 * @var string $vIndex
	 */
	private $vIndex="index.php";
	
	/**
	 * Maximo tiempo de inactividad del usuario en el sistema (tiempo en segundos).
	 *
	 * @var string $vInactividad
	 */
	private $vInactividad= 400;
	
	/**
	 * Host de conexion a la base de datos.
	 *
	 * @var string $vHost
	 */
	private $vHost= "localhost";
	
	/**
	 * Puerto de conexion a la base de datos.
	 *
	 * @var integer $vPort
	 */
	private $vPort= "3306";
	
	/**
	 * Nombre de la base de datos.
	 *
	 * @var string $vDataBase
	 */
	//private $vDataBase= "grupovoi_lottomax";
	private $vDataBase= "lottomax";
	/**
	 * Usuario para conexion a la base de datos.
	 *
	 * @var string $vUsuario
	 */
	//private $vUsuario= "grupovoi_lotto";
	private $vUsuario= "root";	
	
	/**
	 * Clave para conexion a la base de datos.
	 *
	 * @var string $vClave
	 */
	//private $vClave= "secreta#7";
	private $vClave= "";	
	
	/**
	 * Esquema de  la base de datos.
	 *
	 * @var string $vEsquema
	 */
	private $vEsquema= "public";
	
	/**
	 * Arreglo que almacena las variables de configuracion.
	 *
	 * @var array $vArrayConfig
	 */
	private $vConfigVar= array();
	
	/**
	 * Constructor de la clase. Instancia.
	 *
	 */
	public function __construct() {
		// Llena el arreglo con los  atributos de la clase.
		$this->vConfigVar= array(
			'titulo_sistema'=>$this->vTituloSistema,
			'titulo_web'=>$this->vTituloWeb,
			'ruta_modelo'=>$this->vRutaModelo,
			'ruta_vista'=>$this->vRutaVista,
			'ruta_controlador'=>$this->vRutaControlador,
			'ruta_libreria'=>$this->vRutaLibreria,
			'ruta_config'=>$this->vRutaConfig,
			'ext_vista'=>$this->vExtensionVista,
			'index_page'=>$this->vIndex,
			'host'=>$this->vHost,
			'port'=>$this->vPort,
			'data_base'=>$this->vDataBase,
			'usuario_db'=>$this->vUsuario,
			'clave_db'=>$this->vClave,
			'esquema'=>$this->vEsquema,
			'tiempo_inactivo'=>$this->vInactividad,
			'num_registros'=>$this->vCantidadRegistros,
		);
	}
	
	/**
	 * Devuelve el contenido segun la clave del array.
	 *
	 * @param string $variable
	 * @return string
	 */
	public function GetVar($variable){
		return $this->vConfigVar[$variable];
	}
}
?>