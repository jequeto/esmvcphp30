<form name='form_login' method='post' action='<?php echo \core\URL::generar("usuarios/form_login_validar"); ?>'>


	Login: <input type='text' id='login' name='login' maxsize='50' value='<?php echo \core\Datos::values('login', $datos) ?>'/><span class='requerido'>Requerido</span><?php echo \core\HTML_Tag::span_error('login', $datos); ?><br />
	Password: <input type='password' id='password' name='password' maxsize='50' value='<?php echo \core\Datos::values('password', $datos) ?>'/><span class='requerido'>Requerido</span><?php echo \core\HTML_Tag::span_error('password', $datos); ?><br />
	<br />	
	
	<?php echo \core\HTML_Tag::span_error('validacion', $datos);?><br />
	
	<input type='submit' value='enviar' />
	<input type='button' value='cancelar' onclick='window.location.assign("<?php echo \core\URL::generar("inicio"); ?>");'/>
</form>