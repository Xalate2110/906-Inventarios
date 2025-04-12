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
if(isset($_SESSION["user_id"])){ Core::$user = UserData::getById($_SESSION["user_id"]); }
$symbol = ConfigurationData::getByPreffix("currency")->val;
if($symbol=="€"){ $symbol=chr(128); }
else if($symbol=="₡"){ 
//echo intval("€");
	$symbol=    '₡';



}

$product = ProductData::getById($_GET["id"]);

$title = ConfigurationData::getByPreffix("ticket_title")->val;
$iva_val = ConfigurationData::getByPreffix("imp-val")->val;
$ticket_image = ConfigurationData::getByPreffix("ticket_image")->val;




$pdf = new FPDF($orientation='P',$unit='mm');

$pdf->AddPage();
$pdf->SetFont('Arial','B',18);    //Letra Arial, negrita (Bold), tam. 20
//$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);

//$pdf->SetFont('DejaVu','',8);

//$pdf->setXY(5,0);
$plusforimage =0;
if($ticket_image!=""){
	$src = "storage/configuration/".$ticket_image;
	if(file_exists($src)){
		$pdf->Image($src,12,2,15);		
		$plusforimage=25;
	}
}

for($i=0; $i<300; $i+=50){
for($j=0; $j<300; $j+=40){
$pdf->Image('http://localhost/inventio-max-v9.5/?action=generatebarcode&id='.$product->id, 10+$i, 30+$j, 40, 0, 'PNG');
}
}

$textypos = 11+$plusforimage;
$pdf->setY(10);
$pdf->setX(10);
$pdf->Cell(5,$textypos,strtoupper($product->name." - CODIGOS DE BARRA"));
//$pdf->SetFont('DejaVu','',5);    //Letra Arial, negrita (Bold), tam. 20

$pdf->output();
