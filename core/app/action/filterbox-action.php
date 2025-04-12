<?php
    $products = null;

if (isset($_GET["start_at"]) && isset($_GET["finish_at"]) && $_GET["start_at"] != "" && $_GET["finish_at"] != "") {
    $alm    = $_GET['almacen'];
    $inicio = $_GET['start_at'];
    $fin    = $_GET['finish_at'];
    $sql = "select * from sell where operation_type_id=2 and  box_id is NULL and p_id=1 and remision_recuperada in (0,1) and facturado = 0 and credito_liquidado=0  and f_id = 1 and is_draft=0 AND stock_to_id=$alm ";
    $whereparams[] = " ( date(created_at) between '$_GET[start_at]' and '$_GET[finish_at]' ) ";
    $sql2 = $sql . " and " . implode(" and ", $whereparams) . " order by created_at desc";
    $products = SellData::getAllBySQL3($sql2);
}


    

include '/connection/conexion.php';


//SACAMOS LOS GASTOS
$sql = "SELECT * FROM SPEND WHERE created_at between '$_GET[start_at] 00:00:00' and '$_GET[finish_at] 23:59:00' and  KIND = 2  ";
$resultado = $mysqli->query($sql);

$abonos=0;
$gastos=0;
while($row=mysqli_fetch_array($resultado)){
$gastos += $row['price'];}

//SACAMOS LOS ABONOS
$sql2 = "SELECT * FROM SPEND WHERE created_at between '$_GET[start_at] 00:00:00' and '$_GET[finish_at] 23:59:00' and KIND = 1 ";
$resultado2 = $mysqli->query($sql2);
while($row2=mysqli_fetch_array($resultado2)){
$abonos += $row2['price'];}


//SACAMOS LOS ABONOS DE VENTAS CON ANTICIPO
$sql3 = "SELECT * FROM sell WHERE created_at between '$_GET[start_at] 00:00:00' and '$_GET[finish_at] 23:59:00'";
$resultado3 = $mysqli->query($sql3);

$anticipo=0;
while($row3=mysqli_fetch_array($resultado3)){
$anticipo += $row3['reg_anticipo'];} 

// SAMOS LOS ANTICIPOS SOLAMENTE EN EFECTIVO QUE CUBREN A CAJA
$sql4 = "SELECT * FROM bitacora_abonos  where fecha between '$_GET[start_at] 00:00:00' and '$_GET[finish_at] 23:59:00' and operacion = 1 and forma_pago = 1  ";
$resultado4 = $mysqli->query($sql4);

$anticipo_efectivo=0;
while($row4=mysqli_fetch_array($resultado4)){
$anticipo_efectivo += $row4['cant_ingresada'];}

// SAMOS LOS ANTICIPOS SOLAMENTE EN EFECTIVO QUE CUBREN A CAJA
$sql5 = "SELECT * FROM bitacora_abonos where fecha between '$_GET[start_at] 00:00:00' and '$_GET[finish_at] 23:59:00' and operacion = 2 and forma_pago = 1  ";
$resultado5 = $mysqli->query($sql5);
$abono_efectivo=0;
while($row5=mysqli_fetch_array($resultado5)){
$abono_efectivo += $row5['cant_ingresada'];}

// SAMOS LOS ANTICIPOS SOLAMENTE EN EFECTIVO QUE CUBREN A CAJA

$sql_factura_efectivo = "SELECT SUM(monto) as total FROM cfdis 
inner join sell on sell.id = cfdis.Folio_venta 
where cfdis.mpago = 1 and fecha_registro between '$_GET[start_at] 00:00:00' and '$_GET[finish_at] 23:59:00'
and tipo_factura = 1  and timbrado = 1 
and id_deposito = 0 and sell.pendiente = 0 and sell.r_credito = 0";
$resultado_factura = $mysqli->query($sql_factura_efectivo);
$row10 = $resultado_factura -> fetch_assoc();


//SACAMOS LOS ABONOS DE VENTAS CON ANTICIPO
$sql7 = "SELECT * FROM sell WHERE fecha_pago between '$_GET[start_at] 00:00:00' and '$_GET[finish_at] 23:59:00' and remision_recuperada = 1 and p_id = 1 and f_id = 1 ";
$resultado7 = $mysqli->query($sql7);
$saldo_recuperado=0;
while($row7=mysqli_fetch_array($resultado7)){
$saldo_recuperado += $row7['reg_porpagar'];}


