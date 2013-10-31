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


} // Fin de la clase