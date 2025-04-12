<?php $products = SellData::getCreditsByClientId($_SESSION['selected_client_id']); ?>
<?php if(count($products)>0):?>
	<div class="box box-primary table-responsive">
    <a href="./?view=makepayment&id=<?php echo $_SESSION['selected_client_id']; ?>" class="btn btn-success">Aplicar Pagos a Créditos</a>
	<br><br>
	<table class="table table-bordered table-hover table-responsive datatable">
	<thead>
		<th>Detalles</th>
		<th>Ticket</th>
		<th>Nombre o Razón Comercial</th>
		<th>Pago</th>
        <th>Pendiente</th>
		<th>Total</th>
		<th>Fecha</th>
	</thead>
	<?php foreach($products as $sell):?>

	<tr>
		<td style="width:30px;">
			<?php if(isset($_SESSION["user_id"])):?>
		<a href="index.php?view=onesell&id=<?php echo $sell->id; ?>" class="btn btn-sm btn-outline-secondary" data-coreui-toggle="tooltip" data-coreui-placement="top" title="Ver Mas"><i class="bi-eye"></i></a></td>
	<?php endif; ?>
		<td>#<?php echo $sell->id; ?></td>

		<td>


<?php

$c= $sell->getPerson();
echo utf8_encode($c->name);
?>
</td>
<td><?php echo $sell->getP()->name; ?></td>
<td>
  <?php
  $creditsum= 0;
  $tx = PaymentData::sumBySellId($sell->id)->total;

if($tx>=0){
//$credit_array[] = array("sell_id"=>$sell->id,"total"=>$tx);
  $creditsum=$tx;
}
echo "<b> $";
echo number_format($creditsum,2,".",",");
echo "</b>";
?>
</td>
		<td>

<?php
$total= $sell->total-$sell->discount;
	/*foreach($operations as $operation){
		$product  = $operation->getProduct();
		$total += $operation->q*$product->price_out;
	}*/
		echo "<b>".Core::$symbol." ".number_format($total,2,".",",")."</b>";

?>			

		</td>
		<td><?php echo $sell->created_at; ?></td>
	
	</tr>

<?php endforeach; ?>

</table>
</div>
<?php else:?>
	<p class="alert alert-info">El cliente no tiene cuentas pendientes.</p>
<?php endif;?>