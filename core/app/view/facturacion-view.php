<?php 
error_reporting(0);
?>

<script>
function ShowLoading(e) {
 var div = document.createElement('div');
 var img = document.createElement('img');
 img.src = 'cargadores/facturando.gif';
 div.style.cssText = 'background-color: rgb(255,255,255,.7); padding: 100% 50%; position: absolute; top: 40%; z-index: 5000; transform: translate(-50%, -50%); left: 50%;';
 div.appendChild(img);
 document.body.appendChild(div);
 return true;
 }
</script>

<script type="text/javascript">S
$(window).load(function() {
    $(".loader").fadeOut("slow");
});
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<div class="loader"></div>

<?php
include '/connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
if (!isset($_POST['id'])) {
  $id = $_GET['id'];} else 
  {
  echo "Error No se encontro ID de Venta para Timbrar";}
  
  if (!isset($_POST['almacen'])) {
  $id_almacen = $_GET['almacen'];} 
  else {echo "Error!, no se encontro el ID de Almacen";}

  if (!isset($_POST['error_timbrado'])) {
  $error_timbrado = $_GET['error_timbrado'];} 
  else {echo "Error!, no hay id de timbrado";}




  
  if (!isset($_POST['forma_pago'])) {
      $fp = $_GET['forma_pago'];} 
      else {echo "No se tiene definido la forma de pago, tendras que revisar en la informacion del cliente para timbrar la factura.";}
  
  if (!isset($_POST['uso_comprobante'])) {
  $uc = $_GET['uso_comprobante'];} 
  else {echo "No se tiene definido la forma de pago, tendras que revisar en la informacion del cliente para timbrar la factura.";}
  
  if (!isset($_POST['regimen_fiscal'])) {
  $rf = $_GET['regimen_fiscal'];} 
  else {echo "No se tiene definido el regimen fiscal, tendras que revisar en la informacion del cliente para timbrar la factura.";}
  

    $identificador = $_GET['identificador'];   
    if($identificador == 2 ){ 
    $folio_factura = $_GET['folio_factura'];
    }

    

    if($_GET['tiene_rs'] == "0") {
      echo "<B> <span style='color:red';align:'center'>ADVERTENCIA : El cliente que intenta facturar no cuenta con razÓn social, por lo cual debes de ponerla manualmente o regresar al perfil del cliente para actualizarla.</span> </B> ";
    }else {
      echo "<B> <span style='color:green';align:'center'>ADVERTENCIA: El cliente cuenta con razón social, por favor valide el formulario para proceder a generar la factura.</span> </B> ";
    }





    $c_id = $_GET['cliente'];
       
    if($c_id== '1' ){
        echo'<script type="text/javascript">
        alert("El sistema ha detectado una venta a PUBLICO EN GENERAL, debes seleccionar la periodicidad para poder generarla.");
        </script>';
        }elseif ($c_id != '1'){
        echo'<script type="text/javascript">
        alert("El sistema ha detectado que la factura es a un cliente diferente a PUBLICO EN GENERAL, Preciona Aceptar para continuar con el proceso de Facturación");
        </script>';
        }else {
        print "<script>window.location='index.php?view=facturacion&id=$id&cliente=$cliente&almacen=$id_almacen&forma_pago=$fp&uso_comprobante=$uc&identificador=$identificador&regimen_fiscal=$rf';</script>";   
        } 
    
    if($identificador == 1 ){
     $ruta = "core/app/facturacion/timbrado4.0.php";
     }else {
     $ruta = "core/app/facturacion/retimbrado4.0.php";
     }

    $cliente = "";
    if (!isset($_POST['cliente'])) {
    $cliente = $_GET['cliente'];
    $condicion = " where id = $cliente";}    ?>


   
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        $('.js-example-basic-single').select2();
    });
</script>

<?php   
$sesion=$_SESSION["user_id"];
?>

