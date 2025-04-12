    <?php
    include '../../../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");
    date_default_timezone_set('America/Mexico_City');

    $idcliente = $_POST["select_client"];
    $comprobante = $_POST["comprobante"];
    $cfdi = $_POST["cfdi"];
    $moneda = $_POST["moneda"];
    $fpago = $_POST["fpago"];
    $mpago = $_POST["mpago"];
    $email = $_POST["email"];
    $idcompra = $_POST["idcompra"];
    $tipo_factura = $_POST["ti_venta"];
    $id_deposito = $_POST["id_deposito"];
    $id_almacen = $_POST["id_almacen"];
    $comentarios = $_POST["comentarios"];
    $uuid_relacionado = $_POST["uuid_relacionado"];
    $exportacion = $_POST["exportacion"];
    $id_relacion = $_POST["id_relacion"];
    $usuario = $_POST["usuario"];
    $periodicidad = $_POST["periodicidad"];
    $p_mes = $_POST["mes"];
    $p_ano = $_POST["ano"];

    $sql_direccion = "SELECT * FROM person where id = $idcliente";
    $resultado = $mysqli->query($sql_direccion);

    while($row = $resultado->fetch_assoc()){
    $codigo_postal = $row['codigopostal'];
    $direccion = $row['address1'];
    }
    
       if($idcliente == '1' && $periodicidad == '0' && $p_ano == '0' && $p_mes == '0'){
        echo'<script type="text/javascript">
        alert("Estas facturando a Publico en General, por lo cual debes de llenar los campos de Periodicidad, Periodicidad Mes y Año y que no se ingresaron de forma correcta. el sistema te regresara al listado de para que puedas actualizar la 
        informacion solicitada.");
        window.location = "http://localhost/hules_automotrices/?view=facturas"; </script>';
        } else if($idcliente == '1' && $tipo_factura != '3'){
        echo'<script type="text/javascript">
        alert("Estas facturando a Publico en General, por lo cual debiste o debes de seleccionar el TIpo de Factura como global. Contacta al Administrador del sistema para realizar el cambio de factura.")</script>'; 
        }else {
        echo'<script type="text/javascript">
        alert("Factura en Formato Correcto, preciona Aceptar, para continuar con el proceso de Facturación.")</script>';  
        }
    
    pruebaTimbrado($idcliente, $comprobante, $cfdi, $moneda, $fpago, $mpago, $email, $idcompra, $tipo_factura,$uuid_relacionado,$id_relacion,$id_almacen,$id_deposito,$comentarios,$usuario,$codigo_postal,$exportacion,$periodicidad,$p_mes,$p_ano,$direccion);
    function pruebaTimbrado($idcliente, $comprobante, $cfdi, $moneda, $fpago, $mpago, $email, $idcompra, $tipo_factura,$uuid_relacionado,$id_relacion,$id_almacen,$id_deposito,$comentarios,$usuario,$codigo_postal,$exportacion,$periodicidad,$p_mes,$p_ano,$direccion) {
    $rfc_emisor = "";

    $cfdi = generarXML($rfc_emisor, $idcliente, $comprobante, $cfdi, $moneda, $fpago, $mpago, $email, $idcompra, $tipo_factura,$uuid_relacionado,$id_relacion,$id_almacen,$id_deposito,$comentarios,$usuario,$codigo_postal,$exportacion,$periodicidad,$p_mes,$p_ano,$direccion);
    $nombretimbrado = timbrar($cfdi . '.xml', $cfdi);

    if ($nombretimbrado != '') {
    generarPDF($nombretimbrado, $cfdi, $idcompra, $email,$comentarios,$codigo_postal,$id_almacen,$direccion);
  
    } else {
    ?>
    <script>
    if (confirm("Error: Se ha presentando un error en el timbrado, valida la información del cliente a quien estas facturando. Ya que la información debe de corresponder\n\
    a la misma de su Cedula de Identificación Fiscal. O contacta al administrador del sistema, para que te apoye en el error. - El sistema te redireccionara al listado General de Facturas."))
              window.location = "http://localhost/hules_automotrices/?view=facturas";
    </script>
        <?php
     } 
}

function generarXML($rfc_emisor, $idcliente, $comprobante, $cfdi, $moneda, $fpago, $mpago, $email, $idcompra, $tipo_factura,$uuid_relacionado,$id_relacion,$id_almacen,$id_deposito,$comentarios,$usuario,$codigo_postal,$exportacion,$periodicidad,$p_mes,$p_ano,$direccion) {

    include '../../../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql_cliente = "SELECT * FROM person WHERE id =" . $idcliente;
    $resultSet_cliente = $mysqli->query($sql_cliente);
    $fila = $resultSet_cliente->fetch_assoc();

    $sql_sucursales = "SELECT razonsocial FROM razones_sociales where id = $id_almacen";
    $resultSet_sucursales = $mysqli->query($sql_sucursales);
    $fila_nombreempresa = $resultSet_sucursales->fetch_assoc();

    $sql_rfcemisor = "SELECT rfc FROM razones_sociales where id = $id_almacen";
    $resultSet_rfcemisor = $mysqli->query($sql_rfcemisor);
    $fila_rfcemisor = $resultSet_rfcemisor->fetch_assoc();
    
    $sql_cp = "SELECT codigo_postal FROM razones_sociales where id = $id_almacen";
    $resultSet_cp = $mysqli->query($sql_cp);
    $fila_cp = $resultSet_cp->fetch_assoc();
    $cp = $fila_cp['codigo_postal'];
    
    $sql_reg = "SELECT regimen_fiscal FROM razones_sociales where id = $id_almacen";
    $resultSet_reg = $mysqli->query($sql_reg);
    $fila_reg = $resultSet_reg->fetch_assoc();
    $reg =  $fila_reg['regimen_fiscal'];

    $sql_sf = "SELECT serie_facturacion FROM razones_sociales where id = $id_almacen";
    $resultSet_sf = $mysqli->query($sql_sf);
    $fila_sf = $resultSet_sf->fetch_assoc();
    $sf =  $fila_sf['serie_facturacion'];
   
    $sql_conceptos = "SELECT operation.id,operation.product_id,operation.descripcion,product.name,operation.stock_id,operation.stock_destination_id,operation.operation_from_id,operation.q,operation.price_in,operation.price_out,operation.discount,operation.operation_type_id,
    operation.sell_id,operation.status,operation.is_draft,operation.is_traspase,operation.created_at,operation.ref_sell_id,operation.id_trans,operation.id_salida,product.ObjetoImp from operation
    INNER JOIN PRODUCT on product.id = operation.product_id WHERE operation.sell_id = $idcompra and  operation.id_salida = 0";

    
    $resultSet_conceptos = $mysqli->query($sql_conceptos);   

    $sql_compras = "SELECT * FROM sell WHERE id = " . $idcompra;
    $resultSet = $mysqli->query($sql_compras);
    $fila_compras = $resultSet->fetch_assoc();

    $ultimoid = "SELECT MAX(idcfdis) AS id FROM cfdis";
    $resultSet = $mysqli->query($ultimoid);
    $fila_ultimoid = $resultSet->fetch_assoc();   
    

    $noCertificado = "";
    $fact_serie = $sf;
    $fact_folio = $fila_ultimoid['id'];
    $NoFac = $fact_serie . $fact_folio;
    $fact_tipcompr = $comprobante;
    $tasa_iva = 16;
    $descuento = "0.00";
    //$fecha_fact = date("Y-m-d") . "T" . date("H:i:s");
    $fecha_fact = date("Y-m-d") . "T" . date("H:i:s", strtotime("-1 hour"));
    $NumCtaPago = "6473";
    $condicionesDePago = "CONDICIONES";
    $formaDePago = $fpago;
    $metodoDePago = $mpago;
    $TipoCambio = 1;

    //codigo Postal
    $LugarExpedicion = $cp;
    $moneda = $moneda;
    $xml = new DOMDocument('1.0', 'utf-8');
    $root = $xml->createElement("cfdi:Comprobante");
    $root = $xml->appendChild($root);

    $cadena_original = '||';
    $noatt = array();

    // version 4.0
    cargaAtt($root, array("xsi:schemaLocation"=>"http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd", "xmlns:cfdi"=>"http://www.sat.gob.mx/cfd/4", "xmlns:xsi"=>"http://www.w3.org/2001/XMLSchema-instance"
    ));

        date_default_timezone_set('America/Mexico_City');

        if($idcliente == '1'){
        $infoglobal = $xml->createElement("cfdi:InformacionGlobal");
        $infoglobal = $root->appendChild($infoglobal);
        cargaAtt($infoglobal, array(
        "Periodicidad"=>$periodicidad, 
        "Meses"=>$p_mes, 				                      
        "Año"=>$p_ano				  
        )); }

        if($id_relacion == "04"){
        $docrelacionado = $xml->createElement("cfdi:CfdiRelacionados");
        $docrelacionado = $root->appendChild($docrelacionado);
        cargaAtt($docrelacionado, array(
        "TipoRelacion" => $id_relacion));

        $relacionados = $xml->createElement("cfdi:CfdiRelacionado");
        $relacionados = $docrelacionado->appendChild($relacionados);
        cargaAtt($relacionados, array(
        "UUID" => $uuid_relacionado
        )); }

        //sacamos los datos del cliente a quien se le facturo.
        $name_cliente = $fila['name'];
        $last_cliente = $fila['lastname'];
        $no_cliente =   $fila['no'];
        $email_cliente = $fila['email1'];
        $direccion = $fila['address1'];
        $cp = $fila['codigopostal'];
        $rf = $fila['regimen_fiscal'];

        $datoemisor = $fila_rfcemisor['rfc'];
        $emisor = $xml->createElement("cfdi:Emisor");
        $emisor = $root->appendChild($emisor);
        cargaAtt($emisor, array(
       //"Rfc" => $fila_rfcemisor['rfc'], // comentar para pasar a entorno de pruebas
        "Rfc" => "IIA040805DZ4", 
        //"Nombre" => utf8_decode($fila_nombreempresa['name']),
        "Nombre" => 'INDISTRIA ILUMINADORA DE ALMACENES',
         //"RegimenFiscal" => $reg         // comentar para pasar a entorno de pruebas
        "RegimenFiscal" => "626"         // comentar para pasar a entorno de pruebas
        )
        );


       // comentar para pasar a entorno de pruebas
        $receptor = $xml->createElement("cfdi:Receptor");
        $receptor = $root->appendChild($receptor);
        cargaAtt($receptor, array
        (
        "Rfc" => utf8_decode($fila['no']),
        "Nombre" => utf8_decode($fila['lastname']),
        "DomicilioFiscalReceptor"=>utf8_decode($cp), // regimen fiscal.,// codigo postal. 
        "RegimenFiscalReceptor"=>utf8_decode($rf), // regimen fiscal.
        "UsoCFDI" => $cfdi 
        )
       ); 
   
        $conceptos1 = $xml->createElement("cfdi:Conceptos");
        $conceptos1 = $root->appendChild($conceptos1);
       
        $subtotal = 0;
        $total_venta = 0;
        $sub_total = 0;
        $totalretenciones=0;
        $ret_isr=0;
        $ret_iva =0;
        $tr=0;
        
        //traslados
        $importeconceptos = 0;   
        $importeconceptos1 = 0;   

        //isr - retenciones
        $importeconceptos2 = 0;
        $importeconceptos3 = 0;

        //iva - retenido
        $importeconceptos4 = 0;
        $importeconceptos5 = 0;
        
        $longitud = strlen($no_cliente); // sacamos el numero de caracteres del rfc del cliente. 
        while ($fila_conceptos = $resultSet_conceptos->fetch_assoc()) {

        $concepto = $xml->createElement("cfdi:Concepto");
        $concepto = $conceptos1->appendChild($concepto);

        /* aqui se toma el valor del salida y se multiplica por 0.16*/
        $ObjetoImp = $fila_conceptos['ObjetoImp'];
        $coni = $fila_conceptos['price_out']; // SACAMOS EL VALOR UNITATIO SIN IVA DEL PRODUCTO
        $iva2 = $coni * 0.16; // SACAMOS EL IVA DEL PRODUCTO
        $t_coni = $coni + $iva2; // SUMAMOS EL PRECIO UNITARIO + IVA Y SE GENERA EL TOTAL CON IVA
       
    cargaAtt($concepto, array(
            "ClaveProdServ" => obtenercodigosat($fila_conceptos['product_id']),
            "NoIdentificacion" => noidentificacion($fila_conceptos['product_id']),
            "Cantidad" => $fila_conceptos['q'], // 2
            "ClaveUnidad" => obtenerunidadsat($fila_conceptos['product_id']),
            "Unidad" => obtenerunidad($fila_conceptos['product_id']),
            "Descripcion" => obtenernombre($fila_conceptos['product_id'],$idcompra),
            "ValorUnitario" => round($coni,6), // 250
            "Importe" => round($fila_conceptos['q'] * $t_coni / 1.16,6), // 500
            "Descuento" => 0,
            "ObjetoImp"=>obtenerObjetoImp($fila_conceptos['product_id'])));
          
        /* impuestos */
            $impuestos = $xml->createElement("cfdi:Impuestos");
            $impuestos = $concepto->appendChild($impuestos);
            
        /* impuestos traslados */
            $traslados = $xml->createElement("cfdi:Traslados");
            $traslados = $impuestos->appendChild($traslados);
         
        /* Total traslados */   
            $traslado = $xml->createElement("cfdi:Traslado");
            $traslado = $traslados->appendChild($traslado);

            $importeconceptos1 = ($fila_conceptos['q'] * $fila_conceptos['price_out']);
            $importeconceptos += round(($importeconceptos1) * 0.16,6);
      
        // si solamente concepto de impuesto es 02.
            cargaAtt($traslado, array(
            "Base" => round($fila_conceptos['q'] * $fila_conceptos['price_out'],6),
            "Impuesto" => "002",
            "TipoFactor" => "Tasa",
            "TasaOCuota" => "0.160000",
            "Importe" => round((($fila_conceptos['q'] * $fila_conceptos['price_out'])) * 0.16,6)
            ));
            
            if($longitud == '12'){
            
                /* impuestos Retenidos */
            $retenciones = $xml->createElement("cfdi:Retenciones");
            $retenciones = $impuestos->appendChild($retenciones);
          
            /* impuestos Retenidos ISR */   
            $retencion = $xml->createElement("cfdi:Retencion");
            $retencion = $retenciones->appendChild($retencion);

             /* impuestos Retenidos IVA */   
            $retencion1 = $xml->createElement("cfdi:Retencion");
            $retencion1 = $retenciones->appendChild($retencion1);

            /* Cálculos de Retenciones  ISR */
            $importeconceptos2 = ($fila_conceptos['q'] * $fila_conceptos['price_out']);
            $importeconceptos3 += round(($importeconceptos2) * 0.012500,2);

            /* Cálculos de Retenciones  IVA RETENIDO */
            $importeconceptos4 = ($fila_conceptos['q'] * $fila_conceptos['price_out']);
            $importeconceptos5 += round(($importeconceptos4) * 0.106666,2);

            $tr = round($importeconceptos3 + $importeconceptos5,2);
            $ret_isr = round($importeconceptos3,6);
            $ret_iva = round($importeconceptos5,6);
            
        

            cargaAtt($retencion, array(
                "Base" => round($fila_conceptos['q'] * $fila_conceptos['price_out'],2),
                "Impuesto" => "001",
                "TipoFactor" => "Tasa",
                "TasaOCuota" => "0.012500",
                "Importe" => round((($fila_conceptos['q'] * $fila_conceptos['price_out'])) * 0.012500,2)
                ));

            cargaAtt($retencion1, array(
                "Base" => round($fila_conceptos['q'] * $fila_conceptos['price_out'],2),
                "Impuesto" => "002",
                "TipoFactor" => "Tasa",
                "TasaOCuota" => "0.106666",
                "Importe" => round((($fila_conceptos['q'] * $fila_conceptos['price_out'])) * 0.106666,2)
                ));
    

               // validar que le rfc sea igual a 13 
            }

 
        } // AQUI VAN LOS CONCEPTOS CON SU IMPUESTO NORMAL PARA CADA UNO. 

        $totalimp = 0;
        $suma_p = 0;
        $suma_p2 = 0;
        $suma_p3 = 0;
        $suma_p4=0;
        $p_p1 = 0;
        $p_p2 = 0;
        $p_p3 = 0;
        $total = 0;
        $sumaret=0;

        $sql_conceptos_2 = "SELECT * FROM operation WHERE sell_id = $idcompra and  id_salida = 0";
        $resultSet_conceptos_2 = $mysqli->query($sql_conceptos_2);
   
        while ($fila_conceptos2 = $resultSet_conceptos_2->fetch_assoc()) {
        /*  CALCULOS CUANDO EL CLIENTE NO LLEVA RETENCIONES   */
        // sacamos el total de toda la venta. 

        $totalimp += $fila_conceptos2['q'] * $fila_conceptos2['price_out'];
        
        // sacamos el subtotal de la venta
        $p_p = round($fila_conceptos2['q'] * $fila_conceptos2['price_out'],2); 
        $suma_p +=round($p_p,6); 

        // SE LE SACA EL IVA AL PRECIO DEL PRODUCTO
        $p_p1 = ($fila_conceptos2['q'] * $fila_conceptos2['price_out']); // suma total de las ventas
        $suma_p2 += round($p_p1 * 0.16,6); // AQUI EL ROUND

        /*  CALCULOS CUANDO EL CLIENTE NO LLEVA RETENCIONES   */

         $isr_ret = 0.012500;
         $iva_ret = 0.106666;

         if($longitud == '12'){
         // SACAMOS LA SUMA DEL IMPUESTO RETENIDO
         $p_p2 = ($fila_conceptos2['q'] * $fila_conceptos2['price_out']); // suma total de las ventas
         $suma_p3 += round($p_p2 * 0.012500,2); // AQUI EL ROUND

        // SACAMOS LA SUMA DEL IMPUESTO RETENIDO
        $p_p3 = ($fila_conceptos2['q'] * $fila_conceptos2['price_out']); // suma total de las ventas
        $suma_p4 += round($p_p3 * 0.106666,2); // AQUI EL ROUND
      
        if($isr_ret == $iva_ret){
        $sumaret = round($suma_p3 + $suma_p4,2);
        }else if ($isr_ret <> $iva_ret) {
        $sumaret = round($suma_p3 + $suma_p4,2);
        }
        }
    
    }
        
        

        //CONDICIÓN PARA CUANDO EL CLIENTE NO LLEVA RETENCIONES, SOLAMENTE TRASLADOS. 

        if($longitud == '13'){
        // TOTAL GENERADO CUANDO EL CLIENTE NO LLEVA RETENCIONES
        /* *****************************************************  */
        $sub = round($totalimp,6); // subtotal de la venta 122.53      
        //$operacion_subtotal = round($suma_p - $sub,2); // SE SACA LA DIFERECIA DE SUBTOTAL DE VENTA CONTRA EL SUBTOTAL DE IMPUESTOS
        $subtotal_total = round($sub + $operacion_subtotal,2); // SE HACE LA SUMA DE LA DIFERENCIA EN EL SUBTOTAL
        $totalventa = round($totalimp + $suma_p2,2); // total de la venta de nuevo 142.14
        /* *****************************************************  */
        
        //CONDICIÓN PARA CUANDO EL CLIENTE LLEVA RETENCIONES, SOLAMENTE TRASLADOS. 
        } else if ($longitud == '12'){
          // TOTAL GENERADO CUANDO EL CLIENTE LLEVA RETENCIONES
          $total1 = round($totalimp,2); // total venta real.
          $ivaisr = round($suma_p2,2);
          $total2 = round($total1 + $ivaisr,2);
          $ivaret = round($sumaret,2);
          $restotal = round($total2 - $ivaret,2);
          $sub = round($totalimp,2); // subtotal de la venta 122.53      
          $subtotal_total = round($sub,2); // SE HACE LA SUMA DE LA DIFERENCIA EN EL SUBTOTAL
          $totalventa = $restotal ; // total de la venta de nuevo 142.14
        }
        
    

    $foliox = $fact_folio + 1;
    cargaAtt($root, array(
        "Version" => "4.0",
        "Serie" => $sf,
        "Folio" => $foliox,
        //"Fecha" => date("Y-m-d") . "T" . date("H:i:s"),
        "Fecha" => date("Y-m-d") . "T" . date("H:i:s", strtotime("-1 hour")),
        "FormaPago" => $formaDePago,
        "SubTotal" => round($subtotal_total,2),
        "Descuento" => 0.0,
        "Moneda" => $moneda,
        "Total" => round($totalventa,2),
        "TipoDeComprobante" => $fact_tipcompr,
        "Exportacion"=>$exportacion, // se agrrega exportancion. 
        "MetodoPago" => $metodoDePago,
        "LugarExpedicion" => $LugarExpedicion
        ));

       /* impuesto trasladado */
        $traslado = round($subtotal_total,6);
        $total_traslado = round($traslado * 0.16,6);
        $operacion_traslado = round($suma_p2 - $total_traslado,6);
        $total_traslado = round($total_traslado + $operacion_traslado,6);
        
        $impuestos1 = $xml->createElement("cfdi:Impuestos");
        $impuestos1 = $root->appendChild($impuestos1);
        /* impuestos traslados */
        cargaAtt($impuestos1, array(
        "TotalImpuestosTrasladados" => round($importeconceptos,2))
        );
        

        if($longitud == '12'){
            /* impuestos retenidos */
            $retenido = round($subtotal_total,2);
            $total_retenido = round($retenido * 0.012500,6);
            $operacion_retenido = round($total_retenido,6);
            $total_retenido = round($operacion_retenido,6);
    
            /* impuestos retenidos */
            cargaAtt($impuestos1, array(
            "TotalImpuestosRetenidos" => $tr)
            );
        }

        if($longitud == '12'){

        /* impuestos retenciones */
        $retenciones2 = $xml->CreateElement("cfdi:Retenciones");
        $retenciones2 = $impuestos1->appendChild($retenciones2);

        $retencion2 = $xml->CreateElement("cfdi:Retencion");
        $retencion2 = $retenciones2->appendChild($retencion2);
        cargaAtt($retencion2, array(
        "Impuesto" => "001",
        "Importe" => round($ret_isr,2))
           );
           
        /* impuestos retenciones */

        $retencion2 = $xml->CreateElement("cfdi:Retencion");
        $retencion2 = $retenciones2->appendChild($retencion2);
        cargaAtt($retencion2, array(
        "Impuesto" => "002",
        "Importe" => round($ret_iva,2))
           );
        /* impuestos retenciones */
        }

        /* impuestos traslados */
        $traslados1 = $xml->CreateElement("cfdi:Traslados");
        $traslados1 = $impuestos1->appendChild($traslados1);
        $traslado1 = $xml->CreateElement("cfdi:Traslado");
        $traslado1 = $traslados1->appendChild($traslado1);
        cargaAtt($traslado1, array(
        "Base" => round($traslado,2),
        "Impuesto" => "002",
        "TipoFactor" => "Tasa",
        "TasaOCuota" => "0.160000",
        "Importe" => round($importeconceptos,2))
    );/* impuestos traslados */


     $xml->formatOutput = true; // set the formatOutput attribute of domDocument to true


    $carpeta = 'facturas/' . $foliox;
    if (!file_exists($carpeta)) {
        mkdir($carpeta, 0777, true);
    }


       // Ingresamos que se ha facturado una remisión y se convierte en factura.
       $facturado = "UPDATE sell set facturado = 1 where id = $idcompra ";
       $mysqli->query($facturado);
      
       $totalgenerado = round($totalventa,2);
      
       // Ingresamos que se ha facturado una remisión y se convierte en factura.
       $actualiza = "UPDATE payment set val = '" . $totalgenerado . "' , facturado = 1 , val_facturado =  '" . $totalgenerado . "'  where sell_id = $idcompra ";
       $mysqli->query($actualiza);

    //validar que exista una factura.
    $xml->save('facturas/' . $foliox . '/' . $foliox . ".xml"); // save as file                                                                                                                                                                                    

    $sql_xml_sintimbrar = "INSERT INTO `cfdis` ( `folio`,`serie`,`regimen_fiscal`,`codigo_postal`,`periodicidad`,`p_mes`,`p_ano`, `rfc_receptor`, `url`, `UUID`, `fecha_registro`,`Folio_venta`, `tipo_factura`,`subtotal`,`iva`,`Monto`,`retenido`,`uuid_relacionado`,`tipo`,`mpago`,`nombre_cliente`,`apellido_cliente`,`rfc_cliente`,`email_cliente`,`stock_id`,`id_deposito`,`comentarios`,`id_usuario`,`id_cliente`)
    VALUES ('" . $foliox . "','" . $fact_serie. "','" . $rf . "','" . $cp . "','" . $periodicidad . "','" . $p_mes . "','" . $p_ano . "','" . $datoemisor . "','','', NOW(),'" . $idcompra . "', '" . $tipo_factura . "','" . $subtotal_total . "','" . $suma_p2 . "','" . round($totalventa,2) . "','" . round($suma_p3,2). "','" . $uuid_relacionado . "','" . $id_relacion . "','" . $fpago . "','" . $name_cliente . "','" . $last_cliente . "','" . $no_cliente . "','" . $email . "','" . $id_almacen . "','" . $id_deposito . "','" . $comentarios . "','" . $usuario . "','" . $idcliente . "')";
    $mysqli->query($sql_xml_sintimbrar);
    $xml->saveXML();
    return $foliox;
}
    
   
function obtenernombre($identificador,$idcompra) {
    include '../../../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");
    $sql_cliente = "SELECT descripcion FROM operation where product_id = $identificador and sell_id = $idcompra and  id_salida = 0 ";

    $resultSet_cliente = $mysqli->query($sql_cliente);
    $fila = $resultSet_cliente->fetch_assoc();
    return $fila['descripcion'];
}


function noidentificacion($identificador) {
    include '../../../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");
    $sql_cliente = "SELECT * FROM product WHERE id =" . $identificador;
    $resultSet_cliente = $mysqli->query($sql_cliente);
    $fila = $resultSet_cliente->fetch_assoc();
    return $fila['code'];
}

function obtenerObjetoImp($identificador) {
    include '../../../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");
    $sql_cliente = "SELECT * FROM product WHERE id =" . $identificador;
    $resultSet_cliente = $mysqli->query($sql_cliente);
    $fila = $resultSet_cliente->fetch_assoc();
    return $fila['ObjetoImp'];
}

    function obtenerunidadsat($identificador) {
    include '../../../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql_cliente = "SELECT product.*,tblunidades_sat.id_unidad  "
            . " FROM product "
            . " left join tblunidades_sat on product.unit = tblunidades_sat.id"
            . " WHERE product.id =" . $identificador;
    $resultSet_cliente = $mysqli->query($sql_cliente);
    $fila = $resultSet_cliente->fetch_assoc();

    return $fila['id_unidad'];
}

    function obtenercodigosat($identificador) {
    include '../../../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");



    $sql_cliente = "SELECT * FROM product WHERE id =" . $identificador;
    $resultSet_cliente = $mysqli->query($sql_cliente);
    $fila = $resultSet_cliente->fetch_assoc();

    $codigo = $fila['codigo_sat'];

    if ($codigo == "0") {
        $codigo = "01010101";
    } else {
        $sql_cod = "SELECT * FROM tblcodigos_sat WHERE id =" . $codigo;
        $resultSet_cod = $mysqli->query($sql_cod);
        $fila_cod = $resultSet_cod->fetch_assoc();

        $codigo = $fila_cod['id_codigo'];
    }
    return $codigo;
}

function obtenerunidad($identificador) {
    include '../../../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");
    $sql_cliente = "SELECT product.*,tblunidades.descripcion  "
            . " FROM product "
            . " left join tblunidades on product.presentation = tblunidades.id"
            . " WHERE product.id =" . $identificador;

    $resultSet_cliente = $mysqli->query($sql_cliente);
    $fila = $resultSet_cliente->fetch_assoc();

    return $fila['descripcion'];
}



function cargaAtt(&$nodo, $attr) {
    global $xml, $sello;
    $quitar = array('sello' => 1, 'noCertificado' => 1, 'certificado' => 1);
    foreach ($attr as $key => $val) {
        for ($i = 0; $i < strlen($val); $i++) {
            $a = substr($val, $i, 1);
            if ($a > chr(127) && $a !== chr(219) && $a !== chr(211) && $a !== chr(209)) {
                $val = substr_replace($val, ".", $i, 1);
            }
        }
        $val = preg_replace('/\s\s+/', ' ', $val);   // Regla 5a y 5c
        $val = trim($val);                           // Regla 5b
        if (strlen($val) > 0) {   // Regla 6
            $val = str_replace(array('"', '>', '<'), "'", $val);  // &...;
            $val = utf8_encode(str_replace("|", "/", $val)); // Regla 1
            $nodo->setAttribute($key, $val);
        }
    }
}



function timbrar($archivo, $directorio) {
    include '../../../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");
    $nombre = $archivo;


    $ws = "https://cfdi33-pruebas.buzoncfdi.mx:1443/Timbrado.asmx?wsdl"; /* <- Esta ruta es para el servicio de pruebas, para pasar a productivo cambiar por https://timbracfdi33.mx:1443/Timbrado.asmx */
    //$ws="https://timbracfdi33.mx:1443/Timbrado.asmx?wsdl";
    $response = '';

//echo $nombre;
    $workspace = getcwd() . '/facturas/' . $directorio . '/' . $nombre; /* <- Configurar la ruta en donde se encuentra nuestro kit de integración para localizar correctamente el archivo Ejemplo_cfdi_3.3.xml */

//echo $workspace;

    $base64Comprobante = file_get_contents($workspace);
    $base64Comprobante = base64_encode($base64Comprobante);
    try {
        $params = array();
        /* Nombre del usuario integrador asignado, para efecto de pruebas utilizaremos 'mvpNUXmQfK8=' <- Este usuario es para el servicio de pruebas, para pasar a productivo cambiar por el que le asignarán posteriormente */
        //$params['usuarioIntegrador'] = 'K/nl+ecaEJOJpAUUY1c4Kg=='; // COMENTAR PARA PASAR A SERVIDOR DE PRUEBAS
        $params['usuarioIntegrador'] = 'mvpNUXmQfK8='; // DESCOMENTAR PARA PASAR A SERVIDOR DE PRUEBAS.
        /* Comprobante en base 64 */
        $params['xmlComprobanteBase64'] = $base64Comprobante;
        /* Id del comprobante, deberá ser un identificador único, para efecto del ejemplo se utilizará un numero aleatorio */
        $params['idComprobante'] = rand(5, 999999);

        $context = stream_context_create(array(
            'ssl' => array(
                // set some SSL/TLS specific options
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => false
            ),
            'http' => array(
                'user_agent' => 'PHPSoapClient'
            )
        ));
        $options = array();
        $options['stream_context'] = $context;
        $options['cache_wsdl'] = WSDL_CACHE_MEMORY;
        $options['trace'] = true;

        libxml_disable_entity_loader(false);
        // echo "SoapClient";

        $client = new SoapClient($ws, $options);
        // echo "__soapCall";
        $response = $client->__soapCall('TimbraCFDI', array('parameters' => $params));
         } catch (SoapFault $fault) {
         echo "SOAPFault: " . $fault->faultcode . "-" . $fault->faultstring . "\n";
    }

        $tipoExcepcion = $response->TimbraCFDIResult->anyType[0];
        $numeroExcepcion = $response->TimbraCFDIResult->anyType[1];
        $descripcionResultado = $response->TimbraCFDIResult->anyType[2];
        $xmlTimbrado = $response->TimbraCFDIResult->anyType[3];
        $codigoQr = $response->TimbraCFDIResult->anyType[4];
        $cadenaOriginal = $response->TimbraCFDIResult->anyType[5];
        $errorInterno = $response->TimbraCFDIResult->anyType[6];
        $mensajeInterno = $response->TimbraCFDIResult->anyType[7];
        $detalleError = $response->TimbraCFDIResult->anyType[8];

    if ($xmlTimbrado != '') {
        /* El comprobante fue timbrado correctamente */
        /* Guardamos comprobante timbrado */
        $nombrefactura_t = $workspace . 'comprobanteTimbrado.xml';
        file_put_contents($workspace . 'comprobanteTimbrado.xml', $xmlTimbrado);
        /* Guardamos codigo qr */
        file_put_contents($workspace . 'codigoQr.jpg', $codigoQr);
        /* Guardamos cadena original del complemento de certificacion del SAT */
        file_put_contents($workspace . 'cadenaOriginal.txt', $cadenaOriginal);

        $sql_xml_up = "UPDATE cfdis set timbrado = 1, url = '" . $nombrefactura_t . "' where folio = '" . $directorio . "';";
        $mysqli->query($sql_xml_up);

        return $nombrefactura_t;
        
        }else 
        {
        echo "[" . $tipoExcepcion . "  " . $numeroExcepcion . " " . $descripcionResultado . "  ei=" . $errorInterno . " mi=" . $mensajeInterno . "]";

        $sql_xml_up1 = "UPDATE cfdis set numero_excepcion  = '" . $numeroExcepcion . "' where folio = '" . $directorio . "';";
        $mysqli->query($sql_xml_up1);

        $sql_xml_up2 = "UPDATE cfdis set descripcion_resultado  = '" . $descripcionResultado . "' where folio = '" . $directorio . "';";
        $mysqli->query($sql_xml_up2);

        $sql_xml_up3 = "UPDATE cfdis set error_timbrado  = 1 where folio = '" . $directorio . "';";
        $mysqli->query($sql_xml_up3);

        $sql_xml_up4 = "UPDATE cfdis set error_interno_ei  = '" . $errorInterno . "'  where folio = '" . $directorio . "';";
        $mysqli->query($sql_xml_up4);

        $sql_xml_up5 = "UPDATE cfdis set error_interno_mi = '" . $mensajeInterno ."' where folio = '" . $directorio . "';";
        $mysqli->query($sql_xml_up5);

  
    }
}


function generarPDF($nombrexml, $directorio, $idcompra, $email,$comentarios,$codigo_postal,$id_almacen,$direccion){
    include '../../../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $nombrexml = $nombrexml;
 

    $xml = new SimpleXMLElement($nombrexml, 0, true);
    $ns = $xml->getNamespaces(true);
    $xml->registerXPathNamespace('c', $ns['cfdi']);
    $xml->registerXPathNamespace('t', $ns['tfd']);

    $sql_con = "SELECT razonsocial FROM razones_sociales where id = $id_almacen";
    $resultSet_con = $mysqli->query($sql_con);
    $fila_con = $resultSet_con->fetch_assoc();

    $nombreempresa = $fila_con['razonsocial'];

    $sql_con2 = "SELECT direccion FROM razones_sociales where id = $id_almacen";
    $resultSet_con2 = $mysqli->query($sql_con2);
    $fila_con2 = $resultSet_con2->fetch_assoc();

    $direccionemp = $fila_con2['direccion'];

    $sql_con3 = "SELECT rfc FROM razones_sociales where id = $id_almacen";
    $resultSet_con3 = $mysqli->query($sql_con3);
    $fila_con3 = $resultSet_con3->fetch_assoc();

    $rfcemp = $fila_con3['rfc'];

    $sql_con5 = "SELECT ciudad FROM razones_sociales where id = $id_almacen";
    $resultSet_con5 = $mysqli->query($sql_con5);
    $fila_con5 = $resultSet_con5->fetch_assoc();
    $localidad = $fila_con5['ciudad'];
    
    $sql_con6 = "SELECT colonia FROM razones_sociales where id = $id_almacen";
    $resultSet_con6 = $mysqli->query($sql_con6);
    $fila_con6 = $resultSet_con6->fetch_assoc();
    $colonia = $fila_con6['colonia'];

    $sql_imagen= "SELECT image FROM razones_sociales where id = $id_almacen";
    $resultSet_imagen = $mysqli->query($sql_imagen);
    $fila_imagen = $resultSet_imagen->fetch_assoc();
    $imagen_razon =  $fila_imagen['image'];


    // Queremos hacer en pdf la factura numero 1 de la tipica BBDD de facturacion
    require('../../../fpdf/fpdf.php');
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);
    

    foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante) {
        $cfdi = $cfdiComprobante['LugarExpedicion'];
        $cfdi1 = $cfdiComprobante['Serie'];
        $cfdi13 = $cfdiComprobante['Folio'];
        $cfdi3 = $cfdiComprobante['Total'];
        $cfdi4 = $cfdiComprobante['SubTotal'];
        $cfdi8 = $cfdiComprobante['Moneda'];
        $cfdi9 = $cfdiComprobante['MetodoPago'];
        $cfdi10 = $cfdiComprobante['FormaPago'];
        $cfdi11 = $cfdiComprobante['NoCertificado'];
        $cfdi12 = $cfdiComprobante['Version'];
        $cfdi14 = $cfdiComprobante['TipoDeComprobante'];
        $cfdi15 = $cfdiComprobante['Exportacion'];

        
       
        include "../../../core/controller/Core.php";
        include "../../../core/controller/Database.php";
        include "../../../core/controller/Executor.php";
        include "../../../core/controller/Model.php";
        include "../../../core/app/model/RazonData.php";
      

        $stock = RazonData::getById($id_almacen);
        // Imprimimos el logo a 300 ppp
        //$pdf->Image("logo.jpeg", 30, 3, 35);
        if($stock->image!=""){
            $ticket_image = $imagen_razon;} 
          if ($ticket_image != "") {
              $src = "../../../storage/razones_sociales/".$ticket_image;
              if (file_exists($src)) {
              $pdf->Image($src, 10, 1, 50);
                }
            }


        // Consulta a la base de datos para sacar cosas de la factura 1
        // 1º Datos del cliente
        $xml->registerXPathNamespace("tfd", "http://www.sat.gob.mx/TimbreFiscalDigital");
        foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//tfd:TimbreFiscalDigital') as $tfd) {
            $sello4 = $tfd['NoCertificadoSAT'];
            $sello5 = $tfd['UUID'];
      
            foreach ($xml->xpath('//cfdi:Comprobante//cfdi:CfdiRelacionado') as $relacionado) {
            $relacion = $relacionado['UUID'];}

            foreach ($xml->xpath('//cfdi:Comprobante//cfdi:CfdiRelacionados') as $relacion) {
            $tipodoc = $relacion['TipoRelacion'];}
        
      
            foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor) {
            foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor) {
                $emisor1 = $Emisor['Nombre'];
                $emisor2 = $Emisor['Rfc'];
                $emisor3 = $Emisor['RegimenFiscal']; 
                $texto1 = "" . $nombreempresa . " \nRFC : \n" . $Emisor['Rfc'] . "\nRegimen Fiscal : \n".rgmen($emisor3);
             
                $pdf->SetXY(70, 5);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFont("Arial", "B",10);
                $pdf->Cell(70, 4, utf8_decode($nombreempresa), "C");
               
                $pdf->SetXY(70, 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFont("Arial", "B", 8);
                $pdf->Cell(70, 4,"R.F.C : ". $Emisor['Rfc'], "C"); 

                $pdf->SetXY(70, 15);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFont("Arial", "B", 8);
                $pdf->MultiCell(70, 4, "Regimen Fiscal : ".utf8_decode(rgmen($emisor3)),"C"); 

                $pdf->SetXY(70, 25);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFont("Arial", "B", 8);
                $pdf->Cell(70, 4, "Lugar de Expedicion : ".$cfdi, "C"); 

                $cfdi2 = $tfd['FechaTimbrado'];
                $pdf->SetXY(70, 30);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFont("Arial", "B", 8);
                $pdf->Cell(70, 4, "Fecha y Hora de Expedicion : ".$cfdi2, "C");
                    
                }

               
                $pdf->SetXY(150, 3);
                $pdf->SetFillColor(204, 204, 204);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetFont("Arial", "B", 9);
                $pdf->Cell(50, 4, "FACTURA", 1, 0, "C", true);

                $texto2 = "Versión Facturación: " . $cfdi12 . "" . utf8_decode(" \nSerie Factura : "). $cfdi1 . "\nFolio Factura : "  .$cfdi13 ."\nUUID: " .$sello5."\nTipo Comprobante : "  .tipo($cfdi14)."\nExportación : ".  exportacion($cfdi15). "\nRegimen Fiscal : ".  rgmen($emisor3). "";
                $pdf->SetXY(150, 8);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFont("Arial", "B", 6);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->MultiCell(50, 4, utf8_decode($texto2), 1, "l", true);

                
                $pdf->SetXY(10, 42);
                $pdf->SetFillColor(204, 204, 204);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetFont("Arial", "B", 7);
                $pdf->Cell(195, 4, "INFORMACION DEL CLIENTE", 1, 0, "C", true);

                $Receptor1 = $Receptor['Nombre'];
                $Receptor2 = $Receptor['Rfc'];
                $Receptor3 = uso($Receptor['UsoCFDI']);
                $Receptor4 = $Receptor['RegimenFiscalReceptor'];
                $sello1 = $tfd['SelloCFD'];
                $sello2 = $tfd['SelloSAT'];
              
                $cfdi2 = $tfd['FechaTimbrado'];
                $texto1 = utf8_decode($Receptor1) . "\nR.F.C : " . utf8_decode($Receptor2) . "\nUSO DE CFDI : " . $Receptor3. "\nCODIGO POSTAL : " . $codigo_postal."\nDIRECCION : " . $direccion."\nREGIMEN FISCAL : " . utf8_decode(rgmen($Receptor4));
                $pdf->SetXY(10, 47);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFont("Arial", "B", 7);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->MultiCell(195, 4, $texto1, 1, "L", true);

            }
        }
        
              
        $pdf->SetXY(10, 75);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont("Arial", "B", 6);
        $pdf->SetFillColor(204, 204, 204);
        $pdf->MultiCell(195, 4,"Comentarios: ". $comentarios, 1, "L", true);
        
        $pdf->SetXY(10, 85);
        $pdf->SetFillColor(204, 204, 204);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont("Arial", "B", 7);
        $pdf->Cell(15, 6, "Cvepr.", 1, 0, "C", true);
        $pdf->Cell(19, 6, utf8_decode("Código"), 1, 0,"C", true);
        $pdf->Cell(12, 6, "Cant", 1, 0, "C", true);
        $pdf->Cell(19, 6, "Unidad", 1, 0, "C", true);
        $pdf->Cell(83, 6, utf8_decode("Descripción Productos"), 1, 0, "C", true);
        $pdf->Cell(25, 6, "Precio Unitario", 1, 0, "C", true);
        $pdf->Cell(22, 6, "Total", 1, 1, "C", true);
                $total = 0;

        // Los datos (en negro)
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont("Arial", "B", 6);
        // aquí le decimos que busque el nodo padre Comprobante y dentro de el busque el nodo Emisor para
        // así encontrar los atributos.

        foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $Conceptos) {  // SECCION EMISOR
            $claveser = $Conceptos['ClaveProdServ'];
            $descripcion = $Conceptos['Descripcion'];
            $claveunidad = $Conceptos['ClaveUnidad'];
            $NoIdentificacion = $Conceptos['NoIdentificacion'];
            $unidad = $Conceptos['Unidad'];
            $cantidad = $Conceptos['Cantidad'];
            $vunit = $Conceptos['ValorUnitario'];
            $import = $Conceptos['Importe'];
            $pdf->SetFillColor(237, 237, 237);
            $start_x=$pdf->GetX(); //initial x (start of column position)
            $current_y = $pdf->GetY();
            $current_x = $pdf->GetX();
            $cell_width_clave = 15;  //define cell width
            $cell_width_ide = 19;
            $cell_width_cantidad = 12;
            $cell_width_unidad = 19;
            $cell_width_punitario = 25;
            $cell_width_descuento = 5;
            $cell_width_total = 22;
            $cell_width_descripcion = 83;
            $cell_height=7;    //define cell height

            $pdf->SetFont('Arial','',7);

            $pdf->MultiCell($cell_width_clave,$cell_height,$claveser, 1, 'J', 1, 1, '' ,'', true); //print one cell value
            $current_x+=$cell_width_clave;                           //calculate position for next cell
            $pdf->SetXY($current_x, $current_y);               //set position for next cell to print

            $pdf->MultiCell($cell_width_ide,$cell_height,$NoIdentificacion, 1, 'C', 1, 1, '' ,'', true); //printing next cell
            $current_x+=$cell_width_ide;                           //re-calculate position for next cell
            $pdf->SetXY($current_x, $current_y);               //set position for next cell

            $pdf->MultiCell($cell_width_cantidad,$cell_height,$cantidad, 1, 'C', 1, 1, '' ,'', true);
            $current_x+=$cell_width_cantidad;
            $pdf->SetXY($current_x, $current_y);  
            $pdf->SetFont('Arial','',6);
            $pdf->MultiCell($cell_width_unidad,$cell_height,$claveunidad."-".$unidad, 1, 'C', 1, 1, '' ,'', true);
            $current_x+=$cell_width_unidad;
            $pdf->SetXY($current_x, $current_y); 

            $pdf->MultiCell($cell_width_descripcion,7,utf8_decode($descripcion), 1, '', 1, 1, '' ,'', true);
            $current_x+=$cell_width_descripcion;
            $pdf->SetXY($current_x, $current_y);   

            $pdf->MultiCell($cell_width_punitario,$cell_height,"$ ".$vunit, 1, 'C', 1, 1, '' ,'', true);
            $current_x+=$cell_width_punitario;
            $pdf->SetXY($current_x, $current_y);   

            $pdf->MultiCell($cell_width_total,$cell_height,"$ ".$import, 1, 'C', 1, 1, '' ,'', true);
            $current_x+=$cell_width_total;
            $pdf->SetXY($current_x, $current_y);   

            $pdf->Ln();
            $current_x=$start_x;                       //set x to start_x (beginning of line)
            $current_y+=$cell_height;   
            $pdf->SetXY($current_x, $current_y);                  //increase y by cell_height to print on next line
}

            foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Retencion') as $retencion) {
            $retenciones[] = $retencion['Importe']; // Guardar solo el importe } 
            $isr = $retenciones[0]; // porción1
            $ivr = $retenciones[1]; // porción2
              }
            
            foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos') as $impuestos) {  // SECCION EMISOR
            $impuestos = $impuestos['TotalImpuestosTrasladados'];
       
            $pdf->SetFont("Arial", "B", 8);
          // 4º Los totales, IVAs y demás
        
             $pdf->Line(10, 195, 200, 195);
             $pdf->SetXY(150, 200);

            $pdf->SetFillColor(204, 204, 204);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(35, 6, "SUBTOTAL: ", 1, 0, "C", true);
            $pdf->SetFillColor(237, 237, 237);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(20, 6, number_format(floatval($cfdi4), 2), 1, 1, "R", true);

            $pdf->SetXY(150, 205);
            $pdf->SetFillColor(204, 204, 204);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(35, 6, "IVA (16 %)", 1, 0, "C", true);
            $pdf->SetFillColor(237, 237, 237);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(20, 6, number_format(floatval($impuestos), 2), 1, 1, "R", true);

            $pdf->SetXY(150, 210);
            $pdf->SetFillColor(204, 204, 204);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(35, 6, "ISR (1.25 %)", 1, 0, "C", true);
            $pdf->SetFillColor(237, 237, 237);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(20, 6, number_format(floatval($isr), 2), 1, 1, "R", true);
            
            $pdf->SetXY(150, 215);
            $pdf->SetFillColor(204, 204, 204);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(35, 6, "IVA Retenido (0.106666)", 1, 0, "C", true);
            $pdf->SetFillColor(237, 237, 237);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(20, 6, number_format(floatval($ivr), 2), 1, 1, "R", true);

            $pdf->SetXY(150, 220);
            $pdf->SetFillColor(204, 204, 204);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(35, 6, "TOTAL: ", 1, 0, "C", true);
            $pdf->SetFillColor(237, 237, 237);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(20, 6, number_format(floatval($cfdi3), 2), 1, 1, "R", true);
        }
     
            $texto1 = $monto_letras = numletras($cfdi3, 1);
        
    

        $pdf->SetXY(10, 197);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont("Arial", "B", 8);
        $pdf->MultiCell(135, 3, "CANTIDAD CON LETRAS : ", 0, "L");

        $pdf->SetXY(10, 202);
        $pdf->SetFont("Arial", "B", 7);
        $pdf->MultiCell(135, 3, $texto1 . mone($cfdi8), 0, "L");

        $pdf->SetXY(10, 207);
        $pdf->SetFont("Arial", "", 8);
        $pdf->MultiCell(135, 3, "Moneda: " . mone($cfdi8) . " | Metodo de Pago: " . metodope($cfdi9) . " ", 0, "L");

        
        $pdf->SetXY(10, 212);
        $pdf->SetFont("Arial", "", 8);
        $pdf->MultiCell(135, 2, "Forma de Pago : " .  formap($cfdi10), 0, "L");

        $pdf->SetXY(10, 216);
        $pdf->SetFont("Arial", "", 8);
        $pdf->MultiCell(135, 2, "Fecha Timbrado : " . $cfdi2, 0, "L");

        $pdf->SetXY(10, 220);
        $pdf->SetFont("Arial", "", 8);
        $pdf->MultiCell(135, 2, "No. de Serie del Certificado del SAT: ". $sello4, 0, "L");

        $pdf->SetXY(10, 226);
        $pdf->SetFont("Arial", "B", 8);
        $pdf->MultiCell(135, 2, "Tipo Relacion: ". $relacion['TipoRelacion'] , 0, "L");

        
        $pdf->SetXY(10, 230);
        $pdf->SetFont("Arial", "B", 8);
        $pdf->MultiCell(135, 2, "Documento Relacionado: ".  $relacionado['UUID']  , 0, "L");

     
        $texto1 = "SELLO DIGITAL DEL CFDI  " . $sello1;

        $pdf->SetXY(10, 235);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont("Arial", "B", 6);
        $pdf->MultiCell(135, 3, "SELLO DIGITAL DEL CFDI ", 0, "L");

        $pdf->SetXY(10, 240);
        $pdf->SetFont("Arial", "", 6);
        $pdf->MultiCell(135, 3, $sello1, 0, "L");

        $texto1 = "SELLO DIGITAL DEL SAT  " . $sello2;
        $pdf->SetXY(10, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont("Arial", "B", 6);
        $pdf->MultiCell(135, 3, "SELLO DIGITAL DEL SAT ", 0, "L");

        $pdf->SetXY(10, 260);
        $pdf->SetFont("Arial", "", 6);
        $pdf->MultiCell(135, 3, $sello2, 0, "L");
     } 


    $pdf->Image(getcwd() . '/facturas/' . $directorio . '/' . $directorio . '.xmlcodigoQr.jpg', 160, 230, 35, 38);

    $pdf->Line(10, 275, 200, 275);
    $pdf->Text(10, 280, utf8_decode("ESTE DOCUMENTO ES UNA REPRESENTACIÓN IMPRESA DE UN CFDI VERSIÓN 4.0."));

    $sql_xml_up = "update cfdis set uuid = '" . $sello5 . "' where folio = '" . $directorio . "';";
    $mysqli->query($sql_xml_up);

    $sql_xml_up2 = "update sell set invoice_code = '" . $sello5 . "' where id = '" . $idcompra . "';";
    $mysqli->query($sql_xml_up2);

