<?php

include '../../../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");



$sql = "SELECT 
    p.id,
    p.no,
    CONCAT(p.name, ' ', p.lastname) AS nombre,
    a.cantidad_actual
FROM
    person p
        LEFT JOIN
    c_abonos a ON p.id = a.idcliente where p.kind = 1;";

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
    echo '"' . $cadena . '",';

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