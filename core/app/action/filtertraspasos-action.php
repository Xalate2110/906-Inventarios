<script>
    function cancelacion(id,total,f_id,cliente_id,p_id,d_id,id_anticipo,id_almacen) {

        $("#clave").modal('show');
        $("#id_cancelacion").val(id);
        $("#total").val(total);
        $("#f_id").val(f_id);
        $("#id_anticipo").val(id_anticipo);
        $("#d_id").val(d_id);
        $("#cliente_id").val(cliente_id);
        $("#p_id").val(p_id);
        $("#d_id").val(d_id);
        $("#stock_id").val(id_almacen);
        }

      function cancela_ticket3() {
        var data = "pass=" + $("#pass").val() + "&motivo=" + $("#motivo").val() + "&idcancela=" + $("#id_cancelacion").val()+ 
        "&total=" + $("#total").val() +  "&f_id=" + $("#f_id").val() + "&cliente_id=" + $("#cliente_id").val() + "&p_id=" + $("#p_id").val() + "&d_id=" + $("#d_id").val() + "&id_anticipo=" + $("#id_anticipo").val() + "&stock_id=" + $("#stock_id").val() + "&usuario=" + $("#usuario").val() ; 
       
	    if ($("#pass").val() != "" || $("#motivo").val() != "")
        {
            $.ajax({
                type: "POST",
                url: "./core/app/action/comprueba_clave3.php",
                data: data,
                success: function (data) {
                    console.log(data);
                    if (data == "1") {
                        $("#clave").modal('hide');
                        alert("Traspaso cancelado correctamente con el ID : " + $("#id_cancelacion").val());
                        window.location.reload();
                    } else
                    if (data == "0")
                    {
                        $(".error").html("<span style='color:red'>Error Acceso Denegado, Revise sus permisos para cancelar o su clave asingnada</span>");
                    } else
                    {
                        alert("Error en Base de datos");
                    }
                }
            });
        } else {
            $(".error").html("<span style='color:red'>Error los campos * son obligatorios</span>");
        }


    }
