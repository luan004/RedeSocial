<?php
    require_once('db.php');
    require_once('utils.php');

    $postId = $_POST['postId'];
    $text = $_POST['text'];
    $token = $_POST['token'];

    $userId = auth($token, $con, 'id');

    $queryGetPost = $con->query("SELECT id FROM posts WHERE id = '$postId';");
    if($queryGetPost->num_rows != 0) {
        $postId = $queryGetPost->fetch_assoc()['id'];
    } else {
        $postId = null;
    }
    
    if ($userId != null && $postId != null && $text != null && $text != '') {
        $query = $con->query("INSERT INTO comments (post_id, user_id, text, dt) VALUES ('$postId', '$userId', '$text', NOW());");
        if ($query) {
            $json = array(
                'success' => true
            );
        } else {
            $json = array(
                'success' => false
            );
        }
    } else {
        $json = array(
            'success' => false
        );
    }

    header('Content-Type: application/json');
    echo json_encode($json, JSON_PRETTY_PRINT);
    $con->close();
    exit;
?>
