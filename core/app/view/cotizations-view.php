<section class="content"> 
<div class="row">
	<div class="col-md-12">
		<h1><i class='fa fa-square-o'></i> Listado General de Cotizaciones: <?php echo StockData::getPrincipal()->code; ?></h1>
		<div class="clearfix"></div>
<?php
 
 


$products=null;
if(isset($_SESSION["client_id"])){
	$products = SellData::getCotizationsByClientId($_SESSION["client_id"]);
}else if(isset($_SESSION["user_id"])){
	$products = SellData::getCotizations(Core::$user->stock_id);

}

if(count($products)>0){
?>

<div class="card box-primary">
<div class="card-header">
<span class="box-title">Listado De Cotizaciones</span></div>
<div class="card-body">
<table class="table datatable table-bordered table-hover	">
	<thead>
		<th style="text-align: center">Detalle</th>
		<th style="text-align: center">Folio</th>
		<th style="text-align: center">Cliente</th>
		<th style="text-align: center">Producto</th>
		<th style="text-align: center">Pago</th>
		<th style="text-align: center">Entrega</th>
		<th style="text-align: center">Almacen Origen</th>
		<th style="text-align: center">Total</th>
		<th style="text-align: center">Fecha</th>
		<th style="text-align: center">Operaciones</th>
	</thead>
	<?php foreach($products as $sell):?>

	<tr>
		<td style="width:30px;text-align: center ">

		<a href="index.php?view=onecotization&id=<?php echo $sell->id; ?>" class="btn btn-sm btn-outline-secondary" data-coreui-toggle="tooltip" data-coreui-placement="top" title="Ver Mas"><i class="bi-eye"></i></a></td>
		<td style="text-align: center">#<?php echo $sell->id; ?></td>
		<td style="text-align: center"> <?php if($sell->person_id!=null){$c= $sell->getPerson();echo utf8_encode($c->name." ".$c->lastname);} ?> </td>

		<td style="text-align: center">
        <?php
		$operations = OperationData::getAllProductsBySellId($sell->id);
		echo count($operations);
		?>
		</td>
		<td style="text-align: center"><?php echo $sell->getP()->name; ?></td>
		<td style="text-align: center"><?php echo $sell->getD()->name; ?></td>
		<td style="text-align: center"><?php echo $sell->getStockTo()->name; 
		$id_almacen =  $sell->stock_to_id;
		?></td>

<td style="text-align: center">

<?php
//$total= $sell->total-$sell->discount;
	$total=0;
	$acumulador=0;
	foreach($operations as $operation){
		$product  = $operation->getProduct();
		$total += $operation->q*$operation->price_out;
		$idpro = $product->id;
	}
		echo "<b>". Core::$symbol." ".number_format($total,2,".",",")."</b>";

          $acumulador += $total;

?>			

		</td>
		<td style="text-align: center"><?php echo $sell->created_at; ?></td>
		<td style="width:250px;">
		<?php if(isset($_SESSION["user_id"])):?>
        <a href="index.php?action=poscotization&id=<?php echo $sell->id; ?>&idpro=<?php echo $idpro; ?>" class="btn btn-sm btn-outline-info"><i class="bi-cart4"></i>Procesar</a>
		<a  target="_blank" href="cotization.php?id=<?php echo $sell->id ?>&cliente=<?php echo  $sell->person_id;?>&sucursal=<?php echo  $id_almacen;?>" class="btn btn-sm btn-outline-info"><i  class="bi-cart4"> Imprimir</i></a>
		<?php endif;?>
		<a href="index.php?view=delcot&id=<?php echo $sell->id; ?>" class="btn btn-sm btn-danger" data-coreui-toggle="tooltip" data-coreui-placement="top" title="Eliminar"><i class="bi-trash"></i></a>
		
		</td>
	</tr>

<?php endforeach; ?>

</table>
</div>
</div>

<div class="clearfix"></div>

	<?php
}else{
	?>
	<div class="jumbotron">
		<h2>No hay cotizaciones</h2>
		<p>No se ha realizado ninguna cotizacion.</p>
	</div>
	<?php
}

?>
<br><br><br><br><br><br><br><br><br><br>
	</div>
</div>
</section>