<?php

if(count($_POST)>0){
	$user = PersonData::getById($_POST["user_id"]);
	//$user->no = $_POST["no"];
	$user->name = $_POST["name"];
	//$user->lastname = $_POST["lastname"];
	$user->vendedor = $_POST["vendedor"];
	//$user->address1 = $_POST["address1"];
	$user->email1 = $_POST["email1"];
	$user->phone1 = $_POST["phone1"];
	$user->phone2 = $_POST["phone2"];
	$user->credit_limit = $_POST["credit_limit"];
	$user->has_credit = isset($_POST["has_credit"]) ? 1 : 0;
	//$user->stock_id = $_POST["stock_id"];

    $user->update_provider();

    Core::alert("Se ha actualizado la informacion del proveedor correctamente.");
	//print "<script>window.location='index.php?view=providers&stock=$user->stock_id';</script>";
	print "<script>window.location='index.php?view=providers';</script>";



}


?>