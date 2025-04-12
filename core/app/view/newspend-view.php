<?php $sesion=$_SESSION["user_id"];?>
<section class="content">
<div class="row">
	<div class="col-md-12">

<div class="card">
  <div class="card-body">


	<h1>Registro De Abono o Retiro</h1>
	<br>
  <div class="col-md-8">
		<form class="form-horizontal" method="post" id="addcategory" action="index.php?action=addspend" role="form">

    <label for="exampleInputEmail1" class="col-lg-2 control-label">Tipo Movimiento</label>
    <div class="col-md-6">
    <select name="kind" id = "kind" class="form-control" class="col-lg-2" required>
    <option value="">Seleccione una opción</option>
      <option value="1">Aplicar Abono</option>
      <option value="2">Aplicar Retiro</option>
    </select>
    </div>
  </div>

  <div class="form-group">
    <label for="exampleInputEmail1">Fecha De Movimiento (yyyy-mm-dd)</label>
    <div class="col-md-2">
    <input type="date" name="date_at" required class="form-control"  >
  </div>
</div>


  <div class="form-group">
    <label for="exampleInputEmail1" class="col-lg-2 control-label">Concepto del Movimiento</label>
    <div class="col-md-6">
      <input type="text" name="name" required class="form-control" id="name" placeholder="Concepto">
    </div>
  </div>

  <div class="form-group">
    <label for="exampleInputEmail1" class="col-lg-2 control-label">Monto Del Movimiento </label>
    <div class="col-md-6">
      <input type="text" name="price" required class="form-control" id="name" placeholder="Costo">
    </div>
  </div>

  <div class="form-group">
  <label for="exampleInputEmail1" class="col-lg-2 control-label">Sucursal Operación</label>
  <div class="col-md-6">
		   <select name="stock_id" id="stock_id" class="form-control">
			<option value="">Selecciona La Sucursal</option>
			<?php foreach(StockData::getAll() as $stock):?>
				<option value="<?php echo $stock->id; ?>" <?php
        if ($stock->id == StockData::getPrincipal()->id) {
        echo "selected";}?>><?php echo $stock->name; ?></option>
			<?php endforeach; ?>
		</select>
    </div>
	      </div>
        
   <div class="form-group">
  <label for="exampleInputEmail1" class="col-lg-8 control-label">Usuario Quien Realiza El Movimiento : </label>
  <div class="col-md-6">
		   <select name="user_id" id="user_id" class="form-control">
			<option value="">Selecciona el usuario</option>
			<?php foreach(UserData::getAll2($sesion) as $stock):?>
				<option value="<?php echo $stock->id; ?>"><?php echo $stock->username; ?></option>
			<?php endforeach; ?>
		</select>
    </div>
	      </div>




<br>
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-primary" onclick="return procesar();">Agregar Gasto</button>
    </div>
  </div>
      </div>
</form>

  </div>
  </div>


	</div>
</div>
</section>

<script>
function procesar() {
        if (confirm("¿Deseas regtrar el movimiento en el sistema?")) {
        return true;
        } else {
        return false;}}
        </script>
