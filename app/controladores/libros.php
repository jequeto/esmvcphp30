<?php
namespace controladores;


class libros extends \core\Controlador {
	
	
	
	public function index(array $datos = array()) {
		
		$datos['libros'] = \modelos\Libros_En_Array::get_libros();
		
		$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos, true);
		$http_body = \core\Vista_Plantilla::generar('plantilla_libros', $datos, true);
		\core\HTTP_Respuesta::enviar($http_body);
		
	}
	
	
	public function form_anexar(array $datos = array()) {
		
		$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos, true);
		$http_body = \core\Vista_Plantilla::generar('plantilla_libros', $datos, true);
		\core\HTTP_Respuesta::enviar($http_body);
		
	}
	
} // Fin de la clase