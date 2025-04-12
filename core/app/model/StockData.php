<?php
class StockData {
	public static $tablename = "stock";
	public $id;
	public $image;
	public $name;
	public $code;
	public $field1;
	public $field2;
	public $address;
	public $phone;
	public $email;
	public $is_principal;

	public function __construct(){
		$this->name = "";
		$this->image = "";
	}

	public function add(){
		$sql = "insert into stock (image,code,field1,field2,name,address,phone,email,cp,ciudad,colonia) ";
		$sql .= "value (\"$this->image\",\"$this->code\",\"$this->field1\",\"$this->field2\",\"$this->name\",\"$this->address\",\"$this->phone\",\"$this->email\",\"$this->cp\",\"$this->ciudad\",\"$this->colonia\")";
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

   public function update_image(){
	$sql = "update ".self::$tablename." set image=\"$this->image\" where id=$this->id";
	Executor::doit($sql);
}


// partiendo de que ya tenemos creado un objecto StockData previamente utilizamos el contexto
	public function update(){
		 $sql = "update ".self::$tablename." set image=\"$this->image\",code=\"$this->code\",field1=\"$this->field1\",field2=\"$this->field2\",name=\"$this->name\",address=\"$this->address\",phone=\"$this->phone\",email=\"$this->email\",cp=\"$this->cp\",ciudad=\"$this->ciudad\",colonia=\"$this->colonia\"   where id=$this->id";
		
		Executor::doit($sql);
	}


	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new StockData());
	}

	public static function getPrincipal(){
			
		if(Core::$user->kind==2 || Core::$user->kind==3|| Core::$user->kind==4){
			$sql = "select * from ".self::$tablename." where id=".Core::$user->stock_id;
			$query = Executor::doit($sql);
			return Model::one($query[0],new StockData());

		}else{
			$sql = "select * from ".self::$tablename." where is_principal=1";
			$query = Executor::doit($sql);
			return Model::one($query[0],new StockData());
		}
	}


	public static function getAll(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new StockData());
	}

	public static function getAll2($id_almacen){
		$sql = "select * from ".self::$tablename." where id = $id_almacen";
		$query = Executor::doit($sql);
		return Model::many($query[0],new StockData());
	}
	


	public static function getLike($q){
		$sql = "select * from ".self::$tablename." where name like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new StockData());
	}


}

?>