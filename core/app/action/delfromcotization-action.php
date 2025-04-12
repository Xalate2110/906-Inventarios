<?php

$op = OperationData::getById($_GET['op_id']);
$op->del();

		print "<script>window.location='index.php?view=onecotization&id=$_GET[cotization_id]';</script>";

?>