<?php
namespace controladores;


class libros extends \core\Controlador {
	
	
	/**
	 * Devuelve una vista con una tabla html conteniendo en cada fila un libro.
	 * 
	 * @param array $datos
	 */
	public function index(array $datos = array()) {
		
		$datos['libros'] = \modelos\Libros_En_Fichero::get_libros();
		
		$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos, true);
		$http_body = \core\Vista_Plantilla::generar('plantilla_libros', $datos, true);
		\core\HTTP_Respuesta::enviar($http_body);
		
	}
	
	/**
	 * Muestra una vista con el formulario para anexar un libro.
	 * 
	 * @param array $datos
	 */
	public function form_anexar(array $datos = array()) {
			
		$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos, true);
		$http_body = \core\Vista_Plantilla::generar('plantilla_libros', $datos, true);
		\core\HTTP_Respuesta::enviar($http_body);
		
	}
	
	
	public function form_anexar_validar(array $datos = array()) {
		
//		$libro = \core\HTTP_Requerimiento::post();
		
		$validaciones = array(
			"titulo" => "errores_requerido && errores_texto",
			"autor" => "errores_requerido && errores_texto",
			"comentario" => "errores_texto",
		);
		
		$validacion = !\core\Validaciones::errores_validacion_request($validaciones, $datos);
		if (! $validacion) {
			print "-- Depuración: \$datos= "; print_r($datos);
			\core\Distribuidor::cargar_controlador("libros", "form_anexar", $datos);
		}
		else {
			$libro = $datos['values']; //Valores de los input que han sido validados
			\modelos\Libros_En_Fichero::anexar_libro($libro);
//			\modelos\Libros_En_Fichero::anexar_libro($datos['libros']);
			\core\HTTP_Respuesta::set_header_line("location", "?menu=libros&submenu=index");
			\core\HTTP_Respuesta::enviar();
		}
		
	}
	
	
	/**
	 * Muestra una vista con un formulario conteniendo los datos del libro
	 * que se quiere modificar. El id del libro se recibe por get.
	 * 
	 * @param array $datos
	 */
	public function form_modificar(array $datos = array()) {
		
		if ( ! isset($datos['errores'])) {
			// Recuperamos datos del libro del fichero de texto solo si no vienen datos del libro junto con los errores de validación.
			$id = \core\HTTP_Requerimiento::get('id');

			$datos['values'] = \modelos\Libros_En_Fichero::get_libros($id);
			$datos['values']['id'] = $id;
		}
		$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos, true);
		$http_body = \core\Vista_Plantilla::generar('plantilla_libros', $datos, true);
		\core\HTTP_Respuesta::enviar($http_body);
		
	}
	
	
	
	public function form_modificar_validar(array $datos = array()) {
		
//		$libro = \core\HTTP_Requerimiento::post();
		
		//print_r($_POST); print_r($libro); exit(0);
		
		$validaciones = array(
			"id" => "errores_requerido && errores_numero_entero_positivo",
			"titulo" => "errores_requerido && errores_texto",
			"autor" => "errores_requerido && errores_texto",
			"comentario" => "errores_texto",
		);
		
		$validacion = !\core\Validaciones::errores_validacion_request($validaciones, $datos);
		if (! $validacion) {
		    if (isset($datos['errores']['id'])) {
				$datos['errores']['validacion'] = "No es posible identificar el id del libro a modificar.<br />". $datos['errores']['validacion'];
			}
			print "-- Depuración: \$datos= "; print_r($datos);
			\core\Distribuidor::cargar_controlador("libros", "form_modificar", $datos);
		}
		else {
			$libro = $datos['values']; //Valores de los input que han sido validados
			\modelos\Libros_En_Fichero::modificar_libro($libro);
		
	//		\core\Distribuidor::cargar_controlador("libros", "index");
			\core\HTTP_Respuesta::set_header_line("location", "?menu=libros&submenu=index");
			\core\HTTP_Respuesta::enviar();
		}
		
	}
	
	
	/**
	 * Muestra una vista con un formulario de solo lectura conteniendo los datos del libro
	 * que se quiere borrar. El id del libro se recibe por get.
	 * 
	 * @param array $datos
	 */
	public function form_borrar(array $datos = array()) {
		
		if ( ! isset($datos['errores'])) {
			// Recuperamos datos del libro del fichero de texto solo si no vienen datos del libro junto con los errores de validación.

			$id = \core\HTTP_Requerimiento::get('id');

			$datos['values'] = \modelos\Libros_En_Fichero::get_libros($id);
			$datos['values']['id'] = $id;
		}
		
		$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos, true);
		$http_body = \core\Vista_Plantilla::generar('plantilla_libros', $datos, true);
		\core\HTTP_Respuesta::enviar($http_body);
		
		
	}
	
	
	
	public function form_borrar_validar(array $datos = array()) {
		
//		$libro = \core\HTTP_Requerimiento::post();
		
		// print_r($_POST); print_r($libro); exit(0);
		
		$validaciones = array(
			"id" => "errores_requerido && errores_numero_entero_positivo",
			// La siguientes reglas no son necesarias porque el formulario form_borrar es de solo lectura y los datos no se modificarán
			"titulo" => "errores_requerido && errores_texto",
			"autor" => "errores_requerido && errores_texto",
			"comentario" => "errores_texto",
		);
		
		$validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos);
		if (! $validacion) {
			$datos['errores']['validacion'] = 'Error al identificar el id del libro a borrar.' . $datos['errores']['validacion'];
			print "-- Depuración: \$datos= "; print_r($datos);
			\core\Distribuidor::cargar_controlador("libros", "form_borrar", $datos);
		}
		else {
			$libro = $datos["values"]; // Los datos del libro están recogidos por la validación en $datos[values]
			print "-- Depuración: \$datos= "; print_r($datos);
			\modelos\Libros_En_Fichero::borrar_libro($libro['id']);
			\modelos\Libros_En_Fichero::borrar_libro($datos["values"]['id']);

			//		\core\Distribuidor::cargar_controlador("libros", "index");
			\core\HTTP_Respuesta::set_header_line("location", "?menu=libros&submenu=index");
			\core\HTTP_Respuesta::enviar();
		}
	}
	
	
	
} // Fin de la clase