// El documento enviado al navegador
    $pdf->Output($nombrexml . '.pdf', "F");

    enviar_archivos($nombrexml . '.pdf', $directorio, $email, $nombrexml,$Receptor1,$Receptor2,$Receptor3);
    ?>

    
    <script>
    if (confirm("Se ha timbrado la factura correctamente, el sistema enviara el PDF y XML al correo del cliente registrado. -Preciona Aceptar para terminar el proceso- "))
    window.location = "http://localhost/hules_automotrices/?view=facturas"
    </script>
    <?php
    }


function tipo($identificador) {
    if ($identificador == "I") {
        $result = "I - Ingreso";
    }
    if ($identificador == "E") {
        $result = "E - Egreso";
    }
    
    return $result;
}

function exportacion($identificador) {
    if ($identificador == "01") {
        $result = "01 - No aplica";
    }
    if ($identificador == "02") {
        $result = "02 - Definitiva";
    }
    
      if ($identificador == "03") {
        $result = "03 - Temporal";
    }
    
      if ($identificador == "04") {
        $result = "04 - Definitiva con clave distinta A1 o cuando no existe enjenacion en terminos del CFF";
    }
    
    
    return $result;
}


function mone($identificador) {
    if ($identificador == "MXN") {
        $result = "MXN - PESOS";
    }

    if ($identificador == "USD") {
        $result = "USD - DOLARES";
    }
    if ($identificador == "EUR") {
        $result = "EUR - EUROS";
    }

    return $result;
}

