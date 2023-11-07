<?php
    require_once('../connection/Conn.php');
    require_once('../dao/UserDAO.php');
    require_once('../dao/SesstokenDAO.php');
    require_once('../models/User.php');
    require_once('../models/Sesstoken.php');

    $user = $_POST['user'];
    $pass = $_POST['pass'];

    $conn = new Conn();
    $userDAO = new UserDAO($conn);
    $userObj = $userDAO->getUserByUsername($user);

    $pass = md5($pass);

    if ($userObj && $userObj->getPass() === $pass) {
        $token = bin2hex(random_bytes(32)); //token hexadecimal de 64 caracteres
        
        $sesstokenDAO = new SesstokenDAO($conn);
        $sesstoken = new Sesstoken(null, $userObj->getId(), $token);
        $sesstokenDAO->createSesstoken($sesstoken);

        $response = array(
            'auth' => true,
            'token' => $sesstoken->getToken()
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
