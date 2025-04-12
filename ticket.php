<?php
setlocale(LC_CTYPE, 'es_MX');
include "core/controller/Core.php";
include "core/controller/Database.php";
include "core/controller/Executor.php";
include "core/controller/Model.php";
include "core/app/model/UserData.php";
include "core/app/model/SellData.php";
include "core/app/model/OperationData.php";
include "core/app/model/ProductData.php";
include "core/app/model/StockData.php";
include "core/app/model/ConfigurationData.php";
include "core/app/model/PersonData.php";
include "fpdf/fpdf.php";

session_start();
if (isset($_SESSION["user_id"])) {
    Core::$user = UserData::getById($_SESSION["user_id"]);
}
$symbol = ConfigurationData::getByPreffix("currency")->val;
if ($symbol == "€") {
    $symbol = chr(128);
} else if ($symbol == "₡") {
//echo intval("€");
    $symbol = '₡';
}

$title = ConfigurationData::getByPreffix("ticket_title")->val;
$cp = ConfigurationData::getByPreffix("codigo_postal")->val;
$calle = ConfigurationData::getByPreffix("calle")->val;
$rfc = ConfigurationData::getByPreffix("rfc")->val;
$calle = ConfigurationData::getByPreffix("calle")->val;
$telefono = ConfigurationData::getByPreffix("telefono")->val;
$whatsapp = ConfigurationData::getByPreffix("whatsapp")->val;
$iva_val = ConfigurationData::getByPreffix("imp-val")->val;
$ticket_image = ConfigurationData::getByPreffix("ticket_image")->val;
$sell = SellData::getById($_GET["id"]);
$currency = ConfigurationData::getByPreffix("currency")->val;
$stock = StockData::getById($sell->stock_to_id);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$user = $sell->getUser();

$pdf = new FPDF($orientation = 'P', $unit = 'mm', array(98, 400));

$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 6);    //Letra Arial, negrita (Bold), tam. 20
//$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
//$pdf->SetFont('DejaVu','',8);
//$pdf->setXY(5,0);
$plusforimage = 0;
//print_r($stock);
if($stock->image!=""){
     $ticket_image = $stock->image;
}
if ($ticket_image != "") {
     $src = "storage/stocks/". $ticket_image;
    if (file_exists($src)) {
        //echo "yes";
        $pdf->Image($src, 13, 2, 60);
        $plusforimage = 48;
    }
}


$textypos = 8 + $plusforimage;
//$pdf->ln(2);
//$pdf->setY(3);
//$pdf->setX(11);
//$pdf->Cell(3, $textypos, strtoupper($company_name));

$pdf->setY(35);
$pdf->setX(20);
$pdf->SetFont('courier', 'B', 10);
$pdf->MultiCell(70,5, strtoupper($stock->address));

$pdf->setY(40);
$pdf->setX(7);
$pdf->SetFont('courier', 'B', 10);
$pdf->MultiCell(70,5, strtoupper($stock->colonia));

$pdf->setY(45);
$pdf->setX(18);
$pdf->SetFont('courier', 'B', 10);
$pdf->MultiCell(70,5, strtoupper($stock->ciudad));

$pdf->setY(50);
$pdf->setX(16);
$pdf->SetFont('courier', 'B', 10);
$pdf->MultiCell(70,5, strtoupper("WhatsApp: ".$stock->phone));

$pdf->SetFont('courier', 'B', 11);
$pdf->setY(30);
$pdf->setX(2);
$pdf->Cell(5, $textypos, "================================");

$pagado = 0;
// se saca el estado del pago // pagado pendiente.
if($sell->p_id == 1 ){
    $pagado = "Pagada";
}else if ($sell->p_id ==2 ){
    $pagado = "Pendiente";
}else if ($sell->p_id ==4 ){
    $pagado = "Credito";
}

$entregado = 0;
// se saca el estado de entrega // entregado - pendiente.
if($sell->d_id == 1 ){
    $entregado = "Entregado";
}else if ($sell->d_id ==2 ){
    $entregado = "Pendiente";
}

$f_pago = 0;
// se saca el estado de entrega // entregado - pendiente.
if($sell->f_id == 1 ){
    $f_pago = "Efectivo";
}else if ($sell->f_id ==2 ){
    $f_pago = "Transferencia";
}

//$pdf->SetFont('DejaVu','',5);    //Letra Arial, negrita (Bold), tam. 20
$pdf->SetFont('courier', 'B', 5);    //Letra Arial, negrita (Bold), tam. 20
$textypos += 10;
$pdf->setX(2);

