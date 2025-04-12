<section class="content"> 
<div class="row">
	<div class="col-md-12">

<?php 
if(isset($_SESSION["client_id"])):?>
		<h1><i class='glyphicon glyphicon-shopping-cart'></i> Mis Compras</h1>
<?php else:?>
<!--
<div class="btn-group pull-right">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> Descargar <span class="caret"></span>
  </button>
  
  
  <ul class="dropdown-menu" role="menu">
  	    <?php if(Core::$user->kind==1):?>
    <li><a href="report/sells-word.php">Word 2007 (.docx)</a></li>
    <li><a href="report/sells-xlsx.php">Excel 2007 (.xlsx)</a></li>
<?php endif; ?>
<li><a onclick="thePDF()" id="makepdf" class="">PDF (.pdf)</a></li>
  </ul>
</div> -->

<br>

<div class="panel panel-default">
                        <div class="panel-heading">
						<h1><i class='glyphicon glyphicon glyphicon-shopping-cart'></i> Listado Traspasos Del Día </h1>
						</div>
					
						</div>
					
<?php endif;?>
		<div class="clearfix">
		</div>

<form id="filtertraspasos">
	<input type="hidden" name="view" value="filtertraspasos">

<div class="row">
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



<div class="allfiltertraspasos"></div>


<script type="text/javascript">
	$(document).ready(function(){
		$.get("./?action=filtertraspasos",$("#filtertraspasos").serialize(),function(data){
			$(".allfiltertraspasos").html(data);
		});

		$("#filtertraspasos").submit(function(e){
			e.preventDefault();
		$.get("./?action=filtertraspasos",$("#filtertraspasos").serialize(),function(data){
			$(".allfiltertraspasos").html(data);
		});

		})
	});
</script>
	</div>
</div>
</section>