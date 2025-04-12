<section class="content"> 
<div class="row">
	<div class="col-md-12">

<?php 
if(isset($_SESSION["client_id"])):?>
		<h1><i class='bi-shopping-cart'></i> Mis Compras</h1>
<?php else:?>
		<h1><i class='bi-cart4'></i> Cotizaciónes Procesadas</h1>

		<br>

<?php endif;?>
		<div class="clearfix"></div>

<form id="filtercotprocesadas">
	<input type="hidden" name="view" value="sells">
<div class="row">
	<div class="col-md-2">
		<label>Almacen</label>
		<select name="stock_id" class="form-control">
			<option value="">-- ALMACEN--</option>
			<?php foreach(StockData::getAll() as $stock):?>
				<option value="<?php echo $stock->id; ?>"><?php echo $stock->name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="col-md-2">
		<label>Fecha inicio</label>
		<input type="date" name="start_at" value="<?php echo date('Y-m-d'); ?>" required class="form-control">
	</div>
	<div class="col-md-2">
		<label>Fecha fin</label>
		<input type="date" name="finish_at" value="<?php echo date('Y-m-d'); ?>" required class="form-control">
	</div>
	<div class="col-md-2">
		<label>Aplicar Filtro</label><br>
		<input type="submit" value="Aplicar Filtro" class="btn btn-primary btn-block">
	</div>

</div>
</form>




<div class="allfiltercotprocesadas">
</div>


<script type="text/javascript">
	$(document).ready(function(){
		$.get("./?action=filtercotprocesadas",$("#filtercotprocesadas").serialize(),function(data){
			$(".allfiltercotprocesadas").html(data);
		});

		$("#filtercotprocesadas").submit(function(e){
			e.preventDefault();
		$.get("./?action=filtercotprocesadas",$("#filtercotprocesadas").serialize(),function(data){
			$(".allfiltercotprocesadas").html(data);
		});

		})
	});
</script>
	</div>
</div>
</section>