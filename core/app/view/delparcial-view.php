<?php

include '/connection/conexion.php';
$id_venta = $_GET['sell_id'];
$sell = SellData::getByIdParcial($_GET["id"]);

$id = $_GET['sell_id'];
$id_trans = $_GET['id_trans'];

$cancela = "delete from operation where ref_sell_id = '".$id."' and id_salida = '1' and id_trans = '".$id_trans."'";
$mysqli->query($cancela);

foreach ($operations as $op) {
	$op->del_parcial();
}
  $sell->del_parcial();
?>

<?php header("Location: index.php?view=onesell&id=$id_venta"); ?>
