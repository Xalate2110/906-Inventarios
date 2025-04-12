<?php
        $requestData = $_REQUEST;
        $draw = $requestData['draw'];
        $start = $requestData['start'];
        $limit = $requestData['length'];
        $search = $_GET["search"];

    if($limit==-1){

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
  $r=OperationData::getRByStock($product->id,$_GET["stock"]);
  $q=OperationData::getQByStock($product->id,$_GET["stock"]);
  $d=OperationData::getDByStock($product->id,$_GET["stock"]);

$cat_name="";
if($product->category_id){
  $cat = CategoryData::getById($product->category_id);
  $cat_name=$cat->name;
}
    $btn1 = '<a href="index.php?view=inventaryadd&product_id='.$product->id.'&stock='.$_GET['stock'].'" class="btn btn-sm btn-info" data-coreui-toggle="tooltip" data-coreui-placement="bottom" title="Agregar Unidades"><i class="bi-plus-square"></i></a>';
    $btn2 = '<a href="index.php?view=inventarysub&product_id='.$product->id.'&stock='.$_GET['stock'].'" class="btn btn-sm btn-danger" data-coreui-toggle="tooltip" data-coreui-placement="bottom" title="Restar Unidades"><i class="bi-dash-square"></i></a>';
    $btn3 = '<a href="index.php?view=history&product_id='.$product->id.'&stock='.$_GET['stock'].'" class="btn btn-sm btn-success" data-coreui-toggle="tooltip" data-coreui-placement="bottom" title="Historial de Movimientos"><i class="bi-clock"></i></a>';
    $data[]= array ($product->code, $product->name, "$cat_name" , $r, $q, $d, $btn1." ".$btn2." ".$btn3);

  }

   echo json_encode(array(  "draw"=> $draw,
  "recordsTotal"=> count($total_products),
  "recordsFiltered"=> count($total_products),
  "data"=>$data));