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

$id = $_GET['id'];

$cliente = $_GET['cliente'];
$product_id = $_GET['producto_id'];
$sucursal = $_GET['sucursal'];

$sql_cliente = "SELECT * from person where id = $cliente";
$res_cliente = $mysqli->query($sql_cliente);
$row = mysqli_fetch_assoc($res_cliente);
$nombre_cliente = $row['name']."".$row['lastname']; 
$direccion_cliente = $row['address1']; 
$telefono_cliente = $row['phone1'];
$rfc_cliente =  $row['no'];



$suc_name = "SELECT * from stock where id = $sucursal";
$resultado = $mysqli->query($suc_name);
$row = mysqli_fetch_assoc($resultado);
$nombre_sucursal = $row['name']; 
$direccion_sucursal = $row['address']; 
$colonia = $row['colonia']; 
$ciudad = $row['ciudad']; 
$telefono = $row['phone']; 
$wa = $row['field1']; 





$pdf = new FPDF($orientation = 'P', $unit = 'mm');

$pdf->AddPage();
$pdf->SetFont('Arial', '', 6);    //Letra Arial, negrita (Bold), tam. 20
//$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
//$pdf->SetFont('DejaVu','',8);
//$pdf->setXY(5,0);
$plusforimage = 0;
if ($ticket_image != "") {
    $src = "../storage/configuration/" . $ticket_image;
    if (file_exists($src)) {
        $pdf->Image($src, 8, 4, 70);
        $plusforimage = 25;
    }
}
date_default_timezone_set('America/Monterrey');
$pdf->SetFont('Arial', 'B', 9);

$ancho = 190;
$pdf->SetY(12); //Mencionamos que el curso en la posición Y empezará a los 12 puntos para escribir el Usuario:

$pdf->SetY(15);
$pdf->Cell($ancho, 13,'Fecha Impresion : '.date('d/m/Y'), 0, 0, 'R');
$pdf->SetY(18);
$pdf->Cell($ancho, 16,'Hora Impresion : '.date('H:i:s'), 0, 0, 'R');     

$yy = 15; //Variable auxiliar para desplazarse 40 puntos del borde superior hacia abajo en la coordenada de las Y para evitar que el título este al nivel de la cabecera.
$y = $pdf->GetY(); 
$x = 12;
$pdf->SetFont('helvetica', 'B', 15); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(25, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(220, 20, "Comprobante Movimiento de Producto", 0, 4, 'C');
$pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(95, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página

$pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(25, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,4,"". $nombre_sucursal);

$pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(18, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,14,"". $direccion_sucursal);


$pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(20, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,25,"". $colonia);


$pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(28, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,35,"Tel : ". $telefono);



$pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,50,"Informacion General Del Cliente  _____________________________________________________________________");

$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,65, "Nombre Cliente : ". $nombre_cliente);

$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,75, "RFC : ". $rfc_cliente);

$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(140, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,70, "Telefono : ". $telefono_cliente);

$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,85, "ID Cliente : ". $cliente);

$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,95, "Ciudad / Estado : ". $direccion_cliente);


$sql_venta="select * from sell where id = $id";
$resultado = $mysqli->query($sql_venta);
while($row = $resultado->fetch_assoc()){
   
      if($row['f_id'] == '1'){
        $pago = "Pago En Efectivo";
        }elseif($row['f_id'] == '2'){
        $pago = "Transf. Electronica";
        }elseif($row['f_id'] == '3'){
        $pago = "Tarjeta De Credito";
        }elseif($row['f_id'] == '4'){
        $pago = "Tarjeta De Debito";
        }elseif($row['f_id'] == '5'){
        $pago = "Dep. Bancario";
        }elseif($row['f_id'] == '6'){
        $pago = "Pago Con Deposito";
        }elseif($row['f_id'] == '7'){
        $pago = "Pago Con Cheque";
        }elseif($row['f_id'] == '8'){
        $pago= "Por Definir";
          }

        if($row['d_id'] == '1'){
            $entregada = "Entregados";
            }elseif($row['p_id'] == '2'){
            $entregada = "Pendientes";
            }elseif($row['p_id'] == '3'){
            $entregada = "Cancelados";
            }

            if($row['p_id'] == '1'){
            $pagada = "Pagado";
            }elseif($row['p_id'] == '2'){
            $pagada = "Pendiente";
            } elseif($row['p_id'] == '3'){
            $pagada = "Cancelado";
            }
    
$fecha_pago = $row['fecha_pago']; 
$referencia_venta = $row['ref_id']; 
$total = $row['total']; 
$anticipo = $row['anticipo_venta'];
$por_pagar = $row['total_por_pagar'];  
$fecha_remision = $row['fecha_remision'];  
$fecha_cancelacion = $row['cancelacion'];
$folio_factura = $row['invoice_code'];    
$sub_total = $row['sub_total'];
$iva = $row['iva'];    
}


$pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,110,"Informacion General De La Venta  ____________________________________________________________________");


$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,125, "ID Venta : ". $id);


$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,135, "Ref : ".$referencia_venta);



$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,157, "Forma Pago : ".$pago);

$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,169, "Status Entrega : ".$entregada);


$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,145, "Status Pago : ".$pagada);