function metodope($identificador) {
    if ($identificador == "PUE") {
        $result = "PUE - Pago en una sola exhibicion";
    }

    if ($identificador == "PPD") {
        $result = "PPD - Pago en parcialidades o diferidos";
    }

    return $result;
}

function uso($identificador) {
    if ($identificador == "G01") {
        $result = "G01 - Adquisicion de mercancias";
    }

    if ($identificador == "G02") {
        $result = "G02 - Devoluciones, descuentos o bonificaciones";
    }
    if ($identificador == "G03") {
        $result = "G03 - Gastos en general";
    }
    if ($identificador == "I01") {
        $result = "I01 - Construcciones";
    }
    if ($identificador == "I02") {
        $result = "I02 - Mobilario y equipo de oficina por inversiones";
    }
    if ($identificador == "I03") {
        $result = "I03 - Equipo de transporte";
    }
    if ($identificador == "I04") {
        $result = "I04 - Equipo de computo y accesorios";
    }
    if ($identificador == "I05") {
        $result = "I05 - Dados, troqueles, moldes, matrices y herramental	";
    }
    if ($identificador == "I06") {
        $result = "I06 - Comunicaciones telef�nicas	";
    }
    if ($identificador == "I07") {
        $result = "I07 - Comunicaciones satelitales";
    }
    if ($identificador == "I08") {
        $result = "I08 - Otra maquinaria y equipo";
    }
    if ($identificador == "D01") {
        $result = "D01 - Honorarios m�dicos, dentales y gastos hospitalarios.";
    }
    if ($identificador == "D02") {
        $result = "D02 - Gastos m�dicos por incapacidad o discapacidad";
    }
    if ($identificador == "D03") {
        $result = "D03 - Gastos funerales.";
    }
    if ($identificador == "D04") {
        $result = "D04 - Donativos.";
    }
    if ($identificador == "D05") {
        $result = "D05 - Intereses reales efectivamente pagados por cr�ditos hipotecarios (casa habitaci�n).";
    }
    if ($identificador == "D06") {
        $result = "D06 - Aportaciones voluntarias al SAR.";
    }
    if ($identificador == "D07") {
        $result = "D07 - Primas por seguros de gastos m�dicos.";
    }
    if ($identificador == "D08") {
        $result = "D08 - Gastos de transportaci�n escolar obligatoria.";
    }
    if ($identificador == "D09") {
        $result = "D09 - Dep�sitos en cuentas para el ahorro, primas que tengan como base planes de pensiones.";
    }

    if ($identificador == "D10") {
        $result = "D10 - Pagos por servicios educativos (colegiaturas)";
    }

    if ($identificador == "CP01") {
        $result = "CP01 - Pagos";
    }
    
     if ($identificador == "CN01") {
        $result = "CN01 - Nomina";
    }
    
      if ($identificador == "S01") {
        $result = "S01 - Sin Efectvos Fiscales";
    }
  
    return $result;
}

