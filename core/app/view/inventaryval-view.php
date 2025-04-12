<?php
$stock = StockData::getById($_GET["stock"]);
?>
<section class="content">
<div class="row">
	<div class="col-md-12">
<!-- Single button -->
    <h1><i class="glyphicon glyphicon-stats"></i> Valor del Inventario <small><?php echo $stock->name; ?></small></h1>
<div class="btn-group">

</div>
<?php if(Core::$user->kind==1):?>
<?php foreach(StockData::getAll() as $stock):?>
  <a class="btn btn-outline-dark" href="./?view=inventaryval&stock=<?php echo $stock->id; ?>"><?php echo $stock->name; ?></a>
<?php endforeach;?>
<?php endif; ?>
<br><br>
<?php
$products = ProductData::getAll();
if(count($products)>0){
	?>
<div class="clearfix"></div>
<div class="card">
  <div class="card-header">
    <span class="box-title">Valor del Inventario</span>

  </div><!-- /.box-header -->
  <div class="card-body">
  <table class="table table-bordered datatable table-hover">
	<thead>
		<th style="text-align: center">Codigo</th>
		<th style="text-align: center">Descripción Del Producto</th>
		<th style="text-align: center">Existencia Actual</th>
    <th style="text-align: center">Precio Ultima Compra</th>
		<th style="text-align: center">Valor Actual</th>
		<!--<th>Acciones</th>-->
	</thead>
	<?php 
$ttin=0;
$ttout=0;
$ttou2=0;
  foreach($products as $product):
	$r=OperationData::getRByStock($product->id,$_GET["stock"]);
	$q=OperationData::getQByStock($product->id,$_GET["stock"]);
	$d=OperationData::getDByStock($product->id,$_GET["stock"]);
	?>
	<tr class="<?php if($q<=$product->inventary_min/2){ echo "danger";}else if($q<=$product->inventary_min){ echo "warning";}?>">
		<td style="text-align: center"><?php echo $product->code; ?></td>
		<td style="text-align: center"><?php echo $product->name; ?></td>
		<td style="text-align: center">
			<?php echo $q; ?>
		</td>
		<td style="text-align: center">
		<?php echo Core::$symbol; ?>			<?php 
		$otin = OperationData::getAllByOT($product->id,1,$_GET["stock"]);
		$otout = OperationData::getAllByOT($product->id,2,$_GET["stock"]);
		$totin = 0;
		$totout = 0;
		$totout2 = 0;


		foreach ($otin as $o) {
		$totin=$o->price_in;
		}$total =$totin * $q;
		echo number_format($totin,2,".",",");
		?>
 	</td>
    <td style="text-align: center"><?php echo Core::$symbol; ?> <?php echo number_format($total,2,".",","); ?></td>

<!--
		<td style="width:93px;">
	<a href="index.php?view=input&product_id=<?php echo $product->id; ?>" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-circle-arrow-up"></i> Alta</a>
 <a href="index.php?view=history&product_id=<?php echo $product->id; ?>&stock=<?php echo $_GET["stock"];?>" class="btn btn-sm btn-success"><i class="glyphicon glyphicon-time"></i> Historial</a> 
		</td>-->
	</tr>
	<?php endforeach;?>
    </table>


  </div><!-- /.box-body -->
</div><!-- /.box -->



<div class="clearfix"></div>

	<?php
}else{
	?>
	<div class="jumbotron">
		<h2>No hay productos</h2>
		<p>No se han agregado productos a la base de datos, puedes agregar uno dando click en el boton <b>"Agregar Producto"</b>.</p>
	</div>
	<?php
}

?>
<br><br><br><br><br><br><br><br><br><br>
	</div>
</div>
</section>



