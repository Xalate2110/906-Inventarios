<?php

if(isset($_GET['opt']) && $_GET['opt']=="open"){

$box = new BoxData();
$box->user_id = $_SESSION['user_id'];
$box->stock_id = StockData::getPrincipal()->id;
$box->amount = $_POST["amount"];
$box->add();

Core::redir("./?view=sellnew");

}
else if(isset($_GET['opt']) && $_GET['opt']=="close"){


$box = BoxData::getLastOpenByUser($_SESSION["user_id"]);
$box->amount_final = $_POST["amount_final"];
$box->closebox();

Core::redir("./?view=sellnew");

}

?>