<div class="card">
  <div class="card-header">Generación Factura Electronica</div>

  <div class="card-body">

  <form action=<?php echo $ruta ?> method="POST" enctype="multipart/form-data" name="form5" onsubmit="ShowLoading()" >

  

        <!-- SE ENVIA EL ID DE LA VENTA A FACTURAR-->
        <input name="idcompra" type="text" value="<?php echo $id;?>" hidden="true">
                        <!-- SE ENVIA EL ID DEL ALMACEN DONDE PROVIENE LA VENTA-->
                        <input name="id_almacen" type="text" value="<?php echo $id_almacen;?>" hidden="true">
                        <input name="folio_factura" id = "folio_factura" type="text" value="<?php echo $folio_factura;?>" hidden="true">
                        <input name="usuario" name = "usuario" type="text" value="<?php echo $sesion;?>" hidden="true">
                        <input name="error_timbrado" id = "error_timbrado" type="text" value="<?php echo $error_timbrado;?>" hidden="true">


<div class="row">
 <!-- Tab panes -->
  <div class="tab-content">
   <div role="tabpanel" class="tab-pane active" id="main">
   <div class="row">
  <div class="col-md-4">
  <label class="control-label">Nombre Del Cliente</label>
  <select class="form-control select2clients" name="select_client" id="select_client">
  <?php 
  if(isset($_GET["cliente"]) && $_GET['cliente']!=""):
  $cli = PersonData::getById($_GET['cliente']);
  ?>
  <option value="<?php echo $cli->id; ?>"><?php echo $cli->lastname;?></option>
 <?php else:?>
   <option value="">Seleccionar/Buscar Cliente</option>
  <?php endif; ?>
 </select>
 </div>

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
      $("#select_client").change(function(){
        //e.preventDefault();
       // alert($(this).val())
        ///
         $.get("./?action=setselectedclient","client_id="+$(this).val()+"&go=byid",function(data){ 
    });
      })

    </script>
<!-- -->

  
  <div class="col-md-2">
    <label class="control-label">Comprobante</label>
<div class="input-group input-group-sm mb-3">

                        <?php
                        $sql_comprobante = "select * from tbltipo_comprobante";
                        //echo $sql;
                        $resultSet = $mysqli->query($sql_comprobante);
                        ?>
                        <select id="comprobante" class="form-control js-example-basic-single" name="comprobante" required="true" >
                       
                            <?php
                            while ($fila = $resultSet->fetch_assoc()) {
                            echo "<option value='" . $fila['tipo'] . "'>" . $fila['descripcion'] . "</option>";
                            }
                            ?>
                        </select>
              
 
    </div>
  </div>


  <div class="col-md-3">
    <label class="control-label">Uso Del Cfdi</label>
   <div class="input-group input-group-sm mb-3">
                        <?php
                        $sql_cfdi = "select id,clave,descripcion from uso_comprobante";
                        //echo $sql_cfdi;
                        $resultSet = $mysqli->query($sql_cfdi);
                        ?>
                        <select id="cfdi" name="cfdi" class="form-control js-example-basic-single" required="true">
                            <option selected value=''>Selecciona una opcion</option>
                            <?php
                       while ($fila = $resultSet->fetch_assoc()) {
                       if ($fila['id'] == $uc) {
                        $seleccion = "selected";
                         } else {
                        $seleccion = "";
                      }
                      echo "<option value='" . $fila['clave'] . "'" . $seleccion . ">" . $fila['clave'] . "-" . $fila['descripcion'] . "</option>";
                                                }
                      ?>
                        </select>
    </div>
  </div>



  
  <div class="col-md-3">
    <label class="control-label">Regimen Fiscal Cliente</label>

    
<div class="input-group input-group-sm mb-3">
                        <?php
                        $sql_regimen = "select * from regimen_fiscal";
                        $resultSet = $mysqli->query($sql_regimen);
                        ?>
                      <select id="regimen_fiscal" name="regimen_fiscal" class="form-control js-example-basic-single" required="true">
                            <option selected value=''>Selecciona una Opcion</option>
                            <?php
                                           while ($fila = $resultSet->fetch_assoc()) {
                                                    if ($fila['idregimen'] == $rf) {
                                                        $seleccion = "selected";
                                                    } else {
                                                        $seleccion = "";
                                                    }
                                                    echo "<option value='" . $fila['idregimen'] . "'" . $seleccion . ">" . $fila['idregimen'] . "-" . $fila['descripcion'] . "</option>";
                                                }
                                                ?>
                        </select>


    </div>
  </div>
