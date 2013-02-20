<?php

/**
 * Clase para el manejo de varias funciones genericas.
 * 
 */
class Generica{ 
	
	/**
	 * Constructor. Instancia del objeto.
	 * 
	 * @access public
	 * @return void 
	 */
	public function __construct(){
	}
	
	/**
	 * Metodo que indica la ruta de regreso de una pagina
	 *
	 * @access public
	 * @return string
	 */
	public function RutaRegreso(){
		$ruta_script= $_SERVER['REQUEST_URI'];
		$trozos= explode("/", $ruta_script);
		return $trozos[count($trozos)-1];
	}
	
	/**
	 * Merodo que modifica la url para la paginacion
	 *
	 * @access public
	 * @return string $url
	 */
	public function UrlPaginacion(){
		$url="";
		
		// Omite la variable pg de la url
		foreach($_GET as $key=>$value){
			if($key=='pg'){
				continue;
			}
			$url.= $key."=".$value."&amp;";
		}
		
		// Elimina el ultimo ampersand de la url
		$posicion_ultimo= strrpos($url,"&amp;");
		
		// URL final
		$url= 'index.php?'.substr($url,0,$posicion_ultimo);
		return $url;
	}
	
	/**
	 * Limpia una cadena de texto o un arreglo que sea leida de la base de datos
	 *
	 * @access public
	 * @param string o array $texto
	 * @return string|array
	 */
	public function CleanTextDb($texto){
		if(is_array($texto)){
			$txt= array_map('stripslashes',$texto);
			//$txt= array_map('html_entity_decode',$txt);
		}
		else{
			$txt= stripslashes($texto); 
			//$txt= html_entity_decode( $txt );
		}
		return $txt;
	}
	
	/**
	 * Limpia una cadena de texto para ser almacenada en la base de datos
	 *
	 * @access public
	 * @param string $texto
	 * @return string
	 */
	public function CleanText($texto){
		$txt= trim($texto);
		$txt= htmlspecialchars($txt);
		$txt= self::GetQuitarAcentos($txt);
		//$txt= htmlentities($txt);
		return $txt;
	}
	
	/**
	 * Cambia los acentos por su entidad html.
	 *
	 * @access public
	 * @param string $cadena
	 * @return string
	 */
	public function GetQuitarAcentos($cadena) {
		$caracter_normal= array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ");
		$caracter_html= array(
			"&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;", 
			"&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Ntilde;"
		);
		$cadena= str_replace($caracter_normal,$caracter_html,$cadena);
		return $cadena;
	}
	
	/**
	 * Elimina los acentos por su entidad html.
	 *
	 * @access public
	 * @param string $cadena
	 * @return string
	 */
	public function QuitarAcentos($cadena) {
		$caracter_normal= array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ");
		$caracter_html= array("a","e","i","o","u","n","A","E","I","O","U","N");
		$cadena= str_replace($caracter_normal,$caracter_html,$cadena);
		return $cadena;
	}	

	/**
	 * Verifica si una cadena esta vacia
	 *
	 * @access public
	 * @param string $texto
	 * @return boolean
	 */
	public function IsEmpty($texto){
		$cantidad= $this->SizeText($texto);
		if($cantidad==0){
			return true;
		}
		else{
			return false;
		}
	}
	
	/**
	 * Retorna la cantidad de caracteres de una cadena
	 *
	 * @access public
	 * @param string $texto
	 * @return integer $cantidad
	 */
	public function SizeText($texto){
		$txt= trim($texto);
		$cantidad= strlen($txt);
		return $cantidad;
	}
	
	/**
	 * Retorna clave generada al azar
	 *
	 * @access public
	 * @param integer $longitud
	 * @return string $password
	 */
	public function GenerarPassword($longitud=0){
		
		// Verifica la longitud de la clave a generar
		if( !is_numeric($longitud) || $longitud <= 0 ){
			$longitud = 10;
		}
		
		// Caracteres a utilizar para generar la contraseña y los reordena aleatoriamente
		$caracteres = str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
		
		// Concatena los caracteres al azar
		$password= '';
		for($i = 0; $i < $longitud; $i++){
			$key = rand(0,strlen($caracteres)-1);
			$password .= substr($caracteres,$key,1);
		}
		
		return $password;
	}
	
