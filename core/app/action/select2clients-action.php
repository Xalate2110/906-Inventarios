<?php

$id_almacen =  StockData::getPrincipal()->id;
$products = PersonData::getLike($_GET["term"]["term"],$id_almacen);

$array_data = array();
foreach($products as $pro){
	//  $q=OperationData::getQByStock($pro->id,StockData::getPrincipal()->id);
	$array_data[] = array("id"=>$pro->id, "name"=>" ".utf8_encode($pro->name." "."  "."$pro->lastname"));
}


echo json_encode($array_data);

?>

