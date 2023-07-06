<?php
    require_once('../connection/Conn.php');
    require_once('../dao/SesstokenDAO.php');
    require_once('../dao/PostDAO.php');
    require_once('../dao/UserDAO.php');
    require_once('../models/User.php');
    require_once('../models/Sesstoken.php');
    require_once('../models/Post.php');

    $conn = new Conn();

    //$feed = $_POST['feed'];
    //$token = $_POST['token'];

    $type = $_POST['type']; 
    $token = $_POST['token'];

    $sesstokenDAO = new SesstokenDAO($conn);
    $sesstoken = $sesstokenDAO->getSesstokenByToken($token);

    $postDAO = new PostDAO($conn);
    $userDAO = new UserDAO($conn);

    $userId = null;
    if ($sesstoken) {
        $userId = $sesstoken->getUserId();
    }

    if ($type == 'feed' && $sesstoken) {
        //$response = $postDAO->getFeed($userId);
        $response = array(
            'type' => 'feed'
        );
    } elseif ($type == 'user') {
        $user = $userDAO->getUserByUsername($_POST['user']);
        $response = $postDAO->getPostsByUserId($user->getId());
    } elseif ($type == 'all') {
        $response = $postDAO->getAllPosts($userId);
    } else {
        $response = array(
            'success' => false
        );
    }

    $conn->close();
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
?>