function formap($identificador) {
    if ($identificador == "01") {
        $result = "01 - Efectivo";
    }

    if ($identificador == "02") {
        $result = "02 - Cheque Nominativo";
    }
    if ($identificador == "03") {
        $result = "03 - Transferencia electronica de fondos";
    }
    if ($identificador == "04") {
        $result = "04 - Tarjeta de credito";
    }
    if ($identificador == "05") {
        $result = "05 - Monedero electronico";
    }
    if ($identificador == "06") {
        $result = "06 - Dienero electronico";
    }
    if ($identificador == "08") {
        $result = "08 - Vales de despensa";
    }
    if ($identificador == "12") {
        $result = "12 - Dacion de pago";
    }
    if ($identificador == "13") {
        $result = "13 - Pago por subrogacion";
    }
    if ($identificador == "14") {
        $result = "14 - Pago por consignacion";
    }
    if ($identificador == "15") {
        $result = "15 - Condonacion";
    }
    if ($identificador == "17") {
        $result = "17 - Compensacion";
    }
    if ($identificador == "23") {
        $result = "23 - Novacion";
    }
    if ($identificador == "24") {
        $result = "24 - Confusion";
    }
    if ($identificador == "25") {
        $result = "25 - Remision de deuda";
    }
    if ($identificador == "26") {
        $result = "26 - Prescripcion o caducidad";
    }
    if ($identificador == "27") {
        $result = "27 - A satisfaccion del acreedor";
    }
    if ($identificador == "28") {
        $result = "28 - Tarjeta de debito";
    }
    if ($identificador == "29") {
        $result = "29 - Tarjeta de servicios";
    }
    if ($identificador == "99") {
        $result = "99 - Por definir.";
    }

    return $result;
}

