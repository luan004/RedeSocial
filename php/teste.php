<?php
    require_once('db.php');

    $user = 'luan004';
    $pass = '123';
    
    $query = $con->query("SELECT id, pass FROM users WHERE user = '$user';");

    if($query->num_rows > 0){
        $row = $query->fetch_array();
        if ($row[1] == $pass) {
            $token = bin2hex(random_bytes(32)); //token hexadecimal de 64 caracteres
            $con->query("INSERT INTO sesstokens (id, user_id, token) VALUES (null, '$row[0]', '$token');");
            $response = array(
                'auth' => true,
                'token' => $token
            );
        } else {
            $response = array(
                'auth' => false
            );
        }
    } else {
        $response = array(
            'auth' => 'unknown'
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    $con->close();
    exit;
?>
