<?php

include '/connection/conexion.php';
$id = $_GET['idanticipo'];

$actualiza_antcipo = "UPDATE bitacora_abonos set operacion = '1' where idabonos = '".$id."'";
$mysqli->query($actualiza_antcipo);
?>

<?php header("Location: index.php?view=abonos"); ?>
