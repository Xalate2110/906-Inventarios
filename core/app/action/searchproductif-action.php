<?php if(isset($_GET["product"])):?>
	<?php
$products = ProductData::getLike2($_GET["product"]);
if(count($products)>0){
	?>
    <div class="card box-primary">
<div class="card-header">
Listado de productos para salida
</div>
<div class="box box-primary table-responsive">
<table class="table table-bordered table-hover table-responsive datatable">
	<thead>
		<th style="text-align: center;">Codigo</th>
		<th style="text-align: center;">Nombre</th>
		<th style="text-align: center;">Existencia</th>
		<th style="text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Precio Entrada 
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Precio Salida
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cantidad</th>
	</thead>
	<?php
     $products_in_cero=0;
	 foreach($products as $product):
      $q= OperationData::getQByStock($product->id,StockData::getPrincipal()->id);
	  ?>
	<tr class="<?php if($q<=$product->inventary_min){ echo "danger"; }?>">
		<td style="text-align: center;"><?php echo $product->code; ?></td>
		<td style="text-align: center;"><?php echo $product->name; ?></td>
	    <td style="text-align: center;">
		<?php echo $q; ?>
		</td>
		<td style="text-align: center;">
		<form method="post"  id="addtoif<?php echo $product->id; ?>">
		<div class="row">
		<div class="col-md-3">
		<div class="input-group">
		
		<input type="text" style="text-align: center;" class="form-control" name="price_in" placeholder="$ Precio Entrada" value="<?php echo $product->price_in; ?>">
		</div>
		</div>
		<div class="col-md-3">

		<div class="input-group">
	
		<input type="text" style="text-align: center;" class="form-control" name="price_out" placeholder="$ Precio Salida" value="<?php echo $product->price_out; ?>">
		</div>
		</div>
		<div class="col-md-3">


		<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
		<input type="number" max="<?php echo $q;?>" step="any"  class="form-control" required name="q" id="re_q<?php echo $product->id; ?>" placeholder="Cantidad a Salir">
</div>
		<div class="col-md-3">
		<button type="submit" class="btn btn-sm btn-success"><i class="glyphicon glyphicon-ok"></i> Agregar Producto</button>
</div>
</div>
	</form>
		</td>
	</tr>
<script>
		$("#addtoif<?php echo $product->id; ?>").on("submit",function(e){
		e.preventDefault();
			$.post("./?view=addtoif",$("#addtoif<?php echo $product->id; ?>").serialize(),function(data){
				$.get("./?action=cartofif",null,function(data2){
					$("#cartofif").html(data2);
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
