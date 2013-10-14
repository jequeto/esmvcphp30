<?php
namespace core\sgbd;


/**
 * Esta clase a se debe extender por el programador para construir la clase que intercambie datos con el SGBD elegido (mysql, db2, oracle, etc).
 */
 interface SQL_interface  {
	 
	/**
	 * Propiedades estáticas a definir en la clase que se implemente a partir de este interface
	 */
	/**
	 * Variable usada para facilitar la ocultación del resultado de setencias de depuración.
	 * @var boolean 
	 */
	// private static $depuracion = false;
	
	/**
	 * Resource o Link que guarda la conexión con el SGBD
	 * @var resource|link 
	 */
	// public static $conexion;
	
	/**
	 * Prefijo que utilizarán las tablas en la base de datos.
	 * @var string 
	 */
	// public static $prefix_ = '';
	 
	 
	/**
	 * Inicia una conexión con el servidor de bases de datos, cuyos parámetros de configuración están en \core\configuracion.php
	 * <br />
	 * También debe cargar el la propiedad self::$prefix_ el valor del prefijo usado para los elementos guardados en la base de datos.
	 * 
	 * @return false|resource Devuelve false si fallo y un objeto si éxito. El retorno deverá quedar guardado en la propiedad self::$connexion
	 */
	public static function connect() ;
	
	
	/**
	 * Cierra la conexión con el SGBD.
	 * 
	 * @return boolean Devuelve false si fallo y true si éxito.
	 */
	public static function disconnect() ;
	
	
	/**
	 * Ejecuta la consulta SQL que se pasa en el parámetro $consulta.
	 * Se ejecuta sobre la conexión iniciada con el SGBD.
	 * Devuelve false si fallo, true si éxito para consultas que no devuelven filas, y array conteniendo un array por cada fila para consultas que devuelven filas.
	 * 
	 * @param string $consulta Cadena con la consulta SQL
	 * @return boolean|resource
	 */
	 public static function execute($consulta) ;
	
	
	/**
	 * Ejecuta la consulta que se pase como parámetro o si no se pasa, se supone que se ha ejecutado la consulta previamente.
	 * Recupera el resultado de la ejecución de la consulta de self::$resultado y a partír de ahí obtiene un array de índice entero, conteniendo en cada entrada otro array asociativo con los datos de cada una fila de las filas recuperadas por la ejecución de la consulta.
	 * Es solo válido para consultas que devuelvan filas.
	 * 
	 * @param string $consulta Cadena con la consulta SQL
	 * @return fasle|array array()|array(0=>array('col1'=>val1, 'col2'=>val2, ...), 1=>array('col1'=>val1, 'col2'=>val2, ...), ...) Devuleve false si hubo un error de ejecución de la consulta. Devuelve array vacío si no hay resultado.
	 */
	 public static function get_rows($consulta = null) ;


	 /**
	 * Recupera filas de $tabla.
	 * Si $clausulas['where'] no se aporta o es una cadena vacía se recuperan todas las filas.
	 * <br />Si $clausulas['columnas'] no se aporta o es una cadena vacía se recuperan todas las columnas.
	 * <br />Si $tabla no se aporta se toma self::$tabla.
	 * 
	 * @param array $clausulas array(
				'columnas' => '',
				'where' => '',
				'group_by' => '',
				'having' => '',
				'order_by' => ''
			)
	 * @param string $tabla Si no se aporta se usa el valor de self::$tabla
	 * @return fasle|array array()|array(0=>array('col1'=>val1, 'col2'=>val2, ...), 1=>array('col1'=>val1, 'col2'=>val2, ...), ...) Devuleve false si hubo un error de ejecución de la consulta. Devuelve array vacío si no hay resultado. 
	 */
	public static function select(
			$clausulas = array(
				'columnas' => '',
				'where' => '',
				'group_by' => '',
				'having' => '',
				'order_by' => ''
			),
			$tabla = null
	) ;
	
	/**
	 * Devuelve el nombre de la tabla precedido del prefijo si lo hubiera.
	 * <br /> Si tabla no se aporta y self::$tabla no contiene una cadena no vacía, devuelve una execpción.
	 * 
	 * @param string $tabla
	 */
	public static function get_prefix_tabla($tabla = null) ;

	
	
	/**
	 * Inserta la fila cuyos datos están contenidos en las entradas del array $fila. Los datos que no se aporten deberán poderse sustituir por null o valores por defecto en la tabla.
	 * 
	 * @param array $fila Array asociativo con las columnas de la fila a modifiar.
	 * @param string $tabla Tabla en la que insertar
	 * @return boolean True si éxito, false si error de sintáxis.
	 */
	 public static function insert_row(array $fila , $tabla) ;
	
	
	
	/**
	 * Inserta la fila cuyos datos están contenidos en las entradas del array $fila. Los datos que no se aporten deberán poderse sustituir por null o valores por defecto en la tabla.
	 * 
	 * @param array $fila Array asociativo con las columnas de la fila a modifiar.
	 * @param string $tabla Tabla en la que insertar
	 * @return boolean True si éxito, false si error de sintáxis.
	 */
	public static function insert(array $fila, $tabla) ;
	
	
	/**
	 * Modifica la fila cuyo id está contenido en $fila['id'] con los valores contenidos en el resto de entradas de $fila
	 * 
	 * @param array $fila Array asociativo con las columnas de la fila a modifiar.
	 * @return boolean True si éxito, false si error de sintáxis.
	 */		
	 public static function update_row(array $fila , $tabla) ;
	
	
	/**
	 * Borrar la fila de la tabla cuyo id es el valor de la entrada $fila['id']. Si la entrada no se aporta o no tiene valor se genera una execpción.
	 * 
	 * @param array $fila Array asociativo con la entrada id
	 * @param string $tabla Nombre de la tabla
	 * @return boolean True si éxito en ejecución, False si error de sintáxis.
	 */
	public static function delete_row(array $fila, $tabla = null) ;
	
	
	/**
	 * Borra filas que cumplan las condiciones pasadas en la entrada where del array $clausulas.
	 * ¡Si no se pasa la entrada $clausulas['where'] o si es una cadena vacía, se borra toda la tabla!
	 * 
	 * @param array $clausulas Ejemplo de contenido: array(	
				'where' => ' col2 = val1 and col2 = val2 ',
				'order_by' => ' col1, col2, ...')
	 * @param string $tabla Nombre de la tabla. Si no aporta se usa la tabla definida en self::$tabla
	 * @return boolean True si éxito False si fallo de sintáxis.
	 */
	 public static function delete(
			$clausulas = array(	
				'where' => '',
				'order_by' => '',
			),
			$tabla = null
	) ;

	
	
	/**
	 * Devuelve el último valor generado por una cláusula auto increment.
	 * 
	 * @return integer Devuelve el último valor generado por una cláusula auto_increment
	 */
	 public static function last_insert_id()  ;
	
} // Fin de la clase