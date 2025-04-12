<?php
error_reporting(0);
if(!empty($_POST)){
	// print_r($_POST);
	$sell = SellData::getById($_POST["sell_id"]);
	$operations = OperationData::getAllProductsBySellId2($sell->id);

	$p_salida = $_POST["p_salida"];
	$cantidad = $_POST["cantidad"];
	$c_remision = $_POST["remision"];
	$p_remision = $_POST["remision_p"];

	if (empty($c_remision)) {
	    $c_remision =0;}

	if (empty($p_remision)) {
		$p_remision =0;}
	
	
	
	$dev = new SellData();
	$dev->stock_to_id = $sell->stock_to_id;
	$dev->total = $p_salida * $cantidad;
	$dev->sell_from_id = $_POST["sell_id"];
	$dev->person_id = $_POST["person_id"];
	$dev->user_id = $_SESSION["user_id"];
	$dev->c_remision = $c_remision;
	$dev->p_remision = $p_remision;
	$dev->operation_type_id = 5; // devolution
	$dev->status=0;
	$d = $dev->add_de();


	foreach ($operations as $op) {
		if(isset($_POST["devolucion".$op->id])){
			if($_POST["devolucion".$op->id]>0 || $_POST["devolucion".$op->id]<=$op->q){
//				print_r($op);
  			 	 $product = ProductData::getById($op->product_id);
				 $dev_op = new OperationData();
				 $dev_op->product_id = $op->product_id ;
				 $dev_op->price_in = $product->price_in;
				 $dev_op->price_out= $op->price_out;
//				 $dev_op->stock_id = StockData::getPrincipal()->id;
				 $dev_op->stock_id = $sell->stock_to_id;
				 $dev_op->descripcion = "";
				 $dev_op->operation_type_id=OperationTypeData::getByName("devolucion")->id;
				 $dev_op->sell_id=$d[1];
				 $dev_op->status=0;
				 $dev_op->q= $_POST["devolucion".$op->id];
				 $add = $dev_op->add();
			     $op->q -= $_POST["devolucion".$op->id];
		     	// $op->update_q();

				 
				 /// agregamos la devolucion como un gasto
//				 	$product = ProductData::getById($op->product_id);
//				 	$user = new SpendData();
//					$user->name = "Devolucion - Venta - ".$sell->id."  - ".$product->name;
//					$user->price = $product->price_out*$_POST["op_".$op->id];
//					$user->add();

			}
		}

	}
	Core::alert("Se ha registrado correctamente la Devolución, El sistema te enviara al modulo de notificaciones para que apliques la Devolucion Correctamente");
	Core::redir("./?view=notifs");

}

?>