if(count($products)>0){
    $total_total = 0;
?>
    <br>
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Ventas pendientes para asignar corte de caja.</h3>
        </div>
        <table class="table table-bordered table-hover	">
            <thead>
                <th style="text-align: center">Folio </th>
                <th style="text-align: center"># Productos</th>
                <th style="text-align: center">Total Remision</th>
                <th style="text-align: center">Almacen Venta</th>
                <th style="text-align: center">Fecha Venta</th>
                <th style="text-align: center">Usuario Genero</th>
                <th style="text-align: center">Nombre Cliente</th>
                <th></th>
            </thead>
            <?php foreach($products as $sell):?>
            <tr>
                <td style="text-align: center"><?php echo $sell->id?></td>
                <td>
                <?php
                    $operations = OperationData::getAllProductsBySellId($sell->id);
                    echo count($operations);
                    $sql7 = "SELECT user.name,CONCAT(person.name,' ',person.lastname) as nombre_cliente FROM sell 
                    INNER JOIN person on person.id = sell.person_id 
                    INNER JOIN user on user.id = sell.user_id 
                    where sell.id = '".$sell->id."'";
                    $resultado7 = $mysqli->query($sql7);
                    while($row7=mysqli_fetch_array($resultado7)){
                    $usuario = $row7['name'];
                    $cliente = $row7['nombre_cliente'];}
                    ?>                
                </td>
                <td style="text-align: center">
                <?php
                    $total_total += $sell->total;
                    echo "<b>".Core::$symbol." ".number_format($sell->total,2,".",",")."</b>";
                ?>			
                </td>
                <td style="text-align: center"><?php echo $sell->getStockTo()->name; ?></td>
                <td style="text-align: center"><?php echo $sell->created_at; ?></td>
                <?php 
                ?>
                <td style="text-align: center"><?php echo $usuario; ?></td>
                <td style="text-align: center"><?php echo utf8_encode($cliente); ?></td>
                <td></td>
            </tr>
            <?php endforeach; ?>

            <tr>
               <th style="background:red"></th>
               <th style="background:red"></th>
               <th style="background:red"></th>
               <th style="background:red"></th>
               <th style="background:red"></th>
               <th style="background:red"></th>
               <th style="background:red"></th>
               <th style="background:red"></th>
               
        
        
            <tr>

            
            <tr>
               <th style="background:yellow">Venta Del Día  : <?php echo Core::$symbol." ".number_format($total_total,2,".",","); ?></th>
               <th style="background:yellow">Retiros Caja  : <?php echo Core::$symbol." ".number_format($gastos,2,".",","); ?></th>
               <th style="background:yellow">Abonos  Caja : <?php echo Core::$symbol." ".number_format($abonos,2,".",","); ?></th>
               <th style="background:yellow">Ventas Con Anticipo   : <?php echo Core::$symbol." ".number_format($anticipo,2,".",","); ?></th>
               <th style="background:yellow">Abonos Efectivo   : <?php echo Core::$symbol." ".number_format($abono_efectivo,2,".",","); ?></th>
               <th style="background:yellow">Anticipos Efectivo   : <?php echo Core::$symbol." ".number_format($anticipo_efectivo,2,".",","); ?></th>
               <th style="background:yellow">Facturado Efectivo   : <?php echo Core::$symbol." ".number_format($row10['total'],2,".",","); ?></th>
               <th style="background:yellow">Saldo Recuperado   : <?php echo Core::$symbol." ".number_format($saldo_recuperado,2,".",","); ?></th>
            <tr> 
            
        </table>
    </div>
 
        <?php 
       $total_general = ($total_total + $abonos+ $anticipo + $anticipo_efectivo + $abono_efectivo + $row10['total'] + $saldo_recuperado) - $gastos ; 


        ?>

    <h1>EFECTIVO AL MOMENTO: <?php echo Core::$symbol." ".number_format($total_general,2,".",","); ?></h1> 

<?php
} else {
?>
   <br>
    <div class="jumbotron">
        <h2>No hay ventas</h2>
        <p>No se ha realizado ninguna venta en la fecha seleccionada</p>
    </div>
<?php
}
?>