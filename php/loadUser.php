<?php
    /* Realizar conexão ao banco de dados, pegar os valores e colocar num array */
    $var = Array(
        'attempts' =>   $objeto["descricao"],
        'estado' =>     $objeto["estado"],
        'utilizador' => $objeto["id_utilizador"]
    );

    header('Content-Type: application/json');
    echo json_encode($var);
    exit;
?>