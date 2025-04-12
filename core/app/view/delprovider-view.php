<?php

$client = PersonData::getById($_GET["id"]);
$stock = $_GET["stock"];
$client->del();
Core::alert("Se ha elimado correctamente el proveedor del sistema.");
print "<script>window.location='index.php?view=providers&opt=all&stock=$stock';</script>";
?>