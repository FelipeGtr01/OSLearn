<?php
    // Iniciando a sessão
    session_start();

    if (isset($_SESSION["usuario"]) && is_array($_SESSION["usuario"])) {
        require("../modulos/conexao.php");

        $conexaoClass = new Conexao();
        $conexao = $conexaoClass->conectar();

        $adm = $_SESSION["usuario"][1];
        $nome = $_SESSION["usuario"][0];
    } else {
        echo "<script>window.location = 'index.php'</script>";
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="stylesheet" href="../CSS/gerenciamento.css">
    <title>Painel de Administração de Perguntas</title>
</head>
<body>
    <div id="menu">
        <ul>
            <li><a href="admin_dashboard.php">OSLearn (ADM)💻</a></li>
            <li><a href="usuarios_cadastrados.php">LISTA DE USUÁRIOS 📄</a></li>
            <li><a href="gerenciar.php">GERENCIAR MÓDULOS 🔩</a></li>
            <li><a href="../logout.php" id="sair">SAIR 🔚</a></li> 
        </ul>
    </div>

    <div class="conteudo">
    <div class="header">
        <?php
            echo '<a href="../admin/gerenciar.php" class="menu-button">Voltar</a>';
        ?>
        <h2>Painel de administração das PERGUNTAS</h2>
    </div>
        <button id="inserir" class="green-button"><a href="inserirPerguntas.php">Inserir</a></button>
        <button id="editar" class="yellow-button"><a href="listaPerguntas.php">Editar</a></button>
        <button id="excluir" class="red-button"><a href="listaPerguntasExcluir.php">Excluir</a></button>
    </div>
</body>
</html>

