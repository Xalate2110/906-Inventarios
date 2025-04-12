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

  if(!empty($search["value"])){
    $total_products = ProductData::getProductsStartLimit($start, $limit);
    $products = ProductData::getProductsLikeStartLimit($search["value"], $start, $limit);
  }else{
    $total_products = ProductData::getProducts();
    $products = ProductData::getProductsStartLimit($start, $limit);
   }
}

    //$pr = print_r($_GET, true);
   $data = array();
   ?>
   <?php foreach($products as $product) {
   $q=OperationData::getQByStock($product->id,$_GET["stock"]);
   
  $cat_name="";
  if($product->category_id){
  $cat = CategoryData::getById($product->category_id);
  $cat_name=$cat->name;
}
    $btn1 ="";//'<a target="_blank" href="index.php?action=generatebarcode&id='.$product->id.'" class="btn btn-sm btn-secondary"><i class="bi-border-all"></i></a>';
    $btn2 ="";//'<a target="_blank" href="product-barcode.php?id='.$product->id.'" class="btn btn-sm btn-secondary"><i class="bi-border-all"></i></a>';
    $btn3 ="";//'<a target="_blank" href="index.php?action=productqr&id='.$product->id.'" class="btn btn-sm btn-secondary"><i class="bi-qr-code"></i></a>';
    $btn4 = '<a href="index.php?view=editproduct&id='.$product->id.'&stock='.$_GET['stock'].'" class="btn btn-sm btn-warning"><i class="bi-pencil"></i></a>';
    $btn5 = '<a href="index.php?view=delproduct&id='.$product->id.'&stock='.$_GET['stock'].'" class="btn btn-sm btn-danger"><i class="bi-trash"></i></a>';

  $data[]= array ($product->code, $product->name,$q,$product->price_in,$product->price_out,$btn4." ".$btn5);
  }

  

   echo json_encode(array( "draw"=> $draw,
  "recordsTotal"=> count($total_products),
  "recordsFiltered"=> count($total_products),
  "data"=>$data));