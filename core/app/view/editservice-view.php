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
$product = ProductData::getById($_GET["id"]);
$categories = CategoryData::getAll();

if($product!=null):
?>
<div class="row">
	<div class="col-md-12">
	<h1><?php echo $product->name ?> <small>Editar Servicio</small></h1>
  <?php if(isset($_COOKIE["prdupd"])):?>
    <p class="alert alert-info">La informacion del Servicio se ha actualizado exitosamente.</p>
  <?php setcookie("prdupd","",time()-18600); endif; ?>
	<br>
  <div class="card box-primary">
    <div class="card-header">Servicios</div>
    <div class="card-body">

  <table class="table">
  <tr>
  <td>
		<form class="form-horizontal" method="post" id="addproduct" enctype="multipart/form-data" action="index.php?view=updateproduct&opt=service" role="form">


    <!--
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Imagen*</label>
    <div class="col-md-6">
      <input type="file" name="image" id="name" placeholder="">
<?php if($product->image!=""):?>
  <br>
        <img src="storage/products/<?php echo $product->image;?>" class="img-responsive">
<?php endif;?>
    </div>
  </div> -->

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Codigo Interno*</label>
    <div class="col-md-6">
      <input type="text" name="code" class="form-control" id="code" value="<?php echo $product->code; ?>" placeholder="Codigo Interno del Servicio">
    </div>
  </div>

