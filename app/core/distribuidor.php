<?php
namespace core;

/**
 * @author Jesús María de Quevedo Tomé <jequeto@gmail.com>
 * @since 20130130
 */
class Distribuidor {

	private static $controlador_instanciado = null;
	private static $metodo_invocado = null;
	
	
	/**
	 * Realiza el estudio del requerimiento http recibido y elige el 
	 * controlador y método que se ejecutará para atenderla.
	 */
	public static function estudiar_query_string() {
		
		self::interpretar_url_amigable();
		
		
		$controlador = isset($_GET['menu']) ? \core\HTTP_Requerimiento::get('menu') : \core\HTTP_Requerimiento::get('p1');
		$metodo = isset($_GET['submenu']) ?\core\HTTP_Requerimiento::get('submenu'): \core\HTTP_Requerimiento::get('p2');
		
		if ( $controlador  == null || (boolean)\core\Validaciones::errores_identificador($controlador) )
			$controlador = strtolower(\core\Configuracion::$controlador_por_defecto);
		if ( ! $metodo || (boolean)\core\Validaciones::errores_identificador($metodo) )
			$metodo = strtolower(\core\Configuracion::$metodo_por_defecto);
		
		self::cargar_controlador($controlador, $metodo);
		
	}
	
	
	
	/**
	 * Si la propiedad \core\Configuracion::$url_amibable es true, y además
	 * exites fichero .htacces en la carpeta root de la aplicación, y en él 
	 * RewriteEngine = on
	 * 
	 * Este método transfoma la URI /dato1/dato2/dato3/....
	 * En entradas nuevas en $_GET y $_REQUEST, llamadas [p1],[p2],[p3], ... a las
	 * que se asigna los valores dato1, dato2, dato3, ... respectivamente
	 * Si no se hubiesen recibido como parámetros de URI ?menu=...&submenu=...&id=....
	 * también creará las entradas [menu], [submenu] e [id] asignándolas los 
	 * valores datos1, dato2 y dato3 respectivamente.
	 *
	 * @author jequeto@gmail.com
	 * @since 125/11/2013
	 */
	private static function interpretar_url_amigable() {
		
		if ( \core\Configuracion::$url_amigable ) {
			// Sea la URL http://www.servidor.com/dato1/dato2/dato3/[...]
			// o sea la URL http://www.servidor.com/aplicacion/dato1/dato2/dato3/[...]
			// $_SERVER["SCRIPT_NAME"] almacenará la cadena /index.php o /aplicacion/index.php respectivamente.
			// $aplicacion almacena si existe "/" o "/aplicacion/" respectivamente.
			$aplicacion = str_replace("index.php", "", $_SERVER["SCRIPT_NAME"]);

			// $_SERVER["REQUEST_URI"] almacenará la cadena /dato1/dato2/dato3/[...]
			//  o /aplicacio/dato1/dato2/dato3/[...] respectivamente
			$query_string = str_replace($aplicacion, "", $_SERVER["REQUEST_URI"]); 
			// $query_string será ahora una cadena de la forma "dato1/dato2/dato3/"
			
			$valores = array(); // Recogerá los parámetros pasados en forma amigable
			// Buscamos cada uno de los parámetros dato1/  dato2/  ...
			// $valores = explode("/", $query_string);
			preg_match_all("/\w+\//i", $query_string, $valores);
			foreach ($valores[0] as $key => $value) {
				// Si el parámetro se ha recibido no lo añado
				// Si lo añado, quito la / del final.
				if ( ! isset($_GET["p".($key+1)]) ) $_GET["p".($key+1)] = str_replace("/", "",$value);
			}
			
		}
		// Transformación los parámentros p1, p2, p3, p4, ...
		// a otros nombres más significativos menu, submenu, id, ...
		if ( ! isset($_REQUEST['menu']) and isset($_GET['p1'])) {
				$_GET['menu'] = $_GET['p1'];
				$_REQUEST['menu'] = $_GET['p1'];
		}
		if ( ! isset($_REQUEST['submenu']) and isset($_GET['p2'])) {
				$_GET['submenu'] = $_GET['p2'];
				$_REQUEST['submenu'] = $_GET['p2'];
		}
		if ( ! isset($_REQUEST['id']) and isset($_GET['p3'])) {
				$_POST['id'] = $_GET['p3'];
				$_REQUEST['id'] = $_GET['p3'];
		}
		
		//echo "<pre>"; print_r($valores); print_r($GLOBALS);exit(0);
		
	}
	
	
	/**
	 * Carga la clase controladora indicada en los parámetros y ejecuta el método de esa clase pasado en los parámetros. Al método se le pasa el array
	 * de datos pasado como parámetro.
	 * 
	 * @param string $controlador Clase controladora a instanciar
	 * @param string $metodo Método a ejecutar
	 * @param array $datos Datos para el método
	 */
	public static function cargar_controlador($controlador, $metodo, array $datos = array()) {
		
		$fichero_controlador = strtolower(PATH_APP."controladores/$controlador.php");
		$controlador_clase = strtolower("\\controladores\\$controlador");
		
		if (file_exists($fichero_controlador)) {			
			\core\Aplicacion::$controlador = new $controlador_clase();
			\core\Aplicacion::$controlador->datos['controlador_clase'] = strtolower($controlador);
			self::$controlador_instanciado = strtolower($controlador);
			if (method_exists(\core\Aplicacion::$controlador, $metodo)) {
				\core\Aplicacion::$controlador->datos['controlador_metodo'] = strtolower($metodo);
				self::$metodo_invocado = strtolower($metodo);
				\core\Aplicacion::$controlador->$metodo($datos);
				
			}
			else {
				$datos['mensaje'] = "El método <b>$metodo</b> no está definido en la clase <b>$controlador_clase</b> (.php).";
				self::cargar_controlador("errores", "error_404", $datos);
			}
		}
		else {
			$datos['mensaje'] = "La clase <b>$controlador_clase</b> no existe.";
			self::cargar_controlador("errores", "error_404", $datos);
		}
	}
	
	
	
	public static function get_controlador_instanciado() {
		
		return self::$controlador_instanciado;
		
	}
	
	
	public static function get_metodo_invocado() {
		
		return self::$metodo_invocado;
		
	}
	
	
} // Fin de la clase

