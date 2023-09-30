<?php
session_start();

if (!isset($_SESSION["usuario"]) || !is_array($_SESSION["usuario"]) || $_SESSION["usuario"][1] != 1) {
    // Se não houver uma sessão válida ou o usuário não for um administrador, redirecione para o login.
    header("Location: ../index.php");
    exit();
}
// Conteúdo específico para administradores.
?>

<!-- admin_dashboard.php - Exibição da lógica -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/admin_dashboard.css"> 
    <title>Painel de Administração</title>
</head>
<body>
    <div id="menu">
        <ul>
            <li><a href="usuarios_cadastrados.php">LISTA DE USUÁRIOS</a></li>
            <li><a href="../logout.php" id="sair">SAIR</a></li> 
        </ul>
    </div>
    <div id="conteudo">
        <h1>Bem-vindo ao Painel de Administração</h1>
        <p>Aqui você pode gerenciar os usuários e realizar outras tarefas de administração.</p>
    </div>
</body>
</html>



