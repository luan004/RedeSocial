<?php
    require_once('../connection/Conn.php');
    require_once('../dao/SessTokenDAO.php');
    require_once('../models/Sesstoken.php');

    $token = $_POST['token'];

    $conn = new Conn();
    $sesstokenDAO = new SesstokenDAO($conn);
    $sesstoken = $sesstokenDAO->getSesstokenByToken($token);

    if ($sesstoken) {
        $response = array(
            'auth' => true,
            'userId' => $sesstoken->getUserId()
        );
    } else {
        $response = array(
            'auth' => false
        );
    }
    
    $conn->close();
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
?>