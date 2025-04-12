<section class="content">
<!--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script> -->
<head>
  
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<!--PRIMERO LLAMAMOS A JQUERY-->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!--LUEGO A SELECT2-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<div class="row">
	<div class="col-md-12">

<div class="card">
  <div class="card-body">


	<h1>Registro De Abono o Anticipo</h1>
	<br>
		<form class="form-horizontal" method="post" id="addcategory" action="index.php?action=addabono" role="form">


    <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Elige la opción a realizar</label>
    <div class="col-md-6">
    <select name="id_opcion" id="id_opcion" class="form-control" requiered>
                 <option value ="0">Selecciona</option>
                 <option value ="1">Registrar Anticipo a Cliente</option>
                 <option value ="2">Registrar Abono a Cliente</option> 
                 </select>
       </div>
    </div>
    
 
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Selecciona El Cliente</label>
    <div class="col-md-2">
                    <select class="client_p form-control" style="width:400px" name="client_p" id="client_p"></select>
                    <script type="text/javascript">
                  $('.client_p').select2({
                   placeholder: 'Escribe Nombre Del Cliente',
                  ajax: {
                  url: 'ajax.php',
                  dataType: 'json',
                  delay: 250,
                  processResults: function (data) {
                  return {
                  results: data
                  };
                  },
                cache: true
                  }
                  });
                </script>
				</script>
				</div>
                    
				 <script>
				 $("#client_p").change(function(){
  				var user_id = $(this).val();
				  console.log(user_id);
			 	$.ajax({
                type:'POST',
                url:'./core/app/action/trae_cliente_anticipo.php',
                dataType: "json",
                data:{user_id:user_id},
                success:function(data){
                if(data.status == 'ok'){
                    $('#idclient').val(data.result.id);
                    $('#name').val(data.result.id);
                    $('#nombre_cliente').val(data.result.name);
                    $('.user-content').slideDown();
                     }else{
                    $('.user-content').slideUp();
                    alert("El cliente buscado no se encuentra registrado en el sistema");
                } 
            }
        });
         
			}); //llave funcion
             </script>
	</script>

            <input id="name" name="name" type="hidden">
            <input id="nombre_cliente" name="nombre_cliente" type="hidden">
         <br>
            <div class="form-group">
            <div class="col-md-6">
             <label for="inputEmail1" class="col-lg-3 control-label">Ingresa el Monto</label>
							<input type="text" name="cantidad"  id="cantidad" class="form-control" required>
						</div>
            

            <div class="form-group">
            <div class="col-md-6">
							<label>Forma De Pago</label>
              <select name="forma_pago" id="forma_pago" class="form-control" requiered>
                 <option value ="0">Elige un opción</option>
                 <option value="01">01 - Efectivo</option>
                 <option value="02">02 - Cheque nominativo</option>
                 <option value="03">03 - Transferencia electronica</option>
                 <option value="04">04 - Tarjeta de Credito</option>
                 <option value="05">05 - Monedero electronico</option>
                 <option value="06">06 - Dinero electronico</option>
                 <option value="08">08 - Vales de Despensa</option>
                 <option value="12">12 - Dacion en pago</option>
                 <option value="13">13 - Pago por subrogacion</option>
                 <option value="14">14 - Pago por consignacion</option>
                 <option value="15">15 - Condonacion</option>
                 <option value="17">17 - Compensacion</option>
                 <option value="23">23 - Novacion</option>
                 <option value="24">24 - Confusion</option>
                 <option value="25">25 - Remision de deuda</option>
                 <option value="26">26 - Prescripcion o caducidad</option>
                 <option value="27">27 - A satisfaccion del acreedor</option>
                 <option value="28">28 - Tarjeta de debito</option>
                 <option value="29">29 - Tarjeta de servicios</option>
                 <option value="30">30 - Aplicacion de anticipos</option>
                 <option value="31">31 - Intermediario pagos</option>
                 <option value="99">99 - Por definir</option>

                 </select>
						</div>
            

            
            <div class="form-group">
            <div class="col-md-6">
							<label>RFC Emisor Cuenta Ordenante</label>
              <input type="text" name="RfcEmisorCtaOrd"  id="RfcEmisorCtaOrd" class="form-control"  placeholder ="BNM840515VB1" maxlength="12" required>
						</div>

            
            <div class="form-group">
            <div class="col-md-6">
							<label>Nombre Banco Ordenante</label>
              <input type="text" name="NomBancoOrdExt"  id="NomBancoOrdExt" class="form-control" placeholder ="BANAMEX" maxlength = "15" required>
						</div>

            
            <div class="form-group">
            <div class="col-md-6">
							<label>Cuenta Ordenante</label>
              <input type="text" name="CtaOrdenante"  id="CtaOrdenante" class="form-control" placeholder ="1537621219" maxlength = "10" required>
						</div>

           
            <div class="form-group">
            <div class="col-md-6">
							<label>RFC Emisor Cuenta Beneficiario</label>
              <input type="text" name="RfcEmisorCtaBen"  id="RfcEmisorCtaBen" class="form-control" placeholder ="FNM840515VB1" maxlength = "12"  required>
						</div>

            
            <div class="form-group">
            <div class="col-md-6">
							<label>Cuenta Beneficiario</label>
              <input type="text" name="CtaBeneficiario"  id="CtaBeneficiario" class="form-control" placeholder ="0181066324" maxlength = "12" required>
						</div>

           
            <div class="form-group">
            <div class="col-md-6">
							<label>Referencia Deposito</label>
							<input type="text" name="referencia_deposito"  id="referencia_deposito" class="form-control" placeholder ="xxx-xxxx-xxx"  required>
						</div>

            <div class="form-group">
            <div class="col-md-6">
		        <label>Razón Social</label>
            <select name="stock_id" id="stock_id" class="form-control">
            <?php foreach(RazonData::getAll() as $stock):?>
              <option value="<?php echo $stock->id; ?>"><?php echo $stock->razonsocial; ?></option>
            <?php endforeach; ?>
          </select>

          <br>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-primary" onclick="return ingresar();">Agregar Movimiento</button>
    </div>
  </div>
</form>

<script>
function ingresar() {
if (confirm("¿Desas aplicar el Anticipo al Cliente?, al realizarlo se actualizará el monto total de sus anticipos y se generara un Registro")) {
return true;
} else {
return false;

}
}
    </script>

  </div>
  </div>


	</div>
</div>
</section>
