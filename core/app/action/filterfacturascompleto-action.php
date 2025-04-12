<?php
include '/connection/conexion.php';
$stock_id = $_GET['stock_id'];
$fpago = $_GET['fpago'];
$desde = $_GET['start_at'];
$hasta  = $_GET['finish_at'];

if($fpago == 0 ){
    $sql = 'SELECT * FROM cfdis WHERE fecha_registro BETWEEN "'.$desde.' 00:00:00" AND "'.$hasta.' 23:59:00" AND stock_id = "'.$stock_id.'"  and timbrado in (1,3) and 
    tipo_factura in (1,2) and mpago IN(1,2,3,4,5,6,8,12,13,14,15,17,23,24,25,26,27,28,29,30,31,99) and id_deposito in (0,1)'  ;
    }else {
    $sql = 'SELECT * FROM cfdis WHERE fecha_registro BETWEEN "'.$desde.' 00:00:00" AND "'.$hasta.' 23:59:00" AND stock_id = "'.$stock_id.'"  and timbrado in (1,3)  and 
    tipo_factura in (1,2) and mpago = "'.$fpago.'" and id_deposito in (0,1)';}
    $resultado = $mysqli->query($sql);

if(count($sql)>0){
?>
<br>
<div class="card box-primary">
<div class="card-header">
Listado General De Facturas.
</div>
<div class="box box-primary table-responsive">
<table class="table table-bordered datatable table-hover table-responsive datatable	">
	<thead>
		<th style="text-align: center">Folio</th>	
		<th style="text-align: center">Nombre Cliente</th>
		<th style="text-align: center">Forma De Pago</th>
		<th style="text-align: center">Tipo De Factura</th>
		<th style="text-align: center">Deposito Bancario</th>
		<th style="text-align: center">Subtotal</th>
        <th style="text-align: center">Iva</th>
        <th style="text-align: center">Total</th>
        <th style="text-align: center">Status</th>
        <th style="text-align: center">Fecha Factura</th>
	    </thead>
	    <?php 
        
        $sub =0;
        $iv = 0;
        $total =0;
        while($mostrar=mysqli_fetch_array($resultado)){

            $serie = $mostrar['serie'];
            $folio = $mostrar['folio'];
            $nombre_cliente = $mostrar['nombre_cliente'];
            $subtotal = $mostrar['subtotal'];
            $iva = $mostrar['iva'];
            $monto = $mostrar['Monto'];
            $f_r = $mostrar['fecha_registro'];
            $status = $mostrar['timbrado'];

            if($mostrar['tipo_factura'] == 1){
                $t_f = "Normal";
            }else if($mostrar['tipo_factura'] == 2){
               $t_f = "Especial";
            }

            if($mostrar['mpago'] == '1'){
                $mpago = "Efectivo";
                }elseif($mostrar['mpago'] == '2'){
                $mpago = "Cheque";
                }elseif($mostrar['mpago'] == '3'){
                $mpago = "Transferencia Electronica";
                }elseif($mostrar['mpago'] == '4'){
                  $mpago = "Tarjeta Credito"; 
                }elseif($mostrar['mpago'] == '5'){
                  $mpago = "Monedero electrónico"; 
                }elseif($mostrar['mpago'] == '6'){
                  $mpago = "Dinero electrónico"; 
                }elseif($mostrar['mpago'] == '8'){
                  $mpago = "Vales de despensa"; 
                }elseif($mostrar['mpago'] == '12'){
                  $mpago = "Dación en pago"; 
                }elseif($mostrar['mpago'] == '13'){
                  $mpago = "Pago por subrogación"; 
                }elseif($mostrar['mpago'] == '14'){
                  $mpago = "Pago por consignación"; 
                }elseif($mostrar['mpago'] == '15'){
                  $mpago = "Condonación"; 
                }elseif($mostrar['mpago'] == '17'){
                  $mpago = "Compensación"; 
                }elseif($mostrar['mpago'] == '23'){
                  $mpago = "Novacion"; 
                }elseif($mostrar['mpago'] == '24'){
                  $mpago = "Confusion"; 
                }elseif($mostrar['mpago'] == '25'){
                  $mpago = "Remision de deuda"; 
                }elseif($mostrar['mpago'] == '26'){
                  $mpago = "prescripcion o caducidad"; 
                }elseif($mostrar['mpago'] == '27'){
                  $mpago = "A satisfaccion del acreedor"; 
                }elseif($mostrar['mpago'] == '28'){
                  $mpago = "Tarjeta De debito"; 
                }elseif($mostrar['mpago'] == '29'){
                  $mpago = "Tarjeta de servicios"; 
                }elseif($mostrar['mpago'] == '30'){
                  $mpago = "Aplicacion de anticipos"; 
                }elseif($mostrar['mpago'] == '31'){
                  $mpago = "Intermediario Pagos"; 
                }elseif($mostrar['mpago'] == '99'){
                  $mpago = "Por Definir"; 
                }
        
            if($status == 1){
                $a = "Habilitada";
            }else if($status == 3){
               $a = "Cancelada";
            }

            if($mostrar['tipo_factura'] == 1){
                $t_f = "Normal";
            }else if($mostrar['tipo_factura'] == 2){
               $t_f = "Especial";
            }
        
            if($mostrar['id_deposito'] == 0){
                $d_b = "No";
            }else if($mostrar['id_deposito'] == 1){
               $d_b = "Si";
            }
        
            if($mostrar['timbrado'] == 1){
                $subtotal = $mostrar['subtotal'];
                $iva = $mostrar['iva'];
                $monto = $mostrar['Monto'];
            }else if($mostrar['timbrado'] == 3){
                $subtotal = 0;
                $iva = 0;
                $monto = 0 ;
            }
            
            $sub +=$subtotal;
            $iv += $iva;
            $total += $monto;

	
            
            
            ?>

            
        <td style="text-align: center"><?php echo $serie." - ".$folio  ?></td>
        <td style="text-align: center"><?php echo utf8_encode($nombre_cliente) ?></td>
        <td style="text-align: center"><?php echo $mpago?></td>
        <td style="text-align: center"><?php echo $t_f?></td>
        <td style="text-align: center"><?php echo $d_b ?></td>
        <td style="text-align: center"><?php echo "$ ".number_format($subtotal,2,'.',',') ?></td>
        <td style="text-align: center"><?php echo "$ ".number_format($iva,2,'.',',') ?></td>
        <td style="text-align: center"><?php echo "$ ".number_format($monto,2,'.',',')?></td>
        <td style="text-align: center"><?php echo $a?></td>
        <td style="text-align: center"><?php echo $f_r?></td>
        </tr>
        <?php }?>
    

        <TR>
	  <td></td>
	  <td></td>
	  <td></td>
	  <td></td>
	  <td></td>
	  <td></td>
	  <td></td>
	  <td></td>
	  <td></td>
	  <td></td>
	  </TR>
   
      <TR>
	  <td></td>
	  <td></td>
	  <td></td>
	  <td></td>
	  <td style="text-align: center; font-size:20px; " >Total Facturado</td>
      <td style="text-align: center"><?php echo "$ ".number_format($sub,2,'.',',')?></td>
        <td style="text-align: center"><?php echo "$ ".number_format($iv,2,'.',',')?></td>
        <td style="text-align: center"><?php echo "$ ".number_format($total,2,'.',',')?></td>
        <td style="text-align: center"></td>
        <td style="text-align: center"></td>
	  </TR>
      </table>


    
        </div>
    </div>

    <div class="clearfix"></div>
    <?php
    }else{
        ?>
        <div class="jumbotron">
        <br>

        <?php echo "a donde entre?"?>
            <p>No se ha realizado ninguna venta.</p>
        </div>
        <?php
    }

    ?>