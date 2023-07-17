<?php
    require_once("../connection/Conn.php");
    require_once('../dao/UserDAO.php');
    require_once('../dao/sesstokenDAO.php');
    require_once('../models/User.php');
    require_once('../models/sesstoken.php');

    /* $token = $_POST['token'];
    $newPass = $_POST['newPass'];
    $oldPass = $_POST['oldPass']; */

    $token = '0341e64da459639ca897f85bccfc94f842bc4e569d9d876cef4535a98087dee9';
    $newPass = '';
    $oldPass = 'admin';

    $conn = new Conn();

    $sesstokenDAO = new SesstokenDAO($conn);
    $sesstoken = $sesstokenDAO->getSesstokenByToken($token);

    if ($sesstoken) {
        $userDAO = new UserDAO($conn);
        $user = $userDAO->getUserById($sesstoken->getUserId());

        if ($user->getPass() == $oldPass) {
            if (strlen($newPass) <= 32 && strlen($newPass) >= 8 && preg_match('/^[a-zA-Z0-9_]+$/', $newPass)) {
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
    }

    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    $conn->close();
    exit();
?>