<?php

/** Error reporting */
error_reporting(0);


include "../core/autoload.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/OperationTypeData.php";

/** Include PHPExcel */
//require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
//require_once '../core/controller/PHPExcel/Classes/PHPExcel.php';
include "../vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$objPHPExcel = new Spreadsheet();

// Create new PHPExcel object
//$objPHPExcel = new PHPExcel();
//$products = ProductData::getAll();

$operations = array();

			if($_GET["product_id"]==""){
			$operations = OperationData::getAllByDateOfficial($_GET["stock_id"],$_GET["sd"],$_GET["ed"]);
			}
			else{
			$operations = OperationData::getAllByDateOfficialBP($_GET["stock_id"],$_GET["product_id"],$_GET["sd"],$_GET["ed"]);
			} 


// Set document properties
$objPHPExcel->getProperties()->setCreator("Inventio Max v3.1")
							 ->setLastModifiedBy("Inventio Max v3.1")
							 ->setTitle("Report - Inventio Max v3.1")
							 ->setSubject("Inventio Max Report")
							 ->setDescription("")
							 ->setKeywords("")
							 ->setCategory("");


// Add some data
$sheet = $objPHPExcel->setActiveSheetIndex(0);

$sheet->setCellValue('A1', 'Reporte de Inventario - Inventio Max')
->setCellValue('A2', 'Id')
->setCellValue('B2', 'Producto')
->setCellValue('C2', 'Cantidad')
->setCellValue('D2', 'Operacion')
->setCellValue('E2', 'Fecha');

$start = 3;
foreach($operations as $operation){
$sheet->setCellValue('A'.$start, $operation->id)
->setCellValue('B'.$start, $operation->getProduct()->name)
->setCellValue('C'.$start, $operation->q)
->setCellValue('D'.$start, $operation->getOperationType()->name)
->setCellValue('E'.$start, $operation->created_at);

$start++;
}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


////////////////////////////////////////////////////
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reports-'.time().'.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0
//////////////////////////////////////////////////////
$writer = new Xlsx($objPHPExcel);
$writer->save('php://output');
