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

$cliente = $_GET['idcliente'];
$idabono = $_GET['id'];


/*$stock_id = $_GET['stock_id'];
$suc_name = "SELECT * from stock where id = $stock_id";
$resultado = $mysqli->query($suc_name);
$row = mysqli_fetch_assoc($resultado);
$nombre_sucursal = $row['name']; 
*/


$sql_cliente = "SELECT * from person where id = $cliente";
$res_cliente = $mysqli->query($sql_cliente);
$row = mysqli_fetch_assoc($res_cliente);
$nombre_cliente = $row['name']."".$row['lastname']; 
$direccion_cliente = $row['address1']; 
$telefono_cliente = $row['phone1'];
$rfc_cliente =  $row['no'];


//SE SACA LA SUMA TOTAL DEL ANTICIPO
$sql_anticipo = "SELECT sum(cantidad) AS total FROM bitacora_abonos WHERE idabonos = $idabono and idcliente = $cliente";
$res_anticipo = $mysqli->query($sql_anticipo);
$row2 = mysqli_fetch_assoc($res_anticipo);
$monto_total = $row2['total']; 

//SQL PARA SACAR INFORMACION DEL ANTICIPO
$sql_anticipo = "SELECT * from bitacora_abonos where idabonos = $idabono";
$res_anticipo = $mysqli->query($sql_anticipo);
$row1 = mysqli_fetch_assoc($res_anticipo);
$id = $row1['idabonos'];
$cant_ingresada = $row1['cant_ingresada']; 
$idoperacion = $row1['factura_electronica'];
$fecha =  $row1['fecha'];


$pdf = new FPDF($orientation = 'P', $unit = 'mm');

