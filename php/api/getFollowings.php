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
        $followeds = $followDAO->getFolloweds($user->getId());
        $response = array(
            'success' => true,
            'followeds' => array()
        );
        foreach ($followeds as $followed) {
            $followedUser = $userDAO->getUserById($followed->getFollowedId());
            $followedAvatar = $followedUser->getAvatar();
            if ($followedAvatar != null) {
                $files = new Files();
                $followedAvatar = $files->getB64Image($followedAvatar);
            }
            $response['followeds'][] = array(
                'id' => $followedUser->getId(),
                'name' => $followedUser->getName(),
                'user' => $followedUser->getUser(),
                'avatar' => $followedAvatar
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