<?php
  error_reporting(0);
if(isset($_SESSION["reabastecer_inv"])){
	
	    $cart = $_SESSION["reabastecer_inv"];
	    if(count($cart)>0){
        $process = true;
		if($process==true){
			//$y = new YYData();
			//$yy = $y->add();
			$sell = new SellData();
			//$sell->ref_id= "NULL";
			$sell->user_id = $_SESSION["user_id"];
			//$sell->invoice_code = $_POST["invoice_code"];
			$sell->p_id = 0; // no recibe
			$sell->d_id = 0; // no recibe
			$sell->f_id = 0; // no recibe
			$sell->subtotal = $_POST["subtotal"];
			$sell->iva = $_POST["iva"]; 
			$sell->total = $_POST["total"]; 
			$sell->stock_to_id = $_POST["stock_id"];
			$sell->person_id=$_POST["client_id"]!=""?$_POST["client_id"]:"NULL";
			$sell->id_anticipo = 0;
			$sell->remision_ligada = $_POST["remision"];
			$sell->factura_ligada = $_POST["factura"];
			$sell->trabajador = $_POST["trabajador"];
			$sell->obra = $_POST["obra"];
			$s = $sell->add_is();

		   foreach($cart as  $c){
           $operation_type = 10;
			
			if($_POST["d_id"]==2){ $operation_type= 3; // 3.- entrada-pendiente 
			}

			$product = ProductData::getById($c["product_id"]);
			$price_in = $c["price_in"]; // $product->price_out;

			$op = new OperationData();
			$op->price_in = $price_in;
			$op->price_out = $product->price_out;
			$op->stock_id = $_POST["stock_id"];
			$op->product_id = $c["product_id"] ;
			$op->operation_type_id=$operation_type; // 1 - entrada
			$op->sell_id=$s[1];
			$op->q= $c["q"];
			$add = $op->add();			 		}

			unset($_SESSION["reabastecer_inv"]);
			setcookie("selled","selled");
////////////////////
print "<script>setTimeout(function(){ w = window.open('ajuste_inventario_sobrantes.php?id=$s[1]&sucursal=$sell->stock_to_id','ticket','height=720,width=1280');  w.print(); window.location.reload();  }, 100);  </script>";
		}
	}
}




?>