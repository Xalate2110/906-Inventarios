<?php

include '/connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
?>

<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script>
    $(document).ready(function () {
        $("#scantidad").html("0.00");
        var cliente = $("#idcliente").val();
        $.ajax({
            type: "POST",
            url: 'core/app/json/traesaldo.php',
            data: "idcliente=" + cliente,
            success: function (data) {
                $("#id_saldo").val(data);
                $("#scantidad").html(data);
            }
        });
    });

    $(function () {
        $("[name='my-checkbox']").bootstrapToggle();
    })
</script>



<?php 
$client = PersonData::getById($_GET["id"]);
$sells = SellData::getCreditsByClientId($client->id);
// print_r($sells);
//print_r($sells);
//print_r($products);
$total=0;
$credit_array = array();
foreach ($sells as $sell) {
//  print_r($sell);
$tx = PaymentData::sumBySellId($sell->id)->total;

if($tx>=0){
$credit_array[] = array("sell_id"=>$sell->id,"total"=>$tx);
$total+=$tx;
}
}
//$total = PaymentData::sumByClientId($client->id)->total;

?>

<input type="hidden" id="idcliente" value="<?php echo $client->id; ?>">
<input type="hidden" type="text" id="id_saldo" >

<section class="content">
<div class="row">
	<div class="col-md-12">
    
	<h4>Realizar Pago</h4>
  <h5 style="color: red;">Deuda total del Cliente: $ <?php echo number_format($total,2, '.', ','); ?></h5>

  
   
  <div class="col-md-6">
  <h5>Cliente : <?php echo utf8_encode($client->name) ?></h5>

  <!--
  <h5 style="color: green;">Total Abonos Disponibles : $ <span id="scantidad"></span></h5> -->
  </div>
            <script type="text/javascript">
            function ShowSelected()
            {
            /* Para obtener el valor */
            var idanticipo = document.getElementById("id_abono").value;
             $("#id_abono").val(idanticipo);
            /* Para obtener el texto */
            var combo = document.getElementById("id_abono");
            var cantidad_anticipo = combo.options[combo.selectedIndex].text;
            $("#anticipo_seleccionado").val(cantidad_anticipo);
            }   
            </script>

            
            <div class="col-md-5">
                <h5 style="color: green;">Listado Abonos Disponibles :</span></h5>
                <select name="id_abono" id="id_abono" class="form-control" onchange="ShowSelected();" >
                <option value="">Elige el anticipo...</option> 
                <?php
                $sql_anticipos = "select idabonos, idcliente, cantidad  from bitacora_abonos where idcliente = $client->id and cantidad > 0 and operacion = 2 ";
                $resultSet = $mysqli->query($sql_anticipos);
               ?>
               <?php
               while ($fila = $resultSet->fetch_assoc()) {
               echo "<option value='" . $fila['idabonos'] . "'>".$fila['cantidad'] . "</option>";
              }
              ?>
              </select>
              </div>
       
              <div class="col-md-10">
              <h3 style="color: green;"><span id="anticipo_seleccionado"></span></h3>
              </div>
              </div>
              <input type="hidden" type="text" id="id_abono" >
            
  <?php if(count($credit_array)>0):?>
    <?php foreach($credit_array as $ca):?>
  <div class="card box-primary">
    <div class="card-body">
  <table class="table">
  <tr>
  <td>
		<form class="form-horizontal" method="post" enctype="multipart/form-data" id="addpayment<?php echo $ca['sell_id']; ?>" action="index.php?action=addpayment" role="form">
    <div class="pull-right">
     <input type="checkbox" id="<?php echo $ca['sell_id']; ?>"  name='my-checkbox'  data-toggle="toggle" data-onstyle="success"  data-on="Pagar" data-off="Aplicar Abono" onchange="valida(this.id);" >
    </div> 
  


<input type="hidden" name="sell_id" value="<?php echo $ca['sell_id'];?>">
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Venta</label>
    <div class="col-md-4">
      <input type="text" name="" id="" class="form-control" id="barcode" placeholder="Cliente" value="#<?php echo $ca['sell_id'] ?>" readonly>
    </div>
    <br>
    <div class="col-md-2">
    <a href="./?view=onesell&id=<?php echo $ca['sell_id']; ?>" class='btn btn-warning'>Ver detalles Del Crédito</a>
    </div>
    <br>

  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre Cliente</label>
    <div class="col-md-6">
      <input type="text" name="" id="product_code" class="form-control" id="barcode" placeholder="Cliente" value="<?php echo utf8_encode($client->name); ?>" readonly>
      <input type="hidden" name="client_id" class="form-control"  value="<?php echo $client->id; ?>">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Total adeudado</label>
    <div class="col-md-6">
      <input type="text" name="" id="" class="form-control" placeholder="Total adeudado" value="$ <?php echo round($ca['total'],2); ?>" readonly>
    <!-- Se envia el total de la venta que aun continua con adeudo -->
    <input type="hidden" name="total" id="total<?php echo $ca['sell_id']; ?>" class="form-control"  value="<?php echo round($ca['total'],2); ?>">
    </div>
  </div>

  <div class="form-group">
  <label for="inputEmail1" class="col-lg-2 control-label">Forma De Pago</label>
  <div class="col-md-6">
                                                
  <select name="id_formapago" id="id_formapago" class="form-control" required>
  <option value="">Selecciona.....</option>
  <option value="1">1 - Efectivo </option>
  <option value="2">2 - Transferencia Electronica</option>
  </select>
  </div>
  
                                            
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Cantidad Abonar*</label>
    <div class="col-md-6">
      <input type="text" name="val" required id="val<?php echo $ca['sell_id']; ?>" class="form-control" placeholder="Pago a Realizar">
    </div>
  </div>




     
  <div class="form-group">
  <label for="exampleInputEmail1" class="col-lg-2 control-label">Sucursal Operación</label>
  <div class="col-md-6">
		   <select name="stock_id" id="stock_id" class="form-control">
			<option value="">Selecciona La Sucursal</option>
			<?php foreach(StockData::getAll() as $stock):?>
				<option value="<?php echo $stock->id; ?>" <?php
        if ($stock->id == StockData::getPrincipal()->id) {
        echo "selected";}?>><?php echo $stock->name; ?></option>
			<?php endforeach; ?>
		</select>
