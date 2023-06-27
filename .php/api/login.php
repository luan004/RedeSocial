<?php
    require_once('../connection/Conn.php');
    require_once('../dao/UserDAO.php');
    require_once('../models/User.php');

    $user = $_POST['user'];
    $pass = $_POST['pass'];

    $conn = new Conn();
    $userDAO = new UserDAO($conn);
    $userObj = $userDAO->getUserByUsername($user);

    if ($userObj && $userObj->getPass() === $pass) {
        // Login bem-sucedido
        echo "Login realizado com sucesso. Bem-vindo, " . $userObj->getName() . "!";
    } else {
        // Credenciais inválidas
        echo "Usuário ou senha inválidos.";
    }

    $conn->close();
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
?>
