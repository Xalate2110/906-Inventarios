<?php

include '../../../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");

$idcliente = $_POST["idcliente"];
$total = $_POST["total"];
$saldo = $_POST["saldo"];
$idventa = $_POST["idventa"];
$stock_id = $_POST["stock_id"];
$idanticipo = $_POST["idanticipo"];

if (isset($_POST["uuid_factura"])) {
$uuid_factura = $_POST["uuid_factura"];} 
if (isset($_POST["cant_anticipo"])) {
$cant_anticipo = $_POST["cant_anticipo"];} 
$myuid = uniqid('L-'); 

$nuevosaldo = $saldo - $total;
$por_liquidar = $total - $total;

//$sql = "update c_abonos set cantidad_actual = '" . $nuevosaldo . "' where idcliente='" . $idcliente . "'";
//$mysqli->query($sql);

$sql2 = "update sell set p_id = 1 where  id ='" . $idventa . "'";
$mysqli->query($sql2);

$sql3 = "update sell set credito_liquidado = 1 where  id ='" . $idventa . "'";
$mysqli->query($sql3);

$sql4 = "update sell set f_id = 5 where  id ='" . $idventa . "'";
$mysqli->query($sql4);

$sql5 = "update sell set fecha_pago = NOW() where  id ='" . $idventa . "'";
$mysqli->query($sql5);

$sql6 = "update payment set liquidado = 1 where  sell_id ='" . $idventa . "'";
$mysqli->query($sql6);

$sql7 = "update bitacora_abonos set cantidad = cantidad -  '" . $total . "' where idcliente= '" . $idcliente . "' and idabonos = '" . $idanticipo . "' ";
$mysqli->query($sql7);


$inserta_registro = "INSERT INTO bitacora_pagos_anticipo (id_cliente,remision,uuid_factura,pagado,total_remision,nuevo_saldo,fecha_operacion,stock_id,id_operacion,operacion,id_anticipo,folio_cp,serie_cp) 
VALUES ($idcliente,$idventa,'$uuid_factura',$total,$total,$por_liquidar,NOW(),$stock_id,'$myuid',1,$idanticipo,0,0)";
echo ($inserta_registro);
$mysqli->query($inserta_registro);

echo true;
?>