<?php
    require_once('../connection/Conn.php');
    require_once('../dao/NotificationDAO.php');
    require_once('../dao/SesstokenDAO.php');
    require_once('../dao/CommentDAO.php');
    require_once('../dao/PostDAO.php');
    require_once('../models/Notification.php');
    require_once('../models/Sesstoken.php');
    require_once('../models/Comment.php');
    require_once('../models/Post.php');
    
    $text = $_POST['text'];
    $postId = $_POST['postId'];
    $token = $_POST['token'];

    $conn = new Conn();

    $sesstokenDAO = new SesstokenDAO($conn);
    $sesstoken = $sesstokenDAO->getSesstokenByToken($token);

    $postDAO = new PostDAO($conn);
    $post = $postDAO->getPostById($postId);

    if ($sesstoken && $post && $text != '' && $text != null) {
        $comment = new Comment(
            null,
            $post->getId(),
            $sesstoken->getUserId(),
            $text,
            null
        );
        $commentDAO = new CommentDAO($conn);
        $commentDAO->create($comment);
        $response = array(
            'success' => true
        );

        if ($sesstoken->getUserId() != $post->getUserId()) {
            //notification
            $notificationDAO = new NotificationDAO($conn);
            $notification = new Notification(
                null,
                3,
                $post->getUserId(),
                $sesstoken->getUserId(),
                $post->getId(),
                null
            );
            $notificationDAO->create($notification);
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