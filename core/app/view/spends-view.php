        <section class="content">
<div class="row">
	<div class="col-md-12">

	<h1>Registro De Abonos o Retiros</h1>
    <a href="index.php?view=newspend" class="btn btn-secondary"><i class='fa fa-th-list'></i> Registrar Nuevo Movimiento</a>

<br>
<br>
		<?php

		$users = SpendData::getAll();
//		$users = SpendData::getAllUnBoxed();
		if(count($users)>0){
			// si hay usuarios
			$total = 0;
			?>
              <div class="card box-primary">
                <div class="card-header">
                  <span class="box-title">Gastos</span>
                </div><!-- /.box-header -->
      <div class="card-body">
			<table class="table datatable table-bordered table-hover">
			<thead>
			<th style="text-align: center">Tipo Movimiento</th>
            <th style="text-align: center">Concepto</th>
            <th style="text-align: center">Monto Movimiento</th>
            <th style="text-align: center">Fecha Movimiento</th>
            <th style="text-align: center">Acciones</th>

			</thead>
			<?php
			foreach($users as $user){
				?>
				<tr>
				<td style="text-align: center"><?php 
				if($user->kind=="1")
				{ 
				echo "<span class='label label-success'>Abono</span>"; } 
				else if($user->kind=="2"){ 
				echo "<span class='label label-info'>Retiro</span>"; } 

				?></td>
			<td style="text-align: center"><?php echo $user->name; ?></td>
			<td style="text-align: center"><?php echo Core::$symbol; ?> <?php echo number_format($user->price,2,".",","); ?></td>
			<td style="text-align: center"><?php echo $user->created_at; ?></td>
			<td style="width:130px;text-align: center"><a href="index.php?view=editspend&id=<?php echo $user->id;?>" class="btn btn-warning btn-sm">Editar</a> <a href="index.php?action=delspend&id=<?php echo $user->id;?>" class="btn btn-danger btn-sm">Eliminar</a></td>
			</tr>
			<?php
			$total+=$user->price;

		}

		include '/connection/conexion.php';
		$mysqli->query("SET NAMES 'UTF8'");
        $sql_retiros = "select sum(price) as gastos from spend where kind= 2 and stock_id = $user->stock_id";
		$resultSet_retiros = $mysqli->query($sql_retiros);
		$fila = $resultSet_retiros->fetch_assoc();
		$totalre = $fila["gastos"];

		$sql_abonos = "select sum(price) as abonos from spend where kind= 1 and stock_id = $user->stock_id";
		$resultSet_abonos = $mysqli->query($sql_abonos);
		$fila = $resultSet_abonos->fetch_assoc();
		$totalabonos = $fila["abonos"];
        $disponible = round($totalabonos-$totalre,2);



echo "</table>";
echo "<div class='box-body'><h1>Total Disponible : ".Core::$symbol." ".number_format($disponible,2,".",",")."</div></h1>";
echo "</div>";
echo "</div>";
   }else{
		echo "<p class='alert alert-danger'>No hay Gastos</p>";
	}

	?>

</div>
</div>
</section>

