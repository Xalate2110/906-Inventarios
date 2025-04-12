<?php
	header('Content-type:application/xls');
	header('Content-Disposition: attachment; filename=listadoGeneralFacturasEspeciales.xls');
    $conexion=mysqli_connect('localhost','root','','db_paraiso_uru');
	
	$stock = $_GET['stock_id'];
	$fecha_inicio = $_GET['start_at'];
	$fecha_final = $_GET['finish_at'];
	$fpago = $_GET['fpago'];

	if($fpago == 0){
		$sql = 'SELECT * FROM cfdis WHERE fecha_registro BETWEEN "'.$fecha_inicio.' 00:00:00" AND "'.$fecha_final.' 23:59:00" AND stock_id = "'.$stock.'"  and timbrado = 1 and 
		tipo_factura = 2 and mpago and mpago IN(1,2,3,4,5,6,8,12,13,14,15,17,23,24,25,26,27,28,29,30,31,99)      ';
		} else {
	
			$sql = 'SELECT * FROM cfdis WHERE fecha_registro BETWEEN "'.$fecha_inicio.' 00:00:00" AND "'.$fecha_final.' 23:59:00" AND stock_id = "'.$stock.'"  and timbrado = 1 and 
		tipo_factura = 2 and mpago and mpago = "'.$fpago.'"     ';
		}
	
		$resultado = $conexion->query($sql);
	
	
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
<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <h4 align="center">LISTADO GENERAL DE FACTURAS ESPECIALES</h4>
	<h4 align="center">SUCURSAL REPORTE : <?php echo $nombre_sucursal ?></h4>
	<h5 align="center">RANGO SELECCIONADO PARA EL REPORTE: (<?php echo $fecha_inicio ?>) al (<?php echo $fecha_final?>)</h5>
	<h5 align="center"><?php echo $direccion?> <br> <?php echo $colonia?> <br> <?php echo $colonia2 ?> </h5>

    <table width="100%" border="1px" align="center">

	<tr align="center" style = "background:#E5E5E5">
        <td style="text-align: center">SERIE</td>
        <td style="text-align: center">FOLIO</td>
        <td style="text-align: center">Nombre Cliente</td>
		<td style="text-align: center">RFC Cliente</td>
        <td style="text-align: center">Fecha Factura</td>
		<td style="text-align: center">Estatus</td>
		<td style="text-align: center">Forma De Pago</td>
        <td style="text-align: center">SubTotal</td>
		<td style="text-align: center">Iva</td>
        <td style="text-align: center">Total</td>
      
    </tr>
    <?php
		$subtotal =0 ;
		$iva=0;
		$total =0;
        while($datos=$resultado->fetch_array()){
			$subtotal+= $datos["subtotal"];
			$iva+= $datos["iva"];
			$total+= $datos["Monto"];
			
			if($datos['timbrado'] == '1'){
            $status = "Activo";
            }


			if($datos['mpago'] == '1'){
				$mpago = "Efectivo";
				}elseif($datos['mpago'] == '2'){
				$mpago = "Cheque";
				}elseif($datos['mpago'] == '3'){
				$mpago = "Transferencia Electronica";
				}elseif($datos['mpago'] == '4'){
				  $mpago = "Tarjeta Credito"; 
				}elseif($datos['mpago'] == '5'){
				  $mpago = "Monedero electrónico"; 
				}elseif($datos['mpago'] == '6'){
				  $mpago = "Dinero electrónico"; 
				}elseif($datos['mpago'] == '8'){
				  $mpago = "Vales de despensa"; 
				}elseif($datos['mpago'] == '12'){
				  $mpago = "Dación en pago"; 
				}elseif($datos['mpago'] == '13'){
				  $mpago = "Pago por subrogación"; 
				}elseif($datos['mpago'] == '14'){
				  $mpago = "Pago por consignación"; 
				}elseif($datos['mpago'] == '15'){
				  $mpago = "Condonación"; 
				}elseif($datos['mpago'] == '17'){
				  $mpago = "Compensación"; 
				}elseif($datos['mpago'] == '23'){
				  $mpago = "Novacion"; 
				}elseif($datos['mpago'] == '24'){
				  $mpago = "Confusion"; 
				}elseif($datos['mpago'] == '25'){
				  $mpago = "Remision de deuda"; 
				}elseif($datos['mpago'] == '26'){
				  $mpago = "prescripcion o caducidad"; 
				}elseif($datos['mpago'] == '27'){
				  $mpago = "A satisfaccion del acreedor"; 
				}elseif($datos['mpago'] == '28'){
				  $mpago = "Tarjeta De debito"; 
				}elseif($datos['mpago'] == '29'){
				  $mpago = "Tarjeta de servicios"; 
				}elseif($datos['mpago'] == '30'){
				  $mpago = "Aplicacion de anticipos"; 
				}elseif($datos['mpago'] == '31'){
				  $mpago = "Intermediario Pagos"; 
				}elseif($datos['mpago'] == '99'){
				  $mpago = "Por Definir"; 
				}
				?>
            <tr>
                <td style="text-align: center"><?php echo $datos["serie"]?></td>
				<td style="text-align: center"><?php echo $datos["folio"]?></td>
				<td style="text-align: center"><?php echo $datos["nombre_cliente"]."".$datos["apellido_cliente"]?></td>
				<td style="text-align: center"><?php echo $datos["rfc_cliente"]?></td>
				<td style="text-align: center"><?php echo $datos["fecha_registro"]?></td>
				<td style="text-align: center"><?php echo $status?></td>
				<td style="text-align: center"><?php echo $mpago?></td>
				<td style="text-align: center"><?php echo "$ ".$datos["subtotal"]?></td>
				<td style="text-align: center"><?php echo "$ ".$datos["iva"]?></td>
				<td style="text-align: center"><?php echo "$ ".$datos["Monto"]?></td>

           </tr>
           <?php   
		}  ?>
		
		<tr>
		   <td></td>
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
		   <td  style="padding-center:5px;padding-bottom:3px;background:YELLOW"> 
     <strong style="font-size:30px;">Totales</td>
		   <td><?php echo "$ ".$subtotal ?></td>
		   <td><?php echo "$ ".$iva ?></td>
		   <td><?php echo "$ ".$total?></td>
		   </tr>
    </table>

</body>
</html>