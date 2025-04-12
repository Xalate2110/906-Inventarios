<section class="content">
<div class="row">
	<div class="col-md-12">


		<h1><i class='bi-cart4'></i> Compras por Recibir</h1>
<div class="btn-group">

</div>
		<div class="clearfix"></div>


<?php

$products = null;
if(Core::$user->kind==2|| Core::$user->kind==4){
$products = SellData::getResToReceiveByStockId(Core::$user->stock_id);
}else{
$products = SellData::getResToReceive();
}

if(count($products)>0){
	?>

<?php 
    $total_pe =0;
    $total_an=0;
    $total_ant=0;
    foreach($products as $sell):?>
    <?php 
    $total= $sell->total;  
    $total_pe += $total;
    ?>
    <?php endforeach; ?>

   <div class="card box-primary">
   <div class="card-header">
   <tr>
   <td> <h6  style="text-align: right;font-size:16px;background:yellow;color:black"">Total Pendiente Por Cobrar $ : <?php echo number_format($total_pe,2, '.', ',') ?></h6> </td>

   </tr></div></div>

<br>


<div class="card box-primary">
	<div class="card-header">Compras</div>
	<div class="card-body">
<table class="table datatable table-bordered table-hover	">
	<thead>
		<th  style="text-align: center;">Detalle</th>
		<th  style="text-align: center;">Folio</th>
		<th  style="text-align: center;">Pago</th>
		<th  style="text-align: center;">Entrega</th>
		<th  style="text-align: center;">Total</th>
		<th  style="text-align: center;">Fecha Compra</th>
		<th  style="text-align: center;">Acciones</th>
	</thead>
	<?php foreach($products as $sell):
	$operations = OperationData::getAllProductsBySellId($sell->id);
	?>

	<tr>
		<td style="width:30px;text-align: center;"><a href="index.php?view=onere&id=<?php echo $sell->id; ?>" class="btn btn-sm btn-outline-secondary"><i class="bi-eye"></i></a></td>
		<td>#<?php echo $sell->id; ?></td>

<td  style="text-align: center;"><?php echo $sell->getP()->name; ?></td>
<td  style="text-align: center;"><?php echo $sell->getD()->name; ?></td>
<td  style="text-align: center;">
<?php
$total=0;
	foreach($operations as $operation){
		$product  = $operation->getProduct();
		$total += $operation->q*$product->price_in;}
		echo "<b>$ ".number_format($total,2,".",",")."</b>";?>			
		</td>
		<td  style="text-align: center;"><?php echo $sell->created_at; ?></td>
		<td style="width:120px;text-align: center;">
		<a href="./?action=receive1&id=<?php echo $sell->id; ?>" class="btn btn-sm btn-primary">Recibir Mercancia</a>
		<a href="index.php?view=delre&id=<?php echo $sell->id; ?>" class="btn btn-sm btn-danger"><i class="bi-trash"></i></a>
		</td>
	</tr>

<?php endforeach; ?>

</table>
</div>
</div>
	<?php
}else{
	?>
	<div class="jumbotron">
		<h2>No hay datos</h2>
		<p>No se ha realizado ninguna operacion.</p>
	</div>
	<?php
}

?>
<br><br><br><br><br><br><br><br><br><br>
	</div>
</div>
