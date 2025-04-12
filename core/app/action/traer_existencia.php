<?php

include '../../../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
$id = $_POST["id"];
$stock_id = $_POST["stock_id"];

    $sql_conceptos = "select q,operation_type_id,sell_id,created_at from operation where product_id = '" . $id . "' and stock_id= '" . $stock_id . "'  and is_draft=0 and status=1 ";
    $resultado = $mysqli->query($sql_conceptos);
    $q1=0;
    while($mostrar=mysqli_fetch_array($resultado)){
    if($mostrar["operation_type_id"]=="1" || $mostrar["operation_type_id"]=="10" || $mostrar["operation_type_id"]=="5"){ 
     $q1+=$mostrar["q"]; 
    }else if ($mostrar["operation_type_id"]=="2" || $mostrar["operation_type_id"]=="9" || $mostrar["operation_type_id"]=="11" || $mostrar["operation_type_id"]=="13"){
    $q1+=(-$mostrar["q"]);    
    }else if($mostrar["operation_type_id"]=="12"){
    $q1=($mostrar["q"]);
    } 
}   //returns data as JSON format
 


    $sql_conceptos2 = "SELECT sell.id,sell.created_at,operation.product_id,operation.q,operation.operation_type_id from sell 
    inner join operation on sell.id = operation.sell_id where operation.product_id = '" . $id . "'  and sell.stock_to_id = '" . $stock_id . "' ";
    $resultado2 = $mysqli->query($sql_conceptos2);
    
    $q2=0;
    
    while($mostrar=mysqli_fetch_array($resultado2)){
    if($mostrar["operation_type_id"]=="4"){ 
     $q2+=$mostrar["q"]; 
     }else if($mostrar["operation_type_id"]=="13"){
     $q2+=(-$mostrar["q"]);
  }
}   //returns data as JSON format


   echo json_encode(array("existencia"=>$q1,"por_entregar"=>$q2));

?>












