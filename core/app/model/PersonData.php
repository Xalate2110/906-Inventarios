<?php
class PersonData {
	public static $tablename = "person";
	public $id;
	public $name;
	public $price;
	public $code;
	public $no;
	public $lastname;
	public $address1;
	public $phone1;
	public $email1;
	public $is_active_access;
	public $password;
	public $kind;
	public $credit_limit;
	public $has_credit;
	public $created_at;

	public $image;
	public $company;
	public $address2;
	public $phone2;
	public $email2;


	public function __construct(){
		$this->name = "";
		$this->lastname = "";
		$this->email1 = "";
		$this->password = "";
		$this->created_at = "NOW()";
		$this->credit_limit = "NULL";
	}

    public function add_client() {
        $sql = "insert into person (price,no,name,lastname,address1,email1,phone1,phone2,phone3,codigopostal,is_active_access,password,kind,credit_limit,has_credit,created_at,forma_pago,uso_comprobante,regimen_fiscal,tiene_rs,encargado,stock_id) ";
        $sql .= "value ($this->price_id,\"$this->no\",\"$this->name\",\"$this->lastname\",\"$this->address1\",\"$this->email1\",\"$this->phone1\",\"$this->phone2\",\"$this->phone3\",\"$this->codigopostal\",\"$this->is_active_access\",\"$this->password\",1,\"$this->credit_limit\",$this->has_credit,$this->created_at,\"$this->forma_pago\",\"$this->uso_comprobante\",\"$this->regimen_fiscal\",\"$this->tiene_rs\",\"$this->encargado\",\"$this->stock_id\")";
		Executor::doit($sql);
    }

	public function add_provider(){
		$sql = "insert into person (no,name,lastname,address1,email1,phone1,phone2,kind,created_at,credit_limit,has_credit,vendedor) ";
		$sql .= "value (\"$this->no\",\"$this->name\",\"$this->lastname\",\"$this->address1\",\"$this->email1\",\"$this->phone1\",\"$this->phone2\",2,$this->created_at,\"$this->credit_limit\",$this->has_credit,\"$this->vendedor\")";
		echo $sql;
		Executor::doit($sql);
	}


	public function add_contact(){
		$sql = "insert into person (name,lastname,address1,email1,phone1,kind,created_at) ";
		$sql .= "value (\"$this->name\",\"$this->lastname\",\"$this->address1\",\"$this->email1\",\"$this->phone1\",3,$this->created_at)";
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

// partiendo de que ya tenemos creado un objecto PersonData previamente utilizamos el contexto
	public function update(){
		$sql = "update ".self::$tablename." set name=\"$this->name\",email1=\"$this->email1\",address1=\"$this->address1\",lastname=\"$this->lastname\",phone1=\"$this->phone1\" where id=$this->id";
		Executor::doit($sql);
	}

    public function update_client() {
        $sql = "update " . self::$tablename . " set price=\"$this->price_id\",no=\"$this->no\",name=\"$this->name\",email1=\"$this->email1\",address1=\"$this->address1\",lastname=\"$this->lastname\",phone1=\"$this->phone1\",phone2=\"$this->phone2\",phone3=\"$this->phone3\",codigopostal=\"$this->codigopostal\",is_active_access=\"$this->is_active_access\",password=\"$this->password\",has_credit=\"$this->has_credit\",credit_limit=\"$this->credit_limit\",forma_pago=\"$this->forma_pago\",uso_comprobante=\"$this->uso_comprobante\",regimen_fiscal=\"$this->regimen_fiscal\",tiene_rs=\"$this->tiene_rs\",encargado=\"$this->encargado\",stock_id=\"$this->stock_id\" where id=$this->id";
		Executor::doit($sql);
    }

	public function update_provider(){
		$sql = "update ".self::$tablename." set no=\"$this->no\",name=\"$this->name\",vendedor=\"$this->vendedor\",email1=\"$this->email1\",address1=\"$this->address1\",lastname=\"$this->lastname\",phone1=\"$this->phone1\",phone2=\"$this->phone2\",has_credit=\"$this->has_credit\",credit_limit=\"$this->credit_limit\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_contact(){
		$sql = "update ".self::$tablename." set name=\"$this->name\",email1=\"$this->email1\",address1=\"$this->address1\",lastname=\"$this->lastname\",phone1=\"$this->phone1\" where id=$this->id";
		Executor::doit($sql);
	}


	public function update_passwd(){
		$sql = "update ".self::$tablename." set password=\"$this->password\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_credit_limit(){
		$sql = "update ".self::$tablename." set credit_limit=\"$this->credit_limit\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_has_credit(){
		$sql = "update ".self::$tablename." set has_credit=\"$this->has_credit\" where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PersonData());
	}



	public static function getAll(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());

	}

	public static function getClients($stock_id){
		$sql = "select * from ".self::$tablename." where kind=1 and stock_id = $stock_id order by name,lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}

	public static function getClients2() {
        $sql = "select id, name, lastname from " . self::$tablename . " where kind=1 ";
        $query = Executor::doit($sql);
        return Model::many($query[0], new PersonData());
    }
    

	public static function getClientsWithCredit(){
		$sql = "select * from ".self::$tablename." where kind=1 and has_credit=1 order by name,lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}

	public static function getClientsWithCredit2(){
		$sql = "select * from ".self::$tablename." where kind=2 and has_credit=1 order by name,lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}


	public static function getContacts(){
		$sql = "select * from ".self::$tablename." where kind=3 order by name,lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}

	public static function getProviders(){
		$sql = "select * from ".self::$tablename." where kind=2 order by name,lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());

	}

	public static function getLike($q,$id_almacen){
		$sql = "select * from ".self::$tablename." where name like '%$q%' and stock_id = $id_almacen";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());

	}


}

?>