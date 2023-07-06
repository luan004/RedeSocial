<?php
    require_once('../connection/Conn.php');
    require_once('../dao/PostDAO.php');
    require_once('../dao/UserDAO.php');
    require_once('../dao/LikeDAO.php');
    require_once('../dao/CommentDAO.php');
    require_once('../models/Post.php');
    require_once('../models/User.php');
    require_once('../models/Comment.php');

    $id = $_POST['id'];

    $conn = new Conn();
    
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

        $likeDAO = new LikeDAO($conn);
        $likeNum = $likeDAO->getLikeNumByPostId($post->getId());

        $response = array(
            'success' => true,
            'id' => $post->getId(),
            'text' => $post->getText(),
            'likes' => $likeNum,
            'image' => $post->getImage(),
            'dt' => $post->getDt(),
            'user' => $userAr
        );

        $commentDAO = new CommentDAO($conn);
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