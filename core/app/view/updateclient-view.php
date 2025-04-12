<?php
error_reporting(0);
if(count($_POST)>0){
	$user = PersonData::getById($_POST["user_id"]);
    $user->price_id = $_POST["price"];
    $user->no = $_POST["no"];
    $user->name = utf8_decode($_POST["name"]);
    $user->encargado = utf8_decode($_POST["encargado"]);
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
    $user->tiene_rs = isset($_POST["tiene_rs"]) ? 1 : 0;
    $user->is_active_access = isset($_POST["is_active_access"]) ? 1 : 0;
    $user->has_credit = isset($_POST["has_credit"]) ? 1 : 0;
    $user->stock_id = $_POST["stock_id"];

	/* if ($_POST["password"] != "") {
    $user->password = sha1(md5($_POST["password"]));
    } */
	
    $user->update_client();
 
    Core::alert("Se ha actualizado la informacion del cliente correctamente.");
    print "<script>window.location='index.php?view=clients&opt=all&stock=$user->stock_id';</script>";


}


?>