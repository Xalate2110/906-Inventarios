<?php
	header('Content-type:application/xls');
	header('Content-Disposition: attachment; filename=excel_utilidad_remisiones.xls');
    $conexion=mysqli_connect('localhost','root','','db_paraiso_uru');
	
	$stock = $_GET['stock_id'];
    $fecha_inicio = $_GET['start_at'];
	$fecha_final = $_GET['finish_at'];

	$sql='select operation.product_id, operation.descripcion, operation.q, operation.price_in,operation.price_out,product.code,operation.sell_id,sell.p_id, sell.person_id, person.name, operation.created_at
	from operation 
	INNER JOIN sell on operation.sell_id = sell.id 
	INNER JOIN product on operation.product_id = product.id
	INNER JOIN person on person.id = sell.person_id 
	WHERE operation.created_at  BETWEEN "'.$fecha_inicio.' 00:00:00" AND  "'.$fecha_final.' 23:59:00" AND operation.stock_id = "'.$stock.'" and sell.facturado = 0
	and sell.p_id IN (1,4) and sell.operation_type_id = 2 and operation.id_salida = 0 ORDER BY operation.sell_id desc';
	$result=mysqli_query($conexion,$sql);
	
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
    <h4 align="center">LISTADO GENERAL DE REMISIONES</h4>
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

		while($mostrar=mysqli_fetch_array($result)){
        $utilidad = $mostrar['price_out'] - $mostrar['price_in'];
		$utilidad_neta = $utilidad * $mostrar['q'];

		
        if($mostrar['p_id'] == 1){
			$estado = "REMISIÓN PAGADA";
			}else if($mostrar['p_id' == 4]){
			$estado = "REMISIÓN A CREDITO";	
			}
	
	
        if($remision<>$mostrar['sell_id']){
            //EL SISTEMA IMPRIME LAS REMISIONES GENERADAS EN EL DIA		
			$remision = $mostrar['sell_id'];

		  
		if ($x <> 0){   ?>
		<tr>    
       <td style="padding-center:5px;padding-bottom:3px;background:green" ></td>
	   <td style="padding-center:5px;padding-bottom:3px;background:green" ></td>
	   <td style="padding-center:5px;padding-bottom:3px;background:green" ></td>
	   <td style="padding-center:5px;padding-bottom:3px;background:green" ></td>
	   <td style="padding-center:5px;padding-bottom:3px;background:green" ></td>
	   <td style="padding-center:5px;padding-bottom:3px;background:yellow"> 
       <strong style="font-size:20px;">Utilidad Remisión:</td>

        <td style="background:yellow"> 
        <strong style="font-size:20px;"><?php echo "$ ".round($urm,2) ?></td> 
		</tr>
	    <?php }
        $urm=0;
        ?>	


	  <table class="table table-striped table-bordered" id ="utilidad_remisiones" name = "utilidad_remisiones" border = "1"  width="100%" >

		<colgroup>
        
    	<col span="8" style="background:white">
  		</colgroup>
	
        <thead>
		      <tr align="center" style = "background:#E5E5E5">
			  <th>FOLIO REMISION</th>
			  <th>FECHA COMPRA</th>
			  <th>NOMBRE CLIENTE</th>
			  <th style="text-align: center">CLIENTE ID </th>
			  <th style="text-align: center">ESTADO REMISIÓN</th>
			  </tr>
            
		   	<tr>
			<td style="text-align: center;background:green;font-size:20px;color:white"><?php echo "F - ". $mostrar['sell_id'] ?></td>
			<td style="text-align: center"><?php echo $mostrar['created_at'] ?></td>
			<td><?php echo $mostrar['name'] ?></td>
			<td><?php echo $mostrar['person_id'] ?></td>
			<td style="text-align: center;background:#C6C6C6;font-size:20px;color:red"><?php echo $estado ?></td>
	        </tr>
		
			<tr >
			  <th>CODIGO </th>
	    	  <th>DESCRIPCION PRODUCTO (S)</th>
			  <th>CANTIDAD</th>
			  <th>PRECIO COMPRA </th>
			  <th>PRECIO VENTA </th>
			  <th>UTILIDAD POR PRODUCTO</th>
			</tr>
		    <?php } 
		    ?>
		
            <tr> 
			<td style="text-align: center"><?php echo $mostrar['code'] ?></td>
            <td><?php echo $mostrar['descripcion'] ?></td>
			<td style="text-align: center"><?php echo $mostrar['q'] ?></td>
			<td style="text-align: center"><?php echo "$ ".$mostrar['price_in'] ?></td>
			<td style="text-align: center"><?php echo "$ ".$mostrar['price_out'] ?></td>
			
			<td><?php echo "$ ".round($utilidad_neta,2) ?></td> 
		
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
       <strong style="font-size:20px;">Utilidad Remisión:</td>


	<td style="background:yellow"> 
    <strong style="font-size:20px;"><?php echo "$ ".round($urm,2) ?></td> 


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
            <strong style="font-size:25px;">TOTAL UTILIDAD : </td>
			<td  style="padding-center:5px;padding-bottom:3px;background:WHITE"> 
            <strong style="font-size:25px;"><?php echo "$ ".round($tutil,2) ?></td> 
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
