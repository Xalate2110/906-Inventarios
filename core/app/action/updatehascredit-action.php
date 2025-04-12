<?php
$cli = Persondata::getById($_SESSION["selected_client_id"]);

$cli->has_credit=$_GET["has_credit"];

$cli->update_has_credit();

?>