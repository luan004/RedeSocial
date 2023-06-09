<?php
    require_once('db.php');

    $user = $_POST['user'];

    $query = $con->query("SELECT name, avatar, banner FROM users WHERE user = '$user';");

    if(@$query->num_rows > 0){
        $row = $query->fetch_array();
        $response = array(
            'exists' => true,
            'name' => $row[0],
            'avatar' => $row[1],
            'banner' => $row[2]
        );
    } else {
        $response = array(
            'exists' => false
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    $con->close();
    exit;
?>
