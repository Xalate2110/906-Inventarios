<?php

include '/connection/conexion.php';
$id = $_GET['idanticipo'];

$sql_venta = "SELECT * FROM bitacora_pagos_anticipo WHERE id_anticipo = $id";
$resultSet_venta = $mysqli->query($sql_venta);
$fila = $resultSet_venta->fetch_assoc();

if($fila> 0)
{
echo "<script type='text/javascript'>
alert('No puedes eliminar el abono, ya que cuenta con abonos relacionados a remisiones, valida la remisión antes de realizar el movimiento');
</script>";
} else {
    $cancela_abono = "DELETE FROM bitacora_abonos where idabonos = '".$id."'";
    $mysqli->query($cancela_abono);
}
?>
<?php header("Location: index.php?view=mov_abonos_clientes"); ?>
