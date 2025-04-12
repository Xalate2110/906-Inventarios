<?php
            $operation_type="salida-pendiente";
			$product = ProductData::getById($_POST["product_id"]);
			$d=OperationData::getDByStock($_POST["product_id"],StockData::getPrincipal()->id);

			 $op = new OperationData();
			 $op->product_id = $_POST["product_id"] ;
			 $op->price_in = $product->price_in;
			 $op->price_out = $product->price_out;
			 $op->descripcion = "";
			 $op->operation_type_id=OperationTypeData::getByName($operation_type)->id;
			 $op->stock_id = StockData::getPrincipal()->id;
			 $op->sell_id=$_POST['cot_id'];
			 $op->q= $_POST["q"];
			 $add = $op->add_cotization();
			Core::alert("Se ha registrado el producto en la cotización de forma correcta");
			print "<script>window.location='index.php?view=onecotization&id=$_POST[cot_id]';</script>";

		?>