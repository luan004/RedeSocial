<?php
    require_once('db.php');

    $token = $_POST['token'];
    
    $query = $con->query("SELECT user_id FROM sesstokens WHERE token = '$token';");

    if(@$query->num_rows > 0){
        $row = $query->fetch_array();
        $response = array(
            'auth' => true,
            'id' => $row[0]
        );
    } else {
        $response = array(
            'auth' => false
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    $con->close();
    exit;
?>
