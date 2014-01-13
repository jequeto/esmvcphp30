<div>
	<!-- formulario  post_reques_form utilizado para enviar peticiones por post al servidor y evitar que el usuario modifique/juegue con los parámetros modificando la URI mostrada  -->
	<form id="post_request_form"
		  action="<?php echo \core\HTML_Tag::form_registrar("form_request_form", "post"); ?>"  
		method="post"
		style="display: none;"
	>
		<input name="id" id="id" type="hidden" />
	
	</form>
	<h1>Listado de usuarios</h1>
	<table border='1'>
		<thead>
			<tr>
				<th>login</th>
				<th>email</th>
				<th>acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($datos['filas'] as $fila)
			{
				echo "
					<tr>
						<td>{$fila['login']}</td>
						<td>{$fila['email']}</td>
						<td>
							<a class='boton' onclick='submit_post_request_form(\"".\core\URL::generar("usuarios/form_modificar")."\", \"/{$fila['id']}\");' >modificar</a>
							<a class='boton' onclick='submit_post_request_form(\"".\core\URL::generar("usuarios/form_borrar")."\", \"/{$fila['id']}\");' >borrar</a>
						</td>
					</tr>
					";
			}
			echo "
				<tr>
					<td colspan='2'></td>
						<td><a class='boton' href='".\core\URL::generar("usuarios/form_insertar_interno")."' >insertar</a></td>
				</tr>
			";
			?>
		</tbody>
	</table>
</div>