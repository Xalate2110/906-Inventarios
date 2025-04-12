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

//// RLS
//echo Core::$user->kind; //RLS
    $us = UserData::getById(Core::$user->kind);
    //print_r("Almacen asignado");
    //echo $us->stock_id;
//// RLS
// print_r(Core::$user);
if (isset($_SESSION["user_id"])) {
    if (Core::$user->kind == 10) {
        //print_r("no ADMIN");
        $products = SellData::getAllBySQL(" where user_id=" . Core::$user->id . " and operation_type_id=2 and p_id=4 and d_id = 1 and is_draft=0   order by created_at desc ");
    } else if (Core::$user->kind == 2 || Core::$user->kind==4) {
        $products = SellData::getAllBySQL(" where operation_type_id=2 and p_id=4 and d_id=1 and is_draft=0 and stock_to_id=" . Core::$user->stock_id . " ");
    } else {
//print_r($_GET);
        //print_r("es ADMIN");
        $sql = "select * from sell ";
        $whereparams = array();
        $whereparams[] = " (operation_type_id=2  and p_id = 4) ";
        if (isset($_GET["stock_id"]) && $_GET["stock_id"] != "") {
            $whereparams[] = " stock_to_id=$_GET[stock_id] ";
        }
        if (isset($_GET["start_at"]) && isset($_GET["finish_at"]) && $_GET["start_at"] != "" && $_GET["finish_at"] != "") {
            $whereparams[] = " ( date(created_at) between '$_GET[start_at]' and '$_GET[finish_at]' ) ";
        }
        $sql2 = $sql . " where " . implode(" and ", $whereparams) . " and stock_to_id=" . Core::$user->stock_id." order by created_at desc";
        
        $products = SellData::getAllBySQL3($sql2);
    }
    //// RLS
    /*
        if($us->stock_id){
            $sql = "select * from sell ";
            $whereparams = array();
            $whereparams[] = " (operation_type_id=2 and p_id = 4 ) ";
            $whereparams[] = "stock_to_id=$us->stock_id";

            if (isset($_GET["start_at"]) && isset($_GET["finish_at"]) && $_GET["start_at"] != "" && $_GET["finish_at"] != "") {
                $whereparams[] = " ( date(created_at) between '$_GET[start_at]' and '$_GET[finish_at]' ) ";
            }

            $sql2 = $sql . " where " . implode(" and ", $whereparams) . " order by created_at desc";
            $products = SellData::getCredits();
        } */
    //// RLS

} else if (isset($_SESSION["client_id"])) {
    $products = SellData::getAllBySQL(" where person_id=$_SESSION[client_id] and operation_type_id=2 and p_id=1 and d_id=1 and is_draft=0 and credito_liquidado = 1 order by created_at desc");
}

