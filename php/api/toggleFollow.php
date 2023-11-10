<?php
    require_once('../connection/Conn.php');
    require_once('../dao/SesstokenDAO.php');
    require_once('../dao/UserDAO.php');
    require_once('../dao/NotificationDAO.php');
    require_once('../dao/FollowDAO.php');
    require_once('../models/Sesstoken.php');
    require_once('../models/Notification.php');
    require_once('../models/User.php');
    require_once('../models/Follow.php');

    $followedUser = $_POST['followedUser'];
    $token = $_POST['token'];

    $conn = new Conn();

    $sesstokenDAO = new SesstokenDAO($conn);
    $sesstoken = $sesstokenDAO->getSesstokenByToken($token);

    $userDAO = new UserDAO($conn);
    $user = $userDAO->getUserByUsername($followedUser);

    if ($sesstoken && $user) {
        $followDAO = new FollowDAO($conn);
        $follow = $followDAO->getFollowByUserAndFollowed($sesstoken->getUserId(), $user->getId());
        if ($follow == null) {
            //create follow
            $follow = new Follow(
                $sesstoken->getUserId(),
                $user->getId()
            );
            $followDAO->create($follow);
            $response = array(
                'success' => true,
                'followed' => true
            );

            //notification
            $notificationDAO = new NotificationDAO($conn);
            $notification = new Notification(
                null,
                1,
                $user->getId(),
                $sesstoken->getUserId(),
                null,
                null
            );
            $notificationDAO->create($notification);
        } else {
            //delete follow
            $followDAO->delete($follow);
            $response = array(
                'success' => true,
                'followed' => false
            );
        }
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