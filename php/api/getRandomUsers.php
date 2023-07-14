<?php
    require_once('../connection/Conn.php');
    require_once('../dao/UserDAO.php');
    require_once('../models/User.php');

    $conn = new Conn();

    $userDAO = new UserDAO($conn);
    $users = $userDAO->getRandomUsers(10);

    if ($users) {
        $usersAr = array();
        foreach ($users as $user) {
            if ($user->getAvatar() == null) {
                $user->setAvatar('https://ui-avatars.com/api/background=0D8ABC&color=fff?name='.$user->getUser());
            }

            $userAr = array(
                'name' => $user->getName(),
                'user' => $user->getUser(),
                'avatar' => $user->getAvatar()
            );

            array_push($usersAr, $userAr);
        }
        $users = $usersAr;
    }

    $response = array(
        'success' => true,
        'users' => $users
    );

    $conn->close();
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
?>