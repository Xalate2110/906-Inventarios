

<section class="content">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $("#product_id").select2({
		dropdownParent: $('#myModal')
	});
});</script>

  <div class="row">
  <div class="col-md-5">
  <h3>Punto De Venta - Grupo d</h3>
</div>
</div>
  <div class="row">

<div class="col-md-12">
<!-- -->
        <div class="row">
      <div class="col-md-3">
        <input type="hidden" name="view" value="sell">
    
        <select class="form-control select2clients" name="select_client" id="select_client"  onchange = "myFunction()" >
     
      <?php 
      $cli=null;
      if(isset($_SESSION["selected_client_id"])):
       $cli = PersonData::getById($_SESSION['selected_client_id']);
      ?>

      <option value="<?php echo $cli->id; ?>"><?php echo utf8_encode($cli->name." ".$cli->lastname);?></option>
      <?php endif; ?>
       <option value="1">PUBLICO EN GENERAL</option>
       </select>
      </div>
      <?php if($cli!=null):
      ?>
      <div class="col-md-1">
        <input type="text" id="var_credit_limit" class="form-control" value="<?php echo $cli->credit_limit; ?>">
      </div>
      <div class="col-md-1">
    <input type="checkbox" id="var_has_credit" <?php if($cli->has_credit==1){ echo "checked";}?> >
      </div>
     <?php else:?>
      <div class="col-md-1">
        <input type="text" id="var_credit_limit" class="form-control" disabled>
      </div>
      <div class="col-md-1">
        <input type="checkbox" id="var_has_credit" disabled>
      </div>
      <?php endif; ?>
      <div class="col-md-1">
  <!-- Button trigger modal -->
  <?php $id_almacen =  StockData::getPrincipal()->id; ?>
      <a href ="index.php?view=clients&opt=all&stock=<?php echo $id_almacen; ?>" button type="button" title="Preciona para dar de alta un cliente." class="btn btn-secondary btn-block"> <i class="bi bi-person-add" placeholder ="Agregar Cliente"></i></button> </a></div>
      <div class="col-md-2">
  <!-- Button trigger modal -->
  <button type="button" class="btn btn-dark" title="Preciona para consultar precios de productos" data-coreui-toggle="modal" data-coreui-target="#myModal">Consultar Precios</button></div>      
      <br><br>
      <div class="col-md-1">
  <!-- Button trigger modal -->
  <button type="button" id="test_button" class="btn btn-success">A</button></div>      
      <br><br>

    </div>
<script>
  $("#test_button").click(function(){ $.get("./?action=cartofsellnew","",function(data){ $("#cartofsell").html(data); });})
</script>
      <div class="row">
      <div class="col-md-6">
        <input type="hidden" name="view" value="sell">
    <select class="form-control select2products" name="select_product" id="select_product">
    <option>Escribe el nombre del producto</option>
    </select>
      </div>

      <input type="hidden" name="product_name" id="product_name" value="">
<form id="searchp" style="display:inline;" class="col-md-2">
        <input type="hidden" name="view" value="sell">
        <input type="text" id="product_code" name="product_code" autofocus class="form-control" placeholder="Codigo de Barra">
     </form>

    <script type="text/javascript">
      $(document).ready(function(){
        $("#select_client").select2({
    minimumInputLength: 1,
          ajax: {
        url:"./?action=select2clients",
        dataType: 'json',
        type: "GET",
        quietMillis: 50,
        data: function (term) {
          console.log(term)
            return {
                term: term
            };
        },
        processResults: function (data) {
        return {
                results: $.map(data, function (item) {
                    return {
                        text: item.name,
                        slug: "",
                        id: item.id
                    }
                })
            };
        }
    }

     });


     /// CHANGE METHODS
     $("#var_credit_limit").keyup(function(){
      $.get("./?action=updatecreditlimit","credit_limit="+$("#var_credit_limit").val(), function(data){
      });
     $.get("./?action=cartofsellnew",null,function(data5){

      $("#cartofsell").html(data5);
      });
    });

     $("#var_has_credit").change(function(){
      vx=0;
      val = ($("#var_has_credit").is(":checked"));
      if(val){vx=1;}
      //alert(val);
//      if($("#var_has_credit").val())
      $.get("./?action=updatehascredit","has_credit="+vx, function(data){
      console.log(data);
      })
     });

     $.get("./?action=cartofsellnew",null,function(data5){
  
     $("#cartofsell").html(data5);
        });
    });


      function myFunction() {
      $("#credito").modal('show');

      $.get("./?action=getclientjson","id="+$("#select_client").val(), function(datax){
        data2 = JSON.parse(datax);
        $("#var_credit_limit").val(data2.credit_limit);
        $("#var_credit_limit").prop("disabled",false);
        if(data2.has_credit==1){
          $("#var_has_credit").prop("checked",true);
          $("#var_has_credit").prop("disabled",false);
        }else{
          $("#var_has_credit").prop("checked",false);
          $("#var_has_credit").prop("disabled",false);
 
        }
      });
        
      $.get("./?action=getcreditsbyclient","",function(data){
        $("#credit_content").html(data);
        });
      }

     
      $(document).ready(function(){
        $(".select2products").select2({
    minimumInputLength: 1,
          ajax: {
        url:"./?action=select2products",
        dataType: 'json',
        type: "GET",
        quietMillis: 50,
        data: function (term) {
          console.log(term)
            return {
                term: term
            };
        },
        processResults: function (data) {
          console.log("hola")
          console.log(data)
            return {
                results: $.map(data, function (item) {
                    return {
                        text: item.name,
                        slug: "",
                        id: item.id
                    }
                })
            };
        }
    }
       
        });
      });

      $("#select_product").change(function(){
         $.get("./?action=searchproductnew","product_id="+$(this).val()+"&go=byid",function(data){
        $("#show_search_results").html(data);
        $.get("./?action=cartofsellnew",null,function(data2){  
        $("#cartofsell").html(data2);
        $("#select_product").val("")
        });
    });
      })
      
      $("#select_client").change(function(){
        //e.preventDefault();
        //alert($(this).val())
        ///
  
        
       $.get("./?action=setselectedclient","client_id="+$(this).val()+"&go=byid",function(data){
       });
      })

    </script>
