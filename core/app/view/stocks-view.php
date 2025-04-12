<section class="content">
<div class="row">
	<div class="col-md-12">
		<h1>Sucursales</h1>
	<a href="index.php?view=newstock" class="btn btn-outline-dark"><i class='fa fa-th-list'></i> Agregar Nueva Sucursal</a>
<br>
<br>
		<?php

		$users = StockData::getAll();
		if(count($users)>0){
			// si hay usuarios
			?>
<div class="card">
  <div class="card-header">
    <span class="box-title">Listao General De Sucursales</span>

  </div><!-- /.box-header -->
  <div class="card-body no-padding">

			<table class="table datatable table-bordered table-hover">
			<thead>
			<th style="text-align: center">Inventario</th>
			<th style="text-align: center">Logotipo</th>
			<th style="text-align: center">Codigo</th>
			<th style="text-align: center">Nombre</th>
			<th style="text-align: center">Direccion</th>
			<th style="text-align: center">Telefono</th>
			<th style="text-align: center">Email</th>
			<th style="text-align: center">Principal</th>
			<th style="text-align: center">Acciones</th>
			</thead>
			<?php
			foreach($users as $user){
				?>
				<tr>
				<td style="text-align: center"><a href="index.php?view=inventary&stock=<?php echo $user->id;?>" class="btn btn-outline-secondary btn-smcat"><i class="bi-chevron-right"></i></a></td>
				<td style="text-align: center">
			    <?php if($user->image!=""):?>
				<img src="storage/stocks/<?php echo $user->image;?>" style="width:64px;">
			    <?php endif;?>      
		        </td>
				<td style="text-align: center"><?php echo $user->code; ?></td>
				<td style="text-align: center"><?php echo $user->name; ?></td>
				<td style="text-align: center"><?php echo $user->address; ?></td>
				<td style="text-align: center"><?php echo $user->phone; ?></td>
				<td style="text-align: center"><?php echo $user->email; ?></td>
				<td style="text-align: center">
				<style>
				.bi {font-size: 2em;}
				</style>
		
				<?php if($user->is_principal):?>
					<i class="bi bi-check"></i>
				<?php else:?>
					<a href="index.php?action=makestockprincipal&id=<?php echo $user->id;?>" class="btn btn-secondary btn-sm">Hacer Principal</a>
				<?php endif;?>
			
				</td>
				<td style="width:180px;"><a href="index.php?view=editstock&id=<?php echo $user->id;?>" class="btn btn-warning btn-sm">Editar</a> <a href="index.php?action=delstock&id=<?php echo $user->id;?>" class="btn btn-danger btn-sm">Eliminar</a></td>
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