</div>


  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="main">
        
<div class="row">
  
<div class="col-md-2">
    
<label class="control-label">Tipo De Moneda</label>
<div class="input-group input-group-sm mb-3">
<?php
                        $sql_moneda = "select * from tbltipo_moneda";
                        //echo $sql;
                        $resultSet = $mysqli->query($sql_moneda);
                        ?>
                        <select id="moneda" name="moneda" class="form-control js-example-basic-single" required="true" >
                            <?php
                            while ($fila = $resultSet->fetch_assoc()) {
                                echo "<option value='" . $fila['tipo_moneda'] . "'>" . $fila['descripcion'] . "</option>";
                            }
                            ?>
                        </select>
    </div>


  </div>


  <div class="col-md-4">
    <label class="control-label">Forma De Pago</label>
  <div class="input-group input-group-sm mb-3">
  <?php
                        $sql_pago = "select * from tblformas_pago";
                        //echo $sql;
                        $resultSet = $mysqli->query($sql_pago);
                        ?>
                        <select id="fpago" name="fpago" class="form-control js-example-basic-single" required="true">

                        <?php
               
                        while ($fila = $resultSet->fetch_assoc()) {
                         if ($fila['id'] == $fp) {
                        $seleccion = "selected";
                         } else {
                        $seleccion = "";
                                }
                        echo "<option value='" . $fila['clave_pago'] . "'" . $seleccion . ">" . $fila['clave_pago'] . "-" . $fila['descripcion'] . "</option>";
                        } ?>
              </select>
    </div>
  </div>


  
  <div class="col-md-3">
    <label class="control-label">Metodo De Pago</label>
    <div class="input-group input-group-sm mb-3">
   <?php
                        $sql_mpago = "select * from tblmetodo_pago";
                 
                        $resultSet = $mysqli->query($sql_mpago);
                        ?>
                        <select id="mpago" name="mpago" class="form-control js-example-basic-single" required="true" >
                            <?php
                            while ($fila = $resultSet->fetch_assoc()) {
                                echo "<option value='" . $fila['tipo'] . "'>" . $fila['tipo'] . "-" . $fila['descripcion'] . "</option>";
                            }
                            ?>
                        </select>
    </div>
  </div>





  <div class="col-md-3">
    <label class="control-label">Correo Electronico</label>
  <div class="input-group input-group-sm mb-3">
  
<?php
                        $sql_email = "select no,name,lastname,email1 from person where id = $cliente";
                        $resultSet_email = $mysqli->query($sql_email);
                        $rowcount = mysqli_num_rows($resultSet_email);


                        if ($rowcount > 0) {
                            while ($fila = $resultSet_email->fetch_assoc()) {

                                if ($fila['email1'] == '') {
                                    $email = '';
                                } else {
                                    $email = $fila['email1'];
                                }
                            echo "<input class='form-control' type='text' id='email' name='email' required='true' value='" . $email . "' />";
                            }
                        } else {
                            echo "<input class='form-control' type='text' id='email' name='email' required='true' value='' />";
                        }
                        ?>


    </div>
  </div>




<!-- Tab panes -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="main">
        
<div class="row">
  
<div class="col-md-2">
    
