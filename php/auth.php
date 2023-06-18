<?php
    function auth($token, $con) {
        $query = $con->query("SELECT user_id FROM sesstokens WHERE token = '$token';");

        if($query->num_rows > 0){
            $row = $query->fetch_array();
            $response = $row[0];
        } else {
            $response = null;
        }
        return $response;
    }
?>