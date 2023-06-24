<?php
    require_once('db.php');
    require_once('utils.php');

    $token = $_POST['token'];
    $id = auth($token, $con, 'id');

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
        $query2 = $con->query("SELECT name, user, avatar FROM users WHERE id = '".$row['user_id']."';");
        $row2 = $query2->fetch_assoc();

        if ($row2['avatar'] == null) {
            $row2['avatar'] = 'https://ui-avatars.com/api/background=0D8ABC&color=fff?name='.$row2['user'];
        }
        /* get comments number */
        $query3 = $con->query("SELECT id FROM comments WHERE post_id = '".$row['id']."';");
        $row3 = $query3->fetch_assoc();

        $post = array(
            'id' => $row['id'],
            'user' => $row2['user'],
            'name' => $row2['name'],
            'avatar' => $row2['avatar'],
            'text' => $row['text'],
            'image' => $row['image'],
            'likes' => $row['likes'],
            'comments' => $query3->num_rows,
            'dt' => $row['dt']
        );

        if ($row['user_id'] == $id) {
            $post = array_merge(
                $post,
                array(
                    'ismy' => true
                )
            );
        } else {
            $post = array_merge(
                $post,
                array(
                    'ismy' => false
                )
            );
        }

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
