<?php
include '/connection/conexion.php';

if(isset($_FILES["name"])){
	$up = new Upload($_FILES["name"]);
	if($up->uploaded){
		$up->Process("./");
		if($up->processed){
if ( $file = fopen( "./" . $up->file_dst_name , "r" ) ) {

    $ok = 0;
    $almacen = $_POST["almacen"];
    $actualizados = 0;
    $registrados = 0;
    $error = 0;
    $products_array = array();
    $products_array2 = array();


    while($x=fgets($file,4096)){
    	if($_POST["kind"]==1){
        $data = explode(",", $x); 
        if(count($data)>=1){
        $ok++;
      
        $id_pro = $data[0];
        $precio = $data[4];
 
        
        $sql = "SELECT * FROM product WHERE code ='" . $id_pro . "'";
        $resultSet = $mysqli->query($sql);
        $fila = $resultSet->fetch_assoc();

        if(count($fila)> 0){
        $id_interno = $fila["id"];

        if($almacen !=0){
            
        $actualizados ++;
        $sql_xml_sintimbrar = "UPDATE `product` SET price_in = '" . $data[4] . "',price_out = '" . $data[5] . "',name = '" . $data[2] . "', description = '" . $data[3] . "' WHERE code = '".$id_pro."'";
        $mysqli->query($sql_xml_sintimbrar);
        $products_array2[]= array("id"=>$id_interno,"price_in"=>$data[4],"price_out"=>$data[5],"q"=>$data[7],"almacen"=>$almacen);
        }else{
        echo "no se hace nada";}
        
        }else{
        $registrados ++;
        $sql = "insert into product (code,barcode,name,description,price_in,price_out,inventary_min,price_out2,price_out3,price_out4,unit,presentation,codigo_sat,category_id,brand_id,user_id) "
        . "value (\"$data[0]\",\"$data[1]\",\"$data[2]\",\"$data[3]\",$data[4],$data[5],$data[6],$data[8],$data[9],$data[10],\"$data[11]\",\"$data[12]\",\"$data[13]\",\"$data[14]\",\"$data[15]\",$_SESSION[user_id])";
         echo $sql;
        $xy= Executor::doit($sql);
        $products_array[]= array("id"=>$xy[1],"price_in"=>$data[4],"price_out"=>$data[5],"q"=>$data[7],"almacen"=>$almacen);

       
         }
        
         }else{
        $error++;
        }
    	}// si estamos ingresando productos
      }
     }
		unlink("./".$up->file_dst_name);
	}
        
        }
    }

     Core::alert("Productos Subidos $ok , Productos Actualizados $actualizados, Productos Agregados $registrados Realizados con Exito, $error Errores en Importación");
    
     if(count($products_array)>0){
        foreach($products_array as $pa){ $total+=$pa["price_in"]; }
        echo $products_array;
        //$y = new YYData();
        //$yy = $y->add();
        $sell = new SellData();
       // $sell->ref_id= $yy[1];
        $sell->user_id = $_SESSION["user_id"];
        $sell->invoice_code = "";//$_POST["invoice_code"];
        $sell->p_id = 1;//$_POST["p_id"];
        $sell->d_id = 1;//$_POST["d_id"];
        $sell->f_id = 1;//$_POST["f_id"];
        $sell->subtotal = 0;
        $sell->iva = 0;
        $sell->total = $total;
        $sell->stock_to_id =  $pa["almacen"]; 
        $sell->person_id="NULL";
        $s = $sell->add_re(); 
     
        foreach($products_array as $pa){
         $op = new OperationData();
         $op->sell_id = $s[1] ;
         $op->product_id = $pa["id"];
         $op->stock_id = $pa["almacen"]; 
         $op->operation_type_id=OperationTypeData::getByName("entrada")->id;
         $op->price_in =$pa["price_in"];
         $op->price_out= $pa["price_out"];
         $op->q= $pa["q"];
         $op->sell_id="NULL";  
         $op->discount="0"; 
         $op->is_oficial=1;
         $op->add();
    }
    
  
    }


    if(count($products_array2)>0){
       foreach($products_array2 as $pa2){ $total+=$pa2["price_in"]; }
      //$y = new YYData();
      //$yy = $y->add();
      $sell = new SellData();
      //$sell->ref_id= $yy[1];
      $sell->user_id = $_SESSION["user_id"];
      $sell->invoice_code = "";//$_POST["invoice_code"];
      $sell->p_id = 1;//$_POST["p_id"];
      $sell->d_id = 1;//$_POST["d_id"];
      $sell->f_id = 1;//$_POST["f_id"];
      $sell->subtotal = 0;
      $sell->iva = 0;
      $sell->total = $total;
       $sell->stock_to_id =  $pa2["almacen"]; 
      $sell->person_id="NULL";
      $s = $sell->add_re(); 
     
        
       foreach($products_array2 as $pa2){
         $op = new OperationData();
         $op->sell_id = $s[1] ;
         $op->product_id = $pa2["id"] ;
         $op->stock_id = $pa2["almacen"]; 
         $op->operation_type_id=OperationTypeData::getByName("entrada")->id;
         $op->price_in =$pa2["price_in"];
         $op->price_out= $pa2["price_out"];
         $op->q= $pa2["q"];
         $op->sell_id="NULL";
         $op->discount="0"; 
         $op->is_oficial=1;
         $op->add();
    }
  }
      Core::redir("./?view=import");
  ?>