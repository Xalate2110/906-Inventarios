<section class="content">
<div class="row">
	<div class="col-md-12">
    <h1><i class='bi-cart4'></i> Listado General De Compras</h1>



		<div class="clearfix"></div>
<form id="filterres">
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
  <br><br>

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


<div class="allfilterres">
</div>


<script type="text/javascript">
  $(document).ready(function(){
    $.get("./?action=filterres",$("#filterres").serialize(),function(data){
      $(".allfilterres").html(data);
    });

    $("#filterres").submit(function(e){
      e.preventDefault();
    $.get("./?action=filterres",$("#filterres").serialize(),function(data){
      $(".allfilterres").html(data);
    });

    })
  });
</script>

  </div>
</div>
</section>