<?php

$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId2($_GET["id"]);
$operations_from = OperationData::getAllProductsBySellId2($sell->sell_from_id);

$id_dev = $_GET["id"]; // Es devolución.
$id_venta = $_GET["origen"];  

foreach ($operations as $op) {
	foreach($operations_from as $opf){
	if($opf->product_id==$op->product_id){
	$opf->q += $op->q;
	$opf->update_q2();
	break;
		} 
	} 
}  

include '/connection/conexion.php';
// vamos a selecionar la venta
$sql_dev = "SELECT q,price_out FROM operation WHERE sell_id = $id_dev ";
$resultado = $mysqli->query($sql_dev);

$total_dev=0;
$q=0;
$price_out=0;
while($row = $resultado->fetch_assoc()){
$q= $row['q'];
$price_out = $row['price_out'];
$total_dev += ($q*$price_out);}







$sql_ven = "SELECT total,p_id FROM sell WHERE id = $id_venta ";
$resultSet_ven = $mysqli->query($sql_ven);
$fila2 = $resultSet_ven->fetch_assoc();
$credito = $fila2['p_id'];
$total_ven = $fila2['total'];

$operacion = ($total_ven + $total_dev);

$total = round($operacion,2);
$subtotal = round($total / 1.16,2);
$iva = round($subtotal * 0.16,2);

$update1 = "UPDATE SELL SET total = '$total', sub_total = '$subtotal', iva = '$iva' WHERE id = $id_venta ";
$mysqli->query($update1);

if ($credito == "4"){
$update2 = "UPDATE PAYMENT SET val = '$total' WHERE sell_id = $id_venta and payment_type_id = 1";

$mysqli->query($update2);}


$sell->cancel();
Core::alert("Se ha aprovado el regreso de la devolución al sistema, el sistema recalculara el total de la venta y credito en el caso de exista y así mismo devolvera la existencia de regreso");
Core::redir("./index.php?view=devs");

	

?>