<?php
    require_once('../connection/Conn.php');
    require_once('../connection/Files.php');
    require_once('../dao/SesstokenDAO.php');
    require_once('../dao/PostDAO.php');
    require_once('../models/Post.php');
    require_once('../models/Sesstoken.php');

    $token = $_POST['token'];
    $postId = $_POST['postId'];

    $conn = new Conn();
    $sesstokenDAO = new SesstokenDAO($conn);
    $sesstoken = $sesstokenDAO->getSesstokenByToken($token);

    $postDAO = new PostDAO($conn);
    $post = $postDAO->getPostById($postId);

    if ($sesstoken && $post && $sesstoken->getUserId() == $post->getUserId()) {

        /* CHECK IF POST HAS IMAGE, IF TRUE, DELETE */
        $image = $post->getImage();
        if ($image) {
            $files = new Files();
            $files->deleteImage($image);
        }
        
        $postDAO->delete($post);
        $response = array(
            'success' => true
        );
    } else {
        $response = array(
            'success' => false
        );
    }
    
    $conn->close();
    header('Content-type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit();
?>