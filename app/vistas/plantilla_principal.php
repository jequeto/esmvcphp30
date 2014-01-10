<!DOCTYPE HTML>
<html lang='<?php echo \core\Idioma::get(); ?>'>
	<head>
		<title><?php echo \core\Idioma::text("title", "plantilla_internacional"); ?></title>
		<meta name="Description" content="Explicación de la página" /> 
		<meta name="Keywords" content="palabras en castellano e ingles separadas por comas" /> 
		<meta name="Generator" content="esmvcphp framewrok" /> 
	 	<meta name="Origen" content="esmvcphp framework" /> 
		<meta name="Author" content="Jesús María de Quevedo Tomé" /> 
		<meta name="Locality" content="Madrid, España" /> 
		<meta name="Lang" content="<?php echo \core\Idioma::get(); ?>" /> 
		<meta name="Viewport" content="maximum-scale=10.0" /> 
		<meta name="revisit-after" content="1 days" /> 
		<meta name="robots" content="INDEX,FOLLOW,NOODP" /> 
		<meta http-equiv="Content-Type" content="text/html; charset=utf8" /> 
		<meta http-equiv="Content-Language" content="<?php echo \core\Idioma::get(); ?>"/>
	
		<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<link href="favicon.ico" rel="icon" type="image/x-icon" /> 
		
		<link rel="stylesheet" type="text/css" href="<?php echo URL_ROOT; ?>recursos/css/principal.css" />
		<style type="text/css" >
			/* Definiciones hoja de estilos interna */
		</style>
		<?php if (isset($_GET["administrator"])): ?>
		<link rel="stylesheet" type="text/css" href="<?php echo URL_ROOT; ?>recursos/css/administrator.css" />
		<?php endif; ?>
		
		<script type='text/javascript' src="<?php echo URL_ROOT."recursos".DS."jquery".DS."jquery-1.10.2.min.js"; ?>" ></script>
		<script type="text/javascript" src=""></script>
		
		<script type="text/javascript" >
			/* líneas del script */
			function saludo() {
				alert("Bienvenido al primer ejercicio de Desarrollo Web en Entorno Servidor");
			}
		</script>
		
	</head>

	<body>
	
		<!-- Contenido que se visualizar� en el navegador, organizado con la ayuda de etiquetas html -->
		<div id="inicio"></div>
		<div id="encabezado">
			<img src="<?php echo URL_ROOT; ?>recursos/imagenes/ipv_ies_palomeras.png" alt="logo" title="Logo" onclick="window.location.assign('http://www.iespalomeras.net/');"/>
			<img src="<?php echo URL_ROOT; ?>recursos/imagenes/departamento_informatica.png" alt="logo" title="Logo departamento"  onclick="window.location.assign('http://www.iespalomeras.net/index.php?option=com_wrapper&view=wrapper&Itemid=86');" />
			<h1 id="titulo">
			<?php if (isset($_GET["administrator"])): ?>
				Administrator:
			<?php endif; ?>
			Aplicación con patrón MVC</h1>
		</div>
		
		<div id="div_derecha_logo">
			Usuario: 
			<?php 
			echo "<b>".\core\Usuario::$login."</b>";
			if (\core\Usuario::$login != 'anonimo') {
				echo " <a href='".\core\URL::generar("usuarios/desconectar")."'>Desconectar</a>";
			}
			else {
				if ((\core\Usuario::$login == "anonimo") && ! (\core\Distribuidor::get_controlador_instanciado() == "usuarios" && \core\Distribuidor::get_metodo_invocado() == "form_login")) {
				echo " <a href='".\core\URL::generar("usuarios/form_login")."'>Conectar</a>";
				}
			}
			echo "<br />Fecha local: <span id='fecha'></span>";
			echo "<br />Tiempo desde conexión: <span id='tiempo_desde_conexion'>".gmdate('H:i:s',  \core\Usuario::$sesion_segundos_duracion)."</span>";
			echo "<br />Tiempo inactivo: <span id='tiempo_inactivo'></span>";	
			?>
		</div>
		
		<div id="div_menu" >
			<fieldset>
				<legend>Menú - Índice - Barra de navegación:</legend>
					<ul id="menu" class="menu">
						<li class="item"><a href="<?php echo \core\URL::generar(); ?>" title="Inicio">Inicio</a></li>
						<li class="item"><a href="<?php echo \core\URL::generar("revista"); ?>" title="Revista">Revista</a></li>
						<li class="item"><a href="<?php echo \core\URL::generar("libros"); ?>" title="Libros leídos">Libros</a></li>
						<li class="item"><a href="<?php echo \core\URL::generar("inicio/internacional"); ?>" title="Internacional">Internacional</a></li>
						<li class="item"><a href="<?php echo \core\URL::generar("usuarios"); ?>" title="Usuarios">Usuarios</a></li>
						<li class="item"><a href="<?php echo \core\URL::generar("categorias"); ?>" title="Categorías">Categorías</a></li>
						<li class="item"><a href="<?php echo \core\URL::generar("articulos"); ?>" title="Artículos">Artículos</a></li>
					</ul>
			</fieldset>
		</div>

		<div id="view_content">
			
			<?php
				echo $datos['view_content'];
			?>
			
		</div>

	
		<div id="pie">
			
			Pie del documento.<br />
			Documento creado por Jesús María de Quevedo Tomé. <a href="mailto:jequeto@gmail.com">Contactar</a><br />
			Fecha última actualización: 15 de diciembre de 2013.
		</div>
		
		<div id='globals'>
			<?php
				print "<pre>"; print_r($GLOBALS);print "</pre>";
			?>
		</div>
		
		<?php
			if (isset($_SESSION["alerta"])) {
				echo "
					<script type='text/javascript'>
						alert('{$_SESSION["alerta"]}');
					</script>
				";
				unset($_SESSION["alerta"]);
			}
			elseif (isset($datos["alerta"])) {
				echo "
					<script type='text/javascript'>
						alert('{$datos["alerta"]}');
					</script>
				";
				unset($datos["alerta"]);
			}
		?>
		
	</body>

</html>