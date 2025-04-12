<?php
print_r($_FILES);
if(count($_POST)>0){
	$user = StockData::getById($_POST["id"]);
	$user->code = $_POST["code"]; // ok
	$user->cp = $_POST["cp"];
	$user->name = $_POST["name"]; // ok
	$user->ciudad = $_POST["ciudad"];
    $user->colonia = $_POST["colonia"];
	$user->address = $_POST["address"]; // ok 
	$user->phone = $_POST["phone"]; // ok 
	$user->email = $_POST["email"]; // ok 
    $user->update();

	 

	if(isset($_FILES["image"])){
		$image = new Upload($_FILES["image"]);
		if($image->uploaded){
			$image->Process("storage/stocks/");
			if($image->processed){
				$user->image = $image->file_dst_name;
				$user->update_image();
			}
		}
	}




	Core::alert("Se ha registrado el stock en el sistema, por lo cual ya podras generar facturas");
    print "<script>window.location='index.php?view=stocks';</script>";
 }


?>