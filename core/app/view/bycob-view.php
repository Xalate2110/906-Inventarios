<section class="content"> 
<div class="row">
	<div class="col-md-12">

  <?php 
  $sesion=$_SESSION["user_id"];
  ?>  

<!--
<?php if(isset($_SESSION["client_id"])):?>
		<h1><i class='glyphicon glyphicon-shopping-cart'></i> Ventas por Pagar</h1>
<?php else:?>


<div class="btn-group pull-right">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> Descargar <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
  
    <li><a href="report/bycob-word.php">Word 2007 (.docx)</a></li>
    <li><a href="report/bycob-xlsx.php">Excel 2007 (.xlsx)</a></li>
  
<li><a onclick="thePDF()" id="makepdf" class="">PDF (.pdf)</a></li>

  </ul>
</div>		
-->
<div class="panel panel-default">
<div class="panel-heading">
<h1><i class='glyphicon glyphicon glyphicon-shopping-cart'></i> Listado General Ventas Por Cobrar</h1>
</div>
</div>



<?php endif;?>
<div class="clearfix"></div>
<?php

$products=null;
if(isset($_SESSION["user_id"])){

if(Core::$user->kind==3){
$products = SellData::getSellsToCobByUserId(Core::$user->id);
}
else if(Core::$user->kind==2|| Core::$user->kind==4){
$products = SellData::getSellsToCobByStockId(Core::$user->stock_id);
}
else{
$products = SellData::getSellsToCob();

}
}else if(isset($_SESSION["client_id"])){
$products = SellData::getSellsByClientId($_SESSION["client_id"],0,1);	
}

