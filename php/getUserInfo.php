<?php
    $id = $_POST['id'];
    
    $hostdb = "localhost";
    $db = "tpwdb";
    $userdb = "root";
    $passworddb = "";

    $con = new mysqli($hostdb, $userdb, $passworddb, $db);
    $sql = "SELECT name, user, avatar FROM users WHERE id = '$id';";
    $query = $con->query($sql);

    if($query->num_rows > 0){
        $row = $query->fetch_array();
        $response = array(
            'name' => $row[0],
            'user' => $row[1],
            'avatar' => $row[2]
        );
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    $con->close();
    exit;
?>
