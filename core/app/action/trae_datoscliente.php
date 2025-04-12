<?php
if(!empty($_POST['cliente_datos'])){
    $data = array();
    //database details
    $dbHost     = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName     = 'bd_distribuidora_athena';
    
    //create connection and select DB
    $db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
    if($db->connect_error){
        die("Unable to connect database: " . $db->connect_error);
    }

   
    
    //get user data from the database
    $query = $db->query("select id, iva, sub_total, total, created_at from sell where operation_type_id=2 and p_id=4 and is_draft=0 and person_id={$_POST['cliente_datos']} order by created_at desc");

    if($query->num_rows > 0){
        $userData = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['result'] = $userData;
    }else{
        $data['status'] = 'err';
        $data['result'] = '';
    }
    
    //returns data as JSON format
    echo json_encode($data);
}
?>