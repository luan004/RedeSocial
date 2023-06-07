<?php
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    /* $passD = 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3'; */

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

    echo $response['']

    header('Content-Type: application/json');
    if ($user == 'luan004') {
        echo json_encode($response);
    }
    exit;
?>
