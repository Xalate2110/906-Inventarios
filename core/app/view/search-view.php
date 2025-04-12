
<section class="content">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>

<script>
$(document).ready(function () {
$('.js-example-basic-single').select2();
});
</script>

<div class="row">
	<div class="col-md-12">
<!-- Single button -->

<h1><i class="glyphicon glyphicon-stats"></i> Buscar existencia de productos</h1>
<form class="form-inline">
<input type="hidden" name="view" value="search">
  <div class="form-group">
    <label class="sr-only" for="exampleInputEmail3">Escribe el nombre del producto</label>
    <!-- <input type="text" name="q" required class="form-control" id="exampleInputEmail3" placeholder="Buscar ..."> -->
    <?php 
			$products = ProductData::getAll2(); 
			?>
			<select name="q" id="q" class="form-control  js-example-basic-single">
				<option value="0">-- Todos Los Productos --</option>
				<?php foreach($products as $p):?>
					<option value="<?php echo $p->id;?>"><?php echo $p->code;?> - <?php echo $p->marca;?> - <?php echo utf8_encode($p->name);?></option>
				<?php endforeach; ?>
			</select>
      </div>
  <br>
  <button type="submit" class="btn btn-outline-dark">Buscar Producto</button>
</form>

<!-- <a onclick="thePDF()" class="btn btn-default">Descargar PDF</a><br><br> -->
<?php if(isset($_GET["q"]) && $_GET["q"]!=""):?>
<?php
$products = ProductData::getLikeExistencias($_GET["q"]);
$sucursales = StockData::getAll();
if(count($products)>0){
	?>
<br>
<div class="card box-primary">
<div class="card-header">
<span class="box-title">Litado de existencias de producto en sucursales</span></div>
<div class="card-body">
<div class="box box-primary table-responsive">
<table class="table table-bordered table-hover table-responsive datatable">
	<thead>
		<th style="text-align: center">Codigo</th>
		<th style="text-align: center">Nombre</th>
    <?php foreach($sucursales as $suc):?>
		<th style="text-align: center"><?php echo $suc->name; ?></th>
    <?php endforeach; ?>
	</thead>
	<?php foreach($products as $product):?>
	<tr>
		<td style="text-align: center"><?php echo $product->code; ?></td>
		<td style="text-align: center"><?php echo $product->name; ?></td>
    <?php foreach($sucursales as $suc):?>
		<td style="text-align: center">
			<?php 
  $q=OperationData::getQByStock($product->id,$suc->id);
      echo $q; ?>
		</td>
    <?php endforeach; ?>
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
		<p>No se encuentran productos con el termino de busqueda, por favor intente otro..</p>
	</div>
	<?php
}
endif; 
?>
<br><br><br><br><br><br><br><br><br><br>
	</div>
</div>
</section>




