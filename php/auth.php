<?php
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    
    $isValid = false;
    $userId = null;

    /* COMPARAR SENHA RECEBIDA COM SENHA DO BANCO (MD5) */
    if (true) {
        $isValid = true;
    }

    $values = array(
        'isValid' => $isValid
    );

    header('Content-Type: application/json');
    if ($user == 'luan004') {
        echo json_encode($values);
    }
    exit;

?>
