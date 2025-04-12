<?php
        $requestData = $_REQUEST;
        $draw = $requestData['draw'];
        $start = $requestData['start'];
        $limit = $requestData['length'];
        $search = $_GET["search"];


if($limit==-1){
/*
  if(!empty($search["value"])){
    $products = ProductData::getLike($search["value"]);
  }else{
    $products = ProductData::getAll();

  }
*/
}else{


    $total_products = ProductData::getProducts2();
    $products = ProductData::getProductsStartLimit2($start, $limit);
   
}

    //$pr = print_r($_GET, true);
   $data = array();
   ?>
   <?php foreach($products as $product) {
   $q=OperationData::getQByStock($product->id,$_GET["stock"]);
  
    $btn1 ="";//'<a target="_blank" href="index.php?action=generatebarcode&id='.$product->id.'" class="btn btn-sm btn-secondary"><i class="bi-border-all"></i></a>';
    $btn2 ="";//'<a target="_blank" href="product-barcode.php?id='.$product->id.'" class="btn btn-sm btn-secondary"><i class="bi-border-all"></i></a>';
    $btn3 ="";//'<a target="_blank" href="index.php?action=productqr&id='.$product->id.'" class="btn btn-sm btn-secondary"><i class="bi-qr-code"></i></a>';
    $btn4 = '<a href="index.php?view=editproduct&id='.$product->id.'&stock='.$_GET['stock'].'" class="btn btn-sm btn-warning"><i class="bi-pencil"></i></a>';
   

  $data[]= array ($product->code, $product->name,$q,$product->price_in,$product->price_out,$btn1." ".$btn2." ".$btn3." ".$btn4);
  }

  

   echo json_encode(array( "draw"=> $draw,
  "recordsTotal"=> count($total_products),
  "recordsFiltered"=> count($total_products),
  "data"=>$data));