<?php

$product = ProductData::getById($_GET["id"]);


require_once('core/controller/barcode/BCGFontFile.php');
require_once('core/controller/barcode/BCGColor.php');
require_once('core/controller/barcode/BCGDrawing.php');

require_once('core/controller/barcode/BCGcode128.barcode.php');
header('Content-Type: image/png');

$colorFront = new BCGColor(0, 0, 0);
$colorBack = new BCGColor(255, 255, 255);

$code = new BCGcode128();
$code->setScale(4);
$code->setThickness(30);
$code->setForegroundColor($colorFront);
$code->setBackgroundColor($colorBack);
$code->parse($product->barcode);

$drawing = new BCGDrawing('', $colorBack);
$drawing->setBarcode($code);

$drawing->draw();
$drawing->finish(BCGDrawing::IMG_FORMAT_PNG, "storage/barcodes/product-".$product->id.".png");
?>