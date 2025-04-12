<?php

$client = PersonData::getById($_GET["id"]);
$stock = $_GET["stock"];
$client->del();

Core::alert("Se ha elimado correctamente el cliente del sistema.");
print "<script>window.location='index.php?view=clients&opt=all&stock=$stock';</script>";

?>