<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>

        <!-- Main content -->
        <section class="content">
        <div class="panel panel-default">
        <div class="panel-heading">
				<h1><i class='glyphicon glyphiconglyphicon glyphicon-usd'></i> Generación de Complemento De Pago </h1>
				</div>
        </div>

          <div class="row">
        	<div class="col-md-12">

<div class="row">

<div class="col-md-9">

    <form class="form-horizontal" id="filterabonosremisiones">
     <div class="form-group">
     <div class="col-md-4">
                                <div class="col-lg-12">
                                <select class="client_id form-control"  name="client_id" id="client_id"  onchange = "myFunction()">
                                <option value="<?php echo $client->id;?>"> </option>
                                    </select>
                               
                                    <script type="text/javascript">
                                      $('.client_id').select2({
                                       
                                    placeholder: 'Elige Cliente',
                                    ajax: {
                                     url: 'ajax.php',
                                     dataType: 'json',
                                     delay: 250,
                                     processResults: function (data) {
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

                    <script>
                    function myFunction() {
                    var cliente_datos = document.getElementById("client_id").value;}
                    </script>

<br>
     <div class="col-sm-5">
      <button type="submit" class="btn btn-primary">Buscar Abonos Del Cliente</button>
    </div>

  </div>


</form>


</div>
</div>

<div class="allfilterabonosremisiones">

	</div>
</div>
</section><!-- /.content -->
<script type="text/javascript">
  $(document).ready(function(){
      $(".allfilterabonosremisiones").html("<i class='fa fa-refresh fa-spin'></i>");
    $.get("./?action=busqueda_abonos_remisiones",$("#filterabonosremisiones").serialize(),function(data){
      $(".allfilterabonosremisiones").html(data);
    });

    $("#filterabonosremisiones").submit(function(e){
      e.preventDefault();
      $(".allfilterabonosremisiones").html("<i class='fa fa-refresh fa-spin'></i>");
    $.get("./?action=busqueda_abonos_remisiones",$("#filterabonosremisiones").serialize(),function(data){
      $(".allfilterabonosremisiones").html(data);
    });

    })
  });
</script>

