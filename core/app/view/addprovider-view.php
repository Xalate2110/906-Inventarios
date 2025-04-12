<?php
//error_reporting(0);
if(count($_POST)>0){
	$user = new PersonData();
	//$user->no = $_POST["no"];
	$user->name = $_POST["name"];
	//$user->lastname = $_POST["lastname"];
	$user->vendedor = $_POST["vendedor"];
	//$user->address1 = $_POST["address1"];
	$user->email1 = $_POST["email1"];
	$user->phone1 = $_POST["phone1"];
	$user->phone2 = $_POST["phone2"];
	$user->credit_limit = $_POST["credit_limit"];
	$user->has_credit = isset($_POST["has_credit"]) ? 1 : 0;
	//$user->stock_id = $_POST["stock_id"];


	include '/connection/conexion.php';
    //$sql2 = "SELECT * FROM PERSON WHERE no='$user->no' or name ='$user->name' or lastname ='$user->lastname' or address1 ='$user->address1'";
	$sql2 = "SELECT * FROM PERSON WHERE name ='$user->name' and lastname ='$user->lastname' and address1 ='$user->address1'";
    $result=$mysqli->query($sql2); 
    $row_cnt = $result->num_rows;
    if($row_cnt>0){
    Core::alert("No puede registrar al proveedor, debido a que ya se cuenta con una información igual registrada en el sistema.");
	//print "<script>window.location='index.php?view=providers&stock=$user->stock_id';</script>";
	print "<script>window.location='index.php?view=providers';</script>";
    }else {
	$user->add_provider();}
	
	Core::alert("Se ha registrado el proveedor correctamente en el sistema");
	//print "<script>window.location='index.php?view=providers&stock=$user->stock_id';</script>";
	print "<script>window.location='index.php?view=providers';</script>";
    }?>