<label class="control-label">Codigo Postal</label>
<div class="input-group input-group-sm mb-3">
<?php
                        $sql_cp = "select codigopostal from person where id = $cliente";
                        $resultSet_cp = $mysqli->query($sql_cp);
                        $rowcount = mysqli_num_rows($resultSet_cp);
                        if ($rowcount > 0) {
                            while ($fila = $resultSet_cp->fetch_assoc()) {

                                if ($fila['codigopostal'] == '') {
                                    $cp = '';
                                } else {
                                    $cp = $fila['codigopostal'];
                                }
                            echo "<input class='form-control' type='text' id='codigopostal' name='codigopostal' required='true' value='" . $cp . "' />";
                            }
                        } else {
                            echo "<input class='form-control' type='text' id='codigopostal' name='codigopostal' required='true' value='' />";
                        }
                        ?>
    </div>


  </div>


  <div class="col-md-4">
    <label class="control-label">Razón Social Facturación</label>
  <div class="input-group input-group-sm mb-3">
  <select name="id_almacen" id = "id_almacen" class="form-control " required  width="50%">
                                        <?php foreach (RazonData::getAll() as $razon): ?>
                                            <option value="<?php echo $razon->id; ?>">
                                            <?php echo $razon->razonsocial; ?></option>
                                                <?php endforeach; ?>
                                    </select>
    </div>
  </div>


  
  <div class="col-md-3">
    <label class="control-label">Tipo Expotación</label>
    <div class="input-group input-group-sm mb-3">
                        <?php
                        $sql_moneda = "select * from exportacion";
                        //echo $sql;
                        $resultSet = $mysqli->query($sql_moneda);
                        ?>
                        <select id="exportacion" name="exportacion" class="form-control js-example-basic-single" required="true" >
                            <?php
                            while ($fila = $resultSet->fetch_assoc()) {
                                echo "<option value='" . $fila['id_exportacion'] . "'>" . $fila['id_exportacion'] . " - ".$fila['descripcion'] . "</option>";
                            }
                            ?>
                        </select>
    </div>
  </div>





  <div class="col-md-3">
    <label class="control-label">Tipo De Sustitución</label>
  <div class="input-group input-group-sm mb-3">
  
  <select name="id_relacion" id="id_relacion" class="form-control" onchange="ShowSelected();">
                            <option value="">Selecciona.....</option>
                            <option value="01">01 - Nota de crédito de los documentos relacionados</option>
                            <option value="02">02 - Nota de débito de los documentos relacionados</option>
                            <option value="03">03 - Devolución de mercancía sobre facturas o traslados previos</option>
                            <option value="04">04 - Sustitución de los CFDI previos</option>
                            <option value="05">05 - Traslados de mercancías facturados previamente</option>
                            <option value="06">06 - Factura generada por los traslados previos</option>
                            <option value="07">07 - CFDI por aplicación de anticipo</option>
                            <option value="08">08 - Factura generada por pagos en parcialidades</option>
                            <option value="09">09 - Factura generada por pagos diferido</option>
                        </select>  
                       </div>
  </div>

                      <script type="text/javascript">
                       function ShowSelected()
                       {             
                         var cod = document.getElementById("id_relacion").value;
                        alert("Has seleccionado El Metodo de sustutcion de CFDI, recuerda que tiene que estar Cancelada la Factura de la cual vas a tomar el FOLIO UUID, para que se pueda realizar el proceso.");
                        }
                       </script>
                    

  <!-- INICIA TERCER PANEL -->

  
<!-- Tab panes -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="main">
        
<div class="row">
  
<div class="col-md-3">
    
