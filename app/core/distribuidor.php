<?php
namespace core;

/**
 * @author Jesús María de Quevedo Tomé <jequeto@gmail.com>
 * @since 20130130
 */
class Distribuidor {
	 
	
	/**
	 * Realiza el estudio del requerimiento http recibido y elige el 
	 * controlador y método que se ejecutará para atenderla.
	 */
	public static function estudiar_query_string() {
		
		$controlador = \core\HTTP_Requerimiento::get('menu');
		$metodo = \core\HTTP_Requerimiento::get('submenu');
		
		if ( $controlador  == null )
			$controlador = strtolower(\core\Configuracion::$controlador_por_defecto);
		if ( ! $metodo )
			$metodo = strtolower(\core\Configuracion::$metodo_por_defecto);
		
		self::cargar_controlador($controlador, $metodo);
		
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
			if (method_exists(\core\Aplicacion::$controlador, $metodo)) {
				\core\Aplicacion::$controlador->datos['controlador_metodo'] = strtolower($metodo);
				\core\Aplicacion::$controlador->$metodo($datos);
				
			}
			else {
				$datos['mensaje'] = "El método <b>$metodo</b> no está definido en la clase <b>$controlador_clase</b> (.php).";
				$contenido = \core\Vista_Plantilla::generar("plantilla_404", $datos);
				\core\Aplicacion::$controlador = new \controladores\errores();
				\core\Aplicacion::$controlador->error_404($datos);
			}
		}
		else {
			$datos['mensaje'] = "La clase <b>$controlador_clase</b> no existe.";
			\core\Aplicacion::$controlador = new \controladores\errores();
			\core\Aplicacion::$controlador->error_404($datos);
		}
	}
	
} // Fin de la clase

