<?php
if(isset($_GET["product_id"])){
	$cart=$_SESSION["reabastecer_inv"];
	if(count($cart)==1){
	 unset($_SESSION["reabastecer_inv"]);
	}else{
		$ncart = null;
//		$nx=0;
		foreach($cart as $c){
			if($c["product_id"]!=$_GET["product_id"]){
				$ncart[]= $c;
			}
//			$nx++;
		}
		$_SESSION["reabastecer_inv"] = $ncart;
	}

}else{
 unset($_SESSION["reabastecer_inv"]);
}

print "<script>window.location='index.php?view=ajuste_inv_sobrantes';</script>";

?>