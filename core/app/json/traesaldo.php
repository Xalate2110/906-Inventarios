<?php

include '../../../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");

$idcliente = $_POST["idcliente"];

$sql = "SELECT 
    sum(cantidad) as total
FROM
    bitacora_abonos  where idcliente='" . $idcliente . "' AND operacion = 2";
    

$resultado = $mysqli->query($sql);
$row = $resultado->fetch_assoc();
$cont = $resultado->num_rows;

if ($row > 0) {
    echo $row['total'];
} else {
    echo 0;
}
?>