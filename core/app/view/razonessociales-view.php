<section class="content">
<div class="row">
	<div class="col-md-12">
		<h1>Razones Sociales</h1>
	<a href="index.php?view=newrazon" class="btn btn-outline-dark"><i class='fa fa-th-list'></i> Agregar Nueva Razon Social</a>
<br>
<br>
		<?php

		$users = RazonData::getAll();
		if(count($users)>0){
			// si hay usuarios
			?>
<div class="card">
  <div class="card-header">
    <span class="box-title">Listado General De Razones Sociales</span>

  </div><!-- /.box-header -->
  <div class="card-body no-padding">

			<table class="table datatable table-bordered table-hover">
			<thead>
			<th style="text-align: center">Logotipo</th>
			<th style="text-align: center">Razon Social</th>
			<th style="text-align: center">RFC Registrado</th>
			<th style="text-align: center">Codigo Postal</th>
			<th style="text-align: center">Regimen Fiscal</th>
			<th style="text-align: center">Ciudad</th>
			<th style="text-align: center">Colonia</th>
			<th style="text-align: center">Dirección</th>
			<th style="text-align: center">Serie Facturación</th>
			<th style="text-align: center">Acciones</th>
			</thead>
			<?php
			foreach($users as $user){
				?>
				<tr>
				<td style="text-align: center">
			    <?php if($user->image!=""):?>
				<img src="storage/razones_sociales/<?php echo $user->image;?>" style="width:60px;">
			    <?php endif;?>      
		        </td>
				<td style="text-align: center"><?php echo $user->razonsocial;?></td>
				<td style="text-align: center"><?php echo $user->rfc;?></td>
				<td style="text-align: center"><?php echo $user->codigo_postal;?></td>
				<td style="text-align: center"><?php echo $user->regimen_fiscal;?></td>
				<td style="text-align: center"><?php echo $user->ciudad;?></td>
				<td style="text-align: center"><?php echo $user->colonia;?></td>
				<td style="text-align: center"><?php echo $user->direccion; ?></td>
				<td style="text-align: center"><?php echo $user->serie_facturacion; ?></td>
				
				<td style="text-align: center"><a href="index.php?view=editrazon&id=<?php echo $user->id;?>" class="btn btn-warning btn-sm">Editar</a> 
				<a href="index.php?action=delrazon&id=<?php echo $user->id;?>" class="btn btn-danger btn-sm">Eliminar</a></td>
				</tr>
				<?php

			}

			?>
			</table>
  </div><!-- /.box-body -->
</div><!-- /.box -->
			
			<?php



		}else{
			echo "<p class='alert alert-danger'>No hay Categorias</p>";
		}


		?>


	</div>
</div>
</section>