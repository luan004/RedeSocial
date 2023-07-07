<?php
    require_once('../connection/Conn.php');
    require_once('../dao/SesstokenDAO.php');
    require_once('../dao/PostDAO.php');
    require_once('../dao/UserDAO.php');
    require_once('../dao/LikeDAO.php');
    require_once('../dao/CommentDAO.php');
    require_once('../models/User.php');
    require_once('../models/Sesstoken.php');
    require_once('../models/Post.php');
    require_once('../models/Like.php');
    require_once('../models/Comment.php');

    $conn = new Conn();

    $type = $_POST['type'];
    $token = $_POST['token'];

    $sesstokenDAO = new SesstokenDAO($conn);
    $sesstoken = $sesstokenDAO->getSesstokenByToken($token);

    $postDAO = new PostDAO($conn);
    $userDAO = new UserDAO($conn);

    $userId = null;
    if ($sesstoken) {
        $userId = $sesstoken->getUserId();
    }

    if ($type == 'feed' && $sesstoken) {
        //$response = $postDAO->getFeed($userId);
        $response = array(
            'type' => 'feed'
        );
    } elseif ($type == 'user' && $username = $_POST['user']) {
        $user = $userDAO->getUserByUsername($username);
        if ($user) {
            $posts = $postDAO->getPostsByUserId($user->getId(), $userId);

            $postsAr = array();
            foreach ($posts as $post) {
                $user = $userDAO->getUserById($post->getUserId());

                // get user
                $userAr = array(
                    'name' => $user->getName(),
                    'user' => $user->getUser(),
                    'avatar' => $user->getAvatar()
                );

                //get likes
                $likeDAO = new LikeDAO($conn);
                $likes = $likeDAO->getLikeNumByPostId($post->getId());

                //get comments num
                $commentDAO = new CommentDAO($conn);
                $comments = $commentDAO->getCommentsNumByPostId($post->getId());

                $postAr = array(
                    'id' => $post->getId(),
                    'user' => $userAr,
                    'text' => $post->getText(),
                    'image' => $post->getImage(),
                    'likes' => $likes,
                    'comments' => $comments,
                    'dt' => $post->getDt()
                );

                array_push($postsAr, $postAr);
            }

            $response = array(
                'success' => true,
                'posts' => $postsAr
            );

        } else {
            $response = array(
                'success' => false
            );
        }
    } elseif ($type == 'all') {
        $posts = $postDAO->getAllPosts($userId);

        $postsAr = array();
        foreach ($posts as $post) {
            $user = $userDAO->getUserById($post->getUserId());

            // get user
            $userAr = array(
                'name' => $user->getName(),
                'user' => $user->getUser(),
                'avatar' => $user->getAvatar()
            );

            //get likes
            $likeDAO = new LikeDAO($conn);
            $likes = $likeDAO->getLikeNumByPostId($post->getId());

            //get comments num
            $commentDAO = new CommentDAO($conn);
            $comments = $commentDAO->getCommentsNumByPostId($post->getId());

            $postAr = array(
                'id' => $post->getId(),
                'user' => $userAr,
                'text' => $post->getText(),
                'image' => $post->getImage(),
                'likes' => $likes,
                'comments' => $comments,
                'dt' => $post->getDt()
            );

            array_push($postsAr, $postAr);
        }

        $response = array(
            'success' => true,
            'posts' => $postsAr
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