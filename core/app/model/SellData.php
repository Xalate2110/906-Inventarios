<?php
class SellData {
	public static $tablename = "sell";
	public static $facturacion = "cfdis";
	public static $tablename2 = "sell_to_deliver";
	public static $tablename3 = "historial_sell_to_deliver";
	public static $tablename4 = "operation";

	public $id;
	public $box_id;
	public $invoice_code;
	public $comment;
	public $ref_id;
	public $person_id;
	public $stock_id;
	public $stock_to_id;
	public $stock_from_id;
	public $iva;
	public $f_id;
	public $p_id;
	public $d_id;
	public $total;
	public $discount;
	public $cash;
	public $user_id;
	public $created_at;
	public $t;
	public $c;
	public $tot;
	public $invoice_file;
	public $sell_from_id;
	public $operation_type_id;
	public $is_draft;
	public $status;

	public function __construct(){
		$this->created_at = "NOW()";
		$this->ref_id=NULL;
	}

	public function getPerson(){ return PersonData::getById($this->person_id);}
	public function getUser(){ return UserData::getById($this->user_id);}
	public function getP(){ return PData::getById($this->p_id);}
	public function getD(){ return DData::getById($this->d_id);}
	public function getF(){ return FData::getById($this->f_id);}
	public function getStockFrom(){ return StockData::getById($this->stock_from_id);}
	public function getStockTo(){ return StockData::getById($this->stock_to_id);}

		
	public static function addPending($sell_id,$product_id,$product_code,$product_name,$entregada,$total_entregado,$dis_por_producto,$precio_salida,$total_venta_producto,$total_venta,$myuid,$usuario){
	
		//echo $fechaEntrega;
		 
		$sql = "insert into ".self::$tablename2." (sell_id,product_id,product_code,descripcion,entregada,total_entregado,precio_out,dis_por_producto,total_por_producto,total_venta,fechaEntregada,operacion,id_trans,id_usuario) ";
		$sql .= "value ($sell_id,$product_id,'$product_code','$product_name',$entregada,$total_entregado,$precio_salida,'$dis_por_producto',$total_venta_producto,$total_venta,NOW(),1,'$myuid',$usuario)";
		//echo $sql;
		return Executor::doit($sql);
	}

     	public static function add_historial($sell_id,$product_id,$product_code,$product_name,$entregada,$total_entregado,$dis_por_producto,$precio_salida,$total_venta_producto,$total_venta,$myuid,$usuario){
    	$sql_historial = "insert into ".self::$tablename3." (sell_id,product_id,product_code,descripcion,entregada,total_entregado,precio_out,dis_por_producto,total_por_producto,total_venta,fechaEntregada,operacion,id_trans,id_usuario) ";
    	$sql_historial .= "value ($sell_id,$product_id,'$product_code','$product_name',$entregada,$total_entregado,$precio_salida,'$dis_por_producto',$total_venta_producto,$total_venta,NOW(),1,'$myuid',$usuario)";
     	return Executor::doit($sql_historial);
	}


    	public static function add_salida($sell_id,$product_id,$product_code,$product_name,$entregada,$total_entregado,$dis_por_producto,$precio_salida,$total_venta_producto,$total_venta,$myuid,$usuario){
		$sql = "insert into ".self::$tablename4." (product_id,stock_id,stock_destination_id,operation_from_id,q,price_in,price_out,discount,operation_type_id,sell_id,status,is_draft,is_traspase,created_at,ref_sell_id,id_trans,id_salida) ";
		$sql .= "value ('$product_id','1',NULL,NULL,'$entregada','0','$precio_salida','0','13',$sell_id,'1','0','0',NOW(),$sell_id,'$myuid','1')";
	
		return Executor::doit($sql);
	}


	public static function getDelivered($s,$p){
		$sql   = "select sum(entregada) as entregadas from ".self::$tablename2." WHERE sell_id=$s AND product_id=$p";
		$query = Executor::doit($sql);
		$found = null;
	   	$data  = new OperationData();
	   	while($r = $query[0]->fetch_array()){
			$data->entregada  = $r['entregadas'];
			$found = $data;
			break;
	   	}
	   	return $found;
	} 

