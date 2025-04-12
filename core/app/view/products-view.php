<?php if(isset($_GET["opt"]) && $_GET["opt"]=="all"):?>
        <!-- Content Header (Page header) -->
        <?php
        $stock = StockData::getById($_GET["stock"]);
        ?>
        <section class="content-header">
          <h1>
           Listado General De Productos
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">

<div class="row">
  <div class="col-md-12">

<div class="row">
  <div class="col-md-3">
  <a href="index.php?view=newproduct&stock=<?php echo StockData::getPrincipal()->id; ?>" class="btn btn-info">Registrar Producto Nuevo</a>
  </div>
  <div class="col-md-9">
</div>
 </div>
<br>

<div class="products_table_container">
<div class="card box-primary">
  <div class="card-header">
   Listado Productos Activos

  </div><!-- /.box-header -->
  <div class="card-body no-padding">
<div class="box-body table-responsive">
<table class="table  table-bordered products_table table-hover">
  <thead>
    <th style="text-align: center">Modelo</th>
    <th style="text-align: center">Descripción</th>
    <th style="text-align: center">Existencia</th>
    <th style="text-align: center">Precio Compra</th>
    <th style="text-align: center">Precio De Distribuidor</th>
   <th style="text-align: center">Acciones</th>
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
          ajax: "./?action=getproductsajax&stock=<?php echo $stock->id;?>",
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

<script type="text/javascript">
<?php if(isset($_SESSION["flash_success"])):?>
    swal({
    title:'Datos Guardados!',
    text:"Datos Guardados exitosamente!.",
    type:'success'
  })
<?php unset($_SESSION["flash_success"]);endif; ?>
</script>
<?php elseif(isset($_GET["opt"]) && $_GET["opt"]=="new"):?>
<?php endif; ?>