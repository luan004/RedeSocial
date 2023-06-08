<?php
    $token = $_POST['token'];
    
    $hostdb = "localhost";
    $db = "tpwdb";
    $userdb = "root";
    $passworddb = "";

    $con = new mysqli($hostdb, $userdb, $passworddb, $db);
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
    echo json_encode($response);
    $con->close();
    exit;
?>
