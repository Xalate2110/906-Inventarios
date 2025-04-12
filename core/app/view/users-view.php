<section class="content">
<div class="row">
	<div class="col-md-12">
		<h1>Lista de Usuarios</h1>
	<a href="index.php?view=newuser&kind=1" class="btn btn-outline-dark"><i class='bi-plus-square'></i> Nuevo Administrador</a>
	<a href="index.php?view=newuser&kind=2" class="btn btn-outline-dark"><i class='bi-plus-square'></i> Nuevo Almacenista</a>
	<a href="index.php?view=newuser&kind=3" class="btn btn-outline-dark"><i class='bi-plus-square'></i> Nuevo Vendedor</a>
	<a href="index.php?view=newuser&kind=4" class="btn btn-outline-dark"><i class='bi-plus-square'></i> Nuevo Administrador de Sucursal</a>
<br><br>
		<?php

		$users = UserData::getAll();
		if(count($users)>0){
			// si hay usuarios
			?>
			<div class="card box-primary">
			<div class="card-header">Usuarios</div>
			<div class="box box-primary table-responsive">
            <table class="table table-bordered table-hover table-responsive datatable">
			<thead>
			<th style="text-align: center">Detalle</th>
			<th style="text-align: center">Nombre completo</th>
			<th style="text-align: center">Nombre de usuario</th>
			<th style="text-align: center">Email</th>
			<th style="text-align: center">Almacen</th>
			<th style="text-align: center">Activo</th>
			<th style="text-align: center">Tipo</th>
			<th style="text-align: center">Acciones</th>
			</thead>
			<?php
			foreach($users as $user){
				?>
				<tr>
				<td style="width:24px;text-align: center">
					<?php
					if($user->image!=""){
						$url = "storage/profiles/".$user->image;
						if(file_exists($url)){
							echo "<img src='$url' style='width:24px;'>";
						}
					}
					?>
				</td>
				<td style="text-align: center"><?php echo $user->name." ".$user->lastname; ?></td>
				<td style="text-align: center"><?php echo $user->username; ?></td>
				<td style="text-align: center"><?php echo $user->email; ?></td>
				<td style="text-align: center"><?php if($user->stock_id!=null){ echo $user->getStock()->name; } ?></td>
				<td style="text-align: center">
					<?php if($user->status==1):?>
						<i class="bi-check"></i>
					<?php endif; ?>
				</td>
				<td style="text-align: center">
				<?php
                switch ($user->kind) {
				case '1': echo "Administrador"; break;
				case '2': echo "Almacenista"; break;
				case '3': echo "Vendedor"; break;
				case '4': echo "Administrador de Sucursal"; break;
				default:
					# code...
					break;
}
				?>
				</td>
				<td style="width:70px;text-align: center"><a href="index.php?view=edituser&id=<?php echo $user->id;?>" class="btn btn-warning btn-sm"><i class="bi-pencil"></i> Editar Usuario</a>
				<a href="./?action=deluser&id=<?php echo $user->id; ?>"  class="btn btn-danger btn-sm"><i class="bi-trash"></i></a>

				</td>

				</tr>
				<?php

			}
 echo "</table></div></div>";


		}else{
			// no hay usuarios
		}


		?>


	</div>
</div>
</section>