<!--
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Codigo de barras*</label>
    <div class="col-md-6">
      <input type="text" name="barcode" class="form-control" id="barcode" value="<?php echo $product->barcode; ?>" placeholder="Codigo de barras del Servicio">
    </div>
  </div> -->

    <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Nombre Servicio *</label>
    <div class="col-md-6">
      <input type="text" name="name" class="form-control" id="name" value="<?php echo $product->name; ?>" placeholder="Nombre del Servicio">
    </div>
  </div>

  <!--
    <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Categoria</label>
    <div class="col-md-6">
    <select name="category_id" class="form-control">
    <option value="">-- NINGUNA --</option>
    <?php foreach($categories as $category):?>
      <option value="<?php echo $category->id;?>" <?php if($product->category_id!=null&& $product->category_id==$category->id){ echo "selected";}?>><?php echo $category->name;?></option>
    <?php endforeach;?>
      </select>    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Marca</label>
    <div class="col-md-6">
    <select name="brand_id" class="form-control">
    <option value="">-- NINGUNA --</option>
    <?php foreach(BrandData::getAll() as $category):?>
      <option value="<?php echo $category->id;?>" <?php if($product->brand_id!=null&& $product->brand_id==$category->id){ echo "selected";}?>><?php echo $category->name;?></option>
    <?php endforeach;?>
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
                                                <option selected>Unidad de Medida</option>
                                                <?php
                                                while ($fila = $resultSet->fetch_assoc()) {
                                                    if ($fila['id'] == $product->unit) {
                                                        $seleccion = "selected";
                                                    } else {
                                                        $seleccion = "";
                                                    }
                                                    echo "<option value='" . $fila['id'] . "'" . $seleccion . ">" . $fila['id_unidad'] . "-" . $fila['name'] . "</option>";
                                                }
                                                ?>
                                            </select>

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail1" class="col-lg-3 control-label">Presentación</label>

                                        <div class="col-md-6">

                                            <?php
                                            $sql_unidades = "SELECT * FROM tblunidades";
                                            //echo $sql;
                                            $resultSet = $mysqli->query($sql_unidades);
                                            ?>
                                            <select name="presentation" id="presentation" class="form-control js-example-basic-single">
                                                <option selected>presentacion</option>
                                                <?php
                                                while ($fila = $resultSet->fetch_assoc()) {
                                                    if ($fila['id'] == $product->presentation) {
                                                        $seleccion = "selected";
                                                    } else {
                                                        $seleccion = "";
                                                    }
                                                    echo "<option value='" . $fila['id'] . "'" . $seleccion . ">" . $fila['descripcion'] . "</option>";
                                                }
                                                ?>
                                            </select>

                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail1" class="col-lg-3 control-label">Descripción SAT</label>

                                        <div class="col-md-6">

                                            <?php
                                            $sql_sat = "SELECT * FROM tblcodigos_sat";
                                            //echo $sql;
                                            $resultSet = $mysqli->query($sql_sat);
                                            ?>
                                            <select name="codigo_sat" id = "codigo_sat" class="form-control js-example-basic-single">
                                                <option selected>Descripcion Sat</option>
                                                <?php
                                                while ($fila = $resultSet->fetch_assoc()) {
                                                    if ($fila['id'] == $product->codigo_sat) {
                                                        $seleccion = "selected";
                                                    } else {
                                                        $seleccion = "";
                                                    }
                                                    echo "<option value='" . $fila['id'] . "'" . $seleccion . ">" . $fila['id_codigo'] . "-" . $fila['descripcion'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    
         
                                    
                                       <div class="form-group">
                                        <label for="inputEmail1" class="col-lg-3 control-label">Impuesto Producto SAT</label>

                                        <div class="col-md-6">

                                            <?php
                                            $sql_sat = "SELECT * FROM objetoimp";
                                            //echo $sql_sat;
                                            $resultSet = $mysqli->query($sql_sat);
                                            ?>
                                            <select name="objetoimp" id = "objetoimp" class="form-control js-example-basic-single">
                                                <option selected>Impuesto Sat</option>
                                                <?php
                                                while ($fila = $resultSet->fetch_assoc()) {
                                                    if ($fila['id_ObjetoImp'] == $product->ObjetoImp) {
                                                        $seleccion = "selected";
                                                    } else {
                                                        $seleccion = "";
                                                    }
                                                    echo "<option value='" . $fila['id_ObjetoImp'] . "'" . $seleccion . ">" . $fila['id_ObjetoImp'] . "-" . $fila['descripcion'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div> -->

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Descripcion</label>
    <div class="col-md-6">
      <textarea name="description" class="form-control" id="description" placeholder="Descripcion del Producto"><?php echo $product->description;?></textarea>
    </div>
  </div>

  <!--
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Precio de Entrada*</label>
    <div class="col-md-6">
      <input type="text" name="price_in" class="form-control" value="<?php echo $product->price_in; ?>" id="price_in" placeholder="Precio de entrada">
    </div>
  </div> -->


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Precio de Venta *</label>
    <div class="col-md-6">
      <input type="text" name="price_out" class="form-control" id="price_out" value="<?php echo $product->price_out; ?>" placeholder="Precio de Venta">
    </div>
  </div>
  <!--
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Precio de Salida 2*</label>
    <div class="col-md-6">
      <input type="text" name="price_out2" class="form-control" id="price_out2" value="<?php echo $product->price_out2; ?>" placeholder="Precio de salida 2">
    </div>
  </div>
    <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Precio de Salida 3*</label>
    <div class="col-md-6">
      <input type="text" name="price_out3" class="form-control" id="price_out3" value="<?php echo $product->price_out3; ?>" placeholder="Precio de salida 3">
    </div>
  </div> -->


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

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label" >Esta activo</label>
    <div class="col-md-6">
<div class="checkbox">
    <label>
      <input type="checkbox" name="is_active" <?php if($product->is_active){ echo "checked";}?>> 
    </label>
  </div>
    </div>
  </div>

  <div class="form-group">
    <div class="col-lg-offset-3 col-lg-8">
    <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
    <button type="submit" class="btn btn-primary"  onclick="return procesar();">Actualizar Servicio</button>
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
<?php endif; ?>
</section>
<script>
        function procesar() {
        if (confirm("¿Deseas actualizar el producto / servicio en Sistema?")) {
        return true;
        } else {
        return false;}}
        </script>
