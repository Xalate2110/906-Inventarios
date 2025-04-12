<?php

$product = RazonData::getById($_GET["id"]);
$product->del();


Core::redir("./index.php?view=razonessociales");
?>