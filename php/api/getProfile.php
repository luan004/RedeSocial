<?php
    require_once('../connection/Conn.php');
    require_once('../connection/Files.php');
    require_once('../dao/SesstokenDAO.php');
    require_once('../dao/PostDAO.php');
    require_once('../dao/UserDAO.php');
    require_once('../dao/FollowDAO.php');
    require_once('../models/Sesstoken.php');
    require_once('../models/Post.php');
    require_once('../models/User.php');
    require_once('../models/Follow.php');

    $conn = new Conn();

    $userName = $_POST['user'];
    $token = $_POST['token']; 

    $userDAO = new UserDAO($conn);
    $user = $userDAO->getUserByUserName($userName);

    $sesstokenDAO = new SesstokenDAO($conn);
    $sesstoken = $sesstokenDAO->getSesstokenByToken($token);
    $userId = null;

    $followDAO = new FollowDAO($conn);
    $follow = null;
    if ($sesstoken && $user) {
        $userId = $sesstoken->getUserId();
        $follow = $followDAO->getFollowByUserAndFollowed($userId, $user->getId());
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

        // get followersCount
        $followersCount = $followDAO->getFollowersCount($user->getId());

        // get followingCount
        $followingCount = $followDAO->getFollowingCount($user->getId());

        $response = array(
            'success' => true,
            'isme' => $user->getId() == $userId,
            'ifollow' => $follow != null,
            'id' => $user->getId(),
            'name' => $user->getName(),
            'user' => $user->getUser(),
            'followersCount' => $followersCount,
            'followingCount' => $followingCount,
            'aboutme' => $user->getAboutme(),
            'avatar' => $avatar,
            'banner' => $banner,
            'dt' => $user->getDt(),
            'color' => $user->getColor()
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