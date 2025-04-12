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
include 'connection/conexion.php';
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

$name = ConfigurationData::getByPreffix("company_name")->val;
$title = ConfigurationData::getByPreffix("ticket_title")->val;
$direction = ConfigurationData::getByPreffix("direction")->val;
$calle = ConfigurationData::getByPreffix("calle")->val;
$rfc = ConfigurationData::getByPreffix("rfc")->val;
$telefono = ConfigurationData::getByPreffix("telefono")->val;
$iva_val = ConfigurationData::getByPreffix("imp-val")->val;
$ticket_image = ConfigurationData::getByPreffix("ticket_image")->val;
$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$stock = StockData::getById($sell->stock_to_id);
$user = $sell->getUser();
$stock_to = $sell->getStockTo();
$stock_from = $sell->getStockFrom();

$id = $_GET['id'];
$sucursal = $_GET['sucursal'];


$suc_name = "SELECT * from stock where id = $sucursal";
$resultado = $mysqli->query($suc_name);
$row = mysqli_fetch_assoc($resultado);
$nombre_sucursal = $row['name']; 
$direccion_sucursal = $row['address']; 
$colonia = $row['colonia']; 
$ciudad = $row['ciudad']; 
$telefono = $row['phone']; 
$wa = $row['field2']; 
$email = $row['email']; 
$imagen = $row['image'];


$pdf = new FPDF($orientation = 'P', $unit = 'mm');

$pdf->AddPage();
$pdf->SetFont('Arial', '', 6);    //Letra Arial, negrita (Bold), tam. 20
//$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
//$pdf->SetFont('DejaVu','',8);
//$pdf->setXY(5,0);
$plusforimage = 0;
//print_r($stock);


if($stock->image!=""){
        $ticket_image = $stock->image;}

   if ($ticket_image != "") {
        $src = "storage/stocks/". $imagen;
    
       if (file_exists($src)) {
        $pdf->setX(10);
           $pdf->Image($src,150, 4, 45);
           $plusforimage = 25;
           }
        }
    date_default_timezone_set('America/Monterrey');
    $pdf->SetFont('Arial', 'B', 9);

    $ancho = 190;
    $yy = 10; //Variable auxiliar para desplazarse 40 puntos del borde superior hacia abajo en la coordenada de las Y para evitar que el título este al nivel de la cabecera.
    $y = $pdf->GetY(); 
    $x = 10;
    $textypos = 8 + $plusforimage;

$pdf->SetY(30); //Mencionamos que el curso en la posición Y empezará a los 12 puntos para escribir el Usuario:
$pdf->SetX(130);
$pdf->Cell(5,$textypos,'Fecha Impresion : '.date('d/m/Y')." | ".date('H:i:s'), 0, 0, 'R');


$pdf->setY(5);
$pdf->setX(10);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(5,$textypos, $direccion_sucursal);

$pdf->setY(10);
$pdf->setX(10);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(5,$textypos, $colonia);

$pdf->setY(15);
$pdf->setX(10);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(5,$textypos, $ciudad);

$pdf->setY(20);
$pdf->setX(10);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(5,$textypos, "WhatsApp: ".$telefono);

$pdf->setY(25);
$pdf->setX(10);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(5,$textypos, $email);


$pdf->SetFont('helvetica', 'B', 12); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(80, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(50, 5, "COMPROBANTE TRASPASO CANCELADO ", 0, 4, 'C');
$pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(95, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página


$pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,60,"Informacion General Del Traspaso  _____________________________________________________________________");


$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(22, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,80, "Sucursal Envia  : ". $stock_from->name);

$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(120, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,80, "Sucursal Recibe : ". $stock_to->name);

$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(120, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,92, "Fecha Cancelacion : ". $sell->cancelacion);


$sql_venta="select * from sell where id = $id ";
$resultado = $mysqli->query($sql_venta);
while($row = $resultado->fetch_assoc()){
   
$fecha_pago = $row['fecha_pago']; 
$referencia_venta = $row['ref_id']; 
$total = $row['total']; 
$anticipo = $row['anticipo_venta'];
$por_pagar = $row['total_por_pagar'];  
$fecha_traspaso = $row['created_at'];  
$folio_factura = $row['invoice_code'];    
$sub_total = $row['sub_total'];
$iva = $row['iva'];    
$comentarios = $row['comment'];   
$usuario = $row['usuario_cancelo'];   
$chofer = $row['chofer'];  
$motivo = $row['motivo'];  
$p_id = $row['p_id'];  

$sql = "SELECT name from user where id = '".$usuario."'";
$resultado = $mysqli->query($sql);
while($row=mysqli_fetch_array($resultado)){
$usuario = $row[0];}


}

$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(120, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,105, "Usuario Cancelo : ". $usuario);

$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(120, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,119, "Chofer : ". $chofer);

$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(22, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,92, "ID Traspaso : ". $id);

$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(22, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,104, "Fecha Traspaso : ".$fecha_traspaso);

$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(22, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,118, "Usuario : ". $user->name." ".$user->lastname);


$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(14, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,132, "Observaciones / Comentarios : ");

$pdf->SetFont('helvetica', 'B', 8); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(15, 90 ); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->MultiCell(190,4, "".$comentarios, 1, 'J');

$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(14, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,160, "Motivo Cancelacion : ");

$pdf->SetFont('helvetica', 'B', 8); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(15, 104 ); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->MultiCell(190,4, "".$motivo, 1, 'J');

$pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,190,"Listado De Productos En Traspaso ____________________________________________________________________");
        

       $sql = "SELECT operation.q,product.name,product.code,operation.price_out, operation.product_id from operation INNER JOIN product on
       operation.product_id = product.id and 
       operation.sell_id = $id  and operation_type_id = 7 ";
        $resultado = $mysqli->query($sql);


        $pdf->ln(100);
        $pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
        $y = $pdf->GetY(); 
        $pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
        $pdf->SetFont('arial', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
        $y = $pdf->GetY() + 1;
        $pdf->SetXY(10, $y);
        $pdf->MultiCell(15, 4, utf8_decode("Cant"), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
        $pdf->SetXY(25, $y);
        $pdf->MultiCell(25, 4, utf8_decode("Codigo"), 1, 'C');
        $pdf->SetXY(50, $y);
        $pdf->MultiCell(155, 4, utf8_decode("Descripción"), 1, 'C');  
        /*$pdf->SetXY(155, $y);
        $pdf->MultiCell(20, 4, utf8_decode("Precio U."), 1, 'C');
        $pdf->SetXY(175, $y);
        $pdf->MultiCell(27, 4, utf8_decode("Importe"), 1, 'C');  
        $pdf->SetFont('arial', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente */
        $total_entregado=0;

       
        while($row = $resultado->fetch_assoc()){
       $pdf->cell(15, 7,$row['q'],1,0,'C');
       $pdf->cell(25, 7,$row['code'],1,0,'C');
       $pdf->Multicell(155, 7,$row['name'],1,'C');
      /* $pdf->cell(20, 7,"$ ".$row['price_out'],1,0,'C');
       $total_entregado = $row['q'] * $row['price_out'];
       $pdf->Multicell(27, 7,"$ ".$total_entregado,1,'C'); */

    
}


$pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
 $pdf->ln(2);
 $pdf->SetX(65);
 $pdf->Cell(48,15,"___________________________________________");

 $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
 $pdf->ln(6);
 $pdf->SetX(85);
 $pdf->Cell(48,17,"Firma De Conformidad");



  



  $pdf->output();


        
 
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    



 








































