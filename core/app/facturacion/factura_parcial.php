    <?php
    include '../../../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");


    $productos = $_POST['productos'];
    $id_venta   = $_POST['id_venta'];

    $sq1 = "update operation set facturado = 1 where sell_id ='" . $id_venta . "' and product_id in ($productos)";
    $mysqli->query($sq1); 
    echo "1";    
   ?>