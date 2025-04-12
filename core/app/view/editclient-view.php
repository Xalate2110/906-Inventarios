<?php
include '/connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
?>


<section class="content">
<?php $user = PersonData::getById($_GET["id"]);
?>
<div class="row">
	<div class="col-md-12">
	<h1>Editar Cliente</h1>
	<br>
    <div class="card box-primary">
    <div class="card-header">Clientes</div>
    <div class="card-body">

		<form class="form-horizontal" method="post" id="addproduct" name ="addproduct"  action="index.php?view=updateclient" role="form">
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Precio*</label>
    <div class="col-md-6">
      <select name="price" class="form-control" id="name" placeholder="Precio">
        <option value="1" <?php if($user->price==1){ echo "selected";}?>>Precio de Distribuidor</option>
        <option value="2" <?php if($user->price==2){ echo "selected";}?>>Precio Publico Gral</option>
        <option value="3" <?php if($user->price==3){ echo "selected";}?>>Precio Mayoreo</option>
        <option value="4" <?php if($user->price==4){ echo "selected";}?>>Precio Amigo</option>
      </select>
    </div>
  </div>


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre o Razón Comercial *</label>
    <div class="col-md-6">
      <input type="text" name="name" value="<?php echo utf8_encode($user->name);?>" class="form-control" id="name" placeholder="Razon Social Comercial">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Direccion</label>
    <div class="col-md-6">
      <input type="text" name="address1" value="<?php echo utf8_encode($user->address1);?>" class="form-control" id="username" placeholder="Direccion">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Telefono Negocio</label>
    <div class="col-md-6">
      <input type="text" name="phone1"  value="<?php echo $user->phone1;?>"  class="form-control" id="inputEmail1" placeholder="Telefono">
    </div>
  </div>


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre del Encargado o Encargada</label>
    <div class="col-md-6">
      <input type="text" name="encargado" value="<?php echo utf8_encode($user->encargado);?>"  class="form-control" id="encargado" placeholder="Nombre del Encargado o Encargada">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Telefono Encargado</label>
    <div class="col-md-6">
      <input type="text" name="phone2" value="<?php echo utf8_encode($user->phone2);?>"  class="form-control" id="phone2" placeholder="Telefono Encargado">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Telefono Adicional Encargado</label>
    <div class="col-md-6">
      <input type="text" name="phone3" value="<?php echo utf8_encode($user->phone3);?>"  class="form-control" id="phone3" placeholder="Telefono Adicional Encargado">
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



  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Razón Social</label>
    <div class="col-md-6">
      <input type="text" name="lastname" value="<?php echo utf8_encode($user->lastname);?>"  class="form-control" id="lastname" placeholder="Razon Social" disabled >
    </div>
  </div>



  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">RFC cliente</label>
    <div class="col-md-6">
      <input type="text" name="no" value="<?php echo $user->no;?>" class="form-control" id="no" placeholder="RFC Del Cliente" disabled >
    </div>
  </div>


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Correo Electronico</label>
    <div class="col-md-6">
      <input type="text" name="email1" value="<?php echo $user->email1;?>" class="form-control" id="email" placeholder="Email" disabled >
    </div>
  </div>


  <div class="form-group">
  <label for="inputEmail1" class="col-lg-2 control-label">Codigo Postal</label>
  <div class="col-md-6">
  <input type="text" name="codigopostal"  value="<?php echo $user->codigopostal; ?>"  class="form-control" id="inputEmail1" placeholder="Codigo Postal" disabled >
  </div>
  </div>



<!--
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label" >Activar Acceso</label>
    <div class="col-md-6">
