<?php
namespace core\sgbd;

class db2 extends \core\sgbd\SQL_interface {
	
	
	public static function delete(array $datos, $tabla) {
		
				
		$where = " where 1 ";
		
		if ( ! count($datos['values'])) {
			return true;
		}
		else {
			//Hay entradas en $datos['values']
			foreach ($datos['values'] as $key => $value) {
				$where .= " and $key = ".(is_numeric($value) ? $value : "'$value'");
			}
			
		}
				
		$sql = "
			delete from $tabla
			$where

		";
		
		return self::ejecutar_consulta($sql);
			
	}
	
	
	public static function ejecutar_consulta($sql) {
		
		
		return db2_exec($sql);
		
	}
	
	
	
	
}

