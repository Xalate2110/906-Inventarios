


<section class="content"> 
<div class="row">
	<div class="col-md-12">
	<!--<form id="processbox">-->
	<div class="btn-group pull-right">
	<a id="processbox" class="btn btn-primary">Realizar Corte De Caja <i class="fa fa-arrow-right"></i></a>
	</script>
			</div>


<?php 
if(isset($_SESSION["client_id"])):?>
		<h1><i class='glyphicon glyphicon-shopping-cart'></i> Mis Compras</h1>
<?php else:?>

<div class="panel panel-default">
                        <div class="panel-heading">
						<h1><i class='glyphicon glyphicon glyphicon-shopping-cart'></i> Generar Corte de Caja Del Día</h1>
						<h3><p>  AL realizar el proceso de corte, será de la sucursal : <b><?php echo StockData::getPrincipal()->name;?></h3></b></p>
						</div>
						 
                        </div>
<?php endif;?>
		<div class="clearfix"></div>

<form id="filterbox">
	<input type="hidden" name="view" value="sells">
<div class="row">

    <div><input type="hidden" name="almacen" value="<?php $alm = StockData::getPrincipal();	echo $alm->id;?>"></div><!--RLS-->
	<div><input type="hidden" id="fechaActual" value="<?php echo date('Y-m-d'); ?>"></div><!--RLS-->

	<div class="col-md-2">

						<label>Fecha inicio</label>
						<input type="date" name="start_at" id="start_at" value="<?php echo date('Y-m-d'); ?>" required class="form-control">
					</div>
					<div class="col-md-3">
						<label>Fecha fin</label>
						<input type="date" name="finish_at" id="finish_at" value="<?php echo date('Y-m-d'); ?>" required class="form-control">
					</div>
	<div class="col-md-2">
		<label>Aplicar Filtro</label><br>
		<input type="submit" value="Aplicar Filtro" class="btn btn-primary">
	</div>
</div>
</form>



<div class="allfilterbox"></div>
<div id="resultprocessbox"></div>


<script type="text/javascript">

			$(document).ready(function(){
				$.get("./?action=filterbox",$("#filterbox").serialize(),function(data){
					if(data){
						$(".allfilterbox").html(data);
					}
				});

				$("#filterbox").submit(function(e){
					e.preventDefault();
					$.get("./?action=filterbox",$("#filterbox").serialize(),function(data){
						$(".allfilterbox").html(data);
					});
				})



				$("#processbox").click(function(e){
					var fechaCorte = $("#fechaActual").val();
					var fechaInicial = $("#start_at").val();
					var fechaFinal   = $("#finish_at").val();
				
					if(fechaInicial!=fechaFinal){
						//console.log(fechaInicial);
						alert("El corte solo se puede realizar con fecha inicio igual a la fecha fin");
					}
					else{	
						e.preventDefault();
						$.get("./?action=processbox",$("#filterbox").serialize(),function(data){
							$("#resultprocessbox").html(data);
						});
					}
				})

			});

		</script>


</section>

















<!--

<section class="content">
<div class="row">
	<div class="col-md-12">
		<form id="processbox">
			<div class="btn-group pull-right">
			<a id="processbox" class="btn btn-primary">Realizar Corte De Caja <i class="fa fa-arrow-right"></i></a>
				
</script>
			</div>

			<h1><i class='fa fa-archive'></i> LISTADO DE VENTAS PARA GENERAR CORTE DE CAJA</h1>
			<h3><p>  AL PROCESAR EL CORTE DE CAJA, SERÁ DE LA SUCURSAL : <b><?php echo StockData::getPrincipal()->name;?></h3></b></p>
			<div class="clearfix"></div>

			<form id="filterbox">
					<div><input type="hidden" name="almacen" value="<?php $alm = StockData::getPrincipal();	echo $alm->id;?>"></div><!--RLS
					<div><input type="hidden" id="fechaActual" value="<?php echo date('Y-m-d'); ?>"></div><!--RLS
					<div class="col-md-3">
						<label>Fecha inicio</label>
						<input type="date" name="start_at" id="start_at" value="<?php echo date('Y-m-d'); ?>" required class="form-control">
					</div>
					<div class="col-md-3">
						<label>Fecha fin</label>
						<input type="date" name="finish_at" id="finish_at" value="<?php echo date('Y-m-d'); ?>" required class="form-control">
					</div>
					<div class="col-md-2">
						<label>Aplicar Filtro</label><br>
						<input type="submit" value="Aplicar Filtro" class="btn btn-primary">
					</div>
                 	</form>
		</form>
		<br><br><br>

		<div class="allfilterbox"></div>
		
		
		<div id="resultprocessbox"></div>

		<script type="text/javascript">

			$(document).ready(function(){
				$.get("./?action=filterbox",$("#filterbox").serialize(),function(data){
					if(data){
						$(".allfilterbox").html(data);
					}
				});

				$("#filterbox").submit(function(e){
					e.preventDefault();
					$.get("./?action=filterbox",$("#filterbox").serialize(),function(data){
						$(".allfilterbox").html(data);
					});
				})



				$("#processbox").click(function(e){
					//var f    = new Date();
					//var dia  = f.getDate();
					//var mes  = (f.getMonth() +1);
					//var anio = f.getFullYear()
					//var fechaCorte = (anio + "-" + mes + "-" dia);
					var fechaCorte = $("#fechaActual").val();
					var fechaInicial = $("#start_at").val();
					var fechaFinal   = $("#finish_at").val();
				
					if(fechaInicial!=fechaFinal){
						//console.log(fechaInicial);
						alert("El corte solo se puede realizar con fecha inicio igual a la fecha fin");
					}
					else{	
						e.preventDefault();
						$.get("./?action=processbox",$("#filterbox").serialize(),function(data){
							$("#resultprocessbox").html(data);
						});
					}
				})

			});

		</script>
	Codigo Recortado-
	</div>
</div>
</section>

		-->