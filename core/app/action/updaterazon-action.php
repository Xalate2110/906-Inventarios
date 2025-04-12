<?php

if(count($_POST)>0){
	$user = RazonData::getById($_POST["id"]);
	$user->name = $_POST["name"];
	$user->rfc = $_POST["rfc"];
	$user->cp = $_POST["cp"];
	$user->regimen_fiscal = $_POST["regimen_fiscal"];
	$user->ciudad = $_POST["ciudad"];
    $user->colonia = $_POST["colonia"];
	$user->address = $_POST["address"]; 
	$user->sf = $_POST["sf"]; 

	$user->update_razon();

	if(isset($_FILES["image"])){
		$image = new Upload($_FILES["image"]);
		if($image->uploaded){
			$image->Process("storage/razones_sociales/");
			if($image->processed){
				$user->image = $image->file_dst_name;
				$user->update_image();
			}
		}
	}




	Core::alert("Se ha actualizado la razón social en el sistema");
    print "<script>window.location='index.php?view=razonessociales';</script>";
 }?>

 