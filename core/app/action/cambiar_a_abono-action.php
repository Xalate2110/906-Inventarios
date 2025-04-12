<?php

include '/connection/conexion.php';
$id = $_GET['idanticipo'];

$actualiza_abono = "UPDATE bitacora_abonos set operacion = '2' where idabonos = '".$id."'";
$mysqli->query($actualiza_abono);
?>

<?php header("Location: index.php?view=abonos"); ?>
