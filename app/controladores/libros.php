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
		
		$libro = \core\HTTP_Requerimiento::post();
		
		\modelos\Libros_En_Fichero::anexar_libro($libro);
		
		\core\Distribuidor::cargar_controlador("libros", "index");
		
	}
	
	
	/**
	 * Muestra una vista con un formulario conteniendo los datos del libro
	 * que se quiere modificar. El id del libro se recibe por get.
	 * 
	 * @param array $datos
	 */
	public function form_modificar(array $datos = array()) {
		
		$id = \core\HTTP_Requerimiento::get('id');
		
		$datos['values'] = \modelos\Libros_En_Fichero::get_libros($id);
		$datos['values']['id'] = $id;
		
		$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos, true);
		$http_body = \core\Vista_Plantilla::generar('plantilla_libros', $datos, true);
		\core\HTTP_Respuesta::enviar($http_body);
		
	}
	
	
	
	public function form_modificar_validar(array $datos = array()) {
		
		$libro = \core\HTTP_Requerimiento::post();
		
		//print_r($_POST); print_r($libro); exit(0);
		
		\modelos\Libros_En_Fichero::modificar_libro($libro);
		
		\core\Distribuidor::cargar_controlador("libros", "index");
		
	}
	
	
	/**
	 * Muestra una vista con un formulario de solo lectura conteniendo los datos del libro
	 * que se quiere borrar. El id del libro se recibe por get.
	 * 
	 * @param array $datos
	 */
	public function form_borrar(array $datos = array()) {
		
		
		$id = \core\HTTP_Requerimiento::get('id');
		
		$datos['values'] = \modelos\Libros_En_Fichero::get_libros($id);
		$datos['values']['id'] = $id;
		
		$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos, true);
		$http_body = \core\Vista_Plantilla::generar('plantilla_libros', $datos, true);
		\core\HTTP_Respuesta::enviar($http_body);
		
		
	}
	
	
	
	public function form_borrar_validar(array $datos = array()) {
		
		$libro = \core\HTTP_Requerimiento::post();
		
		// print_r($_POST); print_r($libro); exit(0);
		
		\modelos\Libros_En_Fichero::borrar_libro($libro['id']);
		
		\core\Distribuidor::cargar_controlador("libros", "index");
		
	}
	
	
	
} // Fin de la clase