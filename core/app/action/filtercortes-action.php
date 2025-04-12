<?php
if (isset($_GET["start_at"]) && isset($_GET["finish_at"]) && $_GET["start_at"] != "" && $_GET["finish_at"] != "") {
    $suc =  $_GET['stock_id'];
    $inicio = $_GET['start_at'];
    $fin    = $_GET['finish_at'];
    $sql = "select * from box ";
    $whereparams[] = " ( date(created_at) between '$_GET[start_at]' and '$_GET[finish_at]' ) ";
    $sql2 = $sql . " where " . implode(" and ", $whereparams) . "  order by created_at desc";
    $boxes = BoxData::getAll($sql2,$suc);
}

//include '/connection/conexion.php';
include '/../../../connection/conexion.php';

//SACAMOS LOS GASTOS
$sql = "SELECT * FROM SPEND WHERE created_at between '$_GET[start_at] 00:00:00' and '$_GET[finish_at] 23:59:00' and  KIND = 2  ";
$resultado = $mysqli->query($sql);
$gastos="0";
while($row=mysqli_fetch_array($resultado)){
$gastos += $row['price'];}

//SACAMOS LOS ABONOS
$sql2 = "SELECT * FROM SPEND WHERE created_at between '$_GET[start_at] 00:00:00' and '$_GET[finish_at] 23:59:00' and KIND = 1 ";
$resultado2 = $mysqli->query($sql2);

$abonos="0";
while($row2=mysqli_fetch_array($resultado2)){
$abonos += $row2['price'];}

/*
//SACAMOS LOS ABONOS DE VENTAS CON ANTICIPO
$sql3 = "SELECT * FROM sell WHERE created_at between '$_GET[start_at] 00:00:00' and '$_GET[finish_at] 23:59:00' and f_id = 1 and p_id in (1,2)";
$resultado3 = $mysqli->query($sql3);
while($row3=mysqli_fetch_array($resultado3)){
$anticipo += $row3['reg_anticipo'];} */

// SAMOS LOS ANTICIPOS SOLAMENTE EN EFECTIVO QUE CUBREN A CAJA
$sql4 = "SELECT * FROM bitacora_abonos  where fecha between '$_GET[start_at] 00:00:00' and '$_GET[finish_at] 23:59:00' and operacion = 1 and forma_pago = 1  ";
$resultado4 = $mysqli->query($sql4);
while($row4=mysqli_fetch_array($resultado4)){
$anticipo_efectivo += $row4['cant_ingresada'];}

// SAMOS LOS ANTICIPOS SOLAMENTE EN EFECTIVO QUE CUBREN A CAJA
$sql5 = "SELECT * FROM bitacora_abonos where fecha between '$_GET[start_at] 00:00:00' and '$_GET[finish_at] 23:59:00' and operacion = 2 and forma_pago = 1  ";
$resultado5 = $mysqli->query($sql5);
while($row5=mysqli_fetch_array($resultado5)){
$abono_efectivo += $row5['cant_ingresada'];}

// SAMOS LOS ANTICIPOS SOLAMENTE EN EFECTIVO QUE CUBREN A CAJA
$sql6 = "SELECT * FROM CFDIS where fecha_registro between '$_GET[start_at] 00:00:00' and '$_GET[finish_at] 23:59:00' and tipo_factura = 1 and mpago = 1 and timbrado = 1 ";
$resultado6 = $mysqli->query($sql6);
while($row6=mysqli_fetch_array($resultado6)){
$factura_efectivo += $row6['Monto'];}


//SACAMOS LOS ABONOS DE VENTAS CON ANTICIPO
$sql7 = "SELECT * FROM sell WHERE fecha_pago between '$_GET[start_at] 00:00:00' and '$_GET[finish_at] 23:59:00' and remision_recuperada = 1 and f_id = 1 and p_id = 1 ";
$resultado7 = $mysqli->query($sql7);
$saldo_recuperado="0";
while($row7=mysqli_fetch_array($resultado7)){
$saldo_recuperado += $row7['reg_porpagar'];}



//SACAMOS LOS ABONOS DE VENTAS CON ANTICIPO
$sql8 = "SELECT * FROM sell WHERE created_at between '$_GET[start_at] 00:00:00' and '$_GET[finish_at] 23:59:00' and f_id = 1 and p_id = 1 and operation_type_id = 2  and credito_liquidado = 0 and remision_recuperada = 0 
and facturado = 0 and is_draft = 0 ";
$resultado8 = $mysqli->query($sql8);
$efectivo=0;
while($row8=mysqli_fetch_array($resultado8)){
$efectivo += $row8['total'];}

if(count($boxes)>0){
	$total_total = 0;
    ?>
<br>
<div class="card box-primary">
<div class="card-header">
Cortes de Caja
</div>
<div class="box box-primary table-responsive">
<table class="table table-bordered table-hover table-responsive datatable">
	<thead>

		<th  style="text-align: center">Sucursal</th>
		<th  style="text-align: center">Fecha Corte</th>
		<th  style="text-align: center">N° Corte</th>
		<th  style="text-align: center">Imprimir Corte</th>
	
	</thead>
	<?php foreach($boxes as $box):
	$sells = SellData::getByBoxId($box->id);
	?>
    <tr>
	

		<td  style="text-align: center"><?php echo $box->getStock()->name; ?></td>
		<td  style="text-align: center"><?php echo $box->created_at; ?></td>
		<td  style="text-align: center"><?php echo $box->id;?></td>

																																												
      
		<td  style="text-align: center;">
   <a target="_blank" href="corte_caja.php?id=<?php echo $box->id;?>&created_at=<?php echo $box->created_at;?>&sucursal=<?php echo $box->getStock()->id; ?>" class="btn btn-xs btn-prymari" ><i class="bi-ticket"></i></a>
   </td> 
		
	
	</tr>
<?php endforeach; ?>
</table>
</div>
</div>
	<?php
}else {

?>
<br>
	<div class="jumbotron">
		<h2>No hay Cortes con los filtros especificados en el formulario</h2>
	</div>

<?php } ?>
<br><br><br><br><br><br><br><br><br><br>
	</div>
</div>
</section>
