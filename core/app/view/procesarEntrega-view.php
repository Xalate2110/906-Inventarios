<?php
	$sellID = $_POST['ventaid'];
	$precio = $_POST['precio'];
	$total_venta = $_POST['total_venta'];
	$usuario = $_POST['usuario'];
	$myuid = uniqid('T-'); 

	
    foreach ($sellID as $id => $cantidad) {
		    $sell_id = $id;
	}
	$entregado = $_POST['entregado'];
	foreach ($entregado as $product_id => $cantidad) {
		$total = 0;
		$operations = OperationData::getAllBySellIdAndProduct($sell_id,$product_id);
		foreach ($operations as $operation) {
			$products = ProductData::getById($product_id);
			
			//echo "<br> ". $saldo_por_producto;
			//echo "<br> ". $products->name;
            //$cantidad es la cantidad que entregamos de forma parcial.			
			//echo " Cantidad Vendida "; // calcular cantidad pendiente
			//echo $operation->q;        // asignacion de la cantidad pendiente
			//echo "-- Cantidad Pendiente ";
				 $pendiente = ($operation->q) - (SellData::getDelivered($sell_id,$product_id)->entregada);
				 $pendiente = (int)$pendiente;
				
				 $precio_salida = $operation->price_out;
				 // aqui se envia la cantidad entregada x producto en dinero.
				 $total_entregado = $cantidad * $operation->price_out;

				 // se saca el total por producto se su venta total
                 $total_venta_producto = $operation->q * $operation->price_out;
				 // se hace la operacion para sacar el saldo por producto
			
				 include '/connection/conexion.php';
				 $sql = "SELECT SUM(total_entregado) AS TOTAL FROM sell_to_deliver where sell_id = $sell_id and product_id = $product_id ";
				 $resultado = $mysqli->query($sql);
				 $acumulador=0;
				 while($row=mysqli_fetch_array($resultado)){
				 $total_por_producto = $row[0];
				 $acumulador += $total_por_producto;}

				 $dis_por_producto= $total_venta_producto - $acumulador;
			
			//echo $pendiente;
			//echo "-- Cantidad Por Entregar ";
			    $cantidad = $cantidad;
			
			// Si la cantidad a entregar es menor o igual se crea registro en ventas Por Entregar
			if($cantidad > 0){

				if($cantidad <= $pendiente){
							
		
					SellData::addPending($sell_id,$product_id,$products->code,$operation->descripcion,$cantidad,$total_entregado,$dis_por_producto,$precio_salida, $total_venta_producto,$total_venta,$myuid,$usuario);
				
					SellData::add_historial($sell_id,$product_id,$products->code,$operation->descripcion,$cantidad,$total_entregado,$dis_por_producto,$precio_salida, $total_venta_producto,$total_venta,$myuid,$usuario);
					
					SellData::add_salida($sell_id,$product_id,$products->code,$operation->descripcion,$cantidad,$total_entregado,$dis_por_producto,$precio_salida, $total_venta_producto,$total_venta,$myuid,$usuario);

				}
			}
		}
	}
	print "<script>window.location='index.php?view=onesell&id=$sell_id';</script>";
	
?>