if(count($products)>0){
?>

   <?php 
   $creditsum= 0;
   $pendiente_c=0;
   $total_credito=0;
   $total_x_recuperar=0;
   foreach($products as $sell):
   $tx = PaymentData::sumBySellId($sell->id)->total;
   if($tx>=0){
   $credit_array[] = array("sell_id"=>$sell->id,"total"=>$tx);
   $creditsum=$tx;
   $pendiente_c += $creditsum;
   $total_cred= $sell->total-$sell->discount;
   $total_credito += $total_cred;
   $total_x_recuperar = round($total_credito - $pendiente_c,2);
   }?>
   <?php endforeach; ?>
   <br>
    <div class="card box-primary">
    <div class="card-header">
    <tr>
    <td > <h6  style="text-align: right;font-size:20px;color:#FBC22D">Total A Crédito $ : <?php echo number_format($total_credito,2, '.', ',') ?></h6> </td>
    </tr>
    <tr>
    <td> <h6 style="text-align: right;font-size:20px;color:red">Total Pendiente $ : <?php echo number_format($pendiente_c,2, '.', ',') ?></h6> </td>
    </tr>
    </div>
    </div>



<br>
<div class="card box-primary">
<div class="card-header">
Listado Ventas a Crédito
</div>
<div class="box box-primary table-responsive">
<table class="table table-bordered table-hover table-responsive datatable">
             
	<thead>
		<th style="text-align: center" >Detalles</th>
		<th style="text-align: center" >Folio</th>
		<th style="text-align: center" >Nombre Cliente</th>
		<th style="text-align: center" >Pago</th>
		<th style="text-align: center" >Entrega</th>
        <th style="text-align: center" >Pendiente</th>
		<th style="text-align: center" >Total</th>
		<th style="text-align: center" >Fecha</th>
        <th style="text-align: center" >Cancelar</th>
        <th style="text-align: center" >Facturar</th>
        <th style="text-align: center">Comprobante Crédito</th>
        <th style="text-align: center">Comprobante Abonos</th>
        <th style="text-align: center">Aplicar Pago</th>
        <th style="text-align: center">Relación Abonos</th>
	</thead>
	<?php foreach($products as $sell):?>

	<tr>
    <td style="width:30px;">
			<?php if(isset($_SESSION["user_id"])):?>
		<a href="index.php?view=onesell&id=<?php echo $sell->id; ?>" class="btn btn-sm btn-secondary"><i class="bi-eye"></i></a></td>
	    <?php endif; ?>
        <td>R - <?php echo $sell->id; ?></td>
        <td>
        <?php
        $c= $sell->getPerson();
            echo utf8_encode($c->name);
            $fp = $c->forma_pago;
            $uc = $c->uso_comprobante;
            $rf = $c->regimen_fiscal;
            $si_rs = $c->tiene_rs;    
            ?>
            </td>
            <td><?php echo $sell->getP()->name; ?></td>
            <td><?php echo $sell->getD()->name; ?></td>
        <td style="text-align: center">
        <?php
        $creditsum= 0;
        $tx = PaymentData::sumBySellId($sell->id)->total;

        if($tx>=0){
        $credit_array[] = array("sell_id"=>$sell->id,"total"=>$tx);
        $creditsum=$tx;
        }
        echo "<b> $";
        echo number_format($creditsum,2,".",",");
        echo "</b>";
        ?>
        </td>

<td>
<?php
$total= $sell->total;
	/*foreach($operations as $operation){
		$product  = $operation->getProduct();
		$total += $operation->q*$product->price_out;
	}*/
		echo "<b>".Core::$symbol." ".number_format($total,2,".",",")."</b>";

?>			

		</td>
		<td style="text-align: center"><?php echo $sell->created_at; ?></td>
     
		<td style="width:120px;text-align: center">
<?php if(isset($_SESSION["user_id"])):
  
  $sesion=$_SESSION["user_id"];
  $sucursal = StockData::getPrincipal()->id;
  $client = $sell->getPerson();
  
  ?>
                       
                                
                                <?php $identificador = 1 ?>
                <input type="hidden" class="form-control" id="usuario" name = "usuario" value="<?php echo $sesion;?>"> <!-- Se envia el id de la forma de pago -->  
                <input type="hidden" class="form-control" id="p_id" name = "p_id" value="<?php echo $sell->getP()->id;?>"> <!-- Se envia el id de la forma de pago -->  
                <input type="hidden" class="form-control" id="d_id" name = "d_id" value="<?php echo $sell->getD()->id; ?>"> <!-- Se envia el id de la forma de pago -->  
                <?php 
                 $sesion=$_SESSION["user_id"];
                 ?>  
 
            <?php   if (Core::$user->kind == 1) {?>
            <a  class="btn btn-sm btn-danger" onclick="cancelacion(<?php echo $sell->id;?>,<?php echo $sucursal;?>,<?php echo $sell->total;?>,<?php echo $sell->getF()->id;?>,<?php echo $sell->person_id;?>,<?php echo $sell->getP()->id?>,<?php echo $sell->getD()->id?>,<?php echo $sucursal;?>);"><i  class="bi-trash"></i></a>
            <?php }?>

            <?php endif;?>
                </td>


                <td style="text-align: center">
                <?php
                            
                            if ($sell->invoice_code != '' || $sell->box_id != '') {
                                $disabled = "disabled";
                            } else {
                                $disabled = "";
                            }


                            if ($sell->box_id != '') {
                                $disabled2 = "disabled";
                            } else {
                                $disabled2 = "";
                            }
                            ?>



<a href="./index.php?view=facturacion&id=<?php echo $sell->id; ?>&cliente=<?php echo $client->id; ?>&almacen=<?php echo $sucursal; ?>&forma_pago=<?php echo $fp;?>&uso_comprobante=<?php echo $uc;?>&identificador=<?php echo $identificador?>&regimen_fiscal=<?php echo $rf?>&tiene_rs=<?php echo $si_rs; ?>"  class="btn btn-sm btn-success <?php echo $disabled ?>" ><i class="bi-receipt"></i></a> 
  </td>

    <td  style="text-align: center;">
    <a  target="_blank" href="reportes_pdf/nota_credito.php?id=<?php echo $sell->id ?>&cliente=<?php echo $client->id;?>&sucursal=<?php echo $sucursal;?>" class="btn btn-xs btn-prymari" ><i class="bi-ticket" ></i></a>
    </td>
    
    <td  style="text-align: center;">
   <a target="_blank" href="reportes_pdf/reporte_abonos_credito.php?id=<?php echo $sell->id ?>&cliente=<?php echo $client->id;?>&sucursal=<?php echo $sucursal;?>" class="btn btn-xs btn-prymari" ><i class="bi-ticket"></i></a>
   </td> 
    <td style="text-align: center">
    <a href="index.php?view=makepayment&id=<?php echo $client->id;?>" class="btn btn-secondary btn-sm">Aplicar Pago</a>
    </td>
    <td style="text-align: center">
    <a href="index.php?view=paymenthistory&id=<?php echo $client->id;?>&compra=<?php echo $sell->id ?>" class="btn btn-secondary btn-sm">Relación Abonos</a>
    </td>
   </tr>

<?php endforeach; ?>
</table>
</div>
<div class="clearfix"></div>

<?php
}else{
?>

<div class="jumbotron">
	<br>
    <p>No se ha realizado ninguna venta a Crédito</p>
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