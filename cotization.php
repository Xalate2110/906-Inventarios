<?php
setlocale(LC_CTYPE, 'es_MX');
include "fpdf/fpdf.php";
//phpinfo();

$symbol = "$";
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

$sell = SellData::getById($_GET["id"]);
$ticket_image = ConfigurationData::getByPreffix("ticket_image")->val;
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$stock = StockData::getById($sell->stock_to_id);
$person_name="";
$person_rfc="";
$person_address="";
if($sell->person_id){
        $person = PersonData::getById($sell->person_id);
        $person_name=$person->name;
        $person_rfc=$person->no;
        $person_address=$person->address1; }
        $sellx = $sell->id;
        $comentarios = $sell->comment;
        $descuento = $sell->discount;


//////////// ARRAY CON LOS PRODUCTOS , CANTIDADES Y PRECIOS
$products = array();
foreach($operations as $op){
        $product = ProductData::getById($op->product_id);
        $products[] = array(
                "id"=>$product->id,
                "code"=>$product->code,
                "product"=>$product->name,
                "qty"=>$op->q,
                "price"=>$product->price_out,
                "price_out"=>$op->price_out,
                "dxp"=>$op->descuento_p);}

$pdf = new FPDF($orientation='P');
$pdf->AddPage();
$pdf->SetFont('Arial','B',8);    

$plusforimage =0;
if($stock->image!=""){
        $ticket_image = $stock->image;}
   if ($ticket_image != "") {
        $src = "storage/stocks/". $ticket_image;
       if (file_exists($src)) {
        $pdf->setX(10);
           $pdf->Image($src, 10, 6, 50);
           $plusforimage = 0;}
        }

$pdf->SetFont('Arial','B',20);    //Letra Arial, negrita (Bold), tam. 20

/////////////////////////////////////// DATOS FIJOS


$pdf->SetTextColor(0,0,0);
$pdf->setY(48);
$pdf->setX(60);
$pdf->Cell(5,0,utf8_decode("C  O  T  I  Z  A  C  I  Ó  N"));
$pdf->SetFont('Arial','B',9);    //Letra Arial, negrita (Bold), tam. 20

$pdf->SetTextColor(206,14,45);
$pdf->setY(84);
$pdf->setX(10);
$pdf->Cell(190,5,"CONDICIONES:",1);

$pdf->SetFont('Arial','B',12);    //Letra Arial, negrita (Bold), tam. 20
$pdf->SetFillColor(204, 204, 204);
$pdf->SetTextColor(0,0,0);

$pdf->setY(16);
$pdf->setX(150);
$pdf->Cell(50,5,utf8_decode("COTIZACIÓN "),0,1,'C',1);

$pdf->setY(16);
$pdf->setX(150);
$pdf->Cell(50, 5, " " , 1, 1, 'C');


$pdf->SetFont('Arial','B',10);    //Letra Arial, negrita (Bold), tam. 20
$pdf->SetTextColor(206,14,45);
$pdf->setY(21);
$pdf->setX(149);
$pdf->Cell(5,5,'NUM ');
$pdf->setY(21);
$pdf->setX(184);

$pdf->SetFont('Arial','B',10);    //Letra Arial, negrita (Bold), tam. 20
$pdf->SetTextColor(0,0,0); // CAMBIO DE COLOR
$pdf->Cell(60,5,"00 - ".$sellx);
$pdf->SetFont('Arial','B',10);    //Letra Arial, negrita (Bold), tam. 20

$pdf->SetTextColor(255,255,255); // CAMBIO DE COLOR
$pdf->setFillColor(0,100,250);  // BG COLOR DEL ENCABEZADO
$pdf->setY(55);
$pdf->setX(10);
$pdf->Cell(77, 27, "", 1, 1, 'C');
$pdf->setY(55);
$pdf->setX(10);

$pdf->SetFillColor(204, 204, 204);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(77,5,"DATOS DE CLIENTE",0,1,'C',1); //your cell
$pdf->setY(55);
$pdf->setX(106-16);

$pdf->Cell(110, 27, "", 1, 1, 'C');
$pdf->setY(55);
$pdf->setX(90);

$pdf->Cell(110,5,utf8_decode("INFORMACIÓN RELEVANTE"),0,1,'C',1); //your cell
$pdf->SetTextColor(0,0,0);
//////////////////////////////////////// DATOS FIJOS
$textypos = 35+$plusforimage;
$pdf->SetTextColor(0,0,0);
$pdf->setY(10);
$pdf->setX(35);
$pdf->SetFont('Arial','',14);    //Letra Arial, negrita (Bold), tam. 20
$pdf->MultiCell(200, 4, "                       ".strtoupper(utf8_decode($stock->name)),"C");

