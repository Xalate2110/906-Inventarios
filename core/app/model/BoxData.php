<?php
class BoxData {
	public static $tablename = "box";

	public $id;
	public $amount;
	public $amount_final;
	public $stock_id;
	public $user_id;
	public $created_at;
	public $status;
	public $closed_at;



	public function __construct(){
		$this->created_at = "NOW()";
	}

	public function getStock(){ return StockData::getById($this->stock_id); }

	public function add(){
		$sql = "insert into box (stock_id,created_at) ";
		$sql .= "value ($this->stock_id,$this->created_at)";
		return Executor::doit($sql);
	}

	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}


	public function addByDate($fecha){
		$sql = "insert into box (stock_id,created_at) ";
		$sql .= "value ($this->stock_id,'$fecha')";
		return Executor::doit($sql);
	}



// partiendo de que ya tenemos creado un objecto BoxData previamente utilizamos el contexto
	public function update(){
		$sql = "update ".self::$tablename." set name=\"$this->name\" where id=$this->id";
		Executor::doit($sql);
	}

	public function closebox(){
		$sql = "update ".self::$tablename." set amount_final=\"$this->amount_final\",closed_at=now(),status=1 where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new BoxData());
	}

	public static function getLastOpenByUser($id){
		$sql = "select * from ".self::$tablename." where user_id=$id and status=0";
		$query = Executor::doit($sql);
		return Model::one($query[0],new BoxData());
	}


	public static function getAll($sql2,$suc){
		$sql = "select * from ".self::$tablename." where date(created_at) between '$_GET[start_at]' and '$_GET[finish_at]' and stock_id = $suc";
	
		$query = Executor::doit($sql);
		return Model::many($query[0],new BoxData());
	}

	public static function getAllByUserId($id){
		$sql = "select * from ".self::$tablename." where user_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new BoxData());
	}

	public static function getLike($q){
		$sql = "select * from ".self::$tablename." where name like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new BoxData());
	}


}

?>