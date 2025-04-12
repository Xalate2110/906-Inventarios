<?php
	//echo '<pre>';
	//print_r($_POST);
	/*
	*/
	$sellID = $_POST['ventaid'];
	foreach ($sellID as $id => $cantidad) {
		$sell_id = $id;
	}

	$Se = SellData::getOrigenDestinoById($sell_id);

	$entrada = $_POST['entrada'];
	foreach ($entrada as $product_id => $cantidad) {
		// Validar que el valor sea mayor a cero y que la cantidad que el usuario escribe sea menor o igual al restante
		$OpEn = OperationData::getEntrys( $sell_id, $product_id, $Se->stock_to_id);
		$OpRe = OperationData::getReturns($sell_id, $product_id, $Se->stock_from_id);
		$OpTr = OperationData::getTransit($sell_id, $product_id);
		$pendiente = $OpTr->q - ($OpEn->entrada + $OpRe->devolucion);
		
		if($cantidad <= $pendiente){

			$OpDa                  = OperationData::getTransit($sell_id, $product_id);
			$op 				   = new OperationData();
			$op->price_in 		   = $OpDa->price_in;
			$op->price_out 		   = $OpDa->price_out;
			$op->product_id 	   = $product_id;
			$op->operation_type_id = OperationTypeData::getByName("entrada")->id;
			$op->stock_id 		   = $Se->stock_to_id; // almacen destino
			$op->operation_from_id = $OpDa->operation_from_id;
			$op->sell_id		   = $sell_id;
			$op->q				   = $cantidad;
			$op->is_traspase	   = 1;
			$add 				   = $op->add();

			$products              = ProductData::getById($product_id);
			$tipo                  = 'entrada';
			//print_r($products->code);
			OperationData::addEntrysAndReturns($sell_id,$product_id,$products->code,$tipo,$cantidad);
		}
		
	}

	
	$devolucion = $_POST['devolucion'];
	foreach ($devolucion as $product_id => $cantidad) {
		// Validar que el valor sea mayor a cero y que la cantidad que el usuario escribe sea menor o igual al restante
		$OpEn = OperationData::getEntrys( $sell_id, $product_id, $Se->stock_to_id);
		$OpRe = OperationData::getReturns($sell_id, $product_id, $Se->stock_from_id);
		$OpTr = OperationData::getTransit($sell_id, $product_id);
		$pendiente = $OpTr->q - ($OpEn->entrada + $OpRe->devolucion);

		if($cantidad <= $pendiente){

			$OpDa                  = OperationData::getTransit($sell_id, $product_id);
			$op 				   = new OperationData();
			$op->price_in 		   = $OpDa->price_in;
			$op->price_out 		   = $OpDa->price_out;
			$op->product_id 	   = $product_id;
			$op->operation_type_id = OperationTypeData::getByName("entrada")->id;
			$op->stock_id 		   = $Se->stock_from_id; // almacen origen
			$op->operation_from_id = $OpDa->operation_from_id;
			$op->sell_id		   = $sell_id;
			$op->q				   = $cantidad;
			$op->is_traspase	   = 1;
			$add 				   = $op->add();

			$products              = ProductData::getById($product_id);
			$tipo                  = 'devolucion';
			//print_r($products->code);
			OperationData::addEntrysAndReturns($sell_id,$product_id,$products->code,$tipo,$cantidad);
		}

	}
	
	print "<script>window.location='index.php?view=listado_traspasos';</script>";
	


?>