function rgmen($identificador) {
    if ($identificador == "601") {
        $result = "601 - General de Ley Personas Morales";
    }
    if ($identificador == "603") {
        $result = "603 - Personas Morales con Fines no Lucrativos";
    }
    if ($identificador == "605") {
        $result = "605 - Sueldos y Salarios e Ingresos Asimilados a Salarios";
    }
    if ($identificador == "606") {
        $result = "606 - Arrendamiento";
    }
    if ($identificador == "608") {
        $result = "608 - Dem s ingresos";
    }
    if ($identificador == "609") {
        $result = "609 - Consolidaci n";
    }
    if ($identificador == "610") {
        $result = "610 - Residentes en el Extranjero sin Establecimiento Permanente en M xico";
    }
    if ($identificador == "611") {
        $result = "611 - Ingresos por Dividendos (socios y accionistas)";
    }
    if ($identificador == "612") {
        $result = "612 - Personas Fisicas con Actividades Empresariales y Profesionales";
    }
    if ($identificador == "614") {
        $result = "614 - Ingresos por intereses";
    }
    if ($identificador == "616") {
        $result = "616 - Sin obligaciones fiscales";
    }
    if ($identificador == "620") {
        $result = "620 - Sociedades Cooperativas de Producci n que optan por diferir sus ingresos";
    }
    if ($identificador == "621") {
        $result = "621 - Incorporación Fiscal";
    }
    if ($identificador == "622") {
        $result = "622 - Actividades Agr colas, Ganaderas, Silv colas y Pesqueras";
    }
    if ($identificador == "623") {
        $result = "623 - Opcional para Grupos de Sociedades";
    }
    if ($identificador == "624") {
        $result = "624 - Coordinados";
    }
    if ($identificador == "625") {
        $result = "625 - Regimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas";
    }
    if ($identificador == "626") {
        $result = "626 - Regimen Simplificado de Confianza";
    }
    if ($identificador == "628") {
        $result = "628 - Hidrocarburos";
    }
    if ($identificador == "607") {
        $result = "607 - R gimen de Enajenaci n o Adquisici n de Bienes";
    }
    if ($identificador == "629") {
        $result = "629 - De los Reg menes Fiscales Preferentes y de las Empresas Multinacionales";
    }
    if ($identificador == "630") {
        $result = "630 - Enajenaci n de acciones en bolsa de valores";
    }
    if ($identificador == "615") {
        $result = "615 - R gimen de los ingresos por obtenci n de premios";
    }

    return $result;
}


