<?php
    require_once('../connection/Conn.php');
    require_once('../connection/Files.php');
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
    $likeDAO = new LikeDAO($conn);
    $commentDAO = new CommentDAO($conn);

    $userId = null;
    if ($sesstoken) {
        $userId = $sesstoken->getUserId();
    }

    /* FEED */
    if ($type == 'feed' && $sesstoken) {
        $posts = $postDAO->getFeed($userId);

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
            $likes = $likeDAO->getLikeNumByPostId($post->getId());

            //get comments num
            $comments = $commentDAO->getCommentsNumByPostId($post->getId());

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

            $postAr = array(
                'id' => $post->getId(),
                'user' => $userAr,
                'text' => $post->getText(),
                'image' => $image,
                'likes' => $likes,
                'liked' => $liked,
                'ismy' => $ismy,
                'commentsNum' => $comments,
                'dt' => $post->getDt()
            );

            array_push($postsAr, $postAr);
        }

        $response = array(
            'success' => true,
            'posts' => $postsAr
        );
    }
    /* USER POSTS */
    elseif ($type == 'user' && $username = $_POST['user']) {
        $user = $userDAO->getUserByUsername($username);
        if ($user) {
            $posts = $postDAO->getPostsByUserId($user->getId(), $userId);

            $postsAr = array();
            foreach ($posts as $post) {
                $user = $userDAO->getUserById($post->getUserId());

                // get avatar
                $avatar = $user->getAvatar();
                if ($avatar != null) {
                    $files = new Files();
                    $avatar = $files->getB64Image($avatar);
                }

                // get user
                $userAr = array(
                    'name' => $user->getName(),
                    'user' => $user->getUser(),
                    'avatar' => $avatar
                );

                //get likes
                $likes = $likeDAO->getLikeNumByPostId($post->getId());

                //get comments num
                $comments = $commentDAO->getCommentsNumByPostId($post->getId());

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

                $postAr = array(
                    'id' => $post->getId(),
                    'user' => $userAr,
                    'text' => $post->getText(),
                    'image' => $image,
                    'likes' => $likes,
                    'liked' => $liked,
                    'ismy' => $ismy,
                    'commentsNum' => $comments,
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
    }
    /* ALL POSTS */
    elseif ($type == 'all') {
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
            $likes = $likeDAO->getLikeNumByPostId($post->getId());

            //get comments num
            $comments = $commentDAO->getCommentsNumByPostId($post->getId());

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

            $postAr = array(
                'id' => $post->getId(),
                'user' => $userAr,
                'text' => $post->getText(),
                'image' => $image,
                'likes' => $likes,
                'liked' => $liked,
                'ismy' => $ismy,
                'commentsNum' => $comments,
                'dt' => $post->getDt()
            );

            array_push($postsAr, $postAr);
        }

        $response = array(
            'success' => true,
            'posts' => $postsAr
        );
    }
    /* ERROR */
    else {
        $response = array(
            'success' => false
        );
    }

    $conn->close();
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
?>