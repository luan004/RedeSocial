<?php
    require_once('../connection/Conn.php');
    require_once('../dao/SesstokenDAO.php');
    require_once('../dao/UserDAO.php');
    require_once('../models/Sesstoken.php');
    require_once('../models/User.php');
    require_once('../connection/Files.php');

    $token = $_POST['token'];
    $pass = $_POST['pass'];

    $conn = new Conn();

    $sesstokenDAO = new SesstokenDAO($conn);
    $sesstoken = $sesstokenDAO->getSesstokenByToken($token);

    if ($sesstoken) {
        $userDAO = new UserDAO($conn);
        $user = $userDAO->getUserById($sesstoken->getUserId());

        if ($user && $user->getPass() == md5($pass)) {
            $files = new Files();
            $files->deleteAllUserFiles($user->getId());

            $userDAO->delete($user);

            $response = array(
                'success' => true
            );
        } else {
            $response = array(
                'success' => false
            );
        }
    } else {
        $response = array(
            'success' => false
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    $conn->close();
    exit;
?>