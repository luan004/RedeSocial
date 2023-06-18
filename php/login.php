<?php
    require_once('db.php');

    $user = $_POST['user'];
    $pass = $_POST['pass'];

    $stmt = $con->prepare("SELECT id FROM users WHERE user = ? AND pass = ?");
    $stmt->bind_param("ss", $user, $pass);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows == 1){
        $stmt->bind_result($id);
        $stmt->fetch();

        $token = bin2hex(random_bytes(32)); //token hexadecimal de 64 caracteres

        $stmt2 = $con->prepare("INSERT INTO sesstokens (id, user_id, token) VALUES (null, ?, ?)");
        $stmt2->bind_param("is", $id, $token);
        $stmt2->execute();

        $response = array(
            'auth' => true,
        );
    }
    else{
        $response = array(
            'auth' => false
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    $stmt->close();
    $con->close();
    exit;
?>