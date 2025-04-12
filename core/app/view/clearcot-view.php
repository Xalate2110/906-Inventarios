<?php
if(isset($_GET["product_id"])){
	$cart=$_SESSION["cot"];
	if(count($cart)==1){
	 unset($_SESSION["cot"]);
	}else{
		$ncart = array();
		//$nx=0;
		foreach($cart as $c){
			if($c["product_id"]!=$_GET["product_id"]){
				$ncart[]= $c;
			}
			//$nx++;
		}
		$_SESSION["cot"] = $ncart;
	}

}else{
 unset($_SESSION["cot"]);
 unset($_SESSION["selected_client_id"]);
 unset($_SESSION["selected_price"]);
}

print "<script>window.location='index.php?view=cotizador';</script>";

?>