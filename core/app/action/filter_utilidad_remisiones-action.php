<?php
include '/connection/conexion.php';
$stock = $_GET['stock_id'];
$fecha_inicio = $_GET['start_at'];
$fecha_final = $_GET['finish_at'];


$sql='select operation.product_id, operation.descripcion, operation.q, operation.price_in,operation.price_out,product.code,product.name as producto_name,operation.sell_id,sell.p_id, sell.person_id,CONCAT(stock.name) as stock, person.name, operation.created_at
from operation 
INNER JOIN sell on operation.sell_id = sell.id 
INNER JOIN stock on stock.id = sell.stock_to_id
INNER JOIN product on operation.product_id = product.id
INNER JOIN person on person.id = sell.person_id where operation.created_at  BETWEEN "'.$fecha_inicio.' 00:00:00" AND  "'.$fecha_final.' 23:59:00"AND operation.stock_id = "'.$stock.'" and sell.facturado = 0 
and sell.p_id IN (1,4) and sell.operation_type_id = 2 and operation.id_salida = 0 ORDER BY operation.sell_id desc';
$resultado = $mysqli->query($sql);
?>
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
        $utilidad = $mostrar['price_out'] - $mostrar['price_in'];
		$utilidad_neta = $utilidad * $mostrar['q'];
	
        if($mostrar['p_id'] == 1){
        $estado = "Pagada";
		}else if($mostrar['p_id' == 4]){
		$estado = "Crédito";	
        }

		if($mostrar['p_id'] == '1'){
			$estado2 = "Habilitada";
		}else if($mostrar['p_id'] == '2'){
			$estado2 = "Pendiente";
		}else if($mostrar['p_id'] == '3'){
			$estado2 = "Cancelada";
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
       <strong style="font-size:16px;">Utilidad Remisión:</td>

        <td style="background:yellow"> 
        <strong style="font-size:16px;"><?php echo "$ ".number_format($urm, 2, '.', ',') ?></td> 
		</tr>
	    <?php }
        $urm=0;
        ?>	


		<table class="table table-bordered table-hover table-responsive datatable">
	  <!-- <table class="table table-striped table-bordered" id ="utilidad_remisiones" name = "utilidad_remisiones" border = "10"> -->
		<colgroup>
    
    	<col span="8" style="background:white">
  		</colgroup>
	
        <thead>
		      <tr>
			  <th style="text-align: center">Folio</th>
			  <th style="text-align: center">Fecha Remisión</th>
			  <th style="text-align: center">Nombre Cliente</th>
			  <th style="text-align: center">Cliente ID</th>
			  <th style="text-align: center">Status Pago</th>
			  <th style="text-align: center">Almacén</th>
			  <th style="text-align: center">Status</th>
			  </tr>
            
		   	<tr>
		    <td style="text-align: center;background:green;font-size:16px;color:white"><?php echo "F - ". $mostrar['sell_id'] ?></td>
			<td style="text-align: center"><?php echo $mostrar['created_at'] ?></td>
			<td style="text-align: center"><?php echo utf8_encode($mostrar['name']) ?></td>
			<td style="text-align: center"><?php echo $mostrar['person_id'] ?></td>
			<td style="text-align: center;background:#C6C6C6;font-size:16px;color:red"><?php echo $estado ?></td>
			<td style="text-align: center;background:red;font-size:16px;color:white "><?php echo $mostrar['stock'] ?></td>
			<td style="text-align: center;background:#C6C6C6;font-size:16px;color:red"><?php echo $estado2 ?></td>
	        </tr>
		
			<tr>
			  <th style="text-align: center">Codigo </th>
	    	  <th style="text-align: center">Descripción Producto</th>
			  <th style="text-align: center">Cantidad</th>
			  <th style="text-align: center">Precio Compra </th>
			  <th style="text-align: center">Precio Venta</th>
			  <th style="text-align: center">Uutilidad Por Producto</th>
			</tr>
		    <?php } 
		    ?>
		
            <tr> 
			<td style="text-align: center"><?php echo $mostrar['code'] ?></td>
            <td style="text-align: center"><?php echo $mostrar['producto_name'] ?></td>
			<td style="text-align: center"><?php echo $mostrar['q'] ?></td>
			<td style="text-align: center"><?php echo "$ ".$mostrar['price_in'] ?></td>
			<td style="text-align: center"><?php echo "$ ".$mostrar['price_out'] ?></td>
			<td style="text-align: center"><?php echo "$ ".round($utilidad_neta,2) ?></td> 
		
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
     <strong style="font-size:16px;">Utilidad Remisión:</td>


	   <td style="background:yellow"> 
    <strong style="font-size:16px;"><?php echo "$ ".number_format($urm, 2, '.', ',') ?></td> 


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
            <strong style="font-size:20px;">Total Utilidad : </td>
			<td  style="padding-center:5px;padding-bottom:3px;background:WHITE"> 
            <strong style="font-size:20px;"><?php echo "$ ".number_format($tutil,2, '.', ',') ?></td> 
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
        <h2>No hay ventas</h2>
        <p>No se ha realizado ninguna venta.</p>
    </div>
    <?php
}
?>



