
<?php
//print "<pre>"; print_r($GLOBALS); print "</pre>";
// Definiciones constantes
define("DS", DIRECTORY_SEPARATOR);

define("PATH_ROOT", __DIR__.DS ); // Finaliza en DS

define("PATH_APP", __DIR__.DS."app".DS ); // Finaliza en DS

define("URL_ROOT", (isset($_SERVER['REQUEST_SCHEME'])?$_SERVER['REQUEST_SCHEME']:($_SERVER['SERVER_PORT']==80?"http":"https"))."://".$_SERVER['SERVER_NAME'].str_replace("?".$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI'])); // Finaliza en DS

define('TITULO', 'Aplicación MVC');


// Preparar el autocargador de clases.
// Este y el contenido en \core\Autoloader() serán los únicos require/include de toda la aplicación

require PATH_APP.'core/autoloader.php'; 
$autoloader = new \core\Autoloader();
//spl_autoload_register(array('\core\Autoloader', 'autoload'));

//require_once PATH_APP."core/aplicacion.php";
// Cargamos la aplicación
$aplicacion = new \core\Aplicacion();

// Fin de index.php