$pdf->setY(85);
$pdf->setX(16);
$pdf->SetFont('Arial','',8);    //Letra Arial, negrita (Bold), tam. 20
$pdf->MultiCell(400, 4, "                       ".(utf8_decode($comentarios)),"C");

$pdf->setY(1);
$pdf->setX(79);
$pdf->SetFont('Arial','',11);    //Letra Arial, negrita (Bold), tam. 20
$pdf->Cell(5,$textypos,utf8_decode($stock->address));
$pdf->setY(11);
$pdf->setX(12);

$pdf->setY(7);
$pdf->setX(65);
$pdf->SetFont('Arial','',11);    //Letra Arial, negrita (Bold), tam. 20
$pdf->Cell(5,$textypos,utf8_decode($stock->colonia));
$pdf->setY(11);
$pdf->setX(12);

$pdf->setY(13);
$pdf->setX(76);
$pdf->SetFont('Arial','',11);    //Letra Arial, negrita (Bold), tam. 20
$pdf->Cell(5,$textypos,utf8_decode($stock->ciudad));
$pdf->setY(11);
$pdf->setX(12);

$pdf->setY(20);
$pdf->setX(72);
$pdf->SetFont('Arial','',11);    //Letra Arial, negrita (Bold), tam. 20
$pdf->Cell(5,$textypos,utf8_decode("TELÉFONO :  ").utf8_decode($stock->phone));
$pdf->setY(11);
$pdf->setX(12);



$pdf->setX(12);
$pdf->SetFont('Arial','',8);    //Letra Arial, negrita (Bold), tam. 20
//$pdf->Cell(5,$textypos, "                        MUCICIPIO: TLAXCOAPAN, ESTADO : HIDALGO, PAIS: MEXICO, CP: 42953");


//$pdf->SetFont('DejaVu','',5);    //Letra Arial, negrita (Bold), tam. 20
$pdf->SetFont('Arial','',9);    //Letra Arial, negrita (Bold), tam. 20
$textypos+=18;

$pdf->setY(60);
$pdf->setX(11);
$pdf->Cell(5,5,"RFC: ".$person_rfc);

$textypos+=8;
$pdf->setY(64);
$pdf->setX(11);
$pdf->Cell(5,5,"NOMBRE: ".$person_name);
$pdf->setY(68);
$pdf->setX(11);
$pdf->Cell(5,5,"DOMICILIO FISCAL:".substr($person_address,0,20));
$pdf->setY(72);
$pdf->setX(11);
$pdf->Cell(5,5,"".substr($person_address,20,40));
$pdf->setY(80);
$pdf->setX(15);
$pdf->Cell(5,5,"");
//////////////////// DAOS DEL CLIENTE 
$cliente="";
$empresa="";
$area="";
$ciudad="";
$descripcion="";

$pdf->setY(60);
$pdf->setX(110-16);
$pdf->Cell(5,5,utf8_decode("FECHA DE EMISIÓN: ").date("d-m-Y",strtotime($sell->created_at)));

$textypos+=8;
$pdf->setY(64);
$pdf->setX(110-16);
$pdf->Cell(5,5,"FORMA DE PAGO: Por Definir");
$pdf->setY(68);
$pdf->setX(110-16);
$pdf->Cell(5,5,"METODO DE PAGO: Por Defnir");
$pdf->setY(72);
//$pdf->setX(110-16);
//$pdf->Cell(5,5,"FECHA DE EXPIRACION: ".date("d-m-Y",strtotime($sell->created_at)+(60*60*24*30)));
$pdf->setY(72);
$pdf->setX(110-16);
$pdf->Cell(5,5,"MONEDA: (MXN)");

$pdf->SetTextColor(255,255,255); // CAMBIO DE COLOR
$pdf->setFillColor(0,0,0); 
$textypos+=16;
$pdf->setX(15);
$pdf->setY(92);
$pdf->setX(10);
$pdf->SetFont('Arial','B',8);    //Letra Arial, negrita (Bold), tam. 20

////////////////////////////////////////////////////////

$pdf->SetFillColor(204, 204, 204);
$pdf->SetTextColor(0,0,0);

//$pdf->Cell(30,5,"No. IDENTIFICACION NO",1,1,1,'C');


$pdf->setY(92);
$pdf->setX(10);
$pdf->Cell(11,5," Cant.",1,1,1,'C');

$pdf->setY(92);
$pdf->setX(21);
$pdf->Cell(25,5,"       U. Medida.",1,1,1,'C');

$pdf->setY(92);
$pdf->setX(46);
$pdf->Cell(85,5,utf8_decode("                                                Descripción"),1,1,1,'C');

