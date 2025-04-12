<section class="content">

  <h3>Resumen De Traspaso Generado  Folio - <?php echo $_GET["id"] ?></h3>
  
<?php if(isset($_GET["id"]) && $_GET["id"]!=""):?>
<?php
    $sell = SellData::getById($_GET["id"]);
    $operations = OperationData::getAllProductsBySellId($_GET["id"]);
    $total = 0;
?>

<!--
<?php
  if(isset($_COOKIE["selled"])){
  	foreach ($operations as $operation) {
  //		print_r($operation);
      if($operation->operation_type_id==2){
  		$qx = OperationData::getQByStock($operation->product_id,StockData::getPrincipal()->id);
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

  	}
  	setcookie("selled","",time()-18600);
  }

?> -->

<div class="box box-primary">
<table class="table table-bordered">
  <?php if($sell->person_id!=""):
    $client = $sell->getPerson();
  ?>
<?php endif; ?>
<?php if($sell->user_id!=""):
$user = $sell->getUser();
?>
<tr>
	<td>Traspaso Generado por el Usuario :</td>
	<td><?php echo $user->name." ".$user->lastname;?></td>
</tr>
<?php endif; ?>


<tr>
<!--
  <td>Terminal</td>
  <td><?php echo $sell->terminal;?></td>
</tr>
<tr>
  <td>Archivo</td>
  <td><?php if($sell->cam!=""){ echo "<a href='./storage/cams/$sell->cam' class='btn btn-default btn-xs'>Descargar</a>";; }else{ echo "No hay"; }?></td>
</tr>-->
</table>

</div>
<div class="box box-primary">
  <table class="table table-bordered">
    <tr>
      <?php $origen  = StockData::getById($sell->stock_from_id); ?>
      <?php $destino = StockData::getById($sell->stock_to_id); ?>
      <td>Sucursal Origen : <?php echo $origen->name; ?></td>
      <td><?php echo $sell->stock_from_id; ?></td>
      <td>Sucursal Destino : <?php echo $destino->name; ?></td>
      <td><?php echo $sell->stock_to_id; ?></td>
      
    </tr>
  </table>
</div>
<br>
        

        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">

               
                  <a  target="_blank" href="reporte_mov_traspaso.php?id=<?php echo $sell->id ?>" class="btn btn-xs btn-default"><h5> Descargar Comprbante En PDF <i class='fa fa-download'></i></h5></a>
                  
                </div>
                <div class="modal-body">
                      <table class="table table-bordered table-hover" id ="historial_traspaso">
                        <thead>
                          <th style="text-align: center">ID</th>
                          <th style="text-align: center">Codigo</th>
                          <th style="text-align: center">Nombre del Producto</th>
                          <th style="text-align: center">Tipo</th>
                          <th style="text-align: center">cantidad</th>
                          <th style="text-align: center">fecha</th>
                        </thead>
                        <?php
                          $sell = SellData::getById($_GET["id"]);
                          $details = OperationData::getEntrysAndReturnsBysellId($sell->id);
                          foreach($details as $detail){
                        ?>
                        <tr>
                        <td><?php echo $detail->id;    ?></td>
                          <td><?php echo $detail->product_code;    ?></td>
                          <td><?php $product   = $detail->getProduct();
                                    echo $product->name;    
                              ?>
                          </td>
                          <td><?php echo $detail->tipo;    ?></td>
                          <td><?php echo $detail->cantidad;?></td>
                          <td><?php echo $detail->fecha;   ?></td>
                        <?php
                          } 
                        ?>  
                        </tr>
                      </table>

                      <script>
                                    (document).ready(function() {
    $('#historial_traspso').DataTable( {
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal( {
                    header: function ( row ) {
                        var data = row.data();
                        return 'Details for '+data[0]+' '+data[1];
                    }
                } ),
                renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
                    tableClass: 'table'
                } )
            }
        }
    } );
} );
                      </script>

                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                  <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                </div>
            </div>
          </div>
        </div>
        <!-- FIN dEL MODAL -->
    <form method="post" class="form-horizontal" id="processsell" action="index.php?view=procesarTransito">
    <!--<form method="post" class="form-horizontal" id="processsell" action="index.php?action=afectarAlmacenes">-->
      <div class="row">
        <div class="col-md-6">
          <h4>Productos pendientes de Ingreso</h4>
        </div>
        
      
   
        
        <div class="col-md-6">
          <div class="btn-group float-end">
              <button id="afectarAlm" class="btn btn-success" onclick="return entrada();">
            <i class="fa fa-refresh"></i> Generar ingreso al Traspaso </button>
          </div>                
        </div>
        
      </div>

      <script>
function entrada() {
if (confirm("¿Desaeas generar la entrada del traspaso al almacén?")) {
return true;
} else {
return false;

}
}
</script>


