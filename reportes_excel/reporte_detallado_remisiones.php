<?php
	header('Content-type:application/xls');
	header('Content-Disposition: attachment; filename=reporte_detallado_remisiones.xls');
	include '../connection/conexion.php';

	

$stock = $_GET['stock_id'];
$fecha_inicio = $_GET['start_at'];
$fecha_final  = $_GET['finish_at'];
$proveedor_id = $_GET['proveedor_id'];
$product_id   = $_GET['product_id'];

if($proveedor_id == 0 && $product_id == 0){

	$sql='select operation.product_id, operation.descripcion, operation.q, operation.price_in,operation.price_out,product.code,CONCAT(person.name) AS proveedor,CONCAT(stock.name) as stock,operation.sell_id, sell.person_id,sell.operation_type_id,sell.invoice_code,product.name,operation.created_at,sell.stock_to_id,sell.p_id
	from operation 
	INNER JOIN sell on operation.sell_id = sell.id 
	INNER JOIN person on person.id = sell.person_id
	INNER JOIN stock on stock.id = sell.stock_to_id
	INNER JOIN product on operation.product_id = product.id
	WHERE operation.created_at  BETWEEN "'.$fecha_inicio.' 00:00:00" AND  "'.$fecha_final.' 23:59:00"AND operation.stock_id = "'.$stock.'" and sell.operation_type_id = 2 and sell.p_id IN (1,2,4) and operation.id_salida = 0 and  sell.is_draft = 0  order by operation.created_at DESC';

 }else if ($product_id == 0 && $proveedor_id !== 0){

	$sql='select operation.product_id, operation.descripcion, operation.q, operation.price_in,operation.price_out,product.code,CONCAT(person.name) AS proveedor,CONCAT(stock.name) as stock,operation.sell_id, sell.person_id,sell.operation_type_id,sell.invoice_code,product.name,operation.created_at,sell.stock_to_id,sell.p_id
	from operation 
	INNER JOIN sell on operation.sell_id = sell.id 
	INNER JOIN person on person.id = sell.person_id
	INNER JOIN stock on stock.id = sell.stock_to_id
	INNER JOIN product on operation.product_id = product.id
	WHERE sell.person_id = "'.$proveedor_id.'"  and operation.created_at  BETWEEN "'.$fecha_inicio.' 00:00:00" AND  "'.$fecha_final.' 23:59:00"AND operation.stock_id = "'.$stock.'" and sell.operation_type_id = 2 and sell.p_id IN (1,2,4) and operation.id_salida = 0 and  sell.is_draft = 0  order by operation.created_at DESC';

}else if ($product_id !==  0 && $proveedor_id == 0){
	
	$sql='select operation.product_id, operation.descripcion, operation.q, operation.price_in,operation.price_out,product.code,CONCAT(person.name) AS proveedor,CONCAT(stock.name) as stock,operation.sell_id, sell.person_id,sell.operation_type_id,sell.invoice_code,product.name,operation.created_at,sell.stock_to_id,sell.p_id
	from operation 
	 INNER JOIN sell on operation.sell_id = sell.id 
	 INNER JOIN person on person.id = sell.person_id
	 INNER JOIN stock on stock.id = sell.stock_to_id
	 INNER JOIN product on operation.product_id = product.id
	 WHERE operation.product_id = "'.$product_id.'"  and operation.created_at  BETWEEN "'.$fecha_inicio.' 00:00:00" AND  "'.$fecha_final.' 23:59:00"AND operation.stock_id = "'.$stock.'" and sell.operation_type_id = 2 and sell.p_id IN (1,2,4) and operation.id_salida = 0 and  sell.is_draft = 0  order by operation.created_at DESC';
 
}else if ($product_id !== 0 && $proveedor_id !== 0){
	
	 $sql='select operation.product_id, operation.descripcion, operation.q, operation.price_in,operation.price_out,product.code,CONCAT(person.name) AS proveedor,CONCAT(stock.name) as stock,operation.sell_id, sell.person_id,sell.operation_type_id,sell.invoice_code,product.name,operation.created_at,sell.stock_to_id,sell.p_id
	 from operation 
	 INNER JOIN sell on operation.sell_id = sell.id 
	 INNER JOIN person on person.id = sell.person_id
	 INNER JOIN stock on stock.id = sell.stock_to_id
	 INNER JOIN product on operation.product_id = product.id
	 WHERE sell.person_id = "'.$proveedor_id.'" and operation.product_id = "'.$product_id.'"  and operation.created_at  BETWEEN "'.$fecha_inicio.' 00:00:00" AND  "'.$fecha_final.' 23:59:00"AND operation.stock_id = "'.$stock.'" and sell.operation_type_id = 2 and sell.p_id IN (1,2,4) and operation.id_salida = 0 and sell.is_draft = 0  order by operation.created_at DESC';
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
    <h4 align="center">REPORTE DETALLADO DE REMISIONES</h4>
	<h4 align="center">SUCURSAL REPORTE : <?php echo $nombre_sucursal ?></h4>
	<h5 align="center">RANGO SELECCIONADO PARA EL REPORTE: (<?php echo $fecha_inicio ?>) al (<?php echo $fecha_final?>)</h5>
	<h5 align="center"><?php echo $direccion?> <br> <?php echo $colonia?> <br> <?php echo $colonia2 ?> </h5>
	

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
    	$utilidad_neta = $mostrar['price_out'] * $mostrar['q'];
		
		if($mostrar['p_id'] == '1'){
			$estado = "HABILITADA";
		}else if($mostrar['p_id'] == '2'){
			$estado = "PENDIENTE";
		}else if($mostrar['p_id'] == '4'){
			$estado = "CREDITO";
		}
	
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
	   <td  style="padding-center:5px;padding-bottom:3px;background:yellow"> 
       <strong style="font-size:16px;">Total Vendido:</td>

        <td style="background:yellow"> 
        <strong style="font-size:16px;"><?php echo "$ ".number_format($urm,2,'.','') ?></td> 
		</tr>
	    <?php }
        $urm=0;
        ?>	



	  <table class="table table-striped table-bordered" id ="utilidad_remisiones" name = "utilidad_remisiones" border = "10">
		<colgroup>
    
    	<col span="8" style="background:white">
  		</colgroup>
	
        <thead>
		      <tr>
			  <th style="text-align: center">Folio Remisión</th>
			  <th style="text-align: center">Fecha</th>
			  <th style="text-align: center">Nombre Cliente</th>
			  <th style="text-align: center">Cliente ID </th>
			  <th style="text-align: center">Folio Factura </th>
			  <th style="text-align: center">Almacen </th>
			  <th style="text-align: center">Status</th>
			  </tr>
            
		   	<tr>
			   <td style="text-align: center;background:green;font-size:16px;color:white"><?php echo "R - ". $mostrar['sell_id'] ?></td>
			<td style="text-align: center"><?php echo $mostrar['created_at'] ?></td>
			<td style="text-align: center"><?php echo $mostrar['proveedor'] ?></td>
			<td style="text-align: center"><?php echo $mostrar['person_id'] ?></td>
			<td style="text-align: center;font-size:16px;color:red "><?php echo $mostrar['invoice_code'] ?></td>
			<td style="text-align: center;font-size:16px;color:black "><?php echo $mostrar['stock'] ?></td>
			<td style="text-align: center;background:green;font-size:16px;color:white "><?php echo $estado ?></td>
	        </tr>
		
			<tr>
			<th style="text-align: center">Codigo</th>
	    	  <th style="text-align: center">Descripción Producto</th>
			  <th style="text-align: center">Cantidad</th>
			  <th style="text-align: center">Precio Compra </th>
			  <th style="text-align: center">Precio Venta </th>
			  <th style="text-align: center">Total</th>
			</tr>
		    <?php } 
		    ?>
		
            <tr> 
			<td style="text-align: center"><?php echo $mostrar['code'] ?></td>
            <td style="text-align: center"><?php echo $mostrar['name'] ?></td>
			<td style="text-align: center"><?php echo $mostrar['q'] ?></td>
			<td style="text-align: center"><?php echo "$ ".number_format($mostrar['price_in'],2,'.',',') ?></td>
			<td style="text-align: center"><?php echo "$ ".number_format($mostrar['price_out'],2,'.',',') ?></td>
			<td style="text-align: center"><?php echo "$ ".number_format($utilidad_neta,2,'.',',') ?></td> 
		
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
	   <td  style="padding-center:5px;padding-bottom:3px;background:yellow"> 
     <strong style="font-size:16px;">Total Vendido:</td>


	   <td style="background:yellow"> 
    <strong style="font-size:16px;"><?php echo "$ ".number_format($urm,2,'.',',') ?></td> 


		</tr>
            
			<tr>
			<td></td>
            <td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			</tr>
           
			<tr>
            <td></td>
            <td></td>
			<td></td>
			<td></td>
			<td></td>
			<td  style="padding-center:5px;padding-bottom:3px;background:WHITE"> 
            <strong style="font-size:20px;">Total Vendido: </td>
			<td  style="padding-center:5px;padding-bottom:3px;background:WHITE"> 
            <strong style="font-size:20px;"><?php echo "$ ".number_format($tutil,2,'.',',') ?></td> 
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



