<?php $user = PersonData::getById($_GET["id"]);?>
<div class="row">
	<div class="col-md-12">
	<h1>Editar Proveedor</h1>
	<br>

  <div class="card box-primary">
    <div class="card-header">Edición de Información de Proveedor</div>
    <div class="card-body">
		<form class="form-horizontal" method="post" id="addproduct" action="index.php?view=updateprovider" role="form">


  <div class="form-group">
      <!--
    <label for="inputEmail1" class="col-lg-2 control-label">RFC/RUT*</label>
    <div class="col-md-6">
      <input type="text" name="no" value="<?php echo $user->no;?>" class="form-control" id="no" placeholder="RFC/RUT">
    </div>
  </div> -->

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre Proveedor *</label>
    <div class="col-md-6">
      <input type="text" name="name" value="<?php echo $user->name;?>" class="form-control" id="name" placeholder="Nombre Proveedor">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Telefono Proveedor</label>
    <div class="col-md-6">
      <input type="text" name="phone1"  value="<?php echo $user->phone1?>"  class="form-control" id="inputEmail1" placeholder="Telefono Proveedor">
    </div>
  </div>


  
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre Vendedor *</label>
    <div class="col-md-6">
      <input type="text" name="vendedor" value="<?php echo $user->vendedor;?>" class="form-control" id="vendedor" placeholder="Nombre Vendedor">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Telefono Vendedor</label>
    <div class="col-md-6">
      <input type="text" name="phone2"  value="<?php echo $user->phone2?>"  class="form-control" id="inputEmail1" placeholder="Telefono Vendedor">
    </div>
  </div>

  <!--
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Apellido*</label>
    <div class="col-md-6">
      <input type="text" name="lastname" value="<?php echo $user->lastname;?>" required class="form-control" id="lastname" placeholder="Apellido">
    </div>
  </div>-->

<!--
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Direccion*</label>
    <div class="col-md-6">
      <input type="text" name="address1" value="<?php echo $user->address1;?>" class="form-control" required id="username" placeholder="Direccion">
    </div>
  </div> -->


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Correo Electronico *</label>
    <div class="col-md-6">
      <input type="text" name="email1" value="<?php echo $user->email1;?>" class="form-control" id="email" placeholder="Email">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Telefono</label>
    <div class="col-md-6">
      <input type="text" name="phone1"  value="<?php echo $user->phone1;?>"  class="form-control" id="inputEmail1" placeholder="Telefono">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label" >Activar Credito</label>
    <div class="col-md-6">
<div class="checkbox">
    <label>
      <input type="checkbox" name="has_credit" <?php if($user->has_credit){ echo "checked";}?>>
    </label>
  </div>
    </div>
  </div>


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Limite de credito</label>
    <div class="col-md-6">
      <input type="text" name="credit_limit"  value="<?php echo $user->credit_limit;?>"  class="form-control" id="inputEmail1" placeholder="Credito">
    </div>
  </div>


  <!--
  <div class="form-group">
  <label for="exampleInputEmail1" class="col-lg-2 control-label">Sucursal Asignado</label>
  <div class="col-md-6">
		   <select name="stock_id" id="stock_id" class="form-control">
			<option value="">Selecciona la Sucursal</option>
			<?php foreach(StockData::getAll() as $stock):?>
				<option value="<?php echo $stock->id; ?>" <?php
        if ($stock->id == $user->stock_id) {
        echo "selected";}?>><?php echo $stock->name; ?></option>
			<?php endforeach; ?>
		</select>
</div>
	</div>
    </div>

        -->

        <br>

  <p class="alert alert-info">* Campos obligatorios</p>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
    <input type="hidden" name="user_id" value="<?php echo $user->id;?>">
      <button type="submit" class="btn btn-primary">Actualizar Proveedor</button>
    </div>
  </div>
</form>
</div>
	</div>
</div>