<?php
namespace core;


/* Referencias: 
 * http://www.tutorialspoint.com/http/http_messages.htm
 * http://es1.php.net/manual/es/function.header.php
 */

/**
 * Se encarga de enviar la respuesta que se genera en la aplicación.
 * La respuesta será por defecto del tipo MIME 'text/html';
 */
class HTTP_Respuesta extends \core\Clase_Base {
	
	private static $http_header_protocol = "HTTP 1.1";
	private static $http_header_status = "200";
	
	
	private static $http_header_lines = array(
		"Content-Type" => "text/html",
	);
	
	private static $http_body_content = "";
	
	
	
	public static function set_header_line($key, $value) {
		
		self::$http_header_lines[$key] = $value;
		
	}


	/**
	 * Cambia el tipo MIME de la respuesta HTTP que define el contenido de la línea Content-Type del HEADER.
	 * Por defeto las respuestas se envía con el tipo 'text/plain'.
	 * 
	 * @param string $tipo_mime
	 * @throws \Exception
	 */
	public static function set_mime_type($tipo_mime) {
		
		if (\core\Array_Datos::contiene($tipo_mime, \core\Configuracion::$tipos_mime_reconocidos)) {
			self::set_header_line('Content-Type', $tipo_mime);
			if ($tipo_mime == 'application/excel') {
				self::set_header_line('Content-Disposition', "attachment;filename=libro.xls");
			}
		}
		else {
			throw new \Exception(__METHOD__." Error: tipo mime <b>$tipo_mime</b> no válido, solo se admite uno de los siguientes:".  implode(' , ', \core\Configuracion::$tipos_mime_reconocidos));
		}
		
	}
	
	
	
	/**
	 * Envia la respuesta HTTP, compuesta de HEADER y BODY
	 * Si el HEADER ya se ha enviado lanza un warning.
	 * 
	 * @param array $datos
	 * @param type $plantilla
	 * @throws \Exception
	 */
	public static function enviar($http_body_content = null) {
		
		if ($http_body_content) {
			self::set_http_body($http_body_content);
		}
		
		// Añadimos a la cabecera la longitud del cuerpo, si es mayor que cero
		if (strlen(self::$http_body_content)) {
			self::set_header_line("Content-Length", (string) strlen(self::$http_body_content) );
		}
		
		// Enviar HEADER
		self::send_header();
		
		// Enviar BODY
		self::send_body();
		
	}
	
	
	private static function send_header() {
		
		$fichero = ''; // Almacena información en caso de header enviado
		$linea = ''; // Almacena información en caso de header enviado
		
		if ( ! headers_sent($fichero, $linea)) { // Enviamos en encabezado HTTP
			if ( ! isset(self::$http_header_lines['Content-Type']) ) {
				self::$http_header_lines['Content-Type'] = \core\Configuracion::$tipo_mime_por_defecto;
			}
			http_response_code(self::$http_header_status); // Enviamos el código de la respuesta
			foreach (self::$http_header_lines as $key => $value) {
				// Enviamos las líneas del header
				header("$key: $value");
			}
		}
		else { // El encabezado HTTP ya se ha enviado
			echo __METHOD__." Warning: El encabezado php se originó en el fichero <b>$fichero</b> , en la línea <b>$linea</b>.<br />";
		}
		
	}
	
	
	public static function set_http_body($content) {
		
		self::$http_body_content = $content;
		
	}
	
	
	private static function send_body() {
		
		echo self::$http_body_content;
		
	}

	
	public static function set_http_header_status($http_header_status) {
		
		self::$http_header_status = $http_header_status;
		
	}
		
	
}