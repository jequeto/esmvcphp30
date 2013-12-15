<?php
namespace controladores;

class mensajes extends \core\Controlador {
	
	
	public function index(array $datos = array()) {
		
		\core\Distribuidor::cargar_controlador("mensajes", "mensaje");
		
	}
	
	
	
	
	public function mensaje(array $datos = array()) {
		
		$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos, true);
		$http_body = \core\Vista_Plantilla::generar('plantilla_principal', $datos, true);
		\core\HTTP_Respuesta::enviar($http_body);
		
		
	}
	
	
} // Fin de la clase