<?php
namespace core;


/**
 * Description of Idioma
 *
 * @author jesus
 */
class Idioma {
	
	/**
	 * Estudia el requerimiento para definir el idioma seleccionado por el cliente.
	 */
	public static function init() {
		
		$patron = "/^(".\core\Configuracion::$idiomas_reconocidos.")$/";
		if (\core\HTTP_Requerimiento::get("lang") && preg_match($patron, \core\HTTP_Requerimiento::get("lang"))) {
			\core\Configuracion::$idioma_seleccionado = \core\HTTP_Requerimiento::get("lang");
		}
		elseif (\core\HTTP_Requerimiento::cookie("lang") && preg_match($patron, \core\HTTP_Requerimiento::cookie("lang"))) {
			\core\Configuracion::$idioma_seleccionado = \core\HTTP_Requerimiento::cookie("lang");
		}	
		// Enviamos la cookie para que recuerde el idioma
		if (\core\Configuracion::$idioma_seleccionado) {
			\core\HTTP_Respuesta::setcookie("lang", \core\Configuracion::$idioma_seleccionado, time()+60*60*24*365);
		}	
		
	}
	
	
	/**
	 * Retorna el idioma seleccionado por el cliente.
	 * 
	 * @return string
	 */
	public static function get() {
		
		return \core\Configuracion::$idioma_seleccionado ? \core\Configuracion::$idioma_seleccionado : \core\Configuracion::$idioma_por_defecto;
		
	}
	
	/**
	 * Devuelve un texto asociado a una clave, tomado del fichero seccion_lang.txt
	 * @param string $key
	 * @param string $section
	 * @param string $lang
	 * @return string
	 */
	public static function text($key, $section , $lang = null ) {
		
		return \modelos\Idiomas::get($key, $section, $lang);
		
	}
	
}