$pdf->setY(92);
$pdf->setX(131);
$pdf->Cell(18,5,"   MODELO",1,1,1,'C');


$pdf->setY(92);
$pdf->setX(149);
$pdf->Cell(30,5,"               P.U",1,1,1,'C');

$pdf->setY(92);
$pdf->setX(179);
$pdf->Cell(21,5,"     TOTAL",1,1,1,'C');

$pdf->SetFont('Arial','',7);    //Letra Arial, negrita (Bold), tam. 20


$pdf->Ln();
$pdf->SetTextColor(0,0,0); // CAMBIO DE COLOR
$total =0;
$off = $textypos+8;
$pdf->setY(97);
$line = 87;
$ypos_static =105;
$cntxx=0;
$subtotal=0;
$iva=0;
$total_t=0;
foreach($products as $pro){

$id_p = $pro["id"];

include '/connection/conexion.php';
$sql_unit = "select product.unit,tblunidades_sat.name from product 
INNER JOIN tblunidades_sat on product.unit = tblunidades_sat.id
where product.id = $id_p";
$resultSet_unit = $mysqli->query($sql_unit);
$fila = $resultSet_unit->fetch_assoc();
$unit = $fila['name'];


$line+=9;
$ypos_static +=5;
$pdf->setX(10);
//$pdf->Cell(30,5,"       ".$product->code,'LR');
$pdf->Cell(11,5,"        ".$pro["qty"],'LR');
$pdf->Cell(25,5,substr($unit,0,200),'LR',0,'C');
$pdf->Cell(85,5,substr($pro["product"],0,200),'LR',0,'L');
$pdf->Cell(18,5,substr($pro["code"],0,40),'LR',0,'C');
$pdf->Cell(30,5,"$ ".number_format($pro["price"],2,".",",")."    (".$pro["dxp"]."%".")",'LR',0,'R');
$pdf->Cell(21,5,"$ ".number_format($pro["price_out"]*$pro["qty"],2,".",","),'LR',0,'R');
$pdf->Ln();
$cntxx++;



$total += round($pro["price_out"]*$pro["qty"],2);
$subtotal = round($total);
$iva = round($subtotal*0.16,2);
$total_t = round($subtotal+$iva);
$off+=8;
}
//$cntxx=$cntxx+$cntxx;
//pdf->setY($line+($cntxx*5));
$pdf->setX(10);
$pdf->Cell(190,0,'','T');
$line+=8;
//$pdf->setY($ypos_static);
$textypos=$off;


$pdf->setY($ypos_static);
$pdf->setX(10);
$pdf->Cell(190,0,"",1);

//////////////////////////////////////////////
$ypos_static+=5;
$line+=5;
$pdf->SetFont('Arial','',10);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setY($ypos_static);
$pdf->setX(160);
$pdf->Cell(5,6,"SUBTOTAL: " );

$pdf->setY($ypos_static);
$pdf->setX(196);
$pdf->Cell(5,6,"$symbol ".number_format($subtotal,2,".",","),0,0,"R");
$pdf->SetFont('Arial','',10);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setY($ypos_static+5);
$pdf->setX(160);
$pdf->Cell(5,6,"IVA: " );

$pdf->setY($ypos_static+5);
$pdf->setX(196);
$pdf->Cell(5,6,"$symbol ".number_format($iva,2,".",","),0,0,"R");

$pdf->SetFont('Arial','',9);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setY($ypos_static+10);
$pdf->setX(160);
$pdf->Cell(5,5,"DESCUENTO: " );

$pdf->setY($ypos_static+10);
$pdf->setX(196);
$pdf->Cell(5,5,"$symbol ".number_format($descuento,2,".",","),0,0,"R");




$pdf->SetFont('Arial','B',9);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setY($ypos_static+10);
$pdf->setX(160);
$pdf->Cell(5,15,"TOTAL: " );

$pdf->setY($ypos_static+10);
$pdf->setX(196);
$pdf->Cell(5,15,"$symbol ".number_format($total_t,2,".",","),0,0,"R");


$pdf->setY($ypos_static+20);
$pdf->setX(10);
$pdf->Cell(190,0,"",1);

$pdf->SetFont('Arial','B',9);    //Letra Arial, negrita (Bold), tam. 20
$pdf->SetTextColor(206,14,45); // CAMBIO DE COLOR

$pdf->setY($ypos_static+25);
$pdf->setX(10);
$pdf->Cell(190,5,"                                                              Nota: Precios sujetos a cambios sin previo aviso.");
$pdf->SetTextColor(0,0,0); // CAMBIO DE COLOR

$pdf->output();
