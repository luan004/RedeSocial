<?php
    define(
        'DB_SERVER',
        'localhost'
    );

    define(
        'DB_USERNAME',
        'root'
    );

    define(
        'DB_PASSWORD',
        ''
    );

    define(
        'DB_NAME',
        'tpwdb'
    );

    $con = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($con->connect_error) {
        die("Database connection failed: " . $con->connect_error);
    }
?>