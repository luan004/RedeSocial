<?php
    require_once('../connection/Conn.php');
    require_once('../dao/SesstokenDAO.php');
    require_once('../dao/UserDAO.php');
    require_once('../models/Sesstoken.php');
    require_once('../models/User.php');
    require_once('../connection/Files.php');

    $type = 'avatar';
    $token = $_POST['token'];

    $conn = new Conn();
    $sesstokenDAO = new SesstokenDAO($conn);
    $sesstoken = $sesstokenDAO->getSesstokenByToken($token);

    if ($sesstoken) {
        $files = new Files();

        $userDAO = new UserDAO($conn);
        $user = $userDAO->getUserById($sesstoken->getUserId());

        if ($type == 'avatar') {
            $files->deleteImage($user->getAvatar());
            $user->setAvatar(null);
        } else if ($type == 'banner') {
            $files->deleteImage($user->getBanner());
            $user->setBanner(null);
        }

        $userDAO->update($user);

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
    exit;
?>