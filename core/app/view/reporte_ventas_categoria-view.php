<section class="content"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('.js-example-basic-single').select2();
    });
</script>

<div class="row">
	<div class="col-md-20">

<?php 
if(isset($_SESSION["client_id"])):?>
		<h1><i class='glyphicon glyphicon-shopping-cart'></i> Mis Compras</h1>
<?php else:?>
<!--
<div class="btn-group pull-right">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> Descargar <span class="caret"></span>
  </button>
  
  
  <ul class="dropdown-menu" role="menu">
  	    <?php if(Core::$user->kind==1):?>
    <li><a href="report/sells-word.php">Word 2007 (.docx)</a></li>
    <li><a href="report/sells-xlsx.php">Excel 2007 (.xlsx)</a></li>
<?php endif; ?>
<li><a onclick="thePDF()" id="makepdf" class="">PDF (.pdf)</a></li>
  </ul>
</div> -->

<br>

<div class="panel panel-default">
                        <div class="panel-heading">
						<h1><i class='glyphicon glyphicon glyphicon-shopping-cart'></i> Reporte Piezas Vendidas Por Producto </h1>
						</div>
					
						</div>
					
<?php endif;?>
		<div class="clearfix">
		</div>

<form id="filter_ventas_categoria">
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
		<input type="date" name="start_at" value="<?php echo date('Y-m-d'); ?>" required class="form-control">
	</div>
	<div class="col-md-2">
		<label>Fecha fin</label>
		<input type="date" name="finish_at" value="<?php echo date('Y-m-d'); ?>" required class="form-control">
	</div>

<div class="col-md-2">
  <label for="inputEmail1" class="control-label">Buscar Por Producto</label>
  <?php 
   $products = ProductData::getAll(); 
  ?>
  <select name="product_id" class="form-control  js-example-basic-single">
	<option value="0">-- Todos Los Productos --</option>
	<?php foreach($products as $p):?>
	<option value="<?php echo $p->id;?>"><?php echo $p->code;?> - <?php echo $p->name;?> - <?php echo $p->barcode;?></option>
	<?php endforeach; ?>
</select>
</div>

<div class="col-md-2">
  <label for="inputEmail1" class="control-label">Buscar Por Usuario</label>
  <select name="id_usuario" id = "id_usuario" class="form-control  js-example-basic-single">
	<option value="0">-- Todos Los usuarios --</option>
  <?php foreach (FUsuario::getUsuario() as $client): ?>
  <option value="<?php echo $client->id; ?>"><?php echo $client->name." ". $client->lastname; ?></option>
 <?php endforeach; ?>
</select>

</div> 

  <div class="col-md-2">
		<label>Aplicar Filtro</label><br>
		<input type="submit" value="Aplicar Filtro" class="btn btn-primary">
	</div>
</div>

</form>


<div class="filter_ventas_categoria"></div>


<script type="text/javascript">
	$(document).ready(function(){
		$.get("./?action=filter_ventas_categoria",$("#filter_ventas_categoria").serialize(),function(data){
			$(".filter_ventas_categoria").html(data);});

		$("#filter_ventas_categoria").submit(function(e){
			e.preventDefault();
			

		$.get("./?action=filter_ventas_categoria",$("#filter_ventas_categoria").serialize(),function(data){
			$(".filter_ventas_categoria").html(data);
		
	
		});

		})
	});
</script>
	</div>
</div>
</section>