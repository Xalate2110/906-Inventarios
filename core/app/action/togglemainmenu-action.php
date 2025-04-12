<?php

if(isset($_SESSION["toggle_main"])){
	$toggle = $_SESSION['toggle_main'];
	if($toggle==1){
	$_SESSION['toggle_main']=0;
	}else{
		$_SESSION['toggle_main']=1;
	}

}else{
	$_SESSION['toggle_main']=1;

}

?>