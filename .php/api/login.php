<?php
    require_once('../connection/Conn.php');
    require_once('../dao/UserDAO.php');
    require_once('../models/User.php');

    //$user = $_POST['user'];
    //$pass = $_POST['pass'];

    $user = 'luan004';
    $pass = '123';

    $conn = new Conn();
    $userDAO = new UserDAO($conn);
    $userObj = $userDAO->getUserByUsername($user);

    if ($userObj && $userObj->getPass() === $pass) {
        $token = bin2hex(random_bytes(32)); //token hexadecimal de 64 caracteres
        
        $response = array(
            'status' => 'success'
        );
    } else {
        $response = array(
            'status' => 'error'
        );
    }

    $conn->close();
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
?>