$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(70, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,125, "Fecha Remision : ".$fecha_remision);

$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(70, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,138, "Fecha Pago: ".$fecha_pago);


$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(160, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,125, "Anticipo : $ ".$anticipo);



$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(155, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,140, "SubTotal : $ ".$sub_total);


$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(155, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,152, "Impuesto : $ ".$iva);

$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(155, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,165, "Total Venta : $ ".$total);


$pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(155, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,180, "Por Cobrar : $ ".$por_pagar);





$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(70, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,150, "UUID: ".$folio_factura);


$pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,185,"Registro de Movimientos  ___________________________________________________________________________");
        
$sql = "SELECT * FROM sell_to_deliver where sell_id = $id and product_id = $product_id";
        $resultado = $mysqli->query($sql);

        $pdf->ln(98);
        $pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
        $y = $pdf->GetY(); 
        $pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
        $pdf->SetFont('arial', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
        $y = $pdf->GetY() + 1;
        $pdf->SetXY(10, $y);
        $pdf->MultiCell(12, 4, utf8_decode("ID"), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
        $pdf->SetXY(22, $y);
        $pdf->MultiCell(15, 4, utf8_decode("Precio"), 1, 'C');
        $pdf->SetXY(37, $y);
        $pdf->MultiCell(16, 4, utf8_decode("Entregado"), 1, 'C');  
        $pdf->SetXY(53, $y);
        $pdf->MultiCell(15, 4, utf8_decode("Entrada"), 1, 'C');
        $pdf->SetXY(68, $y);
        $pdf->MultiCell(15, 4, utf8_decode("Salida"), 1, 'C');  
        $pdf->SetXY(83, $y);
        $pdf->MultiCell(15, 4, utf8_decode("$ Total "), 1, 'C'); 
        $pdf->SetXY(98, $y);
        $pdf->MultiCell(18, 4, utf8_decode("Operacion "), 1, 'C'); 
        $pdf->SetXY(116, $y);
        $pdf->Cell(18, 4, utf8_decode("Fecha "), 1,0, 'C'); 
        $pdf->SetXY(134, $y);
        $pdf->MultiCell(70, 4, utf8_decode("Producto "), 1, 'C'); 
        $pdf->SetFont('arial', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
        $total_entregado=0;
        while($row = $resultado->fetch_assoc()){
        // Se saca la forma de Pago.
        if($row['operacion'] == '1'){
        $entrega = "Salida";
        }else if($row['operacion'] == '2'){
        $entrega = "Entrada";
         } else  if($row['operacion'] == '3'){
        $entrega = "Salida";
         }
        
       $pdf->cell(12, 7,$row['id'],1,0,'C');
       $pdf->cell(15, 7,"$ ".$row['precio_out'],1,0,'C');
       $pdf->cell(16, 7,$row['entregada'],1,0,'C');
       $pdf->cell(15, 7,$row['cant_entrada'],1,0,'C');
       $pdf->cell(15, 7,$row['cant_salida'],1,0,'C');
       $pdf->cell(15, 7,"$ ".$row['total_entregado'],1,0,'C');
       $total_entregado = $total_entregado + $row['total_entregado'];
     
       $pdf->cell(18, 7,$entrega,1,0,'C');
       $pdf->cell(18, 7,$row['fechaEntregada'],1,0,'C');
       $pdf->Multicell(70, 7,$row['descripcion'],1,'C');
}



    // SE GENERA LA SUMA DE SOLAMENTE EFECTIVO DE ANTICIPOS DE CLIENTES.
    $sql_entregas = "SELECT SUM(total_entregado) as total_e from sell_to_deliver where operacion = 1 and sell_id = $id and product_id = $product_id  ";
    $res_entregas = $mysqli->query($sql_entregas);
    $row = $res_entregas -> fetch_assoc();

    $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->ln(10);
    $pdf->SetX(10);
    $pdf->cell(48, 7,"Total Entregado : $ ".$row['total_e'],1,0,'C');
    

    // SE GENERA LA SUMA DE SOLAMENTE EFECTIVO DE ANTICIPOS DE CLIENTES.
    $sql_entradas = "SELECT SUM(total_entregado) as total_entradas from sell_to_deliver where operacion = 2 and sell_id = $id and product_id = $product_id ";
    $res_entradas = $mysqli->query($sql_entradas);
    $row1 = $res_entradas -> fetch_assoc();

    $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->SetX(80);
    $pdf->cell(48, 7,"Total Entrada : $ ".$row1['total_entradas'],1,0,'C');


    $sql_salidas = "SELECT SUM(total_entregado) as total_salidas from sell_to_deliver where operacion = 3 and sell_id = $id and product_id = $product_id ";
       $res_salidas = $mysqli->query($sql_salidas);
       $row2 = $res_salidas -> fetch_assoc();


    $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->SetX(150);
   $pdf->cell(48, 7,"Total Salida : $ ".$row2['total_salidas'],1,0,'C');
  
      $pdf->ln(12);

      $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->ln(2);
    $pdf->SetX(65);
    $pdf->Cell(48,35,"___________________________________________");
    
    $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
    $pdf->ln(6);
    $pdf->SetX(75);
    $pdf->Cell(48,35,"".$nombre_cliente);
    
    $pdf->output();


        
 
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    



 








































