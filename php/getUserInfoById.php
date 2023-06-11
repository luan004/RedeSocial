<?php
    require_once('db.php');
    require_once('auth.php');

    $id = $_POST['id'];

    $query = $con->query("SELECT name, user, avatar FROM users WHERE id = '$id';");

    if(@$query->num_rows > 0){
        $row = $query->fetch_array();

        if ($row[2] == null) {
            $row[2] = 'https://ui-avatars.com/api/background=0D8ABC&color=fff?name='.$row[1];
        }
        
        $response = array(
            'name' => $row[0],
            'user' => $row[1],
            'avatar' => $row[2]
        );
    }
    
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    $con->close();
    exit;
?>
