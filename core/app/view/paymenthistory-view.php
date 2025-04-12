<?php
$client = PersonData::getById($_GET["id"]);
$total = PaymentData::sumByClientId($client->id)->total;
$compra = $_GET['compra'];

?>

<section class="content">
<div class="row">
  <div class="col-md-12">

    <h1>Historial de pagos y credito</h1>
    <h4>Cliente: <?php echo utf8_encode($client->name." ".$client->lastname); ?></h4>
    <!--<a href="./?view=credit" class="btn btn-outline-dark"> <i class="fa fa-arrow-left"></i> Regresar</a> -->
    <!-- <a href="./report/paymenthistory-word.php?id=<?php echo $client->id; ?>" class="btn btn-outline-dark"> <i class="fa fa-file-text"></i> Descargar Word (.docx)</a> -->
   <!-- <a href="./report/paymenthistory-excel.php?id=<?php echo $client->id; ?>" class="btn btn-outline-dark"> <i class="fa fa-file-text"></i> Descargar Excel (.xlsx)</a> -->
<br>
    <?php

    $users = PaymentData::getAllByClientId($_GET["id"],$compra);
    if(count($users)>0){
      // si hay usuarios
      ?>
<div class="card box-primary">
  <div class="card-header">Abonos a Crédito</div>
  <div class="card-body">
      <table class="table datatable table-bordered table-hover">
      <thead>
      <th style="text-align: center">Tipo Movimiento</th>
      <th style="text-align: center">Valor</th>
      <!--<th style="text-align: center">Saldo</th> -->
      <th style="text-align: center">Fecha Aplicación Movimiento</th>
      <th style="text-align: center">Acciones</th>
      </thead>
      <?php
      foreach($users as $user){
        ?>
        <tr>
        <td style="text-align: center"><?php echo $user->getPaymentType()->name; ?></td>
        <td style="text-align: center">$ <?php echo number_format(abs($user->val),2,".",",");?></td>
        <!--<td style="text-align: center">$ <?php echo number_format($total,2,".",",");?></td> -->
        <td style="text-align: center"><?php echo $user->created_at; ?></td>
        <td style="text-align: center">
        <?php if($user->payment_type_id==2):?>
        <a href="index.php?action=delpayment&id=<?php echo $user->id;?>" class="btn btn-danger btn-xs">Eliminar</a>
      <?php endif; ?>
        </td>
        </tr>
        <?php
        $total -=$user->val;

      }?>
      </table>
      </div>
    </div>
      <?php
    }else{
      echo "<p class='alert alert-danger'>No hay clientes</p>";
    }


    ?>


  </div>
</div>
</section>