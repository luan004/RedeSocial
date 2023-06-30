<?php
    require_once('../connection/Conn.php');
    require_once('../dao/SesstokenDAO.php');
    require_once('../dao/PostDAO.php');
    require_once('../dao/UserDAO.php');
    require_once('../dao/LikeDAO.php');
    require_once('../models/Post.php');
    require_once('../models/Sesstoken.php');
    require_once('../models/User.php');
    require_once('../models/Like.php');

    $conn = new Conn();

    $feed = false;
    $token = '6ba4b2c5f0a7779c83bbc565aa9df955081f0d7aa7ece736e05563bfb8a730b5';

    $sesstokenDAO = new SesstokenDAO($conn);
    $sesstoken = $sesstokenDAO->getSesstokenByToken($token);

    $postDAO = new PostDAO($conn);
    $userDAO = new UserDAO($conn);

    if ($sesstoken) {
        $userId = $sesstoken->getUserId();
    } else {
        $userId = false;
    }

    if ($feed && $sesstoken) {
        $response = array(
            'type' => 'feed'
        );
    } else {
        $response = $postDAO->getAllPosts($userId);
    }

    $conn->close();
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
?>