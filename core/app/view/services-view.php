<?php if(isset($_GET["opt"]) && $_GET["opt"]=="all"):?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
           Litado General De Servicios
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">

<div class="row">
	<div class="col-md-12">

<div class="row">
  <div class="col-md-3">
<div class="btn-group">
  <a href="index.php?view=newservice" class="btn btn-dark">Agregar Servicio</a>

</div>
</div>
</div>
<br>
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
      <?php foreach(CategoryData::getAll() as $stock):?>
        <option value="<?php echo $stock->id; ?>"><?php echo $stock->name; ?></option>
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

<br>
<div class="products_table_container">


<div class="card box-primary">
  <div class="card-header">
    <span class="box-title">Listado De Servicios</span>

  </div><!-- /.box-header -->
  <div class="card-body no-padding">
<div class="box-body table-responsive">
<table class="table  table-bordered products_table table-hover">
  <thead>
    <th style="text-align: center">Codigo</th>
    <th style="text-align: center">Descripción Servicio</th>
    <!--<th style="text-align: center">Precio Entrada</th> -->
    <th style="text-align: center">Precio Venta</th>
    <!--<th style="text-align: center">Precio Salida 2</th>
    <th style="text-align: center">Precio Salida 3</th>
    <th style="text-align: center">Categoria</th> -->
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
          ajax: "./?action=getservicesajax",
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

<?php elseif(isset($_GET["opt"]) && $_GET["opt"]=="new"):?>
<?php endif; ?>