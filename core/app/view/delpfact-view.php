<?php

include '/connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");

$producto = $_GET['producto'];
$id_venta   = $_GET['id'];

$sq1 = "update operation set facturado = 0,status_p = 0 where sell_id ='" . $id_venta . "' and product_id = $producto";
$mysqli->query($sq1); 


Core::alert("Se ha quitado el producto para poder facturar parcialmente");
Core::redir("./?view=onesell&id=".$_GET["id"]);

?>