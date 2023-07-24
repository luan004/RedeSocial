<?php
    require_once('../connection/Conn.php');
    require_once('../dao/UserDAO.php');
    require_once('../models/User.php');
    require_once('../connection/Files.php');

    $conn = new Conn();

    $userDAO = new UserDAO($conn);
    $users = $userDAO->getRandomUsers(10);

    if ($users) {
        $usersAr = array();
        foreach ($users as $user) {
            // get avatar
            $avatar = $user->getAvatar();
            if ($avatar != null) {
                $files = new Files();
                $avatar = $files->getB64Image($avatar);
            }

            $userAr = array(
                'name' => $user->getName(),
                'user' => $user->getUser(),
                'avatar' => $avatar
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