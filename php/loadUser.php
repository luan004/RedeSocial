<?php
    $user = $_POST['user'];
    
    /* BUSCAR AS INFORMAÇÕES DO USUARIO NO BANCO DE DADOS */
    $name = "Luan";
    $avatar = "https://mundoconectado.com.br/uploads/2022/05/25/25658/cacto.jpg";
    $banner = "https://i.pinimg.com/736x/e0/87/6d/e0876dbf66e40ab8fa55a38aee6ed671.jpg";

    $values = array(
        'name' => $name,
        'avatar' => $avatar,
        'banner' => $banner
    );

    header('Content-Type: application/json');
    if ($user == 'luan004') {
        echo json_encode($values);
    }
    exit;

?>