$pdf->AddPage();
$pdf->SetFont('Arial', '', 6);    //Letra Arial, negrita (Bold), tam. 20
//$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
//$pdf->SetFont('DejaVu','',8);
//$pdf->setXY(5,0);
$plusforimage = 0;
if ($ticket_image != "") {
    $src = "storage/configuration/" . $ticket_image;
    if (file_exists($src)) {
        $pdf->Image($src, 15, 4, 45);
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
$pdf->SetFont('helvetica', 'B', 20); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(0, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(220, 30, "Historial Movimientos Abonos", 0, 4, 'C');
$pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(95, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página

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

$pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,120,"Informacion General Del Anticipo  ____________________________________________________________________");



$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(140, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,140, "Total Disponible : "."$". $monto_total);


$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,140, "ID Anticipo : ". $idabono);

$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,160, "ID Operacion : ". $idoperacion);

$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(70, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,140, "Cantidad Ingresada : "."$". $cant_ingresada);


$pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(80, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,160, "Fecha Registro: ". $fecha);



// Se saca si esta Habilitado o Cancelada la Remision
if($row1['forma_pago'] == '1'){
    $forma_pa = "Pago En Efectivo";
    }elseif($row1['forma_pago'] == '2'){
    $forma_pa = "Transferencia Electronicca";
    }if($row1['forma_pago'] == '3'){
    $forma_pa = "Tarjeta De Crédtio";
    }if($row1['forma_pago'] == '4'){
    $forma_pa = "Tarjeta De Debito";
    }if($row1['forma_pago'] == '5'){
    $forma_pa = "Deposito Bancario";
    }if($row1['forma_pago'] == '6'){
    $forma_pa = "Pago Con Cheque";}


       // Se saca si esta Habilitado o Cancelada la Remision
       if($row['forma_pago'] == '01'){
        $forma_pa = "Efectivo";
        }elseif($row1['forma_pago'] == '02'){
        $forma_pa = "Cheque";
        }elseif($row1['forma_pago'] == '03'){
        $forma_pa = "Transf. Elec";
        }elseif($row1['forma_pago'] == '04'){
          $forma_pa = "T. Credito"; 
        }elseif($row1['forma_pago'] == '05'){
          $forma_pa = "Monedero electrónico"; 
        }elseif($row1['forma_pago'] == '06'){
          $forma_pa = "Dinero electrónico"; 
        }elseif($row1['forma_pago'] == '8'){
          $forma_pa = "Vales de despensa"; 
        }elseif($row1['forma_pago'] == '12'){
          $forma_pa = "Dación en pago"; 
        }elseif($row1['forma_pago'] == '13'){
          $forma_pa = "Pago por subrogación"; 
        }elseif($row1['forma_pago'] == '14'){
          $forma_pa = "Pago por consignación"; 
        }elseif($row1['forma_pago'] == '15'){
          $forma_pa = "Condonación"; 
        }elseif($row1['forma_pago'] == '17'){
          $forma_pa = "Compensación"; 
        }elseif($row['forma_pago'] == '23'){
          $forma_pa = "Novacion"; 
        }elseif($row1['forma_pago'] == '24'){
          $forma_pa = "Confusion"; 
        }elseif($row1['forma_pago'] == '25'){
          $forma_pa = "Remision de deuda"; 
        }elseif($row1['forma_pago'] == '26'){
          $forma_pa = "prescripcion o caducidad"; 
        }elseif($row1['forma_pago'] == '27'){
          $forma_pa = "A satisfaccion del acreedor"; 
        }elseif($row1['forma_pago'] == '28'){
          $forma_pa = "T. De debito"; 
        }elseif($row1['forma_pago'] == '29'){
          $forma_pa = "Tarjeta de servicios"; 
        }elseif($row1['forma_pago'] == '30'){
          $forma_pa = "Aplicacion de anticipos"; 
        }elseif($row1['forma_pago'] == '31'){
          $forma_pa = "Intermediario Pagos"; 
        }elseif($row1['forma_pago'] == '99'){
          $forma_pa = "Por Definir"; 
        }
   $pdf->SetFont('helvetica', '', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
   $pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
   $pdf->Cell(260,175, "Forma De Pago : ". $forma_pa);
   


$pdf->SetFont('helvetica', 'B', 10); //Asignar la fuente, el estilo de la fuente (negrita) y el tamaño de la fuente
$pdf->SetXY(10, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
$pdf->Cell(260,190,"Registro General de Movimientos  ___________________________________________________________________");


$sql = "SELECT * FROM bitacora_pagos_anticipo  WHERE id_cliente = $cliente and id_anticipo = $idabono";

 $resultado = $mysqli->query($sql);


 $pdf->ln(100);
 $pdf->SetFont('courier', 'U', 15); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
 $y = $pdf->GetY(); 
 $pdf->SetXY(40, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
 $pdf->SetFont('arial', '', 8); //Asignar la fuente, el estilo de la fuente (subrayado) y el tamaño de la fuente
 $y = $pdf->GetY() + 1;
 $pdf->SetXY(10, $y);
 $pdf->MultiCell(15, 4, utf8_decode("Remision"), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
 $pdf->SetXY(25, $y);
 $pdf->MultiCell(20, 4, utf8_decode("Pagado"), 1, 'C');
 $pdf->SetXY(45, $y);
 $pdf->MultiCell(25, 4, utf8_decode("Total"), 1, 'C');
 $pdf->SetXY(70, $y);
 $pdf->MultiCell(25, 4, utf8_decode("Saldo Nuevo"), 1, 'C');
 $pdf->SetXY(95, $y);
 $pdf->MultiCell(30, 4, utf8_decode("Fecha Operacion"), 1, 'C');  
 $pdf->SetXY(125, $y);
 $pdf->MultiCell(30, 4, utf8_decode("ID Operacion"), 1, 'C');
 
 
 $pdf->SetXY(155, $y);
 $pdf->MultiCell(45, 4, utf8_decode("Operacion"), 1, 'C');  
  while($row = $resultado->fetch_assoc()){
  
      if($row['operacion'] == '3'){
      $operacion = "Compra - Anticipo";
      }elseif($row['operacion'] == '4'){
      $operacion = "Cancelacion Compra";
      }elseif($row['operacion'] == '1'){
      $operacion = "Credito Liquidado";
      }elseif($row['operacion'] == '2'){
      $operacion = "Abono A Credito";
      }elseif($row['operacion'] == '5'){
      $operacion = "Devolucion De Credito";
      }elseif($row['operacion'] == '6'){
      $operacion = "Cancelacion Venta";
      }elseif($row['operacion'] == '7'){
      $operacion = "Credito Liquidado";
      }
$pdf->cell(15, 7,$row['remision'],1,0,'C');
$pdf->cell(20, 7,"$ ".$row['pagado'],1,0,'C');
$pdf->cell(25, 7,"$ ".$row['total_remision'],1,0,'C');
$pdf->cell(25, 7,"$ ".$row['nuevo_saldo'],1,0,'C');
$pdf->cell(30, 7,$row['fecha_operacion'],1,0,'C');
$pdf->cell(30, 7,$row['id_operacion'],1,0,'C');
$pdf->Multicell(45, 7,$operacion,1,'C');


} 

  $pdf->output();


        
 
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    



 








































