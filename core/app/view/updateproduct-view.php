<?php
error_reporting(0);
if(count($_POST)>0){
	$product = ProductData::getById($_POST["product_id"]);

    
    $stock->stock = $_POST["stock"];
    $product->code = $_POST["code"];
	$product->barcode = $_POST["barcode"];
    $product->name = $_POST["name"];
    $product->multiplo = $_POST["multiplo"];
    $product->price_in = $_POST["price_in"];
    $product->price_out = $_POST["price_out"];
    $product->price_out2 = $_POST["price_out2"];
    $product->price_out3 = $_POST["price_out3"];
    $product->price_out4 = $_POST["price_out4"];
  

    $product->unit = $_POST["unit"];
    $product->codigo_sat = $_POST["codigo_sat"];
    $product->objetoimp = $_POST["objetoimp"];
   
 
    $product->description = $_POST["description"] != "" ? $_POST["description"] : "NULL";
    $product->presentation = $_POST["presentation"] != "" ? $_POST["presentation"] : "NULL";
    $product->inventary_min = $_POST["inventary_min"];
    //$product->expire_at = $_POST["expire_at"];
  


    $product->width = 0; //$_POST["width"];
    $product->height = 0;//$_POST["height"];
    $product->weight = 0; //$_POST["weight"];

    $product->brand_id = $_POST["brand_id"] != "" ? $_POST["brand_id"] : "NULL";
    $product->category_id = $_POST["category_id"] != "" ? $_POST["category_id"] : "NULL";
    $product->inventary_min = $_POST["inventary_min"] != "" ? $_POST["inventary_min"] : "10";


    $product->user_id = $_SESSION["user_id"];
    $product->is_active = isset($_POST["is_active"]) ? 1 : 0;

	$product->update();

	if(isset($_FILES["image"])){
		$image = new Upload($_FILES["image"]);
		if($image->uploaded){
			$image->Process("storage/products/");
			if($image->processed){
				$product->image = $image->file_dst_name;
				$product->update_image();
			}
		}
	}

	setcookie("prdupd","true");

if(isset($_GET["opt"]) && $_GET["opt"]=="service"){
Core::alert("Se ha actualizado la información del producto / servicio correctamente en el sistema");
print "<script>window.location='index.php?view=services&opt=all&stock=$stock->stock ';</script>";

}else{
Core::alert("Se ha actualizado la información del producto / servicio correctamente en el sistema");
print "<script>window.location='index.php?view=products&opt=all&stock=$stock->stock ';</script>";

}

}




?>