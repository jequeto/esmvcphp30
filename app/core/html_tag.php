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
	
	
	
	/**
	 * Esta función generará un link para colocarlo en una página web como parte del "menu disperso".
	 * Se considera "menú disperso" al conjunto de links y botones que aparecen fuera de la barra de navegación.
	 * Por ser un elemento del menú, será generado siempre que el usuario conectado tenga permisos para
	 * acceder a ese elemento del menu. Si el usuario no tiene permisos para acceder a la sección y subsección
	 * definidas en la request, se generará una cadena en vacía.
	 * @param string $classes
	 * @param string $url
	 * @param array $query_string = array("s"=>"subseccion", "ss"=>"subseccion" [, ...])
	 * @param string $contenido innerHTML para la etiqueta <a>innerHTML</a>
	 * @param array $otros_argumentos = array("argumento"=>"valor",...)
	 * @return string con html_tag_code
	 */
	public static function menu_link($classes, $url, array $query_string, $contenido, array $otros_argumentos=null) {
		
		if (self::$depuracion) {
			print(__METHOD__."-->");
			print_r($request);
			print_r("<br />");
		}
		
		if ( ! \ESOO::$aplicacion->usuario->tiene_permiso($request["s"], $request["ss"])) {
			$link = (self::$depuracion) ? "({$request["s"]}, {$request["ss"]}) -> sin permisos " : "";
		}
		else {
			$href = ($url != null) ? $url : "";
			if (count($request)) {
				$href .= "?";
				foreach ($request as $key => $value) {
					$href .= $key."=".$value."&";
				}
			}
			//$href.="r=".rand(1000, 9999);
			$link = "<a class='$classes' href='$href' ";
			if (count($otros_argumentos))
				foreach ($otros_argumentos as $key => $value) {
					$link .= " $key='$value' ";
				}
			$link .= " >$contenido</a>";
		}
		return $link;
	}

	

} // Fin de la clase