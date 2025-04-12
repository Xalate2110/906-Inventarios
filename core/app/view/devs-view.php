<section class="content"> 
<div class="row">
	<div class="col-md-12">

<?php if(isset($_SESSION["client_id"])):?>
		<h1><i class='bi-cart4'></i> Mis Compras</h1>
<?php else:?>
		<h1><i class='bi-cart4'></i> Listado General Devoluciones</h1>

<?php endif;?>
		<div class="clearfix"></div>


<?php
$products = null;
if(isset($_SESSION["user_id"])){
if(Core::$user->kind==3){
$products = SellData::getAllBySQL(" where user_id=Core::$user->id and operation_type_id=5 and is_draft=0 and p_id = 0 and d_id = 0 order by created_at asc");

}
else if(Core::$user->kind==2){
$products = SellData::getAllBySQL(" where operation_type_id=5 and is_draft=0 and stock_to_id=Core::$user->stock_id and p_id = 0 and d_id = 0 order by created_at asc");
}
else{
$products = SellData::getAllBySQL(" where operation_type_id=5");

}
}else if(isset($_SESSION["client_id"])){
$products = SellData::getAllBySQL(" where person_id=$_SESSION[client_id] and operation_type_id=5 and is_draft=0  and p_id = 0 and d_id = 0 order by created_at asc");	
}

$sucursal = StockData::getPrincipal()->id;
	if(count($products)>0){

	?>
<br>
<div class="card box-primary">
<div class="card-header">
<span class="box-title">Litado Devoluciones</span></div>
<div class="box box-primary table-responsive">
<table class="table table-bordered table-hover table-responsive datatable">
	<thead>
		<th style="text-align: center">Detalles</th>
		<th style="text-align: center">Folio</th>	
		<th style="text-align: center">Nombre Cliente</th>
		<th style="text-align: center">Vendedor</th>
		<th style="text-align: center">Almacen</th>
		<th style="text-align: center">Fecha</th>
		<th style="text-align: center">Comprobante</th>
		<th style="text-align: center">Cancelar</th>
	</thead>
	<?php foreach($products as $sell):
	$operations = OperationData::getAllProductsBySellId2($sell->id);
	?>


	<tr>
		<td style="text-align: center">
		<a href="index.php?view=onedev&id=<?php echo $sell->id; ?>" class="btn btn-sm btn-secondary"><i class="bi-eye"></i></a></td>
		<td style="text-align: center">#<?php echo $sell->id; ?></td>
	    <td style="text-align: center"> <?php if($sell->person_id!=null){$c= $sell->getPerson();echo utf8_encode($c->name." ".$c->lastname);} ?> </td>
	    <td style="text-align: center"> <?php if($sell->user_id!=null){$c= $sell->getUser();echo utf8_encode($c->name." ".$c->lastname);} ?> </td>
		<td style="text-align: center"><?php echo $sell->getStockTo()->name; ?></td>
		<td style="text-align: center"><?php echo $sell->created_at; ?></td>
		<td style="text-align: center">
		<a  target="_blank" href="comprobante_dev.php?id=<?php echo $sell->id;?>&cliente=<?php echo $sell->person_id ?>&sucursal=<?php echo $sucursal?>"  class="btn btn-xs btn-prymari" ><i class="bi-ticket"></i></a>
	    </td>
   
		<td style="text-align: center">
		<?php if(isset($_SESSION["user_id"])):?>
        <a href="index.php?action=canceldev&id=<?php echo $sell->id; ?>&total=<?php echo $sell->total;?>&origen=<?php echo $sell->sell_from_id?>" class="btn btn-sm btn-danger"><i  class="bi-trash"></i></a>
        <?php endif;?>
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
		<h2>No hay Devoluciones</h2>
		<p>No se ha realizado ninguna devolucion.</p>
	</div>
	<?php
}

?>
	</div>
</div>
</section>
