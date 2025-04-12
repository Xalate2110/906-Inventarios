
<?php
 $company_name = ConfigurationData::getByPreffix("company_name")->val;
 $symbol = ConfigurationData::getByPreffix("currency")->val;
 $iva_val = ConfigurationData::getByPreffix("imp-val")->val;
 $sell = SellData::getById($_GET["id"]);
 $stock = StockData::getById($sell->stock_to_id);
 $details = SellData::getDeliverById($sell->id);
 $client = $sell->getPerson();
 $sucursal = StockData::getPrincipal()->id;
 $sesion=$_SESSION["user_id"];
?>

<section class="content">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>




<h1>Reporte De Venta (FOLIO) =  <?php echo $sell->id;?></h1>
<div class="btn-group ">
		<a  target="_blank" href="ticket.php?id=<?php echo $sell->id; ?>" class="btn btn-sm btn-warning">Descargar Ticket Venta - <i class='bi-ticket'></i></a>
    </div>



<br><br>
<?php if(isset($_GET["id"]) && $_GET["id"]!=""):?>

<?php
  $operations = OperationData::getAllProductsBySellId($_GET["id"]);
  $total = 0;
?><?php

?>
<div class="row">
<div class="col-md-12">
<div class="box box-primary">
<table class="table table-bordered">
<?php if($sell->person_id!=""):
$client = $sell->getPerson();
$sucursal = StockData::getPrincipal()->id;
?>
<tr>
  <td style="text-align: LEFT;font-size:15px;">Nombre Cliente</td>
  <td><?php echo utf8_encode($client->name." ".$client->lastname);?></td>
</tr>

<?php endif; ?>
<?php if($sell->user_id!=""):
$user = $sell->getUser();
?>
<tr>
  <td style="text-align: LEFT;font-size:15px;">Atendido Por</td>
  <td><?php echo $user->name." ".$user->lastname;?></td>
</tr>
<?php endif; ?>

<tr>
  <td style="text-align: LEFT;font-size:15px;">Almacen Salida</td>
  <td><?php 
    echo StockData::getPrincipal()->name;
    ?></td>
</tr>

<tr>
  <td style="text-align: LEFT;font-size:15px;">Folio Factura</td>
  <td> <?php
 echo $sell->invoice_code;?>
  </td>
</tr>

<tr>
  <td style="text-align: LEFT;font-size:15px;">Fecha Remisión</td>
  <td> <?php
 echo $sell->created_at;?>
  </td>
</tr>


</table>
</div>
<?php   
$sesion=$_SESSION["user_id"];

