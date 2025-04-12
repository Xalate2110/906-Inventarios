<?php
if(isset($_GET["opt"]) && $_GET["opt"]=="add"){
	$user = new BrandData();
	$user->name = $_POST["name"];
    
	include '/connection/conexion.php';
    $sql2 = "SELECT * FROM BRAND WHERE name='$user->name'";
    $result=$mysqli->query($sql2); 
    $row_cnt = $result->num_rows;
    if($row_cnt>0){
    Core::alert("No puede registrar la marca, debido a que ya se cuenta con un registro igual actualmente en el sistema.");
	Core::redir("./index.php?view=brands&opt=all");
    }else{
	$user->add();
	Core::alert("Se ha registado la marca correctamente en el sistema.");
	Core::redir("./index.php?view=brands&opt=all");
    }
	
   }
	else if(isset($_GET["opt"]) && $_GET["opt"]=="upd"){
		$user = BrandData::getById($_POST["user_id"]);
		$user->name = $_POST["name"];
		$user->update();
		Core::redir("./index.php?view=brands&opt=all");

	}
	else if(isset($_GET["opt"]) && $_GET["opt"]=="del"){
	$category = BrandData::getById($_GET["id"]);
	$products = ProductData::getAllByCategoryId($category->id);
	foreach ($products as $product) {
		$product->del_brand();
	}

	$category->del();
	Core::redir("./index.php?view=brands&opt=all");
	}


	?>