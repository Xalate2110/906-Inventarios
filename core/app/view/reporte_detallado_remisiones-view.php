<section class="content"> 
<div class="row">
	<div class="col-md-12">

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        $('.js-example-basic-single').select2();
    });
</script>

<h1><i class='bi-cart4'></i> Reporte Detallado Remisiones</h1>

	<form id="filter_reporte_detallado_remisiones">
	        <input type="hidden" name="view" value="sells">
          
          <div class="row">
	<div class="col-md-2">
		<label>Almacen</label>
		<?php if(Core::$user->kind==1):?>
		<?php $clients = StockData::getAll();?>
		
		<select name="stock_id" class="form-control">
			<option value="">-- ALMACEN--</option>
		    <?php foreach($clients as $client):?>
      <option value="<?php echo $client->id;?>"><?php echo $client->name;?></option>
    <?php endforeach;?>
		</select>
		<?php else:?>
      <input type="hidden" name="stock_id" value="<?php echo StockData::getPrincipal()->id; ?>">
      <p class="form-control"><?php echo StockData::getPrincipal()->name; ?></p>
		<?php endif;?>
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
<div class="form-group">
    <div class="col-lg-30">
    <label for="inputEmail1" class="control-label">Buscar por Cliente</label>
    <?php 
	$clients = PersonData::getClients2();
    ?>


<select class="client_id form-control"  name="client_id" id="client_id">
<option value="0">Selecciona el Cliente</option>

                                    </select>
                               
                                    <script type="text/javascript">
                                      $('.client_id').select2({
                                       
                                    placeholder: 'Elige Cliente',
                                    ajax: {
                                     url: 'ajax.php',
                                     dataType: 'json',
                                     delay: 250,
                                     processResults: function (data) {
                                       console.log(data);
                                     return {
                                     results: data
                                     };
                                     },
                                     cache: true
                                       }
                                      });
                                    </script>
                                    
                            
                                </div>
                             </div> 

							 </div>





<!--
    <select name="proveedor_id" id = "proveedor_id" class="form-control js-example-basic-single">
    <option value="0">-- Todos Los Clientes --</option>
    <?php foreach($clients as $client):?>
      <option value="<?php echo $client->id;?>"><?php echo $client->name." ".$client->lastname;?></option>
    <?php endforeach;?>
      </select>
    </div>
  </div>  
  </div>

  -->

  <div class="col-md-2">
  <label for="inputEmail1" class="control-label">Buscar Por Producto</label>
  <?php 
   $products = ProductData::getAll(); 
  ?>
  <select name="product_id" class="form-control  js-example-basic-single">
	<option value="0">-- Todos Los Productos --</option>
	<?php foreach($products as $p):?>
	<option value="<?php echo $p->id;?>"><?php echo $p->name;?></option>
	<?php endforeach; ?>
</select>
</div>


	<div class="col-md-2">
    <br>
		<input type="submit" value="Aplicar Filtro" class="btn btn-primary">
	</div>
  </div>
  </form>


<div class="filter_reporte_detallado_remisiones"></div>

<script type="text/javascript">
	$(document).ready(function(){
		$.get("./?action=filter_reporte_detallado_remisiones",$("#filter_reporte_detallado_remisiones").serialize(),function(data){
			$(".filter_reporte_detallado_remisiones").html(data);
		});

		$("#filter_reporte_detallado_remisiones").submit(function(e){
			e.preventDefault();
		$.get("./?action=filter_reporte_detallado_remisiones",$("#filter_reporte_detallado_remisiones").serialize(),function(data){
			$(".filter_reporte_detallado_remisiones").html(data);
		});

		})});
</script>







	</div>
</div>
</section>