<?php
    require_once('db.php');

    $token = $_POST['token'];

    $stmt = $con->prepare("DELETE FROM sesstokens WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response = array(
            'success' => true
        );
    } else {
        $response = array(
            'success' => false
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    $stmt->close();
    $con->close();
    exit;
?>