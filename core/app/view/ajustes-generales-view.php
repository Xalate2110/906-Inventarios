<section class="content">
<div class="row">
	<div class="col-md-12">

</div>
		<h1><i class='glyphicon glyphicon-shopping-cart'></i> Listado Ajustes Generales </h1>
		<div class="clearfix"></div>
<form id="filterge">
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
    <input type="submit" value="Aplicar Filtro" class="btn btn-primary">
  </div>

</div>
</form>

<div class="allfilterge">
</div>


<script type="text/javascript">
  $(document).ready(function(){
    $.get("./?action=filterge",$("#filterge").serialize(),function(data){
      $(".allfilterge").html(data);
    });

    $("#filterge").submit(function(e){
      e.preventDefault();
    $.get("./?action=filterge",$("#filterge").serialize(),function(data){
      $(".allfilterge").html(data);
    });

    })
  });
</script>

  </div>
</div>
</section>