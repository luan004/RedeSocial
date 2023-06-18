<?php
    require_once('db.php');
    require_once('auth2.php');

    //$user = $_POST['user'];
    $user = 'luan004';
    $id = getIdFromUser($user, $con);

    $query = $con->query("SELECT * FROM posts WHERE user_id = '$id' ORDER BY dt DESC LIMIT 20;");

    $num = 1;
    $json = array(
        'count' => $query->num_rows
    );
    while ($row = $query->fetch_assoc()) {
        $query2 = $con->query("SELECT name, user, avatar FROM users WHERE id = '".$row['user_id']."';");
        $row2 = $query2->fetch_assoc();

        if ($row2['avatar'] == null) {
            $row2['avatar'] = 'https://ui-avatars.com/api/background=0D8ABC&color=fff?name='.$row2['user'];
        }

        $post = array(
            'id' => $row['id'],
            'user' => $row2['user'],
            'name' => $row2['name'],
            'avatar' => $row2['avatar'],
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
