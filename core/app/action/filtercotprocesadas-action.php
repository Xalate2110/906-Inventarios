

<?php
$products = null;
// print_r(Core::$user);
if(isset($_SESSION["user_id"])){
if(Core::$user->kind==3){
$products = SellData::getAllBySQL3(" where user_id=".Core::$user->id." and stock_to_id=".Core::$user->stock_id." and operation_type_id=2 and p_id=2 and d_id=2 and is_draft=1  order by f_cotprocesada desc");

}
else if(Core::$user->kind==2 || Core::$user->kind==4){

$products = SellData::getAllBySQL3(" where operation_type_id=2 and p_id=2 and d_id=2 and is_draft=1 and stock_to_id=".Core::$user->stock_id." order by f_cotprocesada desc");
}
else{
//print_r($_GET);
$sql = "select * from sell ";
$whereparams = array();
$whereparams[] = " (operation_type_id=2 and p_id=2 and d_id=2 and is_draft=1) ";
if(isset($_GET["stock_id"]) && $_GET["stock_id"]!=""){
	$whereparams[] = " stock_to_id=$_GET[stock_id] ";
}
if(isset($_GET["start_at"]) && isset($_GET["finish_at"]) && $_GET["start_at"]!="" && $_GET["finish_at"]!=""){
	$whereparams[] = " ( date(f_cotprocesada) between '$_GET[start_at]' and '$_GET[finish_at]' ) ";
}$sql2 = $sql." where ".implode(" and ", $whereparams)." order by f_cotprocesada desc";
 $products = SellData::getAllBySQL3($sql2);}
}else if(isset($_SESSION["client_id"])){
$products = SellData::getAllBySQL3(" where person_id=$_SESSION[client_id] and operation_type_id=2 and p_id=1 and d_id=1 and is_draft=0 order by f_cotprocesada desc");	
}



if(count($products)>0){
 ?>
<br>
<div class="card box-primary">
<div class="card-header">
Listado Cotizaciónes Procesadas.
</div>
<div class="box box-primary table-responsive">
<table class="table table-bordered table-hover table-responsive datatable">
	<thead>
		<th style="text-align: center">Detalle</th>
		<th style="text-align: center">Folio</th>	
		<th style="text-align: center">Pago</th>
		<th style="text-align: center">Entrega</th>
		<th style="text-align: center">Total</th>
		<th style="text-align: center">Cliente</th>
		<th style="text-align: center">Vendedor</th>
		<th style="text-align: center">Almacen</th>
		<th style="text-align: center">Fecha</th>
        <th style="text-align: center">Forma Pago</th>

	</thead>
  
	<?php foreach($products as $sell):
      $acumulado=0;
	$operations = OperationData::getAllProductsBySellId($sell->id);
	?>

	<tr>
		<td style="width:30px;">
			<?php if(isset($_SESSION["user_id"])):?>
		<a href="index.php?view=onesell&id=<?php echo $sell->id; ?>" class="btn btn-sm btn-secondary"><i class="bi-eye"></i></a></td>
	    <?php endif; ?>

  
    <td style="text-align: center"><?php echo $sell->id; ?></td>
    <td style="text-align: center"><?php echo $sell->getP()->name; ?></td>
	<td style="text-align: center"><?php echo $sell->getD()->name; ?></td>
	<td style="text-align: center">
    <?php
    $total= $sell->total;

	 echo "<b>".Core::$symbol." ".number_format($total,2,".",",")."</b>";
     ?>		
	</td>

	<td style="width:300px;text-align: center"> <?php if($sell->person_id!=null){
        $c= $sell->getPerson();echo utf8_encode($c->name);}
        $fp = $c->forma_pago;
        $uc = $c->uso_comprobante;
        $rf = $c->regimen_fiscal;    
        $si_rs = $c->tiene_rs;    
    ?> 
    </td>

	<td style="width:200px;text-align: center"> <?php if($sell->user_id!=null){$c= $sell->getUser();echo $c->name." ".$c->lastname;} ?> </td>
		<td style="text-align: center"><?php echo $sell->getStockTo()->name; 
        $id_almacen =  $sell->stock_to_id;
        ?></td>

      <?php 
      $sesion=$_SESSION["user_id"];
      ?>       
     <input type="hidden" class="form-control" id="total" name = "total" value="<?php $sell->total; ?>"> <!-- Se envia el total de la venta -->
     <input type="hidden" class="form-control" id="f_id" name = "f_id" value="<?php echo $sell->getF()->id; ?>"> <!-- Se envia el id de la forma de pago -->  
     <input type="hidden" class="form-control" id="cliente_id" name = "cliente_id" value="<?php echo $sell->person_id; ?>"> <!-- Se envia el total de la venta -->  
     <input type="hidden" class="form-control" id="p_id" name = "p_id" value="<?php echo $sell->getP()->id;?>"> <!-- Se envia el id de la forma de pago -->  
     <input type="hidden" class="form-control" id="d_id" name = "d_id" value="<?php echo $sell->getD()->id; ?>"> <!-- Se envia el id de la forma de pago -->  
     <input type="hidden" class="form-control" id="stock_id" name = "stock_id" value="<?php echo $id_almacen;?>"> <!-- Se envia el id de la forma de pago -->  
     <input type="hidden" class="form-control" id="usuario" name = "usuario" value="<?php echo $sesion;?>"> <!-- Se envia el id de la forma de pago -->     

            <td style="text-align: center"><?php echo $sell->created_at; ?></td>
            <td style="text-align: center"><?php  
            if($sell->f_id == '1'){
            echo "Efectivo";
            }else if ($sell->f_id == '2') {
            echo "Transferencia";
            }?></td>
        

	</tr>
	<?php endforeach; ?>
	</table>
</div>
</div>



<div class="clearfix"></div>

	<?php
}else{
	?>
	<div class="jumbotron">
	<br>
		<p>No se ha procesado ninguna cotización.</p>
	</div>
	<?php
}

?>

