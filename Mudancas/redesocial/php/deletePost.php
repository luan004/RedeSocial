<?php
    require_once('db.php');
    require_once('utils.php');

    $postId = $_POST['postId'];
    $token = $_POST['token'];

    $userId = auth($token, $con, 'id');

    if ($userId == null) {
        $json = array(
            'success' => false
        );
        header('Content-Type: application/json');
        echo json_encode($json, JSON_PRETTY_PRINT);
        $con->close();
        exit;
    }

    $stmt = $con->prepare("
        DELETE FROM `comments` WHERE post_id = ? AND user_id = ?;"
    );
    $stmt->bind_param("ii", $postId, $userId);
    $stmt->execute();

    $stmt2 = $con->prepare("
        DELETE FROM `posts` WHERE id = ? AND user_id = ?;"
    );
    $stmt2->bind_param("ii", $postId, $userId);
    $stmt2->execute();

    if ($stmt2->affected_rows > 0) {
        $json = array(
            'success' => true
        );
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