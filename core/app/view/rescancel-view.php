<section class="content"> 
<div class="row">
	<div class="col-md-12">

<?php if(isset($_SESSION["client_id"])):?>
		<h1><i class='glyphicon glyphicon-shopping-cart'></i> Mis Compras</h1>
<?php else:?>
		<h1><i class='glyphicon glyphicon-shopping-cart'></i> Litado Compras Canceladas</h1>

<div class="btn-group">

  <ul class="dropdown-menu" role="menu">
<li><a class="dropdown-item" onclick="thePDF()" id="makepdf" class="">PDF (.pdf)</a></li>
  </ul>
</div>
<?php endif;?>


		<div class="clearfix"></div>


<?php
$products = null;
if(isset($_SESSION["user_id"])){
if(Core::$user->kind==3){
$products = SellData::getAllBySQL(" where user_id=Core::$user->id and operation_type_id=1 and p_id=3 and d_id=3 and is_draft=0 order by created_at desc");

}
else if(Core::$user->kind==3 || Core::$user->kind==4){
$products = SellData::getAllBySQL(" where operation_type_id=1 and p_id=3 and d_id=3 and is_draft=0 and stock_to_id=".Core::$user->stock_id." order by created_at desc");
}
else{
$products = SellData::getAllBySQL(" where operation_type_id=1 and p_id=3 and d_id=3");

}
}else if(isset($_SESSION["client_id"])){
$products = SellData::getAllBySQL(" where person_id=$_SESSION[client_id] and operation_type_id=1 and p_id=3 and d_id=3 and is_draft=0 order by created_at desc");	
}

if(count($products)>0){
?>


<?php 
    $total_pe =0;
    $total_an=0;
    $total_ant=0;
    foreach($products as $sell):?>
    <?php 
    $total= $sell->total;  
    $total_pe += $total;
    ?>
    <?php endforeach; ?>


<br>
<div class="card box-primary">
<div class="card-header">
<tr>
<td> <h6  style="text-align: right;font-size:16px;color:blue">Total Compras Canceladas $ : <?php echo number_format($total_pe,2, '.', ',') ?></h6> </td>
</tr></div></div>


<br>
<div class="card box-primary">
<div class="card-header">
<span class="box-title">Listado Compras Canceladas</span></div>
<div class="card-body">
<table class="table table-bordered table-hover table-responsive datatable	">
	<thead>
		<th></th>
		<th>Folio</th>	
		<th>Pago</th>
		<th>Entrega</th>
		<th>Total</th>
		<th>Cliente</th>
		<th>Vendedor</th>
		<th>Almacen</th>
		<th>Fecha Compra</th>
		<th>Fecha Cancelación</th>
		<th>Motivo</th>
		<th>Acciones</th>
	</thead>
	<?php foreach($products as $sell):
	$operations = OperationData::getAllProductsBySellId($sell->id);
	?>

	<tr>
		<td style="width:30px;">
		<a href="index.php?view=onere&id=<?php echo $sell->id; ?>" class="btn btn-sm btn-outline-secondary"><i class="bi-eye"></i></a></td>
		<td>#<?php echo $sell->id; ?></td>

<td><?php echo $sell->getP()->name; ?></td>
<td><?php echo $sell->getD()->name; ?></td>
		<td>

<?php
$total= $sell->total-$sell->discount;
		echo "<b>".Core::$symbol." ".number_format($total,2,".",",")."</b>";

?>			
		</td>
	<td> <?php if($sell->person_id!=null){$c= $sell->getPerson();echo $c->name." ".$c->lastname;} ?> </td>
	<td> <?php if($sell->user_id!=null){$c= $sell->getUser();echo $c->name." ".$c->lastname;} ?> </td>
		<td><?php echo $sell->getStockTo()->name; ?></td>
		<td><?php echo $sell->created_at; ?></td>
		<td><?php echo $sell->cancelacion; ?></td>
		<td style="width:130px;">
<?php if(isset($_SESSION["user_id"])):?>
	<a id="<?php echo $sell->id; ?>"  onclick="detalle(this.id)"><i  class="btn btn-sm btn-danger">Motivo Cancelacion</i></a>
		
<?php endif;?>
		</td>
		<td style="width:130px;">
<?php if(isset($_SESSION["user_id"])):?>
		<a href="index.php?action=uncancelre&id=<?php echo $sell->id; ?>" class="btn btn-sm btn-info">Restaurar</a>
		<a href="index.php?view=delre&id=<?php echo $sell->id; ?>" class="btn btn-sm btn-danger"><i class="bi-trash"></i></a>
<?php endif;?>
		</td>
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
		<h2>No hay compras</h2>
		<p>No se ha realizado ninguna compra.</p>
	</div>
	<?php
}

?>
	</div>
</div>
</section>



<script type="text/javascript">
    function detalle(id) {
        $.ajax({
            type: "POST",
            url: "./core/app/action/traer_mot.php",
            data: "id=" + id,
            success: function (data) {
                $("#detalle_can").modal('show');
                $("#motivo_c").html(data);
            }
        });
    }

    </script>


<div class="modal fade" id="detalle_can" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog" role="document">
      	<div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clave">Información Cancelación Venta</h5>
				<button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
               </button>
            </div>
			<div class="modal-body">
                <textarea id="motivo_c" readonly="true" rows="6" cols="50"></textarea>
			</div>
            <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-coreui-dismiss="modal">Cerrar Ventana</button>
            </div>
            </div>
        </div>
    </div>
</div>
