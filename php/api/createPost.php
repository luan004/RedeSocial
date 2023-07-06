<?php
    require_once('../connection/Conn.php');
    require_once('../dao/SesstokenDAO.php');
    require_once('../dao/PostDAO.php');
    require_once('../models/Post.php');
    require_once('../models/Sesstoken.php');

    $text = $_POST['text'];
    $image = $_POST['image'];
    $token = $_POST['token'];

    $conn = new Conn();
    $sesstokenDAO = new SesstokenDAO($conn);
    $sesstoken = $sesstokenDAO->getSesstokenByToken($token);

    if ($sesstoken && $text != null || $image != null) {
        $post = new Post(
            null,
            $sesstokenDAO->getSesstokenByToken($token)->getUserId(),
            $text,
            $image,
            null
        );
        $postDAO = new PostDAO($conn);
        $postDAO->create($post);
        $response = array(
            'success' => true
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