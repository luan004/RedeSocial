<?php
    require_once('../connection/Conn.php');
    require_once('../dao/SesstokenDAO.php');
    require_once('../dao/PostDAO.php');
    require_once('../dao/UserDAO.php');
    require_once('../models/Sesstoken.php');

    $conn = new Conn();

    $feed = $_POST['feed'];
    $token = $_POST['token'];

    $sesstokenDAO = new SesstokenDAO($conn);
    $sesstoken = $sesstokenDAO->getSesstokenByToken($token);

    $postDAO = new PostDAO($conn);
    $userDAO = new UserDAO($conn);

    $userId = null;
    if ($sesstoken) {
        $userId = $sesstoken->getUserId();
    }

    if ($feed == 'true' && $sesstoken) {
        $response = $postDAO->getFeed($userId);
    } else {
        $response = $postDAO->getAllPosts($userId);
    }

    $conn->close();
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
?>