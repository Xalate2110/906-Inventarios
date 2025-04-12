<?php
include '/connection/conexion.php';
$stock = $_GET['stock_id'];
$fecha_inicio = $_GET['start_at'];
$fecha_final  = $_GET['finish_at'];
$product_id   = $_GET['product_id'];



if($stock == 0 && $product_id == 0){

$sql='	SELECT operation.product_id, product.name,product.code, operation.q,operation.price_in,operation.price_out,operation.sell_id,CONCAT(user.name) AS usuario,CONCAT(stock_salida.name) AS salida, CONCAT(stock_entrada.name) AS entrada, 
operation.created_at,operation.is_traspase,operation.created_at FROM operation 
INNER JOIN product on product.id = operation.product_id
INNER JOIN sell on sell.id = operation.sell_id
iNNER JOIN user on user.id = sell.user_id 
INNER JOIN stock as stock_salida on stock_salida.id = sell.stock_to_id
INNER JOIN stock as stock_entrada on stock_entrada.id = sell.stock_from_id
where operation.is_traspase = 1 and 
operation.created_at  BETWEEN "'.$fecha_inicio.' 00:00:00" AND   "'.$fecha_final.' 23:59:00" 
and operation.operation_type_id = 2 order by operation.created_at DESC';


}else if ($product_id == 0 && $stock !== 0){


$sql='	SELECT operation.product_id, product.name,product.code, operation.q,operation.price_in,operation.price_out,operation.sell_id,CONCAT(user.name) AS usuario,CONCAT(stock_salida.name) AS salida, CONCAT(stock_entrada.name) AS entrada, 
operation.created_at,operation.is_traspase,operation.created_at FROM operation 
INNER JOIN product on product.id = operation.product_id
INNER JOIN sell on sell.id = operation.sell_id
iNNER JOIN user on user.id = sell.user_id 
INNER JOIN stock as stock_salida on stock_salida.id = sell.stock_to_id
INNER JOIN stock as stock_entrada on stock_entrada.id = sell.stock_from_id
where operation.is_traspase = 1 and 
operation.created_at  BETWEEN "'.$fecha_inicio.' 00:00:00" AND   "'.$fecha_final.' 23:59:00" and operation.stock_id = "'.$stock.'" and operation.operation_type_id = 2 order by operation.created_at DESC';

}else {

		$sql='	SELECT operation.product_id, product.name,product.code, operation.q,operation.price_in,operation.price_out,operation.sell_id,CONCAT(user.name) AS usuario,CONCAT(stock_salida.name) AS salida, CONCAT(stock_entrada.name) AS entrada, 
operation.created_at,operation.is_traspase,operation.created_at FROM operation 
INNER JOIN product on product.id = operation.product_id
INNER JOIN sell on sell.id = operation.sell_id
iNNER JOIN user on user.id = sell.user_id 
INNER JOIN stock as stock_salida on stock_salida.id = sell.stock_to_id
INNER JOIN stock as stock_entrada on stock_entrada.id = sell.stock_from_id
where operation.is_traspase = 1 and 
operation.created_at  BETWEEN "'.$fecha_inicio.' 00:00:00" AND   "'.$fecha_final.' 23:59:00" and operation.stock_id = "'.$stock.'"  and operation.product_id = "'.$product_id.'" 
and operation.operation_type_id = 2 order by operation.created_at DESC';
} 
$resultado = $mysqli->query($sql);
?>
<br>

&nbsp;&nbsp;&nbsp;   <a href="./reportes_excel/reporte_detallado_traspasos.php?stock_id=<?php echo $stock?>&start_at=<?php echo $fecha_inicio ?>&finish_at=<?php echo $fecha_final; ?>&product_id=<?php echo $product_id ?>" class="btn btn-success">GENERAR REPORTE EN EXCEL</a>
<br><br>

