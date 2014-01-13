<?php
namespace modelos;

class usuarios extends \core\sgbd\bd {


	/* Rescritura de propiedades de validación */
	private static $validaciones_insert = array(
		'login' => 'errores_requerido && errores_login && errores_unicidad_insertar:login/usuarios/login',
		'email' => 'errores_requerido && errores_email && errores_unicidad_insertar:email/usuarios/email',
		'password' => 'errores_requerido && errores_password',
		"fecha_alta" => "errores_fecha_hora",
		"fecha_confirmacion_alta" => "errores_fecha_hora",
		"clave_confirmacion" => "errores_texto",
	);
	
	
	private static $validaciones_update = array(
		'login' => 'errores_requerido && errores_login && errores_unicidad_modificar:login/usuarios/login',
		'email' => 'errores_requerido && errores_email && errores_unicidad_modificar:email/usuarios/email',
	);
	
	
	
	private static $validaciones_delete = array(
		"id" => "errores_requerido && errores_numero_entero_posetivo && errores_referencia:id/usuarios/id"
	);
	

	
	
	/**
	 * 
	 * @param type $login
	 * @param type $contrasena
	 * @return string  Valores: ''  'existe'  'existe_autenticado' 'existe_autenticado_confirmado'
	 */
	public static function validar_usuario($login, $contrasena) {
		
		$validacion = null;
		$contrasena = md5($contrasena);
		$sql = "
			select id, login, password, fecha_confirmacion_alta
			from ".self::get_prefix_tabla('usuarios')."
			where login = '$login' 
		";
		$filas = self::recuperar_filas($sql);
		
		if (count($filas) == 1) { // Usuario y contraseña correctos
			$validacion = "existe";
			
			if ($filas[0]['password'] == $contrasena) {
				$validacion .= "_autenticado";
				if ($filas[0]['fecha_confirmacion_alta'] != '') {
					$validacion .= "_confirmado";
				}		
			}
		}
		return ($validacion);
	}
	
	
	
	
	public static function validar_usuario_login_email($datos) 	{
		
		if (isset($datos['login']) && strlen($datos['login']))
			$where = "where login = '{$datos['login']}'";
		elseif (isset($datos['email']) && strlen($datos['email']))
			$where = "where email = '{$datos['email']}'";
		else {
			throw new \Exception(__METHOD__." Error: debe aportarse ['login'] o ['email']");
		}
			
		$validacion = "";
		$contrasena = md5($datos['password']);
		$sql = "
			select id, login, password, fecha_confirmacion_alta
			from ".self::get_prefix_tabla('usuarios')."
			$where and password = '$contrasena' 
		";
		$filas = self::recuperar_filas($sql);
		
		if (count($filas) == 1) { // Usuario y contraseña correctos
			$validacion = "existe";
			
			if ($filas[0]['password'] == $contrasena) {
				$validacion .= "_autenticado";
				if ($filas[0]['fecha_confirmacion_alta'] != '') {
					$validacion .= "_confirmado";
				}		
			}
		}
		//echo __METHOD__; var_dump($validacion);
		return ($validacion);
	}
	
	
	
	public static function permisos_usuario($login) {
		
		$consulta = "
			select controlador , metodo
			from ".self::get_prefix_tabla('usuarios_permisos')."
			where login = '$login'
			union
			select controlador , metodo
			from ".self::get_prefix_tabla('roles_permisos')."
			where rol in  (select rol from ".self::get_prefix_tabla('usuarios_roles')." where login='$login')
			order by 1, 2
			;
		";
		
		$filas = self::recuperar_filas($consulta);
		
		$permisos = array();
		
		foreach ($filas as $key => $recurso) {
			$permisos[$recurso['controlador']][$recurso['metodo']] = true;
		}
		
		return $permisos;
		
	}	
	
}