$textypos += 1;
$pdf->setX(20);
$pdf->SetFont('courier', 'B', 12);    //Letra Arial, negrita (Bold), tam. 20
$pdf->Cell(3, $textypos, " Ticket - " . $sell->id);
$pdf->ln(4);
$pdf->setX(8);
$pdf->SetFont('courier', '', 10);    //Letra Arial, negrita (Bold), tam. 20
$pdf->Cell(20, $textypos, "Fecha : " . $sell->created_at);
$pdf->ln(4);

$pdf->setX(21);
$pdf->SetFont('courier', '', 10);    //Letra Arial, negrita (Bold), tam. 20
$pdf->Cell(3, $textypos, "Pago  : " . $pagado);
$pdf->ln(4);

$pdf->setX(8);
$pdf->SetFont('courier', '', 10);    //Letra Arial, negrita (Bold), tam. 20
$pdf->Cell(3, $textypos, "Estatus Material : " . $entregado);
$pdf->ln(4);

$pdf->setX(10);
$pdf->SetFont('courier', '', 10);    //Letra Arial, negrita (Bold), tam. 20
$pdf->Cell(3, $textypos, "Forma Pago : " . $f_pago);

if ($sell->person_id != null) {
    $textypos += 10;
    $pdf->setX(2);
    $pdf->SetFont('courier', 'B', 11);    //Letra Arial, negrita (Bold), tam. 20
    $pdf->Cell(5, $textypos, '=== Informacion Del Cliente ===');

    $client = PersonData::getById($sell->person_id);
    $textypos += 8;
    $pdf->ln(43);
    $pdf->setX(20);
    $pdf->SetFont('courier', 'B', 10);    //Letra Arial, negrita (Bold), tam. 20
    $pdf->MultiCell(70,4,"  ".$client->name);

    $pdf->ln(2);
   
    }

    $textypos += -80;
    $pdf->setX(2);
    $pdf->SetFont('courier', 'b', 12);    //Letra Arial, negrita (Bold), tam. 20
    $pdf->Cell(5, $textypos, '===== Listado Productos =====');
    $textypos += 10;
    $pdf->setX(4);
    $pdf->SetFont('courier', 'B', 10);    //Letra Arial, negrita (Bold), tam. 20
    $pdf->Cell(5, $textypos,utf8_decode('CANT   DESCRIPCIÓN   PRECIO  TOTAL')); 
    $total = 0;
    $acumulador =0;
    $off = $textypos + -24;
    $pdf->ln(10 );
    foreach ($operations as $op) {
        $product = $op->getProduct();

        $pdf->setX(4);
        $pdf->SetFont('courier', 'B', 10);    //Letra Arial, negrita (Bold), tam. 20
        $pdf->MultiCell(70,3,$op->q);
    
        $pdf->setX(4);
        $pdf->SetFont('courier', 'B', 10);    //Letra Arial, negrita (Bold), tam. 20
        $pdf->MultiCell(75,4,utf8_encode($product->name." - "."Modelo".""."[".$product->code."]"));

        $pdf->setX(30);
        $pdf->SetFont('courier', 'B', 10);    //Letra Arial, negrita (Bold), tam. 20
        $pdf->MultiCell(70,6,"$symbol " . number_format($op->price_out, 2, ".", ",")."    $symbol " . number_format(($op->q*$op->price_out), 2, ".", ","));
        
        $total += $op->q * $op->price_out;

        $acumulador += $op->q;
      
    } 

    $textypos += 6;
    //////////////////////////////////////////////
    if (Core::$plus_iva == 0) {

        $textypos += -16;
        $pdf->setX(2);
        $pdf->SetFont('courier', 'B', 11);    //Letra Arial, negrita (Bold), tam. 20
        $pdf->Cell(5, $textypos, '======== Datos De Venta ========');

        $total = $sell->total;
        $iva_calc = ( ($total) / (1 + ($iva_val / 100) )) * ($iva_val / 100);
        $pdf->SetFont('courier', 'B', 12);    //Letra Arial, negrita (Bold), tam. 20
        $pdf->setX(5);
        $pdf->Cell(5, $off + 23, "SubTotal :  ");
        $pdf->setX(65);
        $pdf->Cell(5, $off + 23, "$symbol " . number_format($sell->sub_total , 2, ".", ","), 0, 0, "R");
        $pdf->setX(5);
        $pdf->Cell(5, $off + 34, "Impuesto :  ");
        $pdf->setX(65);
        $pdf->Cell(5, $off + 34, "$symbol " . number_format($sell->iva, 2, ".", ","), 0, 0, "R");
        $pdf->setX(5);
        $pdf->Cell(5, $off + 46, "Descuento:  ");
        $pdf->setX(65);
        $pdf->Cell(5, $off + 46, "$symbol " . number_format($sell->discount, 2, ".", ","), 0, 0, "R");
        $pdf->SetFont('courier', 'B', 12);    //Letra Arial, negrita (Bold), tam. 20
        $pdf->setX(5);
        $pdf->Cell(5, $off + 57, "Total :  ");
        $pdf->setX(65);
        $pdf->Cell(5, $off + 57, "$symbol " . number_format($total, 2, ".", ","), 0, 0, "R");
        $pdf->SetFont('courier', 'B', 12);    //Letra Arial, negrita (Bold), tam. 20
        $pdf->setX(5);
        $pdf->Cell(5, $off + 69, "Anticipo : ");
        $pdf->setX(65);
        $pdf->Cell(5, $off + 69, "$symbol " . number_format($sell->reg_anticipo, 2, ".", ","), 0, 0, "R");
        $pdf->setX(5);
        $pdf->Cell(5, $off + 80, "Por Pagar : ");
        $pdf->setX(65);
        $pdf->Cell(5, $off + 80, "$symbol " . number_format($sell->total_por_pagar, 2, ".", ","), 0, 0, "R");
    
        $pdf->setX(1);
        $pdf->SetFont('courier', 'B', 11);    //Letra Arial, negrita (Bold), tam. 20
        $pdf->Cell(1, $off + 95, "=================================");
    }


  
        $pdf->SetFont('courier', 'B', 10);    //Letra Arial, negrita (Bold), tam. 20   
        $pdf->setX(25);
        $pdf->Cell(5, $off + 103, 'Articulos :  ' . strtoupper($acumulador));

     
        $pdf->SetFont('courier', 'B', 10);    //Letra Arial, negrita (Bold), tam. 20   
        $pdf->setX(5);
        $pdf->Cell(5, $off + 113, 'FUE ATENDIDO POR :  ' . strtoupper($user->name . " " . $user->lastname));
        
           
          
        $pdf->ln(8);
        $pdf->SetFont('courier', '', 10);    //Letra Arial, negrita (Bold), tam. 20   
        $pdf->setX(3);
        $pdf->Cell(5, $off + 110, 'ESTE TICKET NO ES COMPROBANTE FISCAL');


        $pdf->ln(6);
        $pdf->SetFont('courier', '', 8);    //Letra Arial, negrita (Bold), tam. 20   
        $pdf->setX(4);
        $pdf->Cell(5, $off + 110, 'Todos nuestros precios son netos, por favor');

        $pdf->ln(4);
        $pdf->SetFont('courier', '', 8);    //Letra Arial, negrita (Bold), tam. 20   
        $pdf->setX(4);
        $pdf->Cell(5, $off + 110, utf8_decode(' envíenos su ticket al número de Whatsapp.'));

        $pdf->ln(4);
        $pdf->SetFont('courier', '', 8);    //Letra Arial, negrita (Bold), tam. 20   
        $pdf->setX(4);
        $pdf->Cell(5, $off + 110, utf8_decode('Así como su constancia de situación fiscal,'));

        $pdf->ln(4);
        $pdf->SetFont('courier', '', 8);    //Letra Arial, negrita (Bold), tam. 20   
        $pdf->setX(4);
        $pdf->Cell(5, $off + 110, utf8_decode('    actualizada uso del CFDI y correo.'));

        $pdf->ln(4);
        $pdf->SetFont('courier', '', 8);    //Letra Arial, negrita (Bold), tam. 20   
        $pdf->setX(4);
        $pdf->Cell(5, $off + 110, utf8_decode('   A la brevedad le enviaremos su factura,'));

        $pdf->ln(3);
        $pdf->SetFont('courier', '', 8);    //Letra Arial, negrita (Bold), tam. 20   
        $pdf->setX(4);
        $pdf->Cell(5, $off + 110, utf8_decode('            correspondiente.'));



        $pdf->ln(58);
        $pdf->SetFont('courier', 'B', 10);    //Letra Arial, negrita (Bold), tam. 20   
        $pdf->setX(20);
        $pdf->Cell(5, $off + 11, $stock->name);
        $pdf->ln(4);   
        $pdf->setX(5);
        $pdf->Cell(5, $off + 12, 'AGRADECE SU CONFIANZA Y PREFERENCIA'); 
        

        $pdf->output();




