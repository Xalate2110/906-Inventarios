<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<div class="loader"></div>

<section class="content">
<!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/> -->
<?php 
$sesion=$_SESSION["user_id"];
$sesion1 = $_SESSION['id_almacen'] = StockData::getPrincipal()->id;
$sucursal = $_SESSION['sucursal'] = StockData::getPrincipal()->name;
include '/connection/conexion.php';
?>


<script>
    $(document).ready(function () {
     $('#example').DataTable({
            "ajax": 'core/app/json/jfacturas.php',
            "language": {
                //"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            },
            "scrollX": true,
            "order": [[ 5, "desc" ]],
            "aoColumnDefs": [
                {"targets": 6,
                    "render": function (data, type, row) {
                        if (row[6] == '0') {
                            return '<span class="fa fa-times" style="color:red;"></span>';
                        } else
                        if (row[6] == '3')
                        {
                            return '<span class="fa fa-close" style="color:red;"> Cancel</span>';
                        } else
                        if (row[6] == '')
                        {
                            return "<a id='" + row[2] + "' href='javascript:void()' class='btn btn-danger'   onclick='detalle(this.id)'><span class='fa fa-exclamation-triangle'>Error</span></a>";
                
                        }else {
                            return '<span class="bi-check" style="color:green;"></span>';
                        }

                      }
                  },
                   
                {"targets": 7,
                    "render": function (data, type, row) {
                        if (row[6] != '0') {
                            return '<a href="http://localhost/hulesautomotrices/core/app/facturacion/facturas/' + row[2] + '/' + row[2] + '.xmlcomprobanteTimbrado.xml" class="btn btn-sm btn-warning" target="_blank"><span class="bi-file-earmark-code"></span></a>';
                        } else
                        {
                            return '';
                        }
                    }
                }, {
                    "targets": 8,
                    "render": function (data, type, row) {

                        if (row[6] != '0') {
                            return '<a href="http://localhost/hulesautomotrices/core/app/facturacion/facturas/' + row[2] + '/' + row[2] + '.xmlcomprobanteTimbrado.xml.pdf" class="btn btn-sm btn-success"  target="_blank"><span class="bi-file-earmark-excel"></span></a>';
                        } else
                        {
                            return '';
                        }
                    }
                }, {
                    "targets": 9,
                    "render": function (data, type, row) {

                        if (row[6] != '0') {
                            return "<a id='" + row[2] + "' href='javascript:void()' class='btn btn-sm btn-danger'   onclick='cancelar(this.id)'>Cancelar</a>";
                        } else
                        {
                            return '';
                        }
                    }
                }, {
                    "targets": 10,
                    "render": function (data, type, row) {

                        if (row[6] != '0') {
                            return "<a id='" + row[2] + "' href='javascript:void()' class='btn btn-sm btn-info'   onclick='enviar_factura(this.id)'><span class='bi-envelope'></span></a>";
                        } else
                        {
                            return '';
                        }
                    

                    }
                }, {
                    "targets": 11,
                    "render": function (data, type, row) {

                        if (row[7] != '0') {
                            return "<a id='" + row[3] + "' href='javascript:void()' class='btn btn-sm btn-info'   onclick='timbrar_factura(this.id)'><span class='bi-reply'>Retimbrar</span></a>";
                        } else
                        {
                            return '';
                        }
                    

                    }
                }, {
                    "targets": 12,
                    "render": function (data, type, row) {

                        if (row[8] != '0') {
                            return "<?php echo $sucursal;?>";
                        } else
                        {
                            return '';
                        }
                    

                    }
                }, 
            ]

        });
    });
    

            function cancelar(id) {
        if (confirm('¿Deseas Cancelar La Factura?')) {
            $("#clave2").modal('show');
            $("#id_cancelacion2").val(id);   
        }
            }

            function enviar_factura(id) {
        if (confirm('¿Deseas Reenviar La Factura Algun Correo Electronico?')) {
            $("#enviar_factura").modal('show');
            $("#id_cancelacion3").val(id);  
            }
            }

            function detalle(id) {
            $.ajax({
            type: "POST",
            url: "./core/app/action/traer_mot_factura.php",
            data: "id=" + id,
            success: function (data) {
                $("#detalle_can").modal('show');
                $("#motivo_c").html(data);
            }
        });
    }



     function timbrar_factura(id) {

       $.ajax({
       type: "POST",
       url: "./core/app/action/trae_info_factura.php",
       data: "id="+id,
       success: function (data) {
        console.log(datos);
       var datos = data.split(",");
     
       var cliente = datos[0];
       var stock_id = datos[1];
       var forma_pago = datos[2];
       var uso_comprobante = datos[3];
       var folio_factura = datos[4];
       var regimen_fiscal = datos[5];
       var codigo_postal = datos[6];
       var periodicidad = datos[7];
       var p_mes = datos[8];
       var p_ano = datos[9];
       var tiene_rs = datos[10];
       var identificador = 2 ;
       
       window.location.href = "index.php?view=facturacion&id="+id+"&cliente="+cliente+"&almacen="+stock_id+"&forma_pago="+forma_pago+"&uso_comprobante="+uso_comprobante+"&identificador="+identificador+"&folio_factura="+folio_factura+"&regimen_fiscal="+regimen_fiscal+"&codigo_postal="+codigo_postal+"&periodicidad="+periodicidad+"&p_mes="+p_mes+"&p_ano="+p_ano+"&tiene_rs="+tiene_rs;
       },
       error: function () {
       alert("Ocurrio un error al guardar");
       }
     });
 }
      
        function cancela_ticket2() {
        var data = "pass=" + $("#pass2").val() + "&motivo=" + $("#motivo2").val() + "&idcancela=" + $("#id_cancelacion2").val() + "&usuario=" + $("#usuario").val()+"&idcancelacion=" + $("#idcancelacion").val()+"&uuid_sustitucion="+ $("#uuid_sustitucion").val();
        if ($("#pass").val() != "" || $("#motivo").val() != "")
            {
            $.ajax({
                type: "POST",
                url: "./core/app/action/comprueba_clave2.php",
                data: data,
                success: function (data) {
              
                    if (data == "1" || data == "2") {
                        var id = $("#id_cancelacion2").val(); // mandamos el uuid a cancelar.
                        var idcancelacion = $("#idcancelacion").val(); // mandamos el uuid a cancelar.
                        var uuid_sustitucion = $("#uuid_sustitucion").val(); // mandamos el uuid a cancelar.
                        var stock = $("#stock").val(); // mandamos el uuid a cancelar.
                        $.ajax({
                            url: 'core/app/facturacion/cancelar_factura.php',
                            data: "uuid=" + id+"&idcancelacion="+idcancelacion+"&uuid_sustitucion="+uuid_sustitucion+"&stock="+stock,
                            type: 'POST',
                            async: false,
                            success: function (data, textStatus, jqXHR) {
                               
                                if (data == true) {
                                    $("#clave2").modal('hide');
                                    alert("Se ha cancelado la factura correctamente, si la factura es de un dia anterior, Notifica a caja para revisión de corte.");
                                    var table = $('#example').DataTable();
                                    table.ajax.reload(null, false);

                                } else
                                {
                                    alert(data);
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.log(textStatus);
                            }
                        });

                    } else
                    if (data == "0")
                    {
                        $(".error").html("<span style='color:red'>Error!, NO tienes permisos para cancelar Facturas.</span>");
                    } else
                    {
                        alert("ERROR GENERADO EN LA BASE DE DATOS.");
                    }
                }
            });
        } else {
            $(".error").html("<span style='color:red'>Error los campos * son obligatorios</span>");
        }


    }
</script>



<h1></i>Listado Facturas Sucursal: <?php  echo StockData::getPrincipal()->name?> </h1>
<br> 

<div class="card">
<div class="card-header">FACTURAS</div>
  <div class="card-body">
  <table id="example" class="table table-bordered table-hover table-responsive" style="width:100%" id = "facturas">
        <thead>
            <tr>
                <th style="text-align: center">Nombre Cliente</th>
                <th style="text-align: center">Total </th>
                <th style="text-align: center">Folio</th>
                <th style="text-align: center">Compra</th>
                <th style="text-align: center">UUID</th>
                <th style="text-align: center">Fecha</th>
                <th style="text-align: center">Estatus</th>
                <th style="text-align: center">XML</th>
                <th style="text-align: center">PDF</th>
                <th style="text-align: center">Cancelar</th>     
                <th style="text-align: center">Enviar</th>
                <th style="text-align: center">Retimbrar</th>
                <th style="text-align: center">Sucursal</th>
            
          
            </tr>
        </thead>
    </table>
</div>
   </div>


<div class="modal fade" id="clave2" name ="clave2" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Formulario Cancelación Factura</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>

            </div>
            <div class="modal-body">
                <form>

                    <div class="form-group">
                        <input type="text" class="form-control" id="id_cancelacion2" readonly="true"> 
                    </div>

                    <input type="hidden" class="form-control" id="usuario" name = "usuario" value="<?php echo $sesion;?>"> <!-- Se envia el id de la forma de pago -->  

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Clave de usuario para Cancelar:</label>
                        <input type="password" class="form-control" id="pass2">
                    </div>
                          
                    <div class="form-row">
                    <div class="form-group">
                        <label for="inputState">MOTIVO DE CANCELACION SAT</label>
                        <?php
                        $sql_cancela = "select * from cancelacion_factura";
                        //echo $sql;
                        $resultSet = $mysqli->query($sql_cancela);
                        ?>
                        <select id="cancelacion" name="cancelacion" class="form-control js-example-basic-single" required="true" >
                        <option selected value='0'>Selecciona una opcion</option>
                            <?php
                            while ($fila = $resultSet->fetch_assoc()) {
                                echo "<option value='" . $fila['id_cancelacion'] . "'>" . $fila['id_cancelacion'] . " - ".utf8_encode($fila['descripcion']). "</option>";
                            }
                            ?>
                        </select>
                    </div> 
                    </div> 
                   
                    <script>
                    $(document).ready(function() {
                    $('#cancelacion').change(function(e) {
                     if ($(this).val() === "02") {
                    $('#uuid_sustitucion').prop("disabled", true);
                    } else {
                    $('#uuid_sustitucion').prop("disabled", false);
                    }var estado = $("#cancelacion").val();
        
                    $('#idcancelacion').val(estado);    
                    })
                    });
                    </script>
              
                    <input name="idcancelacion" id="idcancelacion" type="hidden">
                    
                    <div class="form-group">
                    <label for="recipient-name" class="col-form-label">UUID DE SUSTITUCIÓN</label>
                    <input type="text" class="form-control" id="uuid_sustitucion" name="uuid_sustitucion" disabled>
                    </div>
                    

                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Escribe El Motivo de Cancelacion:</label>
                        <textarea class="form-control" id="motivo2"></textarea>
                    </div>

                    <div class="form-group">
                           <label for="message-text" class="col-form-label">Razón social para cancelar factura:</label>
                            <div class="col-lg-10">
                            <select name="stock_id" id ="stock_id" class="form-control " onchange="ShowSelected();" required>
                            <option value="">-- Selecciona --</option>
                            <?php foreach (StockData::getAll() as $stock): ?>
                            <option value="<?php echo $stock->id; ?>" <?php
                            ?>><?php echo $stock->name; ?></option>
                            <?php endforeach; ?>
                            </select>
                            </div>
                           
                           <script type="text/javascript">
                            function ShowSelected()
                            {
                            /* Para obtener el valor */
                            var cod = document.getElementById("stock_id").value;
                            
                            $('#stock').val(cod);    
                            }
                            </script>
                            <input name="stock_id" id="stock" name="stock" type="hidden">
                            </div>

                            <br>
    
                    <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-coreui-dismiss="modal">Cerrar Ventana</button>
                    <button type="button" class="btn btn-primary" onclick="cancela_ticket2();">Cancelar Factura</button>
                    </div>
                </form>
            </div>
            <div> <label class="error"></label></div>
       
            
        </div>
    </div>
</div>



<!-- <?php include "../Envio_correo.php";?> -->


<script type="text/javascript">

function validate() {
    var valid = true;
    $(".info").html("");
    var id_cancelacion3 = document.forms["mailForm"]["id_cancelacion3"].value;
   // var userName = document.forms["mailForm"]["userName"].value;
    var userEmail = document.forms["mailForm"]["userEmail"].value;
    var subject = document.forms["mailForm"]["subject"].value;
    var userMessage = document.forms["mailForm"]["userMessage"].value;
    
    if (userName == "") {
        $("#userName-info").html("(required)");
        $("#userName").css('background-color', '#FFFFDF');
        valid = false;
    } 
    if (userEmail == "") {
        $("#userEmail-info").html("(Se requiere un correo electronico)");
        $("#userEmail").css('background-color', '#FFFFDF');
        valid = false;
    }
    if (!userEmail.match(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/))
    {
        $("#userEmail-info").html("(invalid)");
        $("#userEmail").css('background-color', '#FFFFDF');
        valid = false;
    }

    if (subject == "") {
        $("#subject-info").html("(required)");
        $("#subject").css('background-color', '#FFFFDF');
        valid = false;
    }
    if (userMessage == "") {
        $("#userMessage-info").html("(required)");
        $("#userMessage").css('background-color', '#FFFFDF');
        valid = false;
    }
    return valid;
}

function AgregarArchivos() {
    $(".attachment-row:last").clone().insertAfter(".attachment-row:last");
    $(".attachment-row:last").find("input").val("");
}
</script>
</head>





<div class="modal lg" id="enviar_factura" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
           <div class="modal-body">
               <div class="container">
  <h3 class="mt-5">Reenvio De Factura Electronica</h3>
  <div class="row">
    <div class="col-20 col-md-6"> 
     
      
     
        <form name="mailForm" id="mailForm" method="post" action="correo/Envio_correo.php" enctype="multipart/form-data" onsubmit="return validate()">
              
      <div class="form-group">
           <label style="padding-top: 20px;">FOLIO FACTURA</label>
            <span id="id_cancelacion3-info" class="info"></span><br />
            <input type="text" class="form-control" name="id_cancelacion3" id="id_cancelacion3" readonly="true" />
          </div>
         
          <div class="form-group">
           <label style="padding-top: 20px;">Nombres</label>
            <span id="userName-info" class="info"></span><br />
            <input type="text" class="form-control" name="userName" id="userName" value = "MALLA PARAISO LA MORA GDL - URUAPAN" />
          </div> 
         
         
          <div class="form-group">
            <label>Email</label>
            <span id="userEmail-info" class="info"></span><br />
            <input type="text" class="form-control" name="userEmail" id="userEmail" />
          </div>
          <div class="form-group">
            <label>Asunto</label>
            <span id="subject-info" class="info"></span><br />
            <input type="text" class="form-control" name="subject" id="subject" value ="MALLA PARAISO LA MORA GDL - URUAPAN" />
          </div>

          <div class="form-group">
            <label>Mensaje / Contenido</label>
            <span id="userMessage-info" class="info"></span><br />
            <textarea name="userMessage" id="userMessage" class="form-control"  rows="2">
            </textarea>
          </div>
    

          <!-- <div class="attachment-row">
                <input type="file" class="input-field" name="attachment[]">
                </div> -->
          <!--
          <div onClick="AgregarArchivos();" class="icon-add-more-attachemnt" title="Agregar más archivos"> <img src="correo/image/addthis.png" alt="Agregar más archivos"> </div>
          <div> -->

          <br><br>
            <input type="submit" name="send" class="btn btn-primary" value="Reenviar Factura Ahora" />
            
          </div>

          
        </form>

      <!-- Fin Contenido --> 
    </div>
  </div>
  <!-- Fin row --> 
  
</div>
            </div>
        
        </div>
    </div>


<style>
 textarea {
           text-align: justify;
           white-space: normal;
       }
</style>


    <div class="modal fade" id="detalle_can" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">MENSAJE DE ERROR DE TIMBRADO EN FACTURA </h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
               <h3> <textarea id="motivo_c" readonly="true" rows="10" cols="50"></textarea></h3>

                

            </div>
            <div> <label class="error"></label></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>




</section>
