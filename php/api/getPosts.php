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
    $token = $_POST['token'];

    $sesstokenDAO = new SesstokenDAO($conn);
    $sesstoken = $sesstokenDAO->getSesstokenByToken($token);

    $postDAO = new PostDAO($conn);
    $userDAO = new UserDAO($conn);

    $userId = null;
    if ($sesstoken) {
        $userId = $sesstoken->getUserId();
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