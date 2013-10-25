<h2>Introduce los datos del nuevo libro</h2>
<form id='form_anexar' method='post' action='?menu=libros&submenu=form_anexar_validar'>
	Título: <input type='text' id='titulo' name='titulo' maxsize='50'/><br />
	Autor: <input type='text' id='autor' name='autor' maxsize='50'/><br />
	Comentario: <input type='text'  id='comentario' name='comentario' maxsize='50'/><br />	
	<input type='submit' value='enviar' />
	<input type='button' value='cancelar' onclick='window.location.assign("?menu=libros");'/>
</form>