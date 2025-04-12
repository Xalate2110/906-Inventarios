<?php
$cli = Persondata::getById($_SESSION["selected_client_id"]);

$cli->credit_limit=$_GET["credit_limit"];

$cli->update_credit_limit();

?>