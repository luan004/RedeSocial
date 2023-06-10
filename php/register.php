<?php
    require_once('db.php');

    $name = $_POST['name'];
    $user = $_POST['user'];
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];

    if (strlen($name) <= 64 && strlen($name) >= 1) {
        if (strlen($pass1) <= 32 && strlen($pass1) >= 8 && preg_match('/^[a-zA-Z0-9_]+$/', $pass1)) {
            if (strlen($user) <= 32 && strlen($user) >= 4 && preg_match('/^[a-zA-Z0-9_]+$/', $user)) {
                if ($pass1 == $pass2) {

                    $query2 = "SELECT id FROM users WHERE user = '$user'";
                    $result = $con->query($query2);

                    $rowCount = $result->num_rows;

                    if ($rowCount == 0) {
                        $currentTime = date('Y-m-d H:i:s');
                        $con->query("INSERT INTO users (id, name, user, pass, dt) VALUES (null, '$name', '$user', '$pass1', STR_TO_DATE('$currentTime', '%Y-%m-%d %H:%i:%s'));");
                        if ($con->affected_rows > 0) {
                            $response = array(
                                'register' => true
                            );
                        } else {
                            $response = array(
                                'register' => false,
                                'error' => 'sqlError'
                            );
                        }
                    } else {
                        $response = array(
                            'register' => false,
                            'error' => 'userAlreadyExists'
                        );
                    }

                } else {
                    $response = array(
                        'register' => false,
                        'error' => 'passIsNotTheSame'
                    );
                }
            } else {
                $response = array(
                    'register' => false,
                    'error' => 'userInvalid'
                );
            }
        } else {
            $response = array(
                'register' => false,
                'error' => 'passInvalid'
            );
        }
    } else {
        $response = array(
            'register' => false,
            'error' => 'nameInvalid'
        );
    }
    
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    $con->close();
    exit;
?>
