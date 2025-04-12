    <?php
    include '../../../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");
    date_default_timezone_set('America/Mexico_City');

    $complementos = $_POST['complementos'];
    $idcliente    = $_POST['idcliente'];
        
   // $fecha_complemento = date("Y-m-d") . "T" . date("H:i:s");
    $fecha_complemento = date("Y-m-d") . "T" . date("H:i:s", strtotime("-1 hour"));
    
    $ultimoid = "SELECT MAX(idcfdis) AS id FROM complementos";
    $resultSet = $mysqli->query($ultimoid);
    $fila_ultimoid = $resultSet->fetch_assoc();   

    $fact_folio = $fila_ultimoid['id'];
    $foliox = $fact_folio + 1;

    //Sacamos la información del receptor.
    $sql_cliente = "SELECT * FROM person WHERE id =  $idcliente";
    $resultSet_cliente = $mysqli->query($sql_cliente);
    $fila = $resultSet_cliente->fetch_assoc();
    $name_cliente = utf8_encode($fila['name']);
    $last_cliente = utf8_encode($fila['lastname']);
    $no_cliente = $fila['no'];
    $regimen_fiscal = $fila['regimen_fiscal'];
    $codigo_postal = $fila['codigopostal'];
    $direccion_cliente = $fila['address1'];
    $email = $fila['email1'];

    //Sacamos la información de los abonos para los complementos de pago. 
    $sql = "SELECT bitacora_pagos_anticipo.id_cliente,bitacora_pagos_anticipo.id_anticipo,bitacora_pagos_anticipo.remision,cfdis.serie,cfdis.folio,cfdis.mpago,
    cfdis.UUID,bitacora_pagos_anticipo.pagado,bitacora_pagos_anticipo.total_remision,bitacora_pagos_anticipo.nuevo_saldo,person.name,
    bitacora_pagos_anticipo.fecha_operacion,cfdis.tipo_factura,bitacora_abonos.cant_ingresada,bitacora_abonos.referencia_deposito,bitacora_abonos.fecha,bitacora_abonos.forma_pago,
    bitacora_abonos.RfcEmisorCtaOrd,bitacora_abonos.NomBancoOrdExt,bitacora_abonos.CtaOrdenante,bitacora_abonos.RfcEmisorCtaBen,bitacora_abonos.CtaBeneficiario,bitacora_abonos.stock_id
    from bitacora_pagos_anticipo 
    INNER JOIN person on person.id = bitacora_pagos_anticipo.id_cliente 
    INNER JOIN cfdis on cfdis.Folio_venta = bitacora_pagos_anticipo.remision
    INNER JOIN bitacora_abonos on bitacora_abonos.idabonos = bitacora_pagos_anticipo.id_anticipo
    where  bitacora_abonos.operacion = 2  and cfdis.tipo_factura in (1,2) and cfdis.timbrado = 1 and bitacora_pagos_anticipo.id_cliente = $idcliente 
    and bitacora_pagos_anticipo.id_anticipo in ($complementos) order by bitacora_pagos_anticipo.fecha_operacion ASC";
    $resultado = $mysqli->query($sql);  

    $BaseDR = 0 ;
    $ImporteDR = 0 ;
    $TotalTrasladosBaseIVA16 = 0;
    $TotalTrasladosImpuestoIVA16 = 0;
    $TotalTrasladosImpuestoIVA161 = 0;
    $MontoTotalPagos = 0;
    $MontoTotalPagos1 = 0;
    $total_abonado1 = 0;
    
    $BaseP = 0 ;
    $BaseP1 = 0 ;
    $ImporteP = 0 ;
    $ImporteP1 = 0 ;
    

    $m_pagos = [];
    $importe_pagado = 0;
    while($mostrar=mysqli_fetch_array($resultado)) {
        
            $id_almacen = $mostrar['stock_id'];

            //SACAMOS EL NOCERTIFICADO DEL ALMACEN
            $sql_nocer = "SELECT NoCertificado FROM stock where id = $id_almacen";
            $resultSet_nocer = $mysqli->query($sql_nocer);
            $fila_nocer = $resultSet_nocer->fetch_assoc();
            $NoCertificado =  $fila_nocer['NoCertificado'];
    
            //SACAMOS EL CERTIFICADO PARA EL TIMBRADO. 
            $sql_cer = "SELECT Certificado FROM stock where id = $id_almacen";
            $resultSet_cer = $mysqli->query($sql_cer);
            $fila_cer = $resultSet_cer->fetch_assoc();
            $Certificado =  $fila_cer['Certificado'];

            //Sacamos el codigo postal. 
            $sql_cp = "SELECT codigo_postal FROM razones_sociales where id = $id_almacen";
            $resultSet_cp = $mysqli->query($sql_cp);
            $fila_cp = $resultSet_cp->fetch_assoc();
            $cp = trim($fila_cp['codigo_postal']);

            //Sacamos los datos del EMISOR
            $sql_sucursales = "SELECT razonsocial FROM razones_sociales where id = $id_almacen";
            $resultSet_sucursales = $mysqli->query($sql_sucursales);
            $fila_nombreempresa = $resultSet_sucursales->fetch_assoc();
            $nom_emisor = $fila_nombreempresa['razonsocial'];

            $sql_rfcemisor = "SELECT rfc FROM razones_sociales where id = $id_almacen";
            $resultSet_rfcemisor = $mysqli->query($sql_rfcemisor);
            $fila_rfcemisor = $resultSet_rfcemisor->fetch_assoc();
            $rfc_emisor = $fila_rfcemisor['rfc'];


            $sql_reg = "SELECT regimen_fiscal FROM razones_sociales where id = $id_almacen";
            $resultSet_reg = $mysqli->query($sql_reg);
            $fila_reg = $resultSet_reg->fetch_assoc();
            $reg =  $fila_reg['regimen_fiscal'];

            $uuid  = $mostrar['UUID'];
            $serie = $mostrar['serie'];
            $folio = $mostrar['folio'];
            $total_abono = $mostrar['cant_ingresada'];
           // $referencia_abono = $mostrar['referencia_deposito'];
            $metodo_pago = $mostrar['forma_pago'];

            $fecha_abono = $mostrar['fecha'];
            $fecha = date('Y-m-d',strtotime($fecha_abono));
            $hora = date('H:i:s',strtotime($fecha_abono));  
            $fecha_aplicacion = $fecha."T".$hora;

            $saldo_anterior = round($mostrar['total_remision'],2);
            $saldo_pagado = round($mostrar['pagado'],2);
            $saldo_nuevo = round($mostrar['nuevo_saldo'],2);
           
            $BaseDR = round($saldo_pagado/ 1.16,2); // CORRECTA
            $BaseP += round($BaseDR,2); // SUMAMOS EL TOTAL DE LAS BASES DR. 
            
            $ImporteDR = round($BaseDR * 0.16,2); // CORRECTA
            $ImporteP += round($ImporteDR,2); // 5,294.99
          
            /* BASE DR E IMPORTE DR DE ARRIBA TODO CALCULADO CORRECTAMENTE*/
            $BaseP1 = round(($total_abono / 1.16 - $BaseP),2);
            $TotalTrasladosBaseIVA16 = round(($total_abono / 1.16) - $BaseP1,2);
            
            /*CALCULAMOS EL TOTAL DEL IMPUESTO IVA*/
            $TotalTrasladosImpuestoIVA161 = round($TotalTrasladosBaseIVA16 * 0.16,2);
            $ImporteP1 = round($ImporteP - $TotalTrasladosImpuestoIVA161,2);
            $TotalTrasladosImpuestoIVA16 = round($TotalTrasladosBaseIVA16 * 0.16,2) + $ImporteP1;
       
            /*CALCULAMOS EL TOTAL DEL COMPLEMENTO*/
            $MontoTotalPagos1 = round($TotalTrasladosBaseIVA16 + $TotalTrasladosImpuestoIVA16,2); 
            $total_abonado1 = round($MontoTotalPagos1 - $total_abono,2);
            $MontoTotalPagos = round($TotalTrasladosBaseIVA16 + $TotalTrasladosImpuestoIVA16,2) - $total_abonado1; 

            // SACAMOS EL NUMERO DE PARCIALIDAD DEL PAGO
            $ultimoid_1 = "SELECT MAX(parcialidad) AS id_parcialidad FROM complementos WHERE f_factura = $folio and timbrado = 1";
            $resultSet = $mysqli->query($ultimoid_1);
            $fila_ultimoid_1 = $resultSet->fetch_assoc();   

            $parcial_folio = $fila_ultimoid_1['id_parcialidad'];
            $foliop = $parcial_folio + 1;

	// Agregar a m_pagos:
	$m_pagos[] = [
                "IdDocumento" => $uuid,
                "Serie" => $serie,
                "Folio" => $folio,
                "MonedaDR" => "MXN",
                "EquivalenciaDR"=> "1",
                "MetodoDePagoDR" => "PPD",
                "NumParcialidad" => $foliop,
                "ImpSaldoAnt" => $saldo_anterior,
                "ImpPagado" => $saldo_pagado,
                "ImpSaldoInsoluto" => $saldo_nuevo,
                "ObjetoImpDR"=>"02",
                "ImpuestoDR"=>"002", 
            	"BaseDR" => $BaseDR,
            	"ImporteDR" => $ImporteDR,
                "TipoFactorDR"=>"Tasa", 
                "TasaOCuotaDR"=> "0.160000", 
                "ImpuestoP"=>"002", 
                "TipoFactorP"=>"Tasa", 
                "TasaOCuotaP"=>"0.160000",
            	"BaseP" => $BaseP,
            	"ImporteP" => $ImporteP,
            ];	
	            $importe_pagado += round($saldo_pagado,2);
           
            }       

      $cfdi_pago_info = [
        "Comprobante" => [
		"LugarExpedicion" => trim($cp),
		"TipoDeComprobante" =>"P",
        "Exportacion"=>"01", // se agrrega exportancion. 
		"Version" => "4.0",
		"Fecha" => $fecha_complemento,
		"Total" => "0",
		"SubTotal" => "0",
		"Serie" => "P-GHA",   
		"Folio" => $foliox,
        //"Certificado" => $Certificado,
        //"NoCertificado" => $NoCertificado, 
   
        "NoCertificado" => "00001000000510280313", 
        "Certificado"  => "",
        "Moneda" => "XXX",		
	],


	"Emisor" => [
        //"Rfc" => $rfc_emisor,
		//"Nombre" => $nom_emisor,
        //"RegimenFiscal" => $reg, 
        "Rfc" => "IIA040805DZ4",
        "Nombre" => 'INDISTRIA ILUMINADORA DE ALMACENES',
        "RegimenFiscal" => "626", 
        

	],

	"Receptor" => [
	   
       "Rfc" => utf8_decode($no_cliente),
		"Nombre" => utf8_decode($last_cliente),	
		"UsoCFDI" => "CP01",
        "DomicilioFiscalReceptor" => $codigo_postal, // codigo fiscal.
        "RegimenFiscalReceptor" => $regimen_fiscal, // regimen fiscal.	
        /*
        "Rfc" => "AARD000807DHA",
		"Nombre" => "DIEGO ARANDA RIVERO",	
		"UsoCFDI" => "CP01",
        "DomicilioFiscalReceptor" => "52920", // codigo fiscal.
        "RegimenFiscalReceptor" => "612", // regimen fiscal. */
    ],	
                    
	"Conceptos" => [		
		"ClaveProdServ" => "84111506",
		"ClaveUnidad" => "ACT",
		"Descripcion" => "Pago",		
		"Cantidad" => "1",
		"ValorUnitario" => "0",
		"Importe" => "0",
        "ObjetoImp"=>"01",
	],
                    
                               
	"Pago" => [
                  [
            "FechaPago" => $fecha_aplicacion,
			"FormaDePagoP" => $metodo_pago,
            "MonedaP" => "MXN",
            "TipoCambioP" => '1',
			"Monto" => $importe_pagado, // $total_abono, TOTAL DEL ABONO POR MEDIO FECHA 
			//"NumOperacion" => $referencia_abono,// REFERENCIA DE DEPOSITO. 
			//"RfcEmisorCtaOrd" => "$RfcEmisorCtaOrd",
			//"NomBancoOrdExt" => "$NomBancoOrdExt",
			//"CtaOrdenante" => "$CtaOrdenante",
			//"RfcEmisorCtaBen" => "$RfcEmisorCtaBen",
			//"CtaBeneficiario" => "$CtaBeneficiario",
			"TipoCadPago" => "",
			"CertPago" => "",
			"CadPago" => "",
			"SelloPago" => "",

			"DoctoRelacionado" => $m_pagos
			/*[ 
				[
					"IdDocumento" => $uuid,
					"Serie" => $serie, // SERIE DE LA FACTURA
					"Folio" => $folio, // FOLIO FACTURA
					"MonedaDR" => "MXN",
					"MetodoDePagoDR" => "PPD",
					"NumParcialidad" => "1", // SACAR DE LA BASE DE DATOS. 
					"ImpSaldoAnt" => $saldo_anterior,
					"ImpPagado" => $saldo_pagado,
					"ImpSaldoInsoluto" => $saldo_nuevo,
				]	
							
			]*/
		],
	],
					

                     					
];

