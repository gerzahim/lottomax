<?php 

define('DEBUG_FCD_CEAP',false);

/*
 * Para imprimir querys y/o arrays por pantalla CEAP 
 */
function printDatos($que, $nombre="")
{
	if(!DEBUG_FCD_CEAP) return;
	if(is_array($que))
	{
		echo "<pre style='background-color:#FFFFCF; border:#FF0000 thin dashed; text-align:left; margin: 25px; padding: 10px;'>";
		echo "<p><strong>$nombre</strong></p>";
		print_r($que);
		echo "</pre>";
	}
	else
	{
		echo "<pre style='background-color:#FFFFCF; border:#FF0000 thin dashed; text-align:left; margin: 25px; padding: 10px;'><p><strong>$nombre</strong></p>$que</pre>";	
	}
}

/***************************************************
FUNCIONES DE CONSULTA A BASE DE DATOS 
****************************************************/

// Lista Las Categorias Que Aparecen En El Home
function listar_categorias_home($db_mysql,$tipo)
{
	if($tipo==1)
	{
		$sql = "SELECT id_categoria,nombre_categoria FROM categoria WHERE status_categoria!='0' ORDER BY id_categoria ASC";
		$result = $db_mysql->consulta($sql);
		return $result;
	}
	elseif($tipo==2)
	{
		$sql = "SELECT count(*) FROM categoria WHERE status_categoria!='0'";
		$db_mysql-> consulta($sql);
		$row=$db_mysql->arraycampos();
		return $row['count(*)'];
	}
}

//Lista de las Novedades Que Aparecen En El Home
function listar_novedades_home($db_mysql)
{
	$sql = "SELECT * FROM novedades order by fecha_publicacion_novedad DESC LIMIT 0,1";
	$db_mysql-> consulta($sql);
	$row=$db_mysql->arraycampos();
	return $row;
}

// Lista Los Artículos Que Aparecen En El Home
function listar_articulos_home($db_mysql)
{
	$sql = "SELECT A.id_categoria, A.id_articulo,A.nombre_articulo,A.foto_articulo,A.codigo_articulo,A.descripcion_articulo,A.nuevo_articulo,C.nombre_categoria FROM articulo A INNER JOIN categoria C ON A.id_categoria=C.id_categoria AND A.status_articulo='Activo' AND A.nuevo_articulo='1' ORDER BY A.id_articulo DESC LIMIT 0,6";
	$result = $db_mysql->consulta($sql);
	return $result;
}

// Lista El Artículo Que Aparecen Destacado En El Home
function listar_destacado_home($db_mysql)
{
	$sql = "SELECT A.id_categoria, A.id_articulo,A.nombre_articulo,A.foto_articulo,A.codigo_articulo,A.descripcion_articulo,C.nombre_categoria FROM articulo A INNER JOIN categoria C ON A.id_categoria=C.id_categoria AND A.home_articulo='1' ORDER BY RAND() DESC LIMIT 0,1";
	$db_mysql-> consulta($sql);
	$row=$db_mysql->arraycampos();
	return $row;
}

//Selecciona La Novedad Según id_novedad
function seleccionar_novedad_nuevo($db_mysql,$id_novedad)
{
	$sql = "SELECT * FROM novedades WHERE id_novedad='".$id_novedad."'";
	$db_mysql-> consulta($sql);
	$row=$db_mysql->arraycampos();
	return $row;
}

//Selecciona La Novedad Según id_novedad con md5
function seleccionar_novedad($db_mysql,$id_novedad)
{
	$sql = "SELECT * FROM novedades WHERE md5(id_novedad)='".$id_novedad."'";
	$db_mysql-> consulta($sql);
	$row=$db_mysql->arraycampos();
	return $row;
}

//Selecciona Las Novedades Según id_novedad
function seleccionar_novedades($db_mysql)
{
	$sql = "SELECT * FROM novedades order by fecha_publicacion_novedad DESC";
	$result = $db_mysql->consulta($sql);
	return $result;
}

//Seleccionar Proveedor
function seleccionar_proveedor($db_mysql,$id_proveedor)
{
	$sql = "SELECT * FROM marcas WHERE id_proveedor='".$id_proveedor."'";
	$result = $db_mysql->consulta($sql);
	return $result;
}

