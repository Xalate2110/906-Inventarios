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
$stock = StockData::getById($_GET["stock"]);
$categories = CategoryData::getAll();

if($product!=null):
?>
<div class="row">
	<div class="col-md-12">
	 <h3>Editar Producto</h3> <h5><?php echo $product->name ?></h5>
  <?php if(isset($_COOKIE["prdupd"])):?>
    <p class="alert alert-info">La informacion del producto se ha actualizado exitosamente.</p>
  <?php setcookie("prdupd","",time()-18600); endif; ?>
	<br>
  <div class="card box-primary">
    <div class="card-header">Productos</div>
    <div class="card-body">

  <table class="table">
  <tr>
  <td>
		<form class="form-horizontal" method="post" id="addproduct" enctype="multipart/form-data" action="index.php?view=updateproduct" role="form">

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Imagen*</label>
    <div class="col-md-6">
      <input type="file" name="image" id="image" placeholder="">
<?php if($product->image!=""):?>
  <br>
        <img src="storage/products/<?php echo $product->image;?>" class="img-responsive">
<?php endif;?>
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Modelo*</label>
    <div class="col-md-6">
      <input type="text" name="code" class="form-control" id="code" value="<?php echo $product->code; ?>" placeholder="Modelo">
    </div>
  </div>

  <input type="hidden" name="stock" class="form-control" id="stock" value="<?php echo $stock->id ?>">


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Codigo de barras*</label>
    <div class="col-md-6">
      <input type="text" name="barcode" class="form-control" id="barcode" value="<?php echo $product->barcode; ?>" placeholder="Codigo de barras del Producto">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Multiplo Producto</label>
    <div class="col-md-6">
      <input type="text" name="multiplo" class="form-control" id="multiplo" value="<?php echo $product->multiplo; ?>" placeholder="Multiplo del Producto">
    </div>
  </div>
    <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Descripción*</label>
    <div class="col-md-6">
      <input type="text" name="name" class="form-control" id="name" value="<?php echo $product->name; ?>" placeholder="Nombre del Producto">
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
  </div>-->


  <div class="form-group">
                                        <label for="inputEmail1" class="col-lg-3 control-label">Selecciona Categoria</label>
                                        <div class="col-md-6">

                                            <?php
                                            $sql_mpago = "SELECT * FROM category";
                                            //echo $sql;
                                            $resultSet = $mysqli->query($sql_mpago);
                                            ?>
                                            <select name="category_id" id = "category_id" class="form-control js-example-basic-single">
                                                <option selected>Categoria</option>
                                                <?php
                                                while ($fila = $resultSet->fetch_assoc()) {
                                                    if ($fila['id'] == $product->category_id) {
                                                        $seleccion = "selected";
                                                    } else {
                                                        $seleccion = "";
                                                    }
                                                    echo "<option value='" . $fila['id'] . "'" . $seleccion . ">" . utf8_decode($fila['name']). "</option>";
                                                }
                                                ?>
                                            </select>

                                        </div>
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
                                                <option selected>Marca</option>
                                                <?php
                                                while ($fila = $resultSet->fetch_assoc()) {
                                                    if ($fila['id'] == $product->brand_id) {
                                                        $seleccion = "selected";
                                                    } else {
                                                        $seleccion = "";
                                                    }
                                                    echo "<option value='" . $fila['id'] . "'" . $seleccion . ">" . utf8_decode($fila['name']). "</option>";
                                                }
                                                ?>
                                            </select>

                                        </div>
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
                                    </div>


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Descripcion (Adicional)</label>
    <div class="col-md-6">
      <textarea name="description" class="form-control" id="description" placeholder="Descripcion del Producto"><?php echo $product->description;?></textarea>
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Precio de Compra *</label>
    <div class="col-md-6">
      <input type="text" name="price_in" class="form-control" value="<?php echo $product->price_in; ?>" id="price_in">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Precio de Distribuidor *</label>
    <div class="col-md-6">
      <input type="text" name="price_out" class="form-control" id="price_out" value="<?php echo $product->price_out; ?>">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Precio Publico Gral *</label>
    <div class="col-md-6">
      <input type="text" name="price_out3" class="form-control" id="price_out3" value="<?php echo $product->price_out3; ?>">
    </div>


  </div>
    <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Precio Mayoreo *</label>
    <div class="col-md-6">
      <input type="text" name="price_out2" class="form-control" id="price_out2" value="<?php echo $product->price_out2; ?>">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Precio Amigo *</label>
    <div class="col-md-6">
      <input type="text" name="price_out4" class="form-control" id="price_out4" value="<?php echo $product->price_out4; ?>">
    </div>
  </div>

 
<!--
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label"></label>
    <div class="col-md-2">
    <label class="control-label">Anchura*</label>
      <input type="text" name="width" value="<?php echo $product->width; ?>" class="form-control" placeholder="Ancho">
    </div>
    <div class="col-md-2">
    <label class="control-label">Altura*</label>
      <input type="text" name="height" value="<?php echo $product->height; ?>" class="form-control"  placeholder="Altura">
    </div>
    <div class="col-md-2">
    <label class="control-label">Peso*</label>
      <input type="text" name="weight" value="<?php echo $product->weight; ?>" class="form-control" placeholder="Peso">
    </div>
  -->


  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Minima en inventario:</label>
    <div class="col-md-6">
      <input type="text" name="inventary_min" class="form-control" value="<?php echo $product->inventary_min;?>" id="inputEmail1" placeholder="Minima en Inventario (Default 10)">
    </div>
  </div>


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
    <button type="submit" class="btn btn-primary"  onclick="return procesar();">Actualizar Producto</button>
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
        if (confirm("¿Deseas actualizar el producto en Sistema?")) {
        return true;
        } else {
        return false;}}
        </script>
