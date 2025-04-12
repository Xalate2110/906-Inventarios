<?php
if(isset($_GET["opt"]) && $_GET["opt"]=="add"){
	$user = new CategoryData();
	$user->name = $_POST["name"];
    
    include '/connection/conexion.php';
    $sql2 = "SELECT * FROM CATEGORY WHERE name='$user->name'";
    $result=$mysqli->query($sql2); 
    $row_cnt = $result->num_rows;
    if($row_cnt>0){
    Core::alert("No puede registrar la categoria, debido a que ya se cuenta con un registro igual actualmente en el sistema.");
    Core::redir("./index.php?view=categories&opt=all");
    }else {
	$user->add();}
	Core::redir("./index.php?view=categories&opt=all");
    Core::alert("Se ha registado la categoria correctamente en el sistema.");
    }
    else if(isset($_GET["opt"]) && $_GET["opt"]=="upd"){
	$user = CategoryData::getById($_POST["user_id"]);
	$user->name = $_POST["name"];
	$user->update();
	Core::alert("Se ha Actualizado la categoria correctamente en el sistema.");
	Core::redir("./index.php?view=categories&opt=all");
    }
else if(isset($_GET["opt"]) && $_GET["opt"]=="del"){
$category = CategoryData::getById($_GET["id"]);

$products = ProductData::getAllByCategoryId($category->id);
foreach ($products as $product) {
	$product->del_category();
}

$category->del();
Core::redir("./index.php?view=categories&opt=all");
Core::alert("Se ha Eliminado la categoria correctamente en el sistema.");
}


?>