<?php
		print_r($_POST);

if(isset($_POST["q"]) && !is_numeric($_POST["q"])){
Core::alert("Valor invalido!");
Core::redir("./?view=sell");
}

if(!isset($_SESSION["errors"])){
	$_SESSION['errors'] = array();
	$errors = $_SESSION['errors'];
}
if(!isset($_SESSION["cart"])){


	$price = $_POST['price'];
	

    $px = PriceData::getByPS($_POST["product_id"],StockData::getPrincipal()->id);
    if($px!=null){ 

      $price = $px->price_out; 
      $price2 = $px->price_out2; 
      $price3 = $px->price_out3; 

    }




	if(isset($_SESSION["selected_client_id"])){
		$pro = ProductData::getById($_POST['product_id']);
		if($_SESSION['selected_price']==1){
			$price=$pro->price_out;
			if($px){ $price = $px->price_out; }
		}else if ($_SESSION['selected_price']==2){
			$price=$pro->price_out2;
			if($px){ $price = $px->price_out2; }

		}
		else if ($_SESSION['selected_price']==3){
			$price=$pro->price_out3;
			if($px){ $price = $px->price_out3; }

		}else{
			$price=$pro->price_out;
			if($px){ $price = $px->price_out; }

		}


	}


	$product = array("product_id"=>$_POST["product_id"],"descripcion"=>$_POST["descripcion"],"q"=>$_POST["q"],"discount"=>$_POST["discount"],"price"=>$price,"use_price2"=>0,"price_opt"=>0,"descuento_p"=>$_POST["descuento_p"]);
	$_SESSION["cart"] = array($product);


	$cart = $_SESSION["cart"];

///////////////////////////////////////////////////////////////////
		$num_succ = 0;
		$process=false;
		$errors = array();
		foreach($cart as $c){

			///
			$product = ProductData::getById($c["product_id"]);
			$q = OperationData::getQByStock($c["product_id"],StockData::getPrincipal()->id);
//			echo ">>".$q;
			if($product->kind==2||$c["q"]<=$q){
				$num_succ++;


			}else{
				$error = array("product_id"=>$c["product_id"],"message"=>"No hay suficiente cantidad de producto en inventario.");
				$errors[count($errors)] = $error;
			}

		}
///////////////////////////////////////////////////////////////////

//echo $num_succ;
if($num_succ==count($cart)){
	$process = true;
}
if($process==false){
	unset($_SESSION["cart"]);
$_SESSION["errors"] = $errors;
	?>	
<script>
	window.location="index.php?view=sell";
</script>
<?php
}




}else {

$found = false;
$cart = $_SESSION["cart"];
$index=0;

			$product = ProductData::getById($_POST["product_id"]);
			$q = OperationData::getQByStock($_POST["product_id"],StockData::getPrincipal()->id);





$can = true;
if($product->kind==2||$_POST["q"]<=$q){
}else{
	$error = array("product_id"=>$_POST["product_id"],"message"=>"No hay suficiente cantidad de producto en inventario.");
	$errors[count($errors)] = $error;
	$can=false;
}

if($can==false){
$_SESSION["errors"] = $errors;
	?>	
<script>
	window.location="index.php?view=sell";
</script>
<?php
}
?>

<?php
if($can==true){
foreach($cart as $c){
	if($c["product_id"]==$_POST["product_id"]){
		//echo "found";
		$found=true;
		break;
	}
	$index++;
//	print_r($c);
//	print "<br>";
}

if($found==true){
//	$pro = $cart[$index]["product_id"];
	$qx = OperationData::getQByStock($cart[$index]["product_id"],StockData::getPrincipal()->id);
		$q1 = $cart[$index]["q"];
		$q2 = $_POST["q"];
	if( ($q1+$q2)<=$qx ){
		$cart[$index]["q"]=$q1+$q2;
		$_SESSION["cart"] = $cart;
	}else{
//		echo ":P";
		$error = array("product_id"=>$c["product_id"],"message"=>"No hay suficiente cantidad de producto en inventario.");
		$_SESSION['errors'] = array($error);

	}

}
   print_r($_POST);
if($found==false){
    $nc = count($cart);


    $price = $_POST["price"];

    $px = PriceData::getByPS($_POST["product_id"],StockData::getPrincipal()->id);
    if($px!=null){ 

      $price = $px->price_out; 
      $price2 = $px->price_out2; 
      $price3 = $px->price_out3; 

    }




	if(isset($_SESSION["selected_client_id"])){
		$pro = ProductData::getById($_POST['product_id']);

		if($_SESSION['selected_price']==1){
			$price=$pro->price_out;
			if($px){ $price = $px->price_out; }

		}else if ($_SESSION['selected_price']==2){
			$price=$pro->price_out2;
			if($px){ $price = $px->price_out2; }

		}
		else if ($_SESSION['selected_price']==3){
			$price=$pro->price_out3;
			if($px){ $price = $px->price_out3; }

		}else{
			$price=$pro->price_out;
			if($px){ $price = $px->price_out; }

		}

	}




$use_price2 = isset($_POST['use_price2'])?1:0;
	$product = array("product_id"=>$_POST["product_id"],"descripcion"=>$_POST["descripcion"],"q"=>$_POST["q"],"discount"=>$_POST["discount"],"price"=>$price,"use_price2"=>0,"price_opt"=>$_POST["price_opt"],"descuento_p"=>$_POST["descuento_p"]);
	$cart[$nc] = $product;
	print_r($cart);
	$_SESSION["cart"] = $cart;
}

}
}
// print "<script>window.location='index.php?view=sell';</script>";
// unset($_SESSION["cart"]);

?>