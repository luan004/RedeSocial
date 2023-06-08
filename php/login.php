<?php
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    
    $hostdb = "localhost";
    $db = "tpwdb";
    $userdb = "root";
    $passworddb = "";

    $con = new mysqli($hostdb, $userdb, $passworddb, $db);
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
    echo json_encode($response);
    $con->close();
    exit;
?>
