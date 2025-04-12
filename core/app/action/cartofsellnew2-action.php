<?php
 $symbol = ConfigurationData::getByPreffix("currency")->val;
$iva_name = ConfigurationData::getByPreffix("imp-name")->val;
$iva_val = ConfigurationData::getByPreffix("imp-val")->val;
?>
<?php
if(!isset($_SESSION["cart"])):
?>
<p class="alert alert-warning">
  No hay datos.
  <br>
  Debes buscar y agregar productos.
  </p>
<?php endif; ?>
<?php 
//print_r($_SESSION);
if(isset($_SESSION["errors"])):?>
<h2>Errores</h2>
<p></p>
<table class="table table-bordered table-hover">
<tr class="danger">
	<th>Codigo</th>
	<th>Producto</th>
	<th>Mensaje</th>
</tr>
<?php foreach ($_SESSION["errors"]  as $error):
$product = ProductData::getById($error["product_id"]);
?>
<tr class="danger">
	<td><a href="./?view=editcart"><?php echo $product->id; ?></a></td>
	<td><?php echo $product->name; ?></td>
	<td><b><?php echo $error["message"]; ?></b></td>
</tr>

<?php endforeach; ?>
</table>
<?php
unset($_SESSION["errors"]);
 endif; ?>


<!--- Carrito de compras :) -->
<?php if(isset($_SESSION["cart"])):
$total = 0;
?>
<div class="row">
<div class="col-md-12">

<div class="card">
  <div class="card-header">Datos de la Venta</div>
  <div class="card-body">


<form method="post" class="form-horizontal" id="processsell" enctype="multipart/form-data">

<div class="row">
<div class="col-md-12">
<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab">Principal</a></li>
    <li role="presentation"><a href="#extra"  aria-controls="extra" role="tab" data-toggle="tab">Extra</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="main">
<div class="row">

<div class="col-md-12">
    <label class="control-label">No. Factura</label>
    <div class="col-lg-12">
      <input type="text" name="invoice_code" class="form-control"  placeholder="No. Factura">
    </div>
  </div>
  </div>
<div class="row">

<div class="col-md-6">
    <label class="control-label">Almacen</label>
    <div class="col-lg-12">
    <h4 class=""><?php 
    echo StockData::getPrincipal()->name;
    ?></h4>
    </div>
  </div>

  </div>
<div class="row">



<div class="col-md-8">
    <label class="control-label">Cliente</label>
    <div class="col-lg-12">
    <?php 
$clients = PersonData::getClients();
    ?>
    <select name="client_id" id="client_id" class="form-control">
    <option value="">-- NINGUNO --</option>
    <?php foreach($clients as $client):?>
      <option value="<?php echo $client->id;?>"><?php echo $client->name." ".$client->lastname;?></option>
    <?php endforeach;?>
      </select>
    </div>
  </div>

<div class="col-md-4">
    <label class="control-label">Agregar</label>
    <div class="col-lg-12">

<!-- Button trigger modal -->
<button type="button" class="btn btn-secondary btn-block" data-coreui-toggle="modal" data-coreui-target="#myModal">
  <i class="bi bi-person-add"></i>  Cliente
</button>


  </div>
  </div>
</div>
<div class="row">

<div class="col-md-6">
    <label class="control-label">Pago</label>
    <div class="col-lg-12">
    <?php 
$clients = PData::getAll();
    ?>
    <select name="p_id" id="p_id" class="form-control">
    <?php foreach($clients as $client):?>
      <option value="<?php echo $client->id;?>"><?php echo $client->name;?></option>
    <?php endforeach;?>
      </select>
    </div>
  </div>
<div class="col-md-6">
    <label class="control-label">Entrega</label>

    <div class="col-lg-12">
    <?php 
$clients = DData::getAll();
    ?>
    <select name="d_id" class="form-control">
    <?php foreach($clients as $client):?>
      <option value="<?php echo $client->id;?>"><?php echo $client->name;?></option>
    <?php endforeach;?>
      </select>
    </div>
  </div>

</div>
<div class="row">

<div class="col-md-12">
    <label class="control-label">Forma de pago</label>
    <div class="col-lg-12">
    <?php 
$clients = FData::getAll();
    ?>
    <select name="f_id" id="f_id" class="form-control">
    <?php foreach(FData::getAll() as $client):?>
      <option value="<?php echo $client->id;?>"><?php echo $client->name;?></option>
    <?php endforeach;?>
      </select>
    </div>
  </div>