<label class="control-label">UUID Relacionado </label>
<div class="input-group input-group-sm mb-3">
<input type="text" id="uuid_relacionado" name="uuid_relacionado" class='form-control'><br>
    </div>


  </div>


  <div class="col-md-2">
    <label class="control-label">Tipo De Factura</label>
  <div class="input-group input-group-sm mb-3">
  <select name="ti_venta" id="ti_venta" class="form-control" onchange="ShowSelected2();">
                            <option value="1">FACTURA DIGITAL</option>
                            <option value="2">FACTURA ESPECIAL</option>
                            <option value="3">FACTURA GLOBAL</option>

                         </select>
    </div>
  </div>


             

  
  <div class="col-md-2">
    <label class="control-label">Peridiocidad</label>
    <div class="input-group input-group-sm mb-3">
    <?php
                        $sql_periodicidad= "select * from periodicidad";
                        $resultSet = $mysqli->query($sql_periodicidad);
                        ?>
                        <select id="periodicidad" name="periodicidad" class="form-control js-example-basic-single" required="true" >
                            <option value="0">Seleciona una opcion</option>
                            <?php
                            while ($fila = $resultSet->fetch_assoc()) {
              echo "<option value='" . $fila['id_periodicidad'] . "'" . $seleccion . ">" . $fila['id_periodicidad'] . "-" . $fila['descripcion'] . "</option>";
            }?>
            </select>
    </div>
  </div>





  <div class="col-md-2">
    <label class="control-label">Mes Peridiocidad</label>
  <div class="input-group input-group-sm mb-2">
  <?php
                        $sql_meses= "select * from meses_periodicidad";
                        //echo $sql;
                        $resultSet = $mysqli->query($sql_meses);
                        ?>
                        <select id="mes" name="mes" class="form-control js-example-basic-single" required="true" >
                        <option value="0">Seleciona una opcion</option>
                            <?php
                            while ($fila = $resultSet->fetch_assoc()) {
      echo "<option value='" . $fila['id_meses'] . "'" . $seleccion . ">" . $fila['id_meses'] . "-" . $fila['descripcion'] . "</option>";  
                            }?>
                          
                        </select>


    </div>
   </div>


  <div class="col-md-3">
    <label class="control-label">Año Peridiocidad</label>
  <div class="input-group input-group-sm mb-2">
  <?php
                        $sql_ano = "select * from ano_periodicidad";
                        $resultSet = $mysqli->query($sql_ano);
                        ?>
                        <select id="ano" name="ano" class="form-control js-example-basic-single" required="true" >
                      <option value="0">Seleciona una opcion</option>
                            <?php
                            while ($fila = $resultSet->fetch_assoc()) {
                      
                                echo "<option value='" . $fila['ano'] . "'>" . $fila['ano'];
                            }
                            ?>
                       
                        </select>


    </div>
                          </div>





                          <div class="col-md-5">
    <label class="control-label">Comentarios</label>
  <div class="input-group input-group-sm mb-3">
 
                     
                         <textarea name="comentarios" id = "comentarios"  placeholder="Comentarios Adicionales" class="form-control" rows="1"></textarea>
    </div>
  </div>

  
  <div class="col-md-3">
    <label class="control-label">Estado De Venta</label>
  <div class="input-group input-group-sm mb-2">
  <select name="estado_v" id="estado_v" class="form-control " required="true"  >
    <option value="0">Mantener Parcial</option>
    <option value="1">Cerrar Completa</option>

                         </select>


    </div>
                          </div>

