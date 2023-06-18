<?php
    function auth($token, $con, $user_or_id) {
        $stmt = $con->prepare("SELECT user_id FROM sesstokens WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id);
            $stmt->fetch();

            if ($user_or_id == 'id') {
                $response = $user_id;
            } elseif ($user_or_id == 'user') {
                $stmt = $con->prepare("SELECT user FROM users WHERE id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($user);
                $stmt->fetch();
                $response = $user;
            }
        } else {
            $response = null;
        }

        $stmt->close();
        return $response;
    }

    function getIdFromUser($user, $con) {
        $stmt = $con->prepare("SELECT id FROM users WHERE user = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id);
        $stmt->fetch();
        $stmt->close();
        return $id;
    }
?>