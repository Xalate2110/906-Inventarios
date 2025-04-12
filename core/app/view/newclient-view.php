 <?php
include '/connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
?>

<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>Nuevo Cliente</h1>
	<br>

 

<div class="card box-primary">
  <div class="card-header">Clientes</div>
    <div class="card-body">
		<form class="form-horizontal" method="post" id="addproduct" name ="addproduct" action="index.php?view=addclient" role="form">
  
    <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Precio Asignar *</label>
    <div class="col-md-6">
      <select name="price_id" class="form-control" placeholder="Precio">
        <option value="1">Precio de Distribuidor</option>
        <option value="2">Precio Publico Gral</option>
        <option value="3">Precio Mayoreo</option>
        <option value="4">Precio Amigo</option>
      </select>
    </div>
  </div>

 <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre o Razón Comercial</label>
    <div class="col-md-6">
      <input type="text" name="name" class="form-control"  id="name" placeholder="Nombre o Razón Comercial">
    </div>
  </div>


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Direccion</label>
    <div class="col-md-6">
      <input type="text" name="address1" class="form-control" id="address1" placeholder="Direccion">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Telefono Negocio</label>
    <div class="col-md-6">
      <input type="text" name="phone1" class="form-control" id="phone1" placeholder="Telefono">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre del Encargado o Encargada</label>
    <div class="col-md-6">
      <input type="text" name="encargado" class="form-control" id="encargado" placeholder="Nombre Del Encargado o Encargada">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Telefono Encargado </label>
    <div class="col-md-6">
      <input type="text" name="phone2" class="form-control" id="phone2" placeholder="Telefono Encargado">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Telefono Adicional Encargado </label>
    <div class="col-md-6">
      <input type="text" name="phone3" class="form-control" id="phone3" placeholder="Telefono Adicional Encargado">
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

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label" >Tiene Razón Social</label>
    <div class="col-md-6">
<div class="checkbox">
    <label>
      <input type="checkbox" name="tiene_rs" onclick="funcion()" >
    </label>
  </div>
    </div>
  </div>


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Razón Social</label>
    <div class="col-md-6">
      <input type="text" name="lastname" class="form-control"  id="lastname" placeholder="Razón Social" disabled  >
    </div>
  </div>


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">RFC Cliente</label>
    <div class="col-md-6">
      <input type="text" name="no" class="form-control" id="no" placeholder="RFC del Cliente" disabled  >
    </div>
  </div>
  
 


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Correo Electronico</label>
    <div class="col-md-6">
      <input type="text" name="email1" class="form-control" id="email1" placeholder="Email" disabled  >
    </div>
  </div>



  <div class="form-group">
  <label for="inputEmail1" class="col-lg-2 control-label">Codigo Postal</label>
  <div class="col-md-6">
  <input type="text" name="codigopostal" class="form-control" id="codigopostal" placeholder="Codigo Postal" disabled >
  </div>
  </div>

 

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
                                                echo "<option value='" . $fila['id'] . "'>" . $fila['clave_pago'] . "  -  " . $fila['descripcion'] . "</option>";
                                            }
                                            ?>
                                        </select>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail1" class="col-lg-2 control-label">Uso CFDI Cliente</label>
                                    <div class="col-md-6">

                                        <?php
                                        $sql_mpago2 = "SELECT * FROM uso_comprobante";
                                        //echo $sql;
                                        $resultSet = $mysqli->query($sql_mpago2);
                                        ?>
                                        <select name="uso_comprobante" id = "uso_comprobante" class="form-control js-example-basic-single" disabled >
                                            <option selected>Selecciona una opcion</option>
                                            <?php
                                            while ($fila = $resultSet->fetch_assoc()) {
                                                echo "<option value='" . $fila['id'] . "'>" . $fila['clave'] . "  -  " . $fila['descripcion'] . "</option>";
                                            }
                                            ?>
                                        </select>

                                    </div>
                                </div>
                    
                        <div class="form-group">
                                    <label for="inputEmail1" class="col-lg-2 control-label">Regimen Fiscal Cliente</label>
                                    <div class="col-md-6">

                                        <?php
                                        $sql_mpago = "SELECT * FROM regimen_fiscal";
                                        //echo $sql;
                                        $resultSet = $mysqli->query($sql_mpago);
                                        ?>
                                        <select name="regimen_fiscal" id = "regimen_fiscal" class="form-control js-example-basic-single" disabled >
                                            <option selected>Selecciona una opcion</option>
                                            <?php
                                            while ($fila = $resultSet->fetch_assoc()) {
                                                echo "<option value='" . $fila['idregimen'] . "'>" . $fila['idregimen'] . "  -  " . $fila['descripcion']. "</option>";
                                            }
                                            ?>
                                        </select>

                                    </div>
                                </div>



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
    </div>



    <br>

  <p class="alert alert-info">* Campos obligatorios</p>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
    <button type="submit" class="btn btn-success" onclick="return procesar();"> Registrar cliente en sistema</button>
    </div>
  </div>
</form>
</div>
</div>

</div>
</section>

<script>
function procesar() {
        if (confirm("¿Deseas registrar al cliente en Sistema?")) {
        return true;
        } else {
        return false;}}
        </script>