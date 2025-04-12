<?php
include '/connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
?>

<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>Nueva Sucursal</h1>
	<br>
  <div class="card box-primary">
    <div class="card-header">Registro Sucursales</div>
    <div class="card-body">

  <table class="table">
  <tr><td>
		<form class="form-horizontal" method="post" enctype="multipart/form-data"  id="addcategory" action="index.php?action=addstock" role="form">

    <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Logotipo Sucursal</label>
    <div class="col-md-6">
      <input type="file" name="image" id="image" placeholder="">
    </div>
  </div>
  
  <br>
  
    <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Codigo Sucursal *</label>
    <div class="col-md-6">
      <input type="text" name="code" required class="form-control" id="code" placeholder="Codigo">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Codigo Postal *</label>
    <div class="col-md-6">
      <input type="text" name="cp" required class="form-control" id="cp" placeholder="Codigo postal">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre Sucursal</label>
    <div class="col-md-6">
      <input type="text" name="name" required class="form-control" id="name" placeholder="Nombre">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Ciudad</label>
    <div class="col-md-6">
      <input type="text" name="ciudad" required class="form-control" id="name" placeholder="Ciudad">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Colonia</label>
    <div class="col-md-6">
      <input type="text" name="colonia"  class="form-control" id="name" placeholder="Colonia">
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Direccion*</label>
    <div class="col-md-6">
      <input type="text" name="address"  class="form-control" id="name" placeholder="Direccion">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Telefono / WhatsApp</label>
    <div class="col-md-6">
      <input type="text" name="phone"  class="form-control" id="name" placeholder="Telefono">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Email*</label>
    <div class="col-md-6">
      <input type="text" name="email"  class="form-control" id="name" placeholder="Email">
    </div>
  </div>
  <br>
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
    <button type="submit" class="btn btn-primary"  onclick="return procesar();">Registrar sucursal</button>
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

<script>
        function procesar() {
        if (confirm("¿Deseas registrar la sucursal en sistema?")) {
        return true;
        } else {
        return false;}}
        </script>


