<?php
    require_once('db.php');
    require_once('utils.php');

    $token = $_POST['token'];
    $id = auth($token, $con, 'id');

    $query = $con->query("SELECT name, user, avatar FROM users WHERE id = '$id';");

    if ($id != null) {
        if(@$query->num_rows > 0){
            $row = $query->fetch_array();

            if ($row[2] == null) {
                $row[2] = 'https://ui-avatars.com/api/background=0D8ABC&color=fff?name='.$row[1];
            }
            
            $response = array(
                'auth' => true,
                'name' => $row[0],
                'user' => $row[1],
                'avatar' => $row[2]
            );
        }
    } else {
        $response = array(
            'auth' => false
        );
    }
    
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    $con->close();
    exit;
?>
