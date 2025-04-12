<?php
 $company_name = ConfigurationData::getByPreffix("company_name")->val;
 $symbol = ConfigurationData::getByPreffix("currency")->val;
 $iva_val = ConfigurationData::getByPreffix("imp-val")->val;
 $sell = SellData::getById($_GET["id"]);
 $stock = StockData::getById($sell->stock_to_id);
?>

<section class="content">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script>
$(document).ready(function() {
    $("#product_id").select2({
		dropdownParent: $('#myModal')
	});
});</script>
<h1>Cotización # <?php echo $sell->id;?></h1><br></h1>

<!-- Button trigger modal -->
<button type="button" class="btn btn-secondary" data-coreui-toggle="modal" data-coreui-target="#myModal">
  Agregar Producto a Cotización
</button>

<!-- Modal -->
<div class="modal fade" id="myModal"  aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
		<h5 class="modal-title" id="myModal">Busca el producto para agregar a la Cotización</h5>
      </div>
	  
      <div class="modal-body">
    <form class="form-horizontal" method="post" id="addproduct" action="index.php?action=addproducttocotization" role="form">
    <input type="hidden" name="cot_id" value="<?php echo $_GET['id']; ?>">

	<style>
	.form-select{
	width:750%;}
	</style>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-20 control-label">Selecciona el producto</label>
    <div class="col-md-12">
	<?php 
	$products = ProductData::getAll(); 
  $stock = StockData::getPrincipal()->id; 
	?>

			<select  id="product_id" name ="product_id" class="form-select">
				<option value="0">-- Todos Los Productos --</option>
				<?php foreach($products as $p):?>
				<option value="<?php echo $p->id;?>"><?php echo $p->code;?> <?php echo $p->name;?></option>
				<?php endforeach; ?>
			</select>
	</div>
  </div>
  <br>

  <input type="hidden" name="stock_id" required class="form-control" id="stock_id" value ="<?php echo $stock?>">
    <script>
    (function($){
  $(document).ready(function(){
	$("#product_id").on("change", function(){
      var parametros = { // Parametros que vamos a mandar al servidor
        "id" : $(this).val(),
	    	"stock_id": $("#stock_id").val()};
		$.ajax({
        data:  parametros, // Adjuntamos los parametros
        url:   './core/app/action/traer_existencia.php', // ruta del archivo php que procesará nuestra solicitud
        type:  'post', // metodo por el cual se mandarán los datos
		dataType: "json",
        beforeSend: function () { // callback que se ejecutará antes de enviar la solicitud
          console.log("Enviando por medio de post");
        },
        success:  function (response) { // callback que se ejecutará una vez el servidor responda
		console.log("Respuesta del servidor: ", response);
        $('#existencia').val(response["existencia"]); //es mas recomendable usar ids cuando se trata de javascript/jquery
		    $('#por_entregar').val(response["por_entregar"]); //es mas recomendable usar ids cuando se trata de javascript/jquery
		
        //$("#existencia").val(response); 
        }
      });
    });
  });
})(jQuery);
</script>

<div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Existencia En Inventario</label>
    <div class="col-md-6">
      <input type="text" name="existencia" class="form-control" id="existencia" style="font-size: 20px; background-color: #00FF00;  text-align: center; color: #000000">
    </div>
  </div>
  <br>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Existencia Por Entregar</label>
    <div class="col-md-6">
      <input type="text" name="por_entregar" required class="form-control" id="por_entregar" style="font-size: 20px; background-color: #FF0000;  text-align: center; color: #FFFFFF">
    </div>
  </div>
  <br>


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-8 control-label">Cantidad a Ingresar</label>
    <div class="col-md-6">
      <input type="text" name="q" required class="form-control" id="q" placeholder="Cantidad del Producto">
    </div>
  </div>

  <br><br>
  
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
	<button type="button" class="btn btn-danger" data-coreui-dismiss="modal">Cerrar Ventana</button>
      <button type="submit" class="btn btn-primary" onclick="return procesar();">Agregar Producto</button>
    </div>
  </div> 

</form>


      </div>
    </div>
  </div>
</div>

<script>
function procesar() {
        if (confirm("¿Deseas agregar el producto?, para ello antes revisa las entregas pendientes y el inventario")) {
        return true;
        } else {
        return false;}}
        </script>

<br><br>
<?php if(isset($_GET["id"]) && $_GET["id"]!=""):?>
<?php
$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$total = 0;
?>
<?php

/*
if(isset($_COOKIE["selled"])){
	foreach ($operations as $operation) {
	print_r($operation);
		$qx = OperationData::getQByStock($operation->product_id);
		print "qx=$qx";
			$p = $operation->getProduct();
		if($qx==0){
			echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $p->name</b> no tiene existencias en inventario.</p>";			
		}else if($qx<=$p->inventary_min/2){
			echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $p->name</b> tiene muy pocas existencias en inventario.</p>";
		}else if($qx<=$p->inventary_min){
			echo "<p class='alert alert-warning'>El producto <b style='text-transform:uppercase;'> $p->name</b> tiene pocas existencias en inventario.</p>";
		}
	}
	setcookie("selled","",time()-18600);
} */

?>
<div class="box box-primary">
<table class="table table-bordered">
<?php if($sell->person_id!=""):
$client = $sell->getPerson();
?>
<tr>
	<td style="width:200px;">Nombre Del Cliente</td>
	<td><?php echo $client->name." ".$client->lastname;?></td>
</tr>

<?php endif; ?>


<?php if($sell->user_id!=""):
$user = $sell->getUser();
?>
<tr>
	<td>Atendido por</td>
	<td><?php echo $user->name." ".$user->lastname;?></td>
</tr>
<?php endif; ?>
</table>
</div>
<div class="box box-primary">
<br><table class="table table-bordered table-hover">
	<thead>
		<th style="text-align: center">Codigo</th>
		<th style="text-align: center">Cantidad</th>
		<th style="text-align: center">Nombre del Producto</th>
		<th style="text-align: center">Precio Unitario</th>
		<th style="text-align: center">Total</th>
		<th style="text-align: center">Eliminar</th>

	</thead>
<?php
	foreach($operations as $operation){
		$product  = $operation->getProduct();
?>
<tr>
	<td style="text-align: center"><?php echo $product->code ;?></td>
	<td style="text-align: center"><?php echo $operation->q ;?></td>
	<td style="text-align: center"><?php echo $product->name ;?></td>
	<td style="text-align: center"><?php echo Core::$symbol; ?> <?php echo number_format($operation->price_out,2,".",",") ;?></td>
	<td style="text-align: center"><b><?php echo Core::$symbol; ?> <?php echo number_format($operation->q*$operation->price_out,2,".",",");$total+=$operation->q*$operation->price_out;?></b></td>
	<td style="text-align: center">
		<a class="btn btn-danger btn-sm" href="./?action=delfromcotization&cotization_id=<?php echo $sell->id; ?>&op_id=<?php echo $operation->id; ?>">
			<i class="bi-trash"></i>
		</a>
	</td>
</tr>
<?php
	}
	?>
</table>
</div>
<br><br><h1>Total Cotizado : $ <?php echo number_format($total,2,'.',','); ?></h1>
	<?php

?>	
<?php else:?>
	501 Internal Error
<?php endif; ?>
</section>