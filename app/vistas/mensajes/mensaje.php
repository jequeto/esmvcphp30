<div id='mensaje'>
<?php
	if ( ! isset($datos['mensaje'])) {
			echo "<p>Mensaje indefinido</p>";
	}
	else {
		echo "<p>{$datos['mensaje']}</p>";
	}

	if (isset($datos['url_continuar']))
		echo "<p><a href='{$datos['url_continuar']}'>Continuar</a></p>";

?>
</div>