<?php
namespace controladores;

class inicio extends \core\Controlador {
	
	public function index(array $datos = array()) {
		
		$http_body = \core\Vista_Plantilla::generar('plantilla_principal');
		\core\HTTP_Respuesta::enviar($http_body);
	}
	
	
} // Fin de la clase