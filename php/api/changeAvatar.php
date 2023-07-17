<?php
    require_once("../connection/Conn.php");
    require_once("../connection/Files.php");
    require_once('../dao/UserDAO.php');
    require_once('../dao/sesstokenDAO.php');
    require_once('../models/User.php');
    require_once('../models/sesstoken.php');

    $token = '';
    $avatar = '' // b64 encrypt

    $sesstokenDAO = new SesstokenDAO($conn);
    $sesstoken = $sesstokenDAO->getSesstokenByToken($token);

    if ($sesstoken) {
        $userDAO = new UserDAO($conn);
        $user = $userDAO->getUserById($sesstoken->getUserId());

        $Files = new Files();

        $avatarName = $Files->saveB64Image($avatar);

        $user->setAvatar($avatarName);
        $userDAO->updateUser($user);

        $response = array(
            'success' => true
        );
    } else {
        $response = array(
            'success' => false
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    $conn->close();
    exit();
?>