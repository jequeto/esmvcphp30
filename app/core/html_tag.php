<?php
namespace core;

/**
 * Esta clase genera etiquetas html.
 * Cada etiqueta se definine en un método específico.
 *
 * @author Jesús María de Quevedo Tomé <jequeto@gmail.com>
 * @since 20130130
 */
class HTML_Tag extends \core\Clase_Base {

	protected static $depuracion=false;

	
	
	
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Genera el script html para una etiqueta <span>
	 * 
	 * @param string $input_id
	 * @param array $datos
	 * @return string Script html con una etiqueta <span>
	 */
	public static function span_error($input_id, array $datos) {
		
		return "<span id='error_$input_id' class='input_error' style='color: red;'>".(isset($datos['errores'][$input_id]) ? $datos['errores'][$input_id]:'')."</span>"; 
			
	}

	
	
	/**
	 * Registra el envío de un formulario al cliente web.
	 * Genera un id aleatorio para el formulario que se guardará en el array $_SESSION para luego poder ser validado cuando se reciba el
	 * formulario desde el cliente.
	 * Se emplea para evitar el tratamiento repetido de un mismo formulario. Evita ataques de hackers o reenvíos (F5).
	 * 
	 * @param string $name = null
	 * @return string
	 */
	public static function form_registrar($name = null, $method = "post") {
		
		$form_id  = rand(1000,9999); 
		$_SESSION["formularios"]["form_id"][$form_id ] = $name ;
		$_SESSION["formularios"]["method"][$form_id ] = $method;
		
		return ("<input type='hidden' name='form_id' value='$form_id ' />\n");
		
	}
	
	
	
	public static function form_autenticar($form_name = null, $method = null) {
		
		$resultado = false;
		if ( isset($_REQUEST["form_id"])) {
			$form_id = (integer)$_REQUEST["form_id"]; // Se convierte a integer porque por HTTP ha venido como string.
			if (isset($_SESSION["formularios"]["form_id"][$form_id])) {
				if (is_string($form_name) && strlen($form_name)) {
					$resultado = ($_SESSION["formularios"]["form_id"][$form_id] == $form_name);
					if (is_string($method) && strlen($method)) {
						$resultado = ($resultado && ($_SESSION["formularios"]["method"][$form_id] == $method) && (strtoupper($_SERVER["REQUEST_METHOD"]) == strtoupper($method)));
					}
				}
				// Anulo la entrada del form_id recibido en el array $_SESSION["formularios"]
				unset($_SESSION["formularios"]["form_id"][$form_id]);
				unset($_SESSION["formularios"]["method"][$form_id]);
			}
		}
		
		return $resultado;
		
	}
	
	
	
	
	public static function li_menu
	(
			$clases,
			array $query_string = array("inicio", "index"),
			$texto,
			array $otros_argumentos = null
	) {
		
		$link = "";
		$controlador = isset($query_string[0]) ? $query_string[0] : "inicio";
		$metodo = isset($query_string[1]) ? $query_string[1] : "index";
		if ( ! \core\Usuario::tiene_permiso($controlador, $metodo)) {
			return $link;
		};
				
		$uri = \core\URL::http_generar($query_string);
		$link = "<li class='$clases'>".self::a_boton("", $query_string, $texto, $otros_argumentos)."</li>";
		return $link;
		
	}
	
	public static function a_boton(
			$clases,
			array $query_string = array("inicio", "index"),
			$texto,
			array $otros_argumentos = null
	) {
		
		$link = "";
		$controlador = isset($query_string[0]) ? $query_string[0] : "inicio";
		$metodo = isset($query_string[1]) ? $query_string[1] : "index";
		if ( ! \core\Usuario::tiene_permiso($controlador, $metodo)) {
			return $link;
		};
				
		$uri = \core\URL::http_generar($query_string);
		$link = "<a class='$clases' href='$uri' >$texto</a>";
		return $link;
		
	}
	

} // Fin de la clase