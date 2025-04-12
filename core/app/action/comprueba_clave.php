<?php

include '../../../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
date_default_timezone_set('America/Mexico_City');


$clave = $_POST["pass"];
$usuario = $_POST["usuario"];
$motivo = $_POST["motivo"];
$id = $_POST["idcancela"];
$clave = $_POST["pass"];
$motivo = $_POST["motivo"];


$clave = md5($clave);
if (isset($_POST["total"])) {
 $total = $_POST["total"];} 
if (isset($_POST["f_id"])) {
$f_id = $_POST["f_id"];} 
if (isset($_POST["p_id"])) {
$p_id = $_POST["p_id"];} 
if (isset($_POST["d_id"])) {
$d_id = $_POST["d_id"];} 
if (isset($_POST["cliente_id"])) {
$cliente_id = $_POST["cliente_id"];} 
if (isset($_POST["id_anticipo"])) {
$id_anticipo = $_POST["id_anticipo"];} 
$myuid = uniqid('R-');
if (isset($_POST["stock_id"])) {
$stock = $_POST["stock_id"];}
    

$sql_conceptos = "SELECT * FROM clave_maestra WHERE clave_maestra ='" . $clave . "' and p_ticket = '1' ";
$resultSet = $mysqli->query($sql_conceptos);
$rowcount = mysqli_num_rows($resultSet);

if ($rowcount > 0) {
    if($d_id == '1' || $p_id == '1' || $p_id == '2'){
    $sql_conceptos2 = "update operation set status = 0 where sell_id = '" . $id . "'";
    $mysqli->query($sql_conceptos2);}    
    
    $sql_conceptos = "update sell set p_id = 3, d_id = 3, motivo= '" . $motivo . "',usuario_cancelo= '" . $usuario . "', cancelacion = NOW() where id = '" . $id . "'";

    
    if($p_id == 4){
        $cancela = "update payment set cancelado = 2 where sell_id = '" . $id . "'";
        $mysqli->query($cancela);
    
        $sql_conceptos5 = "update operation set status = 0 where sell_id = '" . $id . "'";
        $mysqli->query($sql_conceptos5);
       } 



      
    if($f_id == 5){
         
      $regresa_anticipo2 = "UPDATE bitacora_abonos SET cantidad = cantidad + $total WHERE idcliente = $cliente_id and idabonos = $id_anticipo";
      $mysqli->query($regresa_anticipo2);   
      
      $inserta_registro = "INSERT INTO bitacora_pagos_anticipo (id_cliente,remision,uuid_factura,pagado,total_remision,nuevo_saldo,fecha_operacion,stock_id,id_operacion,operacion,id_anticipo) 
      VALUES ($cliente_id,$id,'-',$total,$total,0,NOW(),$stock,'$myuid',4,$id_anticipo)";
      $mysqli->query($inserta_registro);
      
      } 
    
    if ($resultSet = $mysqli->query($sql_conceptos)) {
        echo "1";
    } else {
     $sql_conceptos = "update sell set p_id = 3, d_id = 3, motivo= '" . $motivo . "',usuario_cancelo= '" . $usuario . "', cancelacion = NOW() where id ='" . $id . "'";
     if ($resultSet = $mysqli->query($sql_conceptos)) {
     echo "2";
        }
    }
} else {
    echo "0";
}





