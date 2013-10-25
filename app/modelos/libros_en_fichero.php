<?php
/*
.../aplicacion/app/modelos/libros.txt
titulo;autor;comentario[enter]
Título A;Autor de A;Comentario de A[enter]
Título B;Autor de B;Comentario de B[enter]
...

*/
namespace modelos;

class Libros_En_Fichero {
	
	private static $libros = array(
		/*	array(
			"titulo" => "cadena",
			"autor" => "cadena",
			"comentario" => "cadena"
		), */
	);

	
	private static function get_nombre_fichero() {
		
		return PATH_APP."modelos/libros.txt";
		
	}
	
	private static function leer_de_fichero() {
		
		$file_path = self::get_nombre_fichero();
		$lineas = file($file_path);
		//print_r($lineas);
		foreach ($lineas as $numero => $linea) {
			// Dividimos la línea por los ;
			// Ponemos cada trozo de línea en un elemento del array $item
			$libro = explode(";", $linea); 
			//print_r($libro);
			
			// Llenamos el array $items
			if ($numero != 0) {
				self::$libros[$numero-1]["titulo"] = $libro[0]; 
				self::$libros[$numero-1]["autor"] = $libro[1];
				self::$libros[$numero-1]["comentario"] = $libro[2];
			}
		}
		
	}
	
	
	
	private static function escribir_en_fichero() {
		
		$file_path = self::get_nombre_fichero();
		$file = fopen($file_path, "w");
		foreach ($libros as $libro) {
			$linea = implode(";", $libro)."\n\l";
			fwrite($file, $linea);
		}
		fclose($file);
		
	}
	
	/**
	 * Inserta un libro al final de fichero de libros
	 * @param array $libro array("titulo" => string, "autor" => string, "comentario" => string)
	 */
	public static function anexar_libro(array $libro) {
		
		/*
		self::leer_de_fichero();
		
		
		array_push(self::$libros, array(
									"titulo" => $libro["titulo"],
									"autor" => $libro["autor"],
									"comentario" => $libro["comentario"]
									)
		);
		
		self::escribir_en_fichero();
		*/
		
		$file_path = self::get_nombre_fichero();
		$file = fopen($file_path, "a+");
		
		$linea = implode(";", $libro)."\n\l";
		fwrite($file, $linea);
		
		fclose($file);
		
	}
	
	
	public static function borrar_libro($id) {
		
		self::leer_de_fichero();
		
		usset(self::$libros[$id]);
		
		self::escribir_en_fichero();
		
	}
	
	
	public static function modificar_libro(array $libro) {
		
		self::leer_de_fichero();
		
		self::$libros[$libro["id"]]["titulo"] = $libro['titulo'];
		self::$libros[$libro["id"]]["autor"] = $libro['autor'];
		self::$libros[$libro["id"]]["comentario"] = $libro['comentario'];
		
		self::escribir_en_fichero();
		
	}
	
	
	
	public static function get_libros() {
		
		self::leer_de_fichero();
		
		return self::$libros;

	}	

}
