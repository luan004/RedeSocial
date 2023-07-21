<?php
    require_once('../connection/Conn.php');
    require_once('../dao/SesstokenDAO.php');
    require_once('../dao/PostDAO.php');
    require_once('../dao/UserDAO.php');
    require_once('../dao/FollowDAO.php');
    require_once('../models/Sesstoken.php');
    require_once('../models/Post.php');
    require_once('../models/User.php');
    require_once('../models/Follow.php');

    $conn = new Conn();

    $userName = $_POST['user'];
    $token = $_POST['token']; 

    $userDAO = new UserDAO($conn);
    $user = $userDAO->getUserByUserName($userName);

    $sesstokenDAO = new SesstokenDAO($conn);
    $sesstoken = $sesstokenDAO->getSesstokenByToken($token);
    $userId = null;

    $follow = null;
    if ($sesstoken && $user) {
        $userId = $sesstoken->getUserId();
        $followDAO = new FollowDAO($conn);
        $follow = $followDAO->getFollowByUserAndFollowed($userId, $user->getId());
    }

    if ($user) {
        $response = array(
            'success' => true,
            'isme' => $user->getId() == $userId,
            'ifollow' => $follow != null,
            'id' => $user->getId(),
            'name' => $user->getName(),
            'user' => $user->getUser(),
            'avatar' => $user->getAvatar(),
            'banner' => $user->getBanner(),
            'dt' => $user->getDt(),
            'color' => '#ffffff'
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