<?php
include '/connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        $('.js-example-basic-single').select2();
    });
</script>


<section class="content">
    <?php 
$currency = ConfigurationData::getByPreffix("currency")->val;
$categories = CategoryData::getAll();
    ?>
<div class="row">
	<div class="col-md-12">
	<h1>Nuevo Servicio</h1>
	<br>
  <div class="card box-primary">
    <div class="card-header">Servicios</div>
    <div class="card-body">

  <table class="table">
  <tr>
  <td>
		<form class="form-horizontal" method="post" enctype="multipart/form-data" id="addproduct" action="index.php?view=addproduct&opt=service" role="form">

<input type="hidden" name="kind" value="2">

<!--
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Imagen</label>
    <div class="col-md-6">
      <input type="file" name="image" id="image" placeholder="">
    </div>
  </div> -->


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Codigo Interno *</label>
    <div class="col-md-6">
      <input type="text" name="code" id="product_code" class="form-control" id="barcode" placeholder="Codigo Interno">
    </div>
  </div>

  <!--
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Codigo de Barras*</label>
    <div class="col-md-6">
      <input type="text" name="barcode" id="product_code" class="form-control" id="barcode" placeholder="Codigo de Barras del Servicio">
    </div>
  </div> -->

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Nombre Servicio *</label>
    <div class="col-md-6">
      <input type="text" name="name" required class="form-control" id="name" placeholder="Nombre del Servicio">
    </div>
  </div>

  <!--
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Categoria</label>
    <div class="col-md-6">
    <select name="category_id" class="form-control">
    <option value="">-- NINGUNA --</option>
    <?php foreach($categories as $category):?>
      <option value="<?php echo $category->id;?>"><?php echo $category->name;?></option>
    <?php endforeach;?>
      </select>    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Marca</label>
    <div class="col-md-6">
    <select name="brand_id" class="form-control">
    <option value="">-- NINGUNA --</option>
    <?php foreach(BrandData::getAll() as $category):?>
      <option value="<?php echo $category->id;?>"><?php echo $category->name;?></option>
    <?php endforeach;?>
      </select>    </div>
  </div> -->


  
  <!--
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Unidad De Medida</label>
    <div class="col-md-6">
    <?php
                                        $sql_mpago = "SELECT * FROM tblunidades_sat";
                                        //echo $sql;
                                        $resultSet = $mysqli->query($sql_mpago);
                                        ?>
                                        <select name="unit" id = "unit" class="form-control js-example-basic-single">
                                            <option selected value='0'>Selecciona</option>
                                            <?php
                                            while ($fila = $resultSet->fetch_assoc()) {
                                                echo "<option value='" . $fila['id'] . "'>" . $fila['id_unidad'] . "-" . $fila['name'] . "</option>";
                                            }
                                            ?>
      </select>    </div>
  </div>


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Presentación Producto SAT</label>
    <div class="col-md-6">
    <?php
                                        $sql_unidades = "SELECT * FROM tblunidades";
                                        //echo $sql;
                                        $resultSet = $mysqli->query($sql_unidades);
                                        ?>
                                        <select name="presentation" id="presentation" class="form-control js-example-basic-single">
                                            <option selected value='0'>presentacion</option>
                                            <?php
                                            while ($fila = $resultSet->fetch_assoc()) {
                                                echo "<option value='" . $fila['id'] . "'>" . $fila['descripcion'] . "</option>";
                                            }
                                            ?>
    
      </select>    </div>
  </div>


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Descripción Producto SAT</label>
    <div class="col-md-6">
    <?php
                                        $sql_sat = "SELECT * FROM tblcodigos_sat";
                                        //echo $sql;
                                        $resultSet = $mysqli->query($sql_sat);
                                        ?>
                                        <select name="codigo_sat" id = "codigo_sat" class="form-control js-example-basic-single">
                                            <option selected value='0'>Descripcion Sat</option>
                                            <?php
                                            while ($fila = $resultSet->fetch_assoc()) {
                                                echo "<option value='" . $fila['id'] . "'>" . $fila['id_codigo'] . "-" . $fila['descripcion'] . "</option>";
                                            } ?>
      </select>    
    
    </div>
  </div>


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Impuesto Producto SAT</label>
    <div class="col-md-6">
   
    <?php
                                        $sql_imp = "SELECT * FROM objetoimp";
                                       
                                        $resultSet = $mysqli->query($sql_imp);
                                        ?>
                                        <select name="objetoimp" id = "objetoimp" class="form-control js-example-basic-single">
                                            <option selected value='0'>Impuesto Producto</option>
                                            <?php
                                            while ($fila = $resultSet->fetch_assoc()) {
                                            echo "<option value='" . $fila['id_ObjetoImp'] . "'>" . $fila['id_ObjetoImp'] . "-" . $fila['descripcion'] . "</option>";
                                            }
                                            ?>
      </select>    
    
    </div>
  </div> -->

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Descripcion</label>
    <div class="col-md-6">
      <textarea name="description" class="form-control" id="description" placeholder="Descripcion del Producto"></textarea>
    </div>
  </div>
