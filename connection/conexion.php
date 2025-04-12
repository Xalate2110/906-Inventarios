<?php

$mysqli = new mysqli("localhost", "root", "", "bd_hulesautomotrices");
if ($mysqli->connect_errno) {
    echo "Fallo al contenctar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
?>