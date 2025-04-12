<?php
	header('Content-type:application/xls');
	header('Content-Disposition: attachment; filename=reporte_ventas_por_categoria.xls');
    include '../connection/conexion.php';

	
    $stock        = $_GET['stock_id'];
    $fecha_inicio = $_GET['start_at'];
    $fecha_final  = $_GET['finish_at'];
    $usuario_id   = $_GET['id_usuario'];
    $product_id   = $_GET['product_id'];
    
    if($usuario_id == 0 && $product_id == 0){
        $sql = 'SELECT product.price_in, product.name,operation.product_id,operation.price_out,sum(operation.q) as piezas,SUM(operation.q * operation.price_out) as total,SUM(operation.q * operation.price_in) as total_pu, CONCAT(user.name) as usuario  from operation 
        INNER JOIN product on product.id = operation.product_id
        INNER JOIN sell on sell.id = operation.sell_id
        INNER JOIN user on user.id = sell.user_id
        where operation.operation_type_id = 2 and operation.created_at  BETWEEN "'.$fecha_inicio.' 00:00:00" AND  "'.$fecha_final.' 23:59:00" and operation.stock_id = "'.$stock.'" and operation.status = 1 and operation.is_draft = 0 and operation.is_traspase = 0 Group By operation.product_id order by piezas DESC';
        
        } else if ($product_id == 0 && $usuario_id !== 0){
        
        $sql = 'SELECT product.price_in,product.name,operation.product_id,operation.price_out,sum(operation.q) as piezas,SUM(operation.q * operation.price_out) as total,SUM(operation.q * operation.price_in) as total_pu, CONCAT(user.name) as usuario  from operation 
        INNER JOIN product on product.id = operation.product_id
        INNER JOIN sell on sell.id = operation.sell_id
        INNER JOIN user on user.id = sell.user_id
        where operation.operation_type_id = 2 and operation.created_at  BETWEEN "'.$fecha_inicio.' 00:00:00" AND  "'.$fecha_final.' 23:59:00" and operation.stock_id = "'.$stock.'" and operation.status = 1 and operation.is_draft = 0 and operation.is_traspase = 0
        and sell.user_id = "'.$usuario_id.'" Group By operation.product_id order by piezas DESC';
        
        } else if ($product_id !==  0 && $usuario_id == 0){ 
        
        $sql = 'SELECT product.price_in,product.name,operation.product_id,operation.price_out,sum(operation.q) as piezas,SUM(operation.q * operation.price_out) as total,SUM(operation.q * operation.price_in) as total_pu, CONCAT(user.name) as usuario  from operation 
        INNER JOIN product on product.id = operation.product_id
        INNER JOIN sell on sell.id = operation.sell_id
        INNER JOIN user on user.id = sell.user_id
        where operation.operation_type_id = 2 and operation.created_at  BETWEEN "'.$fecha_inicio.' 00:00:00" AND  "'.$fecha_final.' 23:59:00" and operation.stock_id = "'.$stock.'" and operation.status = 1 and operation.is_draft = 0 and operation.is_traspase = 0
        and operation.product_id = "'.$product_id.'" Group By operation.product_id order by piezas DESC';
        
        } else if ($product_id !== 0 && $usuario_id !== 0){
                
        $sql = 'SELECT product.price_in,product.name,operation.product_id,operation.price_out,sum(operation.q) as piezas,SUM(operation.q * operation.price_out) as total,SUM(operation.q * operation.price_in) as total_pu, CONCAT(user.name) as usuario  from operation 
        INNER JOIN product on product.id = operation.product_id
        INNER JOIN sell on sell.id = operation.sell_id
        INNER JOIN user on user.id = sell.user_id
        where operation.operation_type_id = 2 and operation.created_at  BETWEEN "'.$fecha_inicio.' 00:00:00" AND  "'.$fecha_final.' 23:59:00" and operation.stock_id = "'.$stock.'" and operation.status = 1 and operation.is_draft = 0 and operation.is_traspase = 0
        and operation.product_id = "'.$product_id.'" and sell.user_id = "'.$usuario_id.'"  Group By operation.product_id order by piezas DESC';
    
    }else {
       echo'<script type="text/javascript">
       alert("Especifica los datos de busqueda");
       </script>';
    } 
    
    $resultado = $mysqli->query($sql);
	
	require_once '../connection/conexion.php';
	$suc_name2 = "SELECT * from stock where id = $stock";
	$resultado2 = $mysqli->query($suc_name2);
	$row = mysqli_fetch_assoc($resultado2);
	$nombre_sucursal = $row['name']; 
	$direccion = $row['address']; 
	$colonia = $row['colonia']; 
	$colonia2 = $row['ciudad']; 
    ?>
	
	<meta charset="utf-8">
    <h4 align="center">LISTADO GENERAL PIEZAS VENDIDAS POR PRODUCTO </h4>
	<h4 align="center">SUCURSAL REPORTE : <?php echo $nombre_sucursal ?></h4>
	<h5 align="center">RANGO SELECCIONADO PARA EL REPORTE: (<?php echo $fecha_inicio ?>) al (<?php echo $fecha_final?>)</h5>
	<h5 align="center"><?php echo $direccion?> <br> <?php echo $colonia?> <br> <?php echo $colonia2 ?> </h5>
	<?php
	if (count($sql) > 0) {
		?>
    <br>

    <div class="box box-primary">
        <div class="box-header">

        <div class="box-body">
            <table class="table table-bordered table-hover table-responsive datatable " id="filter_ventas_categoria">
                <thead  bgcolor="#eeeeee" align="center">
                <th style="text-align: center;">Descripción Producto</th>
                <th style="text-align: center;">Total Piezas Por Producto</th>
                <th style="text-align: center;">Precio Compra</th>
                <th style="text-align: center;">Precio Venta</th>
                <th style="text-align: center;">Total Precio de Compra </th>
                <th style="text-align: center;">Total Por Producto</th>
               </thead>
                <?php

                    $total_pu = 0;
                    $total_pc = 0;
                	while($mostrar=mysqli_fetch_array($resultado)){
                    ?>
                    <tr>
                    <td style="width:200px;font-size:16px;"><?php echo $mostrar['name'] ?></td>
                    <td style="width:100px;text-align: center;"><?php echo $mostrar['piezas'] ?></td>
                    <td style="width:100px;text-align: center;"><?php echo $mostrar['price_in'] ?></td>
                    <td style="width:100px;text-align: center;"><?php echo $mostrar['price_out'] ?></td>
                    <td style="width:100px;text-align: center;"><?php echo "$ ".number_format($mostrar['total_pu'],2,'.',',') ?></td>
                    <td style="width:100px;text-align: center; padding-center:5px;padding-bottom:3px;background:yellow;font-size:16px;color:black"><?php echo "$".number_format($mostrar['total'],2,'.',',') ?></td>
                    </td>
                    </tr>
                    <?php
                    $total_pu += $mostrar['total_pu'];
                    $total_pc += $mostrar['total'];
                    ?>
                    <?php } ?>
                    <tr>
                    <td style="width:200px;font-size:16px;"></td>
                    <td style="width:200px;font-size:16px;"></td>
                    <td style="width:200px;font-size:16px;"></td>
                    <td style="width:100px;text-align: center; padding-center:5px;padding-bottom:3px;font-size:20px;color:black">Totales </td>
                    <td style="width:100px;text-align: center; padding-center:5px;padding-bottom:3px;background:green;font-size:20px;color:white"><?php echo "$".number_format($total_pu,2,'.',',') ?></td>
                    <td style="width:100px;text-align: center; padding-center:5px;padding-bottom:3px;background:green;font-size:20px;color:white"><?php echo "$".number_format($total_pc,2,'.',',') ?></td>
                    </td>
                    </tr>

            </table>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php
} else {
    ?>
    <div class="jumbotron">
        <h2>No hay ventas</h2>
        <p>No se ha realizado ninguna venta.</p>
    </div>
    <?php
}
?>
<script>
                            $(document).ready(function() {
                            $('#filter_ventas_categoria').DataTable( {
                            "paging":   true,
                            "ordering": true,
                            "iDisplayLength": 15,
                            "scrollX": true,
                            "info":     true
                            } );
                            } );
                      </script>





