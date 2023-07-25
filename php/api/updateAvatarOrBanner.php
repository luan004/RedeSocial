<?php
    require_once('../connection/Conn.php');
    require_once('../dao/SesstokenDAO.php');
    require_once('../dao/UserDAO.php');
    require_once('../models/Sesstoken.php');
    require_once('../models/User.php');
    require_once('../connection/Files.php');

    $type = $_POST['type'];
    $file = $_POST['file']; // Imagem como string
    $token = $_POST['token'];

    $conn = new Conn();
    $sesstokenDAO = new SesstokenDAO($conn);
    $sesstoken = $sesstokenDAO->getSesstokenByToken($token);

    if ($sesstoken && ($type == 'avatar' || $type == 'banner')) {
        $files = new Files();
        $imageName = ($file != null) ? $files->saveB64Image($file, $type) : null;

        $userDAO = new UserDAO($conn);
        $user = $userDAO->getUserById($sesstoken->getUserId());

        if ($type == 'avatar') {
            $files->deleteImage($user->getAvatar());
            $user->setAvatar($imageName);
        } else if ($type == 'banner') {
            $files->deleteImage($user->getBanner());
            $user->setBanner($imageName);
        } else {
            $response = array(
                'success' => false
            );
            header('Content-Type: application/json');
            echo json_encode($response, JSON_PRETTY_PRINT);
            $conn->close();
            exit;
        }

        $userDAO->update($user);

        $response = array(
            'success' => true
        );
    } else {
        $response = array(
            'success' => false
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    $conn->close();
    exit;
?>