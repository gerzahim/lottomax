<?php 
class DB_mysql {
	// VARIABLES DE CONEXION
	var $BaseDatos;
	var $Servidor;
	var $Usuario;
	var $Clave;

	// IDENTIFICADOR DE CONEXION Y CONSULTA
	var $Conexion_ID = 0;
	var $Consulta_ID = 0;
	
	//NUMERO DE ERROR Y TEXTO DE ERROR MYSQL 
	var $Errno = 0;
	var $Error = "";
		
	//VARIABLES PARA LA PAGINACION
	var $total_paginas=0;	
	var $pagina =0;
	var $url = "";

	
	// METODO CONSTRUCTOR
	
	function DB_mysql($bd = "siadcova", $host = "localhost", $user = "root", $pass = "") {
		$this->BaseDatos = $bd;
		$this->Servidor = $host;
		$this->Usuario = $user;
		$this->Clave = $pass;
	}
	
	//CONEXION A LA BASE DE DATOS
	function conectar($bd, $host, $user, $pass){
		if ($bd != "") $this->BaseDatos = $bd;
		if ($host != "") $this->Servidor = $host;
		if ($user != "") $this->Usuario = $user;
		if ($pass != "") $this->Clave = $pass;
	
	//CONECTAR AL SERVIDOR
		$this->Conexion_ID = mysql_connect($this->Servidor, $this->Usuario, $this->Clave);
		if (!$this->Conexion_ID) {
			$this->Error = "Ha fallado la conexión.";
			return 0;
		}
	//SELECCIONAMOS LA BASE DE DATOS
		if (!@mysql_select_db($this->BaseDatos, $this->Conexion_ID)) {
			$this->Error = "Imposible abrir ".$this->BaseDatos ;
			return 0;
		}	
	
	// SI HEMOS TENIDO EXITO CONECTANDO DEVUELVE EL IDENTIFICADOR DE LA CONEXION, SINO DEVUELVE 0
	
		return $this->Conexion_ID;
	}
	
	 
	
	//EJECUTAR CONSULTA
	
	function consulta($sql = ""){
		if ($sql == "") {
			$this->Error = "No ha especificado una consulta SQL";
		return 0;
		}
	
	//EJECUTA UNA CONSULTA
		$this->Consulta_ID = @mysql_query($sql, $this->Conexion_ID);
		
		if (!$this->Consulta_ID) {
			$this->Errno = mysql_errno();
			$this->Error = mysql_error();
		}
	
	// SI HEMOS TENIDO EXITO CONECTANDO DEVUELVE EL IDENTIFICADOR DE LA CONSULTA, SINO DEVUELVE 0
		return $this->Consulta_ID;
	
	}
	
	//DEVUELVE LOS CAMPOS EN UN ARREGLO ASOCIATIVO
	
	function arraycampos($resul="") {
		if (!empty($resul)){
			return mysql_fetch_array($resul);
		}else{
			return mysql_fetch_array($this->Consulta_ID);
		}
		
	}
	
	//NUMEROS DE CAMPOS DE UNA CONEXION
	
	function numcampos() {
		return mysql_num_fields($this->Consulta_ID);
	}
	
	 
	
	//NUMERO DE CAMPOS DE UNA CONSULTA
	
	function numregistros(){
	
		return mysql_num_rows($this->Consulta_ID);
	
	}
	
	//DEVUELVE EL NOMBRE DE UN CAMPO DE UNA CONSULTA
	