function amoneda($numero, $moneda) {
    $longitud = strlen($numero);
    $punto = substr($numero, -1, 1);
    $punto2 = substr($numero, 0, 1);
    $separador = ".";

    if ($punto == ".") {

        $numero = substr($numero, 0, $longitud - 1);
        $longitud = strlen($numero);
    }

    if ($punto2 == ".") {

        $numero = "0" . $numero;
        $longitud = strlen($numero);
    }

    $num_entero = strpos($numero, $separador);
    $centavos = substr($numero, ($num_entero));
    $l_cent = strlen($centavos);

    if ($l_cent == 2) {
        $centavos = $centavos . "0";
    } elseif ($l_cent == 3) {
        $centavos = $centavos;
    } elseif ($l_cent > 3) {
        $centavos = substr($centavos, 0, 3);
    }
    $entero = substr($numero, -$longitud, $longitud - $l_cent);
    if (!$num_entero) {
        $num_entero = $longitud;
        $centavos = ".00";
        $entero = substr($numero, -$longitud, $longitud);
    }

    $start = floor($num_entero / 3);
    $res = $num_entero - ($start * 3);

    if ($res == 0) {
        $coma = $start - 1;
        $init = 0;
    } else {
        $coma = $start;
        $init = 3 - $res;
    }
    $d = $init;
    $i = 0;
    $c = $coma;

    while ($i <= $num_entero) {

        if ($d == 3 && $c > 0) {
            $d = 0;
            $sep = "";
            $c = $c - 1;
        } else {
            $sep = "";
        }

        $final .= $sep . $entero[$i];
        $i = $i + 1; // todos los digitos
        $d = $d + 1; // poner las comas
    }
    if ($moneda == "pesos") {
        $moneda = "";
        return $moneda . "" . $final . $centavos;
    } elseif ($moneda == "dolares") {
        $moneda = "";
        return $moneda . " " . $final . $centavos;
    } elseif ($moneda == "euros") {
        $moneda = "EUR";
        return $final . $centavos . " " . $moneda;
    }
}

