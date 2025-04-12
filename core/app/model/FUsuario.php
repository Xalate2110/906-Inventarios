<?php
class FUsuario {

	public static $tablename = "user";
	
	
	public static function getUsuario(){
		$sql = "select * from ".self::$tablename." where kind in (1,2,3,4)";
		$query = Executor::doit($sql);
		return Model::many($query[0],new FUsuario());
	}





}

?>