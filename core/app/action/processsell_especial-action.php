<?php
date_default_timezone_set("America/Mexico_City");
if (isset($_SESSION["cart"])) {
    $cart = $_SESSION["cart"];
    if (count($cart) > 0) {
/// antes de proceder con lo que sigue vamos a verificar que:
        // haya existencia de productos
        // si se va a facturar la cantidad a facturr debe ser menor o igual al producto facturado en inventario
        $num_succ = 0;
        $process = false;
        $errors = array();
        foreach ($cart as $c) {

            ///
            $product = ProductData::getById($c["product_id"]);
            $q = OperationData::getQByStock($c["product_id"], $_POST["stock_id"]);
            if ($product->kind == 2 || $c["q"] >= $q || $c["q"] <= $q || $c["q"] > $q || $c["q"] < $q || $c["q"] = $q) {

                $num_succ++;
            } else {
                $error = array("product_id" => $c["product_id"], "message" => "No hay suficiente cantidad de producto en inventario.");
                $errors[count($errors)] = $error;
            }
        }

        if ($num_succ == count($cart)) {
            $process = true;
        }

        if ($process == false) {
            $_SESSION["errors"] = $errors;
            ?>	
            <script>
                window.location = "index.php?view=sell";
            </script>
            <?php
        }

        
        //////////////////////////////////
        if ($process == true) {
            $iva_val = 0;
            $x = new XXData();
            $xx = $x->add();
            $sell = new SellData();
            $sell->ref_id = $xx[1];
            $sell->user_id = $_SESSION["user_id"];
            $sell->invoice_code = $_POST["invoice_code"];
            $sell->comment = $_POST["comment"];
            $sell->f_id = $_POST["f_id"];
            $sell->p_id = $_POST["p_id"];
            $sell->d_id = $_POST["d_id"];
            $sell->iva = $_POST["iva"];
            $sell->sub_total = round($_POST["sub_total"],2);
            $sell->cash = round($_POST["money"],2);
            $sell->total = round($_POST["total"],2);
            $sell->discount = $_POST["discount"];
            $sell->monto_comision = $_POST["monto_comision"];
            $sell->anticipo_venta = $_POST["anticipo_venta"];
            $sell->stock_to_id = $_POST["stock_id"];
            $sell->person_id = $_POST["client_id"] != "" ? $_POST["client_id"] : "NULL";
            $sell->is_draft = 0 ;
            $sell->id_anticipo = 0;
            $total_bitacora  = $_POST["total"];
            $sell->nota_credito = $_POST["nota_credito"];
            $sell->r_credito = 0;



            if($_POST['p_id'] == 4 ){
                $sell->r_credito = 4;
              }
  
           

             // SI LA VENTA NO TIENE ANTICIPO, EL VALOR REAL SERA DE 0   
             
             $sell->monto_comision = 0;
             $sell->total_por_pagar = 0;

             $sell->reg_anticipo = 0 ;
             $sell->reg_porpagar = 0 ;
             $sell->pendiente = 0 ;
             $sell->p_pendiente = 0 ;
   
             //Se aplica el cargo del 3% al seleccionar tarjeta de creito o debito.
             if ($_POST["p_id"] == 2 || $_POST["d_id"] == 2) {
             $sell->total_por_pagar = $_POST["total"] - $sell->anticipo_venta = $_POST["anticipo_venta"];
             $sell->reg_anticipo = $_POST["anticipo_venta"];
             $sell->reg_porpagar = $_POST["total"] - $sell->anticipo_venta = $_POST["anticipo_venta"]; 
             $sell->pendiente = 1; 
             $sell->p_pendiente = 2;
            }


            //Se aplica el cargo del 3% al seleccionar tarjeta de creito o debito.
            if ($_POST["f_id"] == 3 || $_POST["f_id"] == 4) {
            $sell->total_comision = ($_POST["monto_comision"] / 100) ; 
            $sell->comision = round($_POST["total"] * $sell->total_comision,2);
            $sell->total = round($_POST["total"] +   $sell->comision,2); 
              }  



                
           //Se llama la funcion SQL para insertar todos los registros. 
             $s = $sell->add();


             include '../../../connection/conexion.php';
             if ($_POST["f_id"] == 5){
 
 
 
               $insert_registro = "INSERT INTO bitacora_abonos (idcliente,nombre_cliente,cantidad,forma_pago,banco_deposito,referencia_deposito,folio_deposito,operacion,descuento_aplicado,factura_electronica,stock_id,fecha) 
                VALUES ('$sell->person_id','X','$total_bitacora','$sell->f_id','0','0','0','3','0','$s[1]','$sell->stock_to_id',NOW()) ";

             
            
                $mysqli->query($insert_registro);
 
              }
 

            /// si es credito....
               if ($_POST["p_id"] == 4) {
                $payment = new PaymentData();
                $payment->sell_id = $s[1];
                $payment->val = round($_POST["total"] + $sell->comision,2);
                $payment->person_id = $_POST["client_id"];
                $payment->add();
            }

             /* SE INGRESAN LOS PRODUCTOS A LA TABLA OPERATION POR EL FOLIO DE VENTA*/

                foreach ($cart as $c) {
                $operation_type = "salida";
                if ($_POST["d_id"] == 2) {
                    $operation_type = "salida-pendiente";
                }

                $product = ProductData::getById($c["product_id"]);
                $price = $c["price"]; // $product->price_out;
                $descripcion = $c["descripcion"]; // $product->price_out;
                //		$px = PriceData::getByPS($product->id,StockData::getPrincipal()->id);
                //		if($px!=null){ $price = $px->price_out; }

                $op = new OperationData();
                $op->price_in = $product->price_in;
                $op->price_out = round($price,2);
                $op->product_id = $c["product_id"];
                $op->descripcion = $descripcion;
                $op->operation_type_id = OperationTypeData::getByName($operation_type)->id;
                $op->stock_id = $_POST["stock_id"];
                $op->sell_id = $s[1];
                $op->q = $c["q"];
                if (isset($_POST["is_oficial"])) {
                    $op->is_oficial = 1;
                }
                 $add = $op->add_especial();


////////////////// generando el mensaje
                $subject = "[" . $s[1] . "] Nueva venta en el inventario";
                $message = "<p>Se ha realizado una venta con Id = " . $s[1] . "</p>";
                $person_th = "";
                $person_td = "";
                $person = null;
                if ($_POST["client_id"] != "") {
                    $person = PersonData::getById($_POST["client_id"]);
                    $person_th = "<td>Cliente</td>";
                    $person_td = "<td>" . $person->name . " " . $person->lastname . "</td>";
                }


                $message .= "<table border='1'><tr>
		<td>Id</td>
		$person_th
		<td>Almacen</td>
		<td>Estado de pago</td>
		<td>Estado de entrega</td>
		<td>Total</td>
		</tr>
<tr>
		<td>" . $s[1] . "</td>
		$person_td
		<td>" . StockData::getById($sell->stock_to_id)->name . "</td>
		<td>" . PData::getById($sell->p_id)->name . "</td>
		<td>" . DData::getById($sell->d_id)->name . "</td>
		<td> $" . number_format($sell->total, 2, ".", ",") . "</td>
		</tr>
		</table>";
                $message .= "<h3 style='color:#333;'>Resumen</h3>";
                $message .= "<table border='1'><thead><th>Id</th><th>Codigo</th><th>Cantidad</th><th>Unidad</th><th>Producto</th><th>P.U</th><th>P. Total</th></thead>";
                foreach ($cart as $c) {
                    $message .= "<tr>";
                    $product = ProductData::getById($c["product_id"]);
                    $message .= "<td>" . $product->id . "</td>";
                    $message .= "<td>" . $product->barcode . "</td>";
                    $message .= "<td>" . $c["q"] . "</td>";
                    $message .= "<td>" . $product->unit . "</td>";
                    $message .= "<td>" . $product->name . "</td>";
                    $message .= "<td>$ " . number_format($product->price_out, 2, ".", ",") . "</td>";
                    $message .= "<td>$ " . number_format($c["q"] * $product->price_out, 2, ".", ",") . "</td>";
                    $message .= "</tr>";
                }
                $message .= "</table>";
//////////////////
                if ($subject != "" && $message != "") {
                    $m = new MailData();
                    $m->open();
                    // enviamos una copia del correo para el cliente
                    if ($person != null) {
                        $m->mail->AddAddress($person->email1);
                    }
                    $m->mail->Subject = $subject;
                    $m->message = "<p>$message</p>";
                    $m->mail->IsHTML(true);
//			    $m->send();
                }
//////////////////




                $qx = OperationData::getQByStock($product->id, $_POST["stock_id"]);
                $subject = "";
                $message = "";
                $last = true;
                if ($qx == 0) {
                    $subject = "[$product->name]" . ' No hay existencias';
                    $message = "Hola, el producto <b>$product->name</b> no tiene existencias en el inventario";
                    $last = false;
                }

                if ($qx <= $product->inventary_min / 2 && $last) {
                    $subject = "[$product->name]" . ' Muy pocas existencias';
                    $message = "Hola, el producto <b>$product->name</b> tiene muy pocas existencias en el inventario";
                    $last = false;
                }
                if ($qx <= $product->inventary_min && $last) {
                    $subject = "[$product->name]" . ' Pocas existencias';
                    $message = "Hola, el producto <b>$product->name</b> tiene pocas existencias en el inventario";
                    $last = false;
                }
//////////////////
                if ($subject != "" && $message != "") {
                    $m = new MailData();
                    $m->open();
                    $m->mail->Subject = $subject;
                    $m->message = "<p>$message</p>";
                    $m->mail->IsHTML(true);
                    //    $m->send();
                }
//////////////////
////////////
            } // SE CIERRA LA VARIABLE FOREACH


            if ($_POST["f_id"] == 3 || $_POST["f_id"] == 4) {   
                $servername = "localhost";
                $database = "db_surtidora_alambrados";
                $username = "root";
                $password = "";
                $conn = mysqli_connect($servername, $username, $password, $database);
                
                // Check connection
                
                if (!$conn) {
                      die("Connection failed: " . mysqli_connect_error());
                }
                $sql = "INSERT INTO operation (`product_id`, `stock_id`, `stock_destination_id`, `operation_from_id`, `q`, `price_in`, `price_out`, `discount`, `operation_type_id`, `sell_id`, `status`, `is_draft`, `is_traspase`, `created_at`) 
                VALUES ('179','SERVICIO', '1', NULL, NULL, '1', '0', '$sell->comision', '0', '2', '$op->sell_id', '1', '0', '0', NOW())";
                if (mysqli_query($conn, $sql)) {
                      print  "Se ha agregado la comisión del $sell->comision a la Venta Realizada";
                } else {
                      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }mysqli_close($conn);
            }

         
            unset($_SESSION["cart"]);
            setcookie("selled", "selled"); ////////////////////
            print "<br><p class='alert alert-success'>Venta procesada exitosamente. <a target='_blank' href='ticket.php?id=" . $s[1] . "' id='printx' class='btn-xs btn btn-info'><i class='fa fa-ticket'></i> Ver Ticket</a> <a href='index.php?view=onesell&id=$s[1]' class='btn-xs btn btn-primary'>Ver Resumen</a> </p>";
            ?>          

            <?php $identificador = 1 ?>  
            <script>
                window.location = "index.php?view=facturacion&id=<?php echo $s[1] ?>&identificador=<?php echo $identificador?>";
            </script> <?php
            /*  echo '<div cla
              /*  echo '<div class="row"><div class="col-md-6 col-md-offset-3">
              <div class="embed-responsive embed-responsive-16by9">
              <iframe id="ticket1" name="ticket1" class="embed-responsive-item" src=ticket.php?id="' . $s[1] . '" allowfullscreen></iframe>
              </div>
              </div></div>
              '; /*
              echo "<script>window.frames['ticket1'].focus();
              window.frames['ticket1'].print();</script>";
             */
// print "<script>setTimeout(function(){ w = window.open('ticket.php?id=$s[1]','prueba','height=720,width=1280');  w.print();  }, 100);  </script>";
// print "<script> w = window.open('ticket.php?id=$s[1]','prueba','height=720,width=1280');  w.print();  w.close(); </script>";
        }
    }
}
?>