error_reporting('E_PARSE');

function satxmlsv33_genera_cadena_original($xml, $cadena) {
    $paso = new DOMDocument("1.0", "UTF-8");
    $paso->loadXML($xml->saveXML());
    $xsl = new DOMDocument("1.0", "UTF-8");
    $file = $cadena;     // Ruta al archivo
    $xsl->load($file);
    $proc = new XSLTProcessor;
    $proc->importStyleSheet($xsl);
    return $proc->transformToXML($paso);
}

function numletras($numero, $_moneda)
/*
  $numero=valor a retornar en letras.
  $_moneda=1=Colones, 2=D�lares 3=Euros
  Las siguientes funciones (unidad() hasta milmillon() forman parte de �sta funci�n
 */ {
    switch ($_moneda) {
        case 1:
            $_nommoneda = 'PESOS';
            break;
        case 2:
            $_nommoneda = 'D�LARES';
            break;
        case 3:
            $_nommoneda = 'EUROS';
            break;
    }
//*** 
    $tempnum = explode('.', $numero);

    if ($tempnum[0] !== "") {
        $numf = milmillon($tempnum[0]);
        if ($numf == "UNO") {
            $numf = substr($numf, 0, -1);
        }

        $TextEnd = $numf . ' ';
        $TextEnd .= $_nommoneda . ' ';
    }
    if ($tempnum[1] == "" || $tempnum[1] >= 100) {
        $tempnum[1] = "00";
    }
    $TextEnd .= $tempnum[1];
    $TextEnd .= "/100";
    return $TextEnd;
}

function unidad($numuero) {
    switch ($numuero) {
        case 9: {
                $numu = "NUEVE";
                break;
            }
        case 8: {
                $numu = "OCHO";
                break;
            }
        case 7: {
                $numu = "SIETE";
                break;
            }
        case 6: {
                $numu = "SEIS";
                break;
            }
        case 5: {
                $numu = "CINCO";
                break;
            }
        case 4: {
                $numu = "CUATRO";
                break;
            }
        case 3: {
                $numu = "TRES";
                break;
            }
        case 2: {
                $numu = "DOS";
                break;
            }
        case 1: {
                $numu = "UNO";
                break;
            }
        case 0: {
                $numu = "";
                break;
            }
    }
    return $numu;
}

