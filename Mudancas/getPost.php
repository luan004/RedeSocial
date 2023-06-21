<?php
    require_once('db.php');
    
    $id = $_POST['postId'];
    //$id = 1;

    $stmt = $con->prepare("SELECT user_id, text, image, likes, dt FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows == 1){
        $stmt->bind_result($userId, $text, $image, $likes, $dt);
        $stmt->fetch();

        $stmt2 = $con->prepare("SELECT user, name, avatar FROM users WHERE id = ?");
        $stmt2->bind_param("i", $userId);
        $stmt2->execute();
        $stmt2->store_result();
        $stmt2->bind_result($user, $name, $avatar);
        $stmt2->fetch();
        $stmt2->close();

        $response = array(
            'success' => true,
            'user' => $user,
            'name' => $name,
            'avatar' => $avatar,
            'text' => $text,
            'image' => $image,
            'likes' => $likes,
            'dt' => $dt
        );

        $stmt3 = $con->prepare("SELECT user_id, text, dt FROM comments WHERE post_id = ?");
        $stmt3->bind_param("i", $id);
        $stmt3->execute();
        $stmt3->store_result();
        $stmt3->bind_result($commentUser, $commentText, $commentDt);
        $comments = array();

        $num = 1;
        while($stmt3->fetch()){
            $stmt4 = $con->prepare("SELECT user, name, avatar FROM users WHERE id = ?");
            $stmt4->bind_param("i", $commentUser);
            $stmt4->execute();
            $stmt4->store_result();
            $stmt4->bind_result($commentUser, $commentName, $commentAvatar);
            $stmt4->fetch();
            $stmt4->close();

            $comment = array(
                'user' => $commentUser,
                'name' => $commentName,
                'avatar' => $commentAvatar,
                'text' => $commentText,
                'dt' => $commentDt
            );

            $comments = array_merge(
                $comments,
                array(
                    'c'.$num => $comment
                )
            );
            $num++;
        }

        $response = array_merge(
            $response,
            array(
                'commentsNum' => $num-1,
                'comments' => $comments
            )
        );

    }
    else{
        $response = array(
            'success' => false
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    $stmt->close();
    $con->close();
    exit;
?>