<?php
	header('Content-type:application/xls');
	header('Content-Disposition: attachment; filename=ListadoGastosRetiros.xls');
    $conexion=mysqli_connect('localhost','root','','db_paraiso_uru');
	
	$stock = $_GET['stock_id'];
	$fecha_inicio = $_GET['start_at'];
	$fecha_final = $_GET['finish_at'];


	$sql = 'SELECT  * from spend where created_at BETWEEN "'.$fecha_inicio.' 00:00:00" AND "'.$fecha_final.' 23:59:00" AND stock_id = "'.$stock.'" and  box_id is NULL order by id DESC';
	$resultado = $conexion->query($sql);
	
	require_once '../connection/conexion.php';
	$suc_name2 = "SELECT * from stock where id = $stock";
	$resultado2 = $mysqli->query($suc_name2);
	$row = mysqli_fetch_assoc($resultado2);
	$nombre_sucursal = $row['name']; 
	$direccion = $row['address']; 
	$colonia = $row['colonia']; 
	$colonia2 = $row['ciudad']; 

	$suma_abonos = 'select sum(price) AS total from spend WHERE created_at BETWEEN "'.$fecha_inicio.' 00:00:00" AND  "'.$fecha_final.' 23:59:00"
	and stock_id = "'.$stock.'" and kind = 1';
	echo $suma_abonos;
	$res_abonos = $mysqli->query($suma_abonos);
	$row1 = mysqli_fetch_assoc($res_abonos);
	$abonos_total = $row1['total']; 

	
	$suma_retiros = 'select sum(price) AS total2 from spend WHERE created_at BETWEEN "'.$fecha_inicio.' 00:00:00" AND  "'.$fecha_final.' 23:59:00"
	and stock_id = "'.$stock.'" and kind = 2';
	$res_retiros = $mysqli->query($suma_retiros);
	$row2 = mysqli_fetch_assoc($res_retiros);
	$retiros_total = $row2['total2']; 
   
    $total_movimientos = 0;
	$total_movimientos = $abonos_total - $retiros_total;
	
?>

<meta charset="utf-8">
<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <h4 align="center">LISTADO GENERAL DE RETIROS Y ABONOS EN EL SISTEMA</h4>
	<h4 align="center">SUCURSAL REPORTE : <?php echo $nombre_sucursal ?></h4>
	<h5 align="center">RANGO SELECCIONADO PARA EL REPORTE: (<?php echo $fecha_inicio ?>) al (<?php echo $fecha_final?>)</h5>
	<h5 align="center"><?php echo $direccion?> <br> <?php echo $colonia?> <br> <?php echo $colonia2 ?> </h5>

    <table width="100%" border="1px" align="center">
    <tr align="center" style = "background:#E5E5E5">
        <td style="text-align: center">ID Mov</td>
        <td style="text-align: center">Descripcion</td>
        <td style="text-align: center">Operación</td>
		<td style="text-align: center">Total</td>
        <td style="text-align: center">Fecha Operación</td>
		<td style="text-align: center">Sucursal</td>
		<td style="text-align: center">Usuario</td>
      
    </tr>
    <?php
		$tipo = 0;
        while($datos=$resultado->fetch_array()){
			$user = $datos['user_id'];
			$tipo = $datos['kind'];
		
			if($tipo == '1'){
				$abono = "Abono";
			}else {
				$abono = "Retiro";
			}
		
			$suc_name2 = "SELECT * from stock where id = $stock";
			$resultado2 = $mysqli->query($suc_name2);
			$row = mysqli_fetch_assoc($resultado2);
			$nombre_sucursal = $row['name']; 
		
			$suc_name3 = "SELECT * from user where id = $user";
			$resultado3 = $mysqli->query($suc_name3);
			$row = mysqli_fetch_assoc($resultado3);
			$nombre_usuario = $row['name']; 
			?>
            <tr>
                <td style="text-align: center"><?php echo $datos["id"]?></td>
				<td style="text-align: center"><?php echo $datos["name"]?></td>
				<td style="text-align: center"><?php echo "$ ". $datos["price"]?></td>
				<td style="text-align: center"><?php echo $abono?></td>
				<td style="text-align: center"><?php echo $datos["created_at"]?></td>
				<td style="text-align: center"><?php echo $nombre_sucursal?></td>
				<td style="text-align: center"><?php echo $nombre_usuario?></td>
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
		   <td style="padding-center:5px;padding-bottom:3px;background:GREEN;color:white">TOTAL ABONOS</td>
		   <td style="padding-center:5px;padding-bottom:3px;background:GREEN;color:white">TOTAL RETIROS</td>
		   <td style="padding-center:5px;padding-bottom:3px;background:GREEN;color:white">TOTAL </td>
		 </tr>
        <tr>
		   <td></td>
	       <td></td>
		   <td></td>
		   <td></td>
		   
		   <td  style="padding-center:5px;padding-bottom:3px;background:YELLOW"> 
     <strong style="font-size:30px;">Totales</td>
		   <td style="padding-center:5px;padding-bottom:3px;background:YELLOW"> 
     <strong style="font-size:30px;"><?php echo "$ ".$abonos_total ?></td>
		   <td style="padding-center:5px;padding-bottom:3px;background:YELLOW"> 
     <strong style="font-size:30px;"><?php echo "$ ".$retiros_total ?></td>
		   <td style="padding-center:5px;padding-bottom:3px;background:YELLOW"> 
     <strong style="font-size:30px;"><?php echo "$ ".$total_movimientos ?></td>
	
		   </tr>

	
    </table>

</body>
</html>