<?php
class RazonData {
	public static $tablename = "razones_sociales";
	public $id;
	public $name;
	public $image;
	public $code;
	public $field1;
	public $field2;
	public $address;
	public $phone;
	public $email;
	public $is_principal;




	public function __construct(){
		$this->name = "";
	}

	public function add(){
		$sql = "insert into razones_sociales (image,razonsocial,rfc,codigo_postal,regimen_fiscal,ciudad,colonia,direccion,serie_facturacion)";
		$sql .= "value (\"$this->image\",\"$this->name\",\"$this->rfc\",\"$this->cp\",\"$this->regimen_fiscal\",\"$this->ciudad\",\"$this->colonia\",\"$this->address\",\"$this->sf\")";
		Executor::doit($sql);
	}

	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}

		public static function unset_principal(){
		$sql = "update ".self::$tablename." set is_principal=0";
		Executor::doit($sql);
	}
		public static function set_principal($id){
		$sql = "update ".self::$tablename." set is_principal=1 where id=$id";
		Executor::doit($sql);
	}


		// partiendo de que ya tenemos creado un objecto StockData previamente utilizamos el contexto
		public function update_razon(){
			$sql = "update ".self::$tablename." set  image=\"$this->image\",razonsocial=\"$this->name\",direccion=\"$this->address\",rfc=\"$this->rfc\",codigo_postal=\"$this->cp\",ciudad=\"$this->ciudad\",colonia=\"$this->colonia\",regimen_fiscal=\"$this->regimen_fiscal\",serie_facturacion=\"$this->sf\"   where id=$this->id";
			Executor::doit($sql);
		}

		public function update_image(){
			$sql = "update ".self::$tablename." set image=\"$this->image\" where id=$this->id";
			Executor::doit($sql);
		}



	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new RazonData());
	}

	public static function getPrincipal(){
			
		if(Core::$user->kind==2 || Core::$user->kind==3|| Core::$user->kind==4){
			$sql = "select * from ".self::$tablename." where id=".Core::$user->stock_id;
			$query = Executor::doit($sql);
			return Model::one($query[0],new RazonData());

		}else{
			$sql = "select * from ".self::$tablename." where is_principal=1";
			$query = Executor::doit($sql);
			return Model::one($query[0],new RazonData());
		}
	}


	public static function getAll(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new RazonData());
	}


	public static function getLike($q){
		$sql = "select * from ".self::$tablename." where name like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new RazonData());
	}


}

?>