<?php
include '/connection/conexion.php';
$fecha_venta = $_GET['fecha'];

$id = $_GET['id'];
$total_venta = $_GET['total'];
$porpagar = $_GET['porpagar'];
$anticipo = $_GET['anticipo'];
$fechaActual = date('d-m-Y');

if(strtotime($fecha_venta) < strtotime($fechaActual)) {
  // SE LE SUMA EL ANTICIPO A LO RESTANTE, PARA DESPUES HACER LA OPERACION DE LIQUIDACION.
   $sql_anticipo = "update sell set anticipo_venta = 0 where id = $id ";
   $mysqli->query($sql_anticipo);

   $sql_liquida = "update sell set total_por_pagar = 0 where id = $id";
   $mysqli->query($sql_liquida);

   $sql_recuperada = "update sell set remision_recuperada = 1 where id = $id";
   $mysqli->query($sql_recuperada);

   $sql_fechapago = "update sell set fecha_pago = NOW() where id = $id";
   $mysqli->query($sql_fechapago);

   echo'<script type="text/javascript">
   alert("Se ha recuperado Una Remision con fecha posterior al Día de Hoy, por lo cual se ingresara como Saldo Recuperado. El sistema aplicará el movimiento Automaticamente.");
   </script>';

}else if($fechaActual == $fecha_venta) {
  echo "";

} else {
      // SE LE SUMA EL ANTICIPO A LO RESTANTE, PARA DESPUES HACER LA OPERACION DE LIQUIDACION.
      $sql_anticipo2 = "update sell set anticipo_venta = 0 where id = $id ";
      $mysqli->query($sql_anticipo2);

     // SE GENERA LA OPERACION PARA LIQUIDAR LA CUENTA. 
     $sql_liquida2 = "update sell set total_por_pagar = 0 where id = $id";
     $mysqli->query($sql_liquida2);

     // SI ES DEL MISMO DIA NO ENTRA COMO REMISION RECUPERADA.
     $sql = "update sell set remision_recuperada = 0  where id = $id ";
     $mysqli->query($sql);   

   // SI LA REMISION SE RECUPERA EL MISMO DÍA SE QUITA EL ANTICIPO DE LA COMPRA 
      // PARA QUE SOLAMENTE SE INGRESE EL TOTAL DE LA COMRA
      $sql2 = "update sell set reg_anticipo = 0  where id = $id ";
      $mysqli->query($sql2);  

      $sql_fechapago2 = "update sell set fecha_pago = NOW() where id = $id";
      $mysqli->query($sql_fechapago2);

   echo'<script type="text/javascript">
   alert("Se ha recuperado una Remisión Por Pagar con la Fecha Del Día de hoy, por lo cual entrará como Venta Normal Del Día. El sistema aplicará el movimiento Automaticamente.");

   </script>';
}


if(isset($_GET["id"])){
$sell = SellData::getById($_GET["id"]);
$sell->p_id=1;
$sell->update_p();
Core::redir("./?view=bycob");
} 
?>