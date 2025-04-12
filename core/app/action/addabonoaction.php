<?php
	
	
	

    $id = $_POST["id_opcion"];
	$id_cliente = $_POST["client_p"];
	$nombre_cliente = $_POST["nombre_cliente"];
	$cantidad = $_POST["cantidad"];
	$forma_pago = $_POST["forma_pago"];
	$RfcEmisorCtaOrd = $_POST["RfcEmisorCtaOrd"];
	$NomBancoOrdExt = $_POST["NomBancoOrdExt"];
	$CtaOrdenante = $_POST["CtaOrdenante"];
	$RfcEmisorCtaBen = $_POST["RfcEmisorCtaBen"];
	$CtaBeneficiario = $_POST["CtaBeneficiario"];
	$referencia_deposito = $_POST["referencia_deposito"];
	$stock_id = $_POST["stock_id"];
	$myuid = uniqid('M -');


	$sql_cliente = "SELECT * FROM person WHERE id =" . $idcliente;
	$mysqli->query($sql_cliente);
    while($row = $query->fetch_assoc()){
    $id = $row['id'];
	utf8_encode($nombre_cliente = $row['name']);
	}

  
	if($id== '1'){
		$sql = "INSERT INTO bitacora_abonos(idcliente,nombre_cliente,cantidad,cant_ingresada,forma_pago,RfcEmisorCtaOrd,NomBancoOrdExt,CtaOrdenante,RfcEmisorCtaBen,CtaBeneficiario,referencia_deposito,folio_deposito,operacion,descuento_aplicado,factura_electronica,stock_id,fecha,facturado) 
		VALUES ('$idcliente','$nombre_cliente','$cantidad','$cantidad','$forma_pago','$RfcEmisorCtaOrd','$NomBancoOrdExt','$CtaOrdenante','$RfcEmisorCtaBen','$CtaBeneficiario','$referencia_deposito',0,1,0,'$myuid','$stock_id',NOW(),0)";
		    $mysqli->query($sql);
	}else if ($id == '2'){
		$sql = "INSERT INTO bitacora_abonos(idcliente,nombre_cliente,cantidad,cant_ingresada,forma_pago,RfcEmisorCtaOrd,NomBancoOrdExt,CtaOrdenante,RfcEmisorCtaBen,CtaBeneficiario,referencia_deposito,folio_deposito,operacion,descuento_aplicado,factura_electronica,stock_id,fecha,facturado) 
	VALUES ('$idcliente','$nombre_cliente','$cantidad','$cantidad','$forma_pago','$RfcEmisorCtaOrd','$NomBancoOrdExt','$CtaOrdenante','$RfcEmisorCtaBen','$CtaBeneficiario','$referencia_deposito',0,2,0,'$myuid','$stock_id',NOW(),0)";
	    $mysqli->query($sql);}

    print "<script>window.location='index.php?view=abonos';</script>";





?>