</div>
	</div>
    </div>



        
    <br>
      <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
          <button type="submit" class="btn btn-primary">Realizar Pago</button>
          <a href="./?view=credit" class="btn btn-danger">Cancelar</a>
          <button type="button" class="btn btn-success" id="click_money" data-coreui-toggle="tooltip" data-coreui-placement="bottom" title="Pagar Completo"><i class="bi-cash"></i></button>
        </div>
      </div>

      <script type="text/javascript">
  $("#click_money").click(function(){
    $("#money").val("<?php echo round($ca['total'],2);?>");
  })

 $("#click_0").click(function(){
    $("#money").val(0);
})

</script>


    </form>
    </td>
    </tr>
    </table>
    </div>


<script>
  $(document).ready(function(){
    $("#addpayment<?php echo $ca['sell_id']; ?>").submit(function(e){
      total = $("#total<?php echo $ca['sell_id']; ?>").val();
      val = $("#val<?php echo $ca['sell_id']; ?>").val();
      id_venta =  $("#id_venta<?php echo $ca['sell_id']; ?>").val();

    
    
      if( val!="" && val>0 ){
        if(parseFloat(val)<=parseFloat(total)){
          // procesamos
          go = confirm("¿Deseas aplicar el abono al crédito el cliente?");
          if(!go){ e.preventDefault(); }
        }else{
        alert("No es posible ingresar un pago mayor a la deuda total.")
        e.preventDefault();          
        }

      }else{
        alert("Debes ingresar un valor mayor que 0.")
        e.preventDefault();
      }
    });
});

</script>
<?php endforeach; ?>
<?php endif; ?>

	</div>
</div>

<script>
   $(document).ready(function () {
            $("#addpayment").submit(function (e) {
                total = $("#total").val();
                val = $("#val").val();
                if (val != "" && val > 0) {
                    console.log(total);
                    if (parseFloat(val) <= parseFloat(total)) {
                        // procesamos
                        go = confirm("Esta seguro que desea continuar?");
                        if (!go) {
                            e.preventDefault();
                        }
                    } else {
                        alert("No es posible ingresar un pago mayor a la deuda total.")
                        e.preventDefault();
                    }

                } else {
                    alert("Debes ingresar un valor mayor que 0.")
                    e.preventDefault();
                }
            });
        }); 


        
$( '.micheckbox' ).on( 'click', function() {
    if( $(this).is(':checked') ){
        // Hacer algo si el checkbox ha sido seleccionado
        alert("El checkbox con valor " + $(this).val() + " ha sido seleccionado");
    } else {
        // Hacer algo si el checkbox ha sido deseleccionado
        alert("El checkbox con valor " + $(this).val() + " ha sido deseleccionado");
    }
});

        function valida(id) {
            var total = parseFloat($("#total" + id).val());
            var saldo = parseInt($("#id_saldo").val());
            var cliente = $("#idcliente").val();
            var stock_id = $('#stock_id').val();
            var idanticipo = $("#id_abono").val();
            var uuid_factura = $("#uuid_factura").val();
            var cant_anticipo = $("#anticipo_seleccionado").val();
        
            if(idanticipo == ""){
            alert ("Para poder liquidar una remision con anticipo, tienes que seleccionar un anticipo de la lista del cliente, de lo contrario puedes agregar ingresar el anticipo en la opcion - Modulo Contabilidad - Lisado de Anticipos ");
            } 
             else  if(cant_anticipo >= total){
             $.ajax({
             type: "POST",
             url: 'core/app/json/remiciones.php',
             data: "idcliente=" + cliente + "&total=" + total + "&saldo=" + saldo + "&idventa=" + id + "&stock_id=" + stock_id +"&idanticipo=" + idanticipo + "&cant_anticipo=" + cant_anticipo ,
             success: function (data) {
            
             alert("EL ABONO SELECCIONADO PUEDE CUBRIR EL MONTO TOTAL DEL CRÉDITO, POR LO CUAL PASARA COMO CREDITO LIQUIDADO Y LO PODRAS VER EN EL LISTADO DE CREDITOS LIQUIDADOS DEL DÍA."); 
             
             location.reload();
            }
            }); 
         
            } else {
             alert("EL ABONO SELECCIONADO, NO CUENTA CON LA CAPACIDAD DE LIQUIDAR EL CREDITO, POR LO CUAL SE INGRRESARA COMO UN ABONO AL CREDITO");
        
            $.ajax({
             type: "POST",
             url: 'core/app/json/abono_remiciones.php',
             data: "idcliente=" + cliente + "&total=" + total + "&saldo=" + saldo + "&idventa=" + id + "&stock_id=" + stock_id +"&idanticipo=" + idanticipo + "&cant_anticipo=" + cant_anticipo ,

             success: function (data) {
                 console.log(data);
             alert("ABONO APLICADO CORRECTAMENTE A LA REMISIÓN....."); 
             location.reload(); 
            }
            }); 
       
            }
            } // llave de la funcion principal.
</script>
</section>