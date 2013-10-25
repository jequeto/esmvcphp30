<div id='libros'>
	<h1>Mis últimos libros leidos</h1>
	<table border='1px'>
		<thead>
			<tr>
				<th>Título</th>
				<th>Autor</th>
				<th>Comentario</th>
			</tr>
		</thead>
		<tbody>
			<?php
			/*
			 for ($i = 0; $i < count($datos['libros']); $i++) {
			 
				echo "<tr>
						<td>{$datos['libros'][$i]['titulo']}</td>
						<td>{$datos['libros'][$i]['autor']}</td>
						<td>{$datos['libros'][$i]['comentario']}</td>
					</tr>";
			}
			*/
			foreach ($datos['libros'] as $libro) {
				echo "<tr>
						<td>{$libro['titulo']}</td>
						<td>{$libro['autor']}</td>
						<td>{$libro['comentario']}</td>
					</tr>";
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan='3'><button onclick='window.location.assign("?menu=libros&submenu=form_anexar");'>anexar un libro</button></td>
			</tr>
		</tfoot>
		
	</table>
</div>