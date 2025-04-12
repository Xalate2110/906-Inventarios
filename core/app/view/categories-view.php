<?php if(isset($_GET["opt"]) && $_GET["opt"]=="all"):?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Categorias
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
<a href="index.php?view=categories&opt=new" class="btn btn-outline-dark"><i class='fa fa-th-list'></i> Nueva Categoria</a>

<div class="row">
	<div class="col-md-12">
<br>
		<?php

		$users = CategoryData::getAll();
		if(count($users)>0){
			// si hay usuarios
			?>
<div class="card">
  <div class="card-header">
    <span class="box-title">Listado General De Categorias</span>
	
  </div><!-- /.box-header -->
  <div class="card-body">

			<table class="table table-bordered datatable table-hover">
			<thead>
			<th style="text-align: center">Productos Relacionados</th>
			<th style="text-align: center">Descripción Categoria</th>
			<th style="text-align: center">Acciones</th>
			</thead>
			<?php
			foreach($users as $user){
				?>
				<tr>
				<td style="text-align: center"><a href="index.php?view=productbycategory&id=<?php echo $user->id;?>" class="btn btn-secondary btn-sm"><i class="fa fa-th-list"></i> Productos Relacionados</a> 
				</td>
				<td style="text-align: center"><?php echo $user->name; ?></td>
				<td style="text-align: center">
					<a href="index.php?view=categories&opt=edit&id=<?php echo $user->id;?>" class="btn btn-warning btn-sm">Editar</a> 
					<a href="index.php?action=categories&opt=del&id=<?php echo $user->id;?>" class="btn btn-danger btn-sm">Eliminar</a></td>
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
  </section><!-- /.content -->
<?php elseif(isset($_GET["opt"]) && $_GET["opt"]=="new"):?>
<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>Nueva Categoria</h1>
	<br>
  <div class="card box-primary">
    <div class="card-header">Categorias</div>
    <div class="card-body">

  <table class="table">
  <tr><td>
		<form class="form-horizontal" method="post" id="addcategory" action="index.php?action=categories&opt=add" role="form">
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre*</label>
    <div class="col-md-6">
      <input type="text" name="name" required class="form-control" id="name" placeholder="Nombre">
    </div>
  </div>
<br>
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-primary">Agregar Categoria</button>
    </div>
  </div>
</form>
</td>
</tr>
</table>
</div>
</div>
	</div>
</div>
</section>
<?php elseif(isset($_GET["opt"]) && $_GET["opt"]=="edit"):?>
<section class="content">
<?php $user = CategoryData::getById($_GET["id"]);?>
<div class="row">
	<div class="col-md-12">
	<h1>Editar Categoria</h1>
	<br>
  <div class="card box-primary">
    <div class="card-header">Categorias</div>
    <div class="card-body">

  <table class="table">
  <tr><td>
		<form class="form-horizontal" method="post" id="addproduct" action="index.php?action=categories&opt=upd" role="form">


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre*</label>
    <div class="col-md-6">
      <input type="text" name="name" value="<?php echo $user->name;?>" class="form-control" id="name" placeholder="Nombre">
    </div>
  </div>
<br>
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
    <input type="hidden" name="user_id" value="<?php echo $user->id;?>">
      <button type="submit" class="btn btn-success">Actualizar Categoria</button>
    </div>
  </div>
</form>
</td>
</tr>
</table>
</div>
</div>
	</div>
</div>
</section>
<?php endif; ?>