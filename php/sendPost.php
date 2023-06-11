<?php
    require_once('db.php');
    require_once('auth.php');

/*     $text = $_POST['text'];
    $image = $_POST['image'];
    $token = $_POST['token'];
    $id = auth($token, $con); */

    $text = 'dsfadsfads';
    $image = null;
    $token = '1fa3cde6421efb37202bf24bf63f178db4dd57b3e99f9804e36c3e7b82e280ee';
    $id = auth($token, $con);

    header('Content-Type: application/json');
    if ($id != null) {
        if ($text == null && $image == null) {
            // no text or image
            $json = array(
                'auth' => true,
                'success' => false
            );
            echo json_encode($json, JSON_PRETTY_PRINT);
        } else {
            // post
            $query = $con->query("INSERT INTO posts (user_id, text, image, likes, dt) VALUES ('$id', '$text', '$image', '0', NOW());");
            if ($query) {
                // success
                $json = array(
                    'auth' => true,
                    'success' => true
                );
                echo json_encode($json, JSON_PRETTY_PRINT);
            } else {
                // error
                $json = array(
                    'auth' => true,
                    'success' => false
                );
                echo json_encode($json, JSON_PRETTY_PRINT);
            }
        }
    } else {
        // auth error
        $json = array(
            'auth' => false,
            'success' => false
        );
        echo json_encode($json, JSON_PRETTY_PRINT);
    }
    $con->close();
    exit;
?>
