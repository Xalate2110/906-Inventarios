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
    $total_products = ProductData::getServicesStartLimit($start, $limit);
    $products = ProductData::getServicesLikeStartLimit($search["value"], $start, $limit);
  }else{
    $total_products = ProductData::getServices();
    $products = ProductData::getServicesStartLimit($start, $limit);

  }


}
//$pr = print_r($_GET, true);
$data = array();
?>


  <?php foreach($products as $product) {
    $btn1 ='<a target="_blank" href="index.php?action=productqr&id='.$product->id.'" class="btn btn-sm btn-secondary"><i class="bi-qr-code"></i></a>';
    $btn2 = '<a href="index.php?view=editservice&id='.$product->id.'" class="btn btn-sm btn-warning"><i class="bi-pencil"></i></a>';
    $btn3 = '<a href="index.php?view=delservice&id='.$product->id.'" class="btn btn-sm btn-danger"><i class="bi-trash"></i></a>';

//$data[]= array ($product->code, $product->name, $product->price_in, $product->price_out, $product->price_out2, $product->price_out3, "$product->category_id" , $btn1." ".$btn2." ".$btn3);
$data[]= array ($product->code, $product->name,$product->price_out,$btn1." ".$btn2." ".$btn3);
  }

echo json_encode(array(  "draw"=> $draw,
  "recordsTotal"=> count($total_products),
  "recordsFiltered"=> count($total_products),
  "data"=>$data));