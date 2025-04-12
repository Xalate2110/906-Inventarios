
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
          Listado Compras A Credito
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">

<div class="row">
	<div class="col-md-12">

<div class="row">
  <div class="col-md-3">
<div class="btn-group">

</div>
</div>
<div class="col-md-9">

<form class="form-horizontal" id="compras_credito">
  <div class="form-group">
    <div class="col-sm-6">
      <input type="text" name="q" class="form-control" id="inputEmail3" placeholder="Nombre Del Cliente">
    </div>

<!--
    <div class="col-sm-3">
    <select name="category_id" class="form-control">
      <option value="">-- CATERGORIA--</option>
      <?php foreach(CategoryData::getAll() as $stock):?>
        <option value="<?php echo $stock->id; ?>"><?php echo $stock->name; ?></option>
      <?php endforeach; ?>
    </select>    
  </div> -->

    <div class="col-sm-2">
      <button type="submit" class="btn btn-primary">Buscar Cliente</button>
    </div>
    </div>
    </form>
    </div>
    </div>

<!--
<a href="./report/credit-word.php" class="btn btn-default"> <i class="fa fa-file-text"></i> Descargar Word (.docx)</a>
    <a href="./report/credit-excel.php" class="btn btn-default"> <i class="fa fa-file-text"></i> Descargar Excel (.xlsx)</a>
 -->
<div class="allfiltercreditos">

	</div>
</div>
</section><!-- /.content -->
<script type="text/javascript">
  $(document).ready(function(){
      $(".allfiltercreditos").html("<i class='fa fa-refresh fa-spin'></i>");
    $.get("./?action=compras_credito",$("#compras_credito").serialize(),function(data){
      $(".allfiltercreditos").html(data);
    });

    $("#compras_credito").submit(function(e){
      e.preventDefault();
      $(".allfiltercreditos").html("<i class='fa fa-refresh fa-spin'></i>");
    $.get("./?action=compras_credito",$("#compras_credito").serialize(),function(data){
      $(".allfiltercreditos").html(data);
    });

    })
  });
</script>

