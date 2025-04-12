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
$stock = StockData::getById($_GET["stock"]);
$categories = CategoryData::getAll();
    ?>
<div class="row">
	<div class="col-md-12">
	<h1>Nuevo Producto</h1>
	<br>
  <div class="card box-primary">
    <div class="card-header">Productos</div>
    <div class="card-body">
  <table class="table">
  <tr>
  <td>
		<form class="form-horizontal" method="post" enctype="multipart/form-data" id="addproduct" action="index.php?view=addproduct" role="form">
<input type="hidden" name="kind" value="1">


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Imagen</label>
    <div class="col-md-6">
      <input type="file" name="image" id="image" placeholder="">
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Modelo</label>
    <div class="col-md-6">
      <input type="text" name="code" id="product_code" class="form-control" id="barcode" placeholder="Modelo">
    </div>
  </div>

  <input type="hidden" name="stock" class="form-control" id="stock" value="<?php echo $stock->id ?>">

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Codigo de Barras</label>
    <div class="col-md-6">
      <input type="text" name="barcode" id="product_code" class="form-control" id="barcode" placeholder="Codigo de Barras del Producto">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Multiplo Producto</label>
    <div class="col-md-6">
      <input type="text" name="multiplo" class="form-control" id="multiplo" placeholder="Multiplo Del Producto">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Descripción</label>
    <div class="col-md-6">
      <input type="text" name="name" class="form-control" id="name" placeholder="Nombre del Producto">
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


  
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Selecciona Categoria</label>
    <div class="col-md-6">
    <?php
                                        $sql_mpago = "SELECT * FROM category";
                                        //echo $sql;
                                        $resultSet = $mysqli->query($sql_mpago);
                                        ?>
                                        <select name="category_id" id = "category_id" class="form-control js-example-basic-single">
                                            <option selected value='0'>Selecciona</option>
                                            <?php
                                            while ($fila = $resultSet->fetch_assoc()) {
                                                echo "<option value='" . $fila['id'] . "'>" .utf8_decode($fila['name']) . "</option>";
                                            }
                                            ?>
      </select>    </div>
  </div>


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Selecciona Marca</label>
    <div class="col-md-6">
    <?php
                                        $sql_mpago = "SELECT * FROM brand";
                                        //echo $sql;
                                        $resultSet = $mysqli->query($sql_mpago);
                                        ?>
                                        <select name="brand_id" id = "brand_id" class="form-control js-example-basic-single">
                                            <option selected value='0'>Selecciona</option>
                                            <?php
                                            while ($fila = $resultSet->fetch_assoc()) {
                                                echo "<option value='" . $fila['id'] . "'>" .utf8_decode($fila['name']). "</option>";
                                            }
                                            ?>
      </select>    </div>
  </div>



  
  
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
  </div>

 <br>


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Descripción (Adicional)</label>
    <div class="col-md-6">
      <textarea name="description" class="form-control" id="description" placeholder="Descripcion del Producto"></textarea>
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Precio de Compra (<?php echo $currency; ?>)</label>
    <div class="col-md-6">
      <input type="text" name="price_in" class="form-control" id="price_in">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Precio de Distribuidor (<?php echo $currency; ?>)</label>
    <div class="col-md-6">
      <input type="text" name="price_out"  class="form-control" id="price_out" >
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Precio Publico Gral (<?php echo $currency; ?>)</label>
    <div class="col-md-6">
      <input type="text" name="price_out3"  class="form-control" id="price_out3" >
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Precio Mayoreo (<?php echo $currency; ?>)</label>
    <div class="col-md-6">
      <input type="text" name="price_out2"  class="form-control" id="price_out2" >
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Precio Amigo(<?php echo $currency; ?>)</label>
    <div class="col-md-6">
      <input type="text" name="price_out4"  class="form-control" id="price_out4">
    </div>
  </div>


  <script type="text/javascript">
//  $(document).ready(function(){
  //  $("#price_in").keyup(function(){
    //  $("#price_out").val( $("#price_in").val()*1.25 );
    //});

  //});
  </script>


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
    </div> -->

  </div>


  
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Minima en inventario:</label>
    <div class="col-md-6">
      <input type="text" name="inventary_min" class="form-control" id="inputEmail1" placeholder="Minima en Inventario (Default 10)">
    </div>
  </div>

  <!--

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Inventario inicial:</label>
    <div class="col-md-6">
      <input type="text" name="q" class="form-control" id="inputEmail1" placeholder="Inventario inicial">
    </div>
  </div> -->

  <br>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
    <button type="submit" class="btn btn-primary"  onclick="return procesar();">Agregar Producto</button>
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
        if (confirm("¿Deseas registrar el producto nuevo en Sistema?")) {
        return true;
        } else {
        return false;}}
        </script>
