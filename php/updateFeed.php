<?php
    require_once('db.php');
    require_once('auth.php');

    $token = '1fa3cde6421efb37202bf24bf63f178db4dd57b3e99f9804e36c3e7b82e280ee';
    $id = auth($token, $con);

    if ($id == null) {
        $json = array(
            'success' => false
        );
        header('Content-Type: application/json');
        echo json_encode($json, JSON_PRETTY_PRINT);
        $con->close();
        exit;
    }

    $query = $con->query("SELECT * FROM posts ORDER BY dt DESC LIMIT 20;");

    $num = 1;
    $json = array(
        'success' => true
    );
    while ($row = $query->fetch_assoc()) {
        $post = array(
            'id' => $row['id'],
            'user' => $row['user_id'],
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
