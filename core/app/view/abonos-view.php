<section class="content"> 
<div class="row">
	<div class="col-md-12">

<?php 
if(isset($_SESSION["client_id"])):?>
		<h1><i class='bi-shopping-cart'></i> Mis Abonos</h1>
<?php else:?>
		<h1><i class='bi-cart4'></i> Registro de Abonos y Anticipos</h1>




<?php endif;?>
		<div class="clearfix"></div>

<form id="filterabonos">
	<input type="hidden" name="view" value="abonos">
<div class="row">
	<div class="col-md-2">
		<label>Almacen</label>
		<?php if(Core::$user->kind==1):?>
		<?php $clients = RazonData::getAll();?>
		
		<select name="stock_id" class="form-control">
			<option value="">Selecciona Razón Social</option>
		    <?php foreach($clients as $client):?>
      <option value="<?php echo $client->id;?>"><?php echo $client->razonsocial;?></option>
    <?php endforeach;?>
		</select>
		<?php else:?>
      <input type="hidden" name="stock_id" value="<?php echo RazonData::getPrincipal()->id; ?>">
      <p class="form-control"><?php echo Razonata::getPrincipal()->razonsocial; ?></p>
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
		<label>Aplicar Filtro</label><br>
		<input type="submit" value="Aplicar Filtro" class="btn btn-primary btn-block">
	</div>
</div>

</form>

<div class="col-md-2">
  <!-- Button trigger modal -->

 
      </div>
	

<div class="allfilterabonos"></div>


<script type="text/javascript">
	$(document).ready(function(){
		$.get("./?action=filterabonos",$("#filterabonos").serialize(),function(data){
			$(".allfilterabonos").html(data);
		});

		$("#filterabonos").submit(function(e){
			e.preventDefault();
		$.get("./?action=filterabonos",$("#filterabonos").serialize(),function(data){
			$(".allfilterabonos").html(data);
		});

		})
	});
</script>
	</div>
</div>
</section>

