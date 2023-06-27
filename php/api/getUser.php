<?php
    require_once('../connection/Conn.php');
    require_once('../dao/UserDAO.php');
    require_once('../models/User.php');

    $opt = $_POST['opt'];
    $val = $_POST['val'];

    $user = null;
    $conn = new Conn();
    $userDAO = new UserDAO($conn);

    if($opt == 'id') {
        $user = $userDAO->getUserById($val);
    }
    elseif ($opt == 'user') {
        $user = $userDAO->getUserByUsername($val);
    }

    if ($user) {
        $response = array(
            'success' => true,
            'id' => $user->getId(),
            'name' => $user->getName(),
            'user' => $user->getUser(),
            'avatar' => $user->getAvatar()
        );
    } else {
        $response = array(
            'success' => false
        );
    }

    $conn->close();
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
?>