	public static function getDeliverById($sell_id){
		$sql = "select * from ".self::$tablename2." where sell_id=$sell_id order by fechaEntregada";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (invoice_code,comment,ref_id,person_id,stock_to_id,iva,f_id,p_id,d_id,total,discount,cash,sub_total,is_draft,user_id,created_at,anticipo_venta,reg_anticipo,reg_porpagar,p_pendiente,total_por_pagar,fecha_remision,pendiente,r_credito,facturado,remision_recuperada,credito_liquidado)";
		$sql .= "value (\"$this->invoice_code\",\"$this->comment\",$this->ref_id,$this->person_id,$this->stock_to_id,$this->iva,$this->f_id,$this->p_id,$this->d_id,$this->total,$this->discount,$this->cash,$this->sub_total,$this->is_draft,$this->user_id,$this->created_at,$this->anticipo_venta,$this->reg_anticipo,$this->reg_porpagar,$this->p_pendiente,$this->total_por_pagar,$this->created_at,$this->pendiente,$this->r_credito,$this->facturado,$this->remision_recuperada,$this->credito_liquidado)";
		return Executor::doit($sql);}

	public function add_actualiza_cot(){
		if($this->status_cot > "0" ){
        $actualiza_cotizacion = "update ".self::$tablename." set cot_procesada = 1, f_cotprocesada = NOW(), ticket_relacionado = $this->ticket where id = $this->status_cot and is_draft = 1 ";
		Executor::doit($actualiza_cotizacion);
		}
	}
	
	public function add_traspase(){
		$sql = "insert into ".self::$tablename." (stock_to_id,stock_from_id,operation_type_id,iva,p_id,d_id,total,discount,user_id,created_at) ";
		$sql .= "value ($this->stock_to_id,$this->stock_from_id,$this->operation_type_id,$this->iva,$this->p_id,$this->d_id,$this->total,$this->discount,$this->user_id,$this->created_at)";
		return Executor::doit($sql);
	}


	public function add_cotization(){
		$sql = "insert into ".self::$tablename." (person_id,is_draft,p_id,d_id,user_id,created_at) ";
		$sql .= "value ($this->person_id,1,2,2,$this->user_id,$this->created_at)";
		return Executor::doit($sql);
	}

	public function add_cotization_by_client(){
		$sql = "insert into ".self::$tablename." (is_draft,p_id,d_id,person_id,created_at) ";
		$sql .= "value (1,2,2,$this->person_id,$this->created_at)";
		return Executor::doit($sql);
	}

	public function add_de(){
		$sql = "insert into ".self::$tablename." (person_id,status,stock_to_id,sell_from_id,user_id,operation_type_id,created_at,total,p_id,d_id,cerrada_dev,parcial_dev) ";
		$sql .= "value ($this->person_id,0,$this->stock_to_id,$this->sell_from_id,$this->user_id,5,$this->created_at,$this->total,0,0,$this->c_remision,$this->p_remision)";
		return Executor::doit($sql);
	}

	public function add_re(){
		$sql = "insert into ".self::$tablename."(invoice_code,f_id,ref_id,person_id,stock_to_id,sub_total,iva,total,p_id,d_id,user_id,operation_type_id,created_at,compra_creditop) ";
		$sql .= "value (\"$this->invoice_code\",$this->f_id,$this->ref_id, $this->person_id,$this->stock_to_id,$this->subtotal,$this->iva,$this->total,$this->p_id,$this->d_id,$this->user_id,1,$this->created_at,$this->compra_creditop)";
		return Executor::doit($sql);
	}

	public function add_is(){
		$sql = "insert into ".self::$tablename."(invoice_code,f_id,ref_id,person_id,stock_to_id,sub_total,iva,total,p_id,d_id,user_id,operation_type_id,created_at,remision_ligada,factura_ligada,obra_salida,trabajador) ";
		$sql .= "value (\"$this->invoice_code\",0,0, $this->person_id,$this->stock_to_id,$this->subtotal,$this->iva,$this->total,$this->p_id,$this->d_id,$this->user_id,10,$this->created_at,$this->remision_ligada,\"$this->factura_ligada\",\"$this->obra\",\"$this->trabajador\")";
		return Executor::doit($sql);
	}

