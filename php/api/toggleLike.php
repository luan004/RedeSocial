<?php
    require_once('../connection/Conn.php');
    require_once('../dao/SesstokenDAO.php');
    require_once('../dao/PostDAO.php');
    require_once('../dao/LikeDAO.php');
    require_once('../models/Post.php');
    require_once('../models/Sesstoken.php');
    require_once('../models/Like.php');

    $postId = $_POST['postId'];
    $token = $_POST['token'];

    $conn = new Conn();

    $sesstokenDAO = new SesstokenDAO($conn);
    $sesstoken = $sesstokenDAO->getSesstokenByToken($token);

    $postDAO = new PostDAO($conn);
    $post = $postDAO->getPostById($postId);
    
    $likeDAO = new LikeDAO($conn);

    if ($sesstoken && $post) {
        $like = $likeDAO->getLikeByUserAndPost($sesstoken->getUserId(), $post->getId());
        if ($like == null) {
            //create like
            $like = new Like(
                $sesstoken->getUserId(),
                $post->getId()
            );
            $likeDAO->create($like);
            $response = array(
                'success' => true,
                'liked' => true
            );
        } else {
            //delete like
            $likeDAO->delete($like);
            $response = array(
                'success' => true,
                'liked' => false
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