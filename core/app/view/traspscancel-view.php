<section class="content"> 
	<div class="row">
		<div class="col-md-12">
			<?php if(isset($_SESSION["user_id"])):?>
				<h1><i class='glyphicon glyphicon-shopping-cart'></i> Listado General Traspasos Cancelados</h1>
			<?php else:?>
		<div class="btn-group pull-right">
	  		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
	  		  <i class="fa fa-download"></i> Descargar <span class="caret"></span>
	  		</button>
	  		<ul class="dropdown-menu" role="menu">
				<li><a onclick="thePDF()" id="makepdf" class="">PDF (.pdf)</a></li>
	  		</ul>
		</div>
		
			<?php endif;?>
		<div class="clearfix"></div>

<?php
	$products = null;
	

	if(isset($_SESSION["user_id"])){


		if(Core::$user->kind==3 || Core::$user->kind==1){
			//$products = SellData::getAllBySQL(" where user_id=Core::$user->id and operation_type_id=6 and is_draft=0 order by created_at desc");
			$products = SellData::getAllBySQL2(" where user_id= ".Core::$user->id." and operation_type_id = 7 and is_draft=0 and p_id = 3 and d_id=3 order by id DESC");
	
			
			}
			else if(Core::$user->kind==2){
			//$products = SellData::getAllBySQL(" where operation_type_id=6 and is_draft=0 and stock_to_id=Core::$user->stock_id order by created_at desc");
			$products = SellData::getAllBySQL2(" where operation_type_id = 7 and is_draft=0 and stock_to_id= ".Core::$user->stock_id." and p_id = 3 and d_id = 3 order by id DESC");
	
			}
			else{
			//$products = SellData::getAllBySQL(" where operation_type_id=6");
			$products = SellData::getAllBySQL2(" where operation_type_id = 7 and p_id = 3 and d_id=3 ");
	
			}
			}else if(isset($_SESSION["client_id"])){
			//$products = SellData::getAllBySQL(" where person_id=$_SESSION[client_id] and operation_type_id=6 and is_draft=0 order by created_at desc");
			$products = SellData::getAllBySQL2(" where person_id=$_SESSION[client_id] and operation_type_id in (6,7) and is_draft=0 order by id DESC");	
			}

			if(count($products)>0){
?>
<br>
<div class="card box-primary">
<div class="card-header">
<span class="box-title">Listado Compras Canceladas</span></div>
<div class="card-body">
<table class="table table-bordered table-hover table-responsive datatable	">
			<thead>
	
				<th style="text-align: center;">Folio</th>	
     			<th style="text-align: center;">Usuario</th>
				<th style="text-align: center;">Suc Origen </th>
				<th style="text-align: center;">Suc Destino </th>
				<th style="text-align: center;">Fecha Traspaso</th>
				<th style="text-align: center;">Estatus</th>
				<th style="text-align: center;">Fecha Cancelacion</th>
				<th style="text-align: center;">Usuario Cancelo</th>
				<th style="text-align: center;">Motivo</th>
				<th style="text-align: center;">Acciones</th>

				
			</thead>
			<?php foreach($products as $sell):
				$operations = OperationData::getAllProductsBySellId($sell->id);
			?>

			<tr style="text-align: center">
			<td  style="text-align: center"><?php echo $sell->id; ?></td>
			<!--
				<td>
			
		<?php
			
				$productos = OperationData::getDistinctProductsBySellId($sell->id);
				$total = 0;
				
				foreach($productos as $producto){
					$OpEn = OperationData::getEntrys($sell->id,  $producto->product_id, $sell->stock_to_id);
					$total += $OpEn->entrada * $producto->price_in;			
				}
				//$OpEn = OperationData::getEntrys($sell->id,  $products->product_id, $sell->stock_to_id);
				//$total= $OpEn->entrada;
				//$total= 1+$sell->total-$sell->discount;
				echo "<b>".Core::$symbol." ".number_format($total,2,".",",")."</b>";
			
		?>			
				</td> -->

			<td  style="text-align: center"> <?php if($sell->user_id!=null){$c= $sell->getUser();echo $c->name." ".$c->lastname;} ?> </td>
				<td><?php echo $sell->getStockFrom()->name; ?></td>
				<td><?php echo $sell->getStockTo()->name; ?></td>
				<td><?php echo $sell->created_at; ?></td>

			
				<?php
					 $total = OperationData::getAllEntrysAndReturnsBysellId($sell->id)->total;
					 $traps = OperationData::getAllTraspaseBysellId($sell->id)->traspaso;
					
					 if($sell->p_id == '3' || $sell->d_id == '3'){

					
				?>
			
	     	<!--<td class="btn btn-success"><?php /*echo OperationTypeData::getById($sell->operation_type_id)->name;*/ ?></td>-->	
				<td class="bg-danger">Traspaso Cancelado</td>
					<?php } ?>	

					<td><?php echo $sell->cancelacion; ?></td>

					<?php
					include '/connection/conexion.php';
					$sql = "SELECT name from user where id = '".$sell->usuario_cancelo."'";
					$resultado = $mysqli->query($sql);
					while($row=mysqli_fetch_array($resultado)){
					$usuario = $row[0];}
				?>
					<td><?php if($sell->usuario_cancelo == 0){
					echo "No hay usuario registrado";
					}else {
					echo $usuario;
					} 
					?>

					<td  style="text-align: center">
					<?php if(isset($_SESSION["user_id"])):?>
						<a id="<?php echo $sell->id; ?>"  onclick="detalle(this.id)"><i  class="btn btn-sm btn-warning">Motivo</i></a>
					<?php endif;?>
					</td>

				<td  style="text-align: center">
				<!--<a  target="_blank" href="ticket-tr.php?id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default"><i class='fa fa-ticket'></i> Ticket</a>-->
				<a  target="_blank" href="traspaso_cancelado.php?id=<?php echo $sell->id ?>&sucursal=<?php  echo $sell->getStockFrom()->id;?>"  class="btn btn-sm btn-secondary"><i class='bi-ticket'></i></a>
			
		<?php if(isset($_SESSION["user_id"])):?>
		<!--
				<a href="index.php?action=cancelsell&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-danger">Cancelar</a> 
				-->
			
			  <?php
			  if (Core::$user->kind == 1) {?>
				
				<a href="index.php?view=deltrasp&id=<?php echo $sell->id; ?>"  class="btn btn-sm btn-danger"  onclick="return cancelar();"><i  class="bi-trash"></i></a>
				
				<?php }?>
				<?php endif;?>
				</td>
			</tr>

			<script>
function cancelar() {
if (confirm("¿Deseas Cancelar El Traspaso?, Al realizarlo los productos adjuntos en el Traspaso regresaran al Almacen De Origen")) {
return true;
} else {
return false;

}
}
</script>
		
		<?php endforeach; ?>
		
		</table>
		</div>
		</div>

<div class="clearfix"></div>

	<?php
}else{
	?>
	<div class="jumbotron">
		<h2>No hay Traspasos</h2>
		<p>No se ha realizado ninguna devolucion.</p>
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
                <h5 class="modal-title" id="clave">Información Cancelación Traspaso</h5>
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
