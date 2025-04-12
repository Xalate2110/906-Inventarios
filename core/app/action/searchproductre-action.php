<?php if(isset($_GET["product"])):?>
<?php
   $products = ProductData::getLike2($_GET["product"]);

if(count($products)>0){
	?>
<div class="card box-primary">
<div class="card-header">
Listado Ventas. 
</div>
<div class="box box-primary table-responsive">
<table class="table table-bordered table-hover table-responsive datatable">
	<thead>
		<th style="text-align: center">Codigo</th>
		<th style="text-align: center">Nombre</th>
		<th style="text-align: center">Existencia Actual</th>
		<th style="text-align: left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Precio Compra &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Precio Venta &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cantidad</th>
	</thead>
	<?php
$products_in_cero=0;
	 foreach($products as $product):
$q= OperationData::getQByStock($product->id,StockData::getPrincipal()->id);
	?>
	<tr class="<?php if($q<=$product->inventary_min){ echo "danger"; }?>">
		<td style="text-align: center"><?php echo $product->code; ?></td>
		<td style="text-align: center"><?php echo $product->name; ?></td>
		<td style="text-align: center">
		<?php echo $q; ?>
		</td>
		<td style="text-align: center">
		<form method="post"  id="addtore<?php echo $product->id; ?>">
		<div class="row">
		<div class="col-md-3">
<div class="input-group">

  <input style="text-align: center" type="text" class="form-control" name="price_in" placeholder=" Precio Compra" value="<?php echo $product->price_in; ?>">
</div>
</div>
		<div class="col-md-3">

<div class="input-group">

  <input style="text-align: center" type="text" class="form-control" name="price_out" placeholder="Precio Venta" value="<?php echo $product->price_out; ?>">
</div>
</div>
		<div class="col-md-3">


		<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
		<input type="text" class="form-control" required name="q" id="re_q<?php echo $product->id; ?>" placeholder="Cantidad de producto ...">
</div>
		<div class="col-md-3">
		<button type="submit" class="btn btn-sm btn-success"><i class="bi-plus-square"></i> Agregar</button>
</div>
</div>
	</form>
		</td>
	</tr>
<script>
		$("#addtore<?php echo $product->id; ?>").on("submit",function(e){
		e.preventDefault();
			$.post("./?view=addtore",$("#addtore<?php echo $product->id; ?>").serialize(),function(data){
				$.get("./?action=cartofre",null,function(data2){
					$("#cartofre").html(data2);
				});
			});
		$("#re_q<?php echo $product->id; ?>").val("");

	});
</script>
	<?php endforeach;?>
</table>
</div>
	<?php
}
?>
<?php else:
?>

<?php endif; ?>
