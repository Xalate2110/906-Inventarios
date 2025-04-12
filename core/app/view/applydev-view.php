	<?php

	$sell = SellData::getById($_GET["id"]);
	//$sell_from = SellData::getById($sell->sell_from_id);
	$operations = OperationData::getAllProductsBySellId2($_GET["id"]);
	$operations_from = OperationData::getAllProductsBySellId2($sell->sell_from_id);

	$origen = $_GET["origen"]; // Es la venta de origen. 
	$dv = $_GET["id"]; // Es la Devolucion. 
	$cerrada_dev = $_GET["cerrada_dev"]; 

	$sell->status=1;
	foreach ($operations as $op) {
		//$op->status=1;
		//$op->update_status();
		
		
		foreach($operations_from as $opf){
			if($opf->product_id==$op->product_id){
			$opf->q -= $op->q;
            $opf->update_q();
			break;
			} 
		} 

 	    //agregamos la devolucion como un gasto
		$product = ProductData::getById($op->product_id);
		$user = new SpendData();
		//$user->box_id = $box->id;
		$user->kind=2;
		$user->name = "Devolucion - Folio - ". $sell->id ."  - ".$product->name;
		$user->price = $product->price_out*$op->q;

		$total_dev = round($user->price,2);
		

        include '/connection/conexion.php';
        // vamos a ponerle a la devolución la cantidad que devolvio. 
		$update_dev = "UPDATE SELL SET total = '$total_dev' WHERE id = $dv ";
        $mysqli->query($update_dev);

		//sacamos si la remisión se va a cerrar o no. 

		$sql_venta2 = "SELECT cerrada_dev FROM sell WHERE id = $dv ";
        $resultSet_venta2 = $mysqli->query($sql_venta2);
        $fila2 = $resultSet_venta2->fetch_assoc();
		$c_dev = $fila2['cerrada_dev'];

		// vamos a selecionar la venta
		$sql_venta = "SELECT total,p_id FROM sell WHERE id = $origen ";
        $resultSet_venta = $mysqli->query($sql_venta);
        $fila = $resultSet_venta->fetch_assoc();
		$total_venta = $fila['total'];
		$credito = $fila['p_id'];
        $operacion = ($total_venta - $user->price);
		
		$total = round($operacion,2);
		$subtotal = round($total / 1.16,2);
        $iva = round($subtotal * 0.16,2);

        $update1 = "UPDATE SELL SET total = '$total', sub_total = '$subtotal', iva = '$iva' WHERE id = $origen ";
        $mysqli->query($update1);

		if ($credito == "4"){
        $update2 = "UPDATE PAYMENT SET val = '$total' WHERE sell_id = $origen and payment_type_id = 1";
		$mysqli->query($update2);}

		if ($c_dev == "1"){
		$update3 = "UPDATE SELL SET p_id = 3,d_id=3, cerrada_dev = 1 WHERE id = $origen";
		$mysqli->query($update3);}

       }

	   

		$sell->update_status();
		Core::alert("Se ha aprovado la devolución exiitosamente, el sistema recalculara el total de la venta y credito en el caso de exista.");
		Core::redir("./index.php?view=devs");

?>