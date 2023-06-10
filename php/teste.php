<?php
    require_once('db.php');
    require_once('auth.php');

    $token = '522e5601c50f7118b364cd5e8154006b5b7bf54facd185e920fec41fb52dfdbf';
    $id = auth($token, $con);

    $query = $con->query("SELECT name, user, avatar FROM users WHERE id = '$id';");

    if ($id != null) {
        if(@$query->num_rows > 0){
            $row = $query->fetch_array();
            $response = array(
                'auth' => true,
                'name' => $row[0],
                'user' => $row[1],
                'avatar' => $row[2]
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