</div>
<div class="row">
      <input type="hidden" value="<?php echo $discount; ?>" readonly name="discount" class="form-control" required value="0" id="discount" placeholder="Descuento">

<!-- <div class="col-md-6">
    <label class="control-label">Descuento </label>
    <div class="col-lg-12">
     <input type="hidden" name="discount_percen" class="form-control" required value="0" id="discount_percen" placeholder="Descuento %">
    </div>
  </div>
   -->
 <div class="col-md-12">
  <div class="row">
    <label class="control-label">Efectivo <?php echo $symbol; ?></label>
    <div class="col-md-6">
      <input type="text" name="money" value="0" style="font-size: 20px ;" class="form-control" id="money" placeholder="Efectivo">
    </div>
    <div class="col-md-2">
      <button type="button" class="btn btn-success" id="click_money" data-coreui-toggle="tooltip" data-coreui-placement="bottom" title="Pagar Completo"><i class="bi-cash"></i></button>
    </div>
    <div class="col-md-2">
      <button type="button" class="btn btn-success" id="click_0" data-coreui-toggle="tooltip" data-coreui-placement="bottom" title="Pagar Completo">0</button>
    </div>
</div>
  <div class="row">


    <div class="col-md-3">
      <button type="button" class="btn btn-success" id="click_100" data-coreui-toggle="tooltip" data-coreui-placement="bottom" title="Pagar Completo">100</button>
    </div>
    <div class="col-md-3">
      <button type="button" class="btn btn-success" id="click_200" data-coreui-toggle="tooltip" data-coreui-placement="bottom" title="Pagar Completo">200</button>
    </div>
    <div class="col-md-3">
      <button type="button" class="btn btn-success" id="click_500" data-coreui-toggle="tooltip" data-coreui-placement="bottom" title="Pagar Completo">500</button>
    </div>
    <div class="col-md-3">
      <button type="button" class="btn btn-success" id="click_1000" data-coreui-toggle="tooltip" data-coreui-placement="bottom" title="Pagar Completo">1000</button>
    </div>
</div>
  </div>
  </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="extra">

<div class="row">

<div class="col-md-12">
    <div class="">
    <label class="control-label">Comentarios</label>
      <textarea name="comment"  placeholder="Comentarios" class="form-control" rows="10"></textarea>
    </div>
  </div>
  </div>

    </div>
  </div>

</div>
</div>
</div>

<script>
//  $("#discount_percen").keyup(function(){
  //  $("#discount").val( ($("#discount_percen").val()/100)*<?php echo $total; ?>  );
 // });
</script>


<?php
//echo $discount;
$subtotal=$total-$discount;
$iva_calc=0;
if(Core::$plus_iva){
  $iva_calc = ($subtotal) *($iva_val/100);
}
$total=$subtotal+$iva_calc;
?>

      <input type="hidden" name="total" value="<?php echo $total; ?>" class="form-control" placeholder="Total">
      <div class="clearfix"></div>
<br>
  <div class="row">
<div class="col-md-12">
<div class="box box-primary">
<?php if(Core::$plus_iva==0):?>
<table class="table table-bordered">
<tr>
  <td><p>Descuento</p></td>
  <td><p><b><?php echo $symbol; ?> <?php echo number_format($discount,2,'.',','); ?></b></p></td>
</tr>
<tr>
  <td><p>Subtotal</p></td>
  <td><p><b><?php echo $symbol; ?> <?php echo number_format($subtotal/(1 + ($iva_val/100) ),2,'.',','); ?></b></p></td>
</tr>
<tr>
  <td><p><?php echo $iva_name." (".$iva_val."%) ";?></p></td>
  <td><p><b><?php echo $symbol; ?> <?php echo number_format(($subtotal/(1 + ($iva_val/100) )) *($iva_val/100),2,'.',','); ?></b></p></td>
</tr>
<tr>
  <td><p>Total</p></td>
  <td><p><b><?php echo $symbol; ?> <?php echo number_format($subtotal,2,'.',','); ?></b></p></td>
</tr>

</table>
<?php elseif(Core::$plus_iva==1):
$iva_calc = ($subtotal) *($iva_val/100);
  ?>
<table class="table table-bordered">
<tr>
  <td><p>Descuento</p></td>
  <td><p><b><?php echo $symbol; ?> <?php echo number_format($discount,2,'.',','); ?></b></p></td>
</tr>
<tr>
  <td><p>Subtotal</p></td>
  <td><p><b><?php echo $symbol; ?> <?php echo number_format($subtotal ,2,'.',','); ?></b></p></td>
