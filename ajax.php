
<?php
include '/connection/conexion.php';
$sql = "select id,name,lastname,has_credit,credit_limit  FROM person 
		WHERE name LIKE '%".$_GET['q']."%'and kind = 1"; 
		    mysqli_set_charset($mysqli, "utf8");
			$resultado = $mysqli->query($sql);
			$result = $mysqli->query($sql);
			$json = [];
			while($row = $result->fetch_assoc()){
	        $json[] = ['id'=>$row['id'], 'text'=>$row['name']];}
			echo json_encode($json);?>