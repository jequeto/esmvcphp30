<?php
namespace core\sgbd;


/**
 * Clase para conectar y operar con Mysql
 */
class mysqli implements \core\sgbd\SQL_interface {
	
	/**
	 * Variable usada para facilitar la ocultación del resultado de setencias de depuración.
	 * @var boolean 
	 */
	private static $depuracion = false;
	
	/**
	 * Resource o Link que guarda la conexión con el SGBD
	 * @var resource|link 
	 */
	public static $conexion;
	
	/**
	 * Prefimo que utilizarán las tablas en la base de datos.
	 * @var string 
	 */
	public static $prefix_ = '';
	
	/**
	 * Nombre de la tabla que se utilizará por defecto cuando no se aporte como parámetro en los métodos que lo requieran.
	 * Esta propiedad deberá definirse en las clases definidas para manipular cada tabla
	 * en app\datos\nombre_tabla.php
	 * <br />Está pensada para cuando se creen las clases específicas para manipular cada tabla de la base de datos.
	 * 
	 * @var string 
	 */
	public static $tabla = '';
	
	/**
	 * Almacena el resultado de la ejecución de la última sentencia SQL ejecutada.
	 * 
	 * @var Resource Elemento específico del SGBD que contiene el resultado (es similar a un CURSOR)
	 */
	protected static $resultado;
	
	
	/**
	 * Prevista para que se puedan instanciar las clases específica que se creen para manipular cada tabla en la carpeta app\datos\nombre_tabla.php
	 * 
	 * @param string $tabla
	 */
	public function __construct($tabla = null) 	{
		
		if ( is_string($tabla) && strlen($tabla))
			self::$tabla = $tabla;
		//exit(__METHOD__); echo self::$tabla; 
		
	}
	

	
	public static function connect() {
		self::$conexion = mysqli_connect(\core\Configuracion::$mysql['server'], \core\Configuracion::$mysql['user'], \core\Configuracion::$mysql['password'],\core\Configuracion::$mysql['dbname']);
		if ( ! self::$conexion)
		{
			throw new \Exception(__METHOD__.' Mysql: Could not connect: ' );
		}
		
		self::$prefix_ = \core\Configuracion::$mysql['prefix_'];
		
		return self::$conexion;
		
	}
	
	
	public static function conectar() {
		
		return self::connect();
		
	}
	
	
	
	
	public static function disconnect() {
		
		return mysqli_close(self::$conexion);
		
	}
	
	
	public static function desconectar() {
		
		return self::disconnect();
		
	}
	
	
	
	
	public static function get_prefix_tabla($tabla = null) {
		
		if ( ! $tabla ) {
			if ( ! self::$tabla ) {
				throw new \Exception(__METHOD__. " -> No está definido el nombre de la tabla de la base de datos.");
			}
			else {
				$tabla = self::$tabla;
			}
		}
		else {
			self::$tabla = $tabla;
		}

		
		return self::$prefix_.$tabla;
	}

	
	/**
	 * Ejecuta la consulta SQL que se pasa en el parámetro $consulta.
	 * Se ejecuta sobre la conexión iniciada con el SGBD.
	 * Devuelve false si fallo, true si éxito para consultas que no devuelven filas, y array conteniendo un array por cada fila para consultas que devuelven filas.
	 * 
	 * @param string $consulta Cadena con la consulta SQL
	 * @return boolean|resource
	 */
	public static function ejecutar_consulta($sql) {
		
		if (self::$depuracion) {echo __METHOD__." \$sql = $sql <br />";}
		
		self::$resultado = mysqli_query(self::$conexion,$sql,MYSQLI_USE_RESULT);
		if ( self::$resultado === false)
			throw new \Exception(__METHOD__." Consulta= $sql <br />Error = ".  mysqli_error(self::$conexion));
		elseif (is_resource (self::$resultado)) {
			return self::recuperar_filas();
		}
		else return self::$resultado;
		
	}
	
	
	