// Selecciona El Artículo Según Su id_articulo encriptado
function seleccionar_articulo($db_mysql,$id_articulo)
{
	$sql = "SELECT * FROM articulo A INNER JOIN categoria C ON A.id_categoria=C.id_categoria AND md5(A.id_articulo)='".$id_articulo."'";
	printDatos($sql);
	printdatos($_SESSION["ocarrito"]->num_productos);
	$db_mysql-> consulta($sql);
	$row=$db_mysql->arraycampos();
	printDatos($row);
	return $row;
}

// Logea Al Cliente
function logear($db_mysql,$login_cliente,$password_cliente)
{
	$sql="SELECT * FROM cliente,parametros WHERE cliente.login_cliente = '".$login_cliente."' and cliente.password_cliente = '".md5($password_cliente)."' and cliente.status_cliente  = 'Activo' and parametros.id  = '1'";
	$db_mysql-> consulta($sql);
	$row=$db_mysql->arraycampos();
	return $row;
}

// Inserta Un Detalle De Cotizacion
function insertar_detalle($db_mysql,$id_cotizacion,$id_articulo,$cantidad_detalle,$precio_unitario_detalle,$iva_detalle,$total_iva_detalle,$total_detalle)
{
	$sql = "INSERT INTO `detalle` (`id_cotizacion`, `id_articulo` , `cantidad_detalle` , `precio_unitario_detalle` , `iva_detalle` , `total_iva_detalle` , `total_detalle` , `status_detalle` ) VALUES ('".$id_cotizacion."', '".$id_articulo."', '".$cantidad_detalle."', '".$precio_unitario_detalle."', '".$iva_detalle."', '".$total_iva_detalle."', '".$total_detalle."', 'Por Confirmar')";
	printDatos($sql);
	$db_mysql-> consulta($sql);
	$id=mysql_insert_id();
	return $id;
}

// Inserta Una Cotizacion
function insertar_cotizacion($db_mysql,$id_cliente,$costo_cotizacion,$iva_cotizacion,$total_iva_cotizacion,$total_cotizacion)
{
	$fecha_cotizacion=date('Y-m-d');
	$sql = "INSERT INTO `cotizacion` (`id_cliente` , `fecha_cotizacion` , `costo_cotizacion` , `iva_cotizacion` , `total_iva_cotizacion` , `total_cotizacion` , `status_cotizacion` ) VALUES ('".$id_cliente."', '".$fecha_cotizacion."', '".$costo_cotizacion."', '".$iva_cotizacion."', '".$total_iva_cotizacion."', '".$total_cotizacion."', 'Por Confirmar')";
	$db_mysql-> consulta($sql);
	$id=mysql_insert_id();
	return $id;
}

// Actualiza Una Cotizacion
function actualizar_cotizacion($db_mysql,$id_cotizacion,$costo_cotizacion,$total_iva_cotizacion,$total_cotizacion)
{
	$sql = "UPDATE `cotizacion` SET `costo_cotizacion`='".$costo_cotizacion."', `total_iva_cotizacion`='".$total_iva_cotizacion."' , `total_cotizacion` ='".$total_cotizacion."' WHERE id_cotizacion='".$id_cotizacion."'";
	$db_mysql-> consulta($sql);
	return true;
}

// Lista Los Artículos Según Su Categoría
function listar_articulos_categoria($db_mysql,$id_categoria,$inicio,$tamano,$tipo)
{
	if ($tipo==2)
	{
		$limite= "LIMIT " . $inicio . "," . $tamano;
	}
	$sql = "SELECT id_articulo,nombre_articulo,foto_articulo,codigo_articulo,descripcion_articulo FROM articulo WHERE status_articulo='Activo' and md5(id_categoria) = '".$id_categoria."' ORDER BY id_articulo ASC ".$limite."";
	$result = $db_mysql->consulta($sql);
	if($tipo==1)
	{
		$num_total_registros = $db_mysql-> numregistros($sql);
		$total_paginas = ceil($num_total_registros / $tamano);
		$resultado = array($num_total_registros,$total_paginas);
		return $resultado;
	}
	else
	{
		return $result;
		
	}
}

// Lista Las Novedades
function listar_novedades($db_mysql,$inicio,$tamano,$tipo)
{
	if ($tipo==2)
	{
		$limite= "LIMIT " . $inicio . "," . $tamano;
	}
	$sql = "SELECT * FROM novedades ORDER BY fecha_publicacion_novedad DESC ".$limite."";
	$result = $db_mysql->consulta($sql);

	if($tipo==1)
	{
		$num_total_registros = $db_mysql-> numregistros($sql);
		$total_paginas = ceil($num_total_registros / $tamano);
		$resultado = array($num_total_registros,$total_paginas);
		return $resultado;
	}
	else
	{
		return $result;
	}
}