</tr>
<tr>
  <td><p><?php echo $iva_name." (".$iva_val."%) ";?></p></td>
  <td><p><b><?php echo $symbol; ?> <?php echo number_format($iva_calc,2,'.',','); ?></b></p></td>
</tr>
<tr>
  <td><p>Total</p></td>
  <td><p><b><?php echo $symbol; ?> <?php echo number_format($subtotal+$iva_calc,2,'.',','); ?></b></p></td>
</tr>

</table>
<?php endif; ?>

</div>
<script type="text/javascript">
  $("#click_money").click(function(){
    $("#money").val("<?php echo $subtotal+$iva_calc; ?>");
  })
 $("#click_0").click(function(){
    $("#money").val(0);
  })
  $("#click_100").click(function(){
    $("#money").val( parseFloat($("#money").val())+parseFloat("100") );
  })
  $("#click_200").click(function(){
    $("#money").val( parseFloat($("#money").val())+parseFloat("200") );
  })
    $("#click_500").click(function(){
    $("#money").val( parseFloat($("#money").val())+parseFloat("500") );
  })
      $("#click_1000").click(function(){
    $("#money").val( parseFloat($("#money").val())+parseFloat("1000") );
  })
</script>

          <input name="is_oficial" type="hidden" value="1">

<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <div class="checkbox">
        <label>
    <a href="index.php?view=clearcart" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a>
        <button class="btn btn-primary"><i class="glyphicon glyphicon-check"></i> Finalizar Venta</button>
        </label>
      </div>
    </div>
  </div>
</form>
</div>
<!-- ---------------------->


</div>

<!-------------------------------------------------->
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar Cliente</h5>
        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
<form class="form-horizontal" method="post" id="addclient" action="index.php?view=addclient" role="form">


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre*</label>
    <div class="col-md-12">
      <input type="text" name="name" class="form-control" required id="name" placeholder="Nombre">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Apellido*</label>
    <div class="col-md-12">
      <input type="text" name="lastname" required class="form-control" id="lastname" placeholder="Apellido">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">RFC/RUT</label>
    <div class="col-md-12">
      <input type="text" name="no" class="form-control" id="no" placeholder="RFC/RUT">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Direccion</label>
    <div class="col-md-12">
      <input type="text" name="address1" class="form-control" id="address1" placeholder="Direccion">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Email</label>
    <div class="col-md-12">
      <input type="text" name="email1" class="form-control" id="email1" placeholder="Email">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Telefono</label>
    <div class="col-md-12">
      <input type="text" name="phone1" class="form-control" id="phone1" placeholder="Telefono">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-4 control-label" >Activar Credito</label>
    <div class="col-md-12">
<div class="checkbox">
    <label>
      <input type="checkbox" name="has_credit">
    </label>
  </div>
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Limite de credito</label>
    <div class="col-md-12">
      <input type="text" name="credit_limit" class="form-control" id="" placeholder="Limite de credito">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-4 control-label" >Activar Acceso </label>
    <div class="col-md-6">
<div class="checkbox">
    <label>
      <input type="checkbox" name="is_active_access">
    </label>
  </div>
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Password</label>
    <div class="col-md-12">
      <input type="password" name="password" class="form-control" id="password" placeholder="Password">
