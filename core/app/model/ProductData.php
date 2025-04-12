<?php
class ProductData {
	public static $tablename = "product";
	public static $tablename2 = "brand";

	public $id;
	public $name;
	public $code;
	public $barcode;
	public $image;
	public $description;
	public $inventary_min;
	public $brand_id;
	public $category_id;
	public $width;
	public $height;
	public $weight;
	public $price_in;
	public $price_out;
	public $price_out2;
	public $price_out3;
	public $price_out4;
	public $unit;
	public $user_id;
	public $presentation;
	public $kind;
	public $expire_at;
	public $created_at;
	public $cx;
    public $is_active;	
	public $ObjetoImp;
	public $codigo_sat;

	public function __construct(){
		$this->name = "";
		$this->price_in = "";
		$this->price_out = "";
		$this->unit = "";
		$this->user_id = "";
		$this->image = "";
		$this->presentation = "0";
		$this->created_at = "NOW()";
	}

	public function getCategory(){ return CategoryData::getById($this->category_id);}

	public function add() {
        $sql = "insert into " . self::$tablename . " (image,kind,code,brand_id,width,height,weight,barcode,name,description,price_in,price_out,price_out2,price_out3,price_out4,user_id,presentation,unit,codigo_sat,ObjetoImp,category_id,inventary_min,expire_at,created_at,multiplo) ";
        $sql .= "value (\"$this->image\",\"$this->kind\",\"$this->code\",$this->brand_id,\"$this->width\",\"$this->height\",\"$this->weight\",\"$this->barcode\",\"$this->name\",\"$this->description\",\"$this->price_in\",\"$this->price_out\",\"$this->price_out2\",\"$this->price_out3\",\"$this->price_out4\",$this->user_id,\"$this->presentation\",\"$this->unit\",\"$this->codigo_sat\",\"$this->objetoimp\",$this->category_id,$this->inventary_min,\"$this->expire_at\",NOW(),\"$this->multiplo\")";
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


    // partiendo de que ya tenemos creado un objecto ProductData previamente utilizamos el contexto
    public function update() {
	$sql = "update " . self::$tablename . " set is_active=\"$this->is_active\",barcode=\"$this->barcode\",name=\"$this->name\",price_in=\"$this->price_in\",price_out=\"$this->price_out\",price_out2=\"$this->price_out2\",price_out3=\"$this->price_out3\",price_out4=\"$this->price_out4\",unit=\"$this->unit\",presentation=\"$this->presentation\",category_id=\"$this->category_id\",inventary_min=\"$this->inventary_min\",description=\"$this->description\",is_active=\"$this->is_active\",expire_at=\"$this->expire_at\",code=\"$this->code\",width=\"$this->width\",height=\"$this->height\",weight=\"$this->weight\",brand_id=\"$this->brand_id\",codigo_sat=\"$this->codigo_sat\",ObjetoImp=\"$this->objetoimp\",multiplo=\"$this->multiplo\" where id=$this->id";

	Executor::doit($sql);
    }



	public function del_category(){
		$sql = "update ".self::$tablename." set category_id=NULL where id=$this->id";
		Executor::doit($sql);
	}

	public function del_brand(){
		$sql = "update ".self::$tablename." set brand_id=NULL where id=$this->id";
		Executor::doit($sql);
	}


	public function update_image(){
		$sql = "update ".self::$tablename." set image=\"$this->image\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_prices(){
		$sql = "update ".self::$tablename." set price_in=\"$this->price_in\",price_out=\"$this->price_out\" where id=$this->id";
		Executor::doit($sql);
	}

	public static function getCount(){
		 $sql = "select count(*) as cx from ".self::$tablename;
		 
		$query = Executor::doit($sql);
		return Model::one($query[0],new ProductData());

	}


	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ProductData());

	}

	public static function getByBarcode($id){
		$sql = "select * from ".self::$tablename." where barcode=\"$id\" or code=\"$id\"";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ProductData());

	}



	public static function getAllByPage($offset,$limit){
		$sql = "select * from ".self::$tablename." limit $offset,$limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getAll(){
		$sql = "select id,code, name,barcode, price_out from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getAll2(){
		$sql = "SELECT p.id,p.code,p.name,p.price_out,p.brand_id,b.name as marca FROM product AS p 
		LEFT JOIN brand as b on p.brand_id = b.id limit 10";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}



 

	public static function getAllBySQL($sql){
		// $sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}


	public static function getAllByCategoryId($id){
		$sql = "select * from ".self::$tablename." where category_id=$id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}




	public static function getLike($p){
		$sql = "select * from ".self::$tablename." where (code like '%$p%' or barcode like '%$p%' or name like '%$p%' or id like '%$p%' or description like '%$p%') and is_active=1 LIMIT 25";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getLikeTraspaso($p){
		$sql = "select * from ".self::$tablename." where (code like '%$p%' or barcode like '%$p%' or name like '%$p%' or id like '%$p%' or description like '%$p%') and is_active=1 and id = $p LIMIT 25";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}


	public static function getLikeExistencias($p){ // aqui puse la condición de id 
		$sql = "select * from ".self::$tablename." where (code like '%$p%' or barcode like '%$p%' or name like '%$p%' or id like '%$p%' or description like '%$p%') and is_active=1 and id = $p LIMIT 25";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}


	public static function getLikeCat($p,$cat){
		$sql = "select * from ".self::$tablename." where (code like '%$p%' or barcode like '%$p%' or name like '%$p%' or id like '%$p%') and category_id=$cat and is_active=1";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getLike2($p){ // aqui puse la condición de id 
		$sql = "select * from ".self::$tablename." where (code like '%$p%' or barcode like '%$p%' or name like '%$p%' or id like '%$p%') and kind=1 and is_active=1 and id = $p LIMIT 25";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}


	public static function getAllByUserId($user_id){
		$sql = "select * from ".self::$tablename." where user_id=$user_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getLikeStartLimit($p, $start, $limit){
		$sql = "select * from ".self::$tablename." where (code like '%$p%' or barcode like '%$p%' or name like '%$p%' or id like '%$p%') and is_active=1 limit $start, $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}


	public static function getProducts(){
		$sql = "select * from ".self::$tablename. " where kind=1 and is_active = 1";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getProducts2(){
		$sql = "select * from ".self::$tablename. " where kind=1 and is_active = 0";
		
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getServices(){
		$sql = "select * from ".self::$tablename." where kind=2";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}


	public static function getProductsStartLimit($start, $limit){
		$sql = "select * from ".self::$tablename." where kind=1  limit $start, $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getProductsStartLimit2($start, $limit){
		$sql = "select * from ".self::$tablename." where kind=1 and is_active = 0 limit $start, $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}


	public static function getServicesStartLimit($start, $limit){
		$sql = "select * from ".self::$tablename." where kind=2  limit $start, $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}


	public static function getProductsLikeStartLimit($p, $start, $limit){
		$sql = "select * from ".self::$tablename." where kind=1 and (code like '%$p%' or barcode like '%$p%' or name like '%$p%' or id like '%$p%' or description like '%$p%') and is_active=1 limit $start, $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getServicesLikeStartLimit($p, $start, $limit){
		$sql = "select * from ".self::$tablename." where kind=2 and (code like '%$p%' or barcode like '%$p%' or name like '%$p%' or id like '%$p%') and is_active=1 limit $start, $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

}

?>