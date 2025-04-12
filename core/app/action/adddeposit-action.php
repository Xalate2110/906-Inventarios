<?php

if(count($_POST)>0){

$box = BoxData::getLastOpenByUser($_SESSION["user_id"]);


	$user = new SpendData();
	$user->kind=3;
	$user->name = $_POST["name"];
	$user->price = $_POST["price"];
	$user->box_id = $box->id; 
	$user->add();

print "<script>window.location='index.php?view=deposits';</script>";


}


?>