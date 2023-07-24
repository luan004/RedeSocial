<?php
    require_once('../connection/Conn.php');
    require_once('../dao/SesstokenDAO.php');
    require_once('../dao/UserDAO.php');
    require_once('../models/User.php');
    require_once('../models/Sesstoken.php');
    require_once('../connection/Files.php');

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
    elseif ($opt == 'token') {
        $sesstokenDAO = new SesstokenDAO($conn);
        $sesstoken = $sesstokenDAO->getSesstokenByToken($val);
        $user = $userDAO->getUserById($sesstoken->getUserId());
    }

    if ($user) {
        // get avatar
        $avatar = $user->getAvatar();
        if ($avatar != null) {
            $files = new Files();
            $avatar = $files->getB64Image($avatar);
        }
        // get banner
        $banner = $user->getBanner();
        if ($banner != null) {
            $files = new Files();
            $banner = $files->getB64Image($banner);
        }
        $response = array(
            'success' => true,
            'id' => $user->getId(),
            'name' => $user->getName(),
            'user' => $user->getUser(),
            'aboutme' => $user->getAboutMe(),
            'color' => $user->getColor(),
            'avatar' => $avatar,
            'banner' => $banner
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