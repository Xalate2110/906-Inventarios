<?php

$client = PersonData::getById($_GET["id"]);
$sells = SellData::getCreditsByClientId2($client->id);
// print_r($sells);
//print_r($sells);
//print_r($products);
$total=0;
$credit_array = array();
foreach ($sells as $sell) {
//  print_r($sell);
$tx = PaymentData::sumBySellId($sell->id)->total;

if($tx>=0){
$credit_array[] = array("sell_id"=>$sell->id,"total"=>$tx);
$total+=$tx;
}
}
//$total = PaymentData::sumByClientId($client->id)->total;

?>

<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>Realizar Pago</h1>
  <h3>Deuda total: $ <?php echo $total; ?></h3>
	<br>
  <?php if(count($credit_array)>0):?>
    <?php foreach($credit_array as $ca):?>
  <div class="card box-primary">
    <div class="card-header">Listado Créditos</div>
    <div class="card-body">
  <table class="table">
  <tr>
  <td>
		<form class="form-horizontal" method="post" enctype="multipart/form-data" id="addpayment<?php echo $ca['sell_id']; ?>" action="index.php?action=addpaymentp" role="form">
<input type="hidden" name="sell_id" value="<?php echo $ca['sell_id'];?>">
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Venta</label>
    <div class="col-md-4">
      <input type="text" name="" id="" class="form-control" id="barcode" placeholder="Cliente" value="#<?php echo $ca['sell_id'] ?>" readonly>
    </div>
    <div class="col-md-2">
    <a href="./?view=onesell&id=<?php echo $ca['sell_id']; ?>" class='btn btn-default'>Ver detalles</a>
    </div>

  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre Cliente</label>
    <div class="col-md-6">
      <input type="text" name="" id="product_code" class="form-control" id="barcode" placeholder="Cliente" value="<?php echo utf8_encode($client->name); ?>" readonly>
      <input type="hidden" name="client_id" class="form-control"  value="<?php echo $client->id; ?>">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Total adeudado</label>
    <div class="col-md-6">
      <input type="text" name="" id="" class="form-control" placeholder="Total adeudado" value="$ <?php echo round($ca['total'],2); ?>" readonly>
    <!-- Se envia el total de la venta que aun continua con adeudo -->
    <input type="hidden" name="total" id="total<?php echo $ca['sell_id']; ?>" class="form-control"  value="<?php echo round($ca['total'],2); ?>">
    </div>
  </div>
                                        
  <div class="form-group">
  <label for="inputEmail1" class="col-lg-2 control-label">Forma De Pago</label>
  <div class="col-md-6">
                                                
  <select name="id_formapago" id="id_formapago" class="form-control" required>
  <option value="">Selecciona.....</option>
  <option value="1">1 - Efectivo </option>
  <option value="2">2 - Transferencia Electronica</option>
  </select>
  </div>
                                            
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Pago a Realizar*</label>
    <div class="col-md-6">
      <input type="text" name="val" required id="val<?php echo $ca['sell_id']; ?>" class="form-control" placeholder="Pago a Realizar">
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
    </div>

        
    <br>
      <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
          <button type="submit" class="btn btn-primary">Realizar Pago</button>
          <a href="./?view=credit" class="btn btn-danger">Cancelar</a>
        </div>
      </div>
    </form>
    </td>
    </tr>
    </table>
    </div>
    </div>


<script>
  $(document).ready(function(){
    $("#addpayment<?php echo $ca['sell_id']; ?>").submit(function(e){
      total = $("#total<?php echo $ca['sell_id']; ?>").val();
      val = $("#val<?php echo $ca['sell_id']; ?>").val();
      id_venta =  $("#id_venta<?php echo $ca['sell_id']; ?>").val();
      
                               
     
      if( val!="" && val>0 ){
        if(parseFloat(val)<=parseFloat(total)){
          // procesamos
          go = confirm("¿Deseas aplicar el abono al crédito el cliente?");
          if(!go){ e.preventDefault(); }
        }else{
        alert("No es posible ingresar un pago mayor a la deuda total.")
        e.preventDefault();          
        }

      }else{
        alert("Debes ingresar un valor mayor que 0.")
        e.preventDefault();
      }
    });
});

</script>
<?php endforeach; ?>
<?php endif; ?>

	</div>
</div>

<script>
  $(document).ready(function(){
    $("#addpayment").submit(function(e){
      total = $("#total").val();
      val = $("#val").val();
      if( val!="" && val>0 ){
        console.log(total);
        if(parseFloat(val)<=parseFloat(total)){
          // procesamos
          go = confirm("¿Deseas aplicar el abono al Crédito?");
          if(!go){ e.preventDefault(); }
        }else{
        alert("No es posible ingresar un pago mayor a la deuda total.")
        e.preventDefault();          
        }

      }else{
        alert("Debes ingresar un valor mayor que 0.")
        e.preventDefault();
      }
    });
});

</script>
</section>