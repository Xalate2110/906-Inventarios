<?php if(isset($_GET["id"]) && $_GET["id"]!=""):
$sell = SellData::getById($_GET["id"]);
if($sell->operation_type_id==2){}
else{
	Core::alert("Error, el folio no corresponde a una venta!");
	Core::redir("./?view=dev");
}
?>
<section class="content">

<h1>Ventana De Devolución</h1>
<p>Capture las cantidades de los productos a devolver.</p>
<?php
if($sell==null){
	Core::alert("No se encontro la venta!");
	Core::redir("./?view=dev");
}
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$total = 0;
?>
<div class="box box-primary">
<table class="table table-bordered">
<?php if($sell->person_id!=""):
$client = $sell->getPerson();
?>
<tr>
	<td style="width:200px;">Nombre Del Cliente</td>
	<td><?php echo utf8_encode($client->name." ".$client->lastname);?></td>
</tr>

<?php endif; ?>
<?php if($sell->user_id!=""):
$user = $sell->getUser();
?>
<tr>
	<td>Atendido Por</td>
	<td><?php echo $user->name." ".$user->lastname;?></td>
</tr>
<?php endif; ?>
</table>
</div>
<br>

<div class="box box-primary table-responsive">
                <div class="box-header">
                  <h3 class="box-title">Listado De Productos</h3>
                </div><!-- /.box-header -->
       <form method="post" action="./?action=processdevolution">
	


                <input type="hidden" name="sell_id" value="<?php echo $sell->id; ?>" >
				<input type="hidden" name="person_id" id ="person_id" value="<?php echo $client->id; ?>" >

<table class="table table-bordered table-hover ">
	<thead>
		<th style="text-align: center">Codigo</th>
		<th style="text-align: center">Cantidad A Devolver</th>
		<th style="text-align: center">Cantidad</th>
		<th style="text-align: center">Nombre del Producto</th>
		<th style="text-align: center">Precio Unitario</th>
		<th style="text-align: center">Total</th>

	</thead>
<?php
	foreach($operations as $operation){
		$product  = $operation->getProduct();
?>
<input type="hidden" name="p_salida" value="<?php echo $operation->price_out; ?>" >


<tr>
	<td style="text-align: center"><?php echo $product->code ;?></td>
	<td style="text-align: center">
	<input type="number" name="devolucion<?php echo $operation->id?>" id = "devolucion" value="" class="form-control" min="1" max="<?php echo $operation->q ;?>">
	</td>
	<script>
	$('#devolucion').focusout(function() {
 	var x = $(this).val();
    $('#cantidad').val(x); 
	});
	</script>
    <input type="hidden" name="cantidad" id="cantidad">
	<td style="text-align: center"><?php echo $operation->q ;?></td>
	<td style="text-align: center"><?php echo $product->name ;?></td>
	<td style="text-align: center"><?php echo Core::$symbol; ?> <?php echo number_format($operation->price_out,2,".",",") ;?></td>
	<td style="text-align: center"><b><?php echo Core::$symbol; ?> <?php echo number_format($operation->q*$operation->price_out,2,".",",");$total+=$operation->q*$operation->price_out;?></b></td>
</tr>
<?php
	}
	?>
	</table>
	<div class="box-body">
	<br><input type="submit" onclick="return procesar();" value="Realizar Devolucion" class="btn btn-primary">
	<a href="./?view=dev" class="btn btn-danger" >Cancelar</a>

	<td  style="text-align: center"><input type="checkbox" id="remision" name="remision" value="1" ></label></td>
    <label class="form-check-label" style="font-size: 20px;   text-align: center; color: #00000; font-weight: bold;" for="flexRadioDefault1">
    Cerrar el Ticket
    </label>
	<td  style="text-align: center"><input type="checkbox" id="remision_p" name="remision_p" value="2" ></label></td>
	<label class="form-check-label" style="font-size: 20px;   text-align: center; color: #00000; font-weight: bold;" for="flexRadioDefault1">
     Matener Ticket Parcial?
    </label>

</div>


	</div>
	
	</form>


<br><br>

</section>
<?php else:?>
	501 Internal Error
<?php endif; ?>


		<script>
		function procesar() {
        if (confirm("¿Deseas realizar la devolución de los productos?")) {
        return true;
        } else {
        return false;}}
        </script>