<?php

include '../../../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
date_default_timezone_set('America/Mexico_City');

$id = $_POST["id"];

$sql2 = "SELECT sell.person_id,sell.stock_to_id,person.forma_pago,person.uso_comprobante,cfdis.folio,cfdis.regimen_fiscal,cfdis.codigo_postal,cfdis.periodicidad,cfdis.p_mes,cfdis.p_ano,person.tiene_rs,cfdis.error_timbrado from sell 
INNER JOIN cfdis on cfdis.Folio_venta = sell.id
INNER JOIN person on person.id = sell.person_id where sell.id =  '".$id."'";


$resultado = $mysqli->query($sql2);
while($row = $resultado->fetch_assoc()){
echo $cliente =$row['person_id'].",".$stock_id=$row['stock_to_id'].",".$forma_pago=$row['forma_pago'].",".$uso_comprobante=$row['uso_comprobante'].",".$folio_factura=$row['folio'].",".$regimen_fiscal=$row['regimen_fiscal'].",".$codigo_postal=$row['codigo_postal'].",".$periodicidad=$row['periodicidad'].",".$p_mes=$row['p_mes'].",".$p_ano=$row['p_ano'].",".$tiene_rs=$row['tiene_rs'].",".$error_timbrado=$row['error_timbrado'];
}





















