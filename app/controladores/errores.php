<?php
namespace controladores;

class errores extends \core\Controlador {
	
	public function error_404(array $datos = array()) {
		
		$contenido = \core\Vista_Plantilla::generar("plantilla_404", $datos);
		\core\HTTP_Respuesta::set_http_header_status("404");
		\core\HTTP_Respuesta::enviar($contenido);
				
	}
	
	
	public function mensaje(array $datos = array()) {
		
		$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos, true);
		$http_body = \core\Vista_Plantilla::generar('plantilla_principal', $datos, true);
		\core\HTTP_Respuesta::enviar($http_body);
		
		
	}
	
	
} // Fin de la clase