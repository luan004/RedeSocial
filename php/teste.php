<?php
    $user = $_POST['user'];
    $pass = $_POST['pass'];

    $hostdb = "localhost";
    $db = "tpwdb";
    $userdb = "root";
    $passworddb = "";

    $con = new mysqli($hostdb, $userdb, $passworddb, $db);

    if($con->connect_error){
        echo "Error: " + connect_errno;
        die();
    }

    $sql = "SELECT id, pass, name, avatar, banner FROM users WHERE user = '$user';";
    $result = $con->query($sql);

    if($result->num_rows > 0){
        while($row = $result->fetch_array()){
            if ($row[1] == $pass) {
                $response = array(
                    'isAuth' => true,
                    'id' => $row[0],
                    'name' => $row[2],
                    'avatar' => $row[3],
                    'banner' => $row[4]
                );
            } else {
                $response = array(
                    'isAuth' => false
                );
            }

            echo $response['isAuth'];
        }
    } else {
        $response = array(
            'isAuth' => 'unknown'
        );
    }

    header('Content-Type: application/json');
    if ($user == 'luan004') {
        echo json_encode($response);
    }
    exit;
?>
