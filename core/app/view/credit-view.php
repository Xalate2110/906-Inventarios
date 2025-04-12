<section class="content">
<div class="row">
  <div class="col-md-12">

    <h1>Credito</h1>
    <h4>Lista de clientes con credito</h4>
<br>
    <?php

    $users = PersonData::getClientsWithCredit();
    if(count($users)>0){
      // si hay usuarios
      ?>


<div class="card box-primary">
  <div class="card-header">Credito</div>
<div class="card-body"> 
      <table class="table datatable table-bordered table-hover">
      <thead>
      <th style="text-align: center">Nombre completo</th>
      <th style="text-align: center">Direccion</th>
      <th style="text-align: center">Telefono</th>
      <th style="text-align: center">Credito</th>
      <th style="text-align: center">Saldo Pendiente</th>
      <th style="text-align: center">Acciones</th>
      </thead>
      <?php
      foreach($users as $user){
        ?>
        <tr>
        <style>
				.bi {font-size: 2em;}
				</style>
		
        <td style="text-align: center"><?php echo utf8_encode($user->name); ?></td>
        <td style="text-align: center"><?php echo utf8_encode($user->address1); ?></td>
        <td style="text-align: center"><?php echo $user->phone1; ?></td>
        <td style="text-align: center"><?php if($user->has_credit){ echo "<i class='bi bi-check'></i>"; }; ?></td>
        <td style="text-align: center">$ <?php
        $sells = SellData::getCreditsByClientId($user->id);
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
        <td style="text-align: center">
        <a href="index.php?view=makepayment&id=<?php echo $user->id;?>" class="btn btn-secondary btn-sm">Realizar Pago</a>
        <a href="index.php?view=paymenthistory&id=<?php echo $user->id;?>" class="btn btn-secondary btn-sm">Historial</a>
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