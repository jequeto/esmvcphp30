<?php
namespace core;

/**
 * @author Jesús María de Quevedo Tomé <jequeto@gmail.com>
 * @since 20130130
 */
class Clase_Base {
	/**
	 * Contenedor de datos para cualquier clase, en especial para los controladores.
	 * @var array 
	 */
	public $datos = array(); 
	
	/**
	 * Realiza el estudio del requerimiento http recibido y elige el 
	 * controlador y método que se ejecutará para atenderla.
	 */
	public static function distribuidor() {
		
		$controlador = \core\Requerimiento_HTTP::get('menu');
		$metodo = \core\Requerimiento_HTTP::get('submenu');
		
		if ( ! $controlador )
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
				$datos['mensaje'] = "El método <b>$metodo</b> no está definido en <b>$controlador_clase</b>.";
				\core\Respuesta::enviar($datos, "plantilla_404");
			}
		}
		else {
			$datos['mensaje'] = "La clase <b>$controlador_clase</b> no existe.";
			\core\Respuesta::enviar($datos, "plantilla_404");
		}
	}
	
	/**
	 * Devuelve el contenido de una entrada del array que se pasa por parámetro.
	 * Si la entrada no existe devuelve null.
	 * 
	 * @param string|integer $indice
	 * @param array $array
	 * @return mixed
	 */
	public function contenido($indice, array $array)
	{
		if ( ! is_string($indice) && ! is_integer($indice))
			throw new \Exception(__METHOD__." Error: parámetro \$indice=$indice debe ser entero o string");
		
		return (array_key_exists($indice, $array) ? $array[$indice] : null);
	}
} // Fin de la clase

