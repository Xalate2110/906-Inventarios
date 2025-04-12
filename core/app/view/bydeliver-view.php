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

<section class="content"> 

<div class="row">
	<div class="col-md-12">
<?php if(isset($_SESSION["client_id"])):?>
		<h1><i class='glyphicon glyphicon-shopping-cart'></i> Compras por Recibir</h1>
<?php else:?>

<div class="panel panel-default">
                        <div class="panel-heading">
						<h1><i class='glyphicon glyphicon glyphicon-shopping-cart'></i> Listado General Ventas Por Entregar</h1>
						</div>
						 
                        </div>
<?php endif;?>
		<div class="clearfix"></div>


<?php

$products=null;

if(isset($_SESSION["user_id"])){
if(Core::$user->kind==10){
$products = SellData::getSellsToDeliverByUserId(Core::$user->id);
}
else if(Core::$user->kind==1 || Core::$user->kind==2 || Core::$user->kind==3 || Core::$user->kind==4){
$products = SellData::getSellsToDeliverByStockId(Core::$user->stock_id);
}
else{
$products = SellData::getSellsToDeliver();

}
}else if(isset($_SESSION["client_id"])){
$products = SellData::getSellsByClientId($_SESSION["client_id"],1,0);	
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
    // anticipos
    $total_an = $sell->anticipo_venta;
    $total_ant += $total_an;
    ?>
    <?php endforeach; ?>


    <br>

    <div class="card box-primary">
   <div class="card-header">
   <tr>
   <td> <h6  style="text-align: right;font-size:16px;background:yellow;color:black">Total Pendiente Por Entregar $ : <?php echo number_format($total_pe,2, '.', ',') ?></h6> </td>
   <td> <h6  style="text-align: right;font-size:16px;background:white;color:green">Total Anticipos Recibidos $ : <?php echo number_format($total_ant,2, '.', ',') ?></h6> </td>
   </tr></div></div>

<br>

	<div class="card box-primary">
<div class="card-header">
<span class="box-title"><b>Listado General Ventas Por Entregar</b></span></div>
<div class="card-body">
<table class="table datatable table-bordered table-hover	">
	<thead>
		<th style="text-align: center;">Detalles</th>
		<th style="text-align: center;">Folio</th>
		<th style="text-align: center;">Nombre Cliente</th>
		<th style="text-align: center;">Pago</th>
		<th style="text-align: center;">Entrega</th>
		<th style="text-align: center;">Pendiente</th>
        <th style="text-align: center;">Anticipo</th>
        <th style="text-align: center;">Total Venta</th>
		<th style="text-align: center;">Sucursal Venta</th>
		<th style="text-align: center;">Fecha Venta</th>
		<th style="text-align: center;">Acciones</th>
	</thead>
	<?php foreach($products as $sell):?>

   <?php
   include '/connection/conexion.php';
    $sql = "SELECT CONCAT(person.name, person.lastname) AS CLIENTE from sell 
    inner join person on
    person_id = person.id and sell.id = $sell->id";
    $resultado = $mysqli->query($sql);
    while($row=mysqli_fetch_array($resultado)){
    $nombrecliente = $row[0];}
   ?>

	<tr>
		<td style="text-align: center;">
		<a href="index.php?view=onesell&id=<?php echo $sell->id; ?>" class="btn btn-sm btn-secondary"><i class="bi-eye"></i></a></td>
		<td>R - <?php echo $sell->id; ?></td>
    <td style="text-align: center;">
    <?php echo $nombrecliente; ?>
    </td>
    <td style="text-align: center;"><?php echo $sell->getP()->name; ?></td>
    <td style="text-align: center;"><?php echo $sell->getD()->name; ?></td>
		
	<td style="text-align: center;">
    
<?php
    $total= $sell->total_por_pagar;
    echo "<b>".Core::$symbol." ".number_format($total,2,".",",")."</b>";
   ?>			
		</td>

    <td style="text-align: center;">
<?php
		echo "<b>".Core::$symbol." ".number_format($sell->anticipo_venta,2,".",",")."</b>";
   ?>			
		</td>

    
    <td style="text-align: center;">
<?php
     $total_venta = $sell->total;
        echo "<b>".Core::$symbol." ".number_format($total_venta,2,".",",")."</b>";
        
?>			

		</td>


        <?php 
        $sesion=$_SESSION["user_id"];
        ?>     

     <input type="hidden" class="form-control" id="total" name = "total" value="<?php echo $total_venta; ?>"> <!-- Se envia el total de la venta -->
     <input type="hidden" class="form-control" id="f_id" name = "f_id" value="<?php echo $sell->getF()->id; ?>"> <!-- Se envia el id de la forma de pago -->  
     <input type="hidden" class="form-control" id="cliente_id" name = "cliente_id" value="<?php echo $sell->person_id ?>"> 
     <input type="hidden" class="form-control" id="p_id" name = "f_id" value="<?php echo $sell->getP()->id; ?>"> <!-- Se envia el id de la forma de pago -->  
     <input type="hidden" class="form-control" id="d_id" name = "f_id" value="<?php echo $sell->getD()->id; ?>"> <!-- Se envia el id de la forma de pago -->  
     <input type="hidden" class="form-control" id="f_id" name ="f_id" value="<?php echo $sell->getF()->id;?>">
     <input type="hidden" class="form-control" id="id_anticipo" name ="id_anticipo" value="<?php echo $sell->id_anticipo;?>">
     <input type="hidden" class="form-control" id="stock_id" name ="stock_id" value="<?php echo $sell->getStockTo()->id; ?>">
     <input type="hidden" class="form-control" id="usuario" name = "usuario" value="<?php echo $sesion;?>"> <!-- Se envia el id de la forma de pago -->     

    <td style="text-align: center;"><?php echo $sell->getStockTo()->name; ?></td>
		<td style="text-align: center;"><?php echo $sell->created_at; ?></td>
		<td style="width:200px;">
    <?php if(isset($_SESSION["user_id"])):
     $sucursal = StockData::getPrincipal()->id;
     $client = $sell->getPerson();
	 $fp = $client->forma_pago;
	 $uc = $client->uso_comprobante;
	 $rf = $client->regimen_fiscal;    
	 $si_rs = $client->tiene_rs;    
  ?>

  
<?php $identificador = 1 ?>
    <?php if($sell->stock_to_id == '1') { ?>
		<a  target="_blank" href="ticket_athena.php?id=<?php echo $sell->id; ?>" class="btn btn-xs btn-warning"><i class='bi-ticket'></i></a>
        <?php } else if($sell->stock_to_id == '2') {  ?>
        <a  target="_blank" href="ticket_la_reyna.php?id=<?php echo $sell->id; ?>" class="btn btn-xs btn-warning"><i class='bi-ticket'></i></a>
        <?php } else if($sell->stock_to_id == '3') { ?>
        <a  target="_blank" href="ticket_cabrera.php?id=<?php echo $sell->id; ?>" class="btn btn-xs btn-warning"><i class='bi-ticket'></i></a>
        <?php } else if($sell->stock_to_id == '4') { ?>
        <a  target="_blank" href="ticket_perez_prado.php?id=<?php echo $sell->id; ?>" class="btn btn-xs btn-warning"><i class='bi-ticket'></i></a>
        <?php }?>
    
    <?php if (Core::$user->kind==1 || Core::$user->kind==4):  ?>	
    <a href="./index.php?view=facturacion&id=<?php echo $sell->id; ?>&cliente=<?php echo $client->id; ?>&almacen=<?php echo $sucursal; ?>&forma_pago=<?php echo $client->forma_pago;?>&uso_comprobante=<?php echo $client->uso_comprobante;?>&identificador=<?php echo $identificador?>&regimen_fiscal=<?php echo $rf?>&tiene_rs=<?php echo $si_rs;?>" class="btn btn-xs btn-success"><i class="bi bi-receipt"></i></i></a>
    <?php endif;?>

<?php if (Core::$user->kind==1 || Core::$user->kind==4 ):  ?>	
    <a href="./?action=deliver&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-primary"  onclick="return salida();"><i class="bi bi-arrow-right-circle-fill"></i></a> 
    <?php endif;?>
<?php if (Core::$user->kind==1 || Core::$user->kind==4):  ?>	
		<!-- <a href="index.php?view=delsell&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-danger" onclick="return cancelar();"><i class="fa fa-trash"></i></a> -->
		<a  class="btn btn-sm btn-danger" onclick="cancelacion(<?php echo $sell->id;?>,<?php echo $sucursal;?>,<?php echo $sell->total;?>,<?php echo $sell->getF()->id;?>,<?php echo $sell->person_id;?>,<?php echo $sell->getP()->id?>,<?php echo $sell->getD()->id?>);"><i  class="bi-trash"></i></a>
        <?php endif;?>
    
        <?php endif;?>
    </td>
	  </tr>
    <?php endforeach; ?>
    </table>
	</div>
</div>



<script>
function salida() {
if (confirm("¿Seguro que deseas darle Salida a la Venta?, Una vez realizado el proceso de salida saldrá la mercancia restante del Inventario")) {
return true;
} else {
return false;}
}
</script>

<script>
function cancelar() {
if (confirm("¿Seguro que deseas cancelar la venta?, una vez cancelada se regresara la mercancia almacen y se eliminara el registro de la base de datos.")) {
return true;
} else {
return false;}}
</script>

<div class="clearfix"></div>

	<?php
}else{
	?>
	<div class="jumbotron">
		<h2>No hay ventas</h2>
		<p>No se ha realizado ninguna venta.</p>
	</div>
	<?php
}

?>
<br><br><br><br><br><br><br><br><br><br>
	</div>
</div>
</section>



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


