<?php

if(count($_POST)>0){
	$user = SpendData::getById($_POST["user_id"]);
	$user->name = $_POST["name"];
	$user->price = $_POST["price"];
	$user->date_at = $_POST["date_at"];
	$user->stock_id = $_POST["stock_id"];
	$user->user_id = $_POST["user_id"];
    $user->update();
	Core::alert("Se ha actualizado el movimiento en el sistema.");
print "<script>window.location='index.php?view=spends';</script>";
}


?>