<?php

$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);

foreach ($operations as $op) {
	$op->del();
}

$sell->del();
Core::alert("Se realizado la eliminación de la cotización correctamente.");
Core::redir("./index.php?view=cotizations");

?>