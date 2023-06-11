<?php
    require_once('db.php');
    require_once('auth.php');

    $token = $_POST['token'];
    $id = auth($token, $con);

    if ($id == null) {
        $json = array(
            'auth' => false
        );
        header('Content-Type: application/json');
        echo json_encode($json, JSON_PRETTY_PRINT);
        $con->close();
        exit;
    }

    $query = $con->query("SELECT * FROM posts ORDER BY dt DESC LIMIT 20;");

    $num = 1;
    $json = array(
        'auth' => true,
        'count' => $query->num_rows
    );
    while ($row = $query->fetch_assoc()) {
        $post = array(
            'id' => $row['id'],
            'user_id' => $row['user_id'],
            'text' => $row['text'],
            'image' => $row['image'],
            'likes' => $row['likes'],
            'dt' => $row['dt']
        );

        $json = array_merge(
            $json,
            array(
                'p'.$num => $post
            )
        );
        $num++;
    }

    header('Content-Type: application/json');
    echo json_encode($json, JSON_PRETTY_PRINT);
    $con->close();
    exit;
?>
