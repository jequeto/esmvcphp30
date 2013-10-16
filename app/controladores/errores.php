<?php
namespace controladores;

class errores extends \core\Controlador {
	
	public function error_404(array $datos = array()) {
		
		$contenido = \core\Vista_Plantilla::generar("plantilla_404", $datos);
		\core\HTTP_Respuesta::set_http_header_status("404");
		\core\HTTP_Respuesta::enviar($contenido);
				
	}
	
	
} // Fin de la clase