// Selecciona La Categoria Según Su id
function seleccionar_categoria($db_mysql,$id_categoria)
{
	$sql="SELECT * FROM categoria WHERE md5(id_categoria) = '".$id_categoria."'";
	$db_mysql-> consulta($sql);
	$row=$db_mysql->arraycampos();
	return $row;
}

// Inserta Un Cliente
function insertar_cliente($db_mysql,$nombre_cliente,$encargado_cliente,$rif_cliente,$nit_cliente,$direccion_cliente,$entrega_cliente,$pais_cliente,$estado_cliente,$ciudad_cliente,$telf_cliente,$fax_cliente,$celular_cliente,$email_cliente,$tipo_emp_cliente,$login_cliente,$password_cliente,$comen_cliente)
{
	$sql = "INSERT INTO `cliente` (`nombre_cliente` , `encargado_cliente` , `rif_cliente` , `nit_cliente` , `direccion_cliente` , `entrega_cliente` , `pais_cliente` , `estado_cliente` , `ciudad_cliente` , `telf_cliente` , `fax_cliente` , `celular_cliente` , `email_cliente` , `tipo_emp_cliente` , `login_cliente` , `password_cliente`, `pass_cliente`,`status_cliente`,`fecha_reg_cliente`,`comen_cliente` ) VALUES ('".$nombre_cliente."' , '".$encargado_cliente."' , '".$rif_cliente."' , '".$nit_cliente."' , '".$direccion_cliente."' , '".$entrega_cliente."' , '".$pais_cliente."' , '".$estado_cliente."' , '".$ciudad_cliente."' , '".$telf_cliente."' , '".$fax_cliente."' , '".$celular_cliente."' , '".$email_cliente."' , '".$tipo_emp_cliente."' , '".$login_cliente."' , '".md5($password_cliente)."', '".$password_cliente."', 'Por Confirmar', '".date("Y-m-d")."', '".$comen_cliente."')";
	$db_mysql-> consulta($sql);
	$id=mysql_insert_id();
	return $id;
}

// Selecciona un cliente según su login
function seleccionar_cliente_login($db_mysql,$login_cliente)
{
	$sql="SELECT login_cliente FROM cliente WHERE login_cliente = '".$login_cliente."'";
	$db_mysql-> consulta($sql);
	$row=$db_mysql->arraycampos();
	return $row;
}

// Selecciona un cliente según su email
function seleccionar_cliente_email($db_mysql,$email_cliente)
{
	$sql="SELECT * FROM cliente WHERE email_cliente = '$email_cliente' and status_cliente = 'Activo'";
	$result = $db_mysql->consulta($sql);
	$row = $db_mysql->arraycampos($result);
	return $row;
}

// Selecciona un cliente según su id
function seleccionar_cliente_id($db_mysql,$id_cliente)
{
	$sql="SELECT * FROM cliente WHERE id_cliente = '".$id_cliente."'";
	$db_mysql->consulta($sql);
	$row=$db_mysql->arraycampos();
	return $row;
}

// Selecciona un cliente según su id
function seleccionar_cliente_id_cotizacion($db_mysql,$id_cotizacion)
{
	$sql="SELECT * FROM cotizacion C2 INNER JOIN cliente C ON C2.id_cotizacion='".$id_cotizacion."' AND C2.id_cliente=C.id_cliente";
	$db_mysql->consulta($sql);
	$row=$db_mysql->arraycampos();
	return $row;
}

// crea un nuevo password de cliente según su email
function cambiar_password($db_mysql,$email_cliente)
{
	$row=seleccionar_cliente_email($db_mysql,$email_cliente);
	$numero = rand(1,10);
	$letras = substr($row['nombre_cliente'],0,2);
	$password_cliente=$row['login_cliente'].$numero.$letras;
	$sql = "UPDATE `cliente` SET `password_cliente` = '".md5($password_cliente)."' WHERE id_cliente = '".$row['id_cliente']."'";
	$db_mysql->consulta($sql);
	$sql = "UPDATE `cliente` SET `pass_cliente` = '".$password_cliente."' WHERE id_cliente = '".$row['id_cliente']."'";
	$db_mysql->consulta($sql);
	return $password_cliente;
}

