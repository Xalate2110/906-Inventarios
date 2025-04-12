<?php

include '../../../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");

$idcliente = $_POST["idcliente"];

$sql = "SELECT 
    a.idcliente,
    CONCAT(p.name, ' ', p.lastname) AS nombre,
    a.cantidad,
    CASE
        WHEN operacion = 1 THEN 'ABONO'
        WHEN operacion = 2 THEN 'DESCUENTO'
        WHEN operacion = 3 THEN 'COMPRA'
    END AS estado,
    a.fecha
FROM
    bitacora_abonos a
        LEFT JOIN
    person p ON p.id = a.idcliente
WHERE
    a.idcliente = '" . $idcliente."'";

//echo $sql;
$resultado = $mysqli->query($sql);
$i = 0;

$cont = $resultado->num_rows;

echo '{"data":[';
while ($row = $resultado->fetch_array(MYSQLI_NUM)) {

    if ($row[3] == '') {
        $cadena = "$0";
    } else {
        $cadena = "$" . $row[3];
    }

    echo '[';
    echo '"' . $row[0] . '",';
    echo '"' . $row[1] . '",';
    echo '"' . $row[2] . '",';
    echo '"' . $row[3]. '",';
    echo '"' . $row[4]. '",';

    echo '"-"';

    if ($i < $cont - 1) {
        echo '],';
    } else {
        echo ']';
    }

    $i++;
}

echo ']}';
?>