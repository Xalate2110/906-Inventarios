<?php

include '../../../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
date_default_timezone_set('America/Mexico_City');


$clave = $_POST["pass"];
$usuario = $_POST["usuario"];
$motivo = $_POST["motivo"];
$id = $_POST["idcancela"];
$clave = md5($clave);

$sql_conceptos = "SELECT * FROM clave_maestra WHERE clave_maestra ='" . $clave . "' and p_traspaso = '1' ";
$resultSet = $mysqli->query($sql_conceptos);
$rowcount = mysqli_num_rows($resultSet);

if ($rowcount > 0) {

    $sql_conceptos2 = "update operation set status = 0 where sell_id = '" . $id . "'";
    $mysqli->query($sql_conceptos2);
    
    $sql_conceptos = "update sell set p_id = 3, d_id = 3, motivo= '" . $motivo . "',usuario_cancelo= '" . $usuario . "',cancelacion = NOW() where id = '" . $id . "'";

    if ($resultSet = $mysqli->query($sql_conceptos)) {
        echo "1";
    } else {
        $sql_conceptos = "update sell set p_id = 3, d_id = 3, motivo= '" . $motivo . "', usuario_cancelo= '" . $usuario . "',cancelacion = NOW() where id ='" . $id . "'";
        if ($resultSet = $mysqli->query($sql_conceptos)) {
            echo "2";
        }
    }
} else {
    echo "0";
}





