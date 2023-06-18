<?php
    require_once('db.php');
    require_once('utils.php');

    $token = $_POST['token'];
    $id = auth($token, $con, 'id');

    $query = $con->query("SELECT name, user, avatar, banner FROM users WHERE id = '$id';");

    if(@$query->num_rows > 0){
        $row = $query->fetch_array();
        if ($row[1] == null) {
            $row[1] = 'https://ui-avatars.com/api/background=0D8ABC&color=fff?name='.$user;
        }
        if ($row[2] == null) {
            $row[2] = './resources/images/banner.jpg';
        }
        $response = array(
            'name' => $row[0],
            'user' => $row[1],
            'avatar' => $row[2],
            'banner' => $row[3]
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    $con->close();
    exit;
?>
