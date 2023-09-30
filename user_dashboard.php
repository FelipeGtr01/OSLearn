<?php
    // Iniciar a sessão
    session_start();
    if (!isset($_SESSION['usuario'])) {
        // Se o usuário não está autenticado, será redirecionado para a página de login
        header('Location: ../modulos/logar.php');
        exit();
    }
?>

<!-- user_dashboard.php - Exibição da lógica do sistema destinado aos usuários comuns -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/user_dashboard.css"> 
    <title>Painel do Usuário</title>
</head>
<body>
    <div id="menu">
        <ul>
            <li><a href="user_dashboard.php">OSLearn</a></li>
            <li><a href="modulos/trilha.php">TRILHA DE ESTUDOS</a></li>
            <li><a href="modulos/ranking.php">RANKING</a></li>
            <li><a href="modulos/feedback.php">FEEDBACK</a></li>
            <li><a href="modulos/conta.php">CONTA</a></li>
            <li><a href="logout.php" id="sair">SAIR</a></li> 
        </ul>
    </div>
    <div id="conteudo">
        <!-- Conteúdo específico para usuários comuns -->
        <div id="boas-vindas">
            <h1>Bem Vindo à OSLearn!</h1>
            <p>Aqui você poderá aprender enquanto se diverte!</p>
            <p>Para utilizar a plataforma utilize os botões de navegação!</p>
            <p>Esperamos que tenha uma boa experiência de utilização. A, não se esqueça, que tal nos contar isso depois na parte dos feedbacks? Contamos com sua ajuda!</p>
            <p>Bons estudos!</p>
        </div>
    </div>
</body>
</html>
