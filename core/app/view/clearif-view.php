<?php
if(isset($_GET["product_id"])){
	$cart=$_SESSION["reabastecer_inv_faltante"];
	if(count($cart)==1){
	 unset($_SESSION["reabastecer_inv_faltante"]);
	}else{
		$ncart = null;
//		$nx=0;
		foreach($cart as $c){
			if($c["product_id"]!=$_GET["product_id"]){
				$ncart[]= $c;
			}
//			$nx++;
		}
		$_SESSION["reabastecer_inv_faltante"] = $ncart;
	}

}else{
 unset($_SESSION["reabastecer_inv_faltante"]);
}

print "<script>window.location='index.php?view=ajuste_inv_faltantes';</script>";

?>