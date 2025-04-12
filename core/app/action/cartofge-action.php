
<!--- Carrito de compras :) -->
<?php if(isset($_SESSION["general_inv"])):
$total = 0;
// $symbol = ConfigurationData::getByPreffix("currency")->val;
$iva_name = ConfigurationData::getByPreffix("imp-name")->val;
$iva_val = ConfigurationData::getByPreffix("imp-val")->val;
?>
<div class="card box-primary">
<div class="card-header">
Listado productos para ajustar inventario
</div>
<div class="box box-primary table-responsive">
<table class="table table-bordered table-hover table-responsive datatable">
<thead>
  <th style="text-align: center;">ID</th>
	<th style="text-align: center;">Codigo Producto</th>
	<th style="text-align: center;">Cantidad Ajustar</th>
	<th style="text-align: center;">Producto Descripción</th>
	<th style="text-align: center;">Eliminar</th>
</thead>
<?php foreach($_SESSION["general_inv"] as $p):
$product = ProductData::getById($p["product_id"]);
$price_in = $p["price_in"]; //$product->price_out;
?>
<tr>
  <td style="text-align: center;"><?php echo $product->id; ?></td>
	<td style="text-align: center;"><?php echo $product->code; ?></td>
	<td style="text-align: center;"><?php echo $p["q"]; ?></td>
	<td style="text-align: center;"><?php echo $product->name; ?></td>
	<!--<td><b><?php echo Core::$symbol; ?> <?php echo number_format($p["price_in"],2,".",","); ?></b></td>-->
 <!-- <td><b><?php echo Core::$symbol; ?> <?php echo number_format($price_in,2,".",","); ?></b></td> -->
	<!--<td><b><?php echo Core::$symbol; ?> <?php  $pt = $price_in*$p["q"]; $total +=$pt; echo number_format($pt,2,".",","); ?></b></td>-->
	<td style="text-align: center;"><a id="clearge-<?php echo $product->id; ?>" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i> Cancelar</a></td>
<script>
  $("#clearge-<?php echo $product->id; ?>").click(function(){
    $.get("index.php?view=clearge","product_id=<?php echo $product->id; ?>",function(data){
        $.get("./?action=cartofge",null,function(data2){
          $("#cartofge").html(data2);
        });

    });
  });
</script>
</tr>
<?php endforeach; ?>
</table>
</div>
</div>
<br>

<?php

$porcentaje = 1.16;
$subtotal = round($total / $porcentaje,2);
$impuesto = 0.16;
$iva = round($subtotal * $impuesto,2);
$total_venta = round($subtotal + $iva,2);


?>

<!--
<h3>RESUMEn DE INGRESO DE COMPRA</h3>

  <div class="row">
<div class="col-md-5">
<div class="box box-primary">
<table class="table table-bordered">
<tr>
	<td><p>Subtotal</p></td>
	<td><p><b><?php echo Core::$symbol; ?> <?php echo number_format($subtotal,2,'.',','); ?></b></p></td>
</tr>
<tr>
	<td><p><?php echo $iva_name." (".$iva_val."%) ";?></p></td>
	<td><p><b><?php echo Core::$symbol; ?> <?php echo number_format($iva,2,'.',','); ?></b></p></td>
</tr>
<tr>
	<td><p>Total</p></td>
	<td><p><b><?php echo Core::$symbol; ?> <?php echo number_format($total_venta,2,'.',','); ?></b></p></td>
</tr>

</table>
</div>

</div>
-->


<div class="col-md-7">
<form class="form-horizontal" id="processsell" method="post" action="./?action=processge">
<input type="hidden" name="iva" value="<?php echo $iva; ?>" class="form-control" placeholder="Total">
<input type="hidden" name="subtotal" value="<?php echo $subtotal; ?>" class="form-control" placeholder="Total">
<input type="hidden" name="total" value="<?php echo $total; ?>" class="form-control" placeholder="Total">
<!--
<div class="col-md-3">
<div class="form-group">
    <label for="inputEmail1" class="control-label">No. Factura</label>
   <input type="text" name="invoice_code" value="" class="form-control"  placeholder="Ingresa Folio De Factura"><div class="row">
  </div>
  </div>
-->
<div class="col-md-12">
<div class="form-group">
    <div class="">
    <label for="inputEmail1" class="control-label">Selecciona Almacén Ajustar</label>
<?php if(Core::$user->kind==1):?>
    <?php 
$clients = StockData::getAll();
    ?>
    <select name="stock_id" class="form-control" required>
    <option value="">-- NINGUNO --</option>
    <?php foreach($clients as $client):?>
      <option value="<?php echo $client->id;?>"><?php echo $client->name;?></option>
    <?php endforeach;?>
      </select>
    <?php else:?>
      <input type="hidden" name="stock_id" value="<?php echo StockData::getPrincipal()->id; ?>">
      <p class="form-control"><?php echo StockData::getPrincipal()->name; ?></p>
    <?php endif;?>
    </div>
  </div>
  </div>

  <br>


  <div class="form-group">
    <div class="col-lg-offset-12 col-lg-10">
      <div class="checkbox">
        <label>
    <a href="index.php?view=clearge" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar Inventario</a>
        <button class="btn btn-primary"><i class="fa fa-refresh"></i> Ajustar Inventario </button>
        </label>
      </div>
    </div>
  </div>
  </form>

</div>
</div>


<script>
  $("#processsell").submit(function(e){
    money = $("#money").val();
    if(money<<?php echo $total;?>){
      alert("No se puede efectuar la operacion");
      e.preventDefault();
    }else{
      go = confirm("¿Seguro que deseas realizar el ajuste de inventario?");
      if(go){
      e.preventDefault();
        $.post("./index.php?action=processge",$("#processsell").serialize(),function(data){
          $.get("./?action=cartofge",null,function(data2){
            $("#cartofge").html(data);
            $("#show_search_results").html("");
          });
          alert("Se ha realizado el ajuste de los productos en inventario, correctamente!");
        });
      }
        else{e.preventDefault();}
    }
  });
</script>

<?php endif; ?>