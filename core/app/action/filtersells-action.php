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

      function cancela_ticket() {
    var data = "pass=" + $("#pass").val() + "&motivo=" + $("#motivo").val() + "&idcancela=" + $("#id_cancelacion").val()+ 
    "&total=" + $("#total").val() +  "&f_id=" + $("#f_id").val() + "&cliente_id=" + $("#cliente_id").val() + "&p_id=" + $("#p_id").val() + "&d_id=" + $("#d_id").val() + "&stock_id=" + $("#stock_id").val() + "&usuario=" + $("#usuario").val();      
        if ($("#pass").val() != "" || $("#motivo").val() != "" || $("#usuario").val() != "")
        {
            $.ajax({
                type: "POST",
                url: "./core/app/action/comprueba_clave.php",
                data: data,
                success: function (data) {
                    console.log(data);
                    if (data == "1") {
                        $("#clave").modal('hide');
                        alert("Cancelación correcta de la Venta: " + $("#id_cancelacion").val());
                        window.location.reload();
                    } else
                    if (data == "0")
                    {
                        $(".error").html("<span style='color:red'>Revise sus permisos para cancelar o su clave asignada</span>");
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

<?php
$products = null;

$fecha_i = $_GET["start_at"];
$fecha_f = $_GET["finish_at"];

// print_r(Core::$user);
if(isset($_SESSION["user_id"])){
if(Core::$user->kind==3){
$products = SellData::getAllBySQL(" where user_id=".Core::$user->id." and stock_to_id=".Core::$user->stock_id." and operation_type_id=2 and p_id=1 and d_id=1 and is_draft=0 and credito_liquidado =0 and remision_recuperada =0 ");
}
else if(Core::$user->kind==2 || Core::$user->kind==4){

$products = SellData::getAllBySQL(" where operation_type_id=2 and p_id=1 and d_id=1 and is_draft=0 and stock_to_id=".Core::$user->stock_id." and credito_liquidado =0 and remision_recuperada =0 and date(created_at) between '$_GET[start_at]' and '$_GET[finish_at]'");
}
else{
//print_r($_GET);
$sql = "select * from sell ";
$whereparams = array();
$whereparams[] = " (operation_type_id=2 and p_id=1 and d_id=1 and is_draft=0 and credito_liquidado =0) ";
if(isset($_GET["stock_id"]) && $_GET["stock_id"]!=""){
	$whereparams[] = " stock_to_id=$_GET[stock_id] ";
}
if(isset($_GET["start_at"]) && isset($_GET["finish_at"]) && $_GET["start_at"]!="" && $_GET["finish_at"]!=""){
	$whereparams[] = " ( date(created_at) between '$_GET[start_at]' and '$_GET[finish_at]' ) ";
}

 $sql2 = $sql." where ".implode(" and ", $whereparams)." order by created_at desc";

 $products = SellData::getAllBySQL3($sql2);}
}else if(isset($_SESSION["client_id"])){
$products = SellData::getAllBySQL(" where person_id=$_SESSION[client_id] and operation_type_id=2 and p_id=1 and d_id=1 and is_draft=0 order by created_at desc");	
}





if(count($products)>0){
 ?>
<br>

<?php 
include '/connection/conexion.php';
$sql_efectivo = "select * from sell where (operation_type_id=2 and f_id = 1 and p_id=1 and d_id=1 and is_draft=0 and facturado = 0 and remision_recuperada= 0 and credito_liquidado= 0) 
and ( date(created_at) between '$fecha_i' and '$fecha_f' )";
$resultado_efectivo = $mysqli->query($sql_efectivo);
$total_efectivo = 0;
while($row = $resultado_efectivo->fetch_assoc()){
$total_efectivo += $row['total'];
}

$sql_transferencia = "select * from sell where (operation_type_id=2 and f_id = 2 and p_id=1 and d_id=1 and is_draft=0 and facturado =0 and remision_recuperada=0 and credito_liquidado=0) 
and ( date(created_at) between '$fecha_i' and '$fecha_f' )";
$resultado_transferencia = $mysqli->query($sql_transferencia);
$total_transferencia= 0;
while($row = $resultado_transferencia->fetch_assoc()){
$total_transferencia += $row['total'];
}?> 


<div class="card box-primary">
<div class="card-header">
<tr>
<td > <h6  style="text-align: right;font-size:20px;color:#12a14b">Total Efectivo $ : <?php echo number_format($total_efectivo,2, '.', ',') ?></h6> </td>
</tr>
<tr>
<td> <h6 style="text-align: right;font-size:20px;color:#12C2FF">Total Transferencia $ : <?php echo number_format($total_transferencia,2, '.', ',') ?></h6> </td>
</tr>
</div>
</div>
<br>
<div class="card box-primary">
<div class="card-header">
<?php date_default_timezone_set('America/Mexico_City');?>
Listado Ventas Del Día 
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
        <th style="text-align: center">Facturado</th>
        <th style="text-align: center">Forma Pago</th>
		<th style="text-align: center">Ticket</th>
        <th style="text-align: center">Facturar</th>
        <th style="text-align: center">Cancelar</th>
     
	
	</thead>
  
	<?php foreach($products as $sell):
      $acumulado=0;
	  $operations = OperationData::getAllProductsBySellId($sell->id);?>

	<tr>
		<td style="text-align: center">
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
            if($sell->facturado == '1'){
            echo "Venta Facturada";
            }else {
            echo "No Facturada";
            }?></td>

            <td style="text-align: center"><?php  
            if($sell->f_id == '1'){
            echo "Efectivo";
            }else if ($sell->f_id == '2') {
            echo "Transferencia";
            }?></td>
        
		<td style="text-align: center">
		<?php if(isset($_SESSION["user_id"])):?>
        <a target="_blank" href="ticket.php?id=<?php echo $sell->id; ?>" class="btn btn-sm btn-secondary"><i class='bi-ticket'></i></a>

  

		<?php endif; ?>
		<script type="text/javascript">
		function printticket<?php echo $sell->id; ?>(){
		w<?php echo $sell->id; ?> = window.open("ticket.php?id=<?php echo $sell->id; ?>");
		w<?php echo $sell->id; ?>.print();
	    }</script><?php
                            if (isset($_SESSION["user_id"]) || Core::$user->kind == 1 || Core::$user->kind == 3):    
                                if ($sell->facturado == 1) {
                                    $disabled3 = "disabled";
                                } else {
                                    $disabled3 = "";
                                }

                              
                                ?>

                                <?php $identificador = 1 ?>
                                
	
                           

                             
    </td>
    <td  style="text-align: center">
    <a href="./index.php?view=facturacion&id=<?php echo $sell->id; ?>&cliente=<?php echo $sell->person_id; ?>&almacen=<?php echo $id_almacen;?>&forma_pago=<?php echo $fp;?>&uso_comprobante=<?php echo $uc;?>&identificador=<?php echo $identificador?>&regimen_fiscal=<?php echo $rf?>&tiene_rs=<?php echo $si_rs; ?>" class="btn btn-sm btn-success <?php echo $disabled3 ?>" ><i class="bi-receipt"></i></a> 
   </td>

    <td  style="text-align: center">
    <?php   if (Core::$user->kind == 1) {?>
    <a  class="btn btn-sm btn-danger" onclick="cancelacion(<?php echo $sell->id;?>,<?php echo $id_almacen;?>,<?php echo $sell->total;?>,<?php echo $sell->getF()->id;?>,<?php echo $sell->person_id;?>,<?php echo $sell->getP()->id?>,<?php echo $sell->getD()->id?>,<?php echo $id_almacen;?>);"><i  class="bi-trash"></i></a>
    <?php }?>
    <?php endif; ?> 
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
	<br>
		<p>No se ha realizado ninguna venta.</p>
	</div>
	<?php
}

?>

<div class="modal fade" id="clave" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clave">Favor de Ingresar Datos</h5>
				<button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
               </button>
            </div>

            <div class="modal-body">
                <form>
       
                    <input type="hidden" class="form-control" id="id_cancelacion"> 
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
                    <button type="button" class="btn btn-primary" onclick="cancela_ticket();">Cancelar Ticket</button>
                    </div>
                </form>
            </div>
            <div> <label class="error"></label></div>
       
        </div>
    </div>
</div>
