<?php
$stock = StockData::getById($_GET["stock"]);
?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Inventario General: <?php echo $stock->name; ?>
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">

<div class="row">
  <div class="col-md-12">

<div class="row">
  <div class="col-md-3">
    <!--
<div class="btn-group pull-right">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> Descargar <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="report/inventary-word.php?stock_id=<?php echo $stock->id; ?>">Word 2007 (.docx)</a></li>
    <li><a href="report/inventary-xlsx.php?stock_id=<?php echo $stock->id; ?>">Excel 2007 (.xlsx)</a></li>
<li><a onclick="thePDF()" id="makepdf" class="">PDF (.pdf)</a>
  </ul>
</div>
-->
</div>
<div class="col-md-9">
<!--
<form class="form-horizontal" id="filterproducts">
  <div class="form-group">
    <div class="col-sm-3">
      <input type="text" name="q" class="form-control" id="inputEmail3" placeholder="Nombre">
    </div>

    <div class="col-sm-3">
    <select name="category_id" class="form-control">
      <option value="">-- CATERGORIA--</option>
      <?php foreach(CategoryData::getAll() as $cat):?>
        <option value="<?php echo $cat->id; ?>"><?php echo $cat->name; ?></option>
      <?php endforeach; ?>
    </select>    
  </div>

    <div class="col-sm-2">
      <button type="submit" class="btn btn-primary">Buscar</button>
    </div>

  </div>


</form>
-->

</div>
</div>


<div class="products_table_container">


<div class="card box-primary">
  <div class="card-header">
   Productos

  </div><!-- /.box-header -->
  <div class="card-body no-padding">
  <div class="box-body table-responsive">
<table class="table  table-bordered products_table table-hover">
  <thead>
    <th style="text-align: center">Codigo</th>
    <th style="text-align: center">Descripción Producto</th>
    <th style="text-align: center">Categoria</th>
    <th style="text-align: center">Productos Por Recibir</th>
    <th style="text-align: center">Disponible</th>
    <th style="text-align: center">Producto Por entregar</th>
  </thead>

</table>
</div>
  </div><!-- /.box-body -->
</div><!-- /.box -->

</div>


<script type="text/javascript">
 $(document).ready(function(){
        $(".products_table").DataTable({
            "processing": true,
            "serverSide": true,
            "searchable": true,
          ajax: "./?action=getinventaryajax&stock=<?php echo $stock->id;?>",
          "pageLength":25,
          "language": {
        "sProcessing":    "Procesando...",
        "sLengthMenu":    "Mostrar _MENU_ registros",
        "sZeroRecords":   "No se encontraron resultados",
        "sEmptyTable":    "Ningún dato disponible en esta tabla",
        "sInfo":          "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":     "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":  "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":   "",
        "sSearch":        "Buscar:",
        "sUrl":           "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":    "Último",
            "sNext":    "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    }
        });

      });
    </script>
<!-- YA NO SE USA PERO SE COMENTA POR SI ALGUIEN LO QUIERE USAR
<div class="allfilterproducts">


  </div>
-->
</div>
        </section><!-- /.content -->
<!-- YA NO SE USA PERO SE COMENTA POR SI ALGUIEN LO QUIERE USAR
<script type="text/javascript">
  $(document).ready(function(){
      $(".allfilterproducts").html("<i class='fa fa-refresh fa-spin'></i>");
    $.get("./?action=filterproducts",$("#filterproducts").serialize(),function(data){
      $(".allfilterproducts").html(data);
    });

    $("#filterproducts").submit(function(e){
      e.preventDefault();
      $(".allfilterproducts").html("<i class='fa fa-refresh fa-spin'></i>");
    $.get("./?action=filterproducts",$("#filterproducts").serialize(),function(data){
      $(".allfilterproducts").html(data);
    });

    })
  });
</script>
-->
<script type="text/javascript">
<?php if(isset($_SESSION["flash_success"])):?>
    swal({
    title:'Datos Guardados!',
    text:"Datos Guardados exitosamente!.",
    type:'success'
  })
<?php unset($_SESSION["flash_success"]);endif; ?>
</script>

