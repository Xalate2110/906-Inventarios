<?php

if(count($_POST)>0){
	$product_id = $_POST["product_id"];
	$id_almacen = $_POST["stock_id"];
	$stocks = StockData::getAll2($id_almacen);
	foreach($stocks as $stock){
		$px = PriceData::getByPS($product_id,$stock->id);
		if($px!=null){
			$px->del();
			$px = new PriceData();
			$px->price_out=  $_POST["price_".$stock->id."_".$product_id];
            $px->price_out2 = $_POST["price2_" . $stock->id . "_" . $product_id];
            $px->price_out3 = $_POST["price3_" . $stock->id . "_" . $product_id];
			$px->price_out4 = $_POST["price4_" . $stock->id . "_" . $product_id];
 			$px->product_id= $product_id;
			$px->stock_id = $stock->id;
			$px->add();
		}else{
			$px = new PriceData();
			$px->price_out=  $_POST["price_".$stock->id."_".$product_id];
			$px->product_id= $product_id;
            $px->price_out2 = $_POST["price2_" . $stock->id . "_" . $product_id];
            $px->price_out3 = $_POST["price3_" . $stock->id . "_" . $product_id];
			$px->price_out4 = $_POST["price4_" . $stock->id . "_" . $product_id];
			$px->stock_id = $stock->id;
			$px->add();			
		}

	
		include '/connection/conexion.php';
		$sql2 = "update product set price_out=$px->price_out,price_out2=$px->price_out2,price_out3=$px->price_out3,price_out4=$px->price_out4  where id=$px->product_id";
		$mysqli->query($sql2); 

	}



	
	print "<script>window.location='index.php?view=prices';</script>";


}


?>