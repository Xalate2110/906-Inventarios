<section class="content">
<div class="row">
	<div class="col-md-12">
		<h1>Directorio de Clientes</h1>
	<a href="index.php?view=newclient" class="btn btn-secondary"><i class='fa fa-smile-o'></i> Registrar Nuevo Cliente</a>

<br><br>
		<?php
         $stock_id = $_GET["stock"];
		$users = PersonData::getClients($stock_id);
		if(count($users)>0){
			// si hay usuarios
			?>
<div class="card box-primary">
	<div class="card-header"><b>Listado De Clientes</b></div>
<div class="card-body">
			<table class="table table-bordered datatable table-hover">
			<thead>
	
			<th style="text-align: center">Nombre o Razón Comercial</th>
			<th style="text-align: center">Telefono</th>
			<th style="text-align: center">Encargado Sucursal</th>
		    <th style="text-align: center">Direccion</th>
			<th style="text-align: center">Razon Social</th>
			<th style="text-align: center">RFC Cliente</th>
		    <th style="text-align: center">Credito</th>
		    <th style="text-align: center">Acciones</th>
			</thead>
			<?php
			foreach($users as $user){
				?>
				<tr>
				<td><?php echo utf8_encode($user->name) ?></td>
				<td style="text-align: center"><?php echo $user->phone1; ?></td>
				<td style="text-align: center"><?php echo utf8_encode($user->encargado) ?></td>
				<td style="text-align: center"><?php echo utf8_encode($user->address1); ?></td>
				<td style="text-align: center"><?php echo utf8_encode($user->lastname); ?></td>
				<td style="text-align: center"><?php echo utf8_encode($user->no); ?></td>
			    <td style="text-align: center"><?php if($user->has_credit){ echo "<i class='bi-check'></i>"; }; ?></td>
				<td style="width:130px;">
				<a href="index.php?view=editclient&id=<?php echo $user->id;?>" class="btn btn-warning btn-sm"><i class="bi-pencil"></i></a>
				<a href="index.php?view=delclient&id=<?php echo $user->id;?>&stock=<?php echo $stock_id;?>" class="btn btn-danger btn-sm"><i class="bi-trash"></i></a>
				</td>
				</tr>
				<?php

			}?>
			</table>
			</div>
			</div>
			<?php
		}else{
			echo "<p class='alert alert-danger'>No hay clientes</p>";
		}?>


	</div>
</div>
</section>