?>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalCenterTitle">Historial Movimientos Venta ID -  ( <?php echo $sell->id ?> )</h3>  
                 <!-- <button onclick="thePDFhistory()" type="button" class="btn btn-default">
                        <i class="fa fa-download"></i> Descargar Reporte PDF
                  </button><br><br> -->
                </div>
                <div class="modal-body">
                      <table class="table table-bordered table-hover" id ="historial_entregas" >
                        <thead>
                      
                          <th style="text-align: center;">Venta</th>
                          <th style="text-align: center;">Descripción</th>
                          <th style="text-align: center;">Entregado</th>
                          <th style="text-align: center;">Precio</th>
                          <th style="text-align: center;">Total</th>
                          <th style="text-align: center;">Operacion</th>
                          <th style="text-align: center;">Fecha</th>
                          <th style="text-align: center;">Eliminar</th>
                        </thead>
                        
                        
                        <?php
                        foreach($details as $detail){
                        ?>              
                        <tr>
                     
                        <td><?php echo $detail->sell_id;    ?></td>
                
                        <td><?php $product   = $detail->getProduct();
                        echo $detail->descripcion;    
                        ?>
                          </td>
                          <td style="text-align: center;"><?php echo $detail->entregada;?></td>
                          <td style="text-align: center;"><?php echo $detail->precio_out;?></td>
                          <td style="text-align: center;"><?php echo $detail->total_entregado;?></td>
                      
                          <td style="text-align: center;">
				                  <?php 
				                  if($detail->operacion=='1'){
				                  echo '<span style="color:green">'. "Salida Parcial";}
			                  	else if($detail->operacion=='2')
				                   { 
                          echo '<span style="color:blue">'."Entrada Producto"; }
                          else if($detail->operacion=='3')
                          { 
                         echo '<span style="color:red">'."Salida Producto"; }?>
				                </td>
                          <td style="text-align: center;"><?php echo $detail->fechaEntregada;?></td>

                         <!-- <td><a target="_blank" href="comprobante.php?&id=<?php echo $detail->sell_id;?>&cliente=<?php echo $client->id;?>&producto_id=<?php echo $product->id?>&sucursal=<?php echo $sucursal?>" class="btn btn-xs btn-success" ><i class="fa fa-file-pdf-o"></i></a></td> -->
                   
                          <?php if (Core::$user->kind == 1): ?>
                          <td style="text-align: center;"> <a href="index.php?view=delparcial&id=<?php echo $detail->id; ?>&sell_id=<?php echo $detail->sell_id; ?>&id_trans=<?php echo $detail->id_trans?>" class="btn btn-xs btn-danger" onclick="return cancelar_historial();"><i class="bi-trash"></i></a>
                          <?php endif; ?>
                          <?php
                          } 
                          ?>  
                         </tr>
                        </table>

                        <script>
                                    (document).ready(function() {
                                   $('#historial_entregas').DataTable( {
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

                       <script>
                        function cancelar_historial() {
  if (confirm("¿Seguro que deseas eliminar el registro del Historial?, al realizarlo se recalculara el total del producto y anticipo del cliente.")) {
return true;
} else {
return false;

}
}
</script>
 </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-coreui-dismiss="modal">Cerrar Ventana</button>
                </div>
            </div>
          </div>
        </div>





        
        <!-- FIN dEL MODAL -->
        <form method="post" class="form-horizontal" id="processsell" action="index.php?view=procesarEntrega">

       <div class="row">
          <div class="col-md-6">
            <h3>Listado de productos pendientes de Entrega</h3>
          </div>

          <div class="col-md-6">
            <div class="btn-group float-end">
            <button id="entregarMercancia"  class="btn btn-success" onclick="return salida();"> <i class="fa fa-refresh"></i> Generar Salida </button>
                
            <button type="button" class="btn btn-warning" data-toggle="modal" id="miBoton" data-target="#exampleModalCenter" >

            <i class="fa fa-history" ></i> Historial De Entregas</button>
            <script>
            $(document).ready(function() {
            $("#miBoton").click(function() {
            $("#exampleModalCenter").modal('show');});
            });

            </script>


            </div>
          </div>
   </div>
<br>




    <script>
function salida() {
if (confirm("¿Seguro que deseas dar salida parcial al Producto?, Al realizarlo se ajustara el total pendiente del producto y el Anticipo Del Cliente")) {
return true;
} else {
return false;
}
}
</script>
    <div class="box box-primary">
    <table class="table table-bordered table-hover">
      <thead>
        <th style="text-align: center;">Codigo</th>
        <th style="text-align: center;">Cantidad</th>
        <th style="text-align: center;">Descripción Producto</th>
        <th style="text-align: center;">Precio</th>
        <th style="text-align: center;">Total</th>
        <th style="text-align: center;">Entregado</th>
        <th style="text-align: center;">Pendiente</th>
        <th style="text-align: center;">Cantidad A Entregar </th>
        <th style="text-align: center;">Total Entregado</th>
        <th style="text-align: center;">Disponible Por Producto</th>
      </thead>
      <?php


                   if ($sell->d_id == '1' && $sell->p_id == '1') {
                             $disabled = "disabled";  
                            }else if (($sell->d_id == '2')){
                              $disabled = "";  
                            } 
                            else {
                              $disabled = "disabled";
                             }?>
                             <?php
                            foreach($operations as $operation){
                            $product  = $operation->getProduct();
                             ?>
    
       
  
       
      <div><input type="hidden" name="ventaid[<?php echo $operation->sell_id;?>]"></div><!--RLS-->
      <div><input type="hidden" name="precio[<?php echo $operation->price_out;?>]"></div><!--RLS-->
      <input type="hidden" class="form-control" id="usuario" name = "usuario" value="<?php echo $sesion;?>"> <!-- Se envia el id de la forma de pago -->             
                       


      <tr>       
      <td style="text-align: center"><?php echo $product->code ;?></td>
      <td style="text-align: center"><?php echo $operation->q ;?></td>
      <td style="text-align: center"><?php echo $product->name ;?></td>
      <td style="text-align: center"><b><?php echo $symbol; ?> <?php echo number_format($operation->price_out,2,".",",") ;?></b></td>
      <td style="text-align: center"><b><?php echo $symbol; ?> 
      <?php
        
      $entregago = $operation->q;
      $total_venta=0;
      $total_venta = $operation->q*$operation->price_out;
      echo number_format($total_venta,2,".",",");
      $total+=$operation->q*$operation->price_out;?></b></td>
         
      <td style="text-align: center"><?php 
               if($sell->d_id == 1 && $sell->p_id == 1 ){
                 // si la venta es pagada el mismo dia y entregada toda sale de almacen.
                    echo $entregada = $operation->q; 
                   }else if($sell->d_id == 2 ) {
                    $entregada = (SellData::getDelivered($sell->id,$product->id)->entregada);
                    echo $entregada;  
                  } else {
                    echo $entregada = $operation->q; 
               }
                 
          ?>
      </td> 

      <!-- AQUI SE MUESTRA LA MERCANCIA QUE QUEDA PENDIENTE -->
      <td style="text-align: center"><?php echo ($operation->q)-$entregada; ?></td>
      <?php
      $por_entregar = $operation->q - $entregada;
      ?>

      <!--<td><input type="text" name="cantidad" id="cantidad" style="width : 80px;"></td>-->
      <td style="text-align: center"><input type="number" step="any" name="entregado[<?php echo $product->id;?>]" min="0" <?php echo $disabled; ?>></td>
      <input type="hidden" name="total_venta" value="<?php echo $total; ?>" class="form-control" placeholder="Total"> <!-- SE ENVIA TOTAL DE LA VENTA-->

      <?php
      include '/connection/conexion.php';
      $sql = "SELECT SUM(total_entregado) AS TOTAL FROM sell_to_deliver where sell_id = $sell->id and product_id = $product->id AND operacion = 1 ";
      $resultado = $mysqli->query($sql);
      $acumulador=0;
      while($row=mysqli_fetch_array($resultado)){
        $total_por_producto = $row[0];}
      ?>

     <?php
     if($sell->d_id == 1){
       $total_por_producto = $operation->q * $operation->price_out;
       }?>

    <td style="text-align: center"><b><?php echo $symbol; ?> <?php echo number_format($total_por_producto,2,".",",") ;?></b></td>
    
    <td style="text-align: center"><b><?php
    
    $disponible = 0;
     if($sell->d_id == 1 || $sell->p_id == 1){
       $disponible = $total_venta - $total_por_producto;
       $acumulador += $disponible;
     }else{
     $disponible = $total_venta - $total_por_producto;
     $acumulador += $disponible;}
    echo $symbol; ?> 
   <?php echo number_format($disponible,2,".",",") ;?></b></td>



    <input type="hidden" name="dis_por_producto" value="<?php echo $disponible; ?>" class="form-control" placeholder="Total"> <!-- SE ENVIA TOTAL DE LA VENTA-->

<script>
function cancelar() {
if (confirm("¿Seguro que deseas eliminar el producto de la Venta : <?php echo $sell->id?> ")) {
return true;
} else {
return false;}}
</script>

<script>
function dev() {
if (confirm("¿El sistema te enviara a la opcion de Devolucion de productos, para regresar productos de la compra con el ID : <?php echo $sell->id?> ")) {
return true;
} else {
return false;}}
</script>


      <script>
        $(function() {
          $("input[name='entregado[<?php echo $product->id;?>]']").change(function(){
            pendiente  = <?php echo ($operation->q) - ($entregada); ?>;
            porEntregar  = $("input[name='entregado[<?php echo $product->id;?>]']").val();
           // alert("SE DARÁ SALIDA A LA SIGUIENTE CANTIDAD DE PRODUCTO, POR FAVOR AUTORICE LA SALIDA PARCIAL "+ porEntregar);
 
            if(porEntregar == ""){porEntregar = 0;}
            if(porEntregar > pendiente){
              alert("Ya no cuentas con productos para realizar salida parcial de este producto");
              $("input[name='entregado[<?php echo $product->id;?>]']").focus();
              document.getElementById('entregarMercancia').disabled=true;
            }else{
              document.getElementById('entregarMercancia').disabled=false;
            }
          });
        });
      </script>
    </tr>

    <?php
      }
      ?>
    </table>

  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <div class="col-lg-offset-10">
          <div class="checkbox">
            <label>
            <!--
            <button class="btn btn-success">Dar Salida A Producto</button>
            -->
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
<div class="col-md-12">
<div class="box box-primary">
  <?php if(Core::$plus_iva==0):?>
<table class="table table-bordered">
 <!-- <tr>
    <td><h4>Subtotal:</h4></td>
    <td><h4><?php echo $symbol; ?> <?php echo number_format($total/(1+($iva_val/100)),2,'.',','); ?></h4></td>
  </tr> -->
  <tr>
    <td><h4>IVA:</h4></td>
    <td><h4><?php echo $symbol; ?> <?php echo round($total/(1+($iva_val/100)) * ($iva_val/100) ,2); ?></h4></td>
  </tr>
  <tr>
    <td><h4>Descuento:</h4></td>
    <td><h4><?php echo $symbol; ?> <?php echo number_format($sell->discount,2,'.',','); ?></h4></td>
  </tr>
  <tr>
    <td><h4>Subtotal:</h4></td>
    <td><h4><?php echo $symbol; ?> <?php echo round($total/1.16,2); ?></h4></td>
  </tr>
  <tr>
    <td><h4>Total:</h4></td>
    <td><h4><?php echo $symbol; ?> <?php echo number_format($total,2,'.',','); ?></h4></td>
  </tr>
</table>

<?php elseif(Core::$plus_iva==1):
  $anticipo = $sell->anticipo_venta ;
  $total=$sell->total;
?>

<?php
      include '/connection/conexion.php';
      $sql_1 = "SELECT SUM(total_entregado) as p_entregado from sell_to_deliver where operacion = 1 and sell_id = $sell->id ";
      $resultado_1 = $mysqli->query($sql_1);
      while($row=mysqli_fetch_array($resultado_1)){
      $p_entregados = $row[0];}
 
      $sql_2 = "SELECT SUM(total_entregado) as p_entradas from sell_to_deliver where operacion = 2 and sell_id = $sell->id ";
      $resultado_2 = $mysqli->query($sql_2);
      while($row=mysqli_fetch_array($resultado_2)){
      $p_entradas = $row[0];}

      $sql_3 = "SELECT SUM(total_entregado) as p_salidas from sell_to_deliver where operacion = 3 and sell_id = $sell->id ";
      $resultado_3 = $mysqli->query($sql_3);
      while($row=mysqli_fetch_array($resultado_3)){
      $p_salidas = $row[0];}

      $sql_4 = "SELECT SUM(total) as devolucion from sell where operation_type_id = 5 and sell_from_id = $sell->id ";
      $resultado_4 = $mysqli->query($sql_4);
      while($row=mysqli_fetch_array($resultado_4)){
      $devolucion_efectivo = $row[0];}
  
     $nuevo_saldo = 0 ;

         if ($sell->d_id == 2 || $sell->p_id == 2) {
          // de lo que ya se entrego  se le descuenta al total de la venta, creando un nuevo saldo
            $saldo_cliente = $total - $p_entregados; // con esta variable vamos teniendo cuanto se le ha ido entregando al cliente de productos
            $entradas      = ($p_entradas);
            $diferencia = $total - $acumulador;
            }?>


<table class="table table-bordered">
  <tr>
    <td ><b><h4>TOTAL VENTA :</h4></b></td>
    <td style="text-align: center;background:green;font-size:16px;color:white"><h4><?php echo $symbol; ?> <?php echo number_format($total,2,'.',','); ?></h4></td>
  </tr>

  <tr>
    <td ><h4>TOTAL ENTREGADO AL DÍA DE HOY :</h4></td>
   <b> <td  style="text-align: center;background:yellow;font-size:16px;color:black"><h4><?php echo $symbol; ?> <?php echo number_format($p_entregados,2,'.',','); ?></h4></td></b>
  </tr> 


  <tr>
    <td><h4>TOTAL DISPONIBLE EN VENTA :</h4></td>
    <td style="text-align: center;background:red;font-size:16px;color:white"><h4><?php echo $symbol; ?> <?php echo number_format($saldo_cliente,2,'.',','); ?></h4></td>
    <input type="hidden" name="saldo_cliente" id = "saldo_cliente" value="<?php echo $saldo_cliente; ?>" class="form-control" placeholder="Total"> <!-- SE ENVIA TOTAL DE LA VENTA -->
  </tr> 



  <tr>
    <td><h4>TOTAL DEVOLUCIONES:</h4></td>
    <td style="text-align: center;background:white;font-size:16px;color:black"><h4><?php echo $symbol; ?> <?php echo number_format($devolucion_efectivo,2,'.',','); ?></h4></td>
  </tr> 

  <tr>
    <td><h4>SALDO DISPONIBLE :</h4></td>
    <td style="text-align: center;background:white;font-size:16px;color:black"><h4><?php echo $symbol; ?> <?php echo number_format($p_entregados + $devolucion_efectivo,2,'.',','); ?></h4></td>
  </tr> 


</table>
<?php endif; ?>
</div>

  <script>
  var valor = $("#saldo_cliente").val();
  console.log(valor);

  if(valor <= '0'){
  document.getElementById('entregarMercancia').disabled=true;
  document.getElementById('ingresoMercancia').disabled=true;
 

  }else if (valor > '0') {
  document.getElementById('entregarMercancia').disabled=false;
  document.getElementById('ingresoMercancia').disabled=false;

  }
  </script>


<!--
<?php if($sell->person_id!=""):
$credit=PaymentData::sumByClientId($sell->person_id)->total;

?>
<div class="box box-primary">
<table class="table table-bordered">
  <tr>
    <td><h4>Saldo anterior:</h4></td>
    <td><h4><?php echo $symbol; ?> <?php echo number_format($credit-$total,2,'.',','); ?></h4></td>
  </tr>
  <tr>
    <td><h4>Saldo Actual:</h4></td>
    <td><h4><?php echo $symbol; ?> <?php echo number_format($credit,2,'.',','); ?></h4></td>
  </tr>
</table>
</div>
<?php endif;?>
</div>
</div>


</div>
-->

</form>

<div class="col-md-12">
<form method="post" class="form-horizontal" action="./?action=updatesell" id="processsell" enctype="multipart/form-data">
<div class="row">
<div class="col-md-12">
<div>

<input type="hidden" class="form-control" id="idventa" name = "idventa" value="<?php echo $sell->id;?>">
<input type="hidden" class="form-control" id="total" name = "total" value="<?php echo $total;?>">
<input type="hidden" class="form-control" id="estatus" name = "estatus" value="<?php echo $operation->operation_type_id;?>">

 
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab">Actualizar Información De Venta</a></li>
  </ul>

  <div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="main">

<div class="row">
  <?php 
     $clients = PersonData::getClients2();?>

  <div class="col-md-4">
                                <label class="control-label">Seleccione Cliente</label>
                                <div class="col-lg-12">
                                <select class="client_id form-control"  name="client_id" id="client_id">
                                <option value="<?php echo $client->id;?>" <?php if($client->id==$sell->person_id){ echo "selected"; }?>><?php echo $client->name." ".$client->lastname;?></option>
                                    </select>
                               
                                    <script type="text/javascript">
                                      $('.client_id').select2({
                                       
                                    placeholder: 'Elige Cliente',
                                    ajax: {
                                     url: 'ajax.php',
                                     dataType: 'json',
                                     delay: 250,
                                     processResults: function (data) {
                                       console.log(data);
                                     return {
                                     results: data
                                     };
                                     },
                                     cache: true
                                       }
                                      });
                                    </script>
                                    
                            
                                </div>
                             </div> 
                             


  <div class="row">

  <div class="col-md-3">
    <label class="control-label">Forma De pago</label>
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

  


<div class="col-md-2">
    <label class="control-label">Estado Entrega</label>
    <?php
                   $clients = DData::getAll();
                         ?>
                       <select name="d_id" class="form-control ">
                        <?php foreach ($clients as $client): ?>
                      <option value="<?php echo $client->id;?>" <?php if($client->id==$sell->d_id){ echo "selected"; }?>><?php echo $client->name;?></option>
                       <?php endforeach; ?>
                      </select>
  </div>



<div class="col-md-2">
    <label class="control-label">Tipo De Pago</label>
   
    <?php
      $clients = PData::getAll();
                 ?>
                                    <select name="p_id" id="p_id" class="form-control ">
                                        <?php foreach ($clients as $client): ?>
                                          <option value="<?php echo $client->id;?>" <?php if($client->id==$sell->p_id){ echo "selected"; }?>><?php echo $client->name;?></option>
                                        <?php endforeach; ?>
                                    </select>
  </div>
</div>
</div>


    <div class="col-md-12">
    <div class="">
    <label class="control-label">Comentarios</label>
      <textarea name="comment"  placeholder="Comentarios" class="form-control" rows="2"><?php echo $sell->comment;?></textarea>
    </div>
  </div>
  </div> 

<!--<div class="col-md-12">
    <label class="control-label">FOLIO DE FACTURA</label>
    <div class="col-lg-12">
    <?php if($sell->invoice_file!=""):?>
      <a href="./storage/invoice_files/<?php echo $sell->invoice_file;?>" target="_blank" class="btn btn-default"><i class="fa fa-file"></i> Archivo Factura (<?php echo $sell->invoice_file; ?>)</a>
      <br><br>
    <?php endif; ?>
      <input type="file" name="invoice_file"  placeholder="Archivo Factura">
    </div>
  </div>
  </div> -->
</div>


<br>
<input type="hidden" name="id" value="<?php echo $sell->id; ?>">
  <div class="row">
<div class="col-md-12">

<div class="form-group">
    <div class="col-lg-offset-9">
      <div class="checkbox">
        <label>
        <button class="btn btn-success" onclick="return procesar();"> Actualizar Datos De Venta</button>
        </label>
      </div>
    </div>
  </div>
</div>
</div>

</form>
</div>
</div>

<script>
function procesar() {
if (confirm("¿Deseas Aplicar el cambio a la Venta?, Al realizarlo se actualizará la información que hayas modificado, valida antes de precionar ACEPTAR.")) {
return true;
} else {
return false;

}
}
</script>
</script>
<script>
  $(document).ready(function(){
  //  $("#makepdf").trigger("click");
  });
</script>





  <?php else:?>
  501 Internal Error
<?php endif; ?>
</section>