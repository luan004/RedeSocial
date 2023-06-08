<?php
    $user = $_POST['user'];
    
    $hostdb = "localhost";
    $db = "tpwdb";
    $userdb = "root";
    $passworddb = "";

    $con = new mysqli($hostdb, $userdb, $passworddb, $db);
    $sql = "SELECT name, avatar, banner FROM users WHERE user = '$user';";
    $query = $con->query($sql);

    if($query->num_rows > 0){
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
    echo json_encode($response);
    $con->close();
    exit;
?>
