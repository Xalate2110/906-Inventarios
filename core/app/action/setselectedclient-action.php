<?php


if(isset($_GET["client_id"])){
	$_SESSION['selected_client_id']=$_GET['client_id'];
	$cli = PersonData::getById($_GET["client_id"]);
	$_SESSION['selected_price']=$cli->price;
}

?>