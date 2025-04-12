<?php
include '../../../connection/conexion.php';

session_start();
if (isset($_SESSION['id_almacen'])) {
$id_almacen = $_SESSION['id_almacen'];}




$mysqli->query("SET NAMES 'UTF8'");
$sql = "SELECT CONCAT(nombre_cliente),monto,folio,folio_venta,uuid,fecha_registro,timbrado,error_timbrado FROM cfdis order by Fecha_registro ASC";
//echo $sql;
$resultado = $mysqli->query($sql);
$i = 0;
$cont = $resultado->num_rows;
echo '{"data":[';
while ($row = $resultado->fetch_array(MYSQLI_NUM)) {

  


    echo '[';
    echo '"' . $row[0] . '",';
    echo '"' .'$  '. number_format($row[1],2,'.',',') . '",';
    echo '"' . $row[2] . '",';
    echo '"' . $row[3] . '",';
    echo '"' . $row[4] . '",';
    echo '"' . $row[5] . '",';
    echo '"' . $row[6] . '",';
    echo '"' . $row[7] . '",';

   
 
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