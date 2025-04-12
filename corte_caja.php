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
$operations = OperationData::getAllProductsBySellId($_GET["id"]);

$caja = $_GET['id'];
$fecha = $_GET['created_at'];
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

$pdf = new FPDF($orientation = 'L', $unit = 'mm', 'A4');

$pdf->AddPage();
$pdf->SetFont('Arial', '', 6);    //Letra Arial, negrita (Bold), tam. 20
//$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
//$pdf->SetFont('DejaVu','',8);
//$pdf->setXY(5,0);
if($stock->image!=""){
  $ticket_image = $stock->image;}

if ($ticket_image != "") {
  $src = "storage/stocks/". $imagen;

 if (file_exists($src)) {
  $pdf->setX(10);
     $pdf->Image($src,220, 4, 45);
     $plusforimage = 25;
     }
  }
date_default_timezone_set('America/Monterrey');
$pdf->SetFont('Arial', 'B', 9);

$yy = 10; //Variable auxiliar para desplazarse 40 puntos del borde superior hacia abajo en la coordenada de las Y para evitar que el título este al nivel de la cabecera.
$y = $pdf->GetY(); 
$x = 10;
$ancho = 190;
$textypos = 8 + $plusforimage;

$pdf->SetY(12); //Mencionamos que el curso en la posición Y empezará a los 12 puntos para escribir el Usuario:

/*
$pdf->SetY(15);
$pdf->Cell($ancho, 13,'Fecha Impresion : '.date('d/m/Y'), 0, 0, 'R');
$pdf->SetY(18);
$pdf->Cell($ancho, 16,'Hora Impresion : '.date('H:i:s'), 0, 0, 'R');     */

$pdf->SetY(10); //Mencionamos que el curso en la posición Y empezará a los 12 puntos para escribir el Usuario:
$pdf->SetX(170);
$pdf->Cell(5,$textypos,'Fecha Impresion : '.date('d/m/Y')." | ".date('H:i:s'), 0, 0, 'R');


$pdf->SetXY(130, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,25, "Numero Corte : ". $caja);

