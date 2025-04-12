<section class="content">
<div class="row">
  <div class="col-md-12">

    <h1>Credito</h1>
    <h4>Lista de proveedores con Crédito pendiente</h4>
<br>
    <?php

    $users = PersonData::getClientsWithCredit2();
    if(count($users)>0){
      // si hay usuarios
      ?>

    <!-- <a href="./report/credit-word.php" class="btn btn-default"> <i class="fa fa-file-text"></i> Descargar Word (.docx)</a> -->
    <a href="./report/credit-excel.php" class="btn btn-outline-dark"> <i class="fa fa-file-text"></i> Descargar Excel (.xlsx)</a>
<br><br>
<div class="card box-primary">
  <div class="card-header">Credito</div>
<div class="card-body"> 
      <table class="table datatable table-bordered table-hover">
      <thead>
      <th>Nombre completo</th>
      <th>Direccion</th>
      <th>Telefono</th>
      <th>Credito</th>
      <th>Saldo Pendiente</th>
      <th></th>
      </thead>
      <?php
      foreach($users as $user){
        ?>
        <tr>
        <td><?php echo utf8_encode($user->name); ?></td>
        <td><?php echo utf8_encode($user->address1); ?></td>
        <td><?php echo $user->phone1; ?></td>
        <td><?php if($user->has_credit){ echo "<i class='fa fa-check'></i>"; }; ?></td>
        <td>$ <?php
        $sells = SellData::getCreditsByClientId2($user->id);
        $total=0;
        foreach ($sells as $sell) {
        $tx = PaymentData::sumBySellId($sell->id)->total;
        if($tx>0){
        $total+=$tx;
        }
        }
        echo $total;

        //echo number_format(PaymentData::sumByClientId($user->id)->total,2,".",","); 
         ?></td>
        <td style="width:230px;">
        <a href="index.php?view=makepaymentp&id=<?php echo $user->id;?>" class="btn btn-secondary btn-sm">Realizar Pago</a>
        <a href="index.php?view=paymenthistoryp&id=<?php echo $user->id;?>" class="btn btn-secondary btn-sm">Historial</a>
        </td>
        </tr>
        <?php

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