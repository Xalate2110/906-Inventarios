<?php

$products = ProductData::getLike($_GET["term"]["term"]);
//print_r($products);
$array_data = array();
foreach($products as $pro){
$array_data[] = array("id"=>$pro->id, "name"=>" ".$pro->name);
}
echo json_encode($array_data);
?>

