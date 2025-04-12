<?php
//error_reporting(0);
$operations = OperationData::getAllByProductId($_GET["id"]);
$stock->stock = $_GET["stock"];
foreach ($operations as $op) {
	$op->del();
}

$product = ProductData::getById($_GET["id"]);
$product->del();

Core::alert("Se ha eliminado correctamente el producto del sistema");
Core::redir("./index.php?view=products&opt=all&stock=$stock->stock");
?>