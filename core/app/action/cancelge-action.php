<?php
include '/connection/conexion.php';

$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);

$id = $_GET['id'];
$usuario = $_GET['usuario'];

$usuario_cancela = "update sell set usuario_cancelo = '".$usuario."' where id = '" . $id . "'";
$mysqli->query($usuario_cancela);

echo'<script type="text/javascript">
	alert("SE HA CANCELADO EL AJUSTE DE INVENTARIO GENERAL CORRECTAMENTE. VALIDA QUE LOS PRODUCTOS REGRESEN A SU ALMACEN DE ORIGEN");
	</script>';
foreach ($operations as $op) {
	$op->cancel();
}

$sell->cancel();
Core::redir("./index.php?view=ajustes-generales");

?>