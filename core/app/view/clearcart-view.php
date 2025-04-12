<?php
if(isset($_GET["product_id"])){
	$cart=$_SESSION["cart"];
	if(count($cart)==1){
	 unset($_SESSION["cart"]);
	}else{
		$ncart = array();
		//$nx=0;
		foreach($cart as $c){
			if($c["product_id"]!=$_GET["product_id"]){
				$ncart[]= $c;
			}
			//$nx++;
		}
		$_SESSION["cart"] = $ncart;
	}

}else{
 unset($_SESSION["cart"]);
 unset($_SESSION["selected_client_id"]);
 unset($_SESSION["selected_price"]);
}

print "<script>window.location='index.php?view=sellnew';</script>";

?>