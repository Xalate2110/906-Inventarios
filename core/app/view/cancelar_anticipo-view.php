<?php

include '/connection/conexion.php';
$id = $_GET['idanticipo'];

$cancela_anticipo = "DELETE FROM bitacora_abonos where idabonos = '".$id."'";
$mysqli->query($cancela_anticipo);
?>

<?php header("Location: index.php?view=mov_anticipos_clientes"); ?>