	public function add_if(){
		$sql = "insert into ".self::$tablename."(invoice_code,f_id,ref_id,person_id,stock_to_id,sub_total,iva,total,p_id,d_id,user_id,operation_type_id,created_at,remision_ligada,factura_ligada,obra_salida,trabajador) ";
		$sql .= "value (\"$this->invoice_code\",0,0, $this->person_id,$this->stock_to_id,$this->subtotal,$this->iva,$this->total,$this->p_id,$this->d_id,$this->user_id,11,$this->created_at,$this->remision_ligada,\"$this->factura_ligada\",\"$this->obra\",\"$this->trabajador\")";
		return Executor::doit($sql);
	}

	public function add_ige(){
		$sql = "insert into ".self::$tablename."(invoice_code,f_id,ref_id,person_id,stock_to_id,sub_total,iva,total,p_id,d_id,user_id,operation_type_id,created_at) ";
		$sql .= "value (\"$this->invoice_code\",0,0, $this->person_id,$this->stock_to_id,$this->subtotal,$this->iva,$this->total,$this->p_id,$this->d_id,$this->user_id,12,$this->created_at)";
		return Executor::doit($sql);
	}

public function add_with_client(){	
		$sql = "insert into ".self::$tablename." (iva,p_id,d_id,total,discount,person_id,user_id,created_at) ";
		$sql .= "value ($this->iva,$this->p_id,$this->d_id,$this->total,$this->discount,$this->person_id,$this->user_id,$this->created_at)";
		return Executor::doit($sql);
	}
	public function add_re_with_client(){
		$sql = "insert into ".self::$tablename." (p_id,d_id,person_id,operation_type_id,user_id,created_at) ";
		$sql .= "value ($this->p_id,$this->d_id,$this->person_id,1,$this->user_id,$this->created_at)";
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

	public function del_parcial(){
		$sql = "delete from ".self::$tablename2." where id=$this->id";
		Executor::doit($sql);
	}



	public function process_cotization(){
		$sql = "update ".self::$tablename." set stock_to_id=$this->stock_to_id,p_id=$this->p_id,d_id=$this->d_id,iva=$this->iva,total=$this->total,discount=$this->discount,cash=$this->cash,is_draft=0 where id=$this->id";
		Executor::doit($sql);
	}

	public function update(){
		$sql = "update ".self::$tablename." set f_id=$this->f_id,person_id=$this->person_id,invoice_code=\"$this->invoice_code\",invoice_file=\"$this->invoice_file\",comment=\"$this->comment\"  where id=$this->id";
		Executor::doit($sql);
	}


	public function update_box(){
		$sql = "update ".self::$tablename." set box_id=$this->box_id where id=$this->id and box_id is null";
		Executor::doit($sql);
	}

	

	public function update_d(){
		$sql = "update ".self::$tablename." set d_id=$this->d_id where id=$this->id";
		Executor::doit($sql);
	}

	public function update_status(){
		$sql = "update ".self::$tablename." set status=$this->status where id=$this->id";
		Executor::doit($sql);
	}

	public function update_p(){
		$sql = "update ".self::$tablename." set p_id=$this->p_id where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		 $sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new SellData());
	}


	public function cancel(){
		$sql = "update ".self::$tablename." set d_id=3,p_id=3 where id=$this->id";
		Executor::doit($sql);
	}

	public function uncancel(){
		$sql = "update ".self::$tablename." set d_id=1,p_id=1 where id=$this->id";
		Executor::doit($sql);
	}

	public static function getCotizations($stock_id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=2 and d_id=2 and is_draft=1 and cot_procesada = 0 and stock_to_id = $stock_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getCreditsByClientId2($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and p_id=4 and is_draft=0 and person_id=$id  order by created_at desc ";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getCotizationsByClientId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=2 and d_id=2 and is_draft=1 and person_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSells(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=1 and d_id=1 and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


	public static function getSellsByUserId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=1 and d_id=1 and is_draft=0 and user_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


	public static function getCredits(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=4 and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getCreditsByUserId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=4 and is_draft=0 and user_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getCreditsByClientId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=4 and is_draft=0 and person_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

  	
	public static function getByIdParcial($id){
		$sql = "select * from ".self::$tablename2." where id=$id";
	   $query = Executor::doit($sql);
	   return Model::one($query[0],new SellData());
   }

	
	/* Obtener el almacen origen */
	public static function getOrigenDestinoById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
	  $query = Executor::doit($sql);
	  $found = null;
	   $data = new OperationData();
	   	while($r = $query[0]->fetch_array()){
			$data->stock_to_id   = $r['stock_to_id'];
			$data->stock_from_id = $r['stock_from_id'];
			$found = $data;
			break;
	   	}
	   	return $found;
   }


	public static function getCreditsByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=4 and is_draft=0 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsByClientId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=1 and d_id=1 and is_draft=0 and person_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


	public static function getSellsToDeliver(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and d_id=2 and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsToDeliverByUserId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and d_id=2 and is_draft=0 and user_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getSellsToDeliverByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and d_id=2 and is_draft=0 and stock_to_id=$id order by created_at desc";
	
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


	public static function getSellsToDeliverByClient($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and d_id=2 and is_draft=0 and person_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsToCob(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=2 and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsToCobByUserId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=2 and is_draft=0 and user_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getSellsToCobByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=2 and is_draft=0 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsToCobByClientId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=2 and is_draft=0 and person_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


	public static function getSellsUnBoxed(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and box_id is NULL and p_id=1 and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


	public static function getSellsUnBoxedByUser($u){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and box_id is NULL and p_id=1 and is_draft=0 and user_id=$u order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsByBox($u){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=1 and is_draft=0 and box_id=$u order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


	public static function getResUnBoxed(){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and box_id is NULL and p_id=1 and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getByBoxId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and box_id=$id and is_draft=0 order by created_at DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getResByBoxId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and box_id=$id and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getRes(){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and p_id=1 and d_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getResByStockId($id,$fecha_i,$fecha_f){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and p_id=1 and d_id=1 and stock_to_id=$id and date(created_at) between '$fecha_i' and '$fecha_f'order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getResToPay(){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and p_id=2  order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getResToPayByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and p_id=2 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getResToReceive(){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and d_id=2  order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getResToReceiveByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and d_id=2 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSQL($sql){
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


	public static function getAllBySQL($sqlextra){
		$sql = "select * from ".self::$tablename." $sqlextra and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	
	public static function getAllBySQL2($sqlextra){
		$sql = "select * from ".self::$tablename." $sqlextra";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	
	public static function getAllBySQL3($sql){
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	} 

	public static function getAllByPage($start_from,$limit){
		$sql = "select * from ".self::$tablename." where id<=$start_from limit $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());

	}

	public static function getAllByDateOp($start,$end,$op){
	  $sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and operation_type_id=$op and is_draft=0 and p_id=1 and d_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getAllByDateOpByUserId($user,$start,$end,$op){
	  $sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and operation_type_id=$op and is_draft=0 and p_id=1 and d_id=1 and user_id=$user order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


	public static function getGroupByDateOp($start,$end,$op){
        $sql = "select id,sum(total) as tot,discount,sum(total-discount) as t,count(*) as c from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and operation_type_id=$op and p_id!=4 and is_draft=0";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


	public static function getAllByDateBCOp($clientid,$start,$end,$op){
 		$sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and person_id=$clientid  and operation_type_id=$op and is_draft=0 and p_id=1 and d_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());

	}

	public static function getAllByDateBCOpByUserId($user,$clientid,$start,$end,$op){
 		$sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and person_id=$clientid  and operation_type_id=$op and is_draft=0 and p_id=1 and d_id=1 and user_id=$user order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());

	}


}

?>