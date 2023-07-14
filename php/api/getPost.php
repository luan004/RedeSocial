<?php
    require_once('../connection/Conn.php');
    require_once('../connection/Files.php');
    require_once('../dao/PostDAO.php');
    require_once('../dao/UserDAO.php');
    require_once('../dao/LikeDAO.php');
    require_once('../dao/CommentDAO.php');
    require_once('../dao/SesstokenDAO.php');
    require_once('../models/Post.php');
    require_once('../models/User.php');
    require_once('../models/Comment.php');
    require_once('../models/Like.php');
    require_once('../models/Sesstoken.php');

    $conn = new Conn();

    $id = $_POST['id'];
    $token = $_POST['token'];

    $sesstokenDAO = new SesstokenDAO($conn);
    $sesstoken = $sesstokenDAO->getSesstokenByToken($token);

    $userId = null;
    if ($sesstoken) {
        $userId = $sesstoken->getUserId();
    }

    
    $postDAO = new PostDAO($conn);
    $post = $postDAO->getPostById($id);

    if ($post) {
        $userDAO = new UserDAO($conn);
        $user = $userDAO->getUserById($post->getUserId());
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
        $commentsNum = $commentDAO->getCommentsNumByPostId($post->getId());

        // check if user liked
        $liked = false;
        if ($userId) {
            $like = $likeDAO->getLikeByPostIdUserId($post->getId(), $userId);
            if ($like) {
                $liked = true;
            }
        }

        // check if is from user
        $ismy = false;
        if ($userId == $post->getUserId()) {
            $ismy = true;
        }

        // send image as b64
        $image = $post->getImage();
        if ($image != null) {
            $files = new Files();
            $image  = $files->getB64Image($image);
        }

        $response = array(
            'success' => true,
            'id' => $post->getId(),
            'text' => $post->getText(),
            'likes' => $likes,
            'commentsNum' => $commentsNum,
            'image' => $image,
            'dt' => $post->getDt(),
            'liked' => $liked,
            'ismy' => $ismy,
            'user' => $userAr
        );

        $comments = $commentDAO->getCommentsByPostId($post->getId());

        if ($comments) {
            $commentsAr = array();
            foreach ($comments as $comment) {
                $user = $userDAO->getUserById($comment->getUserId());
                $userAr = array(
                    'name' => $user->getName(),
                    'user' => $user->getUser(),
                    'avatar' => $user->getAvatar()
                );

                $commentAr = array(
                    'id' => $comment->getId(),
                    'text' => $comment->getText(),
                    'dt' => $comment->getDt(),
                    'user' => $userAr
                );

                array_push($commentsAr, $commentAr);
            }

            $response['comments'] = $commentsAr;
        }
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