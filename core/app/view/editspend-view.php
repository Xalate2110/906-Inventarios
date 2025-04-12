<section class="content">
<?php $sesion=$_SESSION["user_id"];?>
<?php $user = SpendData::getById($_GET["id"]);?>
<div class="row">
	<div class="col-md-12">
	<h1>Editar Abono o Retiro</h1>
	<br>
		<form class="form-horizontal" method="post" id="addproduct" action="index.php?action=updatespend" role="form">

  <div class="form-group">
    <label for="exampleInputEmail1">Fecha De Movimiento (yyyy-mm-dd)</label>
    <div class="col-md-2">
    <input type="date" name="date_at" value="<?php echo $user->created_at;?>" id ="date_at" required class="form-control"  >
  </div>
</div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Concepto del Movimiento</label>
    <div class="col-md-6">
      <input type="text" name="name" value="<?php echo $user->name;?>" class="form-control" id="name" placeholder="Concepto del Movimiento">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Monto Del Movimiento </label>
    <div class="col-md-6">
      <input type="text" name="price" value="<?php echo $user->price;?>" class="form-control" id="name" placeholder="Monto Del Movimiento ">
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
    <input type="hidden" name="user_id" value="<?php echo $user->id;?>">
      <button type="submit" class="btn btn-primary" onclick="return procesar();">Actualizar Movimiento</button>
    </div>
  </div>
</form>
	</div>
</div>
</section>

<script>
function procesar() {
        if (confirm("¿Deseas actualizar la información en el sistema?")) {
        return true;
        } else {
        return false;}}
        </script>
