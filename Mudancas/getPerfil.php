<?php
    require_once('db.php');

    $user = $_POST['user'];

    $stmt = $con->prepare("SELECT name, avatar, banner FROM users WHERE user = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0){
        $stmt->bind_result($name, $avatar, $banner);
        $stmt->fetch();

        if ($avatar == null) {
            $avatar = 'https://ui-avatars.com/api/background=0D8ABC&color=fff?name=' . $user;
        }
        if ($banner == null) {
            $banner = './resources/images/banner.jpg';
        }

        $response = array(
            'exists' => true,
            'name' => $name,
            'avatar' => $avatar,
            'banner' => $banner
        );
    } else {
        $response = array(
            'exists' => false
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    $stmt->close();
    $con->close();
    exit;
?>