	/**
	 * Convierte una cadena de texto completamente en mayusculas
	 *
	 * @access public
	 * @param string $texto
	 * @return string 
	 */
	public function ToUpper($texto){
		return strtoupper(trim($texto));
	}
	
	/**
	 * Convierte una cadena de texto completamente en minusculas
	 *
	 * @access public
	 * @param string $texto
	 * @return string 
	 */
	public function ToLower($texto){
		return strtolower(trim($texto));
	}
	
	/**
	 * Convierte una cadena de texto completamente en  tipo Titulo
	 *
	 * @access public
	 * @param string $texto
	 * @return string 
	 */
	public function ToTitle($texto){
		return ucwords(strtolower($texto));
	}
		
	/**
	 * Verifica si una cadena es numerica o no
	 *
	 * @access public
	 * @param string $texto
	 * @return boolean
	 */
	public function IsNumerico($texto){
		if( !$this->isEmpty($texto) ){
			if( is_numeric($texto) ){
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
	}
	
	/**
	 * Corta una cadena por la longitud de caracteres requeridos,
	 * y le concatena "..." al final de la cadena.
	 *
	 * @access public
	 * @param string $cadena
	 * @param integer $longitud
	 * @return string
	 */
	public function CortarTexto($cadena,$longitud=""){
		// Longitud maxima
		if(empty($longitud)){
			$longitud= 20;
		}
		
		// Procesamiento de la cadena si es mayor a la longitud necesaria
		if($this->SizeText($cadena) > $longitud){
			$cadena= trim( substr($cadena,0,$longitud) ).'...';
		}
		
		return $cadena;
	}
	
	/**
	 * Limpia una cadena para manipularla como clave, eliminando los
	 * caracteres especiales y los espacios en blanco.
	 *
	 * @access public
	 * @param string $cadena
	 * @return string
	 */
	public function CleanTextPassword($cadena){
		$cadena= eregi_replace('[^a-z0-9]|[[space]]', '', $cadena);
		return $cadena;
	}
	
	/**
	 * Retorna los parametros de busqueda de la url, para los enlaces
	 * de exportar listado.
	 *
	 * @access public
	 * @param string $param_get
	 * @return string
	 */
	public function GetParamSearch($param_get){
		$link="";
		foreach($param_get as $key=>$value){
			if($key=='op' || $key=='btnentrar') continue;
			$link.= '&amp;'.$key.'='.$value;
		}
		
		return $link;
	}
	
	/**
	 * Retorna en un array el contenido de un directorio
	 *
	 * @access public
	 * @param ruta folder
	 * @return array
	 */
	public function GetContentDir($dir){
		//$dir = "aliados/"; 		
		$archivos=array(); 
		// Abre un directorio y lee sus contenidos 
		if (is_dir($dir)) { 
		
		   if ($dh = opendir($dir)) { 
		       while (($file = readdir($dh)) !== false) {       
		        if ($file != "." && $file != "..") {
		            $archivos[]=$file;
		        }        
		       } 
		       closedir($dh); 
		   } 
		}		
		//print_r($archivos);		
		return $archivos;
	}	
	
	/**
	 * Cambia el formato de numero.
	 *
	 * @access public
	 * @param string $cadena
	 * @param integer $tipo_cambio
	 * @example integer $tipo_cambio=1 lo convierte a 0000.00
	 * @example integer $tipo_cambio=2 lo convierte a 0000,00
	 * @return decimal|integer
	 */
	public function CambioNumerico($cadena,$tipo_cambio) {
		if($tipo_cambio==1){
			$cadena= str_replace(',','.',$cadena);
		}
		elseif($tipo_cambio==2){
			$cadena= str_replace('.',',',$cadena);
		}
		else{
			$cadena= "";
		}
		return $cadena;
	}
	
	/**
	 * Limpia una cadena para manipularla como login, eliminando los
	 * caracteres especiales con excepcion del underscore.
	 *
	 * @access public
	 * @param string $cadena
	 * @return string
	 */
	public function CleanTextLogin($cadena){
		$cadena= trim($cadena);
		$cadena= $this->ToLower($cadena);
		$cadena= eregi_replace('[^a-z0-9_\.]', '', $cadena);
		return $cadena;
	}
	/**
	 * Convierte una cadena a numerica quitando los caracteres especiales
	 * por ejemplo, si mandamos "Bs 300" retorna solo 300 sin la unidad.
	 *
	 * @access public
	 * @param string $cadena
	 * @return string
	 */
	public function ConvertirANumero($cadena){
		$legalChars = "%[^0-9\-\. ]%";
		$cadena=preg_replace($legalChars,"",$cadena);
  		return $cadena;
	}	
	
	/**
	 * Coloca la primera letra de cada palabra en mayuscula
	 *
	 * @access public
	 * @param string $texto
	 * @return string 
	 */
	public function FirstUpper($texto){
		$texto= $this->ToLower($texto);
		return ucwords($texto);
	}
	
	/**
	 * Borra una carpeta y todo el contenido interior
	 *
	 * @access public
	 * @param string $texto
	 * @return string 
	 */
	public function rm_recursive($filepath){ 
		    if (is_dir($filepath) && !is_link($filepath)) 
		    { 
		        if ($dh = opendir($filepath)) 
		        { 
		            while (($sf = readdir($dh)) !== false) 
		            { 
		                if ($sf == '.' || $sf == '..') 
		                { 
		                    continue; 
		                } 
		                if (!rm_recursive($filepath.'/'.$sf)) 
		                { 
		                    throw new Exception($filepath.'/'.$sf.' could not be deleted.'); 
		                } 
		            } 
		            closedir($dh); 
		        } 
		        return rmdir($filepath); 
		    } 
		    return unlink($filepath); 
		}	
	
	/**
	 * Comprueba direccion de correo electronioco 
	 *
	 * @access public
	 * @param string $texto
	 * @return string 
	 */
	public function is_email($email){
		/*
		  if (preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/i',$email)){ 
			  return TRUE; 
		  } else {
			  return FALSE; 
		  }*/
		
	
	   	$mail_correcto = 0; 
	   	//compruebo unas cosas primeras 
	   	if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@")){ 
	      	 if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) { 
	         	 //miro si tiene caracter . 
	         	 if (substr_count($email,".")>= 1){ 
	            	 //obtengo la terminacion del dominio 
	            	 $term_dom = substr(strrchr ($email, '.'),1); 
	            	 //compruebo que la terminación del dominio sea correcta 
	            	 if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){ 
	               	 //compruebo que lo de antes del dominio sea correcto 
	               	 $antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1); 
	               	 $caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1); 
	               	 if ($caracter_ult != "@" && $caracter_ult != "."){ 
	                  	 $mail_correcto = 1; 
	               	 } 
	            	 } 
	         	 } 
	      	 } 
	   	} 
	   	if ($mail_correcto){
	      	 return 1;} 
	   	else{ 
	      	 return 0; 		
	   	}	  
	  
	}
		
	
	/**
	 * Comprueba Nick o Login Valido
	 *
	 * @access public
	 * @param string $texto
	 * @return string 
	 */
	public function VerificarLogin($login){ 
		   //compruebo que el tamaño del string sea válido. 
		   if (strlen($login)<3 || strlen($login)>20){ 
			  return false; 
		   } 
		
		   //compruebo que los caracteres sean los permitidos 
		   $permitidos = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_"; 
		   for ($i=0; $i<strlen($login); $i++){ 
			  if (strpos($permitidos, substr($login,$i,1))===false){ 
				 return false; 
			  } 
		   } 
		   return true; 
		      
	}
	
	/**
	 * Comparador de Claves
	 *
	 * @access public
	 * @return boolean
	 */
	public function VerificaClaveIguales($pass,$repeatpass){
		
		//Comparacion de claves
		if($pass != $repeatpass){
			return true;
		}else{
			return false;
		}
	}		
		
	/**
	 * Metodo para la paginacion de los listados
	 *
	 * @access public
	 * @param integer $pg
	 * @param integer $pages
	 * @param integer $registros
	 * @param varchar $url
	 * @return string tabla de paginacion
	 */
	public function Paginacion($pg,$pages,$registros,$url){
		
		// Indica la cantidad de registros
		$parte= '
			<table align="center" border="0" cellpadding="0" cellspacing="0" width="80%">
				<tr><td width="200" align="left">'.$registros.' registros encontrados</td>
		';
		
		// Muestra la flecha para la pagina anterior
		if ($pages <= 1){
			$parte.='<td width="170">&nbsp;</td>';
		}
		else{
			if($pg != 1){
				$num_pag= $pg - 1;
				$parte.='
					<td align="left" width="20">
						<a href="'.$url.'&amp;pg='.$num_pag.'"><img src="./images/left.gif" border="0"/></a>
					</td>
				';
			}
			else{
				$parte.='<td align="left" width="20"><img src="./images/left_gray.gif" border="0"/></td>';
			}
			
			/* ------------------------------------------------------ */
			/*      Para mostrar solo diez paginas      */
			/* ------------------------------------------------------ */
			
			// Muestra seis numeros de paginas mas a partir de la pagina actual
			$diferencia= $pages-$pg;
			if($diferencia>=6 && $pages>=10){	
				$num_pag=6;
			}
			else{
				$num_pag= $diferencia;
			}
			
			// Muestra tres paginas anteriores a la pagina actual
			$dife_pag= $pg-3;
			if($dife_pag<=0){
				$inicio_pag= 1;
				if($pages>=10){
					
					// Evita que se impriman los numeros negativos cuando se encuentra en las tres primeras paginas
					if($dife_pag == -2){
						$num_pag= $num_pag+3;
					}
					elseif($dife_pag==-1){
						$num_pag= $num_pag+2;
					}
					elseif($dife_pag==0){
						$num_pag= $num_pag+1;
					} 
				}
			}
			else{
				
				// Cuando esta en la ultima pagina
				if($pg==$pages){		
					if($pages>=10){
						$inicio_pag= $pages-9;
					}
					else{
						$inicio_pag= 1;
					}
				}
				else{
					if ($pages>=10){
						$inicio_pag= $dife_pag;
						
						// Indica hasta que numero de pagina se muestra
						$total_conteo= $pg+$num_pag; 
						
						// Indica hasta donde debe llegar el conteo 
						$conteo_estimado= $inicio_pag+9; 
						
						// Calcula cuantas paginas faltan para el despliegue
						$dif_conteo= $total_conteo-$conteo_estimado; 
						if ($total_conteo<>$conteo_estimado){
							$inicio_pag= $inicio_pag-abs($dif_conteo);
						}
					}
					else{
						$inicio_pag= 1;
					}
				}
			}
			
			// Imprime los numeros de la cantidad de paginas
			for($i=$inicio_pag;$i<=$pg+$num_pag;$i++){
			//for($i=1; $i<($pages + 1); $i++){
				if($i == $pg){
					$parte.='<td align="center" width="25"><strong>'.$i.'</strong></td>';
				}
				else{
					$parte.='<td align="center" width="25"><a href="'.$url.'&amp;pg='.$i.'" class="pagina">'.$i.'</a></td>';
				}
			}
			
			// Muestra la flecha para la pagina siguiente
			if($pg < $pages){
				$num_pag= $pg + 1;
				$parte.='
					<td align="right" width="20" valign="middle">
						<a href="'.$url.'&amp;pg='.$num_pag.'"><img src="./images/right.gif" border="0"/></a>
					</td>
				';
			}
			else{
				$parte.='<td align="right" width="20"><img src="./images/right_gray.gif" border="0"/></td>';
			}
			$parte.='<td align="center" width="120">P&aacute;gina '.$pg.'</td>';
		}
		
		$parte.='</tr></table>';
		
		return $parte;
	}
	
	/**
	 * 
	 */
}
?>