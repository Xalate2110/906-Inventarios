<?php 
  $sesion=$_SESSION["user_id"];
?>  

<?php
$products = null;
if(Core::$user->kind==2||  Core::$user->kind==4){
$products = SellData::getResByStockId(Core::$user->stock_id);
}else{

//$sql = "select * from sell ";
$whereparams = array();
$whereparams[] = " (operation_type_id=10 and p_id=0 and d_id=0) ";
if(isset($_GET["stock_id"]) && $_GET["stock_id"]!=""){
  $whereparams[] = " stock_to_id=$_GET[stock_id] ";
}

if(isset($_GET["start_at"]) && isset($_GET["finish_at"]) && $_GET["start_at"]!="" && $_GET["finish_at"]!=""){
  $whereparams[] = " ( date(created_at) between '$_GET[start_at]' and '$_GET[finish_at]' ) ";
}

 $sql2 = " where ".implode(" and ", $whereparams)." order by created_at desc";
 $products = SellData::getAllBySQL2($sql2);


}
if(count($products)>0){
	?>
<br>
<div class="card box-primary">
<div class="card-header">
Listado Entradas Mercancía
</div>
<div class="box box-primary table-responsive">
<table class="table table-bordered table-hover table-responsive datatable">
	<thead>
		<th style="text-align: center;">Id Entrada</th>
		<th style="text-align: center;">Almacén De Ingreso</th>
		<th style="text-align: center;">Fecha de Operación</th>
		<th style="text-align: center;">Eliminar</th>
    <th style="text-align: center;">Comprobante</th>
	</thead>
	<?php foreach($products as $sell):
	$operations = OperationData::getAllProductsBySellId($sell->id);
  ?>

	<tr>
<td style="text-align: center;"> Folio - <?php echo $sell->id; ?></td>

<!--<td>
<?php
$total=0;
	foreach($operations as $operation){
		$product  = $operation->getProduct();
		$total += $operation->q*$product->price_in;
	}
		echo "<b>".Core::$symbol." ".number_format($total,2,".",",")."</b>";
  ?>	-->		

    </td>
  <td style="text-align: center;"><?php if($sell->stock_to_id!=null){echo
  $sell->getStockTo()->name ;} ?></td>     
  <td style="text-align: center;"><?php echo $sell->created_at;
  ?>
  </td>     
  <td style="text-align: center;">    
  <a href="index.php?action=cancelis&id=<?php echo $sell->id;?>&usuario=<?php echo $sesion;?>" class="btn btn-sm btn-danger" onclick="return cancelar_compra();"><i  class="bi-trash"></i></a>
  </td>   
  
  <td style="text-align: center;">   
  <a target="_blank" href="ajuste_inventario_sobrantes.php?id=<?php echo $sell->id ?>&sucursal=<?php echo $sell->stock_to_id ?>" class="btn btn-sm btn-secondary"><i class='bi-ticket'></i></a>
  </td>   
  </tr>
  <script>
  function cancelar_compra() {
  if (confirm("¿Seguro que deseas eliminar el Ajuste?, al realizarlo se recalculara el Iventario de los productos ajustados")) {
  return true;
  } else {
  return false;}
}
</script>


<?php endforeach; ?>

</table>
</div>
</div>

	<?php
}else{
	?>
	<div class="jumbotron">
  <h2>No hay datos</h2>
		<p>No se ha realizado ninguna operacion.</p>
	</div>
	<?php
}

?>

