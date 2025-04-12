<?php
if(count($_POST)>0){
	$user = new RazonData();
	$user->name = $_POST["name"];
	$user->rfc = $_POST["rfc"];
	$user->cp = $_POST["cp"];
	$user->regimen_fiscal = $_POST["regimen_fiscal"];
	$user->ciudad = $_POST["ciudad"];
    $user->colonia = $_POST["colonia"];
	$user->address = $_POST["address"]; 
    $user->sf = $_POST["sf"]; 

	if(isset($_FILES["image"])){
        $image = new Upload($_FILES["image"]);
        if($image->uploaded){
        $image->Process("storage/razones_sociales/");
      if($image->processed){
       $user->image = $image->file_dst_name;
        }
       }
      }

	$user->add();
	Core::alert("Se ha registrado la razón social en el sistema, por lo cual ya podras generar facturas");
    print "<script>window.location='index.php?view=razonessociales';</script>";


}


?>