<section class="content">
<div class="row">
	<div class="col-md-12">
		<!--<form id="processbox">-->
			<div class="btn-group pull-right">
        </script>
			</div>
			<h1><i class='fa fa-archive'></i> Listado Cortes de Caja</h1>
	
	  		<div class="clearfix"></div>

			<form id="filtercortes">
			
	     	<div class="row">
	    	<div class="col-md-2">
	    	<label>Almacen</label>
	    	<select name="stock_id" id ="stock_id" class="form-control">
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
		<input type="submit" value="Aplicar Filtro" class="btn btn-primary">
	</div>

		
</div>
</form>

		<div class="allfiltercortes"></div>

		<script type="text/javascript">

			$(document).ready(function(){
				$.get("./?action=filtercortes",$("#filtercortes").serialize(),function(data){
					if(data){
						$(".allfiltercortes").html(data);
					}
				});

				$("#filtercortes").submit(function(e){
					e.preventDefault();
					$.get("./?action=filtercortes",$("#filtercortes").serialize(),function(data){
						$(".allfiltercortes").html(data);
					});
				})
			});

		</script>
	<!--Codigo Recortado-->
	</div>
</div>
</section>