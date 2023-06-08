<?php
    $token = '075ba3f3e5e35f49ec9a25c78facd16dc9a6ea662389c09a32e2f0be1213d454';

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
            'id' => $row[0],
            'token' => $token
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
