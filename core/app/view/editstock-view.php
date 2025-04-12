<?php
include '/connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
?>


<?php
$stock = StockData::getById($_GET["id"]);
?>
<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>Editar Sucursal</h1>
	<br>
  <div class="card box-primary">
    <div class="card-header">Edición De Sucursal</div>
    <div class="card-body">

  <table class="table">
  <tr><td>
		<form class="form-horizontal" enctype="multipart/form-data" method="post" id="addcategory" action="index.php?action=updatestock" role="form">

    <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Logo Sucursal</label>
    <div class="col-md-6">
      <input type="file" name="image" id="name" placeholder="">
      <?php if($stock->image!=""):?>
      <br>
      <img src="storage/stocks/<?php echo $stock->image;?>" class="img-responsive">
      <?php endif;?>
    </div>
  </div>



  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Codigo Sucursal</label>
    <div class="col-md-6">
      <input type="text" name="code" required class="form-control" value="<?php echo $stock->code; ?>" id="code" placeholder="Codigo/NIT">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Codigo Postal *</label>
    <div class="col-md-6">
      <input type="text" name="cp" required class="form-control" value="<?php echo $stock->cp; ?>" id="cp" placeholder="Codigo postal">
      
    </div>
  </div>

    <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre Sucursal</label>
    <div class="col-md-6">
    <input type="text" name="name" required class="form-control" value="<?php echo $stock->name; ?>" id="name" placeholder="Nombre">
      <input type="hidden" name="id" value="<?php echo $stock->id; ?>">
    </div>
  </div>



    <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Ciudad</label>
    <div class="col-md-6">
      <input type="text" name="ciudad" required class="form-control"  value="<?php echo $stock->ciudad; ?>" id="name" placeholder="Ciudad">
    </div>
  </div>

  
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Colonia</label>
    <div class="col-md-6">
      <input type="text" name="colonia"  class="form-control"  value="<?php echo $stock->colonia; ?>" id="name" placeholder="Colonia">
    </div>
  </div>



  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Direccion*</label>
    <div class="col-md-6">
      <input type="text" name="address"  class="form-control" value="<?php echo $stock->address; ?>" id="name" placeholder="Direccion">
    </div>
  </div>


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Telefono / WhatsApp</label>
    <div class="col-md-6">
      <input type="text" name="phone"  class="form-control" value="<?php echo $stock->phone; ?>" id="name" placeholder="Telefono">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Email*</label>
    <div class="col-md-6">
      <input type="text" name="email"  class="form-control" value="<?php echo $stock->email; ?>" id="name" placeholder="Email">
    </div>
  </div>

    <br>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-success"  onclick="return procesar();">Actualizar Sucursal</button>
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
        if (confirm("¿Deseas actualizar el registro?")) {
        return true;
        } else {
        return false;}}
        </script>


