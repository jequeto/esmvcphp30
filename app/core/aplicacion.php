<?php
namespace core;
//require_once PATH_APP."core/clase_base.php";
//require_once PATH_APP."core/respuesta.php";


/**
 * Aplicación principal
 *
 * @author Jesús María de Quevedo Tomé <jequeto@gmail.com>
 * @since 20130130
 */
class Aplicacion extends \core\Clase_Base {
	
	/**
	 * Almacenará el objeto resultado de instanciar la clase Controlador que se encargará
	 * de atender la petición HTTP recibida.
	 * 
	 * @var \core\Controlador 
	 */
	public static $controlador;

	
	public function __construct() {
		
		//\core\sgbd\bd::connect();
		
		//\core\SESSION::iniciar();
		
		// Reconocer el usuario que ha iniciado la sesión de trabajo o que continúa dentro de una sesión de trabajo.
		//\core\Usuario::iniciar();
				
		// Los permisos los usamos si trabajamos con la ACL (Access Control List) para definir los permisos de los usuarios
		// \core\Permisos::iniciar();
		
		// Interpretar url amigable
		\core\Rutas::interpretar_url_amigable();
		
		// Estudio del idioma ***************************************
		\core\Idioma::init();
		
		// Distribuidor
		\core\Distribuidor::estudiar_query_string();

		//\core\sgbd\bd::disconnect();
		
		//$content="<A>Contenido</A>";
		//\core\HTTP_Respuesta::enviar($content);
		
		print "<pre>"; print_r($GLOBALS);print "</pre>";
		
		
	}
	
} // Fin de la clase