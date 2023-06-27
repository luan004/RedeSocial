<?php
    require_once('../connection/Conn.php');
    require_once('../dao/UserDAO.php');
    require_once('../models/User.php');

    $name = $_POST['name'];
    $user = $_POST['user'];
    $pass = $_POST['pass'];

    switch (true) {
        case (strlen($name) > 64 || strlen($name) < 1):
            $response = array(
                'register' => false,
                'error' => 'nameInvalid'
            );
            break;
        case (strlen($pass) > 32 || strlen($pass) < 8 || !preg_match('/^[a-zA-Z0-9_]+$/', $pass)):
            $response = array(
                'register' => false,
                'error' => 'passInvalid'
            );
            break;
        case (strlen($user) > 32 || strlen($user) < 4 || !preg_match('/^[a-zA-Z0-9_]+$/', $user)):
            $response = array(
                'register' => false,
                'error' => 'userInvalid'
            );
            break;
        default:
            $conn = new Conn();
            $userDAO = new UserDAO($conn);
            
            // Cria um novo usuário
            $userObj = new User(null, $name, $user, $pass, null, null);
            $userDAO->createUser($userObj);

            $response = array(
                'register' => true
            );
            
            $conn->close();
            break;
    }
    
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
?>