	function nombrecampo($numcampo) {
		return mysql_field_name($this->Consulta_ID, $numcampo);
	}
	
	 
	function paginacion($total_paginas,$pagina,$url){
	$contenido = "<table cellpadding='0' cellspacing='10' border='0'>
			<tr>";
		if ($total_paginas > 1){ 
			if ($pagina<>1){
	$contenido.="<td>
					 <a href=".$url."&amp;pagina=".($pagina - 1)."><img src='./imagenes/map01.gif' alt='pagina ".($pagina - 1)."' border='0' /></a>&nbsp;									
				</td>";
		}else{	
	$contenido.="<td>
					<table width='100%' cellpadding='0' cellspacing='0' border='0'>
						<tr>
							<td >
								<img src='./imagenes/map03.gif' border='0' alt='anterior' />&nbsp;																
							</td>
						</tr>
					</table>													
				</td>";
			}			
			
			$diferencia=$total_paginas-$pagina;
	
			if($diferencia>=6 and $total_paginas>=10){//PARA MOSTRAR SEIS MAS DE LA PAGINA ACTUAL
				
				$num_pagina=6;
			}else{
				
				$num_pagina=$diferencia;
			}
			$dife_pag = $pagina-3;//PARA MOSTAR LAS TRES ANTERIORES
			
			if($dife_pag<=0){
			
				$inicio_pag=1;
				
				if ($total_paginas>=10){
				
					 if ($dife_pag==-2){// PARA QUE NO SE IMPRIMAN LOS NUMEROS NEGACTIVOS CUANDO EN LA PRIMERA PAGINA
					$num_pagina = $num_pagina+3;
					
					}elseif($dife_pag==-1){
						$num_pagina = $num_pagina+2;
					}elseif($dife_pag==0){
						$num_pagina = $num_pagina+1;
					} 
				}
			
			}else{
			
				if ($pagina==$total_paginas){//CUANDO ESTA EN LA ULTIMA PAGINA
						
					if ($total_paginas>=10){
						$inicio_pag = $total_paginas-9;
					}else{
						$inicio_pag=1;
					}
					
				}else{
					if ($total_paginas>=10){
						$inicio_pag =$dife_pag;
						$total_conteo = $pagina+$num_pagina; //INDICA HASTA QUE NUMERO DE PAGINA IMPRIME
						$conteo_estimado=$inicio_pag+9;//INDICA EL CONTEO HASTA DONDE DEBE LLEGAR 
						$dif_conteo = $total_conteo-$conteo_estimado; //CALCULA CUANTAS PAGINAS FALTAN PARA EL DESPLIEGUE
						if ($total_conteo<>$conteo_estimado){
							$inicio_pag=$inicio_pag-abs($dif_conteo) ;
						}
						
					}else{
						$inicio_pag = 1;
					}
				}
				
			}
	$contenido.="<td>";
			for ($i=$inicio_pag;$i<=$pagina+$num_pagina;$i++){  
				if ($pagina == $i) 
					//si muestro el índice de la página actual, no coloco enlace 
	$contenido.="<strong style='color:#1B4F8F;'>[".$pagina."]</strong>&nbsp;"; 
			   else 
					//si el índice no corresponde con la página mostrada actualmente, coloco el enlace para ir a esa página 
	$contenido.="<a style='color:#1B4F8F;' href=".$url."&amp;pagina=".$i.">".$i."</a>&nbsp;";
			} 
	$contenido.="</td>";
			if ($pagina<>$total_paginas){
	$contenido.="<td>
					<a href=".$url."&amp;pagina=".($pagina + 1)."><img src='./imagenes/map02.gif' alt='pagina ".($pagina + 1)."'  border='0'  /></a>														
				</td>";
			}else{
	$contenido.="<td>
					<table width='100%' cellpadding='0' cellspacing='0' border='0'>
						<tr>
							<td>
								<img src='imagenes/map04.gif' alt='siguiente' border='0'/>
							</td>
						</tr>
					</table>
				</td>";		
			}
		}
	$contenido.="</tr>
		</table>";
	return $contenido;		
	}
	
	//MUESTRA LOS DATOS DE UNA CONSULTA
	
	function verconsulta() {
	
	 
	
	echo "<table border=1>\n";
	
	 
	
	// mostramos los nombres de los campos
	
	for ($i = 0; $i < $this->numcampos(); $i++){
	
	echo "<td><b>".$this->nombrecampo($i)."</b></td>\n";
	
	}
	
	echo "</tr>\n";
	
	// mostrarmos los registros
	
	 
	
	while ($row = mysql_fetch_row($this->Consulta_ID)) {
	
	echo "<tr> \n";
	
	for ($i = 0; $i < $this->numcampos(); $i++){
	
	echo "<td>".$row[$i]."</td>\n";
	
	}
	
	echo "</tr>\n";
	
	}
	
	 
	
	}

} //fin de la Clse DB_mysql

?>