	public static function execute($consulta) {
		
		return self::ejecutar_consulta($consulta);
		
	}


	
	public static function get_rows($consulta = null) {
		
		return self::recuperar_filas($consulta);
		
	}


	
	
	public static function recuperar_filas($sql = null) {
		
		if ($sql)
			self::ejecutar_consulta($sql);
		
		$filas = array();
		
		while ($fila = mysqli_fetch_assoc(self::$resultado))
			array_push($filas,$fila);
		
		mysqli_free_result(self::$resultado);
		
		return $filas;
	}


	
	public static function columnas_set(array $fila) {
		
		$columnas_set=" ";
		$i=0;
		foreach ($fila as $key => $value) {
			if ($value == '' || strlen($value) == 0 )
				$columnas_set .= "$key = default ";
			elseif (is_numeric($value))
				$columnas_set .= "$key = $value ";
			elseif (strtoupper($value) == 'DEFAULT')
				$columnas_set .= "$key = $value ";
			elseif (strtoupper($value) == 'NULL'|| $value == null )
				$columnas_set .= "$key = NULL ";
			else // suponemos que es una cadena
				$columnas_set .= "$key = '$value' ";

			if ($i < count($fila)-1)
				$columnas_set .= ", ";
			$i++;
		}
		return $columnas_set;
	}
	
	
	
	public static function insert_row( array $fila , $tabla) {
		
		if (isset($fila['id']))
			throw new \Exception(__METHOD__." Error: no pude insertarse la columna id.");
		
		$columnas_set = self::columnas_set($fila);
		
		$sql = "insert into	".self::get_prefix_tabla($tabla)."
			set $columnas_set
		;
		";
		
		return self::ejecutar_consulta($sql);
	}
	
	
	/**
	 * Inserta la fila cuyos datos están contenidos en las entradas del array $fila. Los datos que no se aporten deberán poderse sustituir por null o valores por defecto en la tabla.
	 * 
	 * @param array $fila Array asociativo con las columnas de la fila a modifiar.
	 * @param string $tabla Tabla en la que insertar
	 * @return boolean True si éxito, false si error de sintáxis.
	 */
	public static function insertar_fila(array $fila, $tabla) {
	
		return self::insert_row($fila, $tabla);
		
	}
	
	
	
	
	public static function insert(array $fila, $tabla) {
	
		return self::insert_row($fila, $tabla);
		
	}
	
	
	
	public static function insertar(array $fila, $tabla) {
	
		return self::insert($fila, $tabla);
		
	}
	
	
		
	public static function update_row(array $fila , $tabla) {
		
		if ( ! isset($fila['id']))
			throw new \Exception(__METHOD__." Error: debe aportarse la id.");
		
		$columnas_set = self::columnas_set($fila);
		
		$sql = "
			update	".self::get_prefix_tabla($tabla)."
			set $columnas_set
			where id = {$fila['id']}
		;
		";
		
		return self::ejecutar_consulta($sql);
	}
	
	
	
	/**
	 * Modifica la fila cuyo id está contenido en $fila['id'] con los valores contenidos en el resto de entradas de $fila
	 * 
	 * @param array $fila Array asociativo con las columnas de la fila a modifiar.
	 * @return boolean True si éxito, false si error de sintáxis.
	 */
	public static function modifica_fila(array $fila, $tabla) {
	
		return self::update_row($fila, $tabla);
		
	}
	
	
	
	
	public static function modificar_fila(array $fila, $tabla) {
	
		return $this->update_row($fila, $tabla);
		
	}
	
	
	
