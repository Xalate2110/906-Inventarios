<?php
error_reporting(0);
if (count($_POST) > 0) {
    $product = new ProductData();
    $stock->stock = $_POST["stock"];
    $product->kind = $_POST["kind"];
    $product->code = $_POST["code"];
    $product->barcode = $_POST["barcode"];
    $product->multiplo = $_POST["multiplo"];
    $product->name = $_POST["name"];
    $product->price_in = $_POST["price_in"];

    $product->price_out = $_POST["price_out"];
    $product->price_out2 = $_POST["price_out2"];
    $product->price_out3 = $_POST["price_out3"];
    $product->price_out4 = $_POST["price_out4"];
    $product->unit = $_POST["unit"];
    $product->description = $_POST["description"];
    $product->presentation = $_POST["presentation"];
    $product->codigo_sat = $_POST["codigo_sat"];
    $product->objetoimp = $_POST["objetoimp"];
   
    
    $product->width =  0;
    $product->height = 0;
    $product->weight = 0;

    //$product->expire_at = $_POST["expire_at"];
    $product->inventary_min = $_POST["inventary_min"];
    $product->brand_id = $_POST["brand_id"] != "" ? $_POST["brand_id"] : "NULL";
    $product->category_id = $_POST["category_id"] != "" ? $_POST["category_id"] : "NULL";
    $product->inventary_min = $_POST["inventary_min"] != "" ? $_POST["inventary_min"] : "10";

//  $product->category_id=$category_id;
//  $product->inventary_min=$inventary_min;
    $product->user_id = $_SESSION["user_id"];

    if (isset($_FILES["image"])) {
        $image = new Upload($_FILES["image"]);
        if ($image->uploaded) {
            $image->Process("storage/products/");
            if ($image->processed) {
                $product->image = $image->file_dst_name;
            }
        }
    }

    include '/connection/conexion.php';
    $sql2 = "SELECT * FROM product WHERE code='$product->code' and barcode='$product->barcode' and name ='$product->name' and description ='$product->description'";
    $result=$mysqli->query($sql2); 
    $row_cnt = $result->num_rows;
    if($row_cnt>0){
    Core::alert("No puede registrar el producto, debido a que ya se cuenta con una información igual registrada en el sistema.");
    print "<script>window.location='index.php?view=products&opt=all&&stock=$stock->stock';</script>";
    }else {
    $prod = $product->add();
    }



 

//print_r($_POST);
//echo $_POST["q"];
    if ($_POST["kind"] == "1" || $_POST["kind"] == "2") {
        if ($_POST["q"] != "" || $_POST["q"] > "0") {

            //$y = new YYData();
            //$yy = $y->add();
            $sell = new SellData();
            $sell->ref_id = "NULL";
            $sell->user_id = $_SESSION["user_id"];
            $sell->invoice_code = ""; //$_POST["invoice_code"];
            $sell->p_id = 1; //$_POST["p_id"];
            $sell->d_id = 1; //$_POST["d_id"];
            $sell->f_id = 1; //$_POST["f_id"];
            $sell->total = $_POST["q"] * $_POST["price_in"];
            $sell->stock_to_id = StockData::getPrincipal()->id; //$_POST["stock_id"];
            $sell->person_id = "NULL";

            $s = $sell->add_re();

            $op = new OperationData();
            $op->sell_id = $s[1];
            $op->product_id = $prod[1];
            $op->stock_id = StockData::getPrincipal()->id;
            $op->operation_type_id = OperationTypeData::getByName("entrada")->id;
            $op->price_in = $_POST["price_in"];
            $op->price_out = $_POST["price_out"];
            $op->q = $_POST["q"];
            $op->discount=0;
            //$op->sell_id="NULL";
            $op->is_oficial = 1;
            $op->add();
        }
    }

     	




    if(isset($_GET["opt"]) && $_GET["opt"]=="service"){
        Core::alert("Se ha registrado el producto / servicio correctamente en el sistema, para dar existancia al producto necesitas ingresar al modulo de compras y generar una compra.");
        print "<script>window.location='index.php?view=services&opt=all&&stock=$stock->stock';</script>";
        
        }else{
        Core::alert("Se ha registrado el producto / servicio correctamente en el sistema, para dar existancia al producto necesitas ingresar al modulo de compras y generar una compra.");
        print "<script>window.location='index.php?view=products&opt=all&&stock=$stock->stock';</script>";
        
        }
}
?>