<?php
    require_once('db.php');

    $token = $_POST['token'];

    $query = $con->query("DELETE FROM sesstokens WHERE token = '$token';");

    if ($query) {
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
    $con->close();
    exit;
?>
