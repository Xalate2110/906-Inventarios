<?php
error_reporting(0);

if(count($_POST)>0){
	$user = new PersonData();
	$user->price_id = $_POST["price_id"];
    $user->no = $_POST["no"];
    $user->name = utf8_decode($_POST["name"]);
    $user->encargado = $_POST["encargado"];
    $user->lastname = utf8_decode($_POST["lastname"]);
    $user->address1 = utf8_decode($_POST["address1"]);
    $user->email1 = $_POST["email1"];
    $user->phone1 = $_POST["phone1"];
    $user->phone2 = $_POST["phone2"];
    $user->phone3 = $_POST["phone3"];
    $user->codigopostal = $_POST["codigopostal"];
    $user->credit_limit = $_POST["credit_limit"];
    $user->forma_pago = $_POST["forma_pago"];
    $user->uso_comprobante = $_POST["uso_comprobante"];  
    $user->regimen_fiscal = $_POST["regimen_fiscal"];  
    $user->has_credit = isset($_POST["has_credit"]) ? 1 : 0;
    $user->tiene_rs = isset($_POST["tiene_rs"]) ? 1 : 0;
    $user->stock_id = $_POST["stock_id"];
    //$user->is_active_access = isset($_POST["is_active_access"]) ? 1 : 0;
    //$user->password = sha1(md5($_POST["password"]));

    include '/connection/conexion.php';
    //$sql2 = "SELECT * FROM PERSON WHERE no='$user->no' or name ='$user->name' or lastname ='$user->lastname' or address1 ='$user->address1'";
    $sql2 = "SELECT * FROM PERSON WHERE name ='$user->name' and lastname ='$user->lastname' and address1 ='$user->address1'";
    $result=$mysqli->query($sql2); 
    $row_cnt = $result->num_rows;
    if($row_cnt>0){
    Core::alert("No puede registrar al cliente, debido a que ya se cuenta con una información igual registrada en el sistema.");
	print "<script>window.location='index.php?view=clients&stock=$user->stock_id';</script>";
    }else {
	$user->add_client();
    }



	Core::alert("Se ha registrado el cliente correctamente en el sistema");
    print "<script>window.location='index.php?view=clients&opt=all&stock=$user->stock_id';</script>";


}


?>

