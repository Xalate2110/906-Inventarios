<?php
	header('Content-type:application/xls');
	header('Content-Disposition: attachment; filename=excel_utilidad_facturas.xls');
    $conexion=mysqli_connect('localhost','root','','db_paraiso_uru');
	
	$stock = $_GET['stock_id'];
	$fecha_inicio = $_GET['start_at'];
	$fecha_final = $_GET['finish_at'];
	
	
	$sql='select operation.product_id, operation.descripcion, operation.q, operation.price_in,operation.price_out,product.code,operation.sell_id,cfdis.Folio_venta,cfdis.folio,cfdis.serie,
	cfdis.fecha_registro,cfdis.nombre_cliente,cfdis.apellido_cliente,cfdis.uuid,cfdis.timbrado,cfdis.mpago, 
	operation.created_at from operation INNER JOIN cfdis on operation.sell_id = cfdis.Folio_venta INNER JOIN product on operation.product_id = product.id 
	where operation.created_at BETWEEN "'.$fecha_inicio.' 00:00:00" AND  "'.$fecha_final.' 23:59:00"AND operation.stock_id = "'.$stock.'" and cfdis.timbrado = 1 and cfdis.tipo_factura = 1 and operation.id_salida = 0  ORDER BY operation.sell_id desc';
	
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
    <h4 align="center">LISTADO GENERAL DE UTILIDAD DE FACTURAS</h4>
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

			if($mostrar['timbrado'] == '1' ){
				$estado = "Activa";
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






		
			if($remision<>$mostrar['sell_id']){
				//EL SISTEMA IMPRIME LAS REMISIONES GENERADAS EN EL DIA		
				$remision = $mostrar['sell_id'];
	
			if ($x <> 0){   ?>
				<tr>    
		   
				<td style="padding-center:5px;padding-bottom:3px;background:green" ></td>
	   			<td style="padding-center:5px;padding-bottom:3px;background:green" ></td>
	 			<td style="padding-center:5px;padding-bottom:3px;background:green" ></td>
	  			<td style="padding-center:5px;padding-bottom:3px;background:green" ></td>
	   			<td  style="padding-center:5px;padding-bottom:3px;background:green" ></td>
		   		<td  style="padding-center:5px;padding-bottom:3px;background:yellow"> 
		   		<strong style="font-size:20px;">Utilidad Factura:</td>
	
			<td style="background:yellow"> 
			<strong style="font-size:20px;"><?php echo "$ ".round($urm,2) ?></td> 
			</tr>
			<?php }
			$urm=0;
			?>	
	
	
	
		  <table class="table table-striped table-bordered" id ="utilidad_remisiones" name = "utilidad_remisiones" border = "1">
			<colgroup>
		
			<col span="8" style="background:white">
			  </colgroup>
		
			<thead>
				  <tr>
				  <th style="text-align: center">SERIE</th>
				  <th style="text-align: center">FOLIO FACTURA</th>
				  <th style="text-align: center">FECHA FACTURA</th>
				  <th style="text-align: center">NOMBRE CLIENTE</th>
				  <th style="text-align: center"> FOLIO UUID</th>
				  <th style="text-align: center">ESTADO</th>
			      <th style="text-align: center">FORMA DE PAGO</th>
				  </tr>
				
				   <tr>
				<td style="text-align: center;background:green;font-size:16px;color:white"><?php echo $mostrar['serie'] ?></td>
				<td style="text-align: center"><?php echo $mostrar['folio'] ?></td>
				<td style="text-align: center"><?php echo $mostrar['fecha_registro'] ?></td>
				<td style="text-align: center"><?php echo $mostrar['nombre_cliente']."    ".$mostrar['apellido_cliente'] ?></td>
				<td style="text-align: center"><?php echo $mostrar['uuid']?></td>
				<td style="text-align: center;background:#C6C6C6;font-size:20px;color:red"><?php echo $estado ?></td>
			   <td style="text-align: center;background:#C6C6C6;font-size:20px;color:red"><?php echo $mpago ?></td>
				</tr>
			
				<tr>
				  <th style="text-align: center">CODIGO </th>
				  <th style="text-align: center">DESCRIPCION PRODUCTO (S)</th>
				  <th style="text-align: center">CANTIDAD</th>
				  <th style="text-align: center">PRECIO COMPRA </th>
				  <th style="text-align: center">PRECIO VENTA </th>
				  <th style="text-align: center">UTILIDAD POR PRODUCTO</th>
				</tr>
				<?php } 
				?>
			
				<tr> 
				<td style="text-align: center"><?php echo $mostrar['code'] ?></td>
				<td style="text-align: center"><?php echo $mostrar['descripcion'] ?></td>
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
		 <strong style="font-size:20px;">Utilidad Factura:</td>
	
	
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
	
	
	
	