$pdf->SetXY(116, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(220,40, "Sucursal : ". utf8_decode($nombre_sucursal));



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


$pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(130, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(30, 1, "Resumen Corte De Caja ", 0, 4, 'C');

$pdf->SetFont('helvetica', 'B', 12); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(350,65, "REPORTE VENTAS DE MOSTRADOR DEL DIA");

$pdf->SetDrawColor(188,188,188);

$pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,80,"Listado - Ventas De Mostrador - Efectivo: ");


// SACAMOS SOLAMENTE VENTAS EN EFECTIVO y COMISIÓN 

// SELECT * from sell where box_id ='".$caja."' and p_id = 1 and operation_type_id = 2 and f_id = 1 and invoice_code = ''  

        $sql = "SELECT sell.id,sell.created_at,sell.p_id,sell.f_id,sell.cash,sell.total,sell.anticipo_venta,sell.user_id,user.name,CONCAT(person.name) AS CLIENTE from sell inner join user on sell.user_id = user.id 
        inner join person on person.id = sell.person_id
        where box_id = '".$caja."' and p_id = 1 and operation_type_id = 2 and f_id = 1 and credito_liquidado = 0 and stock_to_id = $sucursal and facturado = 0 and remision_recuperada = 0 
        and is_draft = 0 order by sell.id";
        $resultado = $mysqli->query($sql);
        $pdf->ln(45);
        $pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
        $y = $pdf->GetY(); 
        $pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
        $pdf->SetFont('arial', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
        $y = $pdf->GetY() + 1;
        $pdf->SetXY(10, $y);
        $pdf->Cell(18, 4, utf8_decode("Nº Remision"), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
        $pdf->SetXY(28, $y); //El resultado 22 es la suma de la posición 10 y el tamaño del Cell de 12.
        $pdf->Cell(50, 4, utf8_decode("Fecha / Hora Remision"), 1, 'C');
        $pdf->SetXY(78, $y);
        $pdf->Cell(20, 4, utf8_decode("Estado"), 1, 'C');
        $pdf->SetXY(98, $y);
        $pdf->Cell(30, 4, utf8_decode("Forma Pago"), 1, 'C');   
        $pdf->SetXY(128, $y);
        $pdf->Cell(25, 4, utf8_decode("Efectivo"), 1, 'C');  
        $pdf->SetXY(153, $y);
        $pdf->Cell(18, 4, utf8_decode("Total"), 1, 'C');
        $pdf->SetXY(171, $y);
        $pdf->Cell(30, 4, utf8_decode("Usuario"), 1, 'C');  
        $pdf->SetXY(201, $y);
        $pdf->Cell(85, 4, utf8_decode("Nombre Del Cliente"), 1, 'C');  
        $total_te=0;
        while($row = $resultado->fetch_assoc()){
        // Se saca la forma de Pago.
        if($row['f_id'] == '1'){
         $forma_pago = "Efectivo";}
        
       $status_ticket = 0;
     // Se saca si esta Habilitado o Cancelada la Remision
       if($row['p_id'] == '1'){
        $status_ticket = "Habilitado";
       }elseif($row['p_id'] == '3'){
        $status_ticket = "Cancelado";
       } 
       $pdf->ln(6);
       $pdf->cell(18, 7,$row['id'],1,0,'C');
       $pdf->cell(50, 7,$row['created_at'],1,0,'C');
       $pdf->cell(20, 7,$status_ticket,1,0,'C');
       $pdf->cell(30, 7,$forma_pago,1,0,'C');
       $pdf->cell(25, 7,number_format($row['cash'],2,'.',','),1,0,'C');
       $pdf->cell(18, 7,number_format($row['total'],2,'.',','),1,0,'C');
       $pdf->cell(30, 7,$row['name'],1,0,'C');
       $pdf->Cell(85, 7,$row['CLIENTE'],1,'C');
       $total_te = $total_te + $row['total'];
       } 
       $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
       $pdf->ln(10);
       $pdf->SetX(220);
       $pdf->cell(48, 7,"Total Efectivo : $ ".number_format($total_te,2,'.',','),0,0,'C');
       $pdf->ln(5);


       // SE SACA EL REPORTE DE VENTAS DE MOSTRADOR SOLAMENTE TRANSFERENCIA ELECTRONICA.

       $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
       $pdf->Cell(300,14,"Listado - Ventas De Mostrador - Transferencia Electronica: ");
       $pdf->ln(10);
       $pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
       $y = $pdf->GetY(); 
       $pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
       $pdf->SetFont('arial', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
       $y = $pdf->GetY() + 1;
       $pdf->SetXY(10, $y);
       $pdf->Cell(18, 4, utf8_decode("Nº Remision"), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
       $pdf->SetXY(28, $y); //El resultado 22 es la suma de la posición 10 y el tamaño del Cell de 12.
       $pdf->Cell(50, 4, utf8_decode("Fecha / Hora Remision"), 1, 'C');
       $pdf->SetXY(78, $y);
       $pdf->Cell(20, 4, utf8_decode("Estado"), 1, 'C');
       $pdf->SetXY(98, $y);
       $pdf->Cell(30, 4, utf8_decode("Forma Pago"), 1, 'C');   
       $pdf->SetXY(128, $y);
       $pdf->Cell(25, 4, utf8_decode("Comisión Tarjeta"), 1, 'C');
       $pdf->SetXY(153, $y);
       $pdf->Cell(18, 4, utf8_decode("Total"), 1, 'C');
       $pdf->SetXY(171, $y);
       $pdf->Cell(30, 4, utf8_decode("Usuario"), 1, 'C');  
       $pdf->SetXY(201, $y);
       $pdf->Cell(85, 4, utf8_decode("Nombre Del Cliente"), 1, 'C');  
       
       $sql_te = " SELECT sell.id,sell.created_at,sell.p_id,sell.f_id,sell.comision_tarjeta,sell.total,sell.user_id,user.name,CONCAT(person.name) AS CLIENTE from sell inner join user on sell.user_id = user.id
      inner join person on person.id = sell.person_id
       where box_id = '".$caja."'  and p_id = 1 and operation_type_id = 2 and f_id = 2 and stock_to_id = $sucursal and facturado = 0 and credito_liquidado = 0 and remision_recuperada = 0 order by sell.id ";
       $resultado_te = $mysqli->query($sql_te);
       
       $total_tre = 0;
       while($row = $resultado_te->fetch_assoc()){
       // Se saca la forma de Pago.
       if($row['f_id'] == '2'){
        $forma_pago = "Transf. Electronica";
       }
   
       // Se saca si esta Habilitado o Cancelada la Remision
       if($row['p_id'] == '1'){
       $status_ticket = "Habilitado";
       }elseif($row['p_id'] == '2'){
       $status_ticket = "Cancelado";
       } 
       $pdf->ln(6);
       $pdf->cell(18, 7,$row['id'],1,0,'C');
       $pdf->cell(50, 7,$row['created_at'],1,0,'C');
       $pdf->cell(20, 7,$status_ticket,1,0,'C');
       $pdf->cell(30, 7,$forma_pago,1,0,'C');
       $pdf->cell(25, 7,$row['comision_tarjeta'],1,0,'C');
       $pdf->cell(18, 7,$row['total'],1,0,'C');
       $pdf->cell(30, 7,$row['name'],1,0,'C');
       $pdf->Cell(85, 7,$row['CLIENTE'],1,'C');
       
       $total_tre = $total_tre + $row['total'];
} 

    $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->ln(10);
    $pdf->SetX(220);
    $pdf->cell(48, 7,"Total Transferencia: $ ".number_format($total_tre,2,'.',','),0,0,'C');
    $pdf->ln(5);

    // SE SACA EL REPORTE DE VENTAS DE MOSTRADOR SOLAMENTE TARJETA DE CRÉDITO
    $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->Cell(259,14,"Listado - Ventas De Mostrador - Tarjeta Credito: ");
    $pdf->ln(10);

    $pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
    $y = $pdf->GetY(); 
    $pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
    $pdf->SetFont('arial', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
    $y = $pdf->GetY() + 1;
    $pdf->SetXY(10, $y);
    $pdf->Cell(18, 4, utf8_decode("Nº Remision"), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
    $pdf->SetXY(28, $y); //El resultado 22 es la suma de la posición 10 y el tamaño del Cell de 12.
    $pdf->Cell(50, 4, utf8_decode("Fecha / Hora Remision"), 1, 'C');
    $pdf->SetXY(78, $y);
    $pdf->Cell(20, 4, utf8_decode("Estado"), 1, 'C');
    $pdf->SetXY(98, $y);
    $pdf->Cell(30, 4, utf8_decode("Forma Pago"), 1, 'C');   
    $pdf->SetXY(128, $y);
    $pdf->Cell(25, 4, utf8_decode("Comisión Tarjeta"), 1, 'C');
    $pdf->SetXY(153, $y);
    $pdf->Cell(18, 4, utf8_decode("Total"), 1, 'C');
    $pdf->SetXY(171, $y);
    $pdf->Cell(30, 4, utf8_decode("Usuario"), 1, 'C');  
    $pdf->SetXY(201, $y);
    $pdf->Cell(85, 4, utf8_decode("Nombre Del Cliente"), 1, 'C');  
   
    // SELECT * from sell where box_id ='".$caja."' and p_id = 1 and operation_type_id = 2 and f_id = 3 and invoice_code = ''
    $sql_tc = "SELECT sell.id,sell.created_at,sell.p_id,sell.f_id,sell.comision_tarjeta,sell.total,sell.user_id,user.name,CONCAT(person.name) AS CLIENTE from sell inner join user on sell.user_id = user.id
    inner join person on person.id = sell.person_id
    where box_id = '".$caja."' and p_id = 1 and operation_type_id = 2 and f_id = 3 and invoice_code = '' and stock_to_id = $sucursal order by sell.id and facturado = 0";
    $resultado_tc = $mysqli->query($sql_tc);


    $total_tc = 0;
    while($row = $resultado_tc->fetch_assoc()){
    // Se saca la forma de Pago.
    if($row['f_id'] == '3'){
     $forma_pago = "Tarjeta Credito";
    }

    // Se saca si esta Habilitado o Cancelada la Remision
    if($row['p_id'] == '1'){
    $status_ticket = "Habilitado";
    }elseif($row['p_id'] == '2'){
    $status_ticket = "Cancelado";
    } 
    $pdf->ln(6);
    $pdf->cell(18, 7,$row['id'],1,0,'C');
    $pdf->cell(50, 7,$row['created_at'],1,0,'C');
    $pdf->cell(20, 7,$status_ticket,1,0,'C');
    $pdf->cell(30, 7,$forma_pago,1,0,'C');
    $pdf->cell(25, 7,$row['comision_tarjeta'],1,0,'C');
    $pdf->cell(18, 7,$row['total'],1,0,'C');
    $pdf->cell(30, 7,$row['name'],1,0,'C');
    $pdf->Cell(85, 7,$row['CLIENTE'],1,'C');
     
    $total_tc = $total_tc + $row['total'];
   } 

    $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->ln(10);
    $pdf->SetX(217);
    $pdf->cell(48, 7,"Total Tarjeta Credito : $ ".number_format($total_tc,2,'.',','),0,0,'C');
    $pdf->ln(5);

    // SE SACA EL REPORTE DE VENTAS DE MOSTRADOR SOLAMENTE TARJETA DE DEBITO.

     $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
     $pdf->Cell(300,14,"Listado - Ventas De Mostrador - Tarjeta Debito: ");
     $pdf->ln(10);
     $pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
     $y = $pdf->GetY(); 
     $pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
     $pdf->SetFont('arial', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
     $y = $pdf->GetY() + 1;
     $pdf->SetXY(10, $y);
     $pdf->Cell(18, 4, utf8_decode("Nº Remision"), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
     $pdf->SetXY(28, $y); //El resultado 22 es la suma de la posición 10 y el tamaño del Cell de 12.
     $pdf->Cell(50, 4, utf8_decode("Fecha / Hora Remision"), 1, 'C');
     $pdf->SetXY(78, $y);
     $pdf->Cell(20, 4, utf8_decode("Estado"), 1, 'C');
     $pdf->SetXY(98, $y);
     $pdf->Cell(30, 4, utf8_decode("Forma Pago"), 1, 'C');   
     $pdf->SetXY(128, $y);
     $pdf->Cell(25, 4, utf8_decode("Comisión Tarjeta"), 1, 'C');
     $pdf->SetXY(153, $y);
     $pdf->Cell(18, 4, utf8_decode("Total"), 1, 'C');
     $pdf->SetXY(171, $y);
     $pdf->Cell(30, 4, utf8_decode("Usuario"), 1, 'C');  
     $pdf->SetXY(201, $y);
     $pdf->Cell(85, 4, utf8_decode("Nombre Del Cliente"), 1, 'C');  
    
     
     $sql_td = "SELECT sell.id,sell.created_at,sell.p_id,sell.f_id,sell.comision_tarjeta,sell.total,sell.user_id,user.name,CONCAT(person.name) AS CLIENTE  from sell inner join user on sell.user_id = user.id
     inner join person on person.id = sell.person_id
     where box_id = '".$caja."' and p_id = 1 and operation_type_id = 2 and f_id = 4 and invoice_code = '' and stock_to_id = $sucursal order by sell.id";
     $resultado_td = $mysqli->query($sql_td);
 
     $total_td = 0;
     while($row = $resultado_td->fetch_assoc()){
     // Se saca la forma de Pago.
     if($row['f_id'] == '4'){
      $forma_pago = "Tarjeta Debito";
     }
     // Se saca si esta Habilitado o Cancelada la Remision
     if($row['p_id'] == '1'){
     $status_ticket = "Habilitado";
     }elseif($row['p_id'] == '2'){
     $status_ticket = "Cancelado";
     } 
     $pdf->ln(6);
     $pdf->cell(18, 7,$row['id'],1,0,'C');
     $pdf->cell(50, 7,$row['created_at'],1,0,'C');
     $pdf->cell(20, 7,$status_ticket,1,0,'C');
     $pdf->cell(30, 7,$forma_pago,1,0,'C');
     $pdf->cell(25, 7,$row['comision_tarjeta'],1,0,'C');
     $pdf->cell(18, 7,$row['total'],1,0,'C');
     $pdf->cell(30, 7,$row['name'],1,0,'C');
     $pdf->Cell(85, 7,$row['CLIENTE'],1,'C');

     $total_td = $total_td + $row['total'];
} 

    $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->ln(10);
    $pdf->SetX(220);
    $pdf->cell(48, 7,"Total Tarjeta Debito : $ ".number_format($total_td,2,'.',','),0,0,'C');
    $pdf->ln(5);
 
     // SE SACA EL REPORTE DE VENTAS DE MOSTRADOR SOLAMENTE COBRO CON ANTICIPO.

     $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
     $pdf->Cell(300,14,"Listado - Ventas De Mostrador - Cobro Con Anticipo: ");
     $pdf->ln(10);
     $pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
     $y = $pdf->GetY(); 
     $pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
     $pdf->SetFont('arial', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
     $y = $pdf->GetY() + 1;
     $pdf->SetXY(10, $y);
     $pdf->Cell(18, 4, utf8_decode("Nº Remision"), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
     $pdf->SetXY(28, $y); //El resultado 22 es la suma de la posición 10 y el tamaño del Cell de 12.
     $pdf->Cell(35, 4, utf8_decode("Fecha / Hora Remision"), 1, 'C');
     $pdf->SetXY(63, $y);
     $pdf->Cell(20, 4, utf8_decode("Estado"), 1, 'C');
     $pdf->SetXY(83, $y);
     $pdf->Cell(30, 4, utf8_decode("Forma Pago"), 1, 'C');   
     $pdf->SetXY(113, $y);
     $pdf->Cell(20, 4, utf8_decode("Total"), 1, 'C');
     $pdf->SetXY(133, $y);
     $pdf->Cell(30, 4, utf8_decode("Usuario"), 1, 'C'); 
     $pdf->SetXY(163, $y);
     $pdf->Cell(123, 4, utf8_decode("Nombre Cliente"), 1, 'C'); 
        
     $sql_ca = "SELECT sell.id,sell.created_at,sell.p_id,sell.f_id,sell.total,sell.user_id,sell.person_id,sell.box_id,user.name AS usuario,person.name from sell 
     INNER JOIN user on 
     user.id = sell.user_id 
     INNER JOIN person on 
     person.id = sell.person_id
     where box_id ='".$caja."' and p_id = 1 and operation_type_id = 2 and f_id = 5 and invoice_code = '' and stock_to_id = $sucursal and credito_liquidado = 0 and remision_recuperada = 0";
     $resultado_ca = $mysqli->query($sql_ca);
 
     $total_ca = 0;

     while($row = $resultado_ca->fetch_assoc()){
     // Se saca la forma de Pago.
     if($row['f_id'] == '5'){
      $forma_pago = "Cobrado Con Anticipo";
     }
 
     // Se saca si esta Habilitado o Cancelada la Remision
     if($row['p_id'] == '1'){
     $status_ticket = "Habilitado";
     }elseif($row['p_id'] == '2'){
     $status_ticket = "Cancelado";
     } 
     $pdf->ln(6);
     $pdf->cell(18, 7,$row['id'],1,0,'C');
     $pdf->cell(35, 7,$row['created_at'],1,0,'C');
     $pdf->cell(20, 7,$status_ticket,1,0,'C');
     $pdf->cell(30, 7,$forma_pago,1,0,'C');
     $pdf->cell(20, 7,$row['total'],1,0,'C');
     $pdf->cell(30, 7,$row['usuario'],1,0,'C'); 
     $pdf->Cell(123, 7,$row['name'],1,'C');
     $total_ca = $total_ca + $row['total'];
} 

    $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->ln(10);
    $pdf->SetX(220);
    $pdf->cell(48, 7,"Total Cobro Anticipo : $ ".number_format($total_ca,2,'.',','),0,0,'C');
    $pdf->ln(5);
   
     // SE SACA EL REPORTE DE VENTAS DE MOSTRADOR COBRADAS CON DEPOSITO BANCARIO.

     $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
     $pdf->Cell(300,14,"Listado - Ventas De Mostrador - Deposito Bancario: ");
     $pdf->ln(10);
     $pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
     $y = $pdf->GetY(); 
     $pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
     $pdf->SetFont('arial', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
     $y = $pdf->GetY() + 1;
     $pdf->SetXY(10, $y);
     $pdf->Cell(18, 4, utf8_decode("Nº Remision"), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
     $pdf->SetXY(28, $y); //El resultado 22 es la suma de la posición 10 y el tamaño del Cell de 12.
     $pdf->Cell(35, 4, utf8_decode("Fecha / Hora Remision"), 1, 'C');
     $pdf->SetXY(63, $y);
     $pdf->Cell(20, 4, utf8_decode("Estado"), 1, 'C');
     $pdf->SetXY(83, $y);
     $pdf->Cell(30, 4, utf8_decode("Forma Pago"), 1, 'C');   
     $pdf->SetXY(113, $y);
     $pdf->Cell(20, 4, utf8_decode("Total"), 1, 'C');
     $pdf->SetXY(133, $y);
     $pdf->Cell(30, 4, utf8_decode("Usuario"), 1, 'C'); 
     $pdf->SetXY(163, $y);
     $pdf->Cell(123, 4, utf8_decode("Nombre Cliente"), 1, 'C'); 
     
     $sql_db = "SELECT sell.id,sell.created_at,sell.p_id,sell.f_id,sell.total,sell.user_id,sell.person_id,sell.box_id,user.name AS usuario,person.name from sell 
     INNER JOIN user on 
     user.id = sell.user_id 
     INNER JOIN person on 
     person.id = sell.person_id
     where box_id = '".$caja."' and p_id = 1 and operation_type_id = 2 and f_id = 6 and invoice_code = '' and stock_to_id = $sucursal";
     $resultado_db = $mysqli->query($sql_db);
 
     $total_db = 0;

     while($row = $resultado_db->fetch_assoc()){
     // Se saca la forma de Pago.
     if($row['f_id'] == '6'){
      $forma_pago = "Dep. Bancario";
     }
 
     // Se saca si esta Habilitado o Cancelada la Remision
     if($row['p_id'] == '1'){
     $status_ticket = "Habilitado";
     }elseif($row['p_id'] == '2'){
     $status_ticket = "Cancelado";
     } 
     $pdf->ln(6);
     $pdf->cell(18, 7,$row['id'],1,0,'C');
     $pdf->cell(35, 7,$row['created_at'],1,0,'C');
     $pdf->cell(20, 7,$status_ticket,1,0,'C');
     $pdf->cell(30, 7,$forma_pago,1,0,'C');
     $pdf->cell(20, 7,$row['total'],1,0,'C');
     $pdf->cell(30, 7,$row['usuario'],1,0,'C'); 
     $pdf->Cell(123, 7,$row['name'],1,'C');

     $total_db = $total_db + $row['total'];
} 

    $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->ln(10);
    $pdf->SetX(220);
    $pdf->cell(48, 7,"Total Deposito Bancario: $ ".number_format($total_db,2,'.',','),0,0,'C');
    $pdf->ln(5);
    
    
    // SE SACA EL REPORTE DE VENTAS DE MOSTRADOR COBRADAS CON DEPOSITO BANCARIO.

     $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
     $pdf->Cell(300,14,"Listado - Ventas De Mostrador - Pago Con Cheque: ");
     $pdf->ln(10);
     $pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
     $y = $pdf->GetY(); 
     $pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
     $pdf->SetFont('arial', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
     $y = $pdf->GetY() + 1;
     $pdf->SetXY(10, $y);
     $pdf->Cell(18, 4, utf8_decode("Nº Remision"), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
     $pdf->SetXY(28, $y); //El resultado 22 es la suma de la posición 10 y el tamaño del Cell de 12.
     $pdf->Cell(35, 4, utf8_decode("Fecha / Hora Remision"), 1, 'C');
     $pdf->SetXY(63, $y);
     $pdf->Cell(20, 4, utf8_decode("Estado"), 1, 'C');
     $pdf->SetXY(83, $y);
     $pdf->Cell(30, 4, utf8_decode("Forma Pago"), 1, 'C');   
     $pdf->SetXY(113, $y);
     $pdf->Cell(20, 4, utf8_decode("Total"), 1, 'C');
     $pdf->SetXY(133, $y);
     $pdf->Cell(30, 4, utf8_decode("Usuario"), 1, 'C'); 
     $pdf->SetXY(163, $y);
     $pdf->Cell(123, 4, utf8_decode("Nombre Cliente"), 1, 'C'); 
    
     $sql_pc = "SELECT sell.id,sell.created_at,sell.p_id,sell.f_id,sell.total,sell.user_id,sell.person_id,sell.box_id,user.name AS usuario,person.name from sell 
     INNER JOIN user on 
     user.id = sell.user_id 
     INNER JOIN person on 
     person.id = sell.person_id
     where box_id = '".$caja."' and p_id = 1 and operation_type_id = 2 and f_id = 7 and invoice_code = '' and stock_to_id = $sucursal";
     $resultado_pc = $mysqli->query($sql_pc);
 
     $total_pc = 0;

     while($row = $resultado_pc->fetch_assoc()){
     // Se saca la forma de Pago.
       if($row['f_id'] == '7'){
      $forma_pago = "Pago Con Cheque";
     }
 
 
     // Se saca si esta Habilitado o Cancelada la Remision
     if($row['p_id'] == '1'){
     $status_ticket = "Habilitado";
     }elseif($row['p_id'] == '2'){
     $status_ticket = "Cancelado";
     } 
     $pdf->ln(6);
     $pdf->cell(18, 7,$row['id'],1,0,'C');
     $pdf->cell(35, 7,$row['created_at'],1,0,'C');
     $pdf->cell(20, 7,$status_ticket,1,0,'C');
     $pdf->cell(30, 7,$forma_pago,1,0,'C');
     $pdf->cell(20, 7,$row['total'],1,0,'C');
     $pdf->cell(30, 7,$row['usuario'],1,0,'C'); 
     $pdf->Cell(123, 7,$row['name'],1,'C');

     $total_pc = $total_pc + $row['total'];
} 

    $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->ln(10);
    $pdf->SetX(220);
    $pdf->cell(48, 7,"Total Pago Con Cheque : $ ".number_format($total_pc,2,'.',','),0,0,'C');
    $pdf->ln(5);
    
    
    // *************************************************************************************************************
    // INCIA EL REPORTE DE FACTURAS GENERAL. 
 
    $pdf->SetFont('helvetica', 'B', 12); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->Cell(400,20,"REPORTE DE FACTURAS DEL DIA");
    $pdf->ln(10);

    $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->Cell(10,14,"Listado - Facturas Digitales");
    $pdf->ln(10);
    $pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
    $y = $pdf->GetY(); 
    $pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
    $pdf->SetFont('arial', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
    $y = $pdf->GetY() + 1;
    $pdf->SetXY(10, $y);
    $pdf->Cell(5, 4, utf8_decode("S"), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
    $pdf->SetXY(15, $y); //El resultado 22 es la suma de la posición 10 y el tamaño del Cell de 12.
    $pdf->Cell(10, 4, utf8_decode("F"), 1, 'C');;
    $pdf->SetXY(25, $y);
    $pdf->Cell(25, 4, utf8_decode("Rfc Receptor"), 1, 'C');   
    $pdf->SetXY(50, $y);
    $pdf->Cell(30, 4, utf8_decode("Fecha Factura"), 1, 'C');
    $pdf->SetXY(80, $y);
    $pdf->Cell(12, 4, utf8_decode("Ticket"), 1, 'C'); 
     $pdf->SetXY(92, $y);
    $pdf->Cell(18, 4, utf8_decode("F. Pago"), 1, 'C');  
    $pdf->SetXY(110, $y);
    $pdf->Cell(16, 4, utf8_decode("Total"), 1, 'C'); 
    $pdf->SetXY(126, $y);
     $pdf->Cell(160, 4, utf8_decode("Nombre Cliente - Facturado"), 1, 'C'); 
 
    $sql_ft = "SELECT idcfdis, serie,folio,rfc_cliente,fecha_registro,Folio_venta,mpago,CONCAT(nombre_cliente,' ',apellido_cliente) 
    AS cliente,subtotal,Monto,timbrado, sell.reg_porpagar,sell.pendiente from cfdis 
    INNER JOIN sell on sell.id = cfdis.Folio_venta
    where cfdis.fecha_registro like '%$fecha%' and cfdis.timbrado = '1' and cfdis.tipo_factura = 1 and cfdis.stock_id = 1 and cfdis.id_deposito = 0
    and sell.pendiente in (0,1) and sell.r_credito in (0,4)";
    $resultado_ft = $mysqli->query($sql_ft);
   
    // SE GENERA LA SUMA DE SOLAMENTE EFECTIVO DE ANTICIPOS DE CLIENTES.
    $sql_factura_efectivo = "SELECT SUM(monto) as total FROM cfdis 
    inner join sell on sell.id = cfdis.Folio_venta 
    where cfdis.mpago = 1 and fecha_registro like '%$fecha%' and tipo_factura = 1 and stock_id = $sucursal and timbrado = 1 
    and id_deposito = 0 and sell.p_id = 1 and sell.r_credito in (0,4)";
    $resultado_factura = $mysqli->query($sql_factura_efectivo);
    $row10 = $resultado_factura -> fetch_assoc();

    
    $mpago = 0;
    while($row = $resultado_ft->fetch_assoc()){
        // Se saca si esta Habilitado o Cancelada la Remision
      if($row['mpago'] == '1'){
      $mpago = "Efectivo";
      }elseif($row['mpago'] == '2'){
      $mpago = "Cheque";
      }elseif($row['mpago'] == '3'){
      $mpago = "Transf. Elec";
      }elseif($row['mpago'] == '4'){
        $mpago = "T. Credito"; 
      }elseif($row['mpago'] == '5'){
        $mpago = "Monedero electrónico"; 
      }elseif($row['mpago'] == '6'){
        $mpago = "Dinero electrónico"; 
      }elseif($row['mpago'] == '8'){
        $mpago = "Vales de despensa"; 
      }elseif($row['mpago'] == '12'){
        $mpago = "Dación en pago"; 
      }elseif($row['mpago'] == '13'){
        $mpago = "Pago por subrogación"; 
      }elseif($row['mpago'] == '14'){
        $mpago = "Pago por consignación"; 
      }elseif($row['mpago'] == '15'){
        $mpago = "Condonación"; 
      }elseif($row['mpago'] == '17'){
        $mpago = "Compensación"; 
      }elseif($row['mpago'] == '23'){
        $mpago = "Novacion"; 
      }elseif($row['mpago'] == '24'){
        $mpago = "Confusion"; 
      }elseif($row['mpago'] == '25'){
        $mpago = "Remision de deuda"; 
      }elseif($row['mpago'] == '26'){
        $mpago = "prescripcion o caducidad"; 
      }elseif($row['mpago'] == '27'){
        $mpago = "A satisfaccion del acreedor"; 
      }elseif($row['mpago'] == '28'){
        $mpago = "T. De debito"; 
      }elseif($row['mpago'] == '29'){
        $mpago = "Tarjeta de servicios"; 
      }elseif($row['mpago'] == '30'){
        $mpago = "Aplicacion de anticipos"; 
      }elseif($row['mpago'] == '31'){
        $mpago = "Intermediario Pagos"; 
      }elseif($row['mpago'] == '99'){
        $mpago = "Por Definir"; 
      }
      $pdf->ln(6);
    $pdf->SetFont('helvetica', '', 8); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->cell(5, 7,$row['serie'],1,0,'C');
    $pdf->cell(10, 7,$row['folio'],1,0,'C');
    $pdf->cell(25, 7,$row['rfc_cliente'],1,0,'C');
    $pdf->cell(30, 7,$row['fecha_registro'],1,0,'C');
    $pdf->cell(12, 7,$row['Folio_venta'],1,0,'C');
    $pdf->cell(18, 7,$mpago,1,0,'C');
    $pdf->cell(16, 7,number_format($row['Monto'],2,'.',','),1,0,'C');
    $pdf->SetFont('helvetica', 'B', 7); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->Cell(160, 7,$row['cliente'],1,'C');

  } 
    $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->ln(10);
    $pdf->SetX(180);
    // SE IMPRIME EL TOTAL DE LA VENTA SOLAMENTE EN EFETIVO CON EL ROW1
    $pdf->cell(48, 7,"Total Efectivo Facturado : $ ".number_format($row10['total'],2,'.',','),0,0,'C');
    $pdf->ln(5);
    
  
    // SE SACA EL REPORTE DE FACTURAS ESPECIALES.
    $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->Cell(10,14,"Listado - Facturas Especiales");
    $pdf->ln(10);
    $pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
    $y = $pdf->GetY(); 
    $pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
    $pdf->SetFont('arial', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
    $y = $pdf->GetY() + 1;
    $pdf->SetXY(10, $y);
    $pdf->Cell(5, 4, utf8_decode("S"), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
    $pdf->SetXY(15, $y); //El resultado 22 es la suma de la posición 10 y el tamaño del Cell de 12.
    $pdf->Cell(10, 4, utf8_decode("F"), 1, 'C');
    $pdf->SetXY(25, $y);
    $pdf->Cell(25, 4, utf8_decode("Rfc Receptor"), 1, 'C');   
    $pdf->SetXY(50, $y);
    $pdf->Cell(35, 4, utf8_decode("Fecha Factura"), 1, 'C');
    $pdf->SetXY(85, $y);
    $pdf->Cell(20, 4, utf8_decode("Estado"), 1, 'C');  
    $pdf->SetXY(105, $y);
    $pdf->Cell(18, 4, utf8_decode("Total"), 1, 'C'); 
    $pdf->SetXY(123, $y);
    $pdf->Cell(163, 4, utf8_decode("Nombre Cliente - Facturado"), 1, 'C'); 


     $sql_fe = "SELECT idcfdis, serie,folio,rfc_cliente,fecha_registro,CONCAT(nombre_cliente,' ',apellido_cliente) AS cliente,Monto,timbrado from cfdis "
    . "where fecha_registro like '%$fecha%' and timbrado = '1' and tipo_factura = 2 and stock_id = $sucursal";
     $resultado_fe = $mysqli->query($sql_fe);
 
    $total_fe = 0;
    while($row = $resultado_fe->fetch_assoc()){
    // Se saca si esta Habilitado o Cancelada la Remision
    if($row['timbrado'] == '1'){
    $status_factura_e = "Habilitado";
    }elseif($row['timbrado'] == '3'){
    $status_factura_e = "Cancelada";
    } 

     $pdf->ln(6);          
    $pdf->SetFont('helvetica', '', 8); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->cell(5, 7,$row['serie'],1,0,'C');
    $pdf->cell(10, 7,$row['folio'],1,0,'C');
    $pdf->cell(25, 7,$row['rfc_cliente'],1,0,'C');
    $pdf->cell(35, 7,$row['fecha_registro'],1,0,'C');
    $pdf->cell(20, 7,$status_factura_e,1,0,'C');
    $pdf->cell(18, 7,$row['Monto'],1,0,'C');
    $pdf->SetFont('helvetica', 'B', 7); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->Cell(163, 7,$row['cliente'],1,'C');

    $total_fe = $total_fe + $row['Monto'];
} 

     $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
     $pdf->ln(10);
     $pdf->SetX(180);
     $pdf->cell(48, 7,"Total Facturas Especiales : $ ".number_format($total_fe,2,'.',','),0,0,'C');
     $pdf->ln(5);
    



    // SE SACA EL REPORTE DE FACTURAS CANCELADAS.
    $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->Cell(10,14,"Listado - Facturas Canceladas");
    $pdf->ln(10);
    $pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
    $y = $pdf->GetY(); 
    $pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
    $pdf->SetFont('arial', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
    $y = $pdf->GetY() + 1;
    $pdf->SetXY(10, $y);
    $pdf->Cell(5, 4, utf8_decode("S"), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
    $pdf->SetXY(15, $y); //El resultado 22 es la suma de la posición 10 y el tamaño del Cell de 12.
    $pdf->Cell(10, 4, utf8_decode("F"), 1, 'C');
    $pdf->SetXY(25, $y);
    $pdf->Cell(25, 4, utf8_decode("Rfc Receptor"), 1, 'C');   
    $pdf->SetXY(50, $y);
    $pdf->Cell(35, 4, utf8_decode("Fecha Factura"), 1, 'C');

    $pdf->SetXY(85, $y);
    $pdf->Cell(35, 4, utf8_decode("Fecha Cancelacion"), 1, 'C');

    $pdf->SetXY(120, $y);
    $pdf->Cell(18, 4, utf8_decode("Total"), 1, 'C'); 
    $pdf->SetXY(138, $y);
    $pdf->Cell(148, 4, utf8_decode("Nombre Cliente"), 1, 'C'); 
    
     // SE UTILIZAR PARA SACAR LAS FACTURAS CANCELADAS DEL DIA ANTERIOR
       $fecha_factura = date("Y-m-d",strtotime($fecha." - 1 days")); 
       $sql_fc = "SELECT idcfdis, serie,folio,rfc_cliente,fecha_registro,fecha_cancelacion,CONCAT(nombre_cliente,' ',apellido_cliente) AS cliente,Monto,timbrado from cfdis "
      . "where fecha_registro like '%$fecha_factura%' and timbrado = '3' and stock_id = $sucursal";
       $resultado_fc = $mysqli->query($sql_fc);
       $total_fc = 0;

    while($row = $resultado_fc->fetch_assoc()){
      $pdf->ln(6);
    // Se saca si esta Habilitado o Cancelada la Remision
    $pdf->cell(5, 7,$row['serie'],1,0,'C');
    $pdf->cell(10, 7,$row['folio'],1,0,'C');
    $pdf->cell(25, 7,$row['rfc_cliente'],1,0,'C');
    $pdf->cell(35, 7,$row['fecha_registro'],1,0,'C');
    $pdf->cell(35, 7,$row['fecha_cancelacion'],1,0,'C');
    $pdf->cell(18, 7,"$ ".$row['Monto'],1,0,'C');
    $pdf->SetFont('helvetica', 'B', 7); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->Cell(148, 7,$row['cliente'],1,'C');

    $total_fc = $total_fc + $row['Monto'];
} 

  $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
  $pdf->ln(10);
  $pdf->SetX(180);
  $pdf->cell(48, 7,"Total Facturas Canceladas : $ ".number_format($total_fc,2,'.',','),0,0,'C');
  $pdf->ln(5);
   


  // ------------------------------------------------------------------------------------------------------------------- //  

  // SE SACA EL REPORTE DE ABONOS DE CLIENTES DEL DÍA. 
  $pdf->SetFont('helvetica', 'B', 15); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
  $pdf->Cell(400,20,"REPORTE ANTICIPOS / ABONOS CLIENTES");
  $pdf->ln(10);

$pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->Cell(10,14,"Listado - Anticipos A Cuenta");
$pdf->ln(10);
$pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
$y = $pdf->GetY(); 
$pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->SetFont('arial', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
$y = $pdf->GetY() + 1;
$pdf->SetXY(10, $y);
$pdf->Cell(20, 4, utf8_decode("ID"), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
$pdf->SetXY(30, $y);
$pdf->Cell(25, 4, utf8_decode("Cantidad"), 1, 'C');   
$pdf->SetXY(55, $y);
$pdf->Cell(40, 4, utf8_decode("Forma De Pago"), 1, 'C');
$pdf->SetXY(95, $y);
$pdf->Cell(35, 4, utf8_decode("Fecha Registro"), 1, 'C');
$pdf->SetXY(130, $y);
$pdf->Cell(35, 4, utf8_decode("Tipo"), 1, 'C');
$pdf->SetXY(165, $y); //El resultado 22 es la suma de la posición 10 y el tamaño del Cell de 12.
$pdf->Cell(122, 4, utf8_decode("Nombre Cliente"), 1, 'C');  

//SQL PARA SACAR EL REPORTE DE ABONOS POR DÍA.
$sql_ac = "SELECT CONCAT(person.name,' ',person.lastname) AS cliente,bitacora_abonos.idabonos,bitacora_abonos.cantidad,bitacora_abonos.forma_pago,bitacora_abonos.fecha,bitacora_abonos.cant_ingresada,bitacora_abonos.operacion from bitacora_abonos
INNER JOIN person ON person.id = bitacora_abonos.idcliente where bitacora_abonos.fecha like '%$fecha%' and bitacora_abonos.operacion in (1,2)";
$resultado_ac = $mysqli->query($sql_ac);

// SE GENERA LA SUMA DE SOLAMENTE EFECTIVO DE ANTICIPOS DE CLIENTES.
$sql_ac_suma = "SELECT SUM(cant_ingresada) as total FROM bitacora_abonos where forma_pago = 1 and fecha like '%$fecha%' ";
$resultado_ac_total = $mysqli->query($sql_ac_suma);
$row1 = $resultado_ac_total -> fetch_assoc();

$pago_anticipo = 0;
$tipo =0;
while($row = $resultado_ac->fetch_assoc()){
    
// Se saca si esta Habilitado o Cancelada la Remision
      

          // Se saca si esta Habilitado o Cancelada la Remision
          if($row['forma_pago'] == '01'){
            $pago_anticipo = "Efectivo";
            }elseif($row['forma_pago'] == '02'){
            $pago_anticipo = "Cheque";
            }elseif($row['forma_pago'] == '03'){
            $pago_anticipo = "Transf. Elec";
            }elseif($row['forma_pago'] == '04'){
              $pago_anticipo = "T. Credito"; 
            }elseif($row['forma_pago'] == '05'){
              $pago_anticipo = "Monedero electrónico"; 
            }elseif($row['forma_pago'] == '06'){
              $pago_anticipo = "Dinero electrónico"; 
            }elseif($row['forma_pago'] == '8'){
              $pago_anticipo = "Vales de despensa"; 
            }elseif($row['forma_pago'] == '12'){
              $pago_anticipo = "Dación en pago"; 
            }elseif($row['forma_pago'] == '13'){
              $pago_anticipo = "Pago por subrogación"; 
            }elseif($row['forma_pago'] == '14'){
              $pago_anticipo = "Pago por consignación"; 
            }elseif($row['forma_pago'] == '15'){
              $pago_anticipo = "Condonación"; 
            }elseif($row['forma_pago'] == '17'){
              $pago_anticipo = "Compensación"; 
            }elseif($row['forma_pago'] == '23'){
              $pago_anticipo = "Novacion"; 
            }elseif($row['forma_pago'] == '24'){
              $pago_anticipo = "Confusion"; 
            }elseif($row['forma_pago'] == '25'){
              $pago_anticipo = "Remision de deuda"; 
            }elseif($row['forma_pago'] == '26'){
              $pago_anticipo = "prescripcion o caducidad"; 
            }elseif($row['forma_pago'] == '27'){
              $pago_anticipo = "A satisfaccion del acreedor"; 
            }elseif($row['forma_pago'] == '28'){
              $pago_anticipo = "T. De debito"; 
            }elseif($row['forma_pago'] == '29'){
              $pago_anticipo = "Tarjeta de servicios"; 
            }elseif($row['forma_pago'] == '30'){
              $pago_anticipo = "Aplicacion de anticipos"; 
            }elseif($row['forma_pago'] == '31'){
              $pago_anticipo = "Intermediario Pagos"; 
            }elseif($row['forma_pago'] == '99'){
              $pago_anticipo = "Por Definir"; 
  }


    if($row['operacion'] == '1'){
    $tipo = "Anticipo";
    }elseif($row['operacion'] == '2'){
    $tipo = "Abono"; 
    }
    $pdf->ln(6);
    $pdf->cell(20, 7,$row['idabonos'],1,0,'C');
    $pdf->cell(25, 7,$row['cant_ingresada'],1,0,'C');
    $pdf->cell(40, 7,$pago_anticipo,1,0,'C');
    $pdf->Cell(35, 7,$row['fecha'],1,0,'C');
    $pdf->Cell(35, 7,$tipo,1,0,'C');
    $pdf->Cell(122, 7,$row['cliente'],1,'C');
    $pdf->cell(18, 7,$row['Monto'],1,0,'C');
    $pdf->Cell(78, 7,$row['nombre_cliente'],1,'C');
    $total_fr = $total_fr + $row['Monto']; 
} 
    $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->ln(10);
    $pdf->SetX(205);
    $pdf->cell(48, 7,"Total Abonos / Anticipos Efectivo : $ ".number_format($row1['total'],2,'.',','),0,0,'C');
    // SE IMPRIME EL TOTAL DE LA VENTA SOLAMENTE EN EFETIVO CON EL ROW1
    $pdf->ln(5);
    
  // ------------------------------------------------------------------------------------------------------------------- //  
       
    //SE SACA EL REPORTE DE SALDOS PENDIENTES DE CLIENTES. 
    $pdf->SetFont('helvetica', 'B', 15); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->Cell(400,20,"REPORTE SALDOS PENDIENTES - (REMISIONES POR COBRAR)");
    $pdf->ln(8);

    
  
    // SE SACA EL REPORTE DE SALDOS PENDIENTES DE CLIENTES. 
     
     $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
     $pdf->Cell(300,14,"Listado - SALDOS PENDIENTES AL DIA DE HOY ");
     $pdf->ln(10);
     $pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
     $y = $pdf->GetY(); 
     $pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
     $pdf->SetFont('arial', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
     $y = $pdf->GetY() + 1;
     $pdf->SetXY(10, $y);
     $pdf->Cell(18, 4, utf8_decode("Remision"), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
     
     $pdf->SetXY(28, $y); //El resultado 22 es la suma de la posición 10 y el tamaño del Cell de 12.
     $pdf->Cell(28, 4, utf8_decode("Fecha"), 1, 'C');

     $pdf->SetXY(56, $y);
     $pdf->Cell(20, 4, utf8_decode("Forma Pago"), 1, 'C');   
     $pdf->SetXY(76, $y);
     $pdf->Cell(20, 4, utf8_decode("Total"), 1, 'C');
     $pdf->SetXY(96, $y);
     $pdf->Cell(22, 4, utf8_decode("Anticipo"), 1, 'C'); 
     $pdf->SetXY(118, $y);
     $pdf->Cell(22, 4, utf8_decode("Pendiente"), 1, 'C'); 
     $pdf->SetXY(140, $y);
     $pdf->Cell(147, 4, utf8_decode("Nombre Cliente"), 1, 'C'); 
    
     $sql_sp = "SELECT sell.id,sell.created_at,sell.remision_recuperada,sell.p_pendiente,sell.fecha_pago,sell.p_id,d_id,sell.f_id,sell.total,sell.user_id,sell.person_id,sell.box_id,sell.total_por_pagar,sell.anticipo_venta,sell.reg_porpagar,sell.reg_anticipo,user.name AS usuario,CONCAT(person.name,' ',person.lastname) AS cliente from sell 
     INNER JOIN user on 
     user.id = sell.user_id 
     INNER JOIN person on 
     person.id = sell.person_id
     where  operation_type_id = 2  and stock_to_id = $sucursal and p_pendiente = 2 and p_id in (1,2) and pendiente = 1 and sell.created_at  <= '$fecha.23:59:00'";
     $resultado_sp = $mysqli->query($sql_sp);

     // SE GENERA LA SUMA DE SOLAMENTE EFECTIVO DE ANTICIPOS DE CLIENTES.
    $sql_sp_suma = "SELECT SUM(reg_porpagar) as total_sp FROM sell where p_id = 2 "
    . " and operation_type_id = 2   and stock_to_id = $sucursal ";
    $resultado_sp_total = $mysqli->query($sql_sp_suma);
    $row2 = $resultado_sp_total -> fetch_assoc();


    // SE GENERA LA SUMA DE SOLAMENTE EFECTIVO DE ANTICIPOS DE CLIENTES.
       $sql_sp_anticipo = "SELECT SUM(reg_anticipo) as total_anticipo FROM sell where f_id = 1 "
    . " and operation_type_id = 2 and p_id in (1,2) and stock_to_id = $sucursal and sell.created_at like '%$fecha%' ";
  
    $resultado_sp_anticipo = $mysqli->query($sql_sp_anticipo);
    $row3 = $resultado_sp_anticipo -> fetch_assoc();
    $venta_anticipo = 0;

  
     while($row = $resultado_sp->fetch_assoc()){
    $venta_anticipo = $row3['total_anticipo'];

    if($row['f_id'] == '1'){
    $pago_pendiente = "Efectivo";
    }elseif($row['f_id'] == '2'){
    $pago_pendiente = "Tra. Electronica";
    }elseif($row['f_id'] == '3'){
    $pago_pendiente = "T. De Credito";
    }elseif($row['f_id'] == '4'){
    $pago_pendiente = "T. De Debito";
    }elseif($row['f_id'] == '5'){
    $pago_pendiente = "Cobro Anticipo";
    }elseif($row['f_id'] == '6'){
    $pago_pendiente = "Dep. Bancario";
    }elseif($row['f_id'] == '7'){
    $pago_pendiente = "Pago con Cheque ";
    }elseif($row['f_id'] == '8'){
    $pago_pendiente = "Por Definir";}

    $pagado = $row['p_id'];
    $pagado2 = $row['remision_recuperada'];
    $pagado3 = $row['p_pendiente'];
    $fecha_pago = $row['fecha_pago'];
    $pdf->ln(6);

    if($fecha_pago <= $fecha."23:59:00" && $pagado == 2){

      $pdf->SetFont('helvetica', '', 8); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
      $pdf->cell(18, 7,"RP ".$row['id'],1,0,'C');
      $pdf->cell(28, 7,$row['created_at'],1,0,'C');
      $pdf->cell(20, 7,$pago_pendiente,1,0,'C');
      $pdf->cell(20, 7,"$ ".$row['total'],1,0,'C');
      $pdf->cell(22, 7,"$ ".$row['reg_anticipo'],1,0,'C'); 
      $pdf->cell(22, 7,"$ ".$row['reg_porpagar'],1,0,'C'); 
      $pdf->SetFont('helvetica', 'B', 7); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
      $pdf->Cell(147, 7,$row['cliente'],1,'C'); 
  
  
    }else if ($fecha_pago >= $fecha."23:59:00" && $pagado3 == 2 ){

      $pdf->SetFont('helvetica', '', 8); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
      $pdf->cell(18, 7,"RP ".$row['id'],1,0,'C');
      $pdf->cell(28, 7,$row['created_at'],1,0,'C');
      $pdf->cell(20, 7,$pago_pendiente,1,0,'C');
      $pdf->cell(20, 7,"$ ".$row['total'],1,0,'C');
      $pdf->cell(22, 7,"$ ".$row['reg_anticipo'],1,0,'C'); 
      $pdf->cell(22, 7,"$ ".$row['reg_porpagar'],1,0,'C'); 
      $pdf->SetFont('helvetica', 'B', 7); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
      $pdf->Cell(147, 7,$row['cliente'],1,'C'); 
      }

   
}
    $pdf->ln(1);
    $pdf->SetFont('helvetica', 'B', 7); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->ln(10);
    $pdf->SetX(30);
    // SE IMPRIME EL TOTAL DE LA VENTA SOLAMENTE EN EFETIVO CON EL ROW1
    /*
    $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->cell(48, 7,"Por Recuperar: $ ".$row2['total_sp'],1,0,'C');
    */
    
    $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->SetX(185);
    // SE IMPRIME EL TOTAL DE LA VENTA SOLAMENTE EN EFETIVO CON EL ROW1
 
    $pdf->cell(48, 7,"Efectivo Anticipado En Caja : $ ".number_format($row3['total_anticipo'],2,'.',','),0,0,'C');

    $pdf->ln(5);

      
 
    // ------------------------------------------------------------------------------------------------------------------- //  

    //SE SACA EL REPORTE DE SALDOS RECUPERADOS DE CLIENTES. 
    $pdf->SetFont('helvetica', 'B', 15); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->Cell(400,20,"REPORTE SALDOS RECUPERADOS - (REMISIONES RECUPERADAS)");
    $pdf->ln(10);
  
    // SE SACA EL REPORTE DE SALDOS RECUPERADOS DE CLIENTES. 
     
     $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
     $pdf->Cell(300,14,"Listado - Saldos Recuperados ");
     $pdf->ln(10);
     $pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
     $y = $pdf->GetY(); 
     $pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
     $pdf->SetFont('arial', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
     $y = $pdf->GetY() + 1;
     $pdf->SetXY(10, $y);
     $pdf->Cell(18, 4, utf8_decode("Remision"), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
     $pdf->SetXY(28, $y); //El resultado 22 es la suma de la posición 10 y el tamaño del Cell de 12.
     $pdf->Cell(35, 4, utf8_decode("Fecha Remision"), 1, 'C');
     $pdf->SetXY(63, $y); //El resultado 22 es la suma de la posición 10 y el tamaño del Cell de 12.
     $pdf->Cell(35, 4, utf8_decode("Fecha Pago"), 1, 'C');
     $pdf->SetXY(98, $y);
     $pdf->Cell(30, 4, utf8_decode("Forma Pago"), 1, 'C');   
     $pdf->SetXY(128, $y);
     $pdf->Cell(17, 4, utf8_decode("Total"), 1, 'C');
  
     $pdf->SetXY(145, $y);
     $pdf->Cell(142, 4, utf8_decode("Nombre Cliente"), 1, 'C'); 
    
     $sql_sr = "SELECT sell.id,sell.created_at,sell.p_id,d_id,sell.f_id,sell.total,sell.user_id,sell.person_id,sell.total_por_pagar,sell.reg_porpagar,sell.fecha_pago,sell.fecha_remision,sell.box_id,user.name AS usuario,CONCAT(person.name,' ',person.lastname) AS cliente from sell 
     INNER JOIN user on 
     user.id = sell.user_id 
     INNER JOIN person on 
     person.id = sell.person_id
     where p_id = 1 and operation_type_id = 2   and remision_recuperada = '1' and stock_to_id = $sucursal and sell.fecha_pago  like '%$fecha%'";
     $resultado_sr = $mysqli->query($sql_sr);
    
     // SE GENERA LA SUMA DE SOLAMENTE EFECTIVO DE ANTICIPOS DE CLIENTES.
    $sql_sr_suma = "SELECT SUM(reg_porpagar) as total_sr FROM sell where f_id = 1 and p_id = 1 "
    . " and operation_type_id = 2 and remision_recuperada = '1' and stock_to_id = $sucursal and sell.fecha_pago  like '%$fecha%'";
    $resultado_sr_total = $mysqli->query($sql_sr_suma);
    $row3 = $resultado_sr_total -> fetch_assoc();

    $fechaActual = date('Y-m-d');
    $total_recuperado=0; 

    $pdf->ln(6);
     while($row = $resultado_sr->fetch_assoc()){
     // Se saca la forma de Pago.
     // Se saca si esta Habilitado o Cancelada la Remision
   
    if($row['f_id'] == '1'){
      $pago_recuperado = "Efectivo";
      }elseif($row['f_id'] == '2'){
      $pago_recuperado = "Tra. Electronica";
      }elseif($row['f_id'] == '3'){
      $pago_recuperado = "T. De Credito";
      }elseif($row['f_id'] == '4'){
      $pago_recuperado = "T. De Debito";
      }elseif($row['f_id'] == '5'){
      $pago_recuperado = "Cobro Anticipo";
      }elseif($row['f_id'] == '6'){
      $pago_recuperado = "Dep. Bancario";
      }elseif($row['f_id'] == '7'){
      $pago_recuperado = "Pago con Cheque ";
      }elseif($row['f_id'] == '8'){
      $pago_recuperado = "Por Definir ";}
  
     $recuperado = 0;
     $fecha_remision = $row["fecha_remision"];

    if($fechaActual > $fecha_remision )
	    {
      $recuperado = $row['reg_porpagar'];
      }else if($fechaActual = $fecha_remision){
      $recuperado = $row['total'];
      } else {
      echo "no entre a nada";
      }
      $total_recuperado += $recuperado;

     $pdf->SetFont('helvetica', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
     $pdf->cell(18, 7,"SR ".$row['id'],1,0,'C');
     $pdf->cell(35, 7,$row['fecha_remision'],1,0,'C');
     $pdf->cell(35, 7,$row['fecha_pago'],1,0,'C');
     $pdf->cell(30, 7,$pago_recuperado,1,0,'C');
     $pdf->cell(17, 7,$recuperado,1,0,'C');
     $pdf->SetFont('helvetica', 'B', 7); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
     $pdf->Cell(142, 7,$row['cliente'],1,'C'); 
}
     $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
     $pdf->ln(10);
     $pdf->SetX(185);
     $pdf->cell(48, 7,"Total Recuperado Efectivo : $ ".number_format($row3['total_sr'],2,'.',','),0,0,'C');
     $pdf->ln(5);
    
    
    // ------------------------------------------------------------------------------------------------------------------- //  

    //SE SACA EL REPORTE DE CREDITOS DEL DÍA. 
    $pdf->SetFont('helvetica', 'B', 15); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->Cell(400,20,"REPORTE DE CREDITOS");
    $pdf->ln(8);


    //SE SACA EL REPORTE DE CREDITOS DEL DÍA.    
     $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
     $pdf->Cell(300,14,"Listado - Creditos Liquidados Del Dia ");
     $pdf->ln(10);
     $pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
     $y = $pdf->GetY(); 
     $pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
     $pdf->SetFont('arial', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
     $y = $pdf->GetY() + 1;
     $pdf->SetXY(10, $y);
     $pdf->Cell(18, 4, utf8_decode("Nº Remision"), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
     $pdf->SetXY(28, $y); //El resultado 22 es la suma de la posición 10 y el tamaño del Cell de 12.
     $pdf->Cell(35, 4, utf8_decode("Fecha Liquidacion"), 1, 'C');
  
     $pdf->SetXY(63, $y);
     $pdf->Cell(30, 4, utf8_decode("Forma Pago"), 1, 'C');   
     $pdf->SetXY(93, $y);
     $pdf->Cell(20, 4, utf8_decode("Total"), 1, 'C');

     $pdf->SetXY(113, $y);
     $pdf->Cell(174, 4, utf8_decode("Nombre Cliente"), 1, 'C'); 
    
     $sql_cre = "SELECT sell.id,sell.created_at,sell.p_id,d_id,sell.f_id,sell.total,sell.user_id,sell.person_id,sell.box_id,sell.fecha_pago,user.name AS usuario,CONCAT(person.name,' ',person.lastname) AS cliente from sell 
     INNER JOIN user on 
     user.id = sell.user_id 
     INNER JOIN person on 
     person.id = sell.person_id
     where  sell.fecha_pago like '%$fecha%' and stock_to_id = $sucursal and credito_liquidado = 1";
     $resultado_cre_li = $mysqli->query($sql_cre);
    
     // SE GENERA LA SUMA DE SOLAMENTE EFECTIVO DE ANTICIPOS DE CLIENTES.
    $sql_cre_liquidado = "SELECT SUM(total) as total_crl FROM sell where  sell.fecha_pago like '%$fecha%' and stock_to_id = $sucursal and credito_liquidado = 1 ";
  
    $resultado_cre_liquidado = $mysqli->query($sql_cre_liquidado);
    $row4 = $resultado_cre_liquidado -> fetch_assoc();
 
     while($row = $resultado_cre_li->fetch_assoc()){
     // Se saca la forma de Pago.
     // Se saca si esta Habilitado o Cancelada la Remision
    if($row['f_id'] == '1'){
    $pago_cre = "Efectivo";
    }elseif($row['f_id'] == '2'){
    $pago_cre = "Transf. Electronica";
    }elseif($row['f_id'] == '3'){
    $pago_cre = "Tarjeta De Credito";
    }elseif($row['f_id'] == '4'){
    $pago_cre = "Tarjeta De Debito";
    }elseif($row['f_id'] == '5'){
    $pago_cre = "Cobro con Abono";
    }elseif($row['f_id'] == '6'){
    $pago_cre = "Pago Con Deposito";
    }elseif($row['f_id'] == '7'){
    $pago_cre = "Pago Con Cheque";
    }elseif($row['f_id'] == '8'){
    $pago_cre = "Por Definir";
      }

      $pdf->ln(6);
     $pdf->SetFont('helvetica', '', 8); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
     $pdf->cell(18, 7,"RC - ".$row['id'],1,0,'C');
     $pdf->cell(35, 7,$row['fecha_pago'],1,0,'C');
     $pdf->cell(30, 7,$pago_cre,1,0,'C');
     $pdf->cell(20, 7,$row['total'],1,0,'C');
     $pdf->SetFont('helvetica', 'B', 7); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
     $pdf->Cell(174, 7,$row['cliente'],1,'C'); 
}
    $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->ln(10);
    $pdf->SetX(185);
    // SE IMPRIME EL TOTAL DE LA VENTA SOLAMENTE EN EFETIVO CON EL ROW3
    $pdf->cell(48, 7,"Total Creditos : $ ".$row4['total_crl'],0,0,'C');
    $pdf->ln(6);
  
    //SE SACA EL REPORTE DE CREDITOS DEL DÍA.    
     $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
     $pdf->Cell(300,14,"Listado Creditos Autorizados Del Dia");
     $pdf->ln(10);
     $pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
     $y = $pdf->GetY(); 
     $pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
     $pdf->SetFont('arial', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
     $y = $pdf->GetY() + 1;
     $pdf->SetXY(10, $y);
     $pdf->Cell(18, 4, utf8_decode("Nº Remision"), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
     $pdf->SetXY(28, $y); //El resultado 22 es la suma de la posición 10 y el tamaño del Cell de 12.
     $pdf->Cell(35, 4, utf8_decode("Fecha / Hora Remision"), 1, 'C');
  
     $pdf->SetXY(63, $y);
     $pdf->Cell(30, 4, utf8_decode("Forma Pago"), 1, 'C');   
     $pdf->SetXY(93, $y);
     $pdf->Cell(20, 4, utf8_decode("Total"), 1, 'C');

     $pdf->SetXY(113, $y);
     $pdf->Cell(174, 4, utf8_decode("Nombre Cliente"), 1, 'C'); 
    
     $sql_cre = "SELECT sell.id,sell.created_at,sell.p_id,d_id,sell.f_id,sell.total,sell.user_id,sell.person_id,sell.box_id,user.name AS usuario,CONCAT(person.name,' ',person.lastname) AS cliente from sell 
     INNER JOIN user on 
     user.id = sell.user_id 
     INNER JOIN person on 
     person.id = sell.person_id
     where operation_type_id = 2  and sell.created_at like '%$fecha%' and stock_to_id = $sucursal and r_credito = '4' and sell.facturado in (0,1)";
     $resultado_cre = $mysqli->query($sql_cre);
    
     // SE GENERA LA SUMA DE SOLAMENTE EFECTIVO DE ANTICIPOS DE CLIENTES.
    $sql_cre_suma = "SELECT SUM(total) as total_cre FROM sell where f_id = 1 "
    . " and operation_type_id = 2  and invoice_code = '' and sell.created_at like '%$fecha%' and stock_to_id = $sucursal and r_credito = '4' ";
    $resultado_cre_total = $mysqli->query($sql_cre_suma);
    $row4 = $resultado_cre_total -> fetch_assoc();

    
     while($row = $resultado_cre->fetch_assoc()){
     // Se saca la forma de Pago.
     // Se saca si esta Habilitado o Cancelada la Remision
    if($row['f_id'] == '1'){
    $pago_cre = "Efectivo";
    }elseif($row['f_id'] == '2'){
    $pago_cre = "Transf. Electronica";
    }elseif($row['f_id'] == '3'){
    $pago_cre = "Tarjeta De Credito";
    }elseif($row['f_id'] == '4'){
    $pago_cre = "Tarjeta De Debito";
    }elseif($row['f_id'] == '5'){
    $pago_cre = "Dep. Bancario";
    }elseif($row['f_id'] == '6'){
    $pago_cre = "Pago Con Deposito";
    }elseif($row['f_id'] == '7'){
    $pago_cre = "Pago Con Cheque";
    }elseif($row['f_id'] == '8'){
    $pago_cre = "Por Definir";
      }

     $pdf->ln(6);
     $pdf->SetFont('helvetica', '', 8); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
     $pdf->cell(18, 7,"RC - ".$row['id'],1,0,'C');
     $pdf->cell(35, 7,$row['created_at'],1,0,'C');
     $pdf->cell(30, 7,$pago_cre,1,0,'C');
     $pdf->cell(20, 7,$row['total'],1,0,'C');
     $pdf->SetFont('helvetica', 'B', 7); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
     $pdf->Cell(174, 7,$row['cliente'],1,'C'); 
}
    $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->ln(2);
    $pdf->SetX(185);
    // SE IMPRIME EL TOTAL DE LA VENTA SOLAMENTE EN EFETIVO CON EL ROW3
  
    $pdf->ln(2);

  //SE SACA EL REPORTE DE CREDITOS DEL DÍA.    
  $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
  $pdf->Cell(300,14,"Listado Abonos A Creditos - Clientes");
  $pdf->ln(10);
  $pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
  $y = $pdf->GetY(); 
  $pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
  $pdf->SetFont('arial', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
  $y = $pdf->GetY() + 1;
  $pdf->SetXY(10, $y);
  $pdf->Cell(18, 4, utf8_decode("ID Abono"), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
  $pdf->SetXY(28, $y); //El resultado 22 es la suma de la posición 10 y el tamaño del Cell de 12.
  $pdf->Cell(20, 4, utf8_decode("N° Remision"), 1, 'C');
  $pdf->SetXY(48, $y);
  $pdf->Cell(20, 4, utf8_decode("Operacion"), 1, 'C');
  $pdf->SetXY(68, $y);
  $pdf->Cell(30, 4, utf8_decode("Forma Pago"), 1, 'C');   
  $pdf->SetXY(98, $y);
  $pdf->Cell(25, 4, utf8_decode("Fecha Abono"), 1, 'C');
  $pdf->SetXY(123, $y);
  $pdf->Cell(20, 4, utf8_decode("Monto"), 1, 'C');
  $pdf->SetXY(143, $y);
  $pdf->Cell(145, 4, utf8_decode("Nombre Cliente"), 1, 'C');  
  $sql_acreditos = "SELECT PAYMENT.ID, PAYMENT.PAYMENT_TYPE_ID, PAYMENT.SELL_ID, PAYMENT.PERSON_ID,PAYMENT.VAL AS abono,PAYMENT.FORMA_PAGO,PAYMENT.CREATED_AT
  ,PAYMENT.STOCK_ID,person.name FROM PAYMENT 
  inner join person on person.id = payment.person_id 
  where PAYMENT.CREATED_AT like '%$fecha%' AND PAYMENT.STOCK_ID = '$sucursal' AND PAYMENT.PAYMENT_TYPE_ID = 2";
  $resultado_acreditos = $mysqli->query($sql_acreditos);
 
  // SE GENERA LA SUMA DE SOLAMENTE EFECTIVO DE ANTICIPOS DE CLIENTES.
 $sql_sumaac = "SELECT SUM(val) as total_acredito from payment where payment_type_id = 2 and forma_pago = 1 
 and created_at like '$fecha' and stock_id = $sucursal";
 $resultado_sumaac = $mysqli->query($sql_sumaac);
 $row11 = $resultado_sumaac -> fetch_assoc(); 
 $pago_credito=0;

 
  while($row = $resultado_acreditos->fetch_assoc()){
  // Se saca la forma de Pago.
  // Se saca si esta Habilitado o Cancelada la Remision

  if($row['FORMA_PAGO'] == '1'){
 $pago_credito = "Efectivo";
 }elseif($row['FORMA_PAGO'] == '2'){
 $pago_credito = "Transf. Electronica";
 }elseif($row['FORMA_PAGO'] == '3'){
 $pago_credito = "Tarjeta De Credito";
 }elseif($row['FORMA_PAGO'] == '4'){
 $pago_credito = "Tarjeta De Debito";
 }elseif($row['FORMA_PAGO'] == '5'){
 $pago_credito = "Dep. Bancario";
 }elseif($row['FORMA_PAGO'] == '6'){
 $pago_credito = "Pago Con Deposito";
 }elseif($row['FORMA_PAGO'] == '7'){
 $pago_credito = "Pago Con Cheque";
 }elseif($row['FORMA_PAGO'] == '8'){
 $pago_credito = "Por Definir";
   }
  
$tipo=0;
// Se saca si esta Habilitado o Cancelada la Remision
    if($row['PAYMENT_TYPE_ID'] == '2'){
      $tipo = "Abono";
    }
    $pdf->ln(6);
  // se pasa a positivo los anticipos a creditos.  
  $abono = $row['abono'] * -1 ;

  $pdf->cell(18, 7,$row['ID'],1,0,'C');
  $pdf->cell(20, 7,$row['SELL_ID'],1,0,'C');
  $pdf->cell(20, 7,$tipo,1,0,'C');
  $pdf->cell(30, 7,$pago_credito,1,0,'C');
  $pdf->cell(25, 7,$row['CREATED_AT'],1,0,'C');
  $pdf->cell(20, 7,"$ " .$abono,1,0,'C');
  $pdf->SetFont('helvetica', 'B', 7); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
  $pdf->Cell(145, 7,$row['name'],1,'C'); 
}

$total_abono = $row11['total_acredito'] * -1;

$pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
 $pdf->ln(10);
 $pdf->SetX(185);
 // SE IMPRIME EL TOTAL DE LA VENTA SOLAMENTE EN EFETIVO CON EL ROW3
 $pdf->cell(48, 7,"Total Abonos : $ ".number_format($total_abono,2,'.',','),0,0,'C'); 
 $pdf->ln(5); 
 
   // ------------------------------------------------------------------------------------------------------------------- //  

    //SE SACA EL REPORTE DE TICKETS CANCELADOS DEL DÍA. 
    $pdf->SetFont('helvetica', 'B', 15); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->Cell(400,20,"Reporte Tickets Cancelados");
    $pdf->ln(10);
  
    //SE SACA EL REPORTE DE CREDITOS DEL DÍA.    
     $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
     $pdf->Cell(300,14,"Listado De Tickets Cancelados Del Dia ");
     $pdf->ln(10);
     $pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
     $y = $pdf->GetY(); 
     $pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
     $pdf->SetFont('arial', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
     $y = $pdf->GetY() + 1;
     $pdf->SetXY(10, $y);
     $pdf->Cell(18, 4, utf8_decode("Nº Remision"), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
     $pdf->SetXY(28, $y); //El resultado 22 es la suma de la posición 10 y el tamaño del Cell de 12.
     $pdf->Cell(35, 4, utf8_decode("Fecha / Hora Remision"), 1, 'C');

     $pdf->SetXY(63, $y);
     $pdf->Cell(30, 4, utf8_decode("Forma Pago"), 1, 'C');   
     $pdf->SetXY(93, $y);
     $pdf->Cell(20, 4, utf8_decode("Total"), 1, 'C');
     $pdf->SetFont('helvetica', 'B', 7); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
     $pdf->SetXY(113, $y);
     $pdf->Cell(174, 4, utf8_decode("Nombre Cliente"), 1, 'C'); 
    
     $sql_tic = "SELECT sell.id,sell.created_at,sell.p_id,sell.cancelacion,sell.d_id,sell.f_id,sell.total,sell.user_id,sell.person_id,sell.box_id,user.name AS usuario,CONCAT(person.name,' ',person.lastname) AS cliente from sell 
     INNER JOIN user on 
     user.id = sell.user_id 
     INNER JOIN person on 
     person.id = sell.person_id
     where p_id = 3 and d_id = 3 and operation_type_id = 2  and invoice_code = '' and sell.created_at like '%$fecha%' and stock_to_id = $sucursal";
     $resultado_tic = $mysqli->query($sql_tic);
    
     // SE GENERA LA SUMA DE SOLAMENTE EFECTIVO DE ANTICIPOS DE CLIENTES.
    $sql_tic_suma = "SELECT SUM(total) as total_tic FROM sell where p_id = 3 and d_id = 3  "
    . " and operation_type_id = 2  and invoice_code = '' and sell.created_at like '%$fecha%' and stock_to_id = $sucursal";
    $resultado_tic_total = $mysqli->query($sql_tic_suma);
    $row5 = $resultado_tic_total -> fetch_assoc();

     while($row = $resultado_tic->fetch_assoc()){
     // Se saca la forma de Pago.
     // Se saca si esta Habilitado o Cancelada la Remision
    if($row['f_id'] == '1'){
    $pago_tcan = "Efectivo";
    }elseif($row['f_id'] == '2'){
    $pago_tcan = "Transf. Electronica";
    }elseif($row['f_id'] == '3'){
    $pago_tcan = "Tarjeta De Credito";
    }elseif($row['f_id'] == '4'){
    $pago_tcan = "Tarjeta De Debito";
    }elseif($row['f_id'] == '5'){
    $pago_tcan = "Pago Con Anticipo";
    }elseif($row['f_id'] == '6'){
    $pago_tcan = "Pago Con Deposito";
    }elseif($row['f_id'] == '7'){
    $pago_tcan = "Pago Con Cheque";
    }elseif($row['f_id'] == '8'){
    $pago_tcan = "Por Definir";}
     
  // Se saca si esta Habilitado o Cancelada la Remision
       if($row['p_id'] == '1'){
        $status_ticket = "Habilitado";
       }elseif($row['p_id'] == '3'){
        $status_ticket = "Cancelado";
       }
      $pdf->ln(6);
     $pdf->cell(18, 7,"RC - ".$row['id'],1,0,'C');
     $pdf->cell(35, 7,$row['created_at'],1,0,'C');

     $pdf->cell(30, 7,$pago_tcan,1,0,'C');
     $pdf->cell(20, 7,$row['total'],1,0,'C');
 
     $pdf->Cell(174, 7,$row['cliente'],1,'C'); 
      }
 $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->ln(10);
    $pdf->SetX(185);
    // SE IMPRIME EL TOTAL DE LA VENTA SOLAMENTE EN EFETIVO CON EL ROW3
    $pdf->cell(48, 7,"Total Tickets Cancelados: $ ".number_format($row5['total_tic'],2,'.',','),0,0,'C');
    $pdf->ln(5);
    
//SE SACA EL REPORTE DE RETIROS DEL SISTEMA.

// SE SACA EL REPORTE DE ABONOS DE CLIENTES DEL DÍA. 
$pdf->SetFont('helvetica', 'B', 15); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->Cell(400,20,"Reporte Retiros Del Sistema Del Dia");
$pdf->ln(10);

$pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->Cell(10,14,"Listado Abonos y Retiros En Sistema:");
$pdf->ln(10);
$pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
$y = $pdf->GetY(); 
$pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->SetFont('arial', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
$y = $pdf->GetY() + 1;
$pdf->SetXY(10, $y);
$pdf->Cell(20, 4, utf8_decode("ID Mov"), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
$pdf->SetXY(30, $y);
$pdf->Cell(20, 4, utf8_decode("Operacion"), 1, 'C');   
$pdf->SetXY(50, $y);
$pdf->Cell(25, 4, utf8_decode("Total"), 1, 'C');
$pdf->SetXY(75, $y);
$pdf->Cell(30, 4, utf8_decode("Fecha Registro"), 1, 'C');
$pdf->SetXY(105, $y); //El resultado 22 es la suma de la posición 10 y el tamaño del Cell de 12.
$pdf->Cell(30, 4, utf8_decode("Usuario"), 1, 'C');  
$pdf->SetXY(135, $y); //El resultado 22 es la suma de la posición 10 y el tamaño del Cell de 12.
$pdf->Cell(151, 4, utf8_decode("Operacion"), 1, 'C');  

//SQL PARA SACAR EL REPORTE DE ABONOS POR DÍA.
$sql_operaciones = "select spend.id,spend.name,spend.price,spend.box_id,spend.created_at,spend.stock_id,spend.kind,user.username AS usuario from spend 
inner join user on user.id = spend.user_id
where spend.created_at like '%$fecha%' and spend.stock_id = $sucursal";
$resultado_operaciones = $mysqli->query($sql_operaciones);

// SE GENERA LA SUMA DE SOLAMENTE EFECTIVO DE ANTICIPOS DE CLIENTES.
$sql_abonos = "SELECT SUM(price) as total_abonos FROM spend where kind = 1 and created_at like '%$fecha%'and stock_id = $sucursal ";
$resultado_abonos = $mysqli->query($sql_abonos);
$total_anticipos = $resultado_abonos -> fetch_assoc();

// SE GENERA LA SUMA DE SOLAMENTE EFECTIVO DE ANTICIPOS DE CLIENTES.
$sql_retiros = "SELECT SUM(price) as total_retiros FROM spend where kind = 2 and created_at like '%$fecha%' and stock_id = $sucursal ";
$resultado_retiros = $mysqli->query($sql_retiros);
$total_retiros = $resultado_retiros -> fetch_assoc();

$pago_anticipo = 0;
$total_fr=0;
while($row = $resultado_operaciones->fetch_assoc()){
  
// Se saca si esta Habilitado o Cancelada la Remision
  if($row['kind'] == '1'){
  $operacion = "Abono";
  }elseif($row['kind'] == '2'){
  $operacion = "Retiro";
  }

  $pdf->ln(6);
  $pdf->SetFont('helvetica', '', 8); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
  $pdf->cell(20, 7,$row['id'],1,0,'C');
  $pdf->cell(20, 7,$operacion,1,0,'C');
  $pdf->cell(25, 7,$row['price'],1,0,'C');
  $pdf->Cell(30, 7,$row['created_at'],1,0,'C');
  $pdf->Cell(30, 7,$row['usuario'],1,0,'C');
  
  $pdf->SetFont('helvetica', 'B', 7); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
  $pdf->Cell(152, 7,$row['name'],1,'C');
  //$pdf->cell(18, 7,$row['price'],1,0,'C');
  //$pdf->Cell(78, 7,$row['nombre_cliente'],1,'C');

} 


  $pdf->ln(10);
  $pdf->SetX(80);
  // SE IMPRIME EL TOTAL DE LA VENTA SOLAMENTE EN EFETIVO CON EL 
  $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
  $pdf->cell(48, 7,"Total Abonos: $ ".number_format($total_anticipos['total_abonos'],2,'.',','),0,0,'C');

  $pdf->SetX(185);

  
  // SE IMPRIME EL TOTAL DE LA VENTA SOLAMENTE EN EFETIVO CON EL ROW1
  $pdf->cell(48, 7,"Total Retiros: $ ".number_format($total_retiros['total_retiros'],2,'.',','),0,0,'C');
  $pdf->ln(8);

$pdf->SetFont('helvetica', 'B', 16); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->Cell(10,14,"RESUMEN COMPLETO DE CORTE DE CAJA :");
$pdf->ln(15);



$pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
$y = $pdf->GetY(); 
$pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->SetFont('arial', 'B', 10); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
$y = $pdf->GetY() + 1;

$pdf->Cell(110, 4, utf8_decode("TOTAL VENTAS MOSTRADOR "." :             $ ".number_format($total_te,2,'.',',')), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
$pdf->ln(8);

$pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
$y = $pdf->GetY(); 
$pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->SetFont('arial', 'B', 10); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
$y = $pdf->GetY() + 1;

$pdf->cell(110, 4, utf8_decode("TOTAL FACTURAS ELECTRÓNICAS :"."          $ ".number_format($row10['total'],2,'.',',')), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
$pdf->ln(8);

$pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
$y = $pdf->GetY(); 
$pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->SetFont('arial', 'B', 10); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
$y = $pdf->GetY() + 1;

$pdf->cell(110, 4, utf8_decode("TOTAL VENTAS CON ANTICIPO :"."            $ ".number_format($venta_anticipo,2,'.',',')), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible


$pdf->ln(8);

$pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
$y = $pdf->GetY(); 
$pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->SetFont('arial', 'B', 10); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
$y = $pdf->GetY() + 1;

$pdf->cell(120, 4, utf8_decode("TOTAL ANTICIPOS - ABONOS A CUENTAS :"."             $ ".number_format($row1['total'],2,'.',',')), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible

$pdf->ln(8);

$pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
$y = $pdf->GetY(); 
$pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->SetFont('arial', 'B', 10); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
$y = $pdf->GetY() + 1;
$pdf->cell(110, 4, utf8_decode("TOTAL SALDOS RECUPERADOS VENTAS :"."          $ ". number_format($row3['total_sr'],2,'.',',')), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible

$pdf->ln(8);

$pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
$y = $pdf->GetY(); 
$pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->SetFont('arial', 'B', 10); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
$y = $pdf->GetY() + 1;

$pdf->Cell(120, 4, utf8_decode("TOTAL ABONOS A CREDITOS :"."            $ ". number_format($row11['total_acredito'] * -1,2,'.',',')), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible

$abonos_creditos = ($row11['total_acredito'] * -1);

$pdf->ln(8);

$pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
$y = $pdf->GetY(); 
$pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->SetFont('arial', 'B', 10); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
$y = $pdf->GetY() + 1;


$pdf->Cell(110, 4, utf8_decode("TOTAL ABONOS A CAJA :" ."           $ ".number_format($total_anticipos['total_abonos'],2,'.',',')), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
$pdf->ln(8);

$pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
$y = $pdf->GetY(); 
$pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->SetFont('arial', 'B', 10); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
$y = $pdf->GetY() + 1;

$pdf->Cell(120, 4, utf8_decode("TOTAL RETIROS DE CAJA :"."          $ ".number_format($total_retiros['total_retiros'],2,'.',',')), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible


$pdf->ln(8);

$pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
$y = $pdf->GetY(); 
$pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->SetFont('arial', 'B', 10); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
$y = $pdf->GetY() + 1;

$pdf->Cell(110, 4, utf8_decode("TOTAL TICKETS CANCELADOS :"."            $ ".number_format($row5['total_tic'],2,'.',',')), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
$pdf->ln(8);
$pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
$y = $pdf->GetY(); 
$pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->SetFont('arial', 'B', 10); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
$y = $pdf->GetY() + 1;



$pdf->Cell(120, 4, utf8_decode("TOTAL FACTURAS CANCELADAS :"."          $ ".number_format($total_fc,2,'.',',')), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible


$total_corte =0;
$total_corte = $total_te+$row10['total']+$row1['total'] + $row3['total_sr']  +$abonos_creditos+ $total_anticipos['total_abonos']  + $venta_anticipo;

$total_efectivo = $total_corte - $total_retiros['total_retiros'];

$pdf->ln(12);
$y = $pdf->GetY(); 
$pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->SetFont('arial', 'B', 14); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente

$y = $pdf->GetY(); 

$pdf->Cell(190, 4, utf8_decode("TOTAL EFECTIVO EN CAJA :  "."   $ ".number_format($total_efectivo,2,'.',','))); //Utilizamos el utf8_decode para evitar código basura o ilegible
$pdf->SetXY(120, $y);




$pdf->output();


    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    



 








































