
<?php
include '/connection/conexion.php';

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

if (count($sql) > 0) {
    ?>
    <br>

<div class="box box-primary">
        <div class="box-header">

        <div class="box box-primary table-responsive">
<table class="table table-bordered table-hover table-responsive datatable" id = "filter_categorias">
                <thead  bgcolor="#eeeeee" align="center">
             
                <a href="./reportes_excel/reporte_ventas_categoria.php?stock_id=<?php echo $stock?>&start_at=<?php echo $fecha_inicio ?>&finish_at=<?php echo $fecha_final; ?>&id_usuario=<?php echo $usuario_id ?>&product_id=<?php echo $product_id ?>" class="btn btn-success">REPORTE EN EXCEL</a>
                <br>  <br> 

                                <!-- Llamar a los complementos javascript -->
                <script src="FileSaver.min.js"></script>
                <script src="Blob.min.js"></script>
                <script src="xls.core.min.js"></script>
                <script src="dist/js/tableexport.js"></script>
                <link href="dist/css/tableexport.css" rel="stylesheet" type="text/css">
                <script>
                $("table").tableExport({
                    formats: ["xlsx","txt", "csv"], //Tipo de archivos a exportar ("xlsx","txt", "csv", "xls")
                    position: 'top',  // Posicion que se muestran los botones puedes ser: (top, bottom)
                    bootstrap: false,//Usar lo estilos de css de bootstrap para los botones (true, false)
                    fileName: "remision_utilidad",    //Nombre del archivo 
                });
                </script>


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

    <script>
                          

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





