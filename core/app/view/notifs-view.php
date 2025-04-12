<section class="content"> 
<div class="row">
	<div class="col-md-12">

<?php if(isset($_SESSION["client_id"])):?>
		<h1><i class='bi-flash'></i> Mis Compras</h1>
<?php else:?>
		<h1><i class='bi-flash'></i> Devoluciones Pendientes</h1>
<?php endif;?>
		<div class="clearfix"></div>


<?php
$products = null;
if(isset($_SESSION["user_id"])){
if(Core::$user->kind==3){
$products = SellData::getAllBySQL(" where user_id=Core::$user->id and operation_type_id=5 and status=0 order by created_at desc");

}
else if(Core::$user->kind==2){
$products = SellData::getAllBySQL(" where operation_type_id=5 and status=0 and stock_to_id=Core::$user->stock_id order by created_at desc");
}
else{
$products = SellData::getAllBySQL(" where operation_type_id=5 and status=0");

}
}else if(isset($_SESSION["client_id"])){
$products = SellData::getAllBySQL(" where person_id=$_SESSION[client_id] and operation_type_id=5 and status=0 order by created_at desc");	
}

if(count($products)>0){
?>
<br>
<div class="card card-primary">
<div class="card-header">
<span class="card-title"> Devoluciones Pendiendes De Autorizar</span>
</div>
<div class="card-body">
<table class="table table-bordered table-hover table-responsive datatable">
	<thead>
		<th style="text-align: center">Detalle</th>
		<th style="text-align: center">Origen</th>	
		<th style="text-align: center">Devolución</th>	
		<th style="text-align: center">Cantidad</th>
		<th style="text-align: center">Total</th>
		<th style="text-align: center">Nombre Cliente</th>
		<th style="text-align: center">Vendedor</th>
		<th style="text-align: center">Almacen Regreso</th>
		<th style="text-align: center">Regreso</th>
		<th style="text-align: center">Acción Remisión</th>
		<th style="text-align: center">Acciones</th>
	    </thead>
	    <?php foreach($products as $sell):
		$operations = OperationData::getAllProductsBySellId2($sell->id);
		?>

	<tr>
		<td style="width:30px;text-align: center">
		<a href="index.php?view=onedev&id=<?php echo $sell->id; ?>" class="btn btn-sm btn-secondary"><i class="bi-eye"></i></a></td>
		<td style="text-align: center"><?php echo $sell->sell_from_id; ?></td>
		<td style="text-align: center"><?php echo $sell->id; ?></td>
		<td style="text-align: center">
		<?php
		$operations = OperationData::getAllProductsBySellId2($sell->id);
		$productos=0;
		foreach ($operations as $operation) {
		$productos+=$operation->q;
		}
		echo $productos;
		?>		
		</td>

		<td style="text-align: center"><?php
		$operations = OperationData::getAllProductsBySellId2($sell->id);
		$total=0;
		foreach ($operations as $operation) {
		$total+=$operation->q*$operation->price_out;}
		echo "<b>$ ".number_format($total,2,".",",")."</b>";
		?>			
		</td>
    	<td style="text-align: center"> <?php if($sell->person_id!=null){$c= $sell->getPerson();echo utf8_encode($c->name." ".$c->lastname);} ?> </td>
	    <td style="text-align: center"> <?php if($sell->user_id!=null){$c= $sell->getUser();echo utf8_encode($c->name." ".$c->lastname);} ?> </td>
		<td style="text-align: center"><?php echo $sell->getStockTo()->name; ?></td>
		<td style="text-align: center"><?php echo $sell->created_at; ?></td>
		<td style="text-align: center"> <?php 
		if($sell->parcial_dev == 2){
		echo "Remisión Parcial";
		}else if ($sell->cerrada_dev == 1){
		echo "Remisión Cerrada";
		}
		 ?> 
		</td>
		<td style="width:130px;text-align: center">
		<?php if(isset($_SESSION["user_id"])):?>

		<a href="index.php?view=applydev&id=<?php echo $sell->id; ?>&origen=<?php echo $sell->sell_from_id; ?>&cantidad=<?php echo $productos ?>&cerrada_dev=<?php echo $sell->cerrada_dev ?>"  onclick="return procesar();" class="btn btn-sm btn-primary"><i class="bi-check"></i><a>
		<a href="index.php?view=deldev&id=<?php echo $sell->id; ?>" class="btn btn-sm btn-danger"><i class="bi-trash"></i><a>
        <?php endif;?>
		</td>
	</tr>

	<script>
		function procesar() {
        if (confirm("¿Deseas autorizar la devolución? Al realizarlo la mercancia saldrá de su almacen de origen y recalculara la exsitencia y el monto de la venta.")) {
        return true;
        } else {
        return false;}}
        </script>

<?php endforeach; ?>

</table>
</div>
</div>

<div class="clearfix"></div>

	<?php
}else{
	?>
	<div class="jumbotron">
		<h2>No hay Devoluciones Pendientes</h2>
		<p>No se ha realizado ninguna devolucion.</p>
	</div>
	<?php
}

?>
	</div>
</div>
</section>