// crea un nuevo password de cliente por el panel
function cambiar_password_panel($db_mysql,$email_cliente,$pasword_cliente,$nuevo_password_cliente)
{
	$row=seleccionar_cliente_email($db_mysql,$email_cliente);
	if($row['password_cliente']==md5($pasword_cliente))
	{
		$sql = "UPDATE `cliente` SET `password_cliente` = '".md5($nuevo_password_cliente)."' WHERE id_cliente = '".$row['id_cliente']."'";
		$db_mysql-> consulta($sql);
		$sql = "UPDATE `cliente` SET `pass_cliente` = '".$nuevo_password_cliente."' WHERE id_cliente = '".$row['id_cliente']."'";
		$db_mysql-> consulta($sql);
		return true;	
	}
	else
	{
		return false;
	}
}

// Lista Los Artículos Según Una Busqueda
function listar_articulos_busqueda($db_mysql,$palabra,$inicio,$tamano,$tipo)
{
	if ($tipo==2)
	{
		$limite= "LIMIT " . $inicio . "," . $tamano;
	}
	$sql = "SELECT id_articulo,nombre_articulo,foto_articulo,codigo_articulo,descripcion_articulo FROM articulo WHERE status_articulo='Activo' and (codigo_articulo like '%".$palabra."%' or descripcion_articulo like '%".$palabra."%') ORDER BY id_articulo ASC ".$limite."";
	$result = $db_mysql->consulta($sql);
	if($tipo==1)
	{
		$num_total_registros = $db_mysql-> numregistros($sql);
		$total_paginas = ceil($num_total_registros / $tamano);
		$resultado = array($num_total_registros,$total_paginas);
		return $resultado;
	}
	else
	{
		return $result;
	}
}

// Selecciona los parametros del sistema
function parametros($db_mysql)
{
	$sql="SELECT * FROM parametros WHERE id  = '1'";
	$db_mysql-> consulta($sql);
	$row=$db_mysql->arraycampos();
	return $row;
}

// Activa Un Cliente
function actualizar_cliente($db_mysql,$login_cliente)
{
	$sql = "UPDATE `cliente` SET `status_cliente`='Activo' WHERE md5(login_cliente)='".$login_cliente."'";
	$db_mysql-> consulta($sql);
	return true;
}

// Selecciona un cliente recien activado
function cliente_activado($db_mysql,$login_cliente)
{
	$sql="SELECT * FROM `cliente` WHERE md5(login_cliente)='".$login_cliente."'";
	$db_mysql-> consulta($sql);
	$row=$db_mysql->arraycampos();
	return $row;
}

// Valida el campo login
function validar_login($db_mysql,$login_cliente)
{ 
   	if (ereg("^[a-zA-Z0-9\-_]{3,20}$", $login_cliente)) 
   	{ 
      	return true; 
   	} 
   	else 
	{ 
		return false; 
   	} 
} 

// Selecciona la cantidad de articulos de una cotizacion
function cant_articulos($id_cotizacion)
{
	$sql="SELECT SUM(cantidad_detalle) AS cantidad FROM `detalle` WHERE id_cotizacion='".$id_cotizacion."'";
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	return $row['cantidad'];
}

// lista las cotizaciones pendientes(1) o confirmadas (2) de un cliente
function listar_cotizaciones($db_mysql,$id_cliente,$tipo)
{
	if($tipo==1)
	{
		$sql = "SELECT * FROM cotizacion WHERE id_cliente='".$id_cliente."' AND status_cotizacion = 'Por Confirmar' ORDER BY fecha_cotizacion DESC LIMIT 0,10";
	}
	elseif($tipo==2)
	{
		$sql = "SELECT * FROM cotizacion WHERE id_cliente='".$id_cliente."' AND status_cotizacion = 'Confirmada' ORDER BY fecha_cotizacion DESC LIMIT 0,10";
	}
	$result = $db_mysql->consulta($sql);
	return $result;
}

// detalles de la cotizacion
function detalle_cotizacion($db_mysql,$id_cotizacion)
{
	$sql="SELECT * FROM detalle D INNER JOIN articulo A ON D.id_cotizacion='".$id_cotizacion."' AND D.id_articulo=A.id_articulo";
	$result = $db_mysql->consulta($sql);
	return $result;
}

function obtenNovedadActual($db_mysql)
	{
	$sql = "SELECT * FROM novedades WHERE fecha_publicacion_novedad <= now() 
	ORDER BY fecha_publicacion_novedad DESC LIMIT 1";	
	//echo $sql;
	$result = $db_mysql->consulta($sql);
	return $result;
	}
?>