<?php
$cli = Persondata::getById($_GET["id"]);
$array_data = array();
if($cli!=null){
    $array_data["has_credit"]=$cli->has_credit;
    $array_data["credit_limit"]=$cli->credit_limit;
    $array_data["id"]=$cli->id;
    $array_data["name"]=$cli->name;}

echo json_encode($array_data);
?>