<?php
namespace controladores;


class usuarios extends \core\Controlador {
	

	
	public function index(array $datos = array()) {
		
		$clausulas['order_by'] = 'login';
		
		$datos['filas'] = \modelos\usuarios::select($clausulas, 'usuarios' );
		
		$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos, true);
		$http_body = \core\Vista_Plantilla::generar('plantilla_principal', $datos, true);
		\core\HTTP_Respuesta::enviar($http_body);
		
	}
	
	
	
	public function form_login(array $datos = array()) {
		
		if ((isset($_SERVER["REQUEST_SCHEME"]) && $_SERVER["REQUEST_SCHEME"] == "http") 
				|| (isset($_SERVER["SERVER_PORT"]) && $_SERVER["SERVER_PORT"] == 80)) {
			\core\HTTP_Respuesta::set_header_line("location", \core\URL::https_generar("usuarios/form_login"));
			\core\HTTP_Respuesta::enviar();
		}
		elseif (\core\Usuario::$login == "anonimo") {
			$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos, true);
			$http_body = \core\Vista_Plantilla::generar('plantilla_principal', $datos, true);
			\core\HTTP_Respuesta::enviar($http_body);
		}
		else {
			$datos["mensaje"] = "El usuario actual es <b>".\core\Usuario::$login."</b>. Para cambiar de usuario debes desconectarte primero." ;
			$this->cargar_controlador('mensajes', 'mensaje', $datos);
		}
		
		
	}
	
	
	public function form_login_validar(array $datos = array()) {
		
		if (\core\Usuario::$login != "anonimo") {
			$datos["mensaje"] = "Ya te encuentras conectado. Utiliza el menú para navegar.";
			\core\Distribuidor::cargar_controlador("mensajes", "mensaje", $datos);
		}
		elseif ( ! \core\HTML_Tag::form_autenticar("form_login", "post")) {
			// El formulario no se ha enviado con anterioridad desde el servidor
			$datos["mensaje"] = "Error: formulario no identificado.";
			\core\Distribuidor::cargar_controlador("errores", "mensaje", $datos);
		}
		else {
			/*
			require_once(PATH_APP.'lib/php/recaptcha-php-1.11/recaptchalib.php');
			$privatekey = "6Lem1-sSAAAAAPfnSmYe5wyruyuj1B7001AJ3CBh";
			$resp = recaptcha_check_answer ($privatekey,
										  $_SERVER["REMOTE_ADDR"],
										  $_POST["recaptcha_challenge_field"],
										  $_POST["recaptcha_response_field"]);

			if (!$resp->is_valid) {
			  		$datos['errores']['validacion'] = 'Error de intruducción del captcha.';
					\core\Distribuidor::cargar_controlador("usuarios", "form_login", $datos);
			} else */
			{
			  // Your code here to handle a successful verification



				// El formulario sí se ha enviado desde el servidor
				$validaciones = array(
					'login' => 'errores_requerido && errores_login',
					'password' => 'errores_requerido && errores_password'
				);

				$validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos);

				if ($validacion) {		
					$respuesta =  \modelos\usuarios::validar_usuario($datos['values']['login'], $datos['values']['password']);

					if  ($respuesta == 'existe') {
							$datos['errores']['validacion'] = 'Error en login o password';
							\core\Distribuidor::cargar_controlador("usuarios", "form_login", $datos);
					}
					elseif ($respuesta == 'existe_autenticado') {
							$datos['mensaje'] = "Falta confimación del usuario {$datos['values']['login']}. Consulta tu correo electrónico" ;
							$this->cargar_controlador('mensajes', 'mensaje', $datos);
					}
					elseif ($respuesta == 'existe_autenticado_confirmado') {
							$datos['login'] = $datos['values']['login'];

							$clausulas['where'] = " login = '{$datos['values']['login']}' ";
							$filas = \modelos\Modelo_SQL::tabla("usuarios")->select($clausulas);

							\core\Usuario::nuevo($datos['values']['login'], $filas[0]['id']);
							$_SESSION["mensaje"] = "Bienvenido la aplicación: {$datos['values']['login']}." ;
							\core\HTTP_Respuesta::set_header_line("location", \core\URL::http_generar("mensajes/mensaje"));
							\core\HTTP_Respuesta::enviar();
					}
				}
				else {
					$datos['errores']['validacion'] = 'Error de usuario o contraseña';
					\core\Distribuidor::cargar_controlador("usuarios", "form_login", $datos);
				}
			}
			
		}
		
	}
	
	
	
	
	public function desconectar(array $datos = array()) {
		
		\core\Usuario::cerrar_sesion();
		if ( ! isset($datos['desconexion_razon']))
			$datos['desconexion_razon'] = null;
		if ($datos['desconexion_razon'] === null) {
			$datos['mensaje'] = 'Adios';
			$datos['url_continuar'] = \core\URL::generar("inicio");
		}
		elseif ($datos['desconexion_razon'] == 'inactividad') {
			$datos['mensaje'] = 'Has superado el tiempo de inactividad que es de <b>'.\core\Configuracion::$sesion_minutos_inactividad.'</b> minutos.';
			$datos['url_continuar'] = \core\URL::generar("inicio");
		}
		elseif ($datos['desconexion_razon'] == 'tiempo_sesion_agotado') {
			$datos['mensaje'] = 'Has agotado el tiempo de tu sesión que es de <b>'.\core\Configuracion::$sesion_minutos_inactividad.'</b> minutos.<br />Vuelve a conectarte para seguir trabajando.';
			 $datos['url_continuar'] = \core\URL::generar("inicio");
		}
		
		$this->cargar_controlador('mensajes', 'mensaje', $datos);
		
	}
	
	
	public function form_login_email(array $datos = array())
	{
		$datos['js'][self::js_script_tag(__FUNCTION__)] = true;
		$datos['js'][self::js_script_lib_tag('jquery/jquery-1.6.4.min.js')] = true;
		$datos['css'][self::css_link_tag(__FUNCTION__)] = true;
		//print_r($datos);

		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		\core\Respuesta::enviar($datos);
	}
	
	
	
	public function validar_form_login_email(array $datos = array())
	{
		$validaciones = array(
			'login' => 'errores_texto',
			'email' => 'errores_email',
			'contrasena' => 'errores_requerido'
		);
		
		$validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos);
		if ($validacion)
		{		
			if ( ! strlen($datos['values']['login']) && ! strlen($datos['values']['login'])) {
				$datos['errores']['validacion'] = 'Introduce el login o el dni';
				$validacion = false;
			}
			elseif ( ! strlen($datos['values']['login']) && ! strlen($datos['values']['login'])) {
				$datos['errores']['validacion'] = 'Introduce solo uno de los dos: login o el dni';
				$validacion = false;
			}
		}
		if ($validacion)
		{		
			$respuesta =  \modelos\usuarios::validar_usuario_login_email($datos['values']);
			if  ($respuesta == 'existe') {
					$datos['error_validacion'] = 'Error en usuario o contraseña';
					$this->form_login($datos);
			}
			elseif ($respuesta == 'existe_autenticado') {
					$datos['login'] = $datos['values']['login'];
					$this->cargar_controlador('inicio', 'falta_confirmar', $datos);
			}
			elseif ($respuesta == 'existe_autenticado_confirmado') {
					$datos['login'] = $datos['values']['login'];
					\core\Usuario::nuevo($datos['values']['login']);
					$this->cargar_controlador('inicio', 'logueado', $datos);
			}
			else
					echo __METHOD__." REspuesta de valicacion: '$respuesta'";
		}
		else {
			//print_r($datos);
			$datos['errores']['validacion'] = 'Corrige los errores.';
			$this->form_login_email($datos);
		}
	}
	
	
	
	
	public function validar_form_modificar(array $datos = array()) {
	
		
	}
	
	
	public function validar_form_borrar(array $datos = array())	{
		
		
	}
	
	
	public function form_insertar(array $datos = array()) {
		
		$datos['view_content'] = \core\Vista::generar("form_insertar", $datos, true);
		$http_body = \core\Vista_Plantilla::generar('plantilla_principal', $datos, true);
		\core\HTTP_Respuesta::enviar($http_body);
		
	}
	
	
	public function form_insertar_validar(array $datos = array()) {
		
		if (self::form_insertar_validar_interno($datos)) {
			
			$_SERVER["alerta"] = "Se ha insertado correctamente el usuario.";
			\core\HTTP_Respuesta::set_header_line("location", \core\URL::generar("usuarios/index"));
			\core\HTTP_Respuesta::enviar();
			
		}
		else {
			\core\Distribuidor::cargar_controlador("usuarios", "form_insertar",$datos);
		}
		
	}
	
	
	
	public function form_insertar_externo(array $datos = array()) {
		
		$datos['view_content'] = \core\Vista::generar("form_insertar", $datos, true);
		$http_body = \core\Vista_Plantilla::generar('plantilla_principal', $datos, true);
		\core\HTTP_Respuesta::enviar($http_body);
		
	}
	
	
	public function form_insertar_externo_validar(array $datos = array()) {
		
		if (self::form_insertar_validar_interno($datos)) {
			
			$url = \core\URL::generar("usuarios/confirmar_alta/{$datos['values']['id']}/{$datos['values']['clave_confirmacion']}");
			
			$to = $datos["values"]["email"];
			$subject = "Confirmación de alta de usuario en ".TITULO;
			$message = "Para confirmar tu registro en la aplicación ".TITULO." pulsa en el siguiente hipervínculo o sino cópialo en la ventana de direcciones de tu navegador. <a href='$url' target='_blank'>$url</a>";
			$additional_headers = "From: ".  \core\Configuracion::$email_noreply;
			
			$envio_email = mail($to, $subject, $message, $additional_headers);
			
			$datos["mensaje"] = "Se ha grabado correctamente el usuario. Haz la confirmación por correo electronico. Pinchando en el enlace que se envía $url";
			
			$this->cargar_controlador('mensajes', 'mensaje', $datos);
			
		}
		else {
			\core\Distribuidor::cargar_controlador("usuarios", "form_insertar_externo",$datos);
		}
		
	}
	
	
	
	
	private function form_insertar_validar_interno(array &$datos = array()) {
		
		$validaciones = array(
			'login' => 'errores_requerido && errores_login && errores_unicidad_insertar:login/usuarios/login',
			'email' => 'errores_requerido && errores_email ',
			'email2' => 'errores_requerido && errores_email ',
			'password' => 'errores_requerido && errores_password',
			'password2' => 'errores_requerido && errores_password',
		);
		
		$validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos);
		
		if (( !isset($datos["errores"]["email"]) && !isset($datos["errores"]["email2"])) && ($datos["values"]["email"] != $datos["values"]["email2"]) ) {
				$datos["errores"]["email2"] = "La repetición del email no coincide.";
				$validacion = false;
		}
		if (( !isset($datos["errores"]["password"]) && !isset($datos["errores"]["password2"])) && ($datos["values"]["password"] != $datos["values"]["password2"]) ) {
				$datos["errores"]["password2"] = "La repetición del password no coincide.";
				$validacion = false;
		}
		
		
		if ($validacion)
		{
			unset($datos["errores"]["email2"]);
			unset($datos["errores"]["password2"]);
			
			$datos['values']['password'] = md5($datos['values']['password']);
			$datos['values']['clave_confirmacion'] = \core\Random_String::generar(30);
	
			\modelos\usuarios::insert($datos['values'], 'usuarios');
			
			$datos['values']['id'] = \modelos\usuarios::last_insert_id();
			
			
		}
		
		return $validacion;
	}
	
	
	
	public function confirmar_alta(array $datos = array()) {
		
		$validaciones = array(
			'pid' => 'errores_requerido && errores_referencia:id/usuarios/id'
			,'key' => 'errores_requerido '
		);
		
		if ( ! $validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos)) {
			$datos['mensaje'] = 'Petición incorrecta.';
			\core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);
			return;
		}
		else {
			
			$clausulas['where'] = " id = {$datos['values']['id']} and clave_confirmacion = '{$datos['values']['key']}' and fecha_confirmacion_alta is not null " ;
			$filas = \modelos\usuarios::select('usuarios', $clausulas);
			
			if (count($filas)) {
				// El usuario esta confirmado previamente
				$datos['mensaje'] = "Este proceso de confirmación lo realizaste en una fecha anterior: {$filas[0]['fecha_confirmacion_alta']}.";
				\core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);
				return;
			}
			else {
				$clausulas['where'] = " id = {$datos['values']['id']} and clave_confirmacion = '{$datos['values']['key']}' and fecha_confirmacion_alta is null " ;
				$filas = \modelos\usuarios::select('usuarios', $clausulas);
				if (count($filas) == 1) {
					// El usuario es correcto y está sin confirmar
					unset($datos['values']['key']);
					$datos['values']['fecha_confirmacion_alta'] = gmdate("Y-m-d h:i:s");
					$resultado = \modelos\usuarios::update($datos['values'], 'usuarios');
					$datos['mensaje'] = "proceso de confirmación completado fecha: {$datos['values']['fecha_confirmacion_alta']}. Ya puedes loquearte";
					
					$datos['url_continuar'] = \core\URL::http("?menu=usuarios&submenu=form_login");
					\core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);
					return;		
				}		
			}	
		}	
	} // Fin de método
	
	
	
	
	
	
	
} // Fin de la clase