</script>
<section class="content"> 
	<div class="row">
		<div class="col-md-12">
			<?php if(isset($_SESSION["user_id"])):?>
		
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
     $sesion=$_SESSION["user_id"];

     if(Core::$user->kind==3){
        //$products = SellData::getAllBySQL(" where user_id=Core::$user->id and operation_type_id=6 and is_draft=0 order by created_at desc");
        $products = SellData::getAllBySQL2(" where created_at between '$_GET[start_at] 00:00:00' and '$_GET[finish_at] 23:59:00' and user_id= ".Core::$user->id." and operation_type_id = 7 and is_draft=0 order by id DESC");

        
        }
        else if(Core::$user->kind==10){
        //$products = SellData::getAllBySQL(" where operation_type_id=6 and is_draft=0 and stock_to_id=Core::$user->stock_id order by created_at desc");
        $products = SellData::getAllBySQL2(" where created_at between '$_GET[start_at] 00:00:00' and '$_GET[finish_at] 23:59:59' and operation_type_id = 7 and is_draft=0 and stock_to_id= ".Core::$user->stock_id." and p_id = 1 and d_id = 1 order by id DESC");

        }
        else{
        //$products = SellData::getAllBySQL(" where operation_type_id=6");
        $products = SellData::getAllBySQL2(" where created_at between '$_GET[start_at] 00:00:00' and '$_GET[finish_at] 23:59:59' and operation_type_id = 7 and p_id = 1 and d_id=1 ORDER BY id DESC ");

        }
        }else if(isset($_SESSION["client_id"])){
        //$products = SellData::getAllBySQL(" where person_id=$_SESSION[client_id] and operation_type_id=6 and is_draft=0 order by created_at desc");
        $products = SellData::getAllBySQL2(" where person_id=$_SESSION[client_id] and operation_type_id in (6,7) and is_draft=0 order by id DESC");	
        }

			if(count($products) > 0){
?>
<br>

    <div class="card box-primary">
<div class="card-header">
Listado Traspasos Generados
</div>
<div class="box box-primary table-responsive">
<table class="table table-bordered table-hover table-responsive datatable">
			<thead>
				<th style="text-align: center;">Detalles</th>
				<th style="text-align: center;">Folio</th>	
     			<th style="text-align: center;">Usuario</th>
				<th style="text-align: center;">Suc Origen </th>
				<th style="text-align: center;">Suc Destino </th>
				<th style="text-align: center;">Fecha</th>
				<th style="text-align: center;">Estatus</th>
				<th style="text-align: center;">Comprobante</th>
				<th style="text-align: center;">Acciones</th>

				
			</thead>
			<?php foreach($products as $sell):
				$operations = OperationData::getAllProductsBySellId($sell->id);
			
			?>

			<tr style="text-align: center;">
				<td style="width:30px;">
				<a href="index.php?view=onetraspase&id=<?php echo $sell->id; ?>" class="btn btn-sm btn-secondary"><i class="bi-eye"></i></a></td>
			    <i class="glyphicon glyphicon-circle-arrow-right"></i></a>
				</td>
				<td><?php echo $sell->id; ?></td>
			<!--
				<td>
			
		<?php
				//echo $sell->stock_from_id;
				//echo $sell->stock_to_id;
				//echo $sell->id;
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
			<td> <?php if($sell->user_id!=null){$c= $sell->getUser();echo $c->name." ".$c->lastname;} ?> </td>
			
				<td><?php echo $sell->getStockFrom()->name; ?></td>
				<td><?php echo $sell->getStockTo()->name; ?></td>
				<td><?php echo $sell->created_at; ?></td>
				<?php
				$total = OperationData::getAllEntrysAndReturnsBysellId($sell->id)->total;
				$traps = OperationData::getAllTraspaseBysellId($sell->id)->traspaso;
				if($traps != $total){?>
                <!--<td class="btn btn-warning"><?php /*echo OperationTypeData::getById($sell->operation_type_id)->name;*/ ?></td>-->
				<td class="bg-warning" >En Espera De Ingreso</td>
				<?php }else{ ?>
			    <td class="bg-success">Traspaso Ingresado</td>
				<?php } ?>	

				<td>
				<a  target="_blank" href="reporte_traspaso.php?id=<?php echo $sell->id ?>&sucursal=<?php  echo $sell->getStockFrom()->id;?>" class="btn btn-sm btn-secondary"><i class='bi-ticket'></i></a>
				</td>
				<td style="width:130px;">
				
			    <?php if(isset($_SESSION["user_id"])):?>
		
			
			  <?php
			  if (Core::$user->kind == 1) {?>
			<a  class="btn btn-xs btn-danger" onclick="cancelacion(<?php echo $sell->id;?>);">X<i class="fa fa-ban"></i></a>				
			<?php }?>
			<?php endif;?>
			</td>
			</tr>


		
		<?php endforeach; ?>
		
		</table>
		</div>
		</div>
		<script>
$(document).ready(function() {
    $('#traspasos').DataTable();
} );
</script>
<div class="clearfix"></div>

	<?php
}else{
	?>
	<div class="jumbotron">
		<h2>No hay Traspasos</h2>
		<p>No se ha realizado ningun traspaso.</p>
	</div>
	<?php
}

?>
	</div>
</div>
</section>




<div class="modal fade" id="clave" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clave">Ingresa los datos para cancelar el traspaso</h5>
				<button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
               </button>
            </div>

            <div class="modal-body">
                <form>
       
                    <input type="hidden" class="form-control" id="id_cancelacion"> 
					<input type="hidden" class="form-control" id="usuario" name = "usuario" value="<?php echo $sesion;?>"> <!-- Se envia el id de la forma de pago -->  
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Contraseña:</label>
                        <input type="password" class="form-control" id="pass">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Motivo de Cancelacion:</label>
                        <textarea class="form-control" id="motivo"></textarea>
                    </div>

				    <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-coreui-dismiss="modal">Cerrar Ventana</button>
                    <button type="button" class="btn btn-primary" onclick="cancela_ticket3();">Cancelar Traspaso</button>
                    </div>
                </form>
            </div>
            <div> <label class="error"></label></div>
       
        </div>
    </div>
</div>