// Mostrar el array:
 //echo "<br><pre>"; print_r( $cfdi_pago_info ); echo "</pre><br>"; // exit;

 
	// Crear XML y timbrar:
	
	$xml_archivo = "Complementos/".$cfdi_pago_info[ "Comprobante" ][ "Serie" ]."_".$cfdi_pago_info[ "Comprobante" ][ "Folio" ]."_enviado.xml";
	$xml_archivo_timbrado = "Complementos/".$cfdi_pago_info[ "Comprobante" ][ "Serie" ]."_".$cfdi_pago_info[ "Comprobante" ][ "Folio" ]."_timbrado.xml";
    	
	// Eliminar si existe (solo la pruebas):
	if( file_exists( $xml_archivo ) ) unlink( $xml_archivo );
	if( file_exists( $xml_archivo_timbrado ) ) unlink( $xml_archivo_timbrado );
	
	$xmlWriter = new XMLWriter();
	$xmlWriter->openMemory();
	$xmlWriter->startDocument('1.0', 'UTF-8');
	$xmlWriter->startElement('cfdi:Comprobante');

	if( $cfdi_pago_info["Comprobante"]["Version"] == "4.0" ){
		// CFDI 4.0
		$xmlWriter->writeAttribute( "xmlns:xsi","http://www.w3.org/2001/XMLSchema-instance" );
		$xmlWriter->writeAttribute( "xmlns:cfdi","http://www.sat.gob.mx/cfd/4" );
		$xmlWriter->writeAttribute( "xmlns:pago20","http://www.sat.gob.mx/Pagos20" );
		$xmlWriter->writeAttribute( "xsi:schemaLocation","http://www.sat.gob.mx/Pagos20 http://www.sat.gob.mx/sitio_internet/cfd/Pagos/Pagos20.xsd http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd" );	
	}else{
		// CFDI 3.3
		$xmlWriter->writeAttribute( "xmlns:xsi","http://www.w3.org/2001/XMLSchema-instance" );
		$xmlWriter->writeAttribute( "xmlns:cfdi","http://www.sat.gob.mx/cfd/3" );
		$xmlWriter->writeAttribute( "xmlns:pago10","http://www.sat.gob.mx/Pagos" );
		$xmlWriter->writeAttribute( "xsi:schemaLocation","http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd http://www.sat.gob.mx/Pagos http://www.sat.gob.mx/sitio_internet/cfd/Pagos/Pagos10.xsd" );
	}

        $xmlWriter->writeAttribute( 'LugarExpedicion',$cfdi_pago_info["Comprobante"]["LugarExpedicion"] );
        $xmlWriter->writeAttribute( 'TipoDeComprobante',$cfdi_pago_info["Comprobante"]["TipoDeComprobante"] );

        $xmlWriter->writeAttribute( "SubTotal",$cfdi_pago_info["Comprobante"]["SubTotal"] );
        $xmlWriter->writeAttribute( "Total",$cfdi_pago_info["Comprobante"]["Total"] );
        $xmlWriter->writeAttribute( "Moneda",$cfdi_pago_info["Comprobante"]["Moneda"] );

	if( $cfdi_pago_info["Comprobante"]["Version"] == "4.0" && $cfdi_pago_info["Comprobante"]["Exportacion"] != "" ) $xmlWriter->writeAttribute( "Exportacion",$cfdi_pago_info["Comprobante"]["Exportacion"] ); // 01
	
        $xmlWriter->writeAttribute( "Fecha",$cfdi_pago_info["Comprobante"]["Fecha"] );		
        $xmlWriter->writeAttribute( "Serie",$cfdi_pago_info["Comprobante"]["Serie"] );		
        $xmlWriter->writeAttribute( "Folio",$cfdi_pago_info["Comprobante"]["Folio"] );		
        $xmlWriter->writeAttribute( "Version",$cfdi_pago_info["Comprobante"]["Version"] );	
	
	$xmlWriter->writeAttribute( "NoCertificado",$cfdi_pago_info["Comprobante"]["NoCertificado"] );
	if( $cfdi_pago_info["Comprobante"]["NoCertificado"] != "" ) $xmlWriter->writeAttribute( "Certificado",$cfdi_pago_info["Comprobante"]["NoCertificado"] );	
	
	// Emisor
	$xmlWriter->text( "\n\t" );
	$xmlWriter->startElement('cfdi:Emisor');
		$xmlWriter->writeAttribute( "Rfc",$cfdi_pago_info["Emisor"]["Rfc"] );		
			// Demo:
		if( $cfdi_pago_info["Emisor"]["Rfc"] == "EKU9003173C9" && $cfdi_pago_info["Comprobante"]["Version"] == "4.0" ) $cfdi_pago_info["Emisor"]["Nombre"] = "ESCUELA KEMPER URGATE SA DE CV";
		$xmlWriter->writeAttribute( "Nombre",$cfdi_pago_info["Emisor"]["Nombre"] );
		$xmlWriter->writeAttribute( "RegimenFiscal",$cfdi_pago_info["Emisor"]["RegimenFiscal"] );
	    $xmlWriter->endElement(); // cfdi:Emisor	

	// Receptor
	$xmlWriter->text( "\n\t" );
	$xmlWriter->startElement('cfdi:Receptor');
		$xmlWriter->writeAttribute( "Rfc",$cfdi_pago_info["Receptor"]["Rfc"] );
		$xmlWriter->writeAttribute( "Nombre",$cfdi_pago_info["Receptor"]["Nombre"] );
		$xmlWriter->writeAttribute( "UsoCFDI",$cfdi_pago_info["Receptor"]["UsoCFDI"] );
     
		// Atributos CFDI 4.0
		if( $cfdi_pago_info["Comprobante"]["Version"] == "4.0" && $cfdi_pago_info["Receptor"]["DomicilioFiscalReceptor"] != "" ) $xmlWriter->writeAttribute( "DomicilioFiscalReceptor",$cfdi_pago_info["Receptor"]["DomicilioFiscalReceptor"] );
		if( $cfdi_pago_info["Comprobante"]["Version"] == "4.0" && $cfdi_pago_info["Receptor"]["RegimenFiscalReceptor"] != "" ) $xmlWriter->writeAttribute( "RegimenFiscalReceptor",$cfdi_pago_info["Receptor"]["RegimenFiscalReceptor"] );
	$xmlWriter->endElement(); // cfdi:Receptor		
	
	// Conceptos:
	$xmlWriter->text( "\n\t" );
	$xmlWriter->startElement('cfdi:Conceptos');	
		$xmlWriter->text( "\n\t\t" );
		$xmlWriter->startElement('cfdi:Concepto');
			// Atributos:
			$xmlWriter->writeAttribute( "ClaveProdServ",$cfdi_pago_info["Conceptos"]["ClaveProdServ"] );								
			$xmlWriter->writeAttribute( "Cantidad",$cfdi_pago_info["Conceptos"]["Cantidad"] );
			$xmlWriter->writeAttribute( "ClaveUnidad",$cfdi_pago_info["Conceptos"]["ClaveUnidad"] );
			$xmlWriter->writeAttribute( "Descripcion",$cfdi_pago_info["Conceptos"]["Descripcion"] );
			$xmlWriter->writeAttribute( "ValorUnitario",$cfdi_pago_info["Conceptos"]["ValorUnitario"] );
			$xmlWriter->writeAttribute( "Importe",$cfdi_pago_info["Conceptos"]["Importe"] );
			if( $cfdi_pago_info["Comprobante"]["Version"] == "4.0" && $cfdi_pago_info["Conceptos"]["ObjetoImp"] != "" ) $xmlWriter->writeAttribute( "ObjetoImp",$cfdi_pago_info["Conceptos"]["ObjetoImp"] ); // Atributo CFDI 4.0
		$xmlWriter->endElement(); // cfdi:Concepto
	$xmlWriter->text( "\n\t" );
	$xmlWriter->endElement(); // cfdi:Conceptos

	// Complemento:
	$xmlWriter->text( "\n\t" );
	$xmlWriter->startElement('cfdi:Complemento');
		$xmlWriter->text( "\n\t\t" );
		$xmlWriter->startElement('pago20:Pagos');
			// Atributos:
			$xmlWriter->startAttribute('Version');
		        $xmlWriter->text( "2.0" );
			$xmlWriter->endAttribute();
                        
                        $xmlWriter->text( "\n\t\t" );
		        $xmlWriter->startElement('pago20:Totales');
			// Atributos:
			$xmlWriter->startAttribute('TotalTrasladosBaseIVA16');
				$xmlWriter->text($TotalTrasladosBaseIVA16);
			$xmlWriter->endAttribute();

			$xmlWriter->startAttribute('TotalTrasladosImpuestoIVA16');
				$xmlWriter->text($TotalTrasladosImpuestoIVA16);
			$xmlWriter->endAttribute();

			$xmlWriter->startAttribute('MontoTotalPagos');
				$xmlWriter->text($MontoTotalPagos);
			$xmlWriter->endAttribute();
		         $xmlWriter->endElement(); // Fin de pago20:Totales
                        
		// Pago-Encabezado:	
		foreach( $cfdi_pago_info["Pago"] as $reg_pago ){	
			$xmlWriter->text( "\n\t\t\t" );
			$xmlWriter->startElement('pago20:Pago');
                	
				// Atributos:
				$xmlWriter->writeAttribute( "FechaPago",$reg_pago["FechaPago"] );
				$xmlWriter->writeAttribute( "FormaDePagoP",$reg_pago["FormaDePagoP"] );
				$xmlWriter->writeAttribute( "MonedaP",$reg_pago["MonedaP"] );
				$xmlWriter->writeAttribute( "TipoCambioP",$reg_pago["TipoCambioP"] );	
				$xmlWriter->writeAttribute( "Monto", number_format( $reg_pago["Monto"],2,".","" ) );
				//$xmlWriter->writeAttribute( "NumOperacion", $reg_pago["NumOperacion"] );

				// Referencia de Banco:
				//if( $reg_pago["NomBancoOrdExt"] != "" ) $xmlWriter->writeAttribute( "NomBancoOrdExt", $reg_pago["NomBancoOrdExt"] );
				//if( $reg_pago["RfcEmisorCtaOrd"] != "" ) $xmlWriter->writeAttribute( "RfcEmisorCtaOrd", $reg_pago["RfcEmisorCtaOrd"] );
				//if( $reg_pago["CtaOrdenante"] != "" ) $xmlWriter->writeAttribute( "CtaOrdenante", $reg_pago["CtaOrdenante"] );
				//if( $reg_pago["RfcEmisorCtaBen"] != "" ) $xmlWriter->writeAttribute( "RfcEmisorCtaBen", $reg_pago["RfcEmisorCtaBen"] );
				//if( $reg_pago["CtaBeneficiario"] != "" ) $xmlWriter->writeAttribute( "CtaBeneficiario", $reg_pago["CtaBeneficiario"] );
				if( $reg_pago["TipoCadPago"] != "" ) $xmlWriter->writeAttribute( "TipoCadPago", $reg_pago["TipoCadPago"] );
				if( $reg_pago["CertPago"] != "" ) $xmlWriter->writeAttribute( "CertificadoPago", $reg_pago["CertPago"] );
				if( $reg_pago["CadPago"] != "" ) $xmlWriter->writeAttribute( "CadPago", $reg_pago["CadPago"] );
				if( $reg_pago["SelloPago"] != "" ) $xmlWriter->writeAttribute( "SelloPago", $reg_pago["SelloPago"] );				
				
				foreach($reg_pago["DoctoRelacionado"] as $reg_docrel ){				
					$xmlWriter->text( "\n\t\t\t\t" );
					$xmlWriter->startElement('pago20:DoctoRelacionado');
						// Atributos:							
						$xmlWriter->writeAttribute( "IdDocumento", $reg_docrel["IdDocumento"] );
						if( $reg_docrel["Serie"] != "" ) $xmlWriter->writeAttribute( "Serie", $reg_docrel["Serie"] );
						if( $reg_docrel["Folio"] != "" ) $xmlWriter->writeAttribute( "Folio", $reg_docrel["Folio"] );
						$xmlWriter->writeAttribute( "MonedaDR", $reg_docrel["MonedaDR"] );
                                                $xmlWriter->writeAttribute( "EquivalenciaDR", $reg_docrel["EquivalenciaDR"] );
						if( $reg_pago["MonedaP"] != $reg_docrel["MonedaDR"] && $reg_docrel["TipoCambioDR"] > 0 ){
						$xmlWriter->writeAttribute( "TipoCambioDR", number_format( $reg_docrel["TipoCambioDR"],4,".","" ) );
						}						
				
						$xmlWriter->writeAttribute( "NumParcialidad", $reg_docrel["NumParcialidad"] );
						$xmlWriter->writeAttribute( "ImpSaldoAnt", number_format( $reg_docrel["ImpSaldoAnt"],2,".","" ) );
						$xmlWriter->writeAttribute( "ImpPagado", number_format( $reg_docrel["ImpPagado"],2,".","" ) );
						$xmlWriter->writeAttribute( "ImpSaldoInsoluto", number_format( $reg_docrel["ImpSaldoInsoluto"],2,".","" ) );
                                                $xmlWriter->writeAttribute( "ObjetoImpDR",$reg_docrel["ObjetoImpDR"]);
                                                
                                                
					
	                                $xmlWriter->text( "\n\t\t\t\t" );
					$xmlWriter->startElement('pago20:ImpuestosDR');
					// Atributos:							
					$xmlWriter->text( "\n\t\t\t\t" );
					$xmlWriter->startElement('pago20:TrasladosDR');
					
							$xmlWriter->text( "\n\t\t\t\t" );
					$xmlWriter->startElement('pago20:TrasladoDR');
                                       
				        $xmlWriter->writeAttribute( "BaseDR",number_format($reg_docrel["BaseDR"],2,".",""));
											
					$xmlWriter->writeAttribute( "ImpuestoDR", $reg_docrel["ImpuestoDR"]);
					
					$xmlWriter->writeAttribute( "TipoFactorDR", $reg_docrel["TipoFactorDR"]);					
					
					$xmlWriter->writeAttribute( "TasaOCuotaDR", $reg_docrel["TasaOCuotaDR"]);	
                                               
                                        $xmlWriter->writeAttribute( "ImporteDR", number_format($reg_docrel["ImporteDR"],2,".",""));
                                                
				        $xmlWriter->endElement(); // Fin de pago10:TrasladoDR
					
				
					$xmlWriter->endElement(); // Fin de pago20:TrasladosDR
					
					$xmlWriter->endElement(); // Fin de pago10:ImpuestosDR
					
					
					$xmlWriter->endElement(); // Fin de pago10:DoctoRelacionado
					
				}
			
        
			
			$xmlWriter->text( "\n\t\t\t\t" );
					$xmlWriter->startElement('pago20:ImpuestosP'); //inicio pago20:ImpuestosP
					
				$xmlWriter->text( "\n\t\t\t\t" );
					$xmlWriter->startElement('pago20:TrasladosP'); //inicio pago20:TrasladosP
					
			$xmlWriter->text( "\n\t\t\t\t" );
					$xmlWriter->startElement('pago20:TrasladoP'); //inicio pago20:TrasladosP
					
			$xmlWriter->writeAttribute( "BaseP",number_format($reg_docrel["BaseP"],2,".",""));
			
			$xmlWriter->writeAttribute( "ImpuestoP",$reg_docrel["ImpuestoP"]); 
			
			$xmlWriter->writeAttribute( "TipoFactorP",$reg_docrel["TipoFactorP"]); 
			
			$xmlWriter->writeAttribute( "TasaOCuotaP",$reg_docrel["TasaOCuotaP"]);
	
                        $xmlWriter->writeAttribute( "ImporteP",number_format($reg_docrel["ImporteP"],2,".","")); 
			
			 
			
					$xmlWriter->text( "\n\t\t\t" );
			$xmlWriter->endElement(); // Fin	pago20:TrasladoP
			
			
					$xmlWriter->text( "\n\t\t\t" );
			$xmlWriter->endElement(); // Fin	pago20:TrasladosP
			
			
					$xmlWriter->text( "\n\t\t\t" );
			$xmlWriter->endElement(); // Fin	pago20:ImpuestosP
			
			
			
			$xmlWriter->text( "\n\t\t\t" );
			$xmlWriter->endElement(); // Fin pago10:Pago
		}
		$xmlWriter->text( "\n\t\t" );
		$xmlWriter->endElement(); // Fin de pago10:Pagos

	$xmlWriter->text( "\n\t" );
	$xmlWriter->endElement(); // Fin cfdi:Complemento
	
	$xmlWriter->text( "\n" );
	$xmlWriter->endElement(); // Fin cfdi:Comprobante

	error_reporting(E_ALL); ini_set('display_errors', '1');
	
	$xmlWriter->text( "\n" );
	$xmlWriter->endElement(); // Fin del elemento <cfdi:Comprobante
	$xmlWriter->endDocument();
	$xml_nombre	= $xml_archivo;
	file_put_contents( $xml_nombre, $xmlWriter->flush(true), FILE_APPEND );


    
    
	$pacnomcor = "PROFACT_PRUEBAS"; // Pruebas
	$pacusu = "mvpNUXmQfK8=";	
	if( $cfdi_pago_info["Comprobante"]["Version"] == "4.0" ){
		$pacurl = "https://pruebas.timbracfdi33.mx/Timbrado.asmx?wsdl"; // CFDI 4.0
	}else{
        $pacurl = "https://pruebas.timbracfdi33.mx/Timbrado.asmx?wsdl"; // CFDI 4.0
	} 
     
   
      
    //DESCOMENTAR PARA PASAR A ENTORNO DE PRUEBAS 
	//$pacnomcor = "PROFACT_PRUEBAS"; // Pruebas
	//$pacusu = "mvpNUXmQfK8=";	
    /*
    $pacusu = "K/nl+ecaEJOJpAUUY1c4Kg==";	
	if( $cfdi_pago_info["Comprobante"]["Version"] == "4.0" ){
		$pacurl = "https://timbracfdi33.mx:1443/Timbrado.asmx?wsdl"; // CFDI 4.0
	}else{
	    $pacurl = "https://timbracfdi33.mx:1443/Timbrado.asmx?wsdl"; // CFDI 3.3
       
	} */
  

	$texto = file_get_contents( $xml_archivo );
	// echo "<p> <textarea style='width:98%;height:150px;'>".$texto."</textarea></p>";
	$base64Comprobante = base64_encode($texto);
	// echo "<p> <textarea style='width:98%;height:150px;'>".$base64Comprobante."</textarea></p>";

	$response = '';
	try {
		$params = array();
		$params['xmlComprobanteBase64'] = $base64Comprobante;
		$params['usuarioIntegrador'] = $pacusu;
		$params['idComprobante'] = rand(5, 999999);
		$context = stream_context_create(array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
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
		$client = new \SoapClient($pacurl, $options);
		$response = $client->__soapCall('TimbraCFDI', array('parameters' => $params)); // 3.3
		// echo "<hr><br> Respuesta TimbraCFDI: <pre>"; print_r( $response ); echo "</pre>"; exit;
		
	}catch (SoapFault $fault) {
		echo "<br> <pre>"; print_r( $fault ); echo "</pre>"; exit;
		exit;
	}
	
	// Respuesta:
	// echo "<br> <pre>"; print_r( $response ); echo "</pre>"; exit;				
	

    $carpeta = 'Complementos-CFDI/' . $foliox;
    if (!file_exists($carpeta)) {
        mkdir($carpeta, 0777, true);
    }

    
	if( $response->TimbraCFDIResult->anyType[4] == NULL ){
	/*	echo "
			<hr />
			<h3 align='center'> ERROR:  </h3>
			<p align='center'>".trim($response->TimbraCFDIResult->anyType[2])."</p>
			<br />
			<p align='center'>
				<a href='".$xml_archivo."' target='_blank' class='enlace1' style='color:blue;'> Ver XML enviado </a>
			</p>
		";
		echo "<br><p align='left'> TimbraCFDIResult: <pre>"; print_r( $response ); echo "</pre></p>";
		exit; */
    }

	// Obtenemos resultado del response
	    $tipoExcepcion = $response->TimbraCFDIResult->anyType[0];
	    $numeroExcepcion = $response->TimbraCFDIResult->anyType[1];
	    $descripcionResultado = $response->TimbraCFDIResult->anyType[2];
	    $xmlTimbrado = $response->TimbraCFDIResult->anyType[3];
	    $codigoQr = $response->TimbraCFDIResult->anyType[4];
	    $cadenaOriginal = $response->TimbraCFDIResult->anyType[5];
	    $errorInterno = $response->TimbraCFDIResult->anyType[6];
	    $mensajeInterno = $response->TimbraCFDIResult->anyType[7];
	    $m_uuid = $response->TimbraCFDIResult->anyType[8];
	    $m_uuid2 = json_decode( $m_uuid );

        // SE TOMA INFORMACION PARA LA BASE DE DATOS. 
        $serie_cp   = $cfdi_pago_info[ "Comprobante" ][ "Serie" ];
        $forma_pago = $reg_pago["FormaDePagoP"];
      
	    if($xmlTimbrado != ''){
	    /* Guardamos cadena original del complemento de certificacion del SAT */
     	// echo "xmlTimbrado";
		// El comprobante fue timbrado correctamente
		// Guardamos comprobante timbrado
		// echo "<br> CFDI:(".$this->xml_archivo2.")";

        file_put_contents($carpeta.'/'.$foliox.'.codigoQr.jpg', $codigoQr);
        file_put_contents($carpeta.'/'.$cfdi_pago_info[ "Comprobante" ][ "Serie" ]."_".$foliox.'cadenaOriginal.txt', $cadenaOriginal);
        file_put_contents($carpeta.'/'.$cfdi_pago_info[ "Comprobante" ][ "Serie" ]."_".$foliox."_timbrado.xml".".xml", $xmlTimbrado);


        if( !file_put_contents( $xml_archivo_timbrado, $xmlTimbrado ) ){
			echo "<p> Error al crear el archivo: ".$xml_archivo_timbrado." </p>";
          }
        
         
        $complemento = "INSERT INTO `complementos` ( `folio`,`serie`, `rfc_receptor`, `fecha_registro`,`timbrado`,`folios_abonos`,`f_factura`,`saldo_anterior`,`monto_pagado`,`saldo_insoluto`,`total_pagado`,`mpago`,`nombre_cliente`,`apellido_cliente`,`rfc_cliente`,`email_cliente`,`id_cliente`,`parcialidad`)
        VALUES ('" . $foliox . "', '" . $serie_cp. "', '" . $rfc_emisor . "', NOW(),' 1 ','" . $complementos . "','" . $folio . "','" . $saldo_anterior . "','" . $saldo_pagado . "','" . $saldo_nuevo . "','" . $importe_pagado . "','" . $forma_pago . "','" . $name_cliente . "','" . $last_cliente . "','" . $no_cliente . "','" . $email . "','" . $idcliente . "','" . $foliop . "')";
        $mysqli->query($complemento);
        
        
        $sql_xml_up = "UPDATE bitacora_abonos set facturado = 1  where idabonos IN ($complementos)";
        $mysqli->query($sql_xml_up);

         // Se pone como facturado el anticipo debido a que se ha cubierto el 100 % al aplicarlo al complemento.
         $sql_xml_up3 = "UPDATE bitacora_pagos_anticipo set folio_cp = '". $foliox ."', serie_cp = '". $serie_cp ."' where id_anticipo  in ($complementos)";
         $mysqli->query($sql_xml_up3);
        
         echo "1";

         generarPDF($xml_archivo_timbrado,$direccion_cliente,$carpeta,$foliox,$cfdi_pago_info,$id_almacen,$rfc_emisor);  
          
        }else{
        echo "0";
        echo "[".$tipoExcepcion."  ".$numeroExcepcion." ".$descripcionResultado."  ei=".$errorInterno." mi=".$mensajeInterno."]" ;
        }


    function generarPDF($xml_archivo_timbrado,$direccion_cliente,$carpeta,$foliox,$cfdi_pago_info,$id_almacen,$rfc_emisor) {
		include '../../../connection/conexion.php';
		$mysqli->query("SET NAMES 'UTF8'");
	 	$xml_archivo_timbrado = $xml_archivo_timbrado;
   
		$xml = new SimpleXMLElement($xml_archivo_timbrado, 0, true);
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
        $cfdi2 = $cfdiComprobante['Folio'];
        $cfdi3 = $cfdiComprobante['Total'];
        $cfdi4 = $cfdiComprobante['SubTotal'];
        $cfdi8 = $cfdiComprobante['Moneda'];
        $cfdi9 = $cfdiComprobante['MetodoPago'];
        $cfdi10 = $cfdiComprobante['FormaDePagoP'];
        $cfdi11 = $cfdiComprobante['NoCertificado'];
        $cfdi12 = $cfdiComprobante['TipoDeComprobante'];
        $cfdi13 = $cfdiComprobante['Fecha'];
    
        $pdf->SetXY(100, 5);
        $pdf->SetFillColor(204, 204, 204);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont("Arial", "B", 12);
        $pdf->Cell(100, 4, "FOLIO    " . $cfdi1 . " - " . $cfdi2, 1, 1, "C", true);
        
        $pdf->SetXY(100, 11);
        $pdf->SetFillColor(204, 204, 204);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont("Arial", "B", 9);
        $pdf->Cell(100, 4, "DATOS EMISOR ", 1, 1, "C", true);


        include "../../../core/controller/Core.php";
        include "../../../core/controller/Database.php";
        include "../../../core/controller/Executor.php";
        include "../../../core/controller/Model.php";
        include "../../../core/app/model/RazonData.php";
   

        $stock = RazonData::getById($id_almacen);
        if($stock->image!=""){
            $ticket_image = $imagen_razon;} 
          if ($ticket_image != "") {
              $src = "../../../storage/razones_sociales/".$ticket_image;
              if (file_exists($src)) {
              $pdf->Image($src, 10, 10, 50);
                }
            }

        $direccion_empresa = "Direccion Fiscal : " .$direccionemp ." ". $colonia;

        $pdf->SetXY(13, 38);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont("Arial", "",8);
        $pdf->Cell(70, 4, utf8_decode($direccion_empresa), "C");

        $pdf->SetXY(34, 42);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont("Arial", "", 8);
        $pdf->Cell(70, 4, $localidad, "C"); 

        // Consulta a la base de datos para sacar cosas de la factura 1
        // 1º Datos del cliente
        $xml->registerXPathNamespace("tfd", "http://www.sat.gob.mx/TimbreFiscalDigital");
        foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//tfd:TimbreFiscalDigital') as $tfd) {
            $sello4 = $tfd['NoCertificadoSAT'];
            $sello5 = $tfd['UUID'];
      
            foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor) {
                foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor) {
                    $emisor1 = $Emisor['Nombre'];
                    $emisor2 = $Emisor['Rfc'];
                    $emisor3 = $Emisor['RegimenFiscal']; 
                    $texto1 = "" . $Emisor['Nombre'] . " \nRFC : " . $Emisor['Rfc'] .  "\nCertificado Sello CSD : " . $cfdi11 . "\nFolio Fiscal UUID : " . $sello5 . "\nRegimen Fiscal : ".rgmen($emisor3);
                    
                    $pdf->SetXY(100, 16);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetFont("Arial", "", 8);
                    $pdf->SetFillColor(255, 255, 255);
                    
                    // ancho y alto
                    $pdf->MultiCell(100, 4,utf8_decode($texto1), 1, "L", true);
                }

                $pdf->SetXY(10, 47);
                $pdf->SetFillColor(204, 204, 204);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetFont("Arial", "B", 8);
                $pdf->Cell(130, 4, "                                                                     DATOS CLIENTE RECEPTOR                                                                      ", 1, 1, "C", true);

                $Receptor1 = $Receptor['Nombre'];
                $Receptor2 = $Receptor['Rfc'];
                $Receptor3 = uso($Receptor['UsoCFDI']);
                $sello1 = $tfd['SelloCFD'];
                $sello2 = $tfd['SelloSAT'];
              
                $cfdi14 = $tfd['FechaTimbrado'];
                $texto1 = "CLIENTE : " . utf8_decode($Receptor1) . "       \nRFC : " . utf8_decode($Receptor2) . "     \nUSO CFDI : " . $Receptor3. " \nDireccion : " . utf8_decode($direccion_cliente);
                $pdf->SetXY(10, 52);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFont("Arial", "", 8);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->MultiCell(130, 4, $texto1, 1, "L", true);

                $pdf->SetXY(150, 47);
                $pdf->SetFillColor(204, 204, 204);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetFont("Arial", "B", 9);
                $pdf->Cell(50, 4, "                                                                     LUGAR DE EXPEDICION                                                                     ", 1, 1, "C", true);

                $texto2 = "C.P : " . $cfdi . "" . utf8_decode(" \nFecha Emisión : ") ."\n". $cfdi13 . "\n"  .$localidad;
                $pdf->SetXY(150, 52);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFont("Arial", "", 8);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->MultiCell(50, 4, $texto2, 1, "C", true);
            }
        }
        
                 $pdf->SetXY(19, 72);
                 $pdf->SetFont("Arial", "B", 9);
                 $pdf->Cell(1,1,"Conceptos  ", 0, 0, "C", true);

                $pdf->SetXY(10, 78);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetFillColor(204, 204, 204);
                $pdf->SetFont("Arial", "B", 7);
                $pdf->Cell(15, 6, "CVEPR", 0, 0, "C", true);
                $pdf->Cell(15, 6, "CANT", 0, 0, "C", true);
                $pdf->Cell(15, 6, "UNIDAD", 0, 0, "C", true);
                $pdf->Cell(58, 6, "DESCRIPCION", 0, 0, "R", true);
                $pdf->Cell(70, 6, "                                        PRECIO U ", 0, 0, "C", true);
                $pdf->Cell(18, 6, "IMPORTE", 0, 1, "C", true);
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
            $cell_width_punitario = 20;
            $cell_width_descuento = 5;
            $cell_width_total = 30;
            $cell_width_descripcion = 95;
            $cell_height=4;    //define cell height

            $pdf->SetFont('Arial','',8);

            $pdf->MultiCell($cell_width_clave,$cell_height,$claveser, 1, 'J', 1, 1, '' ,'', true); //print one cell value
            $current_x+=$cell_width_clave;                           //calculate position for next cell
            $pdf->SetXY($current_x, $current_y);               //set position for next cell to print

            $pdf->MultiCell($cell_width_cantidad,$cell_height,$cantidad, 1, 'C', 1, 1, '' ,'', true);
            $current_x+=$cell_width_cantidad;
            $pdf->SetXY($current_x, $current_y);  
            
            $pdf->SetFont('Arial','',8);
            $pdf->MultiCell($cell_width_unidad,$cell_height,$claveunidad, 1, 'C', 1, 1, '' ,'', true);
            $current_x+=$cell_width_unidad;
            $pdf->SetXY($current_x, $current_y); 

            $pdf->MultiCell($cell_width_descripcion,4,utf8_decode($descripcion), 1, 'C', 1, 1, '' ,'', true);
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

            $pdf->SetXY(25, 93);
            $pdf->SetFont("Arial", "B", 9);
            $pdf->Cell(1,1,"                                                                     Informacion de Pago                                                                   ", 0, 0, "C", true);

            /***********************************************************************************************/
                if($cfdi12 == 'P') {
                $xml->registerXPathNamespace('p', $ns['pago20']);
    
                 // set monto pago 10
                 foreach ($xml->xpath('//p:Pago') as $pago20) {
                   $fecha_abono = $pago20['FechaPago'];
                   $FormaDePagoP = $pago20['FormaDePagoP'];
                   $MonedaP = $pago20['MonedaP'];
                   $Monto = $pago20['Monto'];
                   //$NumOperacion = $pago20['NumOperacion'];
                   //$RfcEmisorCtaOrd = $pago20['RfcEmisorCtaOrd'];
                   //$CtaOrdenante = $pago20['CtaOrdenante'];
                   //$NomBancoOrdExt = $pago20['NomBancoOrdExt'];
                   //$RfcEmisorCtaBen = $pago20['RfcEmisorCtaBen'];
                   //$CtaBeneficiario = $pago20['CtaBeneficiario'];

                   $pdf->SetXY(15, 100);
                   $pdf->SetTextColor(0, 0, 0);
                   $pdf->SetFont("Arial", "B", 8);
                   $pdf->MultiCell(135, 3, "Fecha Pago : ".$fecha_abono, 0, "L"); // me modifico esta linea

                   $pdf->SetXY(15, 105);
                   $pdf->SetTextColor(0, 0, 0);
                   $pdf->SetFont("Arial", "B", 8);
                   $pdf->MultiCell(135, 3, "Forma De Pago : ".$FormaDePagoP, 0, "L"); // me modifico esta linea

                  /* $pdf->SetXY(15, 110);
                   $pdf->SetTextColor(0, 0, 0);
                   $pdf->SetFont("Arial", "B", 8);
                   $pdf->MultiCell(135, 3, "No. Operacion : ".$NumOperacion, 0, "L"); // me modifico esta linea */

                   $pdf->SetXY(15, 110);
                   $pdf->SetTextColor(0, 0, 0);
                   $pdf->SetFont("Arial", "B", 8);
                   $pdf->MultiCell(135, 3, "Moneda de Pago : ".$MonedaP, 0, "L"); // me modifico esta linea
                   
                   $pdf->SetXY(15, 115);
                   $pdf->SetTextColor(0, 0, 0);
                   $pdf->SetFont("Arial", "B", 8);
                   $pdf->MultiCell(135, 3, "Monto : ".$Monto, 0, "L"); // me modifico esta linea

                  /* $pdf->SetXY(100, 100);
                   $pdf->SetTextColor(0, 0, 0);
                   $pdf->SetFont("Arial", "B", 8);
                   $pdf->MultiCell(135, 3, "RFC Emisor Cuenta Ordenante :  ".$RfcEmisorCtaOrd, 0, "L"); // me modifico esta linea

                   $pdf->SetXY(100, 105);
                   $pdf->SetTextColor(0, 0, 0);
                   $pdf->SetFont("Arial", "B", 8);
                   $pdf->MultiCell(135, 3, "Cuenta Ordenante :  ".$CtaOrdenante, 0, "L"); // me modifico esta linea

                   $pdf->SetXY(100, 110);
                   $pdf->SetTextColor(0, 0, 0);
                   $pdf->SetFont("Arial", "B", 8);
                   $pdf->MultiCell(135, 3, "Nombre Banco Ordenante :  ".$NomBancoOrdExt, 0, "L"); // me modifico esta linea

                   $pdf->SetXY(100, 115);
                   $pdf->SetTextColor(0, 0, 0);
                   $pdf->SetFont("Arial", "B", 8);
                   $pdf->MultiCell(135, 3, "RFC Emisor Cuenta Beneficiario :  ".$RfcEmisorCtaBen, 0, "L"); // me modifico esta linea

                   $pdf->SetXY(100, 120);
                   $pdf->SetTextColor(0, 0, 0);
                   $pdf->SetFont("Arial", "B", 8);
                   $pdf->MultiCell(135, 3, "Cuenta beneficiario :  ".$CtaBeneficiario, 0, "L"); // me modifico esta linea
                    */
                }

                    $pdf->SetXY(10, 130);
                         $pdf->SetTextColor(0,0,0);
                $pdf->SetFillColor(204, 204, 204);
                    $pdf->SetFont("Arial", "B", 7);
                    $pdf->Cell(55, 6, "UUID.", 0, 0, "C", true);
                    $pdf->Cell(30, 6, "Serie - Folio", 0, 0, "C", true);
                    $pdf->Cell(18, 6, "No. Parcialidad", 0, 0, "C", true);
                    $pdf->Cell(29, 6, "Saldo Anterior", 0, 0, "R", true);
                    $pdf->Cell(34, 6, "Importe Pagado ", 0, 0, "C", true);
                    $pdf->Cell(25, 6, "Saldo Insoluto", 0, 1, "C", true);
                    $pdf->SetTextColor(0, 0, 0);
                 // set id ducumentp rel
               
                $documentosRel = $xml->xpath('//p:DoctoRelacionado');
                foreach ($documentosRel as $key) {
                $folio_uuid = $key['IdDocumento'];
                $SerieP = $key['Serie'];
                $FolioP = $key['Folio'];
                $Np = $key['NumParcialidad'];
                $PPD = $key['MetodoDePagoDR'];
                $ImpSaldoAnt = $key['ImpSaldoAnt'];
                $ImpPagado = $key['ImpPagado'];
                $ImpSaldoInsoluto  = $key['ImpSaldoInsoluto'];
             
                $pdf->SetFillColor(237, 237, 237);
                $start_x=$pdf->GetX(); //initial x (start of column position)
                $current_y = $pdf->GetY();
                $current_x = $pdf->GetX();
                $cell_width_uuid = 60;  //define cell width
                $cell_width_sf = 18;
                $cell_width_np = 27;
                $cell_width_ImpSaldoAnt = 33;
                $cell_width_ImpPagado = 25;
                $cell_width_ImpSaldoInsoluto = 28;
                $cell_height=4;    //define cell height
    
                $pdf->SetFont('Arial','',7);
                
                $pdf->MultiCell($cell_width_uuid,$cell_height,$folio_uuid, 1, 'J', 1, 1, '' ,'', true); //print one cell value
                $current_x+=$cell_width_uuid;                           //calculate position for next cell
                $pdf->SetXY($current_x, $current_y);               //set position for next cell to print
    
                $pdf->MultiCell($cell_width_sf,$cell_height,$SerieP."-".$FolioP, 1, 'C', 1, 1, '' ,'', true);
                $current_x+=$cell_width_sf;
                $pdf->SetXY($current_x, $current_y);  
                
                $pdf->SetFont('Arial','',8);
                $pdf->MultiCell($cell_width_np,$cell_height,$Np, 1, 'C', 1, 1, '' ,'', true);
                $current_x+=$cell_width_np;
                $pdf->SetXY($current_x, $current_y); 
    
                $pdf->MultiCell($cell_width_ImpSaldoAnt ,$cell_height,"$ ".$ImpSaldoAnt, 1, 'C', 1, 1, '' ,'', true);
                $current_x+=$cell_width_ImpSaldoAnt;
                $pdf->SetXY($current_x, $current_y);   
    
                $pdf->MultiCell($cell_width_ImpPagado,$cell_height,"$ ".$ImpPagado, 1, 'C', 1, 1, '' ,'', true);
                $current_x+=$cell_width_ImpPagado;
                $pdf->SetXY($current_x, $current_y);   
    
                $pdf->MultiCell($cell_width_ImpSaldoInsoluto,$cell_height,"$ ".$ImpSaldoInsoluto, 1, 'C', 1, 1, '' ,'', true);
                $current_x+=$cell_width_ImpSaldoInsoluto;
                $pdf->SetXY($current_x, $current_y);   
    
                $pdf->Ln();
                $current_x=$start_x;                       //set x to start_x (beginning of line)
                $current_y+=$cell_height;   
                $pdf->SetXY($current_x, $current_y);                 
            }
        }

          
        $pdf->Line(10, 201, 205, 201); // se modifico la linea
                       
                $texto1 = $monto_letras = numletras($Monto, 1);
                
                $pdf->SetXY(10, 204);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFont("Arial", "", 8);
                $pdf->MultiCell(135, 3, "CANTIDAD CON LETRAS : ".$texto1 . mone($MonedaP), 0, "L"); // me modifico esta linea

                $pdf->SetXY(10, 208);
                $pdf->SetFont("Arial", "", 8);
                $pdf->MultiCell(135, 3, "Moneda: " . mone($MonedaP)." ", 0, "L");

                
                $pdf->SetXY(10, 212);
                $pdf->SetFont("Arial", "", 8);
                $pdf->MultiCell(135, 2, "Forma de Pago : " .  formap($FormaDePagoP), 0, "L");

                $pdf->SetXY(10, 216);
                $pdf->SetFont("Arial", "", 8);
                $pdf->MultiCell(135, 2, "Fecha Timbrado : " . $cfdi14, 0, "L");

                $pdf->SetXY(10, 220);
                $pdf->SetFont("Arial", "", 8);
                $pdf->MultiCell(135, 2, "No. de Serie del Certificado del SAT: ". $sello4, 0, "L");
            
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

      $pdf->Line(10, 275, 200, 275);
    $pdf->Text(10, 280, utf8_decode("ESTE DOCUMENTO ES UNA REPRESENTACIÓN IMPRESA DE UN CFDI."));
    
    $pdf->Image(getcwd() . '/' . $carpeta . '/' . $foliox . '.codigoQr.jpg', 160, 230, 35, 38);


    // El documento enviado al navegador
    $pdf->Output($carpeta.'/'.$cfdi_pago_info[ "Comprobante" ][ "Serie" ]."_".$foliox."_timbrado.pdf".'.pdf', "F");

    $sql_xml_up = "update complementos set uuid = '" . $sello5 . "' where folio = '" . $foliox . "';";
    $mysqli->query($sql_xml_up);
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
        $result = "02 - Cueque nominativo";
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
        $result = "612 - Personas Físicas con Actividades Empresariales y Profesionales";
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
    //$ruta = "utilerias/xslt33/";
    //echo $cadena;
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
?>