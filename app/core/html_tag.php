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
	public static function form_registrar($name = null) {
		
		$form_id  = rand(1000,9999); 
		$_SESSION["formularios"]["form_id"][$form_id ] = $name ;
		$_SESSION["formularios"]["method"][$form_id ] = $method;
		
		return ("<input type='hidden' name='$name' value='$form_id ' />\n");
		
	}
	
	
	
	public static function form_validar() {
		
		$resultado = false;
		
		if ( isset($_REQUEST["form_id"])) {
			
		}
		
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