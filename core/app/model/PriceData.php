<?php
class PriceData {
	public static $tablename = "price";

	public $id;
	public $price_out;
	public $price_out2;
	public $price_out3;
	public $product_id;
	public $stock_id;



	public function __construct(){

	}

	public function add(){

		/*$sql ="update price set price_out=\"$this->price_out\",price_out2=\"$this->price_out2\",price_out3=\"$this->price_out3\",price_out4=\"$this->price_out4\"  where product_id = \"$this->product_id\" and stock_id = \"$this->stock_id\"";*/
    
	    $sql = "insert into price (price_out,price_out2,price_out3,price_out4,product_id,stock_id) ";
		$sql .= "value (\"$this->price_out\",\"$this->price_out2\",\"$this->price_out3\",\"$this->price_out4\",\"$this->product_id\",\"$this->stock_id\")"; 
		echo $sql;
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


// partiendo de que ya tenemos creado un objecto PriceData previamente utilizamos el contexto
	public function update(){
		$sql = "update ".self::$tablename." set price=\"$this->price\" where id=$this->id";
		Executor::doit($sql);
	}


	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PriceData());
	}

	public static function getByPS($product,$stock){
		$sql = "select * from ".self::$tablename." where product_id=$product and stock_id=$stock";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PriceData());
	}


	public static function getAll(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new PriceData());
	}


	public static function getLike($q){
		$sql = "select * from ".self::$tablename." where name like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PriceData());
	}


}

?>