<?php
if (count($sql) > 0) {
?>
 <div class="panel-body">
		<tbody>
        <?php 
    	$remision = 0;
		$x = 0;
		$urm = 0;
		$tutil = 0;

		while($mostrar=mysqli_fetch_array($resultado)){
     
    	$utilidad_neta = $mostrar['price_in'] * $mostrar['q'];
	
        if($remision<>$mostrar['sell_id']){
            //EL SISTEMA IMPRIME LAS REMISIONES GENERADAS EN EL DIA		
			$remision = $mostrar['sell_id'];

		if ($x <> 0){   ?>
        <tr>    
	   <td ></td>
	   <td ></td>
	   <td ></td>
	   <td ></td>
	   <td ></td>
	

		</tr>
	    <?php }
        $urm=0;
        ?>	




<div class="box box-primary table-responsive">
<table class="table table-bordered table-hover table-responsive datatable">

	  <!--<table class="table table-striped table-bordered" id ="reporte_traspasos" name = "reporte_traspasos" border = "10">-->
		<colgroup>
    
    	<col span="8" style="background:white">
  		</colgroup>
	
        <thead>
		      <tr>
			  <th style="text-align: center">FOLIO TRASPASO</th>
			  <th style="text-align: center">FECHA TRASPASO</th>
			  <th style="text-align: center">USUARIO GENERO</th>
			  <th style="text-align: center">ALMACEN ENVIA </th>
			  <th style="text-align: center">ALMACEN RECIBE </th>
			  </tr>
            
		   	<tr>
		    <td style="text-align: center;background:green;font-size:16px;color:white"><?php echo $mostrar['sell_id'] ?></td>
			<td style="text-align: center"><?php echo $mostrar['created_at'] ?></td>
			<td style="text-align: center"><?php echo $mostrar['usuario'] ?></td>
			<td style="text-align: center;background:red;font-size:16px;color:white "><?php echo $mostrar['entrada'] ?></td>
			<td style="text-align: center;background:grey;font-size:16px;color:white "><?php echo $mostrar['salida'] ?></td>
	        </tr>
		
			<tr>
			  <th style="text-align: center">CODIGO </th>
	    	  <th style="text-align: center">DESCRIPCION PRODUCTO (S)</th>
			  <th style="text-align: center">CANTIDAD</th>
			  <th style="text-align: center">PRECIO COMPRA </th>
			  <th style="text-align: center">PRECIO VENTA </th>
	
			</tr>
		    <?php } 
		    ?>
		
            <tr> 
			<td style="text-align: center"><?php echo $mostrar['code'] ?></td>
            <td style="text-align: center"><?php echo $mostrar['name'] ?></td>
			<td style="text-align: center"><?php echo $mostrar['q'] ?></td>
			<td style="text-align: center"><?php echo "$ ".number_format($mostrar['price_in'],2,'.',',') ?></td>
			<td style="text-align: center"><?php echo "$ ".number_format($mostrar['price_out'],2,'.',',') ?></td>
	
		
	        </tr>
			<?php 
			//SE TOMA EL VALOR PARA IMPRIMIR LA UTILIDAD POR REMISION
			$urm += $utilidad_neta; 
			//SE TOMA EL VALOR PARA IMPRIMIR LA UTILIDAD TOTAL DE LAS REMISIONES
			$tutil += $utilidad_neta;	
			$x++;
		    ?>
            <?php 
            }
	 		?>
			<tr>    
	   <td ></td>
	   <td ></td>
	   <td ></td>
	   <td ></td>
	   <td ></td>
		</tr>
		<tr>
			<td></td>
            <td></td>
			<td></td>
			<td></td>
		
			</tr>
          
			
     
			</table>
            
                                </div>
                            </div>
                            
                        </div>
                        <!--/.content-->
                    </div>
                    <!--/.span9-->
          
        
        <!--/.wrapper--><br />
        <div class="footer span-12">
          
        </div>
</body>




 <div class="clearfix"></div>

    <?php
} else {
    ?>
    <div class="jumbotron">
        <h2>No hay movimientos registrados con ese producto en el sistema</h2>
     
    </div>
    <?php
}
?>



