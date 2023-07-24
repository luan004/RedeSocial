<?php
    require_once('../connection/Conn.php');
    require_once('../dao/UserDAO.php');
    require_once('../dao/SesstokenDAO.php');
    require_once('../models/User.php');
    require_once('../models/Sesstoken.php');

    $token = $_POST['token'];

    $conn = new Conn();

    $sesstokenDAO = new SesstokenDAO($conn);
    $sesstoken = $sesstokenDAO->getSesstokenByToken($token);

    if ($sesstoken) {
        $userDAO = new UserDAO($conn);
        $user = $userDAO->getUserById($sesstoken->getUserId());

        $name = null;
        if (isset($_POST['name'])) {
            $name = $_POST['name'];
        }
        $aboutme = null;
        if (isset($_POST['aboutme'])) {
            $aboutme = $_POST['aboutme'];
        }
        $color = null;
        if (isset($_POST['color'])) {
            $color = $_POST['color'];
        }

        /* changes */
        $changedName = false;
        $changedAboutme = false;
        $changedColor = false;

        /* Update name */
        if ($name && $name != $user->getName() && strlen($name) <= 64 && strlen($name) >= 1) {
            $user->setName($name);
            $changedName = true;
        }

        /* Update about me */
        if ($aboutme && $aboutme != $user->getAboutMe() && strlen($aboutme) <= 150) {
            $user->setAboutMe($aboutme);
            $changedAboutme = true;
        }

        /* Update color */
        if ($color && $color != $user->getColor() && ($color == 'danger' || $color == 'warning' || $color == 'success' || $color == 'primary' || $color == 'info' || $color == 'default')) {
            $user->setColor($color);
            $changedColor = true;
        }

        $userDAO->update($user);

        $response = array(
            'success' => true,
            'changes' => array(
                'name' => $changedName,
                'aboutme' => $changedAboutme,
                'color' => $changedColor
            )
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