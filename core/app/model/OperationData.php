<?php
class OperationData {
	public static $tablename = "operation";
	public static $tablename2 = "entradas_y_devoluciones";
	public static $tablename3 = "sell";

	public function OperationData(){
		$this->name = "";
		$this->product_id = "";
		$this->q = "";
		$this->cut_id = "";
		$this->status = "1";
		$this->operation_type_id = "";
		$this->operation_from_id = "NULL";
		$this->created_at = "NOW()";
		$this->is_traspase=0;
		$this->dxp=0;
	}
	public function addEntrysAndReturns($sell_id,$product_id,$product_code,$tipo,$cantidad){
		$fecha = date("Y") . "-" . date("m") . "-" . date("d")." ". date("H").":". date("m").":". date("s");
		//echo $fechaEntrega;
		
		$sql = "insert into ".self::$tablename2." (sell_id,product_id,product_code,tipo,cantidad,fecha) ";
		$sql .= "value ($sell_id,$product_id,'$product_code','$tipo',$cantidad,'$fecha')";
		//echo $sql;
		return Executor::doit($sql);
	}

	public function getEntrysAndReturnsBysellId($sell_id){
		$sql = "select * from ".self::$tablename2." where sell_id=$sell_id order by product_id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public static function getAllEntrysAndReturnsBysellId($sell_id){
		$sql   = "select sum(cantidad) as totales from ".self::$tablename2." where sell_id=$sell_id order by product_id";
		$query = Executor::doit($sql);
		$found = null;
	   	$data  = new OperationData();
	   	while($r = $query[0]->fetch_array()){
			$data->total  = $r['totales'];
			$found = $data;
			break;
	   	}
	   	return $found;
	}

	public static function getAllTraspaseBysellId($sell_id){
		$sql   = "select sum(q) as traspasos from ".self::$tablename." where sell_id=$sell_id AND operation_type_id in (6,7) order by product_id";
		$query = Executor::doit($sql);
		$found = null;
	   	$data  = new OperationData();
	   	while($r = $query[0]->fetch_array()){
			$data->traspaso = $r['traspasos'];
			$found = $data;
			break;
	   	}
	   	return $found;
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (is_traspase,status,price_in,price_out,stock_id,product_id,descripcion,q,operation_type_id,sell_id,operation_from_id,created_at,ref_sell_id,id_trans,id_salida,descuento_p) ";
		$sql .= "value ($this->is_traspase, $this->status,$this->price_in,$this->price_out,$this->stock_id,\"$this->product_id\",'$this->descripcion',\"$this->q\",$this->operation_type_id,$this->sell_id,$this->operation_from_id,$this->created_at,0,0,0,$this->dxp)";

		return Executor::doit($sql);
	}

	public function add_cotization(){
		$sql = "insert into ".self::$tablename." (is_traspase,status,is_draft,price_in,price_out,stock_id,product_id,descripcion,q,operation_type_id,sell_id,operation_from_id,created_at,ref_sell_id,id_trans,id_salida,descuento_p)";
		$sql .= "value ($this->is_traspase, $this->status,1,$this->price_in,$this->price_out,$this->stock_id,\"$this->product_id\",'$this->descripcion',\"$this->q\",$this->operation_type_id,$this->sell_id,$this->operation_from_id,$this->created_at,0,0,0,$this->dxp)";
	
		return Executor::doit($sql);
	}
        
	public function add_especial(){
		$sql = "insert into ".self::$tablename." (is_traspase,status,price_in,price_out,stock_id,product_id,descripcion,q,operation_type_id,sell_id,operation_from_id,created_at) ";
		$sql .= "value ($this->is_traspase, $this->status, $this->price_in,$this->price_out,$this->stock_id,\"$this->product_id\",'$this->descripcion',\"$this->q\",14,$this->sell_id,$this->operation_from_id,$this->created_at)";
		return Executor::doit($sql);
	}

	public static function delById($id){
	
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}

	// elimna los registros por el id de la venta.
	public function del(){
		$sql = "update ".self::$tablename." SET status = 0 where id=$this->id";
		Executor::doit($sql);
	
	} 

// partiendo de que ya tenemos creado un objecto OperationData previamente utilizamos el contexto
	public function update(){
		$sql = "update ".self::$tablename." set product_id=\"$this->product_id\",q=\"$this->q\" where id=$this->id";
		Executor::doit($sql);
	}

