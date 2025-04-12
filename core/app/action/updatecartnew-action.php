<?php
/*
$cart = $_SESSION['cart'];
$newcart = array();

$descuento =0;
$descuento1=0;
$precio=0;
$op = 100;
$p_porcentaje = isset($_GET['p_porcentaje'])?1:0;
$des_p = round($_GET["discount"] / $op,2);
foreach($cart as $cx){

	if($cx["product_id"]==$_GET['product_id']){




		if($p_porcentaje=="1"){
        $descuento = ($_GET["price_out"] * $_GET["discount"])/ $op;
		
        $cx["q"] = $_GET['q'];
		$cx["discount"] = $descuento;
		$cx["descuento_p"] = $des_p;
		$cx["price"] = $_GET["price_out"];
		$use_price2 = isset($_GET['use_price2'])?1:0;
		}

		if($use_price2=="1"){
		
		if($p_porcentaje=="1"){
        $descuento1 = ($_GET["price_opt"] * $_GET["discount"])/ $op;
		
		$cx["use_price2"] = $use_price2;
		$cx["discount"] = $descuento1;
		$cx["descuento_p"] = $des_p;
		}
		}else if ($use_price2=="0"){
		$cx["use_price2"] = $use_price2;
		}

		if($_GET['price_opt']!=""){
		$cx["price_opt"] = $_GET["price_opt"];
	   }

		$newcart[] = $cx;
	}else{
		$newcart[] = $cx;
	}

}
     //print_r($newcart);


	 
	$cx["descripcion"] = $_GET['descripcion'];
		$cx["q"] = $_GET['q'];
		$cx["discount"] = $_GET["discount"];
		$cx["price"] = $_GET["price_out"];
		$use_price2 = isset($_GET['use_price2'])?1:0;
		$cx["use_price2"] = $use_price2;
		if($_GET['price_opt']!=""){


	 

$_SESSION['cart'] = $newcart;
?>
*/


//print_r($_GET);
$cart = $_SESSION['cart'];
$newcart = array();


foreach($cart as $cx){

	if($cx["product_id"]==$_GET['product_id']){
		$cx["descripcion"] = $_GET['descripcion'];
		$cx["q"] = $_GET['q'];
		$cx["discount"] = $_GET["discount"];
		$cx["price"] = $_GET["price_out"];
		$use_price2 = isset($_GET['use_price2'])?1:0;
		$cx["use_price2"] = $use_price2;
		if($_GET['price_opt']!=""){
		$cx["descripcion"] = $_GET['descripcion'];
		$cx["price_opt"] = $_GET["price_opt"];

		}

		$newcart[] = $cx;
	}else{
		$newcart[] = $cx;
	}

}
     //print_r($newcart);

$_SESSION['cart'] = $newcart;
?>