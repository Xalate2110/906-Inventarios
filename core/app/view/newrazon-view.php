<?php
include '/connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
?>

<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>Nueva Razón Social</h1>
	<br>
  <div class="card box-primary">
    <div class="card-header">Registro de Razón Social</div>
    <div class="card-body">

  <table class="table">
  <tr><td>
		<form class="form-horizontal" method="post" id="addcategory" enctype="multipart/form-data" action="index.php?action=addrazon" role="form">

    <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Logotipo Razón Social</label>
    <div class="col-md-6">
      <input type="file" name="image" id="image" placeholder="">
    </div>
  </div>
  <br>
  
  

    <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre Razón Social</label>
    <div class="col-md-6">
      <input type="text" name="name" required class="form-control" id="name" placeholder="Nombre">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">RFC</label>
    <div class="col-md-6">
      <input type="text" name="rfc" required class="form-control" id="rfc" placeholder="RFC Razón Social">
    </div>
  </div>


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Codigo Postal *</label>
    <div class="col-md-6">
      <input type="text" name="cp" required class="form-control" id="cp" placeholder="Codigo postal">
    </div>
  </div>


    
  <div class="form-group">
                                    <label for="inputEmail1" class="col-lg-2 control-label">Regimen Fiscal</label>
                                    <div class="col-md-6">

                                        <?php
                                        $sql_mpago = "SELECT * FROM regimen_fiscal";
                                        //echo $sql;
                                        $resultSet = $mysqli->query($sql_mpago);
                                        ?>
                                        <select name="regimen_fiscal" id = "regimen_fiscal" class="form-control js-example-basic-single">
                                            <option selected>Selecciona una opcion</option>
                                            <?php
                                            while ($fila = $resultSet->fetch_assoc()) {
                                                echo "<option value='" . $fila['idregimen'] . "'>" . $fila['idregimen'] . "  -  " . $fila['descripcion']. "</option>";
                                            }
                                            ?>
                                        </select>

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
    <label for="inputEmail1" class="col-lg-2 control-label">Serie Facturación</label>
    <div class="col-md-6">
      <input type="text" name="sf"  class="form-control" id="sf" placeholder="Serie Facturación">
    </div>
  </div>

  <br>
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
    <button type="submit" class="btn btn-primary"  onclick="return procesar();">Registrar Razón Social</button>
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
        if (confirm("¿Deseas registrar la razón social en sistema?")) {
        return true;
        } else {
        return false;}}
        </script>


