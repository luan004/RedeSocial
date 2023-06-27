<?php
    require_once('../connection/Conn.php');
    require_once('../dao/UserDAO.php');
    require_once('../dao/SesstokenDAO.php');
    require_once('../models/User.php');
    require_once('../models/Sesstoken.php');

    $token = '65e044541c5cfeb9383e154e2290b1b4942761c1f0c14804828a5fe2b7636812';

    $conn = new Conn();
    $sesstokenDAO = new SesstokenDAO($conn);
    
    if ($sesstokenDAO->exists($token)) {
        $sesstoken = $sesstokenDAO->getSesstokenByToken($token);
        $userDAO = new UserDAO($conn);
        $userObj = $userDAO->getUserById($sesstoken->getUserId());

        $response = array(
            'auth' => true,
            'name' => $userObj->getName(),
            'user' => $userObj->getUser(),
            'avatar' => $userObj->getAvatar()
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