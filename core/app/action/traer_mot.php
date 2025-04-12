<?php

include '../../../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
date_default_timezone_set('America/Mexico_City');

$id = $_POST["id"];

$sql_conceptos = "SELECT cancelacion, motivo  FROM sell WHERE id ='" . $id . "'";
$resultSet = $mysqli->query($sql_conceptos);
$fila = $resultSet->fetch_assoc();

$rowcount = mysqli_num_rows($resultSet);

if ($rowcount > 0) {
    echo $fila["motivo"];
} else {
    echo "";
}





