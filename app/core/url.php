<?php
namespace core;


class URL {
	
	/**
	 * Retorna una URI con el esquema http
	 * <br />La url no contiene el nombre del fichero index.php
	 * <br />Ejemplo de URL generada: http://www.servidor.com/?query_string  http://www.servidor.com/aplicacion/?query_string
	 
	 * @param string $query_string
	 * @return string
	 */
	public static function http($query_string = '') {
		
		$patron = "/^\?.*/";
		if ( !preg_match($patron, $query_string))
			$query_string .= "?$query_string";
		
		$url = "http://".$_SERVER['SERVER_NAME'].str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
		
		return $url.$query_string;
		
	}
	
	
	/**
	 * Retorna una URL que requiere protocolo https partiendo de la que recibió la petición para ejecutar el index.php.
	 * <br />La url no contiene el nombre del fichero index.php
	 * <br />Ejemplo de URL generada: https://www.servidor.com/?query_string  https://www.servidor.com/aplicacion/?query_string
	 * @param string $query_string
	 * @return string
	 */
	public static function https($query_string = '') {
		
		$patron = "/^\?.*/";
		if ( ! preg_match($patron, $query_string))
			$query_string .= "?$query_string";
		
		$carpeta = str_replace('index.php', '',$_SERVER['SCRIPT_NAME']);
		return "https://{$_SERVER['HTTP_HOST']}$carpeta$query_string";
		
	}
	
	
	
	/**
	 * Devuelve una URL en formato amigable o con parámetros en función del valor
	 * de la propiedad boolean \core\Configuracion::$url_amigable.
	 * Construye la url incluyendo el idioma en función del parámetro $withLang y siempre que 
	 * el idioma sea distinto del idioma por defecto de la aplicación (\core\Configuracion::$idioma_por_defecto).
	 * 
	 * @param string|array $query_string "dato1/dato2[/...]" array("dato1", "dato2", ...)
	 * @param boolean $withLang=true genera ulr con idioma <b>false</b> genera url sin idioma 
	 * @return string URL amigable o con parámetros
	 * 
	 * @throws Exception Si el strin no cumple el formato
	 */
	static function generar($query_string = array(), $withLang = true) {
			
			if ( is_string($query_string)) { // Solo actua si hay datos para hacer la query string.
//				// Patrón url amigable
				$patron = "/^([\w\-]+(([\/\w\-]+)(\/.+)*)?)?$/i";

				if ( ! preg_match($patron, $query_string))
					throw new \Exception ("El parámetro \$query_string='$query_string' debe tener el formato 'texto[/texto]...'");
				// Convertimos el la cadena en array
				if ( !strlen($query_string))
					$query_string = array();
				else
					$query_string = explode("/", $query_string);
			
			}
			
			return URL_ROOT.((\core\Configuracion::$url_amigable) ? self::amigable($query_string, $withLang) : self::query_string($query_string, $withLang));

	}
		
	
	
	
	
	static function http_generar($query_string = array(), $withLang = true) {
		
		return self::generar($query_string, $withLang);
		
	}
	
	
	
	static function https_generar($query_string = array(), $withLang = true) {
		
		$url = self::generar($query_string, $withLang);
		return str_replace("http:", "https:", $url);
		
	}
	
	
	/**
	 * Genera una url absoluta que no incluye administrator ni idioma, con esquema http.
	 * 
	 * @param string $query_string
	 * @return string
	 */
	static function http_root($query_string = array()) {
		
		return self::generar($query_string, false, false);
		
	}
	
	
	/**
	 * Genera una url absoluta que no incluye administrator ni idioma, con esquema https.
	 * 
	 * @param string $query_string
	 * @return string
	 */
	static function https_root($query_string = array()) {
		
		$url = self::generar($query_string, false, false);
		return str_replace("http:", "https:", $url);
		
	}
	
	
	

	/**
	 * Genera una url absoluta con administrator e idioma, con esquema http.
	 * 
	 * @param string $query_string
	 * @return string
	 */
	static function generar_con_idioma($query_string = array()) {
		
		return self::generar($query_string, true);
		
	}

	/**
	 * Genera una url absoluta sin administrator y sin idioma, con esquema http.
	 * 
	 * @param string $query_string
	 * @return string
	 */
	static function generar_sin_idioma($query_string = array()) {
		
		return self::generar($query_string, false);
		
	}
	

	/**
	 * Genera URI con parámetros
	 * 
	 * @param array $query_string array("dato1", "dato2"[,...])
	 * @return string URL con formato ?p1=dato1&p2=dato2[&...]
	 */
	private static function query_string(array $query_string = null, $withLang = true, $administrator = true) {

		$url = "?";
		
		if ($administrator && isset($_GET["administrator"])) {
			$url .= "administrator=";
		}
		
		if ($withLang && \core\Configuracion::$idioma_seleccionado && \core\Configuracion::$idioma_seleccionado != \core\Configuracion::$idioma_por_defecto) {
			$url .= (strlen($url) == 1 ? "" : "&")."lang=".\core\Configuracion::$idioma_seleccionado;
		}
		
		foreach ($query_string as $key => $value) {
			$url .= (strlen($url) == 1 ? "" : "&")."p".($key+1)."=$value";
			
		}
		
		// Si la logitud de la url es 1 es que solo tiene el caracter ?, y por tanto lo borramos
		if (strlen($url) ==1) {
			$url = "";
		}

		return $url;

	}


	/**
	 * Genera URI amigable con formato de carpetas
	 * 
	 * @param array $query_string array("dato1", "dato2",...)
	 * @return string URI con formato /dato1/dato2/[.../]
	 */
	private static function amigable(array $query_string = array(), $withLang = true, $administrator = true) {

		$url = "";
		if ($administrator && isset($_GET["administrator"])) {
			$url .= "administrator/";
		}
		if ($withLang && \core\Configuracion::$idioma_seleccionado && \core\Configuracion::$idioma_seleccionado != \core\Configuracion::$idioma_por_defecto) {
			$url .= \core\Configuracion::$idioma_seleccionado."/";
		}
		
		foreach ($query_string as $key => $value) {
			$url .= "$value/";
		}	

		return $url;

	}
	
	
	
			
}