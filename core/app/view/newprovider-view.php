<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>Registrar Nuevo Proveedor</h1>
	<br>
    <div class="card box-primary">
    <div class="card-header">Listado De Proveedores</div>
    <div class="card-body">

		<form class="form-horizontal" method="post" id="addproduct" action="index.php?view=addprovider" role="form">

 
  <div class="form-group">
    <!--   
    <label for="inputEmail1" class="col-lg-2 control-label">RFC Proveedor*</label>
    <div class="col-md-6">
      <input type="text" name="no" class="form-control" id="no" placeholder="RFC Proveedor" required>
    </div> 
-->

  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre Proveedor*</label>
    <div class="col-md-6">
      <input type="text" name="name" class="form-control" id="name" placeholder="Nombre Proveedor" required>
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Telefono Proveedor*</label>
    <div class="col-md-6">
      <input type="text" name="phone1" class="form-control" id="phone1" placeholder="Telefono Proveedor">
    </div>
  </div>


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre Vendedor*</label>
    <div class="col-md-6">
      <input type="text" name="vendedor" class="form-control" id="vendedor" placeholder="Nombre Vendedor" required>
    </div> 

    <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Telefono Vendedor*</label>
    <div class="col-md-6">
      <input type="text" name="phone2" class="form-control" id="phone2" placeholder="Telefono Vendedor" required>
    </div> 

  <!--
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Apellido*</label>
    <div class="col-md-6">
      <input type="text" name="lastname" required class="form-control" id="lastname" placeholder="Apellido">
    </div>
  </div> -->

  <!--<div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Direccion*</label>
    <div class="col-md-6">
      <input type="text" name="address1" class="form-control" required id="address1" placeholder="Direccion">
    </div>
  </div> -->

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Correo Electronico *</label>
    <div class="col-md-6">
      <input type="text" name="email1" class="form-control" id="email1" placeholder="Email">
    </div>
  </div>

 

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label" >Activar Credito</label>
    <div class="col-md-6">
<div class="checkbox">
    <label>
      <input type="checkbox" name="has_credit">
    </label>
  </div>
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Limite de credito</label>
    <div class="col-md-6">
      <input type="text" name="credit_limit" class="form-control" id="" placeholder="Limite de credito">
    </div>
  </div>

  <br>

  <!--
  <div class="form-group">
  <label for="exampleInputEmail1" class="col-lg-2 control-label">Sucursal Asignar</label>
  <div class="col-md-6">

		   <select name="stock_id" id="stock_id" class="form-control">
       <option value="0">Selecciona la Sucursal</option>
			<?php foreach(StockData::getAll() as $stock):?>
				<option value="<?php echo $stock->id; ?>" 
        ><?php echo $stock->name; ?></option>
			<?php endforeach; ?>
		</select>
</div>
	</div>
    </div> -->



  <p class="alert alert-info">* Campos obligatorios</p>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
    <button type="submit" class="btn btn-success" onclick="return procesar();"> Registrar Proveedor en sistema</button>
    </div>
  </div>
</form>
</div>
	</div>
</div>
</section>

<script>
function procesar() {
        if (confirm("¿Deseas registrar al proveedor en Sistema?")) {
        return true;
        } else {
        return false;}}
        </script>