<?php

include '../../../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");

$folio = $_POST['uuid'];
$idcancelacion = $_POST['idcancelacion'];
$uuid_sustitucion = $_POST['uuid_sustitucion'];
$stock = $_POST['stock'];


// FOLIO UUID PRUEBAS $folioUUID = strtoupper('69aef800-91ee-40f3-8028-1d3f499db6f3');
//PAC DE TIMBRADO DE PRUEBA $ws = "https://cfdi33-pruebas.buzoncfdi.mx:1443/Timbrado.asmx?wsdl";

$ws = "https://timbracfdi33.mx:1443/Timbrado.asmx?wsdl"; /* <- Esta ruta es para el servicio de pruebas, para pasar a productivo cambiar por https://timbracfdi33.mx:1443/Timbrado.asmx */
//$ws = "https://cfdi33-pruebas.buzoncfdi.mx:1443/Timbrado.asmx?wsdl";
$response = '';


    /* Nombre del usuario integrador asignado, para efecto de pruebas utilizaremos 'mvpNUXmQfK8=' <- Este usuario es para el servicio de pruebas, para pasar a productivo cambiar por el que le asignarán posteriormente */
    $usuarioIntegrador = 'K/nl+ecaEJOJpAUUY1c4Kg==';
    //$usuarioIntegrador = 'mvpNUXmQfK8=';
    //  RFC DE PRUEBAS
    //$rfcEmisor = 'IIA040805DZ4';

    /* Rfc del Emisor que emitió el comprobante, el rfc del emisor deberá ser 'AAA010101AAA' para efecto de pruebas */
    
    if ($stock == 1) {
     $rfcEmisor = 'RESJ7906225Z3';
     }else if ($stock == 2){ 
     $rfcEmisor = 'HEVS920204I27';
     }else if ($stock == 3){ 
     $rfcEmisor = 'HESJ570902B14';  
     }
     
    //$rfcEmisor = 'MPU210525CJ6';
    /* Folio fiscal(UUID) del comprobante a cancelar, deberá ser uno válido de los que hayamos timbrado previamente en pruebas */
    $folioUUID = strtoupper($folio);
     
    /*Motivo de cancelaciÃ³n del comprobante*/
    $motivoCancelacion = $idcancelacion;
    /*Folio fiscal de sustituciÃ³n, especificar valor sÃ³lo si se desea cancelar con motivo 01*/
    $folioUUIDSustitucion = $uuid_sustitucion;


try {
    $params = array();
    /* Nombre del usuario integrador asignado, para efecto de pruebas utilizaremos 'mvpNUXmQfK8=' */
    $params['usuarioIntegrador'] = $usuarioIntegrador;
    /* Rfc emisor que emitió el comprobante */
    $params['rfcEmisor'] = $rfcEmisor;
    /* Folio fiscal del comprobante a cancelar */
    $params['folioUUID'] = $folioUUID;
    /* Motivo de cancelaciÃ³n del comprobante */
    $params['motivoCancelacion'] = $motivoCancelacion; 
    /* Folio fiscal de sustituciÃ³n en caso de que el motivo de cancelaciÃ³n sea 01 */
     $params['folioUUIDSustitucion'] = $folioUUIDSustitucion;

     $context = stream_context_create(array(
        'ssl' => array(
            // set some SSL/TLS specific options
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true  //--> solamente true en ambiente de pruebas
        ),
        'http' => array(
            'user_agent' => 'PHPSoapClient'
        )
    ));
    $options = array();
    $options['stream_context'] = $context;
    $options['cache_wsdl'] = WSDL_CACHE_MEMORY;
    $options['trace'] = true;

 
    $client = new SoapClient($ws, $options);
    $response = $client->__soapCall('CancelaCFDI40', array('parameters' => $params));
} catch (SoapFault $fault) {
    echo "SOAPFault: " . $fault->faultcode . "-" . $fault->faultstring . "\n";
}

    /* Obtenemos resultado del response */
    $tipoExcepcion = $response->CancelaCFDI40Result->anyType[0];
    $numeroExcepcion = $response->CancelaCFDI40Result->anyType[1];
    $descripcionResultado = $response->CancelaCFDI40Result->anyType[2];
    $xmlTimbrado = $response->CancelaCFDI40Result->anyType[3];
    $codigoQr = $response->CancelaCFDI40Result->anyType[4];
    $cadenaOriginal = $response->CancelaCFDI40Result->anyType[5];


if ($numeroExcepcion == "0") {
    

    $sql_xml_up2 = "update complementos set timbrado = '3'  where  UUID = '" . $folio . "';";
    $mysqli->query($sql_xml_up2);

    $sql_xml_up3 = "update complementos set fecha_cancelacion = NOW()  where  UUID = '" . $folio . "';";
    $mysqli->query($sql_xml_up3);
    
    $sql_xml_up4 = "update complementos set id_motcancelacion = '" . $motivoCancelacion . "'  where  UUID = '" . $folio . "';";
    $mysqli->query($sql_xml_up4);
    
    $sql_xml_up5 = "update complementos set uuid_sustitucion = '" . $folioUUIDSustitucion . "' where  UUID = '" . $folio . "';";
    $mysqli->query($sql_xml_up5);
    
   // echo $sql_xml_up2;

    echo true;
}   else {
    echo $descripcionResultado;
}?>

   