<!-- -->
</div></div>


<div class="modal fade" id="credito" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clave">Alerta del Sistema</h5>
				<button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
               </button>
            </div>
            <div class="modal-body">
            <div id="credit_content"></div>
        

            </div> <!-- CUERPO DEL MODAL-->
            <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-coreui-dismiss="modal">Cerrar Ventana</button>
            </div>
        </div>
    </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div id="show_search_results"></div>

  </div>
</div>
<br>
  <div class="row">
    <div class="col-md-12">
      <div id="cartofsell"></div>
    </div>
  </div>
</section>

<script>
//jQuery.noConflict();

$(document).ready(function(){
  $("#searchp").on("submit",function(e){
    e.preventDefault();

    code = $("#product_code").val();
    name = $("#product_name").val();
    if(name!=""){
alert(name);
    $.get("./?action=searchproductnew",$("#searchp").serialize()+"&go=name",function(data){
      $("#show_search_results").html(data);
      console.log("e:"+data);
    });
    $("#product_name").val("");
    $("#category_id").val("");
    }
    else if(code!=""){
    $.get("./?action=searchproductnew",$("#searchp").serialize()+"&go=code",function(data){
      $("#show_search_results").html(data);


$.get("./?action=cartofsellnew",null,function(data2){
$("#cartofsell").html(data2);
});

    });


    $("#product_code").val("");
    $("#category_id").val("");
    }else {
      $("#show_search_results").html("");
    }

  });
  });

  
/*
$(document).ready(function(){
    $("#product_code").keydown(function(e){
        if(e.which==17 || e.which==74){
            e.preventDefault();
        }else{
            console.log(e.which);
        }
    })
});
*/
</script>
<script>/*
$(document).ready(function(){
$.get("./?action=cartofsellnew",null,function(data){
  console.log(data)
$("#cartofsell").html(data);
});
});/*
</script>
     
<!-- Modal -->
<div class="modal fade" id="myModal"  aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
		<h5 class="modal-title" id="myModal">Consulta De Precios</h5>
      </div>
      
	  
      <div class="modal-body">
	    <style>
	    .form-select{
	    width:750%;}
	    </style>

      <div class="form-group">
       <b> <label for="inputEmail1" class="col-lg-20 control-label">Escanea Codigo De Barras o Escribe la Descripción del Producto</label></b>
       <br>
        <div class="col-md-12">
      <?php 
      $product= array();
      $stock = StockData::getPrincipal()->id; 
      ?>
<input type="text" class="form-control" id="busca_producto">

	</div>
  </div>
  <br>
  <div id="mostrar_datos"></div>

<script>
$("#busca_producto").keyup(function(){
  busca = $(this).val();
 if(busca.length>3){
  $.get("./?action=consultaprecio","producto="+busca,function(data){
  $("#mostrar_datos").html(data);
  })}
});
</script>
<input type="hidden" name="stock_id" required class="form-control" id="stock_id" value ="<?php echo $stock?>">

  

    <!-- Tab panes -->
    <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="main">
<div class="row">
</div>
</div>
</div>




  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
	<button type="button" class="btn btn-danger" data-coreui-dismiss="modal">Cerrar Ventana</button>
  </div>
  </div> 

  




      </div>
    </div>
  </div>
</div>
