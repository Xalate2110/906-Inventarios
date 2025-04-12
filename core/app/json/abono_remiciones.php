<?php
date_default_timezone_set("America/Mexico_City");
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

$nuevosaldo = $saldo - $cant_anticipo;
$por_liquidar = $total - $cant_anticipo;
$myuid = uniqid('A-'); 

// se actualiza el monto total de los disponible de todos los anticipos del cliente.
//$actualiza_total = "update c_abonos set cantidad_actual = '" . $nuevosaldo . "' where idcliente='" . $idcliente . "'";
//$mysqli->query($actualiza_total);

// se actualiza la cantidad nueva del anticipo elegido para liquidar las remisiones.
$actualiza_anticipo = "update bitacora_abonos set cantidad = cantidad -  '" . $cant_anticipo . "' where idcliente= '" . $idcliente . "' and idabonos = '" . $idanticipo . "' ";
$mysqli->query($actualiza_anticipo);

$inserta_registro = "INSERT INTO bitacora_pagos_anticipo (id_cliente,remision,uuid_factura,pagado,total_remision,nuevo_saldo,fecha_operacion,stock_id,id_operacion,operacion,id_anticipo,folio_cp,serie_cp) 
VALUES ($idcliente,$idventa,'$uuid_factura',$cant_anticipo,$total,$por_liquidar,NOW(),$stock_id,'$myuid',2,$idanticipo,0,0)";
echo $inserta_registro;
$mysqli->query($inserta_registro);

$aplicar_abono = "INSERT INTO payment (payment_type_id,sell_id,person_id,val,id_anticipo,forma_pago,created_at,stock_id,id_pago,liquidado,cancelado) 
VALUES ('2',$idventa,$idcliente,$cant_anticipo * -1 ,$idanticipo,'5',NOW(),$stock_id,'$myuid','0','0')";
echo ($aplicar_abono);
$mysqli->query($aplicar_abono);

echo true;
?>