if(count($products)>0){
?>

  <?php 
  $total_pe =0;
  $total_an=0;
  $total_ant=0;
  foreach($products as $sell):?>
  <?php 
  $total= $sell->total;  
  $total_pe += $total;

  // anticipos
  $total_an = $sell->anticipo_venta;
  $total_ant += $total_an;
  ?>

  <?php endforeach; ?>


<br>

    <div class="card box-primary">
   <div class="card-header">
   <tr>
   <td> <h6  style="text-align: right;font-size:16px;background:yellow;color:black">Total Pendiente Por Cobrar $ : <?php echo number_format($total_pe,2, '.', ',') ?></h6> </td>
   <td> <h6  style="text-align: right;font-size:16px;color:green">Total Anticipos Recibidos $ : <?php echo number_format($total_ant,2, '.', ',') ?></h6> </td>
   </tr></div></div>

<br>

	<div class="card box-primary">
<div class="card-header">
<span class="box-title"><b>Litado Ventas Por Cobrar</b></span></div>
<div class="card-body">
<table class="table datatable table-bordered table-hover	">

	<thead>
		<th style="text-align: center;">Detalles</th>
		<th style="text-align: center;">Folio</th>
		<th style="text-align: center;">Nombre Cliente</th>
		<th style="text-align: center;">Pago</th>
		<th style="text-align: center;">Entrega</th>
		<th style="text-align: center;">Pendiente</th>
    <th style="text-align: center;">Anticipo</th>
    <th style="text-align: center;">Total</th>
    <th style="text-align: center;">Almacen</th>
		<th style="text-align: center;">Fecha</th>
		<th style="text-align: center;">Acciones</th>
	</thead>
	<?php foreach($products as $sell):?>

    <?php
     include '/connection/conexion.php';
    $sql = "SELECT CONCAT(person.name, person.lastname) AS CLIENTE from sell 
    inner join person on
    person_id = person.id and sell.id = $sell->id";
    $resultado = $mysqli->query($sql);
    while($row=mysqli_fetch_array($resultado)){
    $nombrecliente = $row[0];}?>


	<tr>
		<td style="text-align: center;">
		<a href="index.php?view=onesell&id=<?php echo $sell->id; ?>" class="btn btn-sm btn-secondary"><i class="bi-eye"></i></a></td>
		<td style="text-align: center;">R - <?php echo $sell->id; ?></td>
    <td style="text-align: center;">
    <?php echo $nombrecliente; ?>
    </td>
    <td style="text-align: center;"><?php echo $sell->getP()->name; ?></td>
    <td style="text-align: center;"><?php echo $sell->getD()->name; ?></td>
    <td style="text-align: center;">
<?php
    $total= $sell->total;
    echo "<b>".Core::$symbol." ".number_format($sell->total_por_pagar,2,".",",")."</b>";
   ?>			
		</td>
    <td style="text-align: center;">
    <?php
		echo "<b>".Core::$symbol." ".number_format($sell->anticipo_venta,2,".",",")."</b>";
   ?>			
		</td>
    <td style="text-align: center;">
    <?php
     $porpagar = $sell->total_por_pagar;
		echo "<b>".Core::$symbol." ".number_format($total,2,".",",")."</b>";
    ?>			
		</td>

 


    <td style="text-align: center;"><?php echo $sell->getStockTo()->name; ?></td>
		<td style="text-align: center;"><?php echo $sell->created_at; ?></td>
		<td style="text-align: center;width:200px">
    <?php if(isset($_SESSION["user_id"])):?>
    
      <?php if($sell->stock_to_id == '1') { ?>
		<a  target="_blank" href="ticket_athena.php?id=<?php echo $sell->id; ?>" class="btn btn-sm btn-secondary"><i class='bi-ticket'></i></a>
        <?php } else if($sell->stock_to_id == '2') {  ?>
        <a  target="_blank" href="ticket_la_reyna.php?id=<?php echo $sell->id; ?>" class="btn btn-sm btn-secondary"><i class='bi-ticket'></i></a>
        <?php } else if($sell->stock_to_id == '3') { ?>
        <a  target="_blank" href="ticket_cabrera.php?id=<?php echo $sell->id; ?>" class="btn btn-sm btn-secondary"><i class='bi-ticket'></i></a>
        <?php } else if($sell->stock_to_id == '4') { ?>
        <a  target="_blank" href="ticket_perez_prado.php?id=<?php echo $sell->id; ?>" class="btn btn-sm btn-secondary"><i class='bi-ticket'></i></a>
        <?php }?>
      <?php if(Core::$user->kind==1 || Core::$user->kind==4):?>
		<a href="./?action=pay2&id=<?php echo $sell->id; ?>&fecha=<?php echo $sell->created_at; ?>&porpagar=<?php echo $sell->total_por_pagar?>&anticipo=<?php echo $sell->anticipo_venta?>&total=<?php echo $sell->total?>" class="btn btn-xs btn-primary" onclick="return generarcobro();"><i class="bi bi-arrow-right-circle-fill"></i></a>
	  <a href="index.php?view=delsell&id=<?php echo $sell->id; ?>&usuario=<?php echo $sesion; ?>" class="btn btn-xs btn-danger" onclick="return cancelar();"><i class="bi-trash"></i></a> 
    <?php endif; ?>
    <?php endif;?>
    <script>




function generarcobro() {
if (confirm("¿Deseas recuperar la Remisión con el Folio  -- " + <?php echo $sell->id ?> + " -- Al recuperarla el sistema detectara si Ingresa como Saldo Recuperado O Venta Del Día")) {
return true;
} else {
return false;
}
}
</script>

<script>
function cancelar() {
if (confirm("¿Seguro que deseas cancelar el cobro a la venta?")) {
return true;
} else {
return false;

}
}
</script>

	</tr>

<?php endforeach; ?>

</table>

</div>
</div>


<div class="clearfix"></div>

	<?php
}else{
	?>
	<div class="jumbotron">
		<h2>No hay ventas por cobrar registradas en el sistema.</h2>
		<p>No se cuentan con registros</p>
	</div>
	<?php
}

?>
<br><br><br><br><br><br><br><br><br><br>
	</div>
</div>
</section>

