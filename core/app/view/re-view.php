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
	<h1>Reabastecer Inventario</h1>
	<p><b>Buscar producto por nombre o por codigo:</b></p>
		<form id="searchp">
		<div class="row">
			<div class="col-md-6">
			<input type="hidden" name="view" value="re">
			<!--<input type="text" name="product" id="product_name" class="form-control"> -->
			<?php 
			$products = ProductData::getAll2(); 
			?>
			<select name="product" id="product_name" class="form-control  js-example-basic-single">
				<option value="0">-- Todos Los Productos --</option>
				<?php foreach($products as $p):?>
				<option value="<?php echo $p->id;?>"><?php echo $p->code;?> - <?php echo $p->marca;?> - <?php echo utf8_encode($p->name);?></option>
				<?php endforeach; ?>
			</select>
			</div>
		
		
			<div class="col-md-3">
			<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> Producto </button>
			</div>
		</div>
		</form>
	</div>

	<br>
	<br>
<div class="col-md-12">
<div id="show_search_results"></div>
<script>
$(document).ready(function(){
	$("#searchp").on("submit",function(e){
		e.preventDefault();
	
    name = $("#product").val();
	brand_id = $("#brand_id").val();
	
	if(name!=""){
		$.get("./?action=searchproductre",$("#searchp").serialize(),function(data){
			$("#show_search_results").html(data);
		});
		$("#product_name").val("");
		$("#brand_id").val("");
    }else{
    	$("#show_search_results").html("");
    }

	});
	});



	</script>

</div>
	<div class="col-md-12">

<div id="cartofre"></div>


</div>
</section>
<script>
$(document).ready(function(){
$.get("./?action=cartofre",null,function(data){
$("#cartofre").html(data);
});
});
</script>