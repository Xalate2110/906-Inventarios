<section class="content">
<div class="row">
	<div class="col-md-12">
<div class="btn-group pull-right">
 

</div>
		<h1><i class='glyphicon glyphicon-shopping-cart'></i> Listado General Entradas</h1>
		<div class="clearfix"></div>
<form id="filteris">
  <input type="hidden" name="view" value="sells">
<div class="row">
  <div class="col-md-2">
    <label>Almacen</label>
    <select name="stock_id" class="form-control" required>
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
<br>

<div class="allfilteris">
</div>


<script type="text/javascript">
  $(document).ready(function(){
    $.get("./?action=filteris",$("#filteris").serialize(),function(data){
      $(".allfilteris").html(data);
    });

    $("#filteris").submit(function(e){
      e.preventDefault();
    $.get("./?action=filteris",$("#filteris").serialize(),function(data){
      $(".allfilteris").html(data);
    });

    })
  });
</script>

  </div>
</div>
</section>