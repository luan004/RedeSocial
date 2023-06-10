<?php
    require_once('db.php');
    require_once('auth.php');

    $token = $_POST['token'];
    $id = auth($token, $con);

    $query = $con->query("SELECT id FROM users WHERE id = '$id';");

    if ($id != null) {
        if(@$query->num_rows > 0){
            $row = $query->fetch_array();
            $response = array(
                'auth' => true
            );
        }
    } else {
        $response = array(
            'auth' => false
        );
    }
    
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    $con->close();
    exit;
?>
