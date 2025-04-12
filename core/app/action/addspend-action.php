<?php

if(count($_POST)>0){

	$user = new SpendData();
	$user->name = $_POST["name"];
	$user->price = $_POST["price"];
	$user->kind = $_POST["kind"];
	$user->stock_id = $_POST["stock_id"];
	$user->user_id = $_POST["user_id"];	
	$user->fecha_mov = $_POST["date_at"];	
    $user->add();

	Core::alert("Se ha registrado el movimiento en el sistema.");
    print "<script>window.location='index.php?view=spends';</script>";


}


?>

