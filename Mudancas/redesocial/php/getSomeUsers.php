<?php
    require_once 'db.php';

    $query = $con->query("SELECT user, name, avatar FROM users ORDER BY RAND() LIMIT 10;");

    $num = 1;
    $json = array(
        'count' => $query->num_rows
    );
    while ($row = $query->fetch_assoc()) {
        if ($row['avatar'] == null) {
            $row['avatar'] = 'https://ui-avatars.com/api/background=0D8ABC&color=fff?name='.$row['user'];
        }
        $user = array(
            'user' => $row['user'],
            'name' => $row['name'],
            'avatar' => $row['avatar']
        );

        $json = array_merge(
            $json,
            array(
                'u'.$num => $user
            )
        );
        $num++;
    }

    header('Content-Type: application/json');
    echo json_encode($json, JSON_PRETTY_PRINT);
    $con->close();
    exit;
?>