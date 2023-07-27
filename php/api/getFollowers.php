<?php
    require_once('../connection/Conn.php');
    require_once('../dao/FollowDAO.php');
    require_once('../dao/UserDAO.php');
    require_once('../models/Follow.php');
    require_once('../models/User.php');

    $username = 'admin';

    $conn = new Conn();

    $userDAO = new UserDAO($conn);
    $user = $userDAO->getUserByUserName($username);

    if ($user) {
        $followDAO = new FollowDAO($conn);
        $followers = $followDAO->getFollowers($user->getId());
        $response = array(
            'success' => true,
            'followers' => array()
        );
        foreach ($followers as $follower) {
            $followerUser = $userDAO->getUserById($follower->getFollowerId());
            $followerAvatar = $followerUser->getAvatar();
            if ($followerAvatar != null) {
                $files = new Files();
                $followerAvatar = $files->getB64Image($followerAvatar);
            }
            $response['followers'][] = array(
                'id' => $followerUser->getId(),
                'name' => $followerUser->getName(),
                'user' => $followerUser->getUser(),
                'avatar' => $followerAvatar
            );
        }
    } else {
        $response = array(
            'success' => false
        );
    }

    header('Content-type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    $conn->close();
    exit

?>