<br>
      <div class="box box-primary">
      <table class="table table-bordered table-hover">
      	<thead>
          <th style="text-align: center">Codigo</th>
      		<th style="text-align: center">Descripción del Producto</th>
      		<th style="text-align: center">Precio U</th>
      		<th style="text-align: center">Total</th>
      		<th style="text-align: center">Traspasar</th>
          <th style="text-align: center">Entradas</th>
          <th style="text-align: center">Devolver</th>
          <th style="text-align: center">Pendiente</th>
          <th style="text-align: center">Entrada</th>
          <th style="text-align: center">Devolución</th>

      	</thead>
      <?php
        //$boton = 1;
        //print_r("Tamaño del array: "+count($operations));
      	foreach($operations as $operation){
          $product   = $operation->getProduct();
      ?>
        <div><input type="hidden" name="ventaid[<?php echo $operation->sell_id;?>]"></div><!--RLS-->
      <tr>
        <?php 
            
            if($operation->operation_type_id==2):
              $Se   = SellData::getOrigenDestinoById($operation->sell_id);
              $OpEn = OperationData::getEntrys($operation->sell_id,  $operation->product_id, $Se->stock_to_id);
              $OpRe = OperationData::getReturns($operation->sell_id, $operation->product_id, $Se->stock_from_id);
        ?>
          <td style="text-align: center"><?php echo $product->code;   ?></td>
          <td style="text-align: center"><?php echo $product->name;   ?></td>
          <td style="text-align: center"><?php echo Core::$symbol;    ?><?php echo number_format($operation->price_in,2,".",",") ;?></td>
          <td style="text-align: center"><b><?php echo Core::$symbol; ?><?php echo number_format($OpEn->entrada*$operation->price_in,2,".",",");$total+=$OpEn->entrada*$operation->price_in;?></b></td>
          <!--<td><b>-->
          <?php //echo Core::$symbol; ?>
          <?php //echo number_format($operation->q*$operation->price_in,2,".",",");$total+=$operation->q*$operation->price_in;?>
          <!--</b></td>-->
          <td style="text-align: center"><?php echo $operation->q     ?></td><!-- Traspasos -->
          <td style="text-align: center"><?php echo $OpEn->entrada;   ?></td><!-- Entrada -->
          <td style="text-align: center"><?php echo $OpRe->devolucion;?></td><!-- Devolucion -->
          <td style="text-align: center"><?php echo $operation->q - ( $OpEn->entrada + $OpRe->devolucion );?></td><!-- Pendiente -->
          <td style="text-align: center"><input type="number" name="entrada[<?php echo $product->id;?>]" min="1"></td>
          <td style="text-align: center"><input type="number" name="devolucion[<?php echo $product->id;?>]" min="1"></td>
            <!--<td><button class="btn btn-success"><i class="fa fa-refresh"></i> Afectar Almacenes</button></td>-->
            <script>
              $(function() {
                  var pendiente  = 0;
                  var restante   = 0;
                  var entrada    = 0;
                  var devolucion = 0;
                  
	                $("input[name='entrada[<?php echo $product->id;?>]']").change(function(){
                    restante   = 0;
                    pendiente  = <?php echo $operation->q - ( $OpEn->entrada + $OpRe->devolucion );?>;
                    entrada    = $("input[name='entrada[<?php echo $product->id;?>]']").val();
                    if(entrada == ""){entrada = 0;}
                    restante   = pendiente - (parseInt(entrada) + parseInt(devolucion));
                    console.log("--------------------------------");
                    console.log("Cantidad Pendiente: " + pendiente);
                    console.log("Cantidad Entrada: " + entrada);
                    console.log("Cantidad Devolución: " + devolucion);
                    console.log("Cantidad Restante: " + restante);

                    if(restante < 0){
                      
                      alert("La suma del campo entrada más el campo devolución no puede ser mayor a la cantidad pendiente");
                      $("input[name='entrada[<?php echo $product->id;?>]']").focus();
                      document.getElementById('afectarAlm').disabled=true;
                      //boton.disabled = true;
                    }else{
                      document.getElementById('afectarAlm').disabled=false;
                    }
	                });

                  $("input[name='devolucion[<?php echo $product->id;?>]']").change(function(){
                    restante   = 0;
                    pendiente  = <?php echo $operation->q - ( $OpEn->entrada + $OpRe->devolucion );?>;
                    devolucion = $("input[name='devolucion[<?php echo $product->id;?>]']").val();
                    if(devolucion == ""){devolucion = 0;}
                    restante   = pendiente - (parseInt(entrada) + parseInt(devolucion));
                    console.log("--------------------------------");
                    console.log("Cantidad Pendiente: " + pendiente);
                    console.log("Cantidad Entrada: " + entrada);
                    console.log("Cantidad Devolución: " + devolucion);
                    console.log("Cantidad Restante: " + restante);
                    if(restante < 0){
                      alert("La suma del campo entrada más el campo devolución no puede ser mayor a la cantidad pendiente");
                      $("input[name='devolucion[<?php echo $product->id;?>]']").focus();
                      document.getElementById('afectarAlm').disabled=true;
                    }else{
                      document.getElementById('afectarAlm').disabled=false;
                    }
	                });
	                
              });
            </script>
        <?php endif; ?>
      </tr>
      <?php
        }
      	?>
      </table>
    </form>
</div>




<div class="row">
<div class="col-md-4">
<!--
<div class="box box-primary">


<table class="table table-bordered">

	<tr>
		<td><h4>Descuento:</h4></td>
		<td><h4><?php echo Core::$symbol; ?> <?php echo number_format($sell->discount,2,'.',','); ?></h4></td>
	</tr>
	<tr>
		<td><h4>Subtotal:</h4></td>
		<td><h4><?php echo Core::$symbol; ?> <?php echo number_format($total,2,'.',','); ?></h4></td>
	</tr>
  -->
  <!--
  <tr>
		<td><h4>Subtotal:</h4></td>
		<td><h4><?php echo Core::$symbol; ?> <?php echo number_format($total,2,'.',','); ?></h4></td>
	</tr>
	<tr>
		<td><h4>Descuento:</h4></td>
    <td><h4><?php echo Core::$symbol; ?> <?php echo number_format($sell->discount,2,'.',','); ?></h4></td>
	</tr>
	<tr>
		<td><h4>Total:</h4></td>
		<td><h4><?php echo Core::$symbol; ?> <?php echo number_format($total-	$sell->discount,2,'.',','); ?></h4></td>
	</tr>  
</table>
</div> 
-->
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