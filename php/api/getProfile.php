<?php
    require_once('../connection/Conn.php');
    require_once('../dao/SesstokenDAO.php');
    require_once('../dao/PostDAO.php');
    require_once('../dao/UserDAO.php');
    require_once('../models/Sesstoken.php');
    require_once('../models/Post.php');
    require_once('../models/User.php');

    $conn = new Conn();

    $userName = 'admin';

    $userDAO = new UserDAO($conn);
    $user = $userDAO->getUserByUserName($userName);

    if ($user) {
        $postDAO = new PostDAO($conn);
        $posts = $postDAO->getPostsByUserId($user->getId());
        $response = array(
            'success' => true,
            'name' => $user->getName(),
            'user' => $user->getUser(),
            'avatar' => $user->getAvatar(),
            'banner' => $user->getBanner(),
            'color' => '#ffffff',
            'posts' => $posts
        );
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