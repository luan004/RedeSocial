<?php
    require_once('../connection/Conn.php');
    require_once('../dao/SesstokenDAO.php');
    require_once('../dao/PostDAO.php');
    require_once('../dao/UserDAO.php');
    require_once('../models/Sesstoken.php');

    $conn = new Conn();

    $feed = true;
    $token = '28ad38f46f6f8e166ad7cbbfc7b1d6be724c09163d2c20b4cae77f1c62272d0d';

    $sesstokenDAO = new SesstokenDAO($conn);
    $sesstoken = $sesstokenDAO->getSesstokenByToken($token);

    $postDAO = new PostDAO($conn);
    $userDAO = new UserDAO($conn);

    $userId = null;
    if ($sesstoken) {
        $userId = $sesstoken->getUserId();
    }

    if ($feed && $sesstoken) {
        $response = $postDAO->getFeed($userId);
    } else {
        $response = $postDAO->getAllPosts($userId);
    }

    $conn->close();
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
?>