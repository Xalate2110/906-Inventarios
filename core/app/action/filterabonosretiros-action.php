<?php
include '/connection/conexion.php';
$stock_id = $_GET['stock_id'];
$desde = $_GET['start_at'];
$hasta  = $_GET['finish_at'];

$sql = 'SELECT * FROM spend WHERE created_at BETWEEN "'.$desde.' 00:00:00" AND "'.$hasta.' 23:59:00" AND stock_id = "'.$stock_id.'"'  ;
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
		<th style="text-align: center">Descripción</th>
		<th style="text-align: center">Operación Realizada</th>
		<th style="text-align: center">Fecha Operación</th>
		<th style="text-align: center">Sucursal</th>
		<th style="text-align: center">Usuario</th>
    <th style="text-align: center">Total</th>
 
	    </thead>
	    <?php 
        
       $total_movimiento=0;
       $total_movimientos=0;
       $retiros_total=0;
       $abonos_total=0; 
        while($mostrar=mysqli_fetch_array($resultado)){

          $id = $mostrar['id'];
          $descripcion = $mostrar['name'];
          $precio = $mostrar['price'];
          $tipo = $mostrar['kind'];
          $fecha = $mostrar['created_at'];
          $stock = $mostrar['stock_id'];
          $user = $mostrar['user_id'];
        
          if($tipo == '1'){
            $abono = "Abono";
          }else {
            $abono = "Retiro";
          }
        
          $suc_name2 = "SELECT * from stock where id = $stock_id";
          $resultado2 = $mysqli->query($suc_name2);
          $row = mysqli_fetch_assoc($resultado2);
          $nombre_sucursal = $row['name']; 
        
          $suc_name3 = "SELECT * from user where id = $user";
          $resultado3 = $mysqli->query($suc_name3);
          $row = mysqli_fetch_assoc($resultado3);
          $nombre_usuario = $row['name']; 

          $suma_abonos = 'select sum(price) AS total from spend WHERE created_at BETWEEN "'.$desde.' 00:00:00" AND  "'.$hasta.' 23:59:00"
          and stock_id = "'.$stock_id.'" and kind = 1';
          $res_abonos = $mysqli->query($suma_abonos);
          $row6 = mysqli_fetch_assoc($res_abonos);
          $abonos_total = $row6['total']; 
        
          $suma_retiros = 'select sum(price) AS total from spend WHERE created_at BETWEEN "'.$desde.' 00:00:00" AND  "'.$hasta.' 23:59:00"
          and stock_id = "'.$stock_id.'" and kind = 2';
          $res_retiros = $mysqli->query($suma_retiros);
          $row7 = mysqli_fetch_assoc($res_retiros);
          $retiros_total = $row7['total']; 
          $total_movimiento = 0;
          $total_movimientos = $abonos_total - $retiros_total;
          ?>
        <td style="text-align: center"><?php echo $id ?></td>
        <td style="text-align: center"><?php echo utf8_encode($descripcion) ?></td>
        <td style="text-align: center"><?php echo $abono?></td>
        <td style="text-align: center"><?php echo $fecha?></td>
        <td style="text-align: center"><?php echo $nombre_sucursal ?></td>
        <td style="text-align: center"><?php echo $nombre_usuario ?></td>
        <td style="text-align: center"><?php echo "$ ".number_format($precio,2,'.',',') ?></td>
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
        </TR>
   
        <TR>

      <td></td>
      <td style="text-align: center; font-size:20px;">Total Retiros</td>
      <td style="text-align: center"><?php echo "$ ".number_format($retiros_total,2,'.',',')?></td>
      <td style="text-align: center; font-size:20px;">Total Abonos</td>
      <td style="text-align: center"><?php echo "$ ".number_format($abonos_total,2,'.',',')?></td>
      <td style="text-align: center; font-size:20px;">Disponible</td>
      <td style="text-align: center"><?php echo "$ ".number_format($total_movimientos,2,'.',',')?></td>
       
      
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