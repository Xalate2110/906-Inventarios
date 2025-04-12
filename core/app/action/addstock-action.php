<?php

if(count($_POST)>0){
	$user = new StockData();
	$user->code = $_POST["code"]; 
	$user->cp = $_POST["cp"];
	$user->name = $_POST["name"];
	$user->ciudad = $_POST["ciudad"];
    $user->colonia = $_POST["colonia"];
	$user->address = $_POST["address"]; 
	$user->phone = $_POST["phone"]; 
	$user->email = $_POST["email"];  
    

    if(isset($_FILES["image"])){
        $image = new Upload($_FILES["image"]);
        if($image->uploaded){
        $image->Process("storage/stocks/");
      if($image->processed){
       $user->image = $image->file_dst_name;
        }
       }
      }

   
	$user->add();
	Core::alert("Se ha registrado el stock en el sistema, por lo cual puedes asignar inventario");
    print "<script>window.location='index.php?view=stocks';</script>";


}


?>