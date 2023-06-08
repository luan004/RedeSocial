<?php
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    
    $hostdb = "localhost";
    $db = "tpwdb";
    $userdb = "root";
    $passworddb = "";

    $con = new mysqli($hostdb, $userdb, $passworddb, $db);
    $sql = "SELECT id, pass FROM users WHERE user = '$user';";
    $result = $con->query($sql);

    if($result->num_rows > 0){
        while($row = $result->fetch_array()){
            if ($row[1] == $pass) {
                $response = array(
                    'isAuth' => true,
                    'id' => $row[0]
                );
            } else {
                $response = array(
                    'isAuth' => false
                );
            }

        }
    } else {
        $response = array(
            'isAuth' => 'unknown'
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
?>