	public function set_draft($d){
		$sql = "update ".self::$tablename." set is_draft=\"$d\" where id=$this->id";
		Executor::doit($sql);
	}


	// calcula la salida de las devoluciones
	public function update_q(){
		$sql = "update ".self::$tablename." set q=\"$this->q\" where id=$this->id";
	    Executor::doit($sql);
	} 

		// calcula la entrada de las devuluciones
	public function update_q2(){
		$sql = "update ".self::$tablename." set q=\"$this->q\" where id=$this->id";
	    Executor::doit($sql);
	} 





	public function cancel(){
		$sql = "update ".self::$tablename." set status=0 where id=$this->id";
		Executor::doit($sql);
	}

	public function uncancel(){
		$sql = "update ".self::$tablename." set status=1 where id=$this->id";
		Executor::doit($sql);
	}

	public function update_type(){
		$sql = "update ".self::$tablename." set operation_type_id=\"$this->operation_type_id\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_status(){
		$sql = "update ".self::$tablename." set status=$this->status where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new OperationData());
	}
	public static function getAll(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());

	}

	public static function getAllTransit(){
		$sql = "select distinct sell_id from ".self::$tablename." WHERE operation_type_id = 8";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public static function getTransit($s,$p){
		$sql = "select * from ".self::$tablename." WHERE operation_type_id=8 AND sell_id=$s AND product_id=$p";
		$query = Executor::doit($sql);
		$found = null;
	   	$data = new OperationData();
	   	while($r = $query[0]->fetch_array()){
			$data->q                  = $r['q']; 
			$data->price_in           = $r['price_in'];
			$data->price_out          = $r['price_out'];
			$data->stock_id           = $r['stock_id'];
			$data->operation_from_id  = $r['operation_from_id'];
			$found = $data;
			break;
	   	}
	   	return $found;
	}

	public static function getReturns($s,$p,$o){
		$sql   = "select sum(q) as devoluciones from ".self::$tablename." WHERE operation_type_id=1 AND sell_id=$s AND product_id=$p AND stock_id=$o";
		$query = Executor::doit($sql);
		$found = null;
	   	$data  = new OperationData();
	   	while($r = $query[0]->fetch_array()){
			$data->devolucion  = $r['devoluciones'];
			$found = $data;
			break;
	   	}
	   	return $found;
	}

	
	public static function getEntrys($s,$p,$d){
		$sql   = "select sum(q) as entradas from ".self::$tablename." WHERE operation_type_id=1 AND sell_id=$s AND product_id=$p AND stock_id=$d";
		$query = Executor::doit($sql);
		$found = null;
	   	$data  = new OperationData();
	   	while($r = $query[0]->fetch_array()){
			$data->entrada  = $r['entradas'];
			$found = $data;
			break;
	   	}
	   	return $found;
	}
	

	public static function getAllByOT($p,$ot,$s){
		$sql = "select * from ".self::$tablename." where product_id=$p and operation_type_id=$ot and status=1 and stock_id=$s";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}


	public static function getAllBySQL($sqlextra){
		$sql = "select * from ".self::$tablename." $sqlextra";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}



	public static function getAllByDateOfficial($stock,$start,$end){
  $sql = "select * from ".self::$tablename." where (date(created_at) >= \"$start\" and date(created_at) <= \"$end\") and stock_id=$stock order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

// Obtener todos los productos propulares
public static function getPPByDateOfficial($start,$end){
 $sql = "select *,sum(q) as total from ".self::$tablename." where (date(created_at) >= \"$start\" and date(created_at) <= \"$end\") and operation_type_id=2 group by product_id order by total desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}
/// Obtener los 10 productos populares por stock
public static function get10Popular($stock, $start,$end){
 $sql = "select *,sum(q) as total from ".self::$tablename." where (date(created_at) >= \"$start\" and date(created_at) <= \"$end\") and operation_type_id=2 group by product_id order by total desc limit 10";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public static function getAllByDateOfficialBP($stock,$product, $start,$end){
 $sql = "select * from ".self::$tablename." where (date(created_at) >= \"$start\" and date(created_at) <= \"$end\") and product_id=$product and stock_id=$stock order by created_at desc";
		if($start == $end){
		 $sql = "select * from ".self::$tablename." where date(created_at) = \"$start\" order by created_at desc";
		                  }
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public function getProduct(){ return ProductData::getById($this->product_id);}
	public function getOperationtype(){ return OperationTypeData::getById($this->operation_type_id);}


	 // ESTA FUNCION NO AFECTO NADA EN INVENTARIOS.
	
	public static function getQ($product_id){
		$q=0;
		$operations = self::getAllByProductId($product_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		$output_id = OperationTypeData::getByName("salida")->id;
		foreach($operations as $operation){
				if($operation->operation_type_id==$input_id){ $q+=$operation->q; }
				else if($operation->operation_type_id==$output_id){  $q+=(-$operation->q); }
		}
		 print_r($data);
		return $q;
	}


    // AQUI AFECTA TODAS LAS EXISTENCIAS
	public static function getQByStock($product_id,$stock_id){
		$q=0;
		$operations = self::getAllByProductIdAndStock($product_id,$stock_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		$ajuste_general = OperationTypeData::getByName("ajuste-inventario")->id;
		$ajuste_sobrantes = OperationTypeData::getByName("ajuste-sobrantes")->id;
		$ajuste_faltantes = OperationTypeData::getByName("ajuste-faltantes")->id;
		$output_id = OperationTypeData::getByName("salida")->id;
		$pendiente_id = OperationTypeData::getByName("salida-pendiente")->id;
		$sustitucion_id = OperationTypeData::getByName("sustitucion-producto")->id;
		$salida_parcial = OperationTypeData::getByName("salida_parcial")->id;
		$dev = OperationTypeData::getByName("devolucion")->id;
	
		foreach($operations as $operation){
			if($operation->operation_type_id==$input_id || $operation->operation_type_id==$ajuste_sobrantes || $operation->operation_type_id==$dev ){ 
				$q+=$operation->q; 
			}else if($operation->operation_type_id==$output_id || $operation->operation_type_id==$sustitucion_id || $operation->operation_type_id==$ajuste_faltantes || $operation->operation_type_id==$salida_parcial)
			{$q+=(-$operation->q); 
			}else if ($operation->operation_type_id==$ajuste_general){
                $q=$operation->q; 
			}
	}
	
	return $q;
} 


		/* ORIGINAL 
		foreach($operations as $operation){
			if($operation->operation_type_id==$input_id || $operation->operation_type_id==$ajuste_sobrantes){ 
				$q+=$operation->q; 
			}
			else if($operation->operation_type_id==$output_id || $operation->operation_type_id==$pendiente_id || $operation->operation_type_id==$sustitucion_id || $operation->operation_type_id==$ajuste_faltantes)
			{  
				$q+=(-$operation->q); 
			
			}else if ($operation->operation_type_id==$ajuste_general){

				$q=$operation->q; 
			}
	}
	// print_r($data);
	return $q;
}  */


	// ESTA FUNCION SACA EL INVENTARIO PRINCIPAL POR STOCK INVENTARIO -> INVENTARIO PRINCIPAL
	public static function getRByStock($product_id,$stock_id){
		$q=0;
		$operations = self::getAllByProductIdAndStock($product_id,$stock_id);
		$input_id = OperationTypeData::getByName("entrada-pendiente")->id;
		foreach($operations as $operation){
				if($operation->operation_type_id==$input_id){ $q+=$operation->q; }
		}
		// print_r($data);
		return $q;
	} 


	public static function getDByStock($product_id,$stock_id){
		$q=0;
		$operations = self::getAllByProductIdAndStock2($product_id,$stock_id);
		$input_id = OperationTypeData::getByName("salida-pendiente")->id;
		$salida_parcial = OperationTypeData::getByName("salida_parcial")->id;
		
		foreach($operations as $operation){
				if($operation->operation_type_id==$input_id)
				{ 
				$q+=($operation->q); 
				}else if ($operation->operation_type_id==$salida_parcial){
				$q+=(-$operation->q); 
				}
		}
		// print_r($data);
		return $q;
	}

	public static function getAllByProductIdCutId($product_id,$cut_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and cut_id=$cut_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public static function getAllByProductId($product_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id  order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public static function getAllByProductIdAndStock($product_id,$stock_id){
		$sql = "select q,operation_type_id,sell_id,created_at from operation where product_id=$product_id and stock_id=$stock_id and is_draft=0 and status=1 ";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public static function getAllByProductIdAndStock2($product_id,$stock_id){
		$sql = "SELECT sell.id,sell.created_at,operation.product_id,operation.q,operation.operation_type_id  from sell 
        inner join operation on sell.id = operation.sell_id where sell.d_id = 2 and operation.product_id = $product_id and sell.stock_to_id = $stock_id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}
	

	//select * from ".self::$tablename." where product_id=$product_id and stock_id=$stock_id and is_draft=0 and status=1 order by created_at desc
	public static function getAllByProductIdCutIdOficial($product_id,$cut_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and cut_id=$cut_id order by created_at desc";
		return Model::many($query[0],new OperationData());
	}


	//aqui tomamos todos los productos para las remisiones.
	public static function getAllProductsBySellId($sell_id){
		$sql = "select * from ".self::$tablename." where sell_id=$sell_id and status = 1 and id_salida = 0";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

		//aqui tomamos todos los productos para las remisiones.
		public static function getAllProductsBySellId2($sell_id){
			$sql = "select * from ".self::$tablename." where sell_id=$sell_id";
			$query = Executor::doit($sql);
			return Model::many($query[0],new OperationData());
		}

	public static function getAllBySellIdAndProduct($sell_id,$product_id){
		$sql = "select * from ".self::$tablename." where sell_id=$sell_id and product_id=$product_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	//select * from ".self::$tablename." where sell_id=$sell_id and product_id=$product_id order by created_at desc

	public static function getDistinctProductsBySellId($sell_id){
		$sql = "select distinct product_id,price_in from ".self::$tablename." where sell_id=$sell_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public static function getAllByProductIdCutIdYesF($product_id,$cut_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and cut_id=$cut_id order by created_at desc";
		return Model::many($query[0],new OperationData());
		return $array;
	}

////////////////////////////////////////////////////////////////////
	public static function getOutputQ($product_id,$cut_id){
		$q=0;
		$operations = self::getOutputByProductIdCutId($product_id,$cut_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		$output_id = OperationTypeData::getByName("salida")->id;
		foreach($operations as $operation){
			if($operation->operation_type_id==$input_id){ $q+=$operation->q; }
			else if($operation->operation_type_id==$output_id){  $q+=(-$operation->q); }
		}
		// print_r($data);
		return $q;
	}

	public static function getOutputQYesF($product_id){
		$q=0;
		$operations = self::getOutputByProductId($product_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		$output_id = OperationTypeData::getByName("salida")->id;
		foreach($operations as $operation){
			if($operation->operation_type_id==$input_id){ $q+=$operation->q; }
			else if($operation->operation_type_id==$output_id){  $q+=(-$operation->q); }
		}
		// print_r($data);
		return $q;
	}

	public static function getInputQByStock($product_id,$stock_id){
		$q=0;
		$operations = self::getInputByProductIdAndStock($product_id,$stock_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		foreach($operations as $operation){
			if($operation->operation_type_id==$input_id){ $q+=$operation->q; }
		}
		// print_r($data);
		return $q;
	}

	

	public static function getOutputByProductIdCutId($product_id,$cut_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and cut_id=$cut_id and operation_type_id=2 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}


	public static function getOutputByProductId($product_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and operation_type_id=2 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

////////////////////////////////////////////////////////////////////
	public static function getInputQ($product_id,$cut_id){
		$q=0;
		return Model::many($query[0],new OperationData());
		$operations = self::getInputByProductId($product_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		$output_id = OperationTypeData::getByName("salida")->id;
		foreach($operations as $operation){
			if($operation->operation_type_id==$input_id){ $q+=$operation->q; }
			else if($operation->operation_type_id==$output_id){  $q+=(-$operation->q); }
		}
		// print_r($data);
		return $q;
	}

	public static function getInputByProductIdCutId($product_id,$cut_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and cut_id=$cut_id and operation_type_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public static function getInputByProductId($product_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and operation_type_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public static function getInputByProductIdAndStock($product_id,$stock_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and operation_type_id=1 and stock_id=$stock_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public static function getInputByProductIdCutIdYesF($product_id,$cut_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and cut_id=$cut_id and operation_type_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

////////////////////////////////////////////////////////////////////////////
}

?>