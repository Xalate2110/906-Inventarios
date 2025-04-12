<?php
// $symbol = ConfigurationData::getByPreffix("currency")->val;
if(!isset($_SESSION["stock_id"])){ Core::redir("./?view=selectstock"); }
$iva_name = ConfigurationData::getByPreffix("imp-name")->val;
$iva_val = ConfigurationData::getByPreffix("imp-val")->val;
$origin = StockData::getById($_SESSION["stock_id"]);
?>
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
	<h1>Traspaso Entre Sucursales</h1>
	<h4>Origen: <?php echo $origin->name; ?></h4>
	<p><b>Buscar producto por nombre o por codigo:</b></p>
		<form>
		<div class="row">
			<div class="col-md-6">
				<input type="hidden" name="view" value="traspase">
				<!--<input type="text" name="product" class="form-control"> -->
				<?php 
			$products = ProductData::getAll2(); 
			?>
			<select name="product" id="product" class="form-control  js-example-basic-single">
				<option value="0">Escribe el nombre del producto</option>
				<?php foreach($products as $p):?>
				<option value="<?php echo $p->id;?>"><?php echo $p->code;?> - <?php echo $p->marca;?> - <?php echo utf8_encode($p->name);?></option>
				<?php endforeach; ?>
			</select>
			</div>
			<div class="col-md-3">
			<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> Buscar</button>
			</div>
		</div>
		</form>
	</div>
	<div class="col-md-12">

<?php if(isset($_GET["product"])):?>
	<?php
$products = ProductData::getLikeTraspaso($_GET["product"]);
if(count($products)>0){
	?>
<h3>Resultados de la Busqueda</h3>
<div class="box box-primary table-responsive">
<table class="table table-bordered table-hover">
	<thead>
		<th style="text-align: center">Codigo</th>
		<th style="text-align: center">Nombre</th>
		<th style="text-align: center">Unidad</th>
		<th style="text-align: center">Precio unitario</th>
		<th style="text-align: center">En inventario</th>
		<th style="text-align: center">Cantidad</th>
		<th style="text-align: center">Acción</th>
	</thead>
	<?php
		$products_in_cero=0;
	 	foreach($products as $product):

		$q= OperationData::getQByStock($product->id,$_SESSION["stock_id"]);?>
		<form method="post" action="index.php?view=addtotraspase">
	    <tr class="<?php if($q<=$product->inventary_min){ echo "danger"; }?>">
		<td style="text-align: center"><?php echo $product->code; ?></td>
		<td style="text-align: center"><?php echo $product->name; ?></td>
		<td style="text-align: center"><?php echo $product->unit; ?></td>
		<td style="text-align: center"><b><?php echo Core::$symbol; ?> <?php echo $product->price_in; ?></b></td>
		<td style="text-align: center">
		<?php echo $q; ?>
		</td>
		<td style="text-align: center">
		<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
		<input type="" class="form-control" required name="q" placeholder="Cantidad de producto ..."></td>
		<td style="text-align: center">
		<button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-refresh"></i> Agregar</button>
		</td>
	</tr>
	</form>
	<?php endforeach;?>
</table>
</div>
	<?php
}
?>
<br><hr>
<hr><br>
<?php else:
?>

<?php endif; ?>
</div>
	<div class="col-md-12">



<!--- Carrito de compras :) -->
<?php if(isset($_SESSION["traspase"])):
$total = 0;
?>
<br>
<h2>Listado de productos para traspaso.</h2>
<div class="box box-primary table-responsive">
<table class="table table-bordered table-hover">
<thead>
	<th style="text-align: center">Codigo</th>
	<th style="text-align: center">Cantidad</th>
	<th style="text-align: center">Unidad</th>
	<th style="text-align: center">Producto</th>
	<th style="text-align: center">Precio Unitario</th>
	<th style="text-align: center">Precio Total</th>
	<th style="text-align: center">Acciones</th>
</thead>
<?php foreach($_SESSION["traspase"] as $p):
$product = ProductData::getById($p["product_id"]);
?>
<tr >
	<td style="text-align: center"><?php echo $product->code; ?></td>
	<td style="text-align: center"><?php echo $p["q"]; ?></td>
	<td style="text-align: center"><?php echo $product->unit; ?></td>
	<td style="text-align: center"><?php echo $product->name; ?></td>
	<td style="text-align: center"><b><?php echo Core::$symbol; ?> <?php echo number_format($product->price_in); ?></b></td>
	<td style="text-align: center"><b><?php echo Core::$symbol; ?> <?php  $pt = $product->price_in*$p["q"]; $total +=$pt; echo number_format($pt); ?></b></td>
	<td style="text-align: center"><a href="index.php?view=cleartraspase&product_id=<?php echo $product->id; ?>" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a></td>

</tr>
<?php endforeach; ?>
</table>
</div>
<form method="post" class="form-horizontal" id="processsell" action="index.php?view=procesarTraspasoPendiente">
<div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Almacén Destino</label>
    <div class="col-lg-10">
    <?php 
    $clients = StockData::getAll();
    ?>
    <select name="stock_id" class="form-control" required>
    <option value="">-- NINGUNO --</option>
    <?php foreach($clients as $client):?>
    	<?php if($client->id!=$_SESSION["stock_id"]):?>
    	<option value="<?php echo $client->id;?>"><?php echo $client->name;?></option>
    <?php endif; ?>
    <?php endforeach;?>
    	</select>
    </div>
  </div>
  <br>

<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <div class="checkbox">
        <label>
		<a href="index.php?view=cleartraspase" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a>
       <!-- <button class="btn btn-primary"><i class="fa fa-refresh"></i> Procesar Traspaso</button> -->
	   <button class="btn btn-primary" onclick="return salida();"><i class="fa fa-refresh"></i> Generar Traspaso</button>
        </label>
      </div>
    </div>
  </div>
</form>
</div>
</div>
</div>

<script>
function salida() {
if (confirm("¿Desaea Aplicar El Traspaso?, Al realizarlo el traspaso entrará al proceso de Entransito, para autorizar la entrada al almacen Destino")) {
return true;
} else {
return false;

}
}
</script>

<br><br><br><br><br>
<?php endif; ?>

</div>
</section>