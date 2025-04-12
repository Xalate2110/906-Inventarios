<?php

if(!isset($_SESSION["reabastecer_inv_faltante"])){


	$product = array("product_id"=>$_POST["product_id"],"q"=>$_POST["q"],"price_in"=>$_POST["price_in"],"price_out"=>$_POST["price_out"]);
	
	$_SESSION["reabastecer_inv_faltante"] = array($product);


	$cart = $_SESSION["reabastecer_inv_faltante"];

///////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////

$process=true;


}else {

$found = false;
$cart = $_SESSION["reabastecer_inv_faltante"];
$index=0;

//$q = OperationData::getQYesF($_POST["product_id"]);





$can = true;

?>

<?php
if($can==true){
foreach($cart as $c){
	if($c["product_id"]==$_POST["product_id"]){
		echo "found";
		$found=true;
		break;
	}
	$index++;
//	print_r($c);
//	print "<br>";
}

if($found==true){
	$q1 = $cart[$index]["q"];
	$q2 = $_POST["q"];
	$cart[$index]["q"]=$q1+$q2;
	$_SESSION["reabastecer_inv_faltante"] = $cart;
}

if($found==false){
    $nc = count($cart);
	$product = array("product_id"=>$_POST["product_id"],"q"=>$_POST["q"],"price_in"=>$_POST["price_in"],"price_out"=>$_POST["price_out"]);
	$cart[$nc] = $product;
//	print_r($cart);
	$_SESSION["reabastecer_inv_faltante"] = $cart;
}

}
}
print "<script>window.location='index.php?view=ajuste_inv_faltantes';</script>";
// unset($_SESSION["reabastecer"]);

?>