<?php
date_default_timezone_set("America/Mexico_City");
if(isset($_SESSION["cot"])){
	$cart = $_SESSION["cot"];
	if(count($cart)>0){
/// antes de proceder con lo que sigue vamos a verificar que:
		// haya existencia de productos
		// si se va a facturar la cantidad a facturr debe ser menor o igual al producto facturado en inventario
		$num_succ = 0;
		$process=false;
		$errors = array();
		foreach($cart as $c){

			///
			$product = ProductData::getById($c["product_id"]);
			$q = OperationData::getQByStock($c["product_id"],StockData::getPrincipal()->id);
			if ($product->kind == 2 || $c["q"] <= $q || $c["q"] >= $q || $c["q"] > $q || $c["q"] < $q || $c["q"] = $q) {

					$num_succ++;
			}else{
				$error = array("product_id"=>$c["product_id"],"message"=>"No hay suficiente cantidad de producto en inventario.");
				$errors[count($errors)] = $error;
			}

		}
//		print_r($errors);
// $num_succ;
if($num_succ==count($cart)){
	$process = true;
	//echo "yes";
}

/*
if(count($errors)>0){
$_SESSION["errors"] = $errors;
	?>	
<script>
	alert("Error de Inventario!");
	window.location="index.php?view=sellnew";
</script>
<?php
} */





//////////////////////////////////
		if($process==true){
			$iva_val = ConfigurationData::getByPreffix("imp-val")->val;
$box = BoxData::getLastOpenByUser($_SESSION["user_id"]);

			$sell = new SellData();
			$sell->ref_id="NULL";
			if($box){
			$sell->box_id=$box->id;

			}else{
			$sell->box_id="NULL";

			}

			$porcentaje = 1.16;
			$sub_total = round($sell->total = $_POST["total"] / $porcentaje,2);
		
			$impuesto = 0.16;
			$iva = round($sub_total * $impuesto,2);
			
	
			$sell->user_id = $_SESSION["user_id"];

			//$sell->invoice_code = $_POST["invoice_code"];
			$sell->comment = $_POST["comment"];
			$sell->f_id = $_POST["f_id"];
			$sell->p_id = '2';
			$sell->d_id = "2";
			$sell->anticipo_venta = $_POST["anticipo_venta"];
			$sell->iva = round($iva,2);
            $sell->sub_total = round($sub_total,2);
			$sell->cash = $_POST["money"];
			$sell->total = ($_POST["total"] - $sell->anticipo_venta = $_POST["anticipo_venta"]);
			$sell->discount = $_POST["discount"];
			$sell->id_cotizacion =  "0";
	        $sell->is_draft = "1";
			$sell->stock_to_id = StockData::getPrincipal()->id;
			$sell->person_id=$_POST["client_id"]!=""?$_POST["client_id"]:"NULL";
			$sell->r_credito = 0;
			$sell->facturado = 0;
			$sell->remision_recuperada = 0;
			$sell->credito_liquidado = 0;


			if($_POST['p_id'] == 4 ){
				$sell->r_credito = 4;
			  }
  
		   
			// SI LA VENTA NO TIENE ANTICIPO, EL VALOR REAL SERA DE 0   
			$sell->monto_comision = 0;
			$sell->total_por_pagar = 0;
  
			$sell->reg_anticipo = 0 ;
			$sell->reg_porpagar = 0 ;
			$sell->pendiente = 0 ;
			$sell->p_pendiente = 0 ;
	 
			//Se aplica el cargo del 3% al seleccionar tarjeta de creito o debito.
			if ($_POST["p_id"] == 4 ) {
			$sell->total_por_pagar = $_POST["total"] - $sell->anticipo_venta = $_POST["anticipo_venta"];
			$sell->reg_anticipo = $_POST["anticipo_venta"];
			$sell->reg_porpagar = $_POST["total"] - $sell->anticipo_venta = $_POST["anticipo_venta"]; 
			$sell->pendiente = 1; 
			$sell->p_pendiente = 2; }
  
  
			//Se aplica el cargo del 3% al seleccionar tarjeta de creito o debito.
			if ($_POST["f_id"] == 3 || $_POST["f_id"] == 4) {
			$sell->total_comision = ($_POST["monto_comision"] / 100) ; 
			$sell->comision = round($_POST["total"] * $sell->total_comision,2);
			$sell->total = round($_POST["total"] +   $sell->comision,2); 
			}

			$s = $sell->add();

			
			 /// si es credito....
			 if($_POST["p_id"]==4){
				$payment = new PaymentData();
				$payment->sell_id = $s[1];
				$payment->val = ($_POST["total"] - $sell->anticipo_venta = $_POST["anticipo_venta"]);
				$payment->person_id = $_POST["client_id"];
				$payment->add();
				if($_POST["money"]>0){
				   $payment2 = new PaymentData();
					$payment2->sell_id = $s[1];
					$payment2->val = -1*$_POST["money"];
					$payment2->person_id = $_POST["client_id"];
					$payment2->add_payment();
				}
			}
			//////////////

		foreach($cart as  $c){
			$operation_type = "salida";
			if($_POST["d_id"]==2){ $operation_type="salida-pendiente"; }

			$product = ProductData::getById($c["product_id"]);
			$descripcion = $c["descripcion"]; // $product->price_out;
			$decuento_p = $c["descuento_p"]; // $product->price_out;

//$price = $product->price_out;
		$px = PriceData::getByPS($product->id,StockData::getPrincipal()->id);
		if($px!=null){ 
			$price = $px->price_out; 
			$price2 = $px->price_out2; 
			$price3 = $px->price_out3; 
			$price4 = $px->price_out4;

		}

		$theprice = $c["price"] - $sell->discount = $_POST["discount"];
		if($c["use_price2"]==1){
		  $theprice = $c["price_opt"] - $sell->discount = $_POST["discount"];
		}
/*
else{
  if($px!=null){
    $theprice=$px->price_out;
  }
}
*/
			$op = new OperationData();
			$op->price_in = $product->price_in;
			$op->price_out = $theprice;
			$op->dxp = $decuento_p;
			$op->product_id = $c["product_id"] ;
			$op->descripcion = $descripcion;
			$op->operation_type_id=OperationTypeData::getByName($operation_type)->id;
			$op->stock_id = StockData::getPrincipal()->id;
			$op->sell_id=$s[1];
			$op->q= $c["q"];
			if(isset($_POST["is_oficial"])){
				$op->is_oficial = 1;
			}

			$add = $op->add_cotization();			 		



////////////////// generando el mensaje
		$subject = "[".$s[1]."] Nueva venta en el inventario";
		$message = "<p>Se ha realizado una venta con Id = ".$s[1]."</p>";
$person_th="";
$person_td="";
$person = null;
if($_POST["client_id"]!=""){
	$person = PersonData::getById($_POST["client_id"]);
	$person_th="<td>Cliente</td>";
	$person_td="<td>".$person->name." ".$person->lastname."</td>";
}


		$message .= "<table border='1'><tr>
		<td>Id</td>
		$person_th
		<td>Almacen</td>
		<td>Estado de pago</td>
		<td>Estado de entrega</td>
		<td>Total</td>
		</tr>
<tr>
		<td>".$s[1]."</td>
		$person_td
		<td>".StockData::getById($sell->stock_to_id)->name."</td>
		<td>".PData::getById($sell->p_id)->name."</td>
		<td>".DData::getById($sell->d_id)->name."</td>
		<td> $".number_format($sell->total,2,".",",")."</td>
		</tr>
		</table>";
		$message.="<h3 style='color:#333;'>Resumen</h3>";
		$message.="<table border='1'><thead><th>Id</th><th>Codigo</th><th>Cantidad</th><th>Unidad</th><th>Producto</th><th>P.U</th><th>P. Total</th></thead>";
		foreach($cart as  $c){
			$message.="<tr>";
		$product = ProductData::getById($c["product_id"]);
		$message.="<td>".$product->id."</td>";
		$message.="<td>".$product->barcode."</td>";
		$message.="<td>".$c["q"]."</td>";
		$message.="<td>".$product->unit."</td>";
		$message.="<td>".$product->name."</td>";
		$message.="<td>$ ".number_format($product->price_out,2,".",",")."</td>";
		$message.="<td>$ ".number_format($c["q"]*$product->price_out,2,".",",")."</td>";
		$message.="</tr>";
		}
		$message.="</table>";
//////////////////
		if($subject!=""&&$message!=""){
				$m = new MailData();
				$m->open();
				// enviamos una copia del correo para el cliente
				if($person!=null){ $m->mail->AddAddress($person->email1); }
			    $m->mail->Subject = $subject;
			    $m->message = "<p>$message</p>";
			    $m->mail->IsHTML(true);
//			    $m->send();
			}
//////////////////




$qx = OperationData::getQByStock($product->id,StockData::getPrincipal()->id);
$subject="";
$message="";
$last = true;
if($qx==0){
			$subject = "[$product->name]".' No hay existencias';
			$message = "Hola, el producto <b>$product->name</b> no tiene existencias en el inventario";
			$last=false;
		}

if($qx<=$product->inventary_min/2 && $last){
	$subject = "[$product->name]".' Muy pocas existencias';
	$message = "Hola, el producto <b>$product->name</b> tiene muy pocas existencias en el inventario";
			$last=false;

}
if($qx<=$product->inventary_min && $last){
	$subject = "[$product->name]".' Pocas existencias';
	$message = "Hola, el producto <b>$product->name</b> tiene pocas existencias en el inventario";
			$last=false;
}
//////////////////
		if($subject!=""&&$message!=""){
				$m = new MailData();
				$m->open();
			    $m->mail->Subject = $subject;
			    $m->message = "<p>$message</p>";
			    $m->mail->IsHTML(true);
			//    $m->send();
			}
//////////////////







////////////

		}
			unset($_SESSION["cot"]);
			unset($_SESSION["cotization_id"]);
			unset($_SESSION["selected_client_id"]);
			unset($_SESSION["selected_price"]);
			setcookie("selled","selled");////////////////////


			print "<script>setTimeout(function(){ w = window.open('cotization.php?id=$s[1]&cliente=$sell->person_id&sucursal=$sell->stock_to_id','ticket','height=720,width=1280'); window.location.reload();  w.print(); w.print();  }, 100);  </script>";
		}    
	}
}



?>