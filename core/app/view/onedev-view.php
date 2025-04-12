<section class="content">
<h1>Resumen de Devolucion</h1>
<div class="btn-group ">
  <button type="button" class="btn btn-outline-dark dropdown-toggle" data-coreui-toggle="dropdown">
    <i class="fa fa-download"></i> Descargar <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
   <!-- <li><a href="report/onedev-word.php?id=<?php echo $_GET["id"];?>">Word 2007 (.docx)</a></li> -->
<li><a class="dropdown-item" onclick="thePDF()" id="makepdf" class=""><i class="fa fa-download"></i> Descargar PDF</a>
  </ul>
</div>
<br><br>
<?php if(isset($_GET["id"]) && $_GET["id"]!=""):?>
<?php
$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId2($_GET["id"]);
$total = 0;
?>
<div class="box box-primary">
<table class="table table-bordered">
<?php if($sell->person_id!=""):
$client = $sell->getPerson();
?>
<tr>
  <td style="width:150px;">Cliente</td>
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
<br>
<div class="box box-primary">
<table class="table table-bordered table-hover">
  <thead>
    <th>Codigo</th>
    <th>Cantidad</th>
    <th>Nombre del Producto</th>
    <th>Precio Unitario</th>
    <th>Total</th>

  </thead>
<?php
  foreach($operations as $operation){
    $product  = $operation->getProduct();
?>
<tr>
  <td><?php echo $product->id ;?></td>
  <td><?php echo $operation->q ;?></td>
  <td><?php echo $product->name ;?></td>
  <td><?php echo Core::$symbol; ?> <?php echo number_format($operation->price_out,2,".",",") ;?></td>
  <td><b><?php echo Core::$symbol; ?> <?php echo number_format($operation->q*$operation->price_out,2,".",",");$total+=$operation->q*$operation->price_out;?></b></td>
</tr>
<?php
  }
  $discount=0;
  if($sell->discount){ $discount = $sell->discount; }
  ?>
</table>
</div>
<br><br>
<div class="row">
<div class="col-md-4">
<div class="box box-primary">
<table class="table table-bordered">
  <tr>
    <td><h4>Descuento:</h4></td>
    <td><h4><?php echo Core::$symbol; ?> <?php echo number_format($discount,2,'.',','); ?></h4></td>
  </tr>
  <tr>
    <td><h4>Subtotal:</h4></td>
    <td><h4><?php echo Core::$symbol; ?> <?php echo number_format($total,2,'.',','); ?></h4></td>
  </tr>
  <tr>
    <td><h4>Total:</h4></td>
    <td><h4><?php echo Core::$symbol; ?> <?php echo number_format($total-  $discount,2,'.',','); ?></h4></td>
  </tr>
</table>
</div>

<?php if($sell->person_id!=""):
$credit=PaymentData::sumByClientId($sell->person_id)->total;

?>
<div class="box box-primary">
<table class="table table-bordered">
  <tr>
    <td><h4>Saldo anterior:</h4></td>
    <td><h4><?php echo Core::$symbol; ?> <?php echo number_format($credit-$total,2,'.',','); ?></h4></td>
  </tr>
  <tr>
    <td><h4>Saldo Actual:</h4></td>
    <td><h4><?php echo Core::$symbol; ?> <?php echo number_format($credit,2,'.',','); ?></h4></td>
  </tr>
</table>
</div>
<?php endif;?>
</div>
</div>









<?php else:?>
  501 Internal Error
<?php endif; ?>
</section>