	public static function delete_row(array $fila, $tabla = null) {
		
		if ( ! isset($fila['id']))
			throw new \Exception(__METHOD__." Error: debe aportarse la id.");
		
		$sql = "
			delete
			from ".self::get_prefix_tabla($tabla)."
			where id = {$fila['id']}
			;
		";
		
		return self::ejecutar_consulta($sql);
	}
	
	
	
	
	/**
	 * Borrar la fila de la tabla cuyo id es el valor de la entrada $fila['id']. Si la entrada no se aporta o no tiene valor se genera una execpción.
	 * 
	 * @param array $fila
	 * @param string $tabla
	 * @return boolean True si éxito en ejecución, False si error de sintáxis.
	 */
	public static function borrar_fila(array $fila, $tabla) {
	
		return self::delete_row($fila, $tabla);
		
	}
	
	
	
	
	
	
	
	
	public static function delete( $clausulas = array(), $tabla = null) {
		
		if (is_string($clausulas) and is_array($tabla)) {
			// Vienen cambiados y los intercambiamos
			$columnas_aux = $tabla;
			$tabla = $columnas;
			$columnas = $columnas_aux;
		}
		
		$where = ( isset($clausulas['where']) ? " where ".$clausulas['where'] : "");
		$order_by = ( isset($clausulas['order_by']) ? " order by ".$clausulas['order_by'] : "");
		
		$sql = "
			delete from ".self::get_prefix_tabla($tabla)."
				$where
				$order_by
			;
		";
		
		return self::ejecutar_consulta($sql);
		
	}
	
	
	
	
	
	
	/**
	 * Borra filas que cumplan las condiciones pasadas en la entrada where del array $clausulas.
	 * ¡Si no se pasa la entrada $clausualas['where'] o si es una cadena vacía, se borra toda la tabla!
	 * 
	 * @param array $clausulas
	 * @param string $tabla
	 * @return boolean 
	 */
	public static function borrar(
			array $clausulas = array(	
				'where' => '',
				'order_by' => '',
			),
			$tabla = null
	) {
		
		return self::delete($clausulas, $tabla);
		
	}
	
	
	
	
	
	
	
	public static function select(
			 $clausulas = array(
				'columnas' => '',
				'where' => '',
				'group_by' => '',
				'having' => '',
				'order_by' => ''
			),
			$tabla = null
	) {
		
		if (is_string($clausulas) and is_array($tabla)) {
			// Vienen cambiados y los intercambiamos
			$columnas_aux = $tabla;
			$tabla = $columnas;
			$columnas = $columnas_aux;
		}
		
		$columnas = ((isset($clausulas['columnas']) and strlen($clausulas['columnas'])) ? $clausulas['columnas'] : '*');
		$where = ((isset($clausulas['where']) and strlen($clausulas['where'])) ? "where ".$clausulas['where'] : '');
		$order_by = ((isset($clausulas['order_by']) and strlen($clausulas['order_by'])) ? "order by ".$clausulas['order_by'] : '');	
		$group_by = ((isset($clausulas['group_by']) and strlen($clausulas['group_by'])) ? "group by ".$clausulas['group_by'] : '');
		$having = ((isset($clausulas['having']) and strlen($clausulas['having'])) ? "having ".$clausulas['having'] : '');
		
		$sql = "
				select $columnas
				from ".self::get_prefix_tabla($tabla)."
				$where
				$group_by
				$having
				$order_by
				;
		";
		
		return self::recuperar_filas($sql);
		
	}
	
	
	
	
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
	 * @return fasle|array array()|array(0=>array('col1'=>val1, 'col2'=>val2, ...), ...) Devuleve false si hubo un error de ejecución de la consulta. Devuelve array vacío si no hay resultado. 
	 */
	public static function recuperar(
			$clausulas = array(
				'columnas' => '',
				'where' => '',
				'group_by' => '',
				'having' => '',
				'order_by' => '',
			),
			$tabla = null
	) {
		
		return $this->select($clausulas, $tabla);
		
	}
	
	
	
	public static function last_insert_id() {
		
		$sql = " select last_insert_id() as id;";
		
		$filas = self::recuperar_filas($sql);
		
		return $filas[0]['id'];
		
	}
	
	
} // Fin de la clase