<div class="checkbox">
    <label>
      <input type="checkbox" name="is_active_access" <?php if($user->is_active_access){ echo "checked";}?>>
    </label>
  </div>
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Password</label>
    <div class="col-md-6">
      <input type="password" name="password" class="form-control" id="phone1" placeholder="Password">
    </div>
    </div> -->

    <div class="form-group">
                                        <label for="inputEmail1" class="col-lg-2 control-label">Forma De Pago</label>
                                        <div class="col-md-6">

                                            <?php
                                            $sql_mpago = "SELECT * FROM tblformas_pago";
                                            //echo $sql;
                                            $resultSet = $mysqli->query($sql_mpago);
                                            ?>
                                            <select name="forma_pago" id = "forma_pago" class="form-control js-example-basic-single">
                                                <option selected>Selecciona una opcion</option>
                                                <?php
                                                while ($fila = $resultSet->fetch_assoc()) {
                                                    if ($fila['id'] == $user->forma_pago) {
                                                        $seleccion = "selected";
                                                    } else {
                                                        $seleccion = "";
                                                    }
                                                    echo "<option value='" . $fila['id'] . "'" . $seleccion . ">" . $fila['clave_pago'] . "-" . $fila['descripcion'] . "</option>";
                                                }
                                                ?>
                                            </select>

                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label for="inputEmail1" class="col-lg-2 control-label">USO DEL CFDI</label>
                                        <div class="col-md-6">

                                            <?php
                                            $sql_mpago = "SELECT * FROM uso_comprobante";
                                            //echo $sql;
                                            $resultSet = $mysqli->query($sql_mpago);
                                            ?>
                                            <select name="uso_comprobante" id = "uso_comprobante" class="form-control js-example-basic-single" disabled >
                                                <option selected>Selecciona una opcion</option>
                                                <?php
                                                while ($fila = $resultSet->fetch_assoc()) {
                                                    if ($fila['id'] == $user->uso_comprobante) {
                                                        $seleccion = "selected";
                                                    } else {
                                                        $seleccion = "";
                                                    }
                                                    echo "<option value='" . $fila['id'] . "'" . $seleccion . ">" . $fila['clave'] . "-" . $fila['descripcion'] . "</option>";
                                                }
                                                ?>
                                            </select>

                                        </div>
                                    </div>
                
                           <div class="form-group">
                                        <label for="inputEmail1" class="col-lg-2 control-label">Regimen fiscal Cliente</label>
                                        <div class="col-md-6">

                                            <?php
                                            $sql_regimen = "SELECT * FROM regimen_fiscal";
                                            $resultSet = $mysqli->query($sql_regimen);
                                            ?>
                                            <select name="regimen_fiscal" id = "regimen_fiscal" class="form-control js-example-basic-single" disabled >
                                                <option selected>Selecciona una opcion</option>
                                                <?php
                                                while ($fila = $resultSet->fetch_assoc()) {
                                                    if ($fila['idregimen'] == $user->regimen_fiscal) {
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

      

           <br>

           <script>
  function funcion(){
       if(document.addproduct.tiene_rs.checked == true){
         document.addproduct.lastname.disabled = false;
         document.addproduct.no.disabled = false;
         document.addproduct.email1.disabled = false;
         document.addproduct.codigopostal.disabled = false;
         document.addproduct.regimen_fiscal.disabled = false;
         document.addproduct.uso_comprobante.disabled = false;
        }
    else{
        document.addproduct.lastname.disabled = true;
        document.addproduct.no.disabled = true;
        document.addproduct.email1.disabled = true;
        document.addproduct.codigopostal.disabled = true;
        document.addproduct.regimen_fiscal.disabled = true;
        document.addproduct.uso_comprobante.disabled = true;
    }
}
</script>






  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label" >Tiene Razon Social</label>
    <div class="col-md-6">
<div class="checkbox">
    <label>
      <input type="checkbox" name="tiene_rs" <?php if($user->tiene_rs){ echo "checked";}?> onclick="funcion()">
    </label>
  </div>
    </div>
  </div>

  
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


  <p class="alert alert-info">* Campos obligatorios</p>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
    <input type="hidden" name="user_id" value="<?php echo $user->id;?>">
    <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
                                <button type="submit" class="btn btn-success" onclick="return procesar();"> Actualizar Información Del Cliente</button>
             
                    </div>
                </div>
    </div>
  </div>
</form>
</div>
	</div>
</div>
</section>

<script>
        function procesar() {
        if (confirm("¿Deseas actualizar los datos del Cliente?")) {
        return true;
        } else {
        return false;}}
        </script>

   