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

  
    function cancela_ticket4() {
        var data = "pass=" + $("#pass").val() + "&motivo=" + $("#motivo").val() + "&idcancela=" + $("#id_cancelacion").val()+ 
        "&total=" + $("#total").val() +  "&f_id=" + $("#f_id").val() + "&cliente_id=" + $("#cliente_id").val() + "&p_id=" + $("#p_id").val() + "&d_id=" + $("#d_id").val() + "&id_anticipo=" + $("#id_anticipo").val() + "&stock_id=" + $("#stock_id").val() + "&usuario=" + $("#usuario").val(); 
        
        if ($("#pass").val() != "" || $("#motivo").val() != "")
        {
            $.ajax({
                type: "POST",
                url: "./core/app/action/comprueba_clave4.php",
                data: data,
                success: function (data) {
                    console.log(data);
                    if (data == "1") {
                        $("#clave").modal('hide');
                        alert("LA COMPRA CANCELADA CON EXITO , EL FOLIO CANCELADO ES : " + $("#id_cancelacion").val());
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

<?php
$products = null;
$fecha_i = $_GET["start_at"];
$fecha_f = $_GET["finish_at"];

if(Core::$user->kind==2 || Core::$user->kind==3 || Core::$user->kind==4){
$products = SellData::getResByStockId(Core::$user->stock_id,$fecha_i,$fecha_f);
}else{

$sql = "select * from sell ";
$whereparams = array();
$whereparams[] = " (operation_type_id=1 and p_id in (1,4) and d_id=1) ";
if(isset($_GET["stock_id"]) && $_GET["stock_id"]!=""){
  $whereparams[] = " stock_to_id=$_GET[stock_id] ";
}


if(isset($_GET["start_at"]) && isset($_GET["finish_at"]) && $_GET["start_at"]!="" && $_GET["finish_at"]!=""){
  $whereparams[] = " ( date(created_at) between '$_GET[start_at]' and '$_GET[finish_at]') ";
}


 $sql2 = $sql." where ".implode(" and ", $whereparams)." order by created_at desc";
 $products = SellData::getAllBySQL3($sql2);


}
if(count($products)>0){
    $sesion=$_SESSION["user_id"];
	?>


<?php 
$total1=0;
foreach($products as $sell):
	$operations = OperationData::getAllProductsBySellId($sell->id);
    $totalc=0;
    foreach($operations as $operation){
            $almacen = $sell->getStockTo()->id;
            $product  = $operation->getProduct();
            $total1 += $operation->q*$product->price_in;
            $totalc +=$total1;} 
    ?>
    <?php endforeach; ?>

<br>

<div class="card box-primary">
<div class="card-header">
<tr>
<td> <h6  style="text-align: right;font-size:16px;background:yellow;color:black">Total De Compras Realizadas $ : <?php echo number_format($totalc,2, '.', ',') ?></h6> </td>
</tr></div></div>





<br>
<div class="card box-primary">
<div class="card-header">
<span class="box-title">Litado de Compras</span></div>
<div class="card-body">
<div class="box box-primary table-responsive">
<table class="table table-bordered table-hover table-responsive datatable">
	<thead>
		<th style="text-align: center;">Detalles</th>
		<th style="text-align: center;">F - Compra</th>
		<th style="text-align: center;">Pago</th>
		<th style="text-align: center;">Entrega</th>
		<th style="text-align: center;">Total</th>
		<th style="text-align: center;">Almacen</th>
		<th style="text-align: center;">Fecha</th>
        <th style="text-align: center;"> Proveedor</th>
        <th style="text-align: center;">Comprobante Compra</th>
        <th style="text-align: center;">Cancelar Compra</th>
       
	</thead>
	<?php foreach($products as $sell):
	$operations = OperationData::getAllProductsBySellId($sell->id);
    ?>

	<tr>
		<td style="width:30px;"><a href="index.php?view=onere&id=<?php echo $sell->id; ?>" class="btn btn-sm btn-secondary"><i class="bi-eye"><i class="glyphicon glyphicon-eye-open"></i></a></td>
		<td>C - <?php echo $sell->id; ?></td>
        <td><?php echo $sell->getP()->name; ?></td>
        <td><?php echo $sell->getD()->name; ?></td>
<td>
<?php
$total=0;
	foreach($operations as $operation){
        $almacen = $sell->getStockTo()->id;
		$product  = $operation->getProduct();
		$total += $operation->q*$product->price_in;
	}
		echo "<b>".Core::$symbol." ".number_format($total,2,".",",")."</b>";
        
?>			

<?php 

$client = $sell->getPerson();
?>


    </td>     <td><?php if($sell->stock_to_id!=null){echo
$sell->getStockTo()->name ;} ?></td>     <td><?php echo $sell->created_at;
?></td>     


<td><?php echo $client->name." ".$client->lastname;?></td>

<td style="text-align: center;">    
<a  target="_blank" href="reporte_compra.php?id=<?php echo $sell->id ?>&sucursal=<?php echo $almacen?>" class="btn btn-sm btn-secondary"><i class='bi-ticket'></i></a>
</td>  

<td style="text-align: center;">    
<a  class="btn btn-sm btn-danger" onclick="cancelacion(<?php echo $sell->id;?>);"><i class="bi-trash"></i></a>
</td>  
</tr>
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
                    <button type="button" class="btn btn-primary" onclick="cancela_ticket4();">Cancelar Ticket</button>
                    </div>
                </form>
            </div>
            <div> <label class="error"></label></div>
       
        </div>
    </div>
</div>

