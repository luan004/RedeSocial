<?php
    require_once("../connection/Conn.php");
    require_once('../dao/UserDAO.php');
    require_once('../dao/sesstokenDAO.php');
    require_once('../models/User.php');
    require_once('../models/sesstoken.php');

    /* $token = $_POST['token'];
    $newPass = $_POST['newPass'];
    $oldPass = $_POST['oldPass']; */

    $token = '33c9bcfd1bc0d74d84cb8925fa47e4e6434f930d302fbe371a5cdd65a5344aa4';
    $newPass = '87654321';
    $oldPass = '12345678';

    $conn = new Conn();

    $sesstokenDAO = new SesstokenDAO($conn);
    $sesstoken = $sesstokenDAO->getSesstokenByToken($token);

    if ($sesstoken) {
        $userDAO = new UserDAO($conn);
        $user = $userDAO->getUserById($sesstoken->getUserId());

        if ($user->getPass() == md5($oldPass)) {
            if (strlen($newPass) <= 32 && strlen($newPass) >= 8 && preg_match('/^[a-zA-Z0-9_]+$/', $newPass)) {
                $user->setPass(md5($newPass));
                $userDAO->update($user);
                $response = array(
                    'success' => true
                );
            } else {
                $response = array(
                    'success' => false,
                    'error' => 2 // Erro de senha inválida
                );
            }
        } else {
            $response = array(
                'success' => false,
                'error' => 1 // Erro de senha incorreta
            );
        }
    } else {
        $response = array(
            'success' => false,
            'error' => 1 // Erro de token inválido
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    $conn->close();
    exit();
?>