<p class="text-muted">Acceso en (http://localhost/inventio-max/?view=clientaccess) con Email, Password y Acceso Activado</p>
    </div>
    </div>

  <p class="alert alert-info">* Campos obligatorios</p>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-primary">Agregar Cliente</button>
    </div>
  </div>
</form>

<script type="text/javascript">
  $("#addclient").submit(function(e){
    e.preventDefault();
    $.post("./?view=addclient",$("#addclient").serialize(),function(d){
      alert("Agregado, puedes cerrar la ventana!");

        $.get("./?action=getclients","",function(d2){
            $("#client_id").html(d2);
        });
    document.getElementById("addclient").reset();
    });

  })
</script>
      </div>

    </div>
  </div>
</div>
<!-- Modal -->

<!-- FORM -->
<!-- /FORM -->


<!-------------------------------------------------->
</div>

</div>

<div class="row">
<div class="col-md-9">

<div class="card">
  <div class="card-header">Listado de productos</div>
  <div class="card-body">

<div class="box box-primary">
<table class="table datatable table-bordered table-hover">
<thead>
  <th style="width:50px;">Codigo</th>
  <th>Producto</th>
  <th style="width:30px;">Cantidad</th>
  <th style="width:30px;">Existencias</th>
<!--  <th style="width:30px;">Unidad</th> -->
<!--  <th style="width:90px;">Precio Unitario</th>
  <th style="width:90px;">Precio Total</th>
  <th style="width:90px;">Descuento</th> -->
  <th style="width:190px;">Precio</th>
  <th style="width:190px;">Total</th>
  <th ></th>
</thead>
<?php 
//print_r($_SESSION['cart']); 
$discount = 0;
foreach($_SESSION["cart"] as $p):
$product = ProductData::getById($p["product_id"]);
$qxa = OperationData::getQByStock($p["product_id"],StockData::getPrincipal()->id);
$price = $product->price_out;
    $px = PriceData::getByPS($product->id,StockData::getPrincipal()->id);
    if($px!=null){ $price = $px->price_out; }

?>
<tr <?php if($qxa<$p["q"]){ echo "class='bg-danger'";}?>>

  <td ><?php echo $product->code; ?></td>
<!--  <td><?php echo $product->unit; ?></td> -->
  <td>



<!-- Button trigger modal -->
<a style="color: blue; text-decoration: underline;" data-coreui-toggle="modal" data-coreui-target="#myModal<?php echo $p["product_id"]?>">
  <?php echo $product->name; ?>
</a>


      

<!-- Modal -->
<div class="modal fade" id="myModal<?php echo $p["product_id"]?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <!--
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
    -->

      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo $product->code; ?> - <?php echo $product->name; ?></h5>
        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="updatecart<?php echo $p["product_id"]; ?>">
          <input type="hidden" name="product_id" value="<?php echo $p["product_id"];?>">
  <!--<div class="form-group">
    <label for="exampleInputEmail1">Cantidad a Granel (Insertar Monto para calcular la cantidad)</label>
    <input type="text" name="qgranel" id="qgranel<?php echo $p['product_id'];?>" value="0" required class="form-control" id="exampleInputEmail1" placeholder="Cantidad">
  </div>-->
  <div class="form-group">
    <label for="exampleInputEmail1">Cantidad</label>
    <input type="number" max="<?php echo $qxa; ?>" min="1" name="q" id="q<?php echo $p['product_id'];?>" value="<?php echo $p["q"];?>" required class="form-control" id="exampleInputEmail1" placeholder="Cantidad">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Precio</label>
    <select class="form-control" name="price_out" id="price_out<?php echo $p['product_id'];?>">
      <option value="<?php echo $product->price_out; ?>" <?php if($p["price"]==$product->price_out){ echo "selected";}?>><?php echo $product->price_out; ?></option>
      <option value="<?php echo $product->price_out2; ?>" <?php if($p["price"]==$product->price_out2){ echo "selected";}?>><?php echo $product->price_out2; ?></option>
      <option value="<?php echo $product->price_out3; ?>" <?php if($p["price"]==$product->price_out3){ echo "selected";}?>><?php echo $product->price_out3; ?></option>
    </select>
    <!--<input type="text" name="price_out" id="price_out<?php echo $p['product_id'];?>" value="<?php echo $p["q"];?>" required class="form-control" id="exampleInputEmail1" placeholder="Precio">-->
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Precio (Opcional)</label>
    <input type="text" name="price_opt" id="price_opt<?php echo $p['product_id'];?>" value="<?php echo $p["price"];?>"  class="form-control" id="exampleInputEmail1" placeholder="Precio">
  </div>
  <script type="text/javascript">
    $(document).ready(function(){
      $("#qgranel<?php echo $p['product_id'];?>").change(function(){
        v = $("#qgranel<?php echo $p['product_id'];?>").val();


        $("#q<?php echo $p['product_id'];?>").val(v/<?php echo $product->price_out; ?>);
      });

    });
  </script>
  <div class="form-group">
    <label for="exampleInputEmail1">Descuento ($ por producto)</label>
    <input type="text" name="discount"  value="<?php echo $p["discount"];?>"  required class="form-control" id="exampleInputEmail1" placeholder="Descuento">
  </div>
<br>
  <button type="submit" class="btn btn-secondary">Actualizar</button>
</form>
<script type="text/javascript">
  $("#myModal<?php echo $p["product_id"]?>").prependTo("body");


  $("#updatecart<?php echo $p["product_id"]; ?>").submit(function(e){
    e.preventDefault();
//    alert("X");
    $.get("./?action=updatecartnew", $("#updatecart<?php echo $p["product_id"]; ?>").serialize(), function(data){
      console.log(data)


// RELOAD CARTOFSELL
        $.get("./?action=cartofsellnew",null,function(data2){
          $("#cartofsell").html(data2);
        });


    });
  });
</script>
      </div>

    </div>
  </div>
</div>





    </td>
      <td ><?php echo $p["q"]; ?></td>
      <td ><?php echo $qxa; ?></td>
<!--  <td><b><?php echo $symbol; ?> <?php echo number_format($p["price"],2,".",","); ?></b></td>
  <td><b><?php echo $symbol; ?> <?php  $pt = $p["price"]*$p["q"]; $total +=$pt; echo number_format($pt,2,".",","); ?></b></td>
  <td><b><?php echo $symbol; ?> <?php echo number_format($p["discount"] * $p["q"],2,".",","); ?></b></td> -->
  <td><b><?php echo $symbol; ?> <?php echo number_format($p["price"],2,".",","); ?></b></td>
  <td><b><?php echo $symbol; ?> <?php echo number_format($pt-($p["discount"] * $p["q"]),2,".",","); $discount+=($p["discount"] * $p["q"]); ?></b></td>

  <td style="width:30px;"><a id="clearcart-<?php echo $product->id; ?>" class="btn btn-sm btn-danger"><i class="bi-trash"></i></a>

<script>
  $("#clearcart-<?php echo $product->id; ?>").click(function(){
    $.get("index.php?view=clearcart","product_id=<?php echo $product->id; ?>",function(data){
        $.get("./?action=cartofsellnew",null,function(data2){
          $("#cartofsell").html(data2);
        });
    });
  });
</script>

  </td>
</tr>

<?php endforeach; ?>
</table>
</div>
</div>
</div>
<!-- ---------------------->
</div>




<script>
	$("#processsell").submit(function(e){
		discount = <?php echo $discount; ?>;
    p = $("#p_id").val();
    client = $("#client_id").val();
		money = $("#money").val();
    if(money!=""){
    if(p!=4){
		if(money<(<?php echo $total;?>-discount)){
			alert("Efectivo insificiente!");
			e.preventDefault();
		}else{
			if(discount==""){ discount=0;}
//      alert(<?php echo $total; ?>);
			go = confirm("Cambio: $"+(money-(<?php echo $total;?> ) ) );
			if(go){
      e.preventDefault();
        $.post("./index.php?action=processsell",$("#processsell").serialize(),function(data){
          $.get("./?action=cartofsell",null,function(data2){
            $("#cartofsell").html(data);
            $("#show_search_results").html("");
          });
        });

      }
				else{e.preventDefault();}
		}
    }else if(p==4){ // usaremos credito
      if(client!=""){
        // procedemos
        cli=Array();
        lim=Array();
        cur=Array();
        <?php 
        foreach(PersonData::getClients() as $cli){
          echo " cli[$cli->id]=$cli->has_credit ;";
          echo " lim[$cli->id]=$cli->credit_limit ;";
$sells = SellData::getCreditsByClientId($cli->id);

$totalx=0;
foreach ($sells as $sell) {
$tx = PaymentData::sumBySellId($sell->id)->total;
if($tx>0){
$totalx+=$tx;
}
}
//echo $totalx;
          echo " cur[$cli->id]=$totalx ;";


        }
        ?>
//console.log(lim[client]);
//console.log(cur[client]+(<?php echo $total; ?>-discount));
        if(cli[client]==1){
          // si el cliente tiene credito entonces procedemos a hacer la venta a credito :D
          e.preventDefault();
if(lim[client]>=cur[client]+(<?php echo $total; ?>-discount)){
          $.post("./index.php?action=processsell",$("#processsell").serialize(),function(data){
            $.get("./?action=cartofsell",null,function(data2){
              $("#cartofsell").html(data);
              $("#show_search_results").html("");
            });
          });
}else{
            alert("El cliente ha alcanzado el limite de credito, no se puede procesar la venta!");

}
        }else{
          // el cliente no tiene credito
          alert("El cliente seleccionado no cuenta con credito!");
          e.preventDefault();

        }
      }else{
        // 
        alert("Debe seleccionar un cliente!");
        e.preventDefault();
      }

    }
  }else{
    alert("Campo de pago vacio")
    e.preventDefault();
  }
	});
</script>
</div>
</div>

<?php endif; ?>

<script type="text/javascript">
  $(document).ready(function(){
        $(".datatable").DataTable()

      });
</script>