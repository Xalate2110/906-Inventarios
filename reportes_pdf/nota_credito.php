<?php
setlocale(LC_CTYPE, 'es_MX');
include "../core/controller/Core.php";
include "../core/controller/Database.php";
include "../core/controller/Executor.php";
include "../core/controller/Model.php";
include "../core/app/model/UserData.php";
include "../core/app/model/SellData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/StockData.php";
include "../core/app/model/ConfigurationData.php";
include "../core/app/model/PersonData.php";
include '../connection/conexion.php';
include "../fpdf/fpdf.php";
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


$id = $_GET['id'];
$cliente = $_GET['cliente'];
$sucursal = $_GET['sucursal'];

$sql_cliente = "SELECT * from person where id = $cliente";
$res_cliente = $mysqli->query($sql_cliente);
$row = mysqli_fetch_assoc($res_cliente);
$nombre_cliente = $row['name']; 
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

if($stock->image!=""){
  $ticket_image = $imagen;}

if ($ticket_image != "") {
    $src = "../storage/stocks/" . $ticket_image;
    if (file_exists($src)) {
      $pdf->Image($src, 150, 4, 45);
        $plusforimage = 25;
    }
}

date_default_timezone_set('America/Monterrey');
$pdf->SetFont('Arial', 'B', 9);

$yy = 15; //Variable auxiliar para desplazarse 40 puntos del borde superior hacia abajo en la coordenada de las Y para evitar que el título este al nivel de la cabecera.
$y = $pdf->GetY(); 
$x = 12;
$textypos = 8 + $plusforimage;
$pdf->SetFont('helvetica', 'B', 15); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(85, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(50, -25,utf8_decode("COMPROBANTE CRÉDITO"), 0, 4, 'C');
$pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(95, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página


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
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,50,utf8_decode("Información General Del Cliente  _____________________________________________________________________"));

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


$sql_venta="select * from sell where id = $id and p_id = 4 ";
$resultado = $mysqli->query($sql_venta);
while($row = $resultado->fetch_assoc()){
   
      if($row['f_id'] == '1'){
        $pago = "En Efectivo";
        }elseif($row['f_id'] == '2'){
        $pago = "Transf. Electronica";
        }elseif($row['f_id'] == '3'){
        $pago = "Tarjeta De Credito";
        }elseif($row['f_id'] == '4'){
        $pago = "Tarjeta De Debito";
        }elseif($row['f_id'] == '5'){
        $pago = "Dep. Bancario";
        }elseif($row['f_id'] == '6'){
        $pago = "Con Deposito";
        }elseif($row['f_id'] == '7'){
        $pago = "Con Cheque";
        }elseif($row['f_id'] == '8'){
        $pago= "Por Definir";
          }

                if($row['d_id'] == '1'){
            $entregada = "Entregado";
            }elseif($row['d_id'] == '2'){
            $entregada = "Pendiente";
            }elseif($row['d_id'] == '3'){
            $entregada = "Cancelado";
            }

            if($row['p_id'] == '1'){
            $pagada = "Pagado";
            }elseif($row['p_id'] == '2'){
            $pagada = "Pendiente";
            } elseif($row['p_id'] == '3'){
            $pagada = "Cancelado";
            }elseif($row['p_id'] == '4'){
            $pagada = "Crédito";
              }
    
            $fecha_pago = $row['fecha_pago']; 
            $referencia_venta = $row['ref_id']; 
            $total = $row['total']; 
            $anticipo = $row['anticipo_venta'];
            $por_pagar = $row['total_por_pagar'];  
            $fecha_remision = $row['created_at'];  
            $fecha_cancelacion = $row['cancelacion'];
            $folio_factura = $row['invoice_code'];    
            $sub_total = $row['sub_total'];
            $iva = $row['iva'];   
            $descuento = $row['discount'];     

}


$pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,110,utf8_decode("Información General De La Venta  ____________________________________________________________________"));


$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,125, "ID Venta : ". $id);


$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,135, "Referencia : N/A");



$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,157, "Forma Pago : ".$pago);

$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,169, "Status Entrega : ".$entregada);


$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,145, "Status Pago : ".utf8_decode($pagada));


$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(70, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,125, "Fecha Remision : ".$fecha_remision);


$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(155, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,125, "Anticipo : $ ".number_format($anticipo,2,'.',',')); 



$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(155, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,140, "SubTotal : $ ".number_format($sub_total,2,'.',','));


$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(155, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,152, "Impuesto : $ ".number_format($iva,2,'.',','));

$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(155, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,163, "Descuento : $ ".number_format($descuento,2,'.',','));


$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(155, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,175, "Total Venta : $ ".number_format($total,2,'.',','));



$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(70, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,138, "UUID: ".$folio_factura);


  $pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
  $pdf->SetXY(70, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
  $pdf->Cell(260,150, "Fecha Pago: ".$fecha_pago);
  

  $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
  $pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
  $pdf->Cell(260,185,"Listado De Productos Comprados ____________________________________________________________________");
        

       $sql = "SELECT operation.q,product.name,product.code,operation.price_out, operation.product_id from operation INNER JOIN product on
       operation.product_id = product.id and 
       operation.sell_id = $id and  id_salida = 0";
        $resultado = $mysqli->query($sql);


        $pdf->ln(98);
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
        $pdf->MultiCell(105, 4, utf8_decode("Descripción"), 1, 'C');  
        $pdf->SetXY(155, $y);
        $pdf->MultiCell(20, 4, utf8_decode("Precio U."), 1, 'C');
        $pdf->SetXY(175, $y);
        $pdf->MultiCell(27, 4, utf8_decode("Importe"), 1, 'C');  
        $pdf->SetFont('arial', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
        $total_entregado=0;
        $totalcredito =0;

       
        while($row = $resultado->fetch_assoc()){
       $pdf->cell(15, 7,$row['q'],1,0,'C');
       $pdf->cell(25, 7,$row['code'],1,0,'C');
       $pdf->cell(105, 7,$row['name'],1,0,'C');
       $pdf->cell(20, 7,"$ ".$row['price_out'],1,0,'C');
       $total_entregado = $row['q'] * $row['price_out'];
       $pdf->Multicell(27, 7,"$ ".number_format($total_entregado,2,'.',','),1,'C');

       $totalcredito +=$total_entregado;
}

$pdf->ln(6);
$pdf->Multicell(192, 5,"Debo(emos) y Pagare(mos), incondicionalmente a la orden de Alejandro Uribe, la cantidad de $ $totalcredito M.N. en esta ciudad de $ciudad el dia ____________________ . Valor de la mercancia que he(emos) recibido a mi (nuestra) entera satisfaccion. Este pagare es mercantil y sera regisdo por la Ley de Titulos y Operaciones a Credito en el ART. 173 y demas correlativos domiciliario. Si este pagare no es cubierto a su vencimiento causara intereses monatorios al __________ % mensual, la firma estampada en cualquier parte de la presente, significara la aceptacion expresa del deudor y obligacion de pago. En toda devolucion de cheques se les cargara un 20% ART. 193 de la L.G.T.O.C.",1,'J');



 $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
 $pdf->ln(2);
 $pdf->SetX(115);
 $pdf->Cell(13,15,"___________________________________________");

 $pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
 $pdf->ln(6);
 $pdf->SetX(145);
 $pdf->Cell(100,18,"Firma Cliente");





  $pdf->output();


        
 
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    



 








































