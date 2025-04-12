<section class="content"> 
<div class="row">
	<div class="col-md-12">
	<h1><i class='bi-cart4'></i> Reporte Utilidad Facturas</h1>

	<form id="filter_utilidad_facturas">
	        <input type="hidden" name="view" value="sells">
            <div class="row">
	         <div class="col-md-2">
		<label>  &nbsp;&nbsp;Almacen</label>
		<select name="stock_id" id = "stock_id" class="form-control"  required class="form-control">
		&nbsp;&nbsp;
			<option value="">-- ALMACEN--</option>
			<?php foreach(StockData::getAll() as $stock):?>
				<option value="<?php echo $stock->id; ?>"><?php echo $stock->name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>

	

	<div class="col-md-2">
		<label>Fecha inicio</label>
		<input type="date" name="start_at" id = "start_at" value="<?php echo date('Y-m-d'); ?>" required class="form-control">
	</div>

 
	<div class="col-md-2">
		<label>Fecha fin</label>
		<input type="date" name="finish_at" id = "finish_at" value="<?php echo date('Y-m-d'); ?>" required class="form-control">
	</div>
	<div class="col-md-2">
		<br>
		<input type="submit" value="Aplicar Filtros Seleccionados" class="btn btn-primary">
	</div>
  </div>
  </form>

  <br><br>
<div class="filter_utilidad_facturas"></div>

<script type="text/javascript">
	$(document).ready(function(){
		$.get("./?action=filter_utilidad_facturas",$("#filter_utilidad_facturas").serialize(),function(data){
			$(".filter_utilidad_facturas").html(data);
		});

		$("#filter_utilidad_facturas").submit(function(e){
			e.preventDefault();
		$.get("./?action=filter_utilidad_facturas",$("#filter_utilidad_facturas").serialize(),function(data){
			$(".filter_utilidad_facturas").html(data);
		});

		})});
</script>







	</div>
</div>
</section>