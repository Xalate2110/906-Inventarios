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
        }   
        

        $sql_precios = "SELECT * FROM price where product_id = $id and stock_id = $stock_id";
        $resultadoprecios = $mysqli->query($sql_precios);
       
        if($resultadoprecios->num_rows > 0){
        while($row = $resultadoprecios->fetch_assoc()){
        $p1= $row['price_out'];
        $p2= $row['price_out2'];
        $p3= $row['price_out3'];
        $p4= $row['price_out4'];
        }
     }else {
        $sql_precios2 = "SELECT * FROM product where id = $id";
        $resultadoprecios2 = $mysqli->query($sql_precios2);
        while($row = $resultadoprecios2->fetch_assoc()){
                $p1= $row['price_out'];
                $p2= $row['price_out2'];
                $p3= $row['price_out3'];
                $p4= $row['price_out4'];
                }
      } 
        
        echo json_encode(array("p1"=>$p1,"p2"=>$p2,"p3"=>$p3,"p4"=>$p4,"existencia"=>$q1));

?>












