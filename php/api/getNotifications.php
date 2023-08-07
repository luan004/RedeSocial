<?php
    require_once('../connection/Conn.php');
    require_once('../connection/Files.php');
    require_once('../dao/SesstokenDAO.php');
    require_once('../dao/UserDAO.php');
    require_once('../dao/NotificationDAO.php');
    require_once('../models/Sesstoken.php');
    require_once('../models/User.php');
    require_once('../models/Notification.php');

    $token = $_POST['token'];
    $limit = $_POST['limit'];

    $conn = new Conn();

    if ($limit > 100) {
        $limit = 100;
    }

    $sesstokenDAO = new SesstokenDAO($conn);
    $sesstoken = $sesstokenDAO->getSesstokenByToken($token);

    if ($sesstoken) {
        $notificationDAO = new NotificationDAO($conn);
        $notifications = $notificationDAO->getNotificationsByUserId($sesstoken->getUserId(), $limit);
        
        $notificationsStr = array();
        foreach ($notifications as $notification) {

            // get user infos
            $userDAO = new UserDAO($conn);
            $author = $userDAO->getUserById($notification->getAuthorId());

            $avatar = $author->getAvatar();
            if ($avatar != null) {
                $files = new Files();
                $avatar = $files->getB64Image($avatar);
            }

            $authorStr = array(
                'user' => $author->getUser(),
                'avatar' => $avatar
            );

            $notificationsStr[] = array(
                'id' => $notification->getId(),
                'type' => $notification->getType(),
                'author' => $authorStr,
                'post' => $notification->getPostId(),
                'dt' => $notification->getDt()
            );
        }

        $response = array(
            'success' => true,
            'notifications' => $notificationsStr
        );
    } else {
        $response = array(
            'success' => false
        );
    }
    
    header('Content-type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    $conn->close();
    exit;
?>