function decena($numdero) {

    if ($numdero >= 90 && $numdero <= 99) {
        $numd = "NOVENTA ";
        if ($numdero > 90)
            $numd = $numd . "Y " . (unidad($numdero - 90));
    }
    else if ($numdero >= 80 && $numdero <= 89) {
        $numd = "OCHENTA ";
        if ($numdero > 80)
            $numd = $numd . "Y " . (unidad($numdero - 80));
    }
    else if ($numdero >= 70 && $numdero <= 79) {
        $numd = "SETENTA ";
        if ($numdero > 70)
            $numd = $numd . "Y " . (unidad($numdero - 70));
    }
    else if ($numdero >= 60 && $numdero <= 69) {
        $numd = "SESENTA ";
        if ($numdero > 60)
            $numd = $numd . "Y " . (unidad($numdero - 60));
    }
    else if ($numdero >= 50 && $numdero <= 59) {
        $numd = "CINCUENTA ";
        if ($numdero > 50)
            $numd = $numd . "Y " . (unidad($numdero - 50));
    }
    else if ($numdero >= 40 && $numdero <= 49) {
        $numd = "CUARENTA ";
        if ($numdero > 40)
            $numd = $numd . "Y " . (unidad($numdero - 40));
    }
    else if ($numdero >= 30 && $numdero <= 39) {
        $numd = "TREINTA ";
        if ($numdero > 30)
            $numd = $numd . "Y " . (unidad($numdero - 30));
    }
    else if ($numdero >= 20 && $numdero <= 29) {
        if ($numdero == 20)
            $numd = "VEINTE ";
        else
            $numd = "VEINTI" . (unidad($numdero - 20));
    }
    else if ($numdero >= 10 && $numdero <= 19) {
        switch ($numdero) {
            case 10: {
                    $numd = "DIEZ ";
                    break;
                }
            case 11: {
                    $numd = "ONCE ";
                    break;
                }
            case 12: {
                    $numd = "DOCE ";
                    break;
                }
            case 13: {
                    $numd = "TRECE ";
                    break;
                }
            case 14: {
                    $numd = "CATORCE ";
                    break;
                }
            case 15: {
                    $numd = "QUINCE ";
                    break;
                }
            case 16: {
                    $numd = "DIECISEIS ";
                    break;
                }
            case 17: {
                    $numd = "DIECISIETE ";
                    break;
                }
            case 18: {
                    $numd = "DIECIOCHO ";
                    break;
                }
            case 19: {
                    $numd = "DIECINUEVE ";
                    break;
                }
        }
    } else
        $numd = unidad($numdero);
    return $numd;
}

function centena($numc) {
    if ($numc >= 100) {
        if ($numc >= 900 && $numc <= 999) {
            $numce = "NOVECIENTOS ";
            if ($numc > 900)
                $numce = $numce . (decena($numc - 900));
        }
        else if ($numc >= 800 && $numc <= 899) {
            $numce = "OCHOCIENTOS ";
            if ($numc > 800)
                $numce = $numce . (decena($numc - 800));
        }
        else if ($numc >= 700 && $numc <= 799) {
            $numce = "SETECIENTOS ";
            if ($numc > 700)
                $numce = $numce . (decena($numc - 700));
        }
        else if ($numc >= 600 && $numc <= 699) {
            $numce = "SEISCIENTOS ";
            if ($numc > 600)
                $numce = $numce . (decena($numc - 600));
        }
        else if ($numc >= 500 && $numc <= 599) {
            $numce = "QUINIENTOS ";
            if ($numc > 500)
                $numce = $numce . (decena($numc - 500));
        }
        else if ($numc >= 400 && $numc <= 499) {
            $numce = "CUATROCIENTOS ";
            if ($numc > 400)
                $numce = $numce . (decena($numc - 400));
        }
        else if ($numc >= 300 && $numc <= 399) {
            $numce = "TRESCIENTOS ";
            if ($numc > 300)
                $numce = $numce . (decena($numc - 300));
        }
        else if ($numc >= 200 && $numc <= 299) {
            $numce = "DOSCIENTOS ";
            if ($numc > 200)
                $numce = $numce . (decena($numc - 200));
        }
        else if ($numc >= 100 && $numc <= 199) {
            if ($numc == 100)
                $numce = "CIEN ";
            else
                $numce = "CIENTO " . (decena($numc - 100));
        }
    } else
        $numce = decena($numc);

    return $numce;
}

function miles($nummero) {
    if ($nummero >= 1000 && $nummero < 2000) {
        $numm = "MIL " . (centena($nummero % 1000));
    }
    if ($nummero >= 2000 && $nummero < 10000) {
        $numm = unidad(Floor($nummero / 1000)) . " MIL " . (centena($nummero % 1000));
    }
    if ($nummero < 1000)
        $numm = centena($nummero);

    return $numm;
}

function decmiles($numdmero) {
    if ($numdmero == 10000)
        $numde = "DIEZ MIL";
    if ($numdmero > 10000 && $numdmero < 20000) {
        $numde = decena(Floor($numdmero / 1000)) . "MIL " . (centena($numdmero % 1000));
    }
    if ($numdmero >= 20000 && $numdmero < 100000) {
        $numde = decena(Floor($numdmero / 1000)) . " MIL " . (miles($numdmero % 1000));
    }
    if ($numdmero < 10000)
        $numde = miles($numdmero);

    return $numde;
}

function cienmiles($numcmero) {
    if ($numcmero == 100000)
        $num_letracm = "CIEN MIL";
    if ($numcmero >= 100000 && $numcmero < 1000000) {
        $num_letracm = centena(Floor($numcmero / 1000)) . " MIL " . (centena($numcmero % 1000));
    }
    if ($numcmero < 100000)
        $num_letracm = decmiles($numcmero);
    return $num_letracm;
}

function millon($nummiero) {
    if ($nummiero >= 1000000 && $nummiero < 2000000) {
        $num_letramm = "UN MILLON " . (cienmiles($nummiero % 1000000));
    }
    if ($nummiero >= 2000000 && $nummiero < 10000000) {
        $num_letramm = unidad(Floor($nummiero / 1000000)) . " MILLONES " . (cienmiles($nummiero % 1000000));
    }
    if ($nummiero < 1000000)
        $num_letramm = cienmiles($nummiero);

    return $num_letramm;
}

function decmillon($numerodm) {
    if ($numerodm == 10000000)
        $num_letradmm = "DIEZ MILLONES";
    if ($numerodm > 10000000 && $numerodm < 20000000) {
        $num_letradmm = decena(Floor($numerodm / 1000000)) . "MILLONES " . (cienmiles($numerodm % 1000000));
    }
    if ($numerodm >= 20000000 && $numerodm < 100000000) {
        $num_letradmm = decena(Floor($numerodm / 1000000)) . " MILLONES " . (millon($numerodm % 1000000));
    }
    if ($numerodm < 10000000)
        $num_letradmm = millon($numerodm);

    return $num_letradmm;
}

function cienmillon($numcmeros) {
    if ($numcmeros == 100000000)
        $num_letracms = "CIEN MILLONES";
    if ($numcmeros >= 100000000 && $numcmeros < 1000000000) {
        $num_letracms = centena(Floor($numcmeros / 1000000)) . " MILLONES " . (millon($numcmeros % 1000000));
    }
    if ($numcmeros < 100000000)
        $num_letracms = decmillon($numcmeros);
    return $num_letracms;
}

function milmillon($nummierod) {
    if ($nummierod >= 1000000000 && $nummierod < 2000000000) {
        $num_letrammd = "MIL " . (cienmillon($nummierod % 1000000000));
    }
    if ($nummierod >= 2000000000 && $nummierod < 10000000000) {
        $num_letrammd = unidad(Floor($nummierod / 1000000000)) . " MIL " . (cienmillon($nummierod % 1000000000));
    }
    if ($nummierod < 1000000000)
        $num_letrammd = cienmillon($nummierod);

    return $num_letrammd;
}



function enviar_archivos($npdf, $directorio, $email, $nxml,$Receptor1,$Receptor2,$Receptor3) {

    require 'PHPMailerAutoload.php';
    $body = "

                </td>
    <p>Por medio del presente correo, le enviamos los archivos PDF y XML del Comprobante Fiscal Digital por Internet (CFDI V 4.0).</p>
    <br>
    Nombre cliente : $Receptor1
    <br> 
    R.F.C Cliente  : $Receptor2
    <br> 
    Uso del CFDI   : $Receptor3
    <table>
    <tbody>
    </tbody>
    </table>";

    // echo $body;
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Debugoutput = 'html';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );


    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = "sistema.alvacar@gmail.com";
    $mail->Password = "tzhridkbtphxfbhh";

    $mail->From = 'sistema.alvacar@gmail.com';
    $mail->FromName = utf8_decode('Sistema De Facturación');


    $mail->addAddress($email);
    $mail->addAddress('plomerialareyna@gmail.com');
    $mail->Subject = 'Envio De Factura Electronica ';
    $mail->MsgHTML(utf8_decode($body));

    $archivo = 'prueba.pdf';
    $mail->AddAttachment($npdf, 'FacturaPDF.pdf');
    $mail->AddAttachment($nxml, 'FacturaXML.xml');
    $mail->send();
}


?>
