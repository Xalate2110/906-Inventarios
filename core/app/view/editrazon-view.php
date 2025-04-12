<?php
include '/connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
?>


<?php
$stock = RazonData::getById($_GET["id"]);
?>
<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>Editar Razón Social</h1>
	<br>
  <div class="card box-primary">
    <div class="card-header">Edición De Razón Social</div>
    <div class="card-body">

  <table class="table">
  <tr><td>
		<form class="form-horizontal" method="post" id="addcategory" enctype="multipart/form-data"  action="index.php?action=updaterazon" role="form">

    
    <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Logo Sucursal</label>
    <div class="col-md-6">
      <input type="file" name="image" id="name" placeholder="">
<?php if($stock->image!=""):?>
  <br>
        <img src="storage/razones_sociales/<?php echo $stock->image;?>" class="img-responsive">
<?php endif;?>
    </div>
  </div>

    <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre Razón Social</label>
    <div class="col-md-6">
    <input type="text" name="name" required class="form-control" value="<?php echo $stock->razonsocial; ?>" id="name" placeholder="Nombre">
      <input type="hidden" name="id" value="<?php echo $stock->id; ?>">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">RFC  *</label>
    <div class="col-md-6">
      <input type="text" name="rfc" required class="form-control" value="<?php echo $stock->rfc; ?>" id="rfc" placeholder="RFC Razón Social">      
    </div>
  </div>

<div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Codigo Postal *</label>
    <div class="col-md-6">
      <input type="text" name="cp" required class="form-control" value="<?php echo $stock->codigo_postal; ?>" id="cp" placeholder="Codigo postal">
      
    </div>
  </div>

  <div class="form-group">
                                        <label for="inputEmail1" class="col-lg-2 control-label">Regimen fiscal Cliente</label>
                                        <div class="col-md-6">

                                            <?php
                                            $sql_regimen = "SELECT * FROM regimen_fiscal";
                                            $resultSet = $mysqli->query($sql_regimen);
                                            ?>
                                            <select name="regimen_fiscal" id = "regimen_fiscal" class="form-control js-example-basic-single">
                                                <option selected>Selecciona una opcion</option>
                                                <?php
                                                while ($fila = $resultSet->fetch_assoc()) {
                                                    if ($fila['idregimen'] == $stock->regimen_fiscal) {
                                                        $seleccion = "selected";
                                                    } else {
                                                        $seleccion = "";
                                                    }
                                                    echo "<option value='" . $fila['idregimen'] . "'" . $seleccion . ">" . $fila['idregimen'] . "-" . $fila['descripcion'] . "</option>";
                                                }
                                                ?>
                                            </select>

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
      <input type="text" name="address"  class="form-control" value="<?php echo $stock->direccion; ?>" id="name" placeholder="Direccion">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Serie Facturación</label>
    <div class="col-md-6">
      <input type="text" name="sf"  class="form-control" value="<?php echo $stock->serie_facturacion; ?>" id="sf" placeholder="Serie Facturación">
    </div>
  </div>

 <br>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-success"  onclick="return procesar();">Actualizar Razón Social</button>
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