<!--
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Precio de Entrada (<?php echo $currency; ?>)*</label>
    <div class="col-md-6">
      <input type="text" name="price_in" required class="form-control" id="price_in" placeholder="Precio de entrada">
    </div>
  </div> -->

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Precio de Venta (<?php echo $currency; ?>)*</label>
    <div class="col-md-6">
      <input type="text" name="price_out" required class="form-control" id="price_out" placeholder="Precio de Venta">
    </div>
  </div>


  <!--
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Precio de Salida 2 (<?php echo $currency; ?>)*</label>
    <div class="col-md-6">
      <input type="text" name="price_out2" required class="form-control" id="price_out2" placeholder="Precio de salida 2">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Precio de Salida 3 (<?php echo $currency; ?>)*</label>
    <div class="col-md-6">
      <input type="text" name="price_out3" required class="form-control" id="price_out3" placeholder="Precio de salida 3">
    </div>
  </div>-->

  <script type="text/javascript">
//  $(document).ready(function(){
  //  $("#price_in").keyup(function(){
    //  $("#price_out").val( $("#price_in").val()*1.25 );
    //});

  //});
  </script>

<input type="hidden" name="inventary_min" value="0">
<input type="hidden" name="q" value="0">
<input type="hidden" name="expire_at" value="0000-00-00">
<input type="hidden" name="width" value="0">
<input type="hidden" name="height" value="0">
<input type="hidden" name="weight" value="0">

<!--
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label"></label>
    <div class="col-md-2">
    <label class="control-label">Anchura*</label>
      <input type="text" name="width"  class="form-control" placeholder="Ancho">
    </div>
    <div class="col-md-2">
    <label class="control-label">Altura*</label>
      <input type="text" name="height"  class="form-control" placeholder="Altura">
    </div>
    <div class="col-md-2">
    <label class="control-label">Peso*</label>
      <input type="text" name="weight"  class="form-control" placeholder="Peso">
    </div>

  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Minima en inventario:</label>
    <div class="col-md-6">
      <input type="text" name="inventary_min" class="form-control" id="inputEmail1" placeholder="Minima en Inventario (Default 10)">
    </div>
  </div>


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Inventario inicial:</label>
    <div class="col-md-6">
      <input type="text" name="q" class="form-control" id="inputEmail1" placeholder="Inventario inicial">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Fecha de caducidad:</label>
    <div class="col-md-6">
      <input type="date" name="expire_at" class="form-control" id="inputEmail1" placeholder="Fecha de caducidad">
    </div>
  </div>
-->
<br>
  <div class="form-group">
    <div class="col-lg-offset-3 col-lg-10">
    <button type="submit" class="btn btn-primary"  onclick="return procesar();">Agregar Servicio</button>
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
        if (confirm("¿Deseas registrar el servicio nuevo en Sistema?")) {
        return true;
        } else {
        return false;}}
        </script>
