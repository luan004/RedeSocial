<?php
    require_once('db.php');

    $user = $_POST['user'];

    $query = $con->query("SELECT name, avatar, banner FROM users WHERE user = '$user';");

    if(@$query->num_rows > 0){
        $row = $query->fetch_array();
        if ($row[1] == null) {
            $row[1] = 'https://ui-avatars.com/api/background=0D8ABC&color=fff?name='.$user;
        }
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
    echo json_encode($response, JSON_PRETTY_PRINT);
    $con->close();
    exit;
?>
