<?php
include '/connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");

$pago = $_POST["val"];
$idventa = $_POST["sell_id"];
$total = round($_POST["total"],2);
$formapago = $_POST["id_formapago"];
$cliente_id = $_POST["client_id"];
$monto = $_POST["val"];
$stock_id = $_POST["stock_id"];



if(count($_POST)>0){
      
	$payment2 = new PaymentData();
 	$payment2->val = -1*$_POST["val"];
 	$payment2->sell_id = $_POST["sell_id"];
 	$payment2->person_id = $_POST["client_id"];   
	$payment2->id_formapago = $_POST['id_formapago'];
	$payment2->total = round($_POST['total'],2);
	$payment2->stock_id = $_POST['stock_id'];
	$payment2->myuid = uniqid('A - '); 
	$payment2->add_payment();

       if($pago == $total){
    	$sql = "update sell set p_id = 1 where id ='" . $idventa . "'" ;
		$mysqli->query($sql);
		$sq2 = "update sell set credito_liquidado = 1 where id ='" . $idventa . "'";
		$mysqli->query($sq2); 
		$sq3 = "update sell set fecha_pago = NOW() where id ='" . $idventa . "'";
		$mysqli->query($sq3);  
        $sq4 = "update payment set liquidado = 1 where sell_id ='" . $idventa . "'";
		$mysqli->query($sq4); 
    }


       if($payment2->id_formapago == '5' ){

		$ajusta_anticipo = "update c_abonos set cantidad_actual = cantidad_actual - $monto  where idcliente ='" . $cliente_id . "' and payment_type_id = 2 ";
		$mysqli->query($ajusta_anticipo);

		$insert_registro = "INSERT INTO bitacora_abonos (idcliente,nombre_cliente,cantidad,forma_pago,banco_deposito,referencia_deposito,folio_deposito,operacion,descuento_aplicado,factura_electronica,stock_id,fecha) 
		VALUES ($cliente_id,'X',$monto,$formapago,'X','X','X','4','X',$idventa,$stock_id,NOW()) ";
		$mysqli->query($insert_registro);


    	/*$descuenta_anticipo = "update bitacora_abonos set cantidad = cantidad - $monto  where idabonos ='" . $idabono . "' and idcliente ='" . $cliente_id . "'";
		$mysqli->query($descuenta_anticipo); */
		
		}
		Core::alert("Se ha liquidado el crédito del cliente, correctame.");
       print "<script>window.location='index.php?view=sellscredit';</script>";
		}
		?>