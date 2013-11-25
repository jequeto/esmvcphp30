<!DOCTYPE HTML>
<html>
	<head>
		<title><?php echo TITULO; ?></title>
		<meta name="Description" content="Explicaci�n de la p�gina" /> 
		<meta name="Keywords" content="palabras en castellano e ingles separadas por comas" /> 
		<meta name="Generator" content="con qu� se ha hecho" /> 
	 	<meta name="Origen" content="Qu�en lo ha hecho" /> 
		<meta name="Author" content="nombre del autor" /> 
		<meta name="Locality" content="Madrid, Espa�a" /> 
		<meta name="Lang" content="es" /> 
		<meta name="Viewport" content="maximum-scale=10.0" /> 
		<meta name="revisit-after" content="1 days" /> 
		<meta name="robots" content="INDEX,FOLLOW,NOODP" /> 
		<meta http-equiv="Content-Type" content="text/html;charset=utf8" /> 
		
		<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<link href="favicon.ico" rel="icon" type="image/x-icon" /> 
		
		<link rel="stylesheet" type="text/css" href="<?php echo URL_ROOT; ?>recursos/css/inicio/principal.css" />
		<style type="text/css" >
			/* Definiciones hoja de estilos interna */
		</style>

		<script type="text/javascript" src=""></script>
		
		<script type="text/javascript" >
			/* l�neas del script */
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
			<h1 id="titulo">Aplicación con patrón MVC</h1>
		</div>
		
		<div id="div_menu" >
			<fieldset>
				<legend>Menú - Índice - Barra de navegación:</legend>
					<ul id="menu" class="menu">
						<li class="item"><a href="<?php echo \core\URL::generar("revista"); ?>" title="Revista">Revista</a></li>
						<li class="item"><a href="<?php echo \core\URL::generar("libros"); ?>" title="Libros leídos">Libros</a></li>
					</ul>
			</fieldset>
		</div>

		<div id="view_content">
			<p>Bienvenido a la aplicación desarrollada en PHP-poo con un patrón MVC</p>
			<a href="docs/Modelo_Vista_Controlador_v04.pdf" target="_blank"><img src='<?php echo URL_ROOT; ?>recursos/imagenes/Arquitectura_MVC.png' alt='Arquitectura_MVC.png' title="Representación del patrón MVC, por el profesor Jesús María de Quevedo Tomé"  height="400px" /></a>
			<a href="http://dreamztech.com/blog/new-features-in-asp-net-mvc-4/" target="_blank"  title="Imagen de patrón MVC de http://dreamztech.com/blog/new-features-in-asp-net-mvc-4/"><img src="<?php echo URL_ROOT; ?>recursos/imagenes/MVC_imagen2.png" alt="MVC_imagen2.png"  height="400px" /></a>
		</div>

	
		<div id="pie">
			<hr />
			Pie del documento.<br />
			Documento creado por Jesús María de Quevedo Tomé. <a href="mailto:jequeto@gmail.com">Contactar</a><br />
			Fecha última actualización: 15 de octubre de 2013.
		</div>
		
	</body>

</html>
