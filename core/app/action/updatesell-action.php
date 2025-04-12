<?php

include '/connection/conexion.php';

$sell = SellData::getById($_POST["id"]);
//$sell->invoice_code  =$_POST["invoice_code"];
$sell->person_id=$_POST["client_id"]!=""?$_POST["client_id"]:"NULL";
$sell->f_id = $_POST["f_id"];
$sell->p_id = $_POST["p_id"];
$sell->d_id = $_POST["d_id"];
$total  = $_POST["total"];
$venta= $_POST["idventa"];
$especial = $_POST["estatus"];
$cliente = $_POST['client_id']; 

$sell->comment  =$_POST["comment"];


 if($_POST["d_id"] == '1'){
  $update11 = "UPDATE sell SET d_id = '1' WHERE id = $venta ";
  $mysqli->query($update11);
}else if ($_POST["d_id"] == '2'){
  $update12 = "UPDATE sell SET d_id = '2' WHERE id = $venta ";
  $mysqli->query($update12);

}
 
if($_POST["d_id"] == '1' && $_POST["estatus"] == '14' ){
  $update10 = "UPDATE operation SET operation_type_id = '14' WHERE sell_id = $venta ";
  $mysqli->query($update10);

} else if($_POST["d_id"] == '1'){
  $update4 = "UPDATE operation SET operation_type_id = '2' WHERE sell_id = $venta ";
  $mysqli->query($update4);

}else if($_POST["d_id"] == '2'){
  $update5 = "UPDATE operation SET operation_type_id = '4' WHERE sell_id = $venta ";
  $mysqli->query($update5);

}if($_POST["d_id"] == '2' && $_POST["estatus"] == '14' ){
  $update6 = "UPDATE operation SET operation_type_id = '14' WHERE sell_id = $venta ";
  $mysqli->query($update6);
}

$sql_venta = "SELECT * FROM payment WHERE sell_id = $venta and payment_type_id = 1 ";
$resultSet_venta = $mysqli->query($sql_venta);
$fila = $resultSet_venta->fetch_assoc();

if($fila> 0)
{
if($_POST["p_id"] == '1'){

$delete = "DELETE FROM payment where sell_id = $venta and person_id = $cliente and payment_type_id = 1 ";
$mysqli->query($delete);

$update6 = "UPDATE SELL SET r_credito = '0', p_id = '1' WHERE id = $venta ";
$mysqli->query($update6);}

} else if($_POST["p_id"] == '4'){
$inserta_credito = "INSERT INTO payment (payment_type_id,sell_id,person_id,val,id_anticipo,forma_pago,created_at,stock_id,id_pago,liquidado,cancelado) 
VALUES ('1',$venta,$cliente,$total,'0','0',NOW(),'0','0','0','0') ";
$mysqli->query($inserta_credito);

$update = "UPDATE SELL SET p_pendiente = 'NULL', pendiente = 'NULL', p_id = '4' WHERE id = $venta ";
$mysqli->query($update);

$update4 = "UPDATE SELL SET r_credito = '4' WHERE id = $venta ";
$mysqli->query($update4);


}else if($_POST["p_id"] == '2'){
  $update2 = "UPDATE SELL SET p_id = '2', p_pendiente = '2', pendiente = '1' WHERE id = $venta ";
  $mysqli->query($update2);

  $update7 = "UPDATE SELL SET r_credito = '0' WHERE id = $venta ";
  $mysqli->query($update7);
}
else if($_POST["p_id"] == '1'){
  $update3 = "UPDATE SELL SET p_pendiente = 'NULL', pendiente = 'NULL', p_id = '1' WHERE id = $venta ";
  $mysqli->query($update3);
}

$sell->invoice_file = "";
  if(isset($_FILES["invoice_file"])){
    $image = new Upload($_FILES["invoice_file"]);
    if($image->uploaded){
      $image->Process("storage/invoice_files/");
      if($image->processed){
        $sell->invoice_file = $image->file_dst_name;
      }
    }
  }

$sell->update();
Core::alert("Se ha actualizado la informacion de la venta correctamente.");

Core::redir("./?view=onesell&id=".$_POST["id"]);
?>