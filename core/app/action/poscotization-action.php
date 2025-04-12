<?php
$sell = SellData::getById($_GET["id"]);
$product = ProductData::getById($_GET["idpro"]);
$operations = OperationData::getAllProductsBySellId($sell->id);
$_SESSION['cotization_id']=$_GET['id'];
	//print_r($sell); 

unset($_SESSION['cart']);

$_SESSION["cart"] = array();

$cart = array();

foreach($operations as $op){
	$cart[] = array("product_id"=>$op->product_id,"descripcion"=>$product->name,"q"=>$op->q,"discount"=>0,"price"=>$op->price_out,"use_price2"=>0,"price_opt"=>0,"descuento_p"=>$op->descuento_p);
}

if($sell->person_id){ 
//	$_SESSION['selected_client_id'];
	$_SESSION['selected_client_id']=$sell->person_id;
//	$cli = PersonData::getById($_GET["client_id"]);
	$_SESSION['selected_price']=1;
}
$_SESSION['cart'] = $cart;

Core::redir("./?view=sellnew");
?>