<div class="box box-primary table-responsive">
                    <table class="table" width="100%">
                        <thead >

                            <tr>
                                <th scope="col" style="text-align: center" >Cantidad</th>
                                <th scope="col" style="text-align: center">Presentación</th>
                                <th scope="col" style="text-align: center">Clave Unidad</th>
                                <th scope="col" style="text-align: center">Clave</th>
                                <th scope="col" style="text-align: center">Impuesto</th>
                                <th scope="col" style="text-align: center">Descripción Producto (S)</th>
                                <th scope="col" style="text-align: center">P.Unitario</th>
                                <th scope="col" style="text-align: center">Importe</th>
                            </tr>
                        </thead>
                       
                        <tbody style="background-color: #dddddd; color: black">
                           
                           <?php
                               $sql_operacion = "SELECT 
                               operation.*, product.name,product.code,product.unit, product.id as idproducto
                               FROM
                               operation
                               LEFT JOIN
                               product ON product.id = operation.product_id
                               WHERE
                               sell_id = $id and id_salida = 0";
                           
                               $resultSet = $mysqli->query($sql_operacion);
                       
                           $cont = 0;
                           ?>

                           <?php
                           while ($fila = $resultSet->fetch_assoc()) {
                                  
                               
                                  $sql_unidad = "SELECT product.presentation,product.name,tblunidades_sat.name,product.ObjetoImp,ObjetoImp.descripcion , tblunidades_sat.id_unidad
                                   from product
                                   left join 
                                   tblunidades_sat on product.unit = tblunidades_sat.id
                                   inner join ObjetoImp on ObjetoImp.id_ObjetoImp = product.ObjetoImp
                                   where product.id ='" . $fila['idproducto'] . "'";                          
                                   $resultSet2 = $mysqli->query($sql_unidad);
                                   $fila2 = $resultSet2->fetch_assoc();

                                   $presentacion = "SELECT product.presentation, tblunidades.descripcion from product 
                                   left join tblunidades
                                   on product.presentation = tblunidades.id where product.id = '" . $fila['idproducto'] . "'";
                                   $resultSet3 = $mysqli->query($presentacion);
                                   $fila3 = $resultSet3->fetch_assoc();
                               
                                   $p_p = round(($fila['price_out'] * $fila ['q']),2) ; // tomo el precio de los productos sin iva 
                                   $suma_p = round($p_p + $suma_p,2);
                         
                               echo "<tr>";
                               echo "<th>" . $fila['q'] . "</th>";
                               echo "<th>" . $fila3['descripcion'] . "</th>";
                               echo "<th>" . $fila2['id_unidad'] ." - ".$fila2['name'] .  "</th>";
                               echo "<th>" . $fila['code'] . "</th>";
                               echo "<th>" . $fila2['ObjetoImp']." - ".$fila2['descripcion'] .  "</th>";
                               echo "<th>" . utf8_decode($fila['name']). "</th>";
                               echo "<th>"."$ " . number_format($fila['price_out'],2,'.',',') . "</th>";
                               echo "<th>"."$ ". number_format(round($fila['price_out'] * $fila ['q'], 2),2,'.',',') . "</th>";
                               echo "</tr>";
                               $subtotal = round($fila['price_out'] * $fila ['q'],2);
                             }
                       
                  
                             $sub_total = round($subtotal,2); // 122.53
                             // SE SACA LA DIFERENCIA DE CENTAVOS.
                             $operacion_subtotal = round($suma_p - $sub_total,2);
                             $subtotal_total = round($sub_total + $operacion_subtotal,2);
                             $iva = round($subtotal_total * 0.16,2);
                             $total = ($subtotal_total + $iva);
               
                              
                           echo "<tr>";
                           echo "<th></th>";
                           echo "<th></th>";
                           echo "<th></th>";
                           echo "<th></th>";
                           echo "<th></th>";
                              echo "<th></th>";
                           echo "<th style='background-color:black; color:white;'>Subtotal</th>";
                           echo "<th>"."$ " . number_format(round($subtotal_total,2),2,'.',',') . "</th>";
                           echo "<tr>";

                           echo "<tr>";
                           echo "<th></th>";
                           echo "<th></th>";
                           echo "<th></th>";
                           echo "<th></th>";
                           echo "<th></th>";
                              echo "<th></th>";
                           echo "<th style='background-color:black; color:white;'>IVA</th>";
                           echo "<th>"."$ " . number_format(round($iva,2),2,'.',','). "</th>";
                           echo "<tr>";

                           echo "<tr>";
                           echo "<th></th>";
                           echo "<th></th>";
                           echo "<th></th>";
                           echo "<th></th>";
                           echo "<th></th>";
                              echo "<th></th>";
                           echo "<th style='background-color:black; color:white;'>Total</th>";
                           echo "<th>"."$ " . number_format(round($total,2),2,'.',','). "</th>";
                           echo "<tr>";
                           ?>

                       </tbody>
                   </table>
               </div>
               

           
               <button type="submit" class="btn btn-danger pull-right" id ="genera_factura" name ="genera_factura" onclick="return cancelar();" >Generar Factura</button>


         
              
       </div>

       </form>

   </div>

</section>

<script>
function cancelar() {
if (confirm("¿Toda la información esta correcta, para iniciar con el proceso de Factura Electronica Versión 4.0?")) {
return true;
} else {
return false;

}
}
</script>

