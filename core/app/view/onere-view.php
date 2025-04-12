<?php
$sell = SellData::getById($_GET["id"]);
$symbol = ConfigurationData::getByPreffix("currency")->val;
$title = ConfigurationData::getByPreffix("ticket_title")->val;
$iva_val = ConfigurationData::getByPreffix("imp-val")->val;
?>
<section class="content">






<h1>Resumen De Compra - # <?php echo $sell->id;?></h1>
<?php if(isset($_GET["id"]) && $_GET["id"]!=""):?>
<?php
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$total = 0;
?>
<?php
/*
if(isset($_COOKIE["selled"])){
	foreach ($operations as $operation) {
//		print_r($operation);
		$qx = OperationData::getQYesF($operation->product_id);
		// print "qx=$qx";
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
}
*/
?>
<div class="row">
<div class="col-md-8">
<div class="box box-primary">
<table class="table table-bordered">
<?php if($sell->person_id!=""):
$client = $sell->getPerson();
?>
<tr>
	<td style="width:150px;">Proveedor</td>
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
		<th style="text-align: center">Descripción del Producto</th>
		<th style="text-align: center">Precio Compra</th>
		<th style="text-align: center">Total</th>

	</thead>
<?php
	foreach($operations as $operation){
		$product  = $operation->getProduct();
?>
<tr>
	<td style="text-align: center"><?php echo $product->code ;?></td>
	<td style="text-align: center"><?php echo $operation->q ;?></td>
	<td style="text-align: center"><?php echo $product->name ;?></td>
	<td style="text-align: center"><?php echo Core::$symbol; ?> <?php echo number_format($operation->price_in,2,".",",") ;?></td>
	<td style="text-align: center"><b><?php echo Core::$symbol; ?> <?php echo number_format($operation->q*$operation->price_in,2,".",",");$total+=$operation->q*$operation->price_in;?></b></td>
</tr>
<?php
	}
	?>
</table>
</div>

</div>
<div class="col-md-4">
<form method="post" class="form-horizontal" action="./?action=updatere" id="processsell" enctype="multipart/form-data">
<div class="row">
<div class="col-md-12">
<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab">Principal</a></li>
    <li role="presentation"><a href="#extra"  aria-controls="extra" role="tab" data-toggle="tab">Extra</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="main">
<div class="row">

<div class="col-md-12">
    <label class="control-label">No. Factura</label>
    <div class="col-lg-12">
      <input type="text" name="invoice_code" value="<?php echo $sell->invoice_code;?>" class="form-control"  placeholder="No. Factura">
    </div>
  </div>
  </div>
<div class="row">

<div class="col-md-6">
    <label class="control-label">Almacen</label>
    <div class="col-lg-12">
    <h4 class=""><?php 
    echo StockData::getPrincipal()->name;
    ?></h4>
    </div>
  </div>

  <?php 
   $stock_id = StockData::getPrincipal()->id;
  ?>

<div class="col-md-6">
    <label class="control-label">Proveedor</label>
    <div class="col-lg-12">
    <?php 
$clients = PersonData::getProviders($stock_id);
    ?>
    <select name="client_id" id="client_id" class="form-control">
    <option value="">-- NINGUNO --</option>
    <?php foreach($clients as $client):?>
      <option value="<?php echo $client->id;?>" <?php if($client->id==$sell->person_id){ echo "selected"; }?>><?php echo $client->name." ".$client->lastname;?></option>
    <?php endforeach;?>
      </select>
    </div>
  </div>
  </div>

<div class="row">

<div class="col-md-12">
    <label class="control-label">Forma de pago</label>
    <div class="col-lg-12">
    <?php 
$clients = FData::getAll();
    ?>
    <select name="f_id" id="p_id" class="form-control">
    <?php foreach(FData::getAll() as $client):?>
      <option value="<?php echo $client->id;?>" <?php if($client->id==$sell->f_id){ echo "selected"; }?>><?php echo $client->name;?></option>
    <?php endforeach;?>
      </select>
    </div>
  </div>

</div>
    </div>
    <div role="tabpanel" class="tab-pane" id="extra">

<div class="row">

<div class="col-md-12">
    <label class="control-label">Archivo Factura</label>
    <div class="col-lg-12">
    <?php if($sell->invoice_file!=""):?>
      <a href="./storage/invoice_files/<?php echo $sell->invoice_file;?>" target="_blank" class="btn btn-default"><i class="fa fa-file"></i> Archivo Factura (<?php echo $sell->invoice_file; ?>)</a>
      <br><br>
    <?php endif; ?>
      <input type="file" name="invoice_file"  placeholder="Archivo Factura">
    </div>
  </div>
  </div>

<div class="row">

<div class="col-md-12">
    <div class="">
    <label class="control-label">Comentarios</label>
      <textarea name="comment"  placeholder="Comentarios" class="form-control" rows="10"><?php echo $sell->comment;?></textarea>
    </div>
  </div>
  </div>

    </div>
  </div>

</div>
</div>
</div>



<br>
<input type="hidden" name="id" value="<?php echo $sell->id; ?>">
  <div class="row">
<div class="col-md-12">

<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <div class="checkbox">
        <label>
        <button class="btn btn-success"  onclick="return procesar();"> Actualizar Compra</button>
        </label>
      </div>
    </div>
  </div>
</form>
</div>
</div>

<script>
function procesar() {
        if (confirm("¿Deseas actualizar la información de la compra?")) {
        return true;
        } else {
        return false;}}
        </script>

<br><br><h1>Total: <?php echo Core::$symbol; ?> <?php echo number_format($total,2,'.',','); ?></h1>
	<?php

?>	




<?php else:?>
	501 Internal Error
<?php endif; ?>
</section>