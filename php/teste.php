<?php
    $token = '47fb112ed6dc1582534a51e91b4226dff7fa5a6cd657d404e0c6c34ec6dc1629';
    
    $hostdb = "localhost";
    $db = "tpwdb";
    $userdb = "root";
    $passworddb = "";

    $con = new mysqli($hostdb, $userdb, $passworddb, $db);
    $query = $con->query("DELETE FROM stokens WHERE token = '$token';");

    if ($query) {
        $response = array(
            'success' => true
        );
    } else {
        $response = array(
            'success' => false
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    $con->close();
    exit;
?>
