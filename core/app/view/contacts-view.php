<section class="content">
<div class="row">
	<div class="col-md-12">
		<h1>Directorio de Contactos</h1>
<div class="btn-group">
	<a href="index.php?view=newcontact" class="btn btn-outline-dark"><i class='fa fa-user'></i> Nuevo Contacto</a>
</div>
<br><br>
		<?php

		$users = PersonData::getContacts();
		if(count($users)>0){
			// si hay usuarios
			?>
<div class="card card-primary">
<div class="card-header">
    <span class="box-title">Contactos</span>
	
  </div>
<div class="card-body">
			<table class="table table-bordered datatable table-hover">
			<thead>
			<th>Nombre completo</th>
			<th>Direccion</th>
			<th>Email</th>
			<th>Telefono</th>
			<th></th>
			</thead>
			<?php
			foreach($users as $user){
				?>
				<tr>
				<td><?php echo $user->name." ".$user->lastname; ?></td>
				<td><?php echo $user->address1; ?></td>
				<td><?php echo $user->email1; ?></td>
				<td><?php echo $user->phone1; ?></td>
				<td style="width:130px;">
				<a href="index.php?view=editcontact&id=<?php echo $user->id;?>" class="btn btn-warning btn-sm">Editar</a>
				<a href="index.php?view=delcontact&id=<?php echo $user->id;?>" class="btn btn-danger btn-sm">Eliminar</a>

				</td>
				</tr>
				<?php

			}
			?>
			</table>
			</div>
			</div>
			<?php



		}else{
			echo "<p class='alert alert-danger'>No